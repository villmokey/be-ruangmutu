<?php


namespace App\Service;

use App\Service\AppService;
use App\Models\Entity\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Carbon\Carbon;

use setasign\Fpdi\Fpdi;


class FileUploadService extends AppService
{
    protected $disk;
    protected $model;
    protected $storage;

    private $realName;
    private $realExtension;
    private $realSize;
    private $mimeType;

    private $fileDirectory = null;
    private $fileName = null;
    private $filePath = null;

    private $typeName = null;
    private $groupName = null;
    private $thumbnailPath = [];


    /**
     * AppService constructor.
     * @param File $model
     * @param null $disk
     */
    public function __construct(File $model, $disk = null)
    {
        parent::__construct($model);

        $this->disk = !empty($disk) ? $disk : env('UPLOAD_STORAGE', 'public');
    }

    /**
     * @param UploadedFile $file
     * @param null $storageDisk
     * @return $this
     */
    public function handleImage(UploadedFile $file, $storageDisk = null)
    {
        $this->setStorageDisk($storageDisk);
        $this->processFile($file);

        // check if uploaded file is image
        if (in_array($this->storage, ['local', 'public', 'private','minio']) && substr($this->mimeType, 0, 5) == 'image') {
            $thumbDimensions = config('upload.image.thumb-dimension');
            foreach ($thumbDimensions as $width) {
                $thumbDestination = $this->fileDirectory . DIRECTORY_SEPARATOR . "{$width}_{$this->fileName}";
                $this->generateThumbnail($file->getRealPath(), $thumbDestination, $width);
                $this->thumbnailPath[] = "{$this->fileDirectory}/{$width}_{$this->fileName}";
            }
        }
        return $this;
    }

    public function handleFile(UploadedFile $file, $storageDisk = null)
    {
        $this->setStorageDisk($storageDisk);
        $this->processFile($file);

        return $this;
    }

    public function deleteFiles($filePath) : bool
    {
        // if $filePath is in array form
        if (is_array($filePath) && !empty($filePath)) {
            $files = array_map(function ($path) {
                if ($this->storage()->has($path)) {
                    return $path;
                }

                return null;
            }, $filePath);

            return $this->storage()->delete($files);
        }

        // if $filePath is a string
        if (is_string($filePath) && $this->storage()->has($filePath)) {
            return $this->storage()->delete($filePath);
        }

        return false;
    }

    public function saveToDb($group = null)
    {
        DB::beginTransaction();

        try {
            // get storage visibility
            $getVisibility = $this->storage()->getVisibility($this->filePath);

            $fileRecord = $this->model->newQuery()->create([
                'group'      => $group,
                'visibility' => $getVisibility,
                'real_name'  => $this->realName,
                'extension'  => $this->realExtension,
                'size'       => $this->realSize,
                'mime_type'  => $this->mimeType,
                'file_dir'   => $this->normalizeBackslash($this->fileDirectory),
                'file_name'  => $this->fileName,
                'file_path'  => $this->normalizeBackslash($this->filePath),
            ]);

            DB::commit();
            return $fileRecord;
        } catch (\Exception $exception) {
            DB::rollBack();

            return $exception->getMessage();
        }
    }

    public function toArray()
    {
        $getVisibility = $this->storage()->getVisibility($this->filePath);

        return [
            'visibility' => $getVisibility,
            'real_name'  => $this->realName,
            'extension'  => $this->realExtension,
            'size'       => $this->realSize,
            'mime_type'  => $this->mimeType,
            'file_dir'   => $this->normalizeBackslash($this->fileDirectory),
            'file_name'  => $this->fileName,
            'file_path'  => $this->normalizeBackslash($this->filePath),
            'thumbnails' => $this->thumbnailPath,
            'base_url'   => $this->storage()->url(null),
        ];
    }

    protected function storage()
    {
        return Storage::disk($this->disk);
    }

    protected function setStorageDisk($disk = null)
    {
        if (isset($disk)) {
            $this->disk = $disk;
        }
    }

    protected function processFile(UploadedFile $file)
    {
        $this->realName = $file->getClientOriginalName();
        $this->realExtension = $file->getClientOriginalExtension();
        $this->realSize = $file->getSize();
        $this->mimeType = $file->getMimeType();

        $this->fileDirectory  = $this->uploadDirectory('uploads');
        $this->fileName       = $this->generateNewName()  . ".{$this->realExtension}";
        $this->filePath       = $file->storeAs($this->fileDirectory, $this->fileName, ['disk' => $this->disk]);
    }
    
    public static function directProcessFile(UploadedFile $file)
    {
        $result = [
            'real_name'         => $file->getClientOriginalName(),
            'real_extension'    => $file->getClientOriginalExtension(),
            'real_size'         => $file->getSize(),
            'mime_type'         => $file->getMimeType(),
        ];

        $result['file_dir']        = "uploads/" . date('Y') . "/" . date('m');
        $result['file_name']       = Str::uuid()->toString()  . "." . $result['real_extension'];
        $result['file_path']       = $file->storeAs($result['file_dir'], $result['file_name'], ['disk' => env('UPLOAD_STORAGE', 'public')]);

        return $result;
    }

    public static function directSaveToDb($params)
    {
        DB::beginTransaction();

        try {
            // get storage visibility
            $getVisibility = Storage::disk($params['disk'])->getVisibility($params['file_path']);

            $fileRecord = File::create([
                'group'         => $params['group'],
                'visibility'    => $getVisibility,
                'real_name'     => $params['real_name'],
                'extension'     => $params['real_extension'],
                'fileable_id'   => $params['fileable_id'],
                'fileable_type' => $params['fileable_type'],
                'size'          => $params['real_size'],
                'mime_type'     => $params['mime_type'],
                'file_dir'      => $params['file_dir'],
                'file_name'     => $params['file_name'],
                'file_path'     => $params['file_path'],
            ]);

            DB::commit();
            return $fileRecord;
        } catch (\Exception $exception) {
            DB::rollBack();

            return $exception->getMessage();
        }
    }

    public static function generateNewName(string $prefix = null, string $suffix = null)
    {
        $prefix_ = (!empty($prefix)) ? trim("{$prefix}_") : null;
        $_suffix = (!empty($prefix)) ? trim("_{$suffix}") : null;

        return $prefix_ . Str::uuid()->toString() . $_suffix;
    }

    /**
     * Generate Thumbnail of specified size (width & height)
     *
     * @param string $sourceFilePath
     * @param string $destinationPath
     * @param int|null $width
     * @param int|null $height
     *
     * @see http://image.intervention.io/api/resize
     * @return void|mixed
     */
    protected function generateThumbnail(string $sourceFilePath, string $destinationPath = null, int $width = null, int $height = null)
    {
        $storeTo = $this->storage()->path($destinationPath);
        Image::make($sourceFilePath)->resize($width, $height, function ($constrain) {
            $constrain->aspectRatio();
        })->save($storeTo);
    }

    protected function uploadDirectory(string $dirPath = null)
    {
        return trim($dirPath, '/ ') . "/" . date('Y') . "/" . date('m');
    }

    /**
     * @param string $subject
     * @return string|string[]
     */
    private function normalizeBackslash(string $subject)
    {
        return str_replace('\\', '/', $subject);
    }
}
