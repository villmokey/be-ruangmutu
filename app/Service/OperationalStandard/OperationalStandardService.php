<?php


namespace App\Service\OperationalStandard;


use App\Models\Table\OperationalStandardTable;
use App\Models\Table\OperationalStandardUnitTable;
use App\Models\Table\OperationalStandardHistoryTable;
use App\Models\Table\DocumentTypeTable;
use App\Models\Table\ProgramTable;
use App\Models\Table\FileTable;
use App\Service\Document\DocumentService;
use App\Service\FileUploadService;

use App\Service\AppService;
use App\Service\AppServiceInterface;

use Illuminate\Http\UploadedFile;
use Illuminate\Http\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class OperationalStandardService extends AppService implements AppServiceInterface
{
    protected $opsUnit;
    protected $opsHistory;
    protected $fileTable;
    protected $program;
    protected $documentService;
    protected $uploadService;
    protected $docTypeTable;

    public function __construct(
        OperationalStandardTable $model,
        OperationalStandardUnitTable $opsUnit,
        OperationalStandardHistoryTable $opsHistory,
        ProgramTable $program,
        FileTable $fileTable,
        DocumentService $documentService,
        FileUploadService $uploadService,
        DocumentTypeTable $docTypeTable,
    ) {
        $this->opsUnit = $opsUnit;
        $this->opsHistory = $opsHistory;
        $this->fileTable = $fileTable;
        $this->documentService = $documentService;
        $this->program = $program;
        $this->uploadService = $uploadService;
        $this->docTypeTable = $docTypeTable;
        parent::__construct($model);
    }

    public function getAll($search = null, $year = null)
    {
        $result =   $this->model->newQuery()->with(['flowDiagramUrl'])
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', '%' . $search . '%');
            })
            ->when($year, function ($query, $year) {
                return $query->whereYear('created_at', $year);
            });


        $result->orderBy('created_at', 'DESC');

        return $this->sendSuccess($result->get());
    }

    public function getPaginated($search = null, $year = null, $perPage = 15, $page = null)
    {
        $result  = $this->model->newQuery()
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', '%' . $search . '%');
            })
            ->when($year, function ($query, $year) {
                return $query->whereYear('created_at', $year);
            })
            ->orderBy('created_at', 'DESC')
            ->paginate((int)$perPage, ['*'], null, $page);

        return $this->sendSuccess($result);
    }

    public function getById($id)
    {
        $result = $this->model->newQuery()
            ->with('relatedFile.related.file')
            ->find($id);

        return $this->sendSuccess($result);
    }

    public function create($data)
    {
        \DB::beginTransaction();

        try {

            $document = $this->model->newQuery()->create([
                'name'              => $data['name'],
                'document_number'   => $data['document_number'],
                'revision_number'   => $data['revision_number'],
                'released_date'     => $data['released_date'],
                'page'              => $data['page'],
                'total_page'        => $data['total_page'],
                'meaning'           => $data['meaning'],
                'goal'              => $data['goal'],
                'policy'            => $data['policy'],
                'reference'         => $data['reference'],
                'tools'             => $data['tools'],
                'procedures'        => $data['procedures'],
                'flow_diagram'      => $data['flow_diagram'],
                'related_program'   => $data['related_program'],
                'created_id'        =>  \Auth::user()->id
            ]);

            if (isset($data['related_program'])) {
                if (!empty($data['related_program'])) {
                    foreach (explode(',', $data['related_program']) as $prog) {
                        $this->opsUnit->newQuery()->create([
                            'operational_standard_id'     => $document->id,
                            'program_id'                  => $prog
                        ]);
                    }
                }
            }

            if (isset($data['histories'])) {
                if (!empty($data['histories']) && count($data['histories'])) {
                    foreach ($data['histories'] as $hist) {
                        $this->opsHistory->newQuery()->create([
                            'operational_standard_id'     => $document->id,
                            'name'                        => $hist['name'],
                            'value'                       => $hist['value'],
                            'publish'                     => $hist['publish'],
                        ]);
                    }
                }
            }

            $image = $this->fileTable->newQuery()->find($data['flow_diagram']);
            if($image) {
                $image->update([
                    'fileable_type' => get_class($document),
                    'fileable_id'   => $document->id,
                ]);
            }

            if($this->saveToDocuments($data, $image)){
                \DB::commit(); // commit the changes
                return $this->sendSuccess($document);
            }else {
                \DB::rollBack(); // rollback the changes
                return $this->sendError(null, 'Failed save to documents');
            }
        } catch (\Exception $exception) {
            \DB::rollBack(); // rollback the changes
            return $this->sendError(null, $this->debug ? $exception->getMessage() : null);
        }
    }

    public function update($id, $data)
    {
        $document   =   $this->model->newQuery()->find($id);

        \DB::beginTransaction();

        try {

            $document->name          =   $data['name'];
            $document->slug          =   Str::slug($data['name']);
            $document->start_date    =   $data['start_date'];
            $document->end_date      =   $data['end_date'];
            $document->save();

            \DB::commit(); // commit the changes
            return $this->sendSuccess($document);
        } catch (\Exception $exception) {
            \DB::rollBack(); // rollback the changes
            return $this->sendError(null, $this->debug ? $exception->getMessage() : null);
        }
    }

    public function delete($id)
    {
        $read   =   $this->model->newQuery()->find($id);
        try {
            $read->delete();
            \DB::commit(); // commit the changes
            return $this->sendSuccess($read);
        } catch (\Exception $exception) {
            \DB::rollBack(); // rollback the changes
            return $this->sendError(null, $this->debug ? $exception->getMessage() : null);
        }
    }

    public function saveToDocuments($data, $flowDiagram)
    {
        \DB::beginTransaction();

        try {

            $docType = $this->docTypeTable->newQuery()->where('name', 'SOP')->first();

            if(!$docType) {
                $docType = $this->docTypeTable->create([
                    'name' => 'SOP'
                ]);
            }

            $params = [
                'name'              =>  $data['name'],
                'slug'              =>  Str::slug($data['name']),
                'document_type_id'  =>  $docType->id,
                'document_number'   =>  $data['document_number'],
                'publish_date'      =>  $data['released_date'],
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
                $qrCode = \QrCode::format('png')->size(60)->merge('/public/images/square_ruang_mutu.png', .3)->generate(env('FRONTEND_URL', 'http://localhost:3000') . '/view-file/sop/' . $doc->data->id);
                $props['qrcode'] = base64_encode($qrCode);
                
                $pdf = \PDF::loadView('print.sop', $props);
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
