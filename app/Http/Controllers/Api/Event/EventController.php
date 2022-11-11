<?php

namespace App\Http\Controllers\Api\Event;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Event\CreateEventRequest;
use App\Http\Requests\Api\Event\UpdateEventRequest;
use App\Service\Event\EventService;
use Illuminate\Http\Request;

class EventController extends ApiController
{
    protected $eventService;

    public function __construct(
        EventService $eventService,
        Request $request
    ) {
        $this->eventService    =   $eventService;
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
        $month          = $this->request->query('month', null);
        $program        = $this->request->query('program_id', null);

        $programs = [];
        if($program !== null) {
            $explodeProgram = explode(',', $program);
            if(count($explodeProgram) > 0) {
                $programs = $explodeProgram;
            }
        }

        if ($paginate == 'true' || $paginate == '1') {
            $result = $this->eventService->getPaginated($search, $year, $perPage, $page, $programs);
        } else {
            $result = $this->eventService->getAll($search, $year, $month, $programs);
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

    public function store(CreateEventRequest $request): \Illuminate\Http\JsonResponse
    {
        $input  =   $request->all();
        $result =   $this->eventService->create($input);
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

    public function update($id, UpdateEventRequest $request): \Illuminate\Http\JsonResponse
    {
        $input  =   $request->all();
        $result =   $this->eventService->update($id, $input);

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
        $result =   $this->eventService->delete($id);
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
        $result = $this->eventService->getById($id);

        try {
            if ($result->success) {
                return $this->sendSuccess($result->data, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), "", 500);
        }
    }

    public function realized($id): \Illuminate\Http\JsonResponse
    {
        $result = $this->eventService->makeRealized($id);

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
