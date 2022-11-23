<?php

namespace App\Http\Controllers\Api\Indicator;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Indicator\CreateIndicatorProfileRequest;
use App\Http\Requests\Api\Indicator\UpdateIndicatorProfileRequest;
use App\Service\Indicator\IndicatorProfileService;
use Illuminate\Http\Request;
use App\Service\FileUploadService;
use App\Service\Document\DocumentService;
use App\Models\Table\DocumentTable;
use App\Models\Table\IndicatorProfileTable;
use App\Models\Table\FileTable;
use App\Models\Table\DocumentTypeTable;

use Illuminate\Http\UploadedFile;
use Illuminate\Http\File;

class IndicatorProfileController extends ApiController
{
    protected $indicatorProfileService;
    protected $documentService;
    protected $fileUploadService;
    protected $documentTable;
    protected $fileTable;
    protected $documentTypeTable;

    public function __construct(
        IndicatorProfileService $indicatorProfileService,
        FileUploadService $fileUploadService,
        DocumentTable $documentTable,
        FileTable $fileTable,
        DocumentTypeTable $documentTypeTable,
        DocumentService $documentService,
        Request $request)
    {
        $this->indicatorProfileService    =   $indicatorProfileService;
        $this->documentService      =   $documentService;
        $this->fileUploadService    =   $fileUploadService;
        $this->documentTable        =   $documentTable;
        $this->fileTable            =   $fileTable;
        $this->documentTypeTable    =   $documentTypeTable;

        parent::__construct($request);
        $this->middleware('auth:api', ['except' => ['index', 'show', 'generateProfileIndicator']]);
    }

    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $search         = $this->request->query('search', null);
        $page           = $this->request->query('page', null);
        $perPage        = $this->request->query('per_page', 15);
        $paginate       = $this->request->query('paginate', true);
        $year           = $this->request->query('year', null);
        $subProgram     = $this->request->query('sub_program', null);
        $type           = $this->request->query('type', 'quality');

        if ($paginate == 'true' || $paginate == '1') {
            $result = $this->indicatorProfileService->getPaginated($search, $year, $subProgram, $perPage, $page, $type);
        } else {
            $result = $this->indicatorProfileService->getAll($search, $year, $subProgram, $type);
        }

        try {
            if ($result->success) {
                return $this->sendSuccess($result->data, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (Exception $exception) {
            return $this->sendError($exception->getMessage(),"",500);
        }
    }

    public function store(CreateIndicatorProfileRequest $request): \Illuminate\Http\JsonResponse
    {
        $input  =   $request->all();
        $result =   $this->indicatorProfileService->create($input);

        try {
            if ($result->success) {
                $response = $result->data;
                return $this->sendSuccess($response, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(),"",500);
        }
    }

    public function update($id, UpdateIndicatorProfileRequest $request): \Illuminate\Http\JsonResponse
    {
        $input  =   $request->all();
        $result =   $this->indicatorProfileService->update($id,$input);

        try {
            if ($result->success) {
                $response = $result->data;
                return $this->sendSuccess($response, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(),"",500);
        }
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $result =   $this->indicatorProfileService->delete($id);
        try {
            if ($result->success) {
                $response = $result->data;
                return $this->sendSuccess($response, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(),"",500);
        }
    }

    public function show($id): \Illuminate\Http\JsonResponse
    {
        $result = $this->indicatorProfileService->getById($id);

        try {
            if ($result->success) {
                return $this->sendSuccess($result->data, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (Exception $exception) {
            return $this->sendError($exception->getMessage(),"",500);
        }
    }

    public function qualityGoal(Request $request): \Illuminate\Http\JsonResponse
    {
        $search         = $this->request->query('search', null);
        $page           = $this->request->query('page', null);
        $perPage        = $this->request->query('per_page', 15);
        $paginate       = $this->request->query('paginate', true);
        $year           = $this->request->query('year', null);

        if ($paginate == 'true' || $paginate == '1') {
            $result = $this->indicatorProfileService->getPaginatedQualityGoal($search, $year, $perPage, $page);
        } else {
            $result = $this->indicatorProfileService->getAllQualityGoal($search, $year);
        }

        try {
            if ($result->success) {
                return $this->sendSuccess($result->data, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (Exception $exception) {
            return $this->sendError($exception->getMessage(),"",500);
        }
    }

    public function getSignature($id, Request $request): \Illuminate\Http\JsonResponse
    {
        $result = $this->indicatorProfileService->getSignature($id, $request);

        try {
            if ($result->success) {
                return $this->sendSuccess($result->data, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (Exception $exception) {
            return $this->sendError($exception->getMessage(),"",500);
        }
    }
    
    public function getChartDataById($id) : \Illuminate\Http\JsonResponse 
    {
        $result = $this->indicatorProfileService->getChartDataById($id);
        
        try {
            if ($result->success) {
                return $this->sendSuccess($result->data, $result->message, $result->code);
            }
    
            return $this->sendError($result->data, $result->message, $result->code);
        } catch (Exception $exception) {
            return $this->sendError($exception->getMessage(),"",500);
        }


    }

    public function getApprovalInformation(): \Illuminate\Http\JsonResponse
    {
        $result = $this->indicatorProfileService->getApprovalInformation();

        try {
            if ($result->success) {
                return $this->sendSuccess($result->data, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (Exception $exception) {
            return $this->sendError($exception->getMessage(),"",500);
        }
    }

    

    public function changeStatus($id, Request $request): \Illuminate\Http\JsonResponse
    {
        $input  =   $request->all();
        $result =   $this->indicatorProfileService->changeStatus($id,$input);

        try {
            if ($result->success) {
                $response = $result->data;
                return $this->sendSuccess($response, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(),"",500);
        }
    }

    public function generateProfileIndicator($id) {
        if($id) {

            $listFrequently = [
              [
                'label'=> 'Harian',
                'value'=> 'Harian',
              ],
              [
                'label'=> 'Mingguan',
                'value'=> 'Mingguan',
              ],
              [
                'label'=> 'Bulan',
                'value'=> 'Bulan',
              ],
              [
                'label'=> 'Tahunan',
                'value'=> 'Tahunan',
              ]
            ];
            $dimensiMutuOptions = [
            [
                'label' => 'Kelayakan',
                'value' => 'Kelayakan',
            ],
            [
                'label' => 'Ketepatan Waktu',
                'value' => 'Ketepatan Waktu',
            ],
            [
                'label' => 'Manfaat',
                'value' => 'Manfaat',
            ],
            [
                'label' => 'Ketersiadaan',
                'value' => 'Ketersiadaan',
            ],
            [
                'label' => 'Keselamatan',
                'value' => 'Keselamatan',
            ],
            [
                'label' => 'Efisiensi',
                'value' => 'Efisiensi',
            ],
            [
                'label' => 'Efektivias',
                'value' => 'Efektivias',
            ],
            [
                'label' => 'Kesinambungan',
                'value' => 'Kesinambungan',
            ]
            ];
            $tipeIndikatorOptions = [
                [
                'label'=> 'Input',
                'value'=> 'Input',
                ],
                [
                'label'=> 'Proses',
                'value'=> 'Proses',
                ],
                [
                'label'=> 'Output',
                'value'=> 'Output',
                ],
                [
                'label'=> 'Outcome',
                'value'=> 'Outcome',
                ]
            ];

            $periodeWaktuPelaporanOptions = [
                [
                    'label'=> 'Bulanan',
                    'value'=> 'Bulanan',
                ],
                [
                    'label'=> 'Triwulan',
                    'value'=> 'Triwulan',
                ],
                [
                    'label'=> 'Semester',
                    'value'=> 'Semester',
                ],
                [
                    'label'=> 'Tahunan',
                    'value'=> 'Tahunan',
                ]
            ];
        
            $result = $this->indicatorProfileService->getById($id);
            
            try {
                \DB::beginTransaction();
                if ($result->success && $result->data) {

                    $profile = $result->data;

                    $docType = $this->documentTypeTable->newQuery()->where('name', $profile->type === 'quality' ? 'Profil Indikator Mutu' : 'Profile Indikator Kinerja')->first();

                    if(!$docType) {
                        $docType = $this->documentTypeTable->create([
                            'name' => $profile->type === 'quality' ? 'Profil Indikator Mutu' : 'Profile Indikator Kinerja'
                        ]);
                    }

                    $params = [
                        'name'              =>  $profile->title,
                        'slug'              =>  \Str::slug($profile->title),
                        'document_type_id'  =>  $docType->id,
                        'document_number'   =>  null,
                        'publish_date'      =>  date('Y-m-d'),
                        'is_confidential'   =>  false,
                        'program_related'   =>  [$profile->program_id, $profile->sub_program_id],
                    ];
            
                    $doc = $this->documentService->backendCreate($params);

                    $document = $doc->data;

                    $qrCode = \QrCode::format('png')->size(120)->merge('/public/images/square_ruang_mutu.png', .3)->errorCorrection('H')->generate(
                        config('app.frontend_url') . '/view-file/doc/' . $document->id);


                    $props['data'] = $result->data;
                    $props['list_frequently'] = $listFrequently;
                    $props['list_dimension'] = $dimensiMutuOptions;
                    $props['list_indicator_options'] = $tipeIndikatorOptions;
                    $props['list_reports'] = $periodeWaktuPelaporanOptions;
                    $props['qr_image'] = base64_encode($qrCode);

                    $pdf = \PDF::loadView('print.profile-indicator', $props);
                    $file = $pdf->output();
                    $file_name = \Str::slug($profile->title) . '.pdf';
                    $makeFile = $this->fromBase64(base64_encode($file), $file_name);

                    $upload = $this->fileUploadService->handleFile($makeFile)->saveToDb($profile->type === 'quality' ? 'quality_profile_indicator' : 'perfomance_profile_indicator');

                    if($upload && $upload->id) {
                        try {
                            $image = $this->fileTable->newQuery()->find($upload->id);
                            $image->update([
                                'fileable_type' => 'App\Models\Table\DocumentTable',
                                'fileable_id'   => $document->id,
                            ]);

                            $find = IndicatorProfileTable::find($id);

                            if($find) {
                                $find->is_generated = true;
                                $find->save();
                            }
        
                            \DB::commit();
                            return $this->sendSuccess($upload, 'Sukses menyimpan ke lemari mutu');
                        } catch (\Exception $ex) {
                            \DB::rollback();
                            return $this->sendError(null, $ex->getMessage(), 400);
                        }
                    }
                }else {
                    \DB::rollback();
                    return $this->sendError(null, 'Invalid argument!', 400);
                }
    
            } catch (Exception $exception) {
                return $this->sendError($exception->getMessage(),"",500);
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
