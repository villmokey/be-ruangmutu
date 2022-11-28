<?php

namespace App\Http\Controllers\Api\Utils;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Service\FileUploadService;
use Illuminate\Http\Request;

use Illuminate\Http\UploadedFile;
use Illuminate\Http\File;
use Ramsey\Uuid\Uuid;

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
        $isBase64 = $request->input('is_base_64', false);

        if($isBase64 === true || $isBase64 === 'true') {
            $converted = $this->fromBase64($request->input('file'), Uuid::uuid4()->toString() . '.png');

            $upload = $this->fileUploadService
                ->handleFile($converted)
                ->saveToDb($request->input('group_name'));
        }else {
            $upload = $this->fileUploadService
                ->handleFile($request->file('file'))
                ->saveToDb($request->input('group_name'));
        }

        return $this->sendSuccess($upload);
    }

    public static function fromBase64(string $base64File, string $fname): UploadedFile
    {
        try {
            //code...
            // Get file data base64 string
            $fileData = base64_decode(\Arr::last(explode(',', $base64File)));
            
            // Create temp file and get its absolute path
            $tempFile = tmpfile();
            $tempFilePath = stream_get_meta_data($tempFile)['uri'];
            
            // Save file data in file
            file_put_contents($tempFilePath, $fileData);
            
            $tempFileObject = new File($tempFilePath);
            $file = new UploadedFile(
                $tempFileObject->getPathname(),
                $fname,
                $tempFileObject->getMimeType(),
                0,
                false // Mark it as test, since the file isn't from real HTTP POST.
            );
            
            // Close this file after response is sent.
            // Closing the file will cause to remove it from temp director!
            app()->terminating(function () use ($tempFile) {
                fclose($tempFile);
            });
            
            // return UploadedFile object
            return $file;
        } catch (\Exception $ex) {
            dd($ex);
        }
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $result =   $this->fileUploadService->delete($id);
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
}

