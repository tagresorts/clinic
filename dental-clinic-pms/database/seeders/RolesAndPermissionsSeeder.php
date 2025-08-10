<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

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

        // Define Permissions
        $permissions = [
            // Standard Permissions
            'user-list', 'user-create', 'user-edit', 'user-delete',
            'role-list', 'role-create', 'role-edit', 'role-delete',
            'permission-list', 'permission-create', 'permission-edit', 'permission-delete',
            // Patients & Appointments
            'patient-list', 'patient-create', 'patient-edit', 'patient-delete',
            'appointment-list', 'appointment-create', 'appointment-edit', 'appointment-delete',
            'treatment-plan-list', 'treatment-plan-create', 'treatment-plan-edit', 'treatment-plan-delete',
            'invoice-list', 'invoice-create', 'invoice-edit', 'invoice-delete',
            'procedure-list', 'procedure-create', 'procedure-edit', 'procedure-delete',
            // Inventory & Suppliers
            'inventory-list', 'inventory-create', 'inventory-edit', 'inventory-delete',
            'supplier-list', 'supplier-create', 'supplier-edit', 'supplier-delete',
            'report-list',
            'view_dashboard', // Single permission for the dashboard
        ];

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Define permissions for each role
        $receptionist_permissions = [
            'patient-list', 'patient-create', 'patient-edit',
            'appointment-list', 'appointment-create', 'appointment-edit',
            'invoice-list', 'invoice-create', 'invoice-edit',
            'view_dashboard',
        ];

        $dentist_permissions = [
            'patient-list', 'patient-create', 'patient-edit', 'patient-delete',
            'appointment-list', 'appointment-create', 'appointment-edit', 'appointment-delete',
            'treatment-plan-list', 'treatment-plan-create', 'treatment-plan-edit', 'treatment-plan-delete',
            'view_dashboard',
        ];

        // Create Roles and assign permissions
        $receptionist_role = Role::firstOrCreate(['name' => 'receptionist']);
        $receptionist_role->syncPermissions($receptionist_permissions);

        $dentist_role = Role::firstOrCreate(['name' => 'dentist']);
        $dentist_role->syncPermissions($dentist_permissions);

        // Administrator gets all permissions
        $admin_role = Role::firstOrCreate(['name' => 'administrator']);
        $admin_role->syncPermissions(Permission::all());

        // Viewer role
        $viewer_role = Role::firstOrCreate(['name' => 'viewer']);
        $viewer_role->syncPermissions(['patient-list', 'appointment-list']);

        // Create Demo Users if they don't exist
        if (User::where('email', 'superadmin@example.com')->doesntExist()) {
            $user = User::factory()->create([
                'name' => 'Super Admin',
                'email' => 'superadmin@example.com',
            ]);
            $user->assignRole($admin_role);
        }

        if (User::where('email', 'admin@example.com')->doesntExist()) {
            $user = User::factory()->create([
                'name' => 'Admin User',
                'email' => 'admin@example.com',
            ]);
            $user->assignRole($admin_role);
        }

        if (User::where('email', 'editor@example.com')->doesntExist()) {
            $user = User::factory()->create([
                'name' => 'Editor User',
                'email' => 'editor@example.com',
            ]);
            $user->assignRole($dentist_role);
        }

        if (User::where('email', 'viewer@example.com')->doesntExist()) {
            $user = User::factory()->create([
                'name' => 'Viewer User',
                'email' => 'viewer@example.com',
            ]);
            $user->assignRole($viewer_role);
        }
    }
}