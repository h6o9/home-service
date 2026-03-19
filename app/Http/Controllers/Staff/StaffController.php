<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Staff;

class StaffController extends Controller
{
    public function index()
    {
        $staffs = Staff::latest()->paginate(15);
        return view('admin.staff.index', compact('staffs'));
    }

    public function create()
    {
        return view('admin.staff.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:staffs,email',
            'phone' => 'required|string|max:20|unique:staffs,phone'
        ]);

        Staff::create([
            'name'  => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        return redirect()->route('admin.staff.index')->with('success','Staff added successfully');
    }

    public function edit($id)
    {
        $staff = Staff::findOrFail($id);
        return view('admin.staff.edit', compact('staff'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:staffs,email,' . $id,
            'phone' => 'required|string|max:20|unique:staffs,phone,' . $id
        ]);

        $staff = Staff::findOrFail($id);
        $staff->update([
            'name'  => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        return redirect()->route('admin.staff.index')->with('success','Staff updated successfully');
    }

    public function destroy($id)
    {
        $staff = Staff::findOrFail($id);
        $staff->delete();

        return redirect()->route('admin.staff.index')->with('success','Staff deleted successfully');
    }
}
