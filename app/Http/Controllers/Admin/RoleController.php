<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    public function index()
    {
        abort_if(!auth()->user()->hasPermission('roles.view'), 403);
        $roles = Role::withCount('users', 'permissions')->get();
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        abort_if(!auth()->user()->hasPermission('roles.update'), 403); // Assuming update permission covers create for roles for now, or roles.create
        $permissions = Permission::all()->groupBy('module');
        return view('admin.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        abort_if(!auth()->user()->hasPermission('roles.update'), 403);
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles,name'],
            'display_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,id'],
        ]);

        $role = Role::create([
            'name' => $validated['name'],
            'display_name' => $validated['display_name'],
            'description' => $validated['description'] ?? null,
            'is_system' => false,
        ]);

        if (isset($validated['permissions'])) {
            $role->permissions()->sync($validated['permissions']);
        }

        return redirect()->route('admin.roles.index')->with('success', 'Role dan Hak Akses berhasil dibuat.');
    }

    public function edit(Role $role)
    {
        abort_if(!auth()->user()->hasPermission('roles.update'), 403);
        
        $permissions = Permission::all()->groupBy('module');
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        abort_if(!auth()->user()->hasPermission('roles.update'), 403);
        
        $validated = $request->validate([
            'display_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,id'],
        ]);

        // Note: is_system roles usually shouldn't have their internal 'name' changed to prevent hardcode breaks, 
        // so we only update display_name and description, or we allow it if it's not a system role.
        
        $roleData = [
            'display_name' => $validated['display_name'],
            'description' => $validated['description'] ?? null,
        ];
        
        if (!$role->is_system) {
            $request->validate(['name' => ['required', 'string', 'max:255', Rule::unique('roles')->ignore($role->id)]]);
            $roleData['name'] = $request->name;
        }

        $role->update($roleData);

        if (isset($validated['permissions'])) {
            $role->permissions()->sync($validated['permissions']);
        } else {
            $role->permissions()->detach();
        }

        return redirect()->route('admin.roles.index')->with('success', 'Role dan Hak Akses berhasil diperbarui.');
    }

    public function destroy(Role $role)
    {
        abort_if(!auth()->user()->hasPermission('roles.update'), 403); // Assuming update permission covers delete
        
        if ($role->is_system) {
            return back()->with('error', 'System role tidak dapat dihapus.');
        }

        if ($role->users()->exists()) {
            return back()->with('error', 'Role masih digunakan oleh pengguna.');
        }

        $role->delete();

        return redirect()->route('admin.roles.index')->with('success', 'Role berhasil dihapus.');
    }
}
