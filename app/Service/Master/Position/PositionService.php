<?php


namespace App\Service\Master\Position;


use App\Models\Table\PositionTable;
use App\Service\AppService;
use App\Service\AppServiceInterface;
use Illuminate\Database\Eloquent\Model;

class PositionService extends AppService implements AppServiceInterface
{

    public function __construct(PositionTable $model)
    {
        parent::__construct($model);
    }

    public function getAll($search = null)
    {
        $result =   $this->model->newQuery()
                                ->when($search, function ($query, $search) {
                                    return $query->where('name','like','%'.$search.'%');
                                })
                                ->get();

        return $this->sendSuccess($result);
    }

    public function getPaginated($search = null, $perPage = 15, $page = null)
    {
        $result  = $this->model->newQuery()
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

            if($data['is_leader'] === true || $data['is_leader'] === '1') {
                PositionTable::where('is_leader', true)->update(['is_leader' => false]);
            }

            $position = $this->model->newQuery()->create([
                'name'          =>  $data['name'],
                'is_leader'     =>  $data['is_leader'] ?? false,
                'created_id'    =>  \Auth::user()->id,
            ]);

            \DB::commit(); // commit the changes
            return $this->sendSuccess($position);
        } catch (\Exception $exception) {
            \DB::rollBack(); // rollback the changes
            return $this->sendError(null, $this->debug ? $exception->getMessage() : null);
        }
    }

    public function update($id, $data)
    {
        $position   =   $this->model->newQuery()->find($id);

        \DB::beginTransaction();

        try {

            $position->name         =   $data['name'];
            $position->is_leader    =   $data['is_leader'];
            $position->save();

            \DB::commit(); // commit the changes
            return $this->sendSuccess($position);
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
