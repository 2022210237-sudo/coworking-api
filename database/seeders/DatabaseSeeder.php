<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class, // primero roles y permisos
            UserSeeder::class,           // luego usuarios (necesitan los roles)
            SpaceSeeder::class,
        ]);
    }
}