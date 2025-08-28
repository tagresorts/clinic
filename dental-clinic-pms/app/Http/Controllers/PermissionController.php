<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Log;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::all()->groupBy(function ($permission) {
            $parts = explode('-', $permission->name);
            return ucwords(str_replace('-', ' ', $parts[0]));
        });

        return view('permissions.index', compact('permissions'));
    }

    public function create()
    {
        return view('permissions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name',
        ]);

        $permission = Permission::create(['name' => $request->name]);

        Log::channel('log_viewer')->info("Permission '{$permission->name}' created by " . auth()->user()->name, [
            'permission_id' => $permission->id
        ]);

        return redirect()->route('permissions.index')->with('success', 'Permission created successfully.');
    }

    public function edit(Permission $permission)
    {
        return view('permissions.edit', compact('permission'));
    }

    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name,' . $permission->id,
        ]);

        $oldName = $permission->name;
        $permission->update(['name' => $request->name]);

        Log::channel('log_viewer')->info("Permission '{$oldName}' updated by " . auth()->user()->name, [
            'permission_id' => $permission->id,
            'old_name' => $oldName,
            'new_name' => $request->name
        ]);

        return redirect()->route('permissions.index')->with('success', 'Permission updated successfully.');
    }

    public function destroy(Permission $permission)
    {
        $permissionName = $permission->name;
        $permission->delete();

        Log::channel('log_viewer')->info("Permission '{$permissionName}' deleted by " . auth()->user()->name, [
            'permission_id' => $permission->id
        ]);

        return redirect()->route('permissions.index')->with('success', 'Permission deleted successfully.');
    }
}
