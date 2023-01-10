<?php


namespace App\Service\Document;


use App\Models\Table\FileTable;
use App\Models\Table\DocumentTable;
use App\Models\Table\DocumentRelatedTable;
use App\Models\Table\DocumentProgramTable;

use App\Service\AppService;
use App\Service\FileUploadService;
use App\Service\AppServiceInterface;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

use setasign\Fpdi\Fpdi;

class DocumentService extends AppService implements AppServiceInterface
{
    protected $fileTable;
    protected $documentRelated;
    protected $programRelated;

    public function __construct(
        DocumentTable $model,
        FileTable $fileTable,
        DocumentRelatedTable $documentRelated,
        DocumentProgramTable $programRelated
    )
    {
        $this->fileTable = $fileTable;
        $this->documentRelated = $documentRelated;
        $this->programRelated = $programRelated;
        parent::__construct($model);
    }

    public function getAll($search = null,$year = null, $type = null, $program = null)
    {
        $result =   $this->model->newQuery()
                                ->when($search, function ($query, $search) {
                                    return $query->where('name','like','%'.$search.'%');
                                })
                                ->when($year, function ($query, $year) {
                                    return $query->whereYear('created_at', $year);
                                })
                                ->when($type, function ($query, $type) {
                                    return $query->where('document_type_id', $type);
                                })
                                ->when($program, function ($query, $program) {
                                    return $query->where('program_id', $program);
                                })
                                ->get();

        $countAll       = $this->model->newQuery()->count();
        $countSelected  = $result->count();
        $countNew       = $this->model->newQuery()->whereMonth('created_at', date('m'))->count();

        return $this->sendSuccess([
            'countAll' => $countAll,
            'countSelected' => $countSelected,
            'countNew' => $countNew,
            'data' => $result,
        ]);
    }

    public function getPaginated($search = null,$year = null, $type = null, $programs = [], $perPage = 15, $page = null, $sortBy = 'created_at', $sort = 'DESC', $hideSecret = false)
    {
        $result  = $this->model->newQuery()->with(['related_program.program', 'documentType.thumbnail', 'file'])
                                ->when($search, function ($query, $search) {
                                    return $query->where('name','like','%'.$search.'%')->orWhere('document_number', 'like', '%'.$search.'%');
                                })
                                ->when($year, function ($query, $year) {
                                    return $query->whereYear('created_at', $year);
                                })
                                ->when($type, function ($query, $type) {
                                    return $query->where('document_type_id', $type);
                                })
                                ->when($programs, function ($query, $programs) {
                                    $query->whereHas('related_program.program', function($q) use ($programs) {
                                        $q->whereIn('id', $programs);
                                    });
                                })
                                ->when($hideSecret === "true", function ($query) {
                                    return $query->where('is_credential', 0);
                                })
                                ->when($sort && $sortBy, function ($query) use ($sort, $sortBy) {
                                    $query->orderBy($sortBy, $sort);
                                });

        $countAll       = $this->model->newQuery()->count();
        $countSelected  = $result->count();
        $countNew       = $this->model->newQuery()->whereMonth('created_at', date('m'))->count();

        return $this->sendSuccess([
            'countAll' => $countAll,
            'countSelected' => $countSelected,
            'countNew' => $countNew,
            'data' => $result->paginate((int)$perPage, ['*'], null, $page),
        ]);
    }

    public function getById($id)
    {
        $result = $this->model->newQuery()
            ->with('file')
            ->with('documentType.thumbnail')
            ->with('relatedFile.related.file')
            ->with('related_program.program')
            ->find($id);

        return $this->sendSuccess($result);
    }

    public function create($data)
    {

        \DB::beginTransaction();

        try {

            $document = $this->model->newQuery()->create([
                'name'              =>  $data['name'],
                'slug'              =>  Str::slug($data['name']),
                'document_type_id'  =>  $data['document_type_id'],
                'document_number'   =>  $data['document_number'],
                'publish_date'      =>  $data['publish_date'],
                'is_credential'     =>  $data['is_confidential'],
            ]);

            if (isset($data['document_related'])) {
                foreach($data['document_related'] as $doc) {
                    $this->documentRelated->newQuery()->create([
                        'document_id'            =>  $document->id,
                        'related_document_id'    =>  $doc,
                    ]);
                }
            }

            if (isset($data['program_related'])) {
                foreach($data['program_related'] as $program) {
                    $this->programRelated->newQuery()->create([
                        'document_id'            =>  $document->id,
                        'program_id'             =>  $program,
                    ]);
                }
            }

            if (!empty($data['file_id'])) {
                
                $fileOriginData = $this->fileTable->newQuery()->find($data['file_id']);
                
                if($fileOriginData) {

                    $filePathOrigin = $fileOriginData->file_path;

                    try {
                        // Source file and watermark config 
                        $originalFile = 'storage/'.$filePathOrigin; 
                        $generateQR = \QrCode::format('png')->size(120)->merge('/public/images/square_ruang_mutu.png', .3)->errorCorrection('H')->generate(
                            config('app.frontend_url') . '/view-file/doc/' . $document->id); 

                        $disk = env('UPLOAD_STORAGE', 'public');
        
                        $temporaryPath = 'uploads/temporary/';
                        $temporaryName = uniqid().'-'. time() . '.png';
        
                        $makeImage = \App\Service\OperationalStandard\OperationalStandardService::fromBase64(base64_encode($generateQR), $temporaryName);
                        $makeImage->storeAs($temporaryPath, $temporaryName, ['disk' => $disk]);
                        $text_image = 'storage/'.$temporaryPath.$temporaryName;
                        $pdf = new Fpdi(); 
        
                        if(file_exists(public_path($originalFile))){ 
                            $pagecount = $pdf->setSourceFile($originalFile); 
                        }else{ 
                            return $this->sendError(null,  'Terjadi kesalahan, silahkan ulangi beberapa saat');
                        } 
         
                        for($i=1;$i<=$pagecount;$i++){ 
                            $tpl = $pdf->importPage($i); 
                            $size = $pdf->getTemplateSize($tpl); 
                            $pdf->addPage(); 
                            // $pdf->useTemplate($tpl, 1, 1, $size['width'], $size['height'], TRUE); 
                            $pdf->useTemplate($tpl, null, 1, $size['width'], $size['height'], TRUE); 
                            
                            // $xxx_final = ($size['width']-35); 
                            // $yyy_final = ($size['height']-275); 
                            $xxx_final = ($size['width']-($size['width'] / 6)); 
                            $yyy_final = ($size['height']-($size['height'] - 4)); 
                            $pdf->Image($text_image, $xxx_final, $yyy_final, 0, 0, 'png'); 
                        } 
                        // Remove qrcode and temporary file From Storage
                        \Storage::disk($disk)->delete($temporaryPath.$temporaryName);
                        \Storage::disk($disk)->delete($filePathOrigin);
                        $fileOriginData->delete();

                        $newPdfName = FileUploadService::generateNewName() . '.pdf';
                        $makeNewPdf = \App\Service\OperationalStandard\OperationalStandardService::fromBase64(base64_encode($pdf->Output('S', $newPdfName)), $newPdfName);

                        $props = FileUploadService::directProcessFile($makeNewPdf);

                        $props['disk'] = $disk;
                        $props['group'] = 'document_files';
                        $props['fileable_id'] = $document->id;
                        $props['fileable_type'] = get_class($document);

                        $finalSave = FileUploadService::directSaveToDb($props);
            
                    } catch (\Exception $e) {
                        // return $e->getMessage();
                        return $this->sendError(null,   $e->getMessage() ?? null);

                    }
                }
            }


            \DB::commit(); // commit the changes
            return $this->sendSuccess($document);
        } catch (\Exception $exception) {
            \DB::rollBack(); // rollback the changes
            return $this->sendError(null, $this->debug ? $exception->getMessage() : null);
        }
    }

    public function backendCreate($data)
    {

        \DB::beginTransaction();

        try {

            $document = $this->model->newQuery()->create([
                'name'              =>  $data['name'],
                'slug'              =>  Str::slug($data['name']),
                'document_type_id'  =>  $data['document_type_id'],
                'document_number'   =>  $data['document_number'],
                'publish_date'      =>  $data['publish_date'],
                'is_confidential'   =>  $data['is_confidential'],
            ]);

            if (isset($data['document_related'])) {
                foreach($data['document_related'] as $doc) {
                    $this->documentRelated->newQuery()->create([
                        'document_id'            =>  $document->id,
                        'related_document_id'    =>  $doc,
                    ]);
                }
            }

            if (isset($data['program_related'])) {
                foreach($data['program_related'] as $program) {
                    $this->programRelated->newQuery()->create([
                        'document_id'            =>  $document->id,
                        'program_id'             =>  $program,
                    ]);
                }
            }

            if (!empty($data['file_id'])) {
                $image = $this->fileTable->newQuery()->find($data['file_id']);
                $image->update([
                    'fileable_type' => get_class($document),
                    'fileable_id'   => $document->id,
                ]);
            }


            \DB::commit(); // commit the changes
            return $this->sendSuccess($document);
        } catch (\Exception $exception) {
            \DB::rollBack(); // rollback the changes
            return $this->sendError(null, $this->debug ? $exception->getMessage() : null);
        }
    }

    public function update($id, $data)
    {
        $document   =   $this->model->newQuery()->find($id);

        \DB::beginTransaction();

        try {

            $document->name             =   $data['name'];
            $document->slug             =   Str::slug($data['name']);
            $document->publish_date     =   $data['publish_date'];
            $document->document_number  =   $data['document_number'];
            $document->document_type_id =   $data['document_type_id'];
            $document->is_credential    =   $data['is_credential'];
            
            $document->save();

            $this->documentRelated->newQuery()->where('document_id', $id)->delete();
            foreach($data['document_related'] as $doc) {
                $this->documentRelated->newQuery()->create([
                    'document_id'            =>  $document->id,
                    'related_document_id'    =>  $doc,
                ]);
            }
            
            $this->programRelated->newQuery()->where('document_id', $id)->delete();
            if (isset($data['program_related'])) {
                foreach($data['program_related'] as $program) {
                    $this->programRelated->newQuery()->create([
                        'document_id'            =>  $document->id,
                        'program_id'             =>  $program,
                    ]);
                }
            }

            \DB::commit(); // commit the changes
            return $this->sendSuccess($document);
        } catch (\Exception $exception) {
            \DB::rollBack(); // rollback the changes
            return $this->sendError(null, $this->debug ? $exception->getMessage() : null);
        }
    }

    public function delete($id)
    {
        $read   =   $this->model->newQuery()->find($id);
        try {
            $read->delete();
            \DB::commit(); // commit the changes
            return $this->sendSuccess($read);
        } catch (\Exception $exception) {
            \DB::rollBack(); // rollback the changes
            return $this->sendError(null, $this->debug ? $exception->getMessage() : null);
        }
    }
}
