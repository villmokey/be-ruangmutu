<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;

use App\Service\Dashboard\DashboardService;

use Illuminate\Http\Request;

class DashboardController extends ApiController
{
    protected $dashboardService;

    public function __construct(
        DashboardService $dashboardService,
        Request $request)
    {
        $this->dashboardService    =   $dashboardService;
        parent::__construct($request);
        $this->middleware('auth:api', ['except' => ['index', 'show']]);
    }

    public function indicator(Request $request): \Illuminate\Http\JsonResponse
    {
        $input = $request;
        $result = $this->dashboardService->dashboard($input);

        try {
            if ($result->success) {
                return $this->sendSuccess($result->data, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (Exception $exception) {
            return $this->sendError($exception->getMessage(),"",500);
        }
    }

    public function cardlist(Request $request) {
        $result = $this->dashboardService->indicatorDataList($request);

        try {
            if ($result->success) {
                return $this->sendSuccess($result->data, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (Exception $exception) {
            return $this->sendError($exception->getMessage(),"",500);
        }
    }
}
