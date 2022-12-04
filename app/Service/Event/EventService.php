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

    public function getAll($search = null, $year = null, $month = null, $programs = null)
    {
        $result =   $this->model->newQuery()->with(['relatedProgram.program', 'user', 'relatedFile.related.file', 'program', 'otherFiles'])
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', '%' . $search . '%');
            })
            ->when($programs, function ($query, $programs) {
                $query->whereHas('relatedProgram.program', function($q) use ($programs) {
                    $q->whereIn('id', $programs);
                });
            })
            ->when($month, function ($query, $month) {
                return $query->where('start_date','like', '%-'.$month.'-%');
            })
            ->when($year, function ($query, $year) {
                return $query->where('start_date','like', $year.'-%');
            })->orderBy('start_date', 'DESC')->get();

        return $this->sendSuccess($result);
    }

    public function getPaginated($search = null, $year = null, $perPage = 15, $page = null, $month = null, $programs = null)
    {
        $result = $this->model->newQuery()->with(['relatedProgram.program', 'user', 'relatedFile.related.file', 'program', 'otherFiles'])
                                            ->when($search, function ($query, $search) {
                                                return $query->where('name', 'like', '%' . $search . '%');
                                            })
                                            ->when($programs, function ($query, $programs) {
                                                $query->whereHas('relatedProgram.program', function($q) use ($programs) {
                                                    $q->whereIn('id', $programs);
                                                });
                                            })
                                            ->when($month, function ($query, $month) {
                                                return $query->where('start_date','like', '%-'.$month.'-%');
                                            })
                                            ->when($year, function ($query, $year) {
                                                return $query->where('start_date','like', $year.'-%');
                                            })
                                            ->orderBy('start_date', 'DESC')
                                            ->paginate((int)$perPage, ['*'], null, $page);

                                            return $this->sendSuccess($result);
    }

    public function getById($id)
    {
        $result = $this->model->newQuery()
            ->with(['relatedProgram.program', 'user', 'relatedFile.related.file', 'program', 'otherFiles'])
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
        \DB::beginTransaction();

        $event = $this->model->newQuery()->find($id);

        try {
            if($event) {
                $event->name = $data['name'];
                $event->slug = Str::slug($data['name']);
                $event->start_date = $data['start_date'];
                $event->end_date = $data['end_date'];
                $event->program_id = $data['program_id'];
                $event->description = $data['description'];
                $event->save();
    
                if (isset($data['document_related'])) {
                    $this->eventDocument->newQuery()->where('event_id', $id)->delete();
                    foreach ($data['document_related'] as $doc) {
                        $this->eventDocument->newQuery()->create([
                            'event_id'            =>  $event->id,
                            'document_id'    =>  $doc,
                        ]);
                    }
                }
    
                if (isset($data['related_program'])) {
                    if (!empty($data['related_program'])) {
                        $this->eventProgram->newQuery()->where('event_id', $id)->delete();
                        foreach ($data['related_program'] as $prog) {
                            $this->eventProgram->newQuery()->create([
                                'event_id'     => $event->id,
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
                                    'fileable_type' => get_class($event),
                                    'fileable_id'   => $event->id,
                                ]);
                            }
                        }
                    }
                }
    
                \DB::commit(); // commit the changes
                return $this->sendSuccess($event);
            }

            \DB::rollBack(); // rollback the changes
            return $this->sendError(null, "Event tidak ditemukan, silahkan coba lagi");
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
