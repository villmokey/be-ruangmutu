<?php

namespace App\Http\Controllers\Api\Indicator;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Indicator\CreateIndicatorProfileRequest;
use App\Http\Requests\Api\Indicator\UpdateIndicatorProfileRequest;
use App\Service\Indicator\IndicatorProfileService;
use Illuminate\Http\Request;
use App\Service\FileUploadService;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class IndicatorProfileController extends ApiController
{
    protected $indicatorProfileService;
    protected $fileUploadService;

    public function __construct(
        IndicatorProfileService $indicatorProfileService,
        FileUploadService $fileUploadService,
        Request $request)
    {
        $this->indicatorProfileService    =   $indicatorProfileService;
        $this->fileUploadService    =   $fileUploadService;
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

        if ($paginate == 'true' || $paginate == '1') {
            $result = $this->indicatorProfileService->getPaginated($search, $year, $subProgram, $perPage, $page);
        } else {
            $result = $this->indicatorProfileService->getAll($search, $year, $subProgram);
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
              ]];

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

              $qrCode = QrCode::merge('https://www.seeklogo.net/wp-content/uploads/2016/09/facebook-icon-preview-1.png', .3, true)->size(60)->generate('Make me into a QrCode!');
            //   $qrCode = QrCode::format('png')->merge('https://www.seeklogo.net/wp-content/uploads/2016/09/facebook-icon-preview-1.png', .3, true)->size(200)->generate('http://www.simplesoftware.io');
              $result = $this->indicatorProfileService->getById($id);
            try {
                if ($result->success && $result->data) {
                    $props['data'] = $result->data;
                    $props['list_frequently'] = $listFrequently;
                    $props['list_dimension'] = $dimensiMutuOptions;
                    $props['list_indicator_options'] = $tipeIndikatorOptions;
                    $props['list_reports'] = $periodeWaktuPelaporanOptions;
                    $props['qr_image'] = base64_encode($qrCode);

                    $pdf = \PDF::loadView('print.profile-indicator', $props);
                    return $pdf->download($result->data->title . " (" . date('d M Y') . ')' . '.pdf');
                }else {
                    return $this->sendError(null, 'Invalid argument!', 400);
                }
    
            } catch (Exception $exception) {
                return $this->sendError($exception->getMessage(),"",500);
            }
        }
    } 
}
