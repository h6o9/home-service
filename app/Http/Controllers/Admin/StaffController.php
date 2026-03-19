<?php

namespace App\Http\Controllers\Admin;

use App\Enums\RedirectType;
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
		return view('admin.staff.create');
	}

public function store(Request $request)
{
    $request->validate([
        'name'  => 'required|string|max:255',
        'email' => 'required|email|unique:staff,email',
        'phone' => 'required|string|max:20'
    ]);

	$password = 12345678;
    \App\Models\Staff::create([
        'name'  => $request->name,
        'email' => $request->email,
        'phone' => $request->phone,
		'password' => bcrypt($password),
    ]);

return redirect('admin/staff')->with('success', 'Staff added successfully');
}

public function edit($id)
{
	$staff = \App\Models\Staff::findOrFail($id);
	return view('admin.staff.edit', compact('staff'));
}

public function update(Request $request, $id)
{
	$request->validate([
		'name'  => 'required|string|max:255',
		'email' => 'required|email|unique:staff,email,' . $id,
		'phone' => 'required|string|max:20'
	]);

	$password = 12345678;
	$staff = \App\Models\Staff::findOrFail($id);
	$staff->update([
		'name'  => $request->name,
		'email' => $request->email,
		'phone' => $request->phone,
		'password' => bcrypt($password),
	]);

return $this->redirectWithMessage(
    'Staff ' . RedirectType::UPDATE->value,
    'admin.staff.index'
);}

public function destroy($id)
    {
        $staff = \App\Models\Staff::findOrFail($id);
        $staff->delete();

        return redirect()->route('admin.staff.index')->with('success','Staff deleted successfully');
    }

}
