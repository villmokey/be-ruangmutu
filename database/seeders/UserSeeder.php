<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use App\Models\Entity\User;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::create([
            'id' => "00000000-0000-1111-1111-000000000011",
            'name' => 'admin',
            'email' => 'admin@imut.id',
            'email_verified_at' => now(),
            'password' => Hash::make('Secret123!'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $admin->assignRole('admin');

        $staff = User::create([
            'id' => "00000000-0000-1111-1111-000000000012",
            'name' => 'staff',
            'email' => 'staff@imut.id',
            'email_verified_at' => now(),
            'password' => Hash::make('Secret123!'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $staff->assignRole('staff');

        $staff = User::create([
            'id' => "00000000-0000-1111-1111-000000000013",
            'name' => 'guest',
            'email' => 'guest@imut.id',
            'email_verified_at' => now(),
            'password' => Hash::make('Secret123!'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $staff->assignRole('guest');
    }
}
