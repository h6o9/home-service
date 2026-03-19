<?php

namespace App\Http\Controllers\Admin;

use App\Enums\RedirectType;
use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Traits\RedirectHelperTrait;
use Illuminate\Http\Request;

class StaffController extends Controller
{
	use RedirectHelperTrait;
	
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

        return $this->redirectWithMessage(RedirectType::CREATE->value, 'admin.staff.index');
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

        return $this->redirectWithMessage(RedirectType::UPDATE->value, 'admin.staff.index');

}

public function destroy($id)
    {
        $staff = \App\Models\Staff::findOrFail($id);
        $staff->delete();

    return $this->redirectWithMessage(RedirectType::DELETE->value, 'admin.staff.index');

    }

	public function status(Request $request)
{
    $staff = Staff::findOrFail($request->id);

    $staff->is_active = !$staff->is_active;
    $staff->save();

    return response()->json([
        'success' => true,
        'message' => 'Status updated successfully'
    ]);
}

}
