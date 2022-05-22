<?php

namespace App\Http\Controllers\Api\Utils;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Service\FileUploadService;
use Illuminate\Http\Request;

class FileUploadController extends ApiController
{
    protected $fileUploadService;

    /**
     * FileUploadController constructor.
     * @param Request $request
     * @param FileUploadService $fileUploadService
     */
    public function __construct(
        Request $request,
        FileUploadService $fileUploadService
    )
    {
        parent::__construct($request);
        $this->fileUploadService    = $fileUploadService;
    }

    public function uploadImage(Request $request): \Illuminate\Http\JsonResponse
    {
        $upload = $this->fileUploadService
            ->handleImage($request->file('file'))
            ->saveToDb($request->input('group_name'));

        return $this->sendSuccess($upload);
    }

    public function uploadFile(Request $request): \Illuminate\Http\JsonResponse
    {
        $upload = $this->fileUploadService
            ->handleFile($request->file('file'))
            ->saveToDb($request->input('group_name'));

        return $this->sendSuccess($upload);
    }
}
