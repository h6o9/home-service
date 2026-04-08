<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class AssignRoleController extends Controller
{
    /**
     * Display the assign roles page.
     */
    public function index()
    {
        // Check if admin has permission to assign roles
        if (!auth('admin')->user()->can('role.assign')) {
            abort(403, 'You do not have permission to assign roles.');
        }

        $admins = Admin::where('is_super_admin', 0)->get(); // Get sub-admins only
        $roles = Role::where('guard_name', 'admin')->get();

        return view('admin.assign-roles.index', compact('admins', 'roles'));
    }

    /**
     * Assign role to admin.
     */
    public function assign(Request $request)
    {
        // Check if admin has permission to assign roles
        if (!auth('admin')->user()->can('role.assign')) {
            abort(403, 'You do not have permission to assign roles.');
        }

        $request->validate([
            'admin_id' => 'required|exists:admins,id',
            'role_id' => 'required|exists:roles,id',
        ]);

        $admin = Admin::findOrFail($request->admin_id);
        $role = Role::findOrFail($request->role_id);

        // Remove all existing roles and assign new one
        $admin->syncRoles([$role]);

        return redirect()->route('admin.assign-roles.index')
            ->with('success', 'Role assigned successfully to ' . $admin->name);
    }

    /**
     * Get admin roles (AJAX endpoint).
     */
    public function getAdminRoles(Admin $admin)
    {
        // Check if admin has permission to assign roles
        if (!auth('admin')->user()->can('role.assign')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $role = $admin->roles->first();
        $roleId = $role ? $role->id : null;

        return response()->json([$roleId]);
    }
}
