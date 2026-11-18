<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Limpia la caché de permisos de Spatie
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [
            // Espacios
            'spaces.view',
            'spaces.create',
            'spaces.update',
            'spaces.delete',

            // Tipos de espacio
            'space_types.view',
            'space_types.create',
            'space_types.update',
            'space_types.delete',

            // Usuarios
            'users.view',
            'users.create',
            'users.update',
            'users.delete',

            // Roles y permisos
            'roles.view',
            'roles.create',
            'roles.update',
            'roles.delete',
            'roles.assign_permissions',

            // Reservas (cliente)
            'reservations.create',
            'reservations.view_own',
            'reservations.cancel_own',

            // Reservas (staff)
            'reservations.view_all',
            'reservations.approve',
            'reservations.reject',
            'reservations.complete',
            'availability.view',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        // Admin: todos los permisos
        $admin = Role::findOrCreate('admin', 'web');
        $admin->syncPermissions(Permission::all());

        // Staff / recepción
        $staff = Role::findOrCreate('staff', 'web');
        $staff->syncPermissions([
            'spaces.view',
            'reservations.view_all',
            'reservations.approve',
            'reservations.reject',
            'reservations.complete',
            'availability.view',
        ]);

        // Cliente
        $client = Role::findOrCreate('client', 'web');
        $client->syncPermissions([
            'spaces.view',
            'reservations.create',
            'reservations.view_own',
            'reservations.cancel_own',
        ]);
    }
}