<?php

namespace App\Http\Controllers\Api\Master\ServiceUnit;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Modals\Entity\HealthService;
use App\Http\Requests\Api\Master\HealthService\CreateHealthServiceRequest;
use App\Http\Requests\Api\Master\User\UpdateUserRequest;
use App\Service\Master\ServiceUnit\ServiceUnitService;
use Illuminate\Http\Request;

class ServiceUnitController extends ApiController
{
    protected $serviceUnitservice;

    public function __construct(
        ServiceUnitService $serviceUnitservice,
        Request $request
    )
    {
        $this->serviceUnitservice    =   $serviceUnitservice;
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
            $result = $this->serviceUnitservice->getPaginated($search, $perPage, $page);
        } else {
            $result = $this->serviceUnitservice->getAll($search);
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

    public function store(CreateHealthServiceRequest $request): \Illuminate\Http\JsonResponse
    {
        $input  =   $request->all();
        $result =   $this->serviceUnitservice->create($input);

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

    public function update($id, UpdateUserRequest $request): \Illuminate\Http\JsonResponse
    {
        $input  =   $request->all();
        $result =   $this->serviceUnitservice->update($id,$input);

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
        $result =   $this->serviceUnitservice->delete($id);
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
        $result = $this->serviceUnitservice->getById($id);

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
