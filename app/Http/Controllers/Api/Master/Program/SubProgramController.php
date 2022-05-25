<?php

namespace App\Http\Controllers\Api\Master\Program;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Master\Program\CreateSubProgramRequest;
use App\Http\Requests\Api\Master\Program\UpdateSubProgramRequest;
use App\Service\Master\Program\SubProgramService;
use Illuminate\Http\Request;

class SubProgramController extends ApiController
{
    protected $subProgramService;

    public function __construct(
        SubProgramService $subProgramService,
        Request $request)
    {
        $this->subProgramService    =   $subProgramService;
        parent::__construct($request);
        $this->middleware('auth:api', ['except' => ['index', 'show']]);
    }

    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $search         = $this->request->query('search', null);
        $page           = $this->request->query('page', null);
        $perPage        = $this->request->query('per_page', 15);
        $paginate       = $this->request->query('paginate', true);

        if ($paginate == 'true' || $paginate == '1') {
            $result = $this->subProgramService->getPaginated($search, $perPage, $page);
        } else {
            $result = $this->subProgramService->getAll($search);
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

    public function store(CreateSubProgramRequest $request): \Illuminate\Http\JsonResponse
    {
        $input  =   $request->all();
        $result =   $this->subProgramService->create($input);

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

    public function update($id, UpdateSubProgramRequest $request): \Illuminate\Http\JsonResponse
    {
        $input  =   $request->all();
        $result =   $this->subProgramService->update($id,$input);

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
        $result =   $this->subProgramService->delete($id);
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
        $result = $this->subProgramService->getById($id);

        try {
            if ($result->success) {
                return $this->sendSuccess($result->data, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (Exception $exception) {
            return $this->sendError($exception->getMessage(),"",500);
        }
    }

    public function updatePublish($id): \Illuminate\Http\JsonResponse
    {
        $result =   $this->subProgramService->updatePublish($id);

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
}
