<?php


namespace App\Service\Event;


use App\Models\Table\FileTable;
use App\Models\Table\EventTable;
use App\Models\Table\EventDocumentTable;
use App\Models\Table\EventProgramTable;

use App\Service\AppService;
use App\Service\AppServiceInterface;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class EventService extends AppService implements AppServiceInterface
{
    protected $fileTable;
    protected $eventDocument;
    protected $eventProgram;

    public function __construct(
        EventTable $model,
        FileTable $fileTable,
        EventDocumentTable $eventDocument,
        EventProgramTable $eventProgram
    ) {
        $this->fileTable = $fileTable;
        $this->eventDocument = $eventDocument;
        $this->eventProgram = $eventProgram;
        parent::__construct($model);
    }

    public function getAll($search = null, $year = null, $month = null, $programs = [])
    {
        $result =   $this->model->newQuery()->with(['relatedProgram.program', 'user', 'relatedFile.related.file', 'program', 'otherFiles'])
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', '%' . $search . '%');
            })
            ->when($month, function ($query, $month) {
                return $query->where('created_at','like', '%'.$month.'%');
            })
            ->when($programs, function ($query, $programs) {
                $query->whereHas('relatedProgram.program', function($q) use ($programs) {
                    $q->whereIn('id', $programs);
                });
            })
            ->when($year, function ($query, $year) {
                return $query->whereYear('created_at', $year);
            });
            
            if($year && $month && $month != 'ALL') {
                $result->where('created_at','like', $year . '-' .$month.'%');
            }
            
            $result->orderBy('created_at', 'DESC');

        return $this->sendSuccess($result->get());
    }

    public function getPaginated($search = null, $year = null, $perPage = 15, $page = null, $programs = [])
    {
        $result  = $this->model->newQuery()->with(['relatedProgram.program', 'user', 'relatedFile'])
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', '%' . $search . '%');
            })
            ->when($year, function ($query, $year) {
                return $query->whereYear('created_at', $year);
            })
            ->when($programs, function ($query, $programs) {
                $query->whereHas('relatedProgram.program', function($q) use ($programs) {
                    $q->whereIn('id', $programs);
                });
            })
            ->orderBy('created_at', 'DESC')
            ->paginate((int)$perPage, ['*'], null, $page);

        return $this->sendSuccess($result);
    }

    public function getById($id)
    {
        $result = $this->model->newQuery()
            ->with(['user','relatedFile.related.file'])
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
                'start_date'        =>  $data['start_date'],
                'end_date'          =>  $data['end_date'],
                'description'       =>  $data['description'],
                'program_id'        =>  $data['program_id'] ?? null,
                'created_id'        =>  \Auth::user()->id
            ]);

            if (isset($data['document_related'])) {
                foreach ($data['document_related'] as $doc) {
                    $this->eventDocument->newQuery()->create([
                        'event_id'            =>  $document->id,
                        'document_id'    =>  $doc,
                    ]);
                }
            }

            if (isset($data['related_program'])) {
                if (!empty($data['related_program'])) {
                    foreach ($data['related_program'] as $prog) {
                        $this->eventProgram->newQuery()->create([
                            'event_id'     => $document->id,
                            'program_id'   => $prog
                        ]);
                    }
                }
            }

            if (isset($data['event_files'])) {
                if (!empty($data['event_files'])) {
                    foreach ($data['event_files'] as $file) {
                        $fileRow = $this->fileTable->newQuery()->find($file);
                        if($fileRow) {
                            $fileRow->update([
                                'fileable_type' => get_class($document),
                                'fileable_id'   => $document->id,
                            ]);
                        }
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

    public function update($id, $data)
    {
        $document   =   $this->model->newQuery()->find($id);

        \DB::beginTransaction();

        try {

            $document->name    =   $data['name'];
            $document->slug    =   Str::slug($data['name']);
            $document->start_date    =   $data['start_date'];
            $document->end_date    =   $data['end_date'];
            $document->save();

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
    
    public function makeRealized($id) {
        $read   =   $this->model->newQuery()->find($id);
        try {
            $read->is_realized = true;
            $read->update();
            \DB::commit(); // commit the changes
            return $this->sendSuccess($read);
        } catch (\Exception $exception) {
            \DB::rollBack(); // rollback the changes
            return $this->sendError(null, $this->debug ? $exception->getMessage() : null);
        }

    }
}
