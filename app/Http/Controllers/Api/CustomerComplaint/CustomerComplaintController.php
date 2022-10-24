<?php

namespace App\Http\Controllers\Api\CustomerComplaint;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CustomerComplaint\CreateCustomerComplaintRequest;
use App\Http\Requests\Api\CustomerComplaint\UpdateCustomerComplaintRequest;
use App\Service\CustomerComplaint\CustomerComplaintService;
use Illuminate\Http\Request;

class CustomerComplaintController extends ApiController
{
    protected $complaintService;

    public function __construct(
        CustomerComplaintService $complaintService,
        Request $request)
    {
        $this->complaintService    =   $complaintService;
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
            $result = $this->complaintService->getPaginated($search, $programs, $perPage, $page, $sortBy, $sort);
        } else {
            $result = $this->complaintService->getAll($search, $program);
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

    public function store(UpdateCustomerComplaintRequest $request): \Illuminate\Http\JsonResponse
    {
        $input  =   $request->all();
        $result =   $this->complaintService->create($input);

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

    public function update($id, UpdateCustomerComplaintRequest $request): \Illuminate\Http\JsonResponse
    {
        $input  =   $request->all();
        $result =   $this->complaintService->update($id,$input);

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
    public function updateInfo($id, Request $request): \Illuminate\Http\JsonResponse
    {
        $input  =   $request->all();
        $result =   $this->complaintService->updateInformation($id, $input);

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
        $result =   $this->complaintService->delete($id);
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
        $result = $this->complaintService->getById($id);

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
