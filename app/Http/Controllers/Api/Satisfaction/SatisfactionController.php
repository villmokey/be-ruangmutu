<?php

namespace App\Http\Controllers\Api\Satisfaction;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SatisfactionLevel\CreateSatisfactionLevelRequest;
use App\Http\Requests\Api\Document\UpdateDocumentRequest;
use App\Service\Satisfaction\SatisfactionService;
use Illuminate\Http\Request;

class SatisfactionController extends ApiController
{
    protected $satisfactionService;

    public function __construct(
        SatisfactionService $satisfactionService,
        Request $request)
    {
        $this->satisfactionService    =   $satisfactionService;
        parent::__construct($request);
        $this->middleware('auth:api');
    }

    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $search         = $this->request->query('search', null);
        $page           = $this->request->query('page', null);
        $perPage        = $this->request->query('per_page', 15);
        $paginate       = $this->request->query('paginate', true);
        $sort           = $this->request->query('sort', 'DESC');
        $sortBy         = $this->request->query('sort_by', 'created_at');
        $program        = $this->request->query('programs', null);

        $programs = [];
        if($program !== null) {
            $explodeProgram = explode(',', $program);
            if(count($explodeProgram) > 0) {
                $programs = $explodeProgram;
            }
        }

        if ($paginate == 'true' || $paginate == '1') {
            $result = $this->satisfactionService->getPaginated($search, $programs, $perPage, $page, $sortBy, $sort);
        } else {
            $result = $this->satisfactionService->getAll($search, $program);
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

    public function store(CreateSatisfactionLevelRequest $request): \Illuminate\Http\JsonResponse
    {
        $input  =   $request->all();
        $result =   $this->satisfactionService->create($input);

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

    public function update($id, UpdateDocumentRequest $request): \Illuminate\Http\JsonResponse
    {
        $input  =   $request->all();
        $result =   $this->satisfactionService->update($id,$input);

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
        $result =   $this->satisfactionService->delete($id);
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
        $result = $this->satisfactionService->getById($id);

        try {
            if ($result->success) {
                return $this->sendSuccess($result->data, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (Exception $exception) {
            return $this->sendError($exception->getMessage(),"",500);
        }
    }

    public function chart(): \Illuminate\Http\JsonResponse
    {
        $result = $this->satisfactionService->chart();

        try {
            if ($result->success) {
                return $this->sendSuccess($result->data, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (Exception $exception) {
            return $this->sendError($exception->getMessage(),"",500);
        }
    }
    
    public function information(): \Illuminate\Http\JsonResponse
    {
        $result = $this->satisfactionService->info();

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
