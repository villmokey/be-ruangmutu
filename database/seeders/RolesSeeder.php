<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'id'   => "00000000-0000-1111-1111-000000000001",
            'name' => 'admin'
        ]);
        Role::create([
            'id'   => "00000000-0000-1111-1111-000000000002",
            'name' => 'staff'
        ]);
        Role::create([
            'id'   => "00000000-0000-1111-1111-000000000003",
            'name' => 'guest'
        ]);
    }
}
