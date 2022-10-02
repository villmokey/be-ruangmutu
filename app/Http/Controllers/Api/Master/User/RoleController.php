<?php

namespace App\Http\Controllers\Api\Master\User;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Master\User\CreateUserRequest;
use App\Http\Requests\Api\Master\User\UpdateUserRequest;
use App\Service\Master\Role\RoleService;
use Illuminate\Http\Request;

class RoleController extends ApiController
{
    protected $roleService;

    public function __construct(
        RoleService $roleService,
        Request $request)
    {
        $this->roleService    =   $roleService;
        parent::__construct($request);
        $this->middleware('auth:api');
    }

    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $search         = $this->request->query('search', null);
        $page           = $this->request->query('page', null);
        $perPage        = $this->request->query('per_page', 15);
        $paginate       = $this->request->query('paginate', true);

        if ($paginate == 'true' || $paginate == '1') {
            $result = $this->roleService->getPaginated($search, $perPage, $page);
        } else {
            $result = $this->roleService->getAll($search);
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
