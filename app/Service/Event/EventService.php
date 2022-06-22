<?php


namespace App\Service\Event;


use App\Models\Table\FileTable;
use App\Models\Table\EventTable;
use App\Models\Table\EventDocumentTable;

use App\Service\AppService;
use App\Service\AppServiceInterface;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class EventService extends AppService implements AppServiceInterface
{
    protected $fileTable;
    protected $eventDocument;

    public function __construct(
        EventTable $model,
        FileTable $fileTable,
        EventDocumentTable $eventDocument
    )
    {
        $this->fileTable = $fileTable;
        $this->eventDocument = $eventDocument;
        parent::__construct($model);
    }

    public function getAll($search = null,$year = null)
    {
        $result =   $this->model->newQuery()
                                ->when($search, function ($query, $search) {
                                    return $query->where('name','like','%'.$search.'%');
                                })
                                ->when($year, function ($query, $year) {
                                    return $query->whereYear('created_at', $year);
                                })
                                ->get();

        return $this->sendSuccess($result);
    }

    public function getPaginated($search = null, $year = null, $perPage = 15, $page = null)
    {
        $result  = $this->model->newQuery()
                                ->when($search, function ($query, $search) {
                                    return $query->where('name','like','%'.$search.'%');
                                })
                                ->when($year, function ($query, $year) {
                                    return $query->whereYear('created_at', $year);
                                })
                                ->orderBy('created_at','DESC')
                                ->paginate((int)$perPage, ['*'], null, $page);

        return $this->sendSuccess($result);
    }

    public function getById($id)
    {
        $result = $this->model->newQuery()
            ->with('relatedFile.related.file')
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
            ]);

            if (isset($data['document_related'])) {
                foreach($data['document_related'] as $doc) {
                    $this->eventDocument->newQuery()->create([
                        'event_id'            =>  $document->id,
                        'document_id'    =>  $doc,
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
}
