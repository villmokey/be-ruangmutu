<?php


namespace App\Service\Master\HealthService;

use Illuminate\Support\Facades\Hash;

use App\Models\Entity\User;
use App\Models\Entity\Role;
use App\Models\Entity\HealthService;

use App\Models\Table\HealthServiceUnitTable;
use App\Models\Table\HealthServiceTable;
use App\Models\Table\FileTable;

use App\Service\AppService;
use App\Service\AppServiceInterface;
use App\Service\FileUploadService;

use Illuminate\Database\Eloquent\Model;

class HealthServiceService extends AppService implements AppServiceInterface
{
    public function __construct(
        HealthServiceTable $model
    )
    {
        parent::__construct($model);

    }

    public function getAll($search = null)
    {
        $result =   $this->model->newQuery()
                                ->with(['units.service'])
                                ->when($search, function ($query, $search) {
                                    return $query->where('name','like','%'.$search.'%');
                                })
                                ->get();

        return $this->sendSuccess($result);
    }

    public function getPaginated($search = null, $perPage = 15, $page = null)
    {
        $result  = $this->model->newQuery()
                                ->with(['units.service'])
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
            $health = HealthService::create([
                'name'       =>  $data['name'],
                'created_id' => \Auth::user()->id
            ]);

            \DB::commit(); // commit the changes
            return $this->sendSuccess($health);
        } catch (\Exception $exception) {
            \DB::rollBack(); // rollback the changes
            return $this->sendError(null, $this->debug ? $exception->getMessage() : null);
        }
    }

    public function assignServiceUnit($data)
    {
        try {
            if($data && $data['units']) {
                $expl = explode(',', $data['units']);
                $health = null;
                if(count($expl) > 0) {
                    HealthServiceUnitTable::where(['health_service_id' => $data['health_service_id']])->delete();
                    foreach($expl as $unit) {
                        $health = HealthServiceUnitTable::create([
                            'service_unit_id'   => $unit,
                            'health_service_id' => $data['health_service_id'],
                            'created_id'        => \Auth::user()->id
                        ]);
                    }
                }
            }
            return $this->sendSuccess($health);
        } catch (\Exception $exception) {
            \DB::rollBack(); // rollback the changes
            return $this->sendError(null, $this->debug ? $exception->getMessage() : null);
        }
    }

    public function update($id, $data)
    {
        $health  =  HealthService::find($id);

        \DB::beginTransaction();

        try {

            $health->name          =   $data['name'];
            $health->save();

            \DB::commit(); // commit the changes
            return $this->sendSuccess($health);
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
