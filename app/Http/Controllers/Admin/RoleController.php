<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    protected $permissions = [
        'manage_services',
        'manage_customers',
        'manage_orders',
        'view_orders',
        'update_order_status',
        'view_reports',
        'manage_blog',
        'view_own_profile',
    ];

    public function index()
    {
        $roles = Role::withCount('users')->orderBy('created_at', 'desc')->get();
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = $this->permissions;
        return view('admin.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
        ]);

        $permissions = $request->permissions ?? [];

        Role::create([
            'name' => $request->name,
            'permissions' => $permissions,
        ]);

        return redirect()->route('admin.roles.index')->with('success', 'Role berhasil dibuat.');
    }

    public function edit(Role $role)
    {
        $permissions = $this->permissions;
        return view('admin.roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
        ]);

        $permissions = $request->permissions ?? [];

        $role->update([
            'name' => $request->name,
            'permissions' => $permissions,
        ]);

        return redirect()->route('admin.roles.index')->with('success', 'Role berhasil diubah.');
    }

    public function destroy(Role $role)
    {
        if ($role->users()->count() > 0) {
            return redirect()->back()->with('error', 'Tidak bisa menghapus role yang masih memiliki user.');
        }

        if (in_array('all', $role->permissions ?? [])) {
            return redirect()->back()->with('error', 'Tidak bisa menghapus role Administrator / Super Admin.');
        }

        $role->delete();
        return redirect()->route('admin.roles.index')->with('success', 'Role berhasil dihapus.');
    }
}
