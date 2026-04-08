<?php

namespace App\Http\Controllers\Admin;

use App\Enums\RedirectType;
use App\Http\Controllers\Controller;
use App\Http\Requests\RoleFormRequest;
use App\Models\Admin;
use App\Traits\RedirectHelperTrait;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesController extends Controller
{
    use RedirectHelperTrait;

    public function index()
    {
        checkAdminHasPermissionAndThrowException('role.view');
        $roles = Role::where('name', '!=', 'Super Admin')->paginate(15);
        $admins_exists = Admin::notSuperAdmin()->whereStatus('active')->count();

        return view('admin.roles.index', compact('roles', 'admins_exists'));
    }

    public function create()
    {
        checkAdminHasPermissionAndThrowException('role.create');
        $permissions = Permission::all();
        $permission_groups = Admin::getPermissionGroupsWithPermissions();

        return view('admin.roles.create', compact('permissions', 'permission_groups'));
    }

    public function store(RoleFormRequest $request)
    {
        checkAdminHasPermissionAndThrowException('role.store');
        $role = Role::create(['name' => $request->name]);
        if (! empty($request->permissions)) {
            $role->syncPermissions($request->permissions);
        }

        return $this->redirectWithMessage(RedirectType::CREATE->value, 'admin.role.index');
    }

    public function edit($id)
    {
        checkAdminHasPermissionAndThrowException('role.edit');
        $role = Role::where('name', '!=', 'Super Admin')->where('id', $id)->first();
        abort_if(! $role, 403);
        $permissions = Permission::all();
        $permission_groups = Admin::getPermissionGroupsWithPermissions();

        return view('admin.roles.edit', compact('permissions', 'permission_groups', 'role'));
    }

    public function update(RoleFormRequest $request, $id)
    {
        checkAdminHasPermissionAndThrowException('role.update');
        $role = Role::where('name', '!=', 'Super Admin')->where('id', $id)->first();
        abort_if(! $role, 403);
        if (! empty($request->permissions)) {
            $role->name = $request->name;
            $role->save();
            $role->syncPermissions($request->permissions);
        }

        return $this->redirectWithMessage(RedirectType::UPDATE->value, 'admin.role.index');
    }

    public function show($id)
    {
        checkAdminHasPermissionAndThrowException('role.view');
        $role = Role::where('name', '!=', 'Super Admin')->where('id', $id)->first();
        abort_if(! $role, 403);
        
        return view('admin.roles.show', compact('role'));
    }

    public function destroy($id)
    {
        checkAdminHasPermissionAndThrowException('role.delete');
        $role = Role::where('name', '!=', 'Super Admin')->where('id', $id)->first();
        abort_if(! $role, 403);
        if (! is_null($role)) {
            $role->delete();
        }

        return $this->redirectWithMessage(RedirectType::DELETE->value, 'admin.role.index');
    }

    public function assignRoleView()
    {
        // checkAdminHasPermissionAndThrowException('role.assign');
        $admins = Admin::notSuperAdmin()->whereStatus('active')->get();
        $roles = Role::where('name', '!=', 'Super Admin')->get();

        return view('admin.roles.assign-role', compact('admins', 'roles'));
    }

    public function getAdminRoles($id)
    {
        $role = Role::find($id);
        if ($role) {
            $permissions = $role->permissions->pluck('name')->toArray();
            
            return response()->json([
                'success' => true,
                'permissions' => $permissions,
            ]);
        }

        return response()->json([
            'success' => false,
            'data' => $options,
        ]);
    }

    public function assignRoleUpdate(Request $request)
    {
        // checkAdminHasPermissionAndThrowException('role.assign');

        $messages = [
            'user_id.required' => __('You must select a role'),
            'user_id.exists' => __('Role not found'),
            'permissions.array' => __('You must select permissions'),
            'permissions.*.required' => __('You must select permissions'),
            'permissions.*.string' => __('You must select permissions'),
        ];

        $request->validate([
            'user_id' => 'required|exists:roles,id',
            'permissions' => 'nullable|array',
            'permissions.*' => 'nullable|string',
        ], $messages);

        $role = Role::findOrFail($request->user_id);
        
        // Sync permissions to the role
        if ($request->has('permissions') && is_array($request->permissions)) {
            $permissions = array_filter($request->permissions);
            $role->syncPermissions($permissions);
        } else {
            $role->syncPermissions([]);
        }

        return $this->redirectWithMessage(RedirectType::UPDATE->value, 'admin.role.index');
    }
}
