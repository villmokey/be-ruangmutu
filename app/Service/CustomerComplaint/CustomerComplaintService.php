<?php


namespace App\Service\CustomerComplaint;


use App\Models\Table\CustomerComplaintTable;
use App\Models\Table\SatisfactionDetailTable;
use App\Models\Table\IndicatorSignatureTable;
use App\Models\Table\DocumentTypeTable;
use App\Models\Table\FileTable;
use App\Models\Table\ProgramTable;
use App\Service\Document\DocumentService;
use App\Service\FileUploadService;

use App\Service\AppService;
use App\Service\AppServiceInterface;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\File;

class CustomerComplaintService extends AppService implements AppServiceInterface
{
    protected $docTypeTable;
    protected $fileTable;
    protected $programTable;
    protected $documentService;
    protected $uploadService;

    public function __construct(
        CustomerComplaintTable $model,
        ProgramTable $programTable,
        FileTable $fileTable,
        DocumentService $documentService,
        FileUploadService $uploadService,
        DocumentTypeTable $docTypeTable,
    )
    {
        $this->fileTable = $fileTable;
        $this->documentService = $documentService;
        $this->program = $programTable;
        $this->uploadService = $uploadService;
        $this->docTypeTable = $docTypeTable;
        parent::__construct($model);
    }

    public function getAll($search = null, $year = null)
    {
        $isNotAdmin = !\Auth::user()->hasAnyRole('admin');
        $result =   $this->model->newQuery()
                                ->with(['program', 'healthService', 'creator'])
                                ->when($search, function ($query, $search) {
                                    return $query->where('report','like','%'.$search.'%');
                                })
                                ->when($year, function ($query, $year) {
                                    return $query->whereYear('created_at', $year);
                                })
                                ->when($isNotAdmin, function ($query, $year) {
                                    return $query->whereYear('is_public', true);
                                })
                                ->get();

        return $this->sendSuccess($result);
    }

    public function getPaginated($search = null, $year = null, $perPage = 15, $page = null, $sort_by = 'created_at', $sort = 'desc', $type = null)
    {
        $isNotAdmin = !\Auth::user()->hasAnyRole('Super Admin');
        $result  = $this->model->newQuery()
                                ->with(['program', 'healthService', 'creator'])
                                ->when($search, function ($query, $search) {
                                    return $query->where('report','like','%'.$search.'%');
                                })
                                ->when($year, function ($query, $year) {
                                    return $query->whereYear('created_at', $year);
                                })
                                ->when($isNotAdmin, function ($query, $year) {
                                    return $query->where('is_public', true);
                                })
                                ->orderBy($sort_by, $sort)
                                ->paginate((int)$perPage, ['*'], null, $page);

        return $this->sendSuccess($result);
    }

    public function getById($id)
    {
        $result = $this->model->newQuery()
                                ->find($id);

        return $this->sendSuccess($result);
    }

    public function create($data)
    {
        \DB::beginTransaction();
        
        try {

            $satisfaction = $this->model->newQuery()->create([
                'health_service_id'         => $data['health_service_id'],
                'complaint_id'              => $this->model->generateCode(),
                'program_id'                => $data['program_id'],
                'report'                    => $data['report'],
                'source'                    => $data['source'],
                'reported_by'               => $data['reported_by'],
                'complaint_date'            => $data['complaint_date'],
                'note'                      => null,
                'status'                    => 'PENDING',
                'created_id'                => \Auth::user()->id,
            ]);

            \DB::commit(); // commit the changes
            return $this->sendSuccess($satisfaction);
        } catch (\Exception $exception) {
            \DB::rollBack(); // rollback the changes
            return $this->sendError(null, $this->debug ? $exception->getMessage() : null);
        }
    }

    public function updateInformation($id, $data)
    {
        $complaint  =  $this->model->newQuery()->find($id);

        \DB::beginTransaction();

        try {

            $complaint->coordination         =   $data['coordination'] ?? null;
            $complaint->follow_up            =   $data['follow_up'];
            $complaint->status               =   $data['status'];
            $complaint->clarification_date   =   date('Y-m-d');
            $complaint->is_public            =   $data['type'] === 'publish' ? true : false;
            $complaint->save();

            $this->saveToDocuments($complaint, null);

            \DB::commit(); // commit the changes
            return $this->sendSuccess($complaint);
        } catch (\Exception $exception) {
            \DB::rollBack(); // rollback the changes
            return $this->sendError(null, $this->debug ? $exception->getMessage() : null);
        }
    }

    public function update($id, $data)
    {
        return $this->sendError(null, 'Forbidden', 403);
    }

    public function delete($id)
    {
        return $this->sendError(null, 'Forbidden', 403);
    }

    public function saveToDocuments($data, $flowDiagram)
    {
        \DB::beginTransaction();

        try {

            $docType = $this->docTypeTable->newQuery()->where('name', 'Keluhan Pelanggan')->first();

            if(!$docType) {
                $docType = $this->docTypeTable->create([
                    'name' => 'Keluhan Pelanggan'
                ]);
            }

            $params = [
                'name'              =>  substr($data['report'], 0, 15),
                'slug'              =>  \Str::slug($data['name']),
                'document_type_id'  =>  $docType->id,
                'program_related'   =>  explode(',', $data['program_id']),
                'document_number'   =>  $data['complaint_id'],
                'publish_date'      =>  date('Y-m-d'),
                'is_confidential'   =>  'true',
            ];
    
            $doc = $this->documentService->backendCreate($params);

            $flowImage = '';

            if($flowDiagram) {
                $flowImage = base64_encode(\Storage::disk(env('UPLOAD_STORAGE', 'public'))->get($flowDiagram['file_path']));
            }

            try {
                $props['data'] = $data;
                $props['data']['flow_image'] = $flowImage;
                $props['data']['programs'] = $this->program->newQuery()->select(['name'])->whereIn('id', explode(',' ,$data['related_program']))->get();
           
            } catch (\Exception $x) {
                dd($x);
            }
            try {
                $qrCode = \QrCode::format('png')->size(60)->merge('/public/images/square_ruang_mutu.png', .3)->generate(env('FRONTEND_URL', 'http://localhost:3000') . '/view-file/doc/' . $doc->data->id);
                $props['qrcode'] = base64_encode($qrCode);
                // dd($props);
                $pdf = \PDF::loadView('print.complaint', $props);
                $file_name = $params['slug'].'.pdf';    
                $file = $pdf->output();
                $makeFile = $this->fromBase64(base64_encode($file), $file_name);
            } catch (\Exception $ex) {
                dd($ex);
            }

            $upload = $this->uploadService->handleFile($makeFile)->saveToDb('operational_standard');
           
            if($upload && $upload->id) {
                try {
                    $image = $this->fileTable->newQuery()->find($upload->id);
                    $image->update([
                        'fileable_type' => 'App\Models\Table\DocumentTable',
                        'fileable_id'   => $doc->data->id,
                    ]);

                    \DB::commit();
                    return true;
                } catch (\Exception $ex) {
                    \DB::rollback();
                    dd($ex);
                    return false;
                }
            }
        } catch (\Exception $th) {
            \DB::rollback();
            return false;
        }        
    }

    public static function fromBase64(string $base64File, string $fname): UploadedFile
    {
        try {
            //code...
            // Get file data base64 string
            $fileData = base64_decode(\Arr::last(explode(',', $base64File)));
            
            // Create temp file and get its absolute path
            $tempFile = tmpfile();
            $tempFilePath = stream_get_meta_data($tempFile)['uri'];
            
            // Save file data in file
            file_put_contents($tempFilePath, $fileData);
            
            $tempFileObject = new File($tempFilePath);
            $file = new UploadedFile(
                $tempFileObject->getPathname(),
                $fname,
                $tempFileObject->getMimeType(),
                0,
                false // Mark it as test, since the file isn't from real HTTP POST.
            );
            
            // Close this file after response is sent.
            // Closing the file will cause to remove it from temp director!
            app()->terminating(function () use ($tempFile) {
                fclose($tempFile);
            });
            
            // return UploadedFile object
            return $file;
        } catch (\Exception $ex) {
            dd($ex);
        }
    }
}
