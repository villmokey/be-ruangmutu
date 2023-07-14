<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Send Response Success
     *
     * @param null $data
     * @param null $message
     * @param null $statusCode
     * @return  \Illuminate\Http\JsonResponse
     */
    protected function sendSuccess($data = null, $message = null, $statusCode = null)
    {
        $data = $this->responseWrapper($data)->success($message, $statusCode);

        return response()->header('Access-Control-Allow-Headers', '*')->header('Access-Control-Allow-Origin', 'origin')->header('Access-Control-Allow-Methods', '*')->json($data, $data->code);
    }

    /**
     * Send Response Error
     *
     * @param null $data
     * @param null $message
     * @param null $statusCode
     * @return  \Illuminate\Http\JsonResponse
     */
    protected function sendError($data = null, $message = null, $statusCode = null)
    {
        $data = $this->responseWrapper($data)->error($message, $statusCode);

        return response()->header('Access-Control-Allow-Headers', '*')->header('Access-Control-Allow-Origin', 'origin')->header('Access-Control-Allow-Methods', '*')->json($data, $data->code);
    }
}
