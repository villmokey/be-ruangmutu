<?php


namespace App\Service\Master\Role;

use Illuminate\Support\Facades\Hash;

use App\Models\Entity\Role;

use App\Service\AppService;
use App\Service\AppServiceInterface;
use App\Service\FileUploadService;

use Illuminate\Database\Eloquent\Model;

class RoleService extends AppService
{
    public function __construct(
        Role $model
    )
    {
        parent::__construct($model);

    }

    public function getAll($search = null)
    {
        $result =   $this->model->newQuery()
                                ->select(['id', 'name'])
                                ->when($search, function ($query, $search) {
                                    return $query->where('name','like','%'.$search.'%');
                                })
                                ->get();

        return $this->sendSuccess($result);
    }

    public function getPaginated($search = null, $perPage = 15, $page = null)
    {
        $result  = $this->model->newQuery()
                                ->select(['id', 'name'])
                                ->when($search, function ($query, $search) {
                                    return $query->where('name','like','%'.$search.'%');
                                })
                                ->orderBy('created_at','DESC')
                                ->paginate((int)$perPage, ['*'], null, $page);

        return $this->sendSuccess($result);
    }
}
