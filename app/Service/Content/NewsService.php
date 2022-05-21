<?php


namespace App\Service\Content;


use App\Models\Table\NewsTable;
use App\Service\AppService;
use App\Service\AppServiceInterface;
use Yajra\DataTables\Facades\DataTables;

class NewsService extends AppService implements AppServiceInterface
{

    public function __construct(NewsTable $model)
    {
        parent::__construct($model);
    }

    public function getAll()
    {

        $model = $this->model->query()->orderBy('created_at','DESC');

        return DataTables::eloquent($model)->addIndexColumn()->toJson();
    }

    public function getPaginated($search = null, $perPage = 15)
    {
        // TODO: Implement getPaginated() method.
    }

    public function getById($id)
    {
        // TODO: Implement getById() method.
    }

    public function create($data)
    {
        // TODO: Implement create() method.
    }

    public function update($id, $data)
    {
        // TODO: Implement update() method.
    }

    public function delete($id)
    {
        // TODO: Implement delete() method.
    }
}
