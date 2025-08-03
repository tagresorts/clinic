<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Permissions
        $permissions = [
            'user-list', 'user-create', 'user-edit', 'user-delete',
            'role-list', 'role-create', 'role-edit', 'role-delete',
            'permission-list', 'permission-create', 'permission-edit', 'permission-delete',
            'patient-list', 'patient-create', 'patient-edit', 'patient-delete',
            'appointment-list', 'appointment-create', 'appointment-edit', 'appointment-delete',
            'treatment-plan-list', 'treatment-plan-create', 'treatment-plan-edit', 'treatment-plan-delete',
            'invoice-list', 'invoice-create', 'invoice-edit', 'invoice-delete',
            'procedure-list', 'procedure-create', 'procedure-edit', 'procedure-delete',
            'inventory-list', 'inventory-create', 'inventory-edit', 'inventory-delete',
            'report-list',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create Roles and assign created permissions

        $receptionist_permissions = [
            'patient-list', 'patient-create', 'patient-edit',
            'appointment-list', 'appointment-create', 'appointment-edit',
            'invoice-list', 'invoice-create', 'invoice-edit',
        ];

        $dentist_permissions = array_merge($receptionist_permissions, [
            'patient-delete',
            'appointment-delete',
            'treatment-plan-list', 'treatment-plan-create', 'treatment-plan-edit', 'treatment-plan-delete',
        ]);

        $role = Role::create(['name' => 'receptionist']);
        $role->givePermissionTo($receptionist_permissions);

        $role = Role::create(['name' => 'dentist']);
        $role->givePermissionTo($dentist_permissions);

        $role = Role::create(['name' => 'administrator']);
        $role->givePermissionTo(Permission::all());

        $role = Role::create(['name' => 'viewer']);
        $role->givePermissionTo(['patient-list', 'appointment-list']);

        // Create Demo Users
        $user = \App\Models\User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
        ]);
        $user->assignRole('administrator');

        $user = \App\Models\User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
        ]);
        $user->assignRole('administrator');

        $user = \App\Models\User::factory()->create([
            'name' => 'Editor User',
            'email' => 'editor@example.com',
        ]);
        $user->assignRole('dentist');

        $user = \App\Models\User::factory()->create([
            'name' => 'Viewer User',
            'email' => 'viewer@example.com',
        ]);
        $user->assignRole('viewer');
    }
}
