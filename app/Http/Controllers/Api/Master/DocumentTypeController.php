<?php

namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Master\Document\CreateDocumentRequest;
use App\Http\Requests\Api\Master\Document\UpdateDocumentRequest;
use App\Service\Master\Document\DocumentTypeService;
use Illuminate\Http\Request;

class DocumentTypeController extends ApiController
{
    protected $documentTypeService;

    public function __construct(
        DocumentTypeService $documentTypeService,
        Request $request)
    {
        $this->documentTypeService    =   $documentTypeService;
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
            $result = $this->documentTypeService->getPaginated($search, $perPage, $page);
        } else {
            $result = $this->documentTypeService->getAll($search);
        }

        try {
            if ($result->success) {
                return $this->sendSuccess($result->data, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (Exception $exception) {
            $this->sendError($exception->getMessage(),"",500);
        }
    }

    public function store(CreateDocumentRequest $request): \Illuminate\Http\JsonResponse
    {
        $input  =   $request->all();
        $result =   $this->documentTypeService->create($input);

        try {
            if ($result->success) {
                $response = $result->data;
                return $this->sendSuccess($response, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (\Exception $exception) {
            $this->sendError($exception->getMessage(),"",500);
        }
    }

    public function update($id, UpdateDocumentRequest $request): \Illuminate\Http\JsonResponse
    {
        $input  =   $request->all();
        $result =   $this->documentTypeService->update($id,$input);

        try {
            if ($result->success) {
                $response = $result->data;
                return $this->sendSuccess($response, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (\Exception $exception) {
            $this->sendError($exception->getMessage(),"",500);
        }
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $result =   $this->documentTypeService->delete($id);
        try {
            if ($result->success) {
                $response = $result->data;
                return $this->sendSuccess($response, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (\Exception $exception) {
            $this->sendError($exception->getMessage(),"",500);
        }
    }

    public function show($id): \Illuminate\Http\JsonResponse
    {
        $result = $this->documentTypeService->getById($id);

        try {
            if ($result->success) {
                return $this->sendSuccess($result->data, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (Exception $exception) {
            $this->sendError($exception->getMessage(),"",500);
        }
    }

    public function updatePublish($id): \Illuminate\Http\JsonResponse
    {
        $result =   $this->documentTypeService->updatePublish($id);

        try {
            if ($result->success) {
                $response = $result->data;
                return $this->sendSuccess($response, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (\Exception $exception) {
            $this->sendError($exception->getMessage(),"",500);
        }
    }
}
