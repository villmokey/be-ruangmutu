<?php


namespace App\Service\Document;


use App\Models\Table\FileTable;
use App\Models\Table\DocumentTable;
use App\Models\Table\DocumentRelatedTable;
use App\Models\Table\DocumentProgramTable;

use App\Service\AppService;
use App\Service\AppServiceInterface;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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

    public function getPaginated($search = null,$year = null, $type = null, $programs = [], $perPage = 15, $page = null, $sortBy = 'created_at', $sort = 'DESC')
    {
        $result  = $this->model->newQuery()->with(['related_program.program', 'documentType', 'file'])
                                ->when($search, function ($query, $search) {
                                    return $query->where('name','like','%'.$search.'%');
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
                                ->when($sort && $sortBy, function ($query) use ($sort, $sortBy) {
                                    $query->orderBy($sortBy, $sort);
                                })
                                ->paginate((int)$perPage, ['*'], null, $page);

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

    public function getById($id)
    {
        $result = $this->model->newQuery()
            ->with('file')
            ->with('documentType')
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

            $document->name    =   $data['name'];
            $document->slug    =   Str::slug($data['name']);
            $document->document_type_id = $data['document_type_id'];
            $document->program_id = $data['program_id'];
            $document->save();

            $this->documentRelated->newQuery()->where('document_id', $id)->delete();
            foreach($data['document_related'] as $doc) {
                $this->documentRelated->newQuery()->create([
                    'document_id'            =>  $doc->id,
                    'related_document_id'    =>  $doc,
                ]);
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
