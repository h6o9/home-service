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
     * Assign roles to admin.
     */
    public function assign(Request $request)
    {
        // Check if admin has permission to assign roles
        if (!auth('admin')->user()->can('role.assign')) {
            abort(403, 'You do not have permission to assign roles.');
        }

        $request->validate([
            'admin_id' => 'required|exists:admins,id',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
        ]);

        $admin = Admin::findOrFail($request->admin_id);
        $roles = Role::whereIn('id', $request->roles)->get();

        // Remove all existing roles and assign new ones
        $admin->syncRoles($roles);

        return redirect()->route('admin.assign-roles.index')
            ->with('success', 'Roles assigned successfully to ' . $admin->name);
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

        $roles = $admin->roles->pluck('id')->toArray();

        return response()->json($roles);
    }
}
