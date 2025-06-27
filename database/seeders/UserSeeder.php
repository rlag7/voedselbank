<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {

        $roles = [
            'admin',
            'user',
            'employee',
            'volunteer',
        ];

        foreach ($roles as $roleName) {
            $role = Role::firstOrCreate(['name' => $roleName]);


            $user = User::firstOrCreate(
                    ['email' => $roleName . '@voedselbank.com'],
                [
                    'name' => ucfirst($roleName) . ' User',
                    'password' => Hash::make('password'),
                ]
            );

            $user->assignRole($role);
        }

        $userRole = Role::firstOrCreate(['name' => 'user']);
        User::factory()->count(3)->create()->each(function ($demoUser) use ($userRole) {
            $demoUser->assignRole($userRole);
        });
    }
}
