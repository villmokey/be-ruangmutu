<?php


namespace App\Traits;


trait HttpResponseTrait
{
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

        return (object) [
            'code'      => $code,
            'success'   => true,
            'message'   => $message,
            'data'      => $data
        ];
    }

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

        return (object) [
            'code'      => $code,
            'success'   => false,
            'message'   => $message,
            'data'      => $data
        ];
    }
}
