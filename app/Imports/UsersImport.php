<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithLimit;
use App\Models\Entity\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;


class UsersImport implements ToModel, WithHeadingRow, WithStartRow,WithBatchInserts
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if(isset($row['nip'])) {
            echo $row['email'];
            $newUuid = \Str::uuid();
            $user = new User([
                'id'    => $newUuid,
                'nip' => $row['nip'],
                'name' => $row['nama_lengkap_dan_gelar'], 
                'email' => $row['email'],
                'password' => \Hash::make(substr($row['nip'], -6)) 
            ]);

            $roleId = \App\Models\Entity\Role::where('name', self::getRoleName($row['role_user']))->first()->id;

            \DB::table('model_has_roles')->insert([
                ['role_id' => $roleId, 'model_type' => 'App\Models\Entity\User', 'model_id' => $newUuid]
            ]);

            return $user;
                        
        }
    }

    private function getRoleName($role) {
        switch ($role) {
            case 'SUPER ADMIN':
                return 'Super Admin';
                break;
            case 'ADMIN':
                return 'Admin';
                break;
            case 'USER':
                return 'User';
                break;
            
            default:
                return 'User';
                break;
        }
    }

    public function headingRow(): int
    {
        return 2;
    }

    public function startRow(): int
    {
        return 3;
    }

    public function batchSize(): int
    {
        return 148;
    }
}
