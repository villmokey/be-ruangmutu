<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait HttpResponseTrait
{
    /**
     * It returns a json response with the data and the status code.
     *
     * @param data The data you want to return.
     */
    public function resSuccess($data = null)
    {
        $statusCode = null;
        $message = (empty($message)) ? 'success' : $message;

        // check if message constructed in array format (multiple message)
        if (is_array($message)) {
            $extract = array_values($message);
            $message = $extract[0];
        }

        // set http status code
        $code = (empty($statusCode) || !is_numeric($statusCode)) ?
            http_response_code() :
            $statusCode;

        return response()->json([
            'code'      => $code,
            'success'   => true,
            'message'   => $message,
            'data'      => $data
        ], $code);
    }

    /**
     * It returns a json response with a status code, success, message and data.
     *
     * @param data The data you want to return.
     * @param message The message you want to display.
     * @param statusCode The HTTP status code to be returned.
     */
    public function responseError($data = null, $message = null, $statusCode = 500)
    {
        $message = (empty($message)) ? 'not success' : $message;

        // check if message constructed in array format (multiple message)
        if (is_array($message)) {
            $extract = array_values($message);
            $message = $extract[0];
        }

        // set http status code
        $code = (empty($statusCode) || !is_numeric($statusCode)) ?
            http_response_code() :
            $statusCode;

        return response()->json([
            'code'      => $code,
            'success'   => true,
            'message'   => $message,
            'data'      => $data
        ], $code);
    }

}
