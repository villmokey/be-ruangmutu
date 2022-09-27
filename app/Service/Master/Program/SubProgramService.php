<?php


namespace App\Service\Master\Program;


use App\Models\Table\SubProgramTable;
use App\Service\AppService;
use App\Service\AppServiceInterface;
use Illuminate\Database\Eloquent\Model;

class SubProgramService extends AppService implements AppServiceInterface
{

    public function __construct(SubProgramTable $model)
    {
        parent::__construct($model);
    }

    public function getAll($search = null)
    {
        $result =   $this->model->newQuery()
                                ->where('is_publish', true)
                                ->with('program')
                                ->when($search, function ($query, $search) {
                                    return $query->where('name','like','%'.$search.'%');
                                })
                                ->get();

        return $this->sendSuccess($result);
    }

    public function getPaginated($search = null, $perPage = 15, $page = null, $filter = null)
    {
        $result  = $this->model->newQuery()
                                ->where('is_publish', true)
                                ->with('program')
                                ->when($search, function ($query, $search) {
                                    return $query->where('name','like','%'.$search.'%');
                                })
                                ->when($filter, function ($query, $filter) {
                                    return $query->where('program_id', $filter);
                                })
                                ->orderBy('created_at','DESC')
                                ->paginate((int)$perPage, ['*'], null, $page);

        return $this->sendSuccess($result);
    }

    public function getById($id)
    {
        $result = $this->model->newQuery()->with('program')->find($id);

        return $this->sendSuccess($result);
    }

    public function create($data)
    {
        \DB::beginTransaction();

        try {

            $serviceUnit = $this->model->newQuery()->create([
                'program_id'    =>  $data['program_id'],
                'name'          =>  $data['name'],
            ]);

            \DB::commit(); // commit the changes
            return $this->sendSuccess($serviceUnit);
        } catch (\Exception $exception) {
            \DB::rollBack(); // rollback the changes
            return $this->sendError(null, $this->debug ? $exception->getMessage() : null);
        }
    }

    public function update($id, $data)
    {
        $serviceUnit   =   $this->model->newQuery()->find($id);

        \DB::beginTransaction();

        try {

            $serviceUnit->program_id   =   $data['program_id'];
            $serviceUnit->name    =   $data['name'];
            $serviceUnit->save();

            \DB::commit(); // commit the changes
            return $this->sendSuccess($serviceUnit);
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
        $serviceUnit = $this->model->newQuery()->find($id);

        \DB::beginTransaction();

        try {
            $serviceUnit->is_publish = $serviceUnit->is_publish ? false : true;
            $serviceUnit->save();

            \DB::commit(); // commit the changes
            return $this->sendSuccess($serviceUnit);
        } catch (\Exception $exception) {
            \DB::rollBack(); // rollback the changes
            return $this->sendError(null, $this->debug ? $exception->getMessage() : null);
        }
    }
}
