<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use Illuminate\Http\Request;

class StaffController extends Controller
{

    //
    public function index()
    {
        $staffs = \App\Models\Staff::latest()->paginate(15);
        return view('admin.staff.index', compact('staffs'));
    }

    public function create()
    {
        $permissions = [
            'add_shop' => 'Add Shop',
            'view_shop_list' => 'Shop List / Jobs'
        ];
        foreach ($permissions as $key => $name) {
            \Spatie\Permission\Models\Permission::firstOrCreate(['name' => $key, 'guard_name' => 'staff']);
        }
        return view('admin.staff.create', compact('permissions'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:staff,email',
            'phone' => 'required|string|max:20',
            'status' => 'required|boolean'
        ]);

        $password = 12345678;
        $staff = \App\Models\Staff::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'status' => $request->status,
            'password' => bcrypt($password),
        ]);

        if ($request->has('permissions')) {
            $staff->syncPermissions($request->permissions);
        }

        return redirect()->route('admin.staff.index')->with([
            'message' => 'Staff added successfully',
            'alert-type' => 'success'
        ]);
    }
    public function edit($id)
    {
        $staff = \App\Models\Staff::findOrFail($id);
        $permissions = [
            'add_shop' => 'Add Shop',
            'view_shop_list' => 'Shop List / Jobs'
        ];
        foreach ($permissions as $key => $name) {
            \Spatie\Permission\Models\Permission::firstOrCreate(['name' => $key, 'guard_name' => 'staff']);
        }
        $staffPermissions = $staff->permissions->pluck('name')->toArray();
        return view('admin.staff.edit', compact('staff', 'permissions', 'staffPermissions'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:staff,email,' . $id,
            'phone' => 'required|string|max:20',
            'status' => 'required|boolean'
        ]);

        $password = 12345678;
        $staff = \App\Models\Staff::findOrFail($id);
        $staff->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'status' => $request->status,
            'password' => bcrypt($password),
        ]);

        if ($request->has('permissions')) {
            $staff->syncPermissions($request->permissions);
        }
        else {
            $staff->syncPermissions([]);
        }

        return redirect()->route('admin.staff.index')->with([
            'message' => 'Staff updated successfully',
            'alert-type' => 'success'
        ]);
    }
    public function destroy($id)
    {
        $staff = \App\Models\Staff::findOrFail($id);
        $staff->delete();

        return redirect()->route('admin.staff.index')->with([
            'message' => 'Staff deleted successfully',
            'alert-type' => 'success'
        ]);

    }

    public function changeStatus($id)
    {
        $staff = Staff::findOrFail($id);
        $staff->status = request('status');
        $staff->save();

        return response()->json(['success' => true]);
    }

}
