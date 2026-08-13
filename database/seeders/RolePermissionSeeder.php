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
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'dashboard.view',
            'products.view', 'products.create', 'products.edit', 'products.delete',
            'orders.view', 'orders.edit', 'orders.export',
            'quotes.view', 'quotes.edit', 'quotes.export',
            'customers.view', 'customers.edit',
            'content.view', 'content.create', 'content.edit', 'content.delete', 'content.approve',
            'projects.view', 'projects.create', 'projects.edit', 'projects.delete',
            'careers.view', 'careers.create', 'careers.edit', 'careers.delete',
            'messages.view', 'messages.edit',
            'media.view', 'media.create', 'media.delete',
            'locations.view', 'locations.edit',
            'settings.view', 'settings.edit',
            'users.view', 'users.create', 'users.edit', 'users.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        $roles = [
            'super-admin' => $permissions,
            'administrator' => array_values(array_filter(
                $permissions,
                fn (string $p) => ! str_starts_with($p, 'users.')
            )),
            'sales-manager' => [
                'dashboard.view',
                'products.view',
                'orders.view', 'orders.edit', 'orders.export',
                'quotes.view', 'quotes.edit', 'quotes.export',
                'customers.view', 'customers.edit',
                'messages.view', 'messages.edit',
            ],
            'content-manager' => [
                'dashboard.view',
                'content.view', 'content.create', 'content.edit', 'content.delete', 'content.approve',
                'projects.view', 'projects.create', 'projects.edit', 'projects.delete',
                'media.view', 'media.create', 'media.delete',
                'products.view',
            ],
            'hr-manager' => [
                'dashboard.view',
                'careers.view', 'careers.create', 'careers.edit', 'careers.delete',
                'messages.view',
            ],
            'customer-support' => [
                'dashboard.view',
                'orders.view',
                'quotes.view',
                'customers.view',
                'messages.view', 'messages.edit',
            ],
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::findOrCreate($roleName);
            $role->syncPermissions($rolePermissions);
        }
    }
}
