<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['name' => 'Administrador', 'email' => 'admin@coworking.test', 'role' => 'admin'],
            ['name' => 'Recepción', 'email' => 'staff@coworking.test', 'role' => 'staff'],
            ['name' => 'Cliente Demo', 'email' => 'client@coworking.test', 'role' => 'client'],
        ];

        foreach ($users as $data) {
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => 'password123', // el cast 'hashed' la encripta
                ]
            );

            $user->syncRoles([$data['role']]);
        }
    }
}