<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller as Controller;

class BaseController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($result, $message, $code = 200)
    {
    	$response = [
            'success' => true,
            'code'    => $code,
            'data'    => $result,
            'message' => $message,
        ];


        return response()->json($response, $code);
    }


    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($error, $errorMessages = [], $code = 404)
    {
    	$response = [
            'success' => false,
            'code'    => $code,
            'data'    => $errorMessages,
            'message' => $error,
        ];

        return response()->json($response, $code);
    }
}
