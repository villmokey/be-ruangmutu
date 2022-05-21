<?php


namespace App\Service;


interface AppServiceInterface
{
    public function getAll($search = null);

    public function getPaginated($search = null, $perPage = 15, $page = null);

    public function getById($id);

    public function create($data);

    public function update($id, $data);

    public function delete($id);
}
