<?php

namespace App\Http\Controllers\Api\QualityIndicator;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\QualityIndicator\CreateQualityIndicatorRequest;
use App\Http\Requests\Api\QualityIndicator\UpdateQualityIndicatorRequest;
use App\Service\QualityIndicator\QualityIndicatorService;
use Illuminate\Http\Request;

class QualityIndicatorController extends ApiController
{
    protected $qualityIndicatorService;

    public function __construct(
        QualityIndicatorService $qualityIndicatorService,
        Request $request)
    {
        $this->qualityIndicatorService    =   $qualityIndicatorService;
        parent::__construct($request);
        $this->middleware('auth:api', ['except' => ['index', 'show']]);
    }

    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $search         = $this->request->query('search', null);
        $page           = $this->request->query('page', null);
        $perPage        = $this->request->query('per_page', 15);
        $paginate       = $this->request->query('paginate', true);
        $year           = $this->request->query('year', null);

        if ($paginate == 'true' || $paginate == '1') {
            $result = $this->qualityIndicatorService->getPaginated($search, $year, $perPage, $page);
        } else {
            $result = $this->qualityIndicatorService->getAll($search, $year);
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

    public function store(CreateQualityIndicatorRequest $request): \Illuminate\Http\JsonResponse
    {
        $input  =   $request->all();
        $result =   $this->qualityIndicatorService->create($input);

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

    public function update($id, UpdateQualityIndicatorRequest $request): \Illuminate\Http\JsonResponse
    {
        $input  =   $request->all();
        $result =   $this->qualityIndicatorService->update($id,$input);

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
        $result =   $this->qualityIndicatorService->delete($id);
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
        $result = $this->qualityIndicatorService->getById($id);

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
            $result = $this->qualityIndicatorService->getPaginatedQualityGoal($search, $year, $perPage, $page);
        } else {
            $result = $this->qualityIndicatorService->getAllQualityGoal($search, $year);
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
}
