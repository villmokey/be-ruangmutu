<?php


namespace App\Service\Master\User;

use Illuminate\Support\Facades\Hash;

use App\Models\Entity\User;
use App\Models\Entity\Role;

use App\Models\Table\UserTable;
use App\Models\Table\FileTable;

use App\Service\AppService;
use App\Service\AppServiceInterface;
use App\Service\FileUploadService;

use Illuminate\Database\Eloquent\Model;

class UserService extends AppService implements AppServiceInterface
{
    protected $fileUploadService;
    protected $fileTable;

    public function __construct(
        FileUploadService $fileUploadService,
        FileTable $fileTable,
        UserTable $model
    )
    {
        $this->fileUploadService    =   $fileUploadService;
        $this->fileTable            =   $fileTable;
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
        $result = $this->model->newQuery()
        ->with('signature')
        ->with('pic')
        ->find($id);

        return $this->sendSuccess($result);
    }

    public function create($data)
    {
        \DB::beginTransaction();

        try {

            $user = User::create([
                'nip'       =>  $data['nip'],
                'name'      =>  $data['name'],
                'email'     =>  $data['email'],
                'password'  =>  Hash::make($data['password']),
                'status'    =>  'active',
            ]);

            $role = Role::where('id', $data['role_id'])->first();
            $user->assignRole($role->name);

            if (!empty($data['signature_id'])) {
                $image = $this->fileTable->newQuery()->find($data['signature_id']);
                $image->update([
                    'fileable_type' => get_class($user),
                    'fileable_id'   => $user->id,
                ]);
            }

            \DB::commit(); // commit the changes
            return $this->sendSuccess($user);
        } catch (\Exception $exception) {
            \DB::rollBack(); // rollback the changes
            return $this->sendError(null, $this->debug ? $exception->getMessage() : null);
        }
    }

    public function update($id, $data)
    {
        $user   =   User::find($id);

        \DB::beginTransaction();

        try {

            $user->nip          =   $data['nip'];
            $user->name         =   $data['name'];
            $user->email        =   $data['email'];
            if (isset($data['password'])) {
                $user->password    =   Hash::make($data['password']);
            }
            $user->status       =   $data['status'];
            $user->save();

            if (isset($data['role_id'])) {
                $role = Role::where('id', $data['role_id'])->first();
                $user->syncRoles($role->name);
            }

            if (!empty($data['signature_id'])) {
                $image = $this->fileTable->newQuery()->find($data['signature_id']);
                $image->update([
                    'fileable_type' => get_class($user),
                    'fileable_id'   => $user->id,
                ]);
            }

            \DB::commit(); // commit the changes
            return $this->sendSuccess($user);
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
