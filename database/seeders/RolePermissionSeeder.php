<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $studentRole = Role::create([
            'name' => 'student'
        ]);

        $mentorRole = Role::create([
            'name' => 'mentor'
        ]);

        $adminRole = Role::create([
            'name' => 'admin'
        ]);

        $userRole = User::create([
            'name' => 'Dicky',
            'email' => 'dickyumum27@gmail.com',
            'password' => bcrypt('Sandine321')
        ]);

        $userRole->assignRole('admin');
    }
}
