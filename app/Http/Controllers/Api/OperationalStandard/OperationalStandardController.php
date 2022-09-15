<?php

namespace App\Http\Controllers\Api\OperationalStandard;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Api\OperationalStandard\CreateOperationalStandardRequest;
use App\Http\Requests\Api\OperationalStandard\UpdateOperationalStandardRequest;
use App\Service\OperationalStandard\OperationalStandardService;
use Illuminate\Http\Request;

class OperationalStandardController extends ApiController
{
    protected $opsService;

    public function __construct(
        OperationalStandardService $opsService,
        Request $request
    ) {
        $this->opsService    =   $opsService;
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
        $program        = $this->request->query('programs', null);

        $programs = [];
        if ($program !== null) {
            $explodeProgram = explode(',', $program);
            if (count($explodeProgram) > 0) {
                $programs = $explodeProgram;
            }
        }

        if ($paginate == 'true' || $paginate == '1') {
            $result = $this->opsService->getPaginated($search, $year, $programs, $perPage, $page);
        } else {
            $result = $this->opsService->getAll($search, $year, $program);
        }

        try {
            if ($result->success) {
                return $this->sendSuccess($result->data, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), "", 500);
        }
    }

    public function store(CreateOperationalStandardRequest $request): \Illuminate\Http\JsonResponse
    {
        $input  =   $request->all();
        $result =   $this->opsService->create($input);

        try {
            if ($result->success) {
                $response = $result->data;
                return $this->sendSuccess($response, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), "", 500);
        }
    }

    public function update($id, UpdateOperationalStandardRequest $request): \Illuminate\Http\JsonResponse
    {
        $input  =   $request->all();
        $result =   $this->opsService->update($id, $input);

        try {
            if ($result->success) {
                $response = $result->data;
                return $this->sendSuccess($response, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), "", 500);
        }
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $result =   $this->opsService->delete($id);
        try {
            if ($result->success) {
                $response = $result->data;
                return $this->sendSuccess($response, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), "", 500);
        }
    }

    public function show($id): \Illuminate\Http\JsonResponse
    {
        $result = $this->opsService->getById($id);

        try {
            if ($result->success) {
                return $this->sendSuccess($result->data, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), "", 500);
        }
    }
}
