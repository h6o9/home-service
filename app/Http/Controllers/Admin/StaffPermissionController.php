<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\StaffPermission;
use Illuminate\Http\Request;
use App\Enums\RedirectType;
use App\Traits\RedirectHelperTrait;



class StaffPermissionController extends Controller
{
        use RedirectHelperTrait;

    public function index()
    {
        $staff = Staff::with('permissions')->get();
        $modules = StaffPermission::$modules;        
        return view('admin.staff-permissions.index', compact('staff', 'modules'));
    }

    public function edit($id)
    {
        $staff = Staff::with('permissions')->findOrFail($id);
        $modules = StaffPermission::$modules;
        
        return view('admin.staff-permissions.edit', compact('staff', 'modules'));
    }

    public function update(Request $request, $id)
    {
        $staff = Staff::findOrFail($id);
        
        foreach (StaffPermission::$modules as $moduleKey => $moduleName) {
            StaffPermission::updateOrCreate(
                [
                    'staff_id' => $staff->id,
                    'module' => $moduleKey,
                ],
                [
                    'can_view' => $request->input("permissions.{$moduleKey}.can_view", false),
                    'can_create' => $request->input("permissions.{$moduleKey}.can_create", false),
                    'can_edit' => $request->input("permissions.{$moduleKey}.can_edit", false),
                    'can_delete' => $request->input("permissions.{$moduleKey}.can_delete", false),
                    'permissable' => $request->input("permissions.{$moduleKey}.permissable", false),
                ]
            );

             
        }

        return $this->redirectWithMessage(RedirectType::UPDATE->value, 'admin.staff-permissions.index');
    }

    public function show($id)
    {
        $staff = Staff::with('permissions')->findOrFail($id);
        $modules = StaffPermission::$modules;
        
        return view('admin.staff-permissions.show', compact('staff', 'modules'));
    }
}
