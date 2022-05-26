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
        $user = User::create([
            'name' => 'admin',
            'email' => 'admin@imut.id',
            'email_verified_at' => now(),
            'password' => Hash::make('Secret123!'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $user->assignRole('admin');
    }
}
