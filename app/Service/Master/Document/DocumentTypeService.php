<?php


namespace App\Service\Master\Document;


use App\Models\Table\DocumentTypeTable;
use App\Service\AppService;
use App\Service\AppServiceInterface;
use Illuminate\Database\Eloquent\Model;

class DocumentTypeService extends AppService implements AppServiceInterface
{

    public function __construct(DocumentTypeTable $model)
    {
        parent::__construct($model);
    }

    public function getAll($search = null)
    {
        $result =   $this->model->newQuery()
                                ->where('is_publish', true)
                                ->when($search, function ($query, $search) {
                                    return $query->where('name','like','%'.$search.'%');
                                })
                                ->get();

        return $this->sendSuccess($result);
    }

    public function getPaginated($search = null, $perPage = 15, $page = null)
    {
        $result  = $this->model->newQuery()
                                ->where('is_publish', true)
                                ->when($search, function ($query, $search) {
                                    return $query->where('name','like','%'.$search.'%');
                                })
                                ->orderBy('created_at','DESC')
                                ->paginate((int)$perPage, ['*'], null, $page);

        return $this->sendSuccess($result);
    }

    public function getById($id)
    {
        $result = $this->model->newQuery()->find($id);

        return $this->sendSuccess($result);
    }

    public function create($data)
    {
        \DB::beginTransaction();

        try {

            $documentType = $this->model->newQuery()->create([
                'name'    =>  $data['name'],
                'desc'    =>  $data['desc'],
            ]);

            \DB::commit(); // commit the changes
            return $this->sendSuccess($documentType);
        } catch (\Exception $exception) {
            \DB::rollBack(); // rollback the changes
            return $this->sendError(null, $this->debug ? $exception->getMessage() : null);
        }
    }

    public function update($id, $data)
    {
        $documentType   =   $this->model->newQuery()->find($id);

        \DB::beginTransaction();

        try {

            $documentType->name    =   $data['name'];
            $documentType->desc    =   $data['desc'];
            $documentType->save();

            \DB::commit(); // commit the changes
            return $this->sendSuccess($documentType);
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

    public function updatePublish($id)
    {
        $documentType = $this->model->newQuery()->find($id);

        \DB::beginTransaction();

        try {
            $documentType->is_publish = $documentType->is_publish ? false : true;
            $documentType->save();

            \DB::commit(); // commit the changes
            return $this->sendSuccess($documentType);
        } catch (\Exception $exception) {
            \DB::rollBack(); // rollback the changes
            return $this->sendError(null, $this->debug ? $exception->getMessage() : null);
        }
    }
}
