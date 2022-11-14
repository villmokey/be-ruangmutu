<?php


namespace App\Service\Master\Program;


use App\Models\Table\ProgramTable;
use App\Service\AppService;
use App\Service\AppServiceInterface;
use Illuminate\Database\Eloquent\Model;

class ProgramService extends AppService implements AppServiceInterface
{

    public function __construct(ProgramTable $model)
    {
        parent::__construct($model);
    }

    public function getAll($search = null)
    {
        $result =   $this->model->newQuery()
                                ->where('is_publish', true)
                                ->with('pic')
                                ->with('subPrograms')
                                ->when($search, function ($query, $search) {
                                    return $query->where('name','like','%'.$search.'%');
                                })
                                ->orderBy('name', 'asc')
                                ->get();

        return $this->sendSuccess($result);
    }

    public function getPaginated($search = null, $perPage = 15, $page = null)
    {
        $result  = $this->model->newQuery()
                                ->where('is_publish', true)
                                ->with('pic')
                                ->with('subPrograms')
                                ->when($search, function ($query, $search) {
                                    return $query->where('name','like','%'.$search.'%');
                                })
                                ->orderBy('name','asc')
                                ->paginate((int)$perPage, ['*'], null, $page);

        return $this->sendSuccess($result);
    }

    public function getById($id)
    {
        $result = $this->model->newQuery()->with('subPrograms')->find($id);

        return $this->sendSuccess($result);
    }

    public function create($data)
    {
        \DB::beginTransaction();

        try {

            $serviceUnit = $this->model->newQuery()->create([
                'pic_id'  =>  $data['pic_id'] ?? null,
                'name'    =>  $data['name'],
                'color'    =>  $data['color'],
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

            $serviceUnit->pic_id  =   $data['pic_id'] ?? null;
            $serviceUnit->name    =   $data['name'];
            $serviceUnit->color   =   $data['color'];
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
