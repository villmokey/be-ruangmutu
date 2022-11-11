<?php


namespace App\Service\Indicator;


use App\Models\Table\IndicatorTable;
use App\Models\Table\IndicatorSignatureTable;
use App\Models\Table\FileTable;
use App\Models\Table\DocumentTable;
use App\Models\Table\DocumentTypeTable;

use App\Service\AppService;
use App\Service\AppServiceInterface;
use App\Service\FileUploadService;

use Illuminate\Http\UploadedFile;
use Illuminate\Http\File;
use Illuminate\Database\Eloquent\Model;

class IndicatorService extends AppService implements AppServiceInterface
{
    protected $fileUploadService;
    protected $fileTable;
    protected $signatureTable;
    protected $documentTypeTable;
    protected $documentTable;

    public function __construct(
        FileUploadService $fileUploadService,
        FileTable $fileTable,
        IndicatorSignatureTable $signatureTable,
        DocumentTable $documentTable,
        DocumentTypeTable $documentTypeTable,
        IndicatorTable $model
    )
    {
        $this->fileUploadService    =   $fileUploadService;
        $this->fileTable            =   $fileTable;
        $this->documentTable        =   $documentTable;
        $this->documentTypeTable    =   $documentTypeTable;
        $this->signatureTable       =   $signatureTable;
        parent::__construct($model);
    }

    public function getAll($search = null, $year = null, $subProgram = null, $type = null)
    {
        $result =   $this->model->newQuery()
                                ->when($search, function ($query, $search) {
                                    return $query->where('title','like','%'.$search.'%');
                                })
                                ->when($year, function ($query, $year) {
                                    return $query->whereYear('created_at', $year);
                                })
                                ->when($subProgram, function ($query, $subProgram) {
                                    return $query->where('sub_program_id', $subProgram);
                                })
                                ->get();

        return $this->sendSuccess($result);
    }

    public function getPaginated($search = null, $year = null, $subProgram = null, $perPage = 15, $page = null, $type = null)
    {
        $result  = $this->model->newQuery()
                                ->when($search, function ($query, $search) {
                                    return $query->where('title','like','%'.$search.'%');
                                })
                                ->when($year, function ($query, $year) {
                                    return $query->whereYear('created_at', $year);
                                })
                                ->when($subProgram, function ($query, $subProgram) {
                                    return $query->where('sub_program_id', $subProgram);
                                })
                                ->orderBy('created_at','DESC')
                                ->paginate((int)$perPage, ['*'], null, $page);

        return $this->sendSuccess($result);
    }

    public function getById($id)
    {
        $result = $this->model->newQuery()
                                ->with('program')
                                ->with('file')
                                ->with('subProgram')
                                ->with('profileIndicator')
                                ->with('firstPic')
                                ->with('secondPic')
                                ->with('assignBy')
                                ->with('document')
                                ->with('signature')
                                ->find($id);

        return $this->sendSuccess($result);
    }

    public function create($data)
    {
        \DB::beginTransaction();

        try {

            $findDuplicate = $this->model->newQuery()->where('title', $data['title'])->where('month', $data['month'])->first();

            if(!$findDuplicate) {
                $indicator = $this->model->newQuery()->create([
                    'title'                     => $data['title'],
                    'program_id'                =>  $data['program_id'],
                    'sub_program_id'            =>  $data['sub_program_id'],
                    'month_target'              =>  $data['month_target'],
                    'month'                     =>  $data['month'],
                    'quality_goal'              =>  $data['quality_goal'],
                    'human'                     =>  $data['human'],
                    'tools'                     =>  $data['tools'],
                    'method'                    =>  $data['method'],
                    'policy'                    =>  $data['policy'],
                    'environment'               =>  $data['environment'],
                    'next_plan'                 =>  $data['next_plan'],
                    'first_pic_id'              =>  $data['first_pic_id'],
                    'second_pic_id'             =>  $data['second_pic_id'] ?? null,
                    'created_by'                =>  $data['created_by'],
                    'assign_by'                 =>  $data['assign_by'],
                    'type'                      =>  $data['type'],
                ]);
    
                foreach($data['signature'] as $signatures) {
                    $this->signatureTable->newQuery()->create([
                        'indicator_id'                 => $indicator->id,
                        'user_id'                      => $signatures['user_id'],
                        'level'                        => $signatures['level'],
                    ]);
                }
    
                if (!empty($data['document_id'])) {
                    $image = $this->fileTable->newQuery()->find($data['document_id']);
                    $image->update([
                        'fileable_type' => get_class($indicator),
                        'fileable_id'   => $indicator->id,
                    ]);
                }
    
                \DB::commit(); // commit the changes
                return $this->sendSuccess($indicator);
            }else {
                \DB::rollBack(); // rollback the changes
                return $this->sendError(null, 'Bulan yang dipilih telah dimasukan sebelumnya!', 400);
            }

        } catch (\Exception $exception) {
            \DB::rollBack(); // rollback the changes
            return $this->sendError(null, $this->debug ? $exception->getMessage() : null);
        }
    }

    public function update($id, $data)
    {
        $indicator                 =   $this->model->newQuery()->find($id);
        $oldImage                  =   $indicator->document()->first();

        \DB::beginTransaction();

        try {
            $indicator->program_id        =   $data['program_id'];
            $indicator->sub_program_id    =   $data['sub_program_id'];
            $indicator->month_target      =   $data['month_target'];
            $indicator->month             =   $data['month'];
            $indicator->quality_goal_id   =   $data['quality_goal_id'];
            $indicator->human             =   $data['human'];
            $indicator->tools             =   $data['tools'];
            $indicator->method            =   $data['method'];
            $indicator->policy            =   $data['policy'];
            $indicator->environment       =   $data['environment'];
            $indicator->next_plan         =   $data['next_plan'];
            $indicator->first_pic_id      =   $data['first_pic_id'];
            $indicator->second_pic_id     =   $data['second_pic_id'] ?? null;
            $indicator->created_by        =   $data['created_by'];
            $indicator->assign_by         =   $data['assign_by'];
            $indicator->save();

            if(!empty($data['signature'])) {
                $indicator->signature()->delete();
                foreach($data['signature'] as $signatures) {
                    $this->signatureTable->newQuery()->create([
                        'indicator_id'                 => $indicator->id,
                        'user_id'                      => $signatures['user_id'],
                        'level'                        => $signatures['level'],
                    ]);
                }
            }

            if (!empty($data['document_id'])) {
                if (!empty($oldImage)) {
                    $oldImage->delete();
                }
                $image = $this->fileTable->newQuery()->find($data['document_id']);
                $image->update([
                    'fileable_type' => get_class($indicator),
                    'fileable_id'   => $indicator->id,
                ]);
            }


            \DB::commit(); // commit the changes
            return $this->sendSuccess($indicator);
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

    public function getSignature($id, $input)
    {
        $program_id = $input->get('program_id', null);
        $year = $input->get('year', null);
        $status = $input->get('status', null);
        $type = $input->get('type', null);
        $search = $input->get('search', null);
        $paginate = $input->get('paginate', true);
        $page = $input->get('page', 1);
        $perPage = $input->get('limit', 10);
                                
        if ($paginate == 'true' || $paginate == '1') {
            $result = $this->model->newQuery()
                                    ->whereHas('signature', function($query) use ($id) {
                                        $query->where('user_id', $id);
                                    })
                                    ->with('profileIndicator')
                                    ->with('subProgram')
                                    ->with('signature')
                                    ->when($program_id, function ($query, $program_id) {
                                        return $query->whereIn('program_id', explode(',', $program_id));
                                    })
                                    ->when($search, function ($query) use($search) {
                                        $query->whereHas('profileIndicator', function ($q) use ($search) {
                                            $q->where('title', 'like', '%'.$search.'%');
                                        });
                                    })->when($status, function ($query, $status) {
                                        return $query->where('status', $status === 'signed' ? '>' : '=', 0);
                                    })
                                    ->when($type, function ($query, $type) {
                                        return $query->where('type', $type);
                                    })
                                    ->when($year, function ($query, $year) {
                                        return $query->whereYear('created_at', $year);
                                    })->paginate((int)$perPage, ['*'], null, $page);        
        }else {
            $result = $this->model->newQuery()
                                    ->whereHas('signature', function($query) use ($id) {
                                        $query->where('user_id', $id);
                                    })
                                    ->with('profileIndicator')
                                    ->with('subProgram')
                                    ->with('signature')
                                    ->when($program_id, function ($query, $program_id) {
                                        return $query->whereIn('program_id', explode(',', $program_id));
                                    })
                                    ->when($search, function ($query) use($search) {
                                        $query->whereHas('profileIndicator', function ($q) use ($search) {
                                            $q->where('title', 'like', '%'.$search.'%');
                                        });
                                    })->when($status, function ($query, $status) {
                                        return $query->where('status', $status === 'signed' ? '>' : '=', 0);
                                    })
                                    ->when($type, function ($query, $type) {
                                        return $query->where('type', $type);
                                    })
                                    ->when($year, function ($query, $year) {
                                        return $query->whereYear('created_at', $year);
                                    })->get();
        }

        return $this->sendSuccess($result);
    }

    public function changeStatus($id, $data)
    {
        $indicator   =   $this->model->newQuery()->find($id);

        \DB::beginTransaction();
        try {
            if (isset($data['status']) == 'rejected') {
                $indicator->update([
                    'status' => -1,
                ]);
            } else {
                $signature = $indicator->signature()->where('user_id', $data['user_id'])->first();
                $signature->update([
                    'signed' => 1,
                    'signed_at' => date('Y-m-d H:i:s'),
                ]);
                if ($indicator->signature()->where('signed', 0)->count() == 0) {
                    $indicator->update([
                        'status' => 3,
                    ]);
                }
            }
            \DB::commit(); // commit the changes
            return $this->sendSuccess($indicator);
        } catch (\Exception $exception) {
            \DB::rollBack(); // rollback the changes
            return $this->sendError(null, $this->debug ? $exception->getMessage() : null);
        }

        return $this->sendSuccess($indicator);
    }

    public function generatePDF($id, $chartFileId, $input) {
        if($id) {
            $indicator = $this->model->newQuery()->with(['program', 'subProgram'])->where('id', $id)->first();
            if($indicator) {
                try {
                    
                    $docType = $this->documentTypeTable->newQuery()->where('name', $indicator->type === 'quality' ? 'Indikator Mutu' : 'Indikator Kinerja')->first();

                    if(!$docType) {
                        $docType = $this->documentTypeTable->create([
                            'name' => $indicator->type === 'quality' ? 'Indikator Mutu' : 'Indikator Kinerja'
                        ]);
                    }

                    $nameOrigin = $indicator->profileIndicator->title . ' ' . $indicator->month . ' ' . date('Y', strtotime($indicator->created_at));

                    $document = $this->documentTable->newQuery()->create([
                        'name'              =>  $indicator->profileIndicator->title . '-' . $indicator->month,
                        'slug'              =>  \Str::slug($nameOrigin),
                        'document_type_id'  =>  $docType->id,
                        'document_number'   =>  null,
                        'publish_date'      =>  date('Y-m-d'),
                        'is_confidential'   =>  false,
                    ]);

                    $qrCode = \QrCode::format('png')->size(120)->merge('/public/images/square_ruang_mutu.png', .3)->errorCorrection('H')->generate(
                        config('app.frontend_url') . '/view-file/doc/' . $document->id);

                    $image = $this->fileTable->newQuery()->find($chartFileId);

                    $chartImage = "";

                    if($image) {
                        $chartImage = \base64_encode(\Storage::disk(env('UPLOAD_STORAGE', 'public'))->get($image['file_path']));
                    }

                    $props = [
                        'qr_image'      => \base64_encode($qrCode),
                        'chart_image'   => $chartImage,
                        'data'          => $indicator
                    ];
                    
                    $pdf = \PDF::loadView('print.indicator', $props);
                    $file = $pdf->output();
                    $file_name = \Str::slug($nameOrigin) . '.pdf';
                    $makeFile = $this->fromBase64(base64_encode($file), $file_name);

                    $upload = $this->fileUploadService->handleFile($makeFile)->saveToDb($indicator->type === 'quality' ? 'quality_indicator' : 'perfomance_indicator');

                    if($upload && $upload->id) {
                        try {
                            $image = $this->fileTable->newQuery()->find($upload->id);
                            $image->update([
                                'fileable_type' => 'App\Models\Table\DocumentTable',
                                'fileable_id'   => $document->id,
                            ]);
        
                            \DB::commit();
                            return $this->sendSuccess($upload, 'Sukses menyimpan ke lemari mutu');
                        } catch (\Exception $ex) {
                            \DB::rollback();
                            return $this->sendError(null, $ex->getMessage(), 400);
                        }
                    }

                } catch (\Exception $exception) {
                    return $this->sendError(null, $this->debug ? $exception->getMessage() : null);
                }
            }else {
                return $this->sendError('', 'Indicator not found', 400);
            }
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
