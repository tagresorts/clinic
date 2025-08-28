<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Log;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::all();
        return view('roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role = Role::create(['name' => $request->name]);
        $role->givePermissionTo($request->permissions);

        Log::channel('log_viewer')->info("Role '{$role->name}' created by " . auth()->user()->name, [
            'role_id' => $role->id,
            'permissions' => $request->permissions ?? []
        ]);

        return redirect()->route('roles.index')->with('success', 'Role created successfully.');
    }

    public function edit(Role $role)
    {
        $permissions = Permission::all();
        $rolePermissions = $role->permissions->pluck('name')->toArray();
        return view('roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id,
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $oldName = $role->name;
        $oldPermissions = $role->permissions->pluck('name')->toArray();
        
        $role->update(['name' => $request->name]);
        $role->syncPermissions($request->permissions);

        Log::channel('log_viewer')->info("Role '{$oldName}' updated by " . auth()->user()->name, [
            'role_id' => $role->id,
            'old_name' => $oldName,
            'new_name' => $request->name,
            'old_permissions' => $oldPermissions,
            'new_permissions' => $request->permissions ?? []
        ]);

        return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        $roleName = $role->name;
        $role->delete();
        
        Log::channel('log_viewer')->info("Role '{$roleName}' deleted by " . auth()->user()->name, [
            'role_id' => $role->id
        ]);
        
        return redirect()->route('roles.index')->with('success', 'Role deleted successfully.');
    }
}
