<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use Illuminate\Http\Request;
use App\Traits\RedirectHelperTrait;
use App\Enums\RedirectType;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\PermissionRegistrar;


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

       
                return $this->redirectWithMessage(RedirectType::CREATE->value, 'admin.staff.index');

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

       
        // if ($request->has('permissions')) {
        //     $staff->syncPermissions($request->permissions);
        // }
        // else {
        //     $staff->syncPermissions([]);
        // }
                return $this->redirectWithMessage(RedirectType::UPDATE->value, 'admin.staff.index');

    }
	
    /**
 * Remove the specified staff from storage.
 */
public function destroy($id)
{
    $staff = \App\Models\Staff::findOrFail($id);
    
    // Don't allow deleting yourself
    // if ($staff->id == auth('admin')->id()) {
    //     return redirect()->back()->with('error', 'You cannot delete your own account!');
    // }

    // 🔥 Delete related data first - Proper order
    try {
        // Delete custom module permissions (HasMany relationship)
        $staff->staffPermissions()->delete();
        
        // Delete assigned jobs (HasMany relationship)
        $staff->assignedJobs()->delete();
        
        // Remove Spatie roles/permissions (pivot tables)
        // Some installations end up with a mis-resolved relationship type that throws:
        // "Call to undefined method Illuminate\Database\Eloquent\Relations\HasMany::detach()"
        // Deleting from pivots directly is safe and avoids relation-method mismatch.
        DB::table('model_has_roles')
            ->where('model_type', Staff::class)
            ->where('model_id', $staff->id)
            ->delete();

        DB::table('model_has_permissions')
            ->where('model_type', Staff::class)
            ->where('model_id', $staff->id)
            ->delete();

        app(PermissionRegistrar::class)->forgetCachedPermissions();
        
        // Then delete staff
        $staff->delete();
        
    return $this->redirectWithMessage(RedirectType::DELETE->value, 'admin.staff.index');

            
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Error deleting staff: ' . $e->getMessage());
    }
}

    public function changeStatus($id)
    {
        $staff = Staff::findOrFail($id);
        $staff->status = request('status');
        $staff->save();

        return response()->json(['success' => true]);
    }

}
