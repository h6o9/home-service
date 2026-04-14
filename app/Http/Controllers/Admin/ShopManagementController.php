<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\Staff;
use App\Models\StaffJob;
use App\Models\ShopCategory;
use Illuminate\Http\Request;
use App\Enums\RedirectType;
use App\Traits\RedirectHelperTrait;

class ShopManagementController extends Controller
{
    use RedirectHelperTrait;
    
    public function index()
    {
        // Get only staff members who have shop_management permissions with permissable checked
        $allStaff = Staff::where('is_active', true)
            ->whereHas('staffPermissions', function($query) {
                $query->where('module', 'shop_management')
                      ->where('permissable', true);
            })
            ->get();
            
        $shops = Shop::with(['staff', 'jobs.assignedTo'])->latest()->paginate(10);
        return view('admin.shop-management.index', compact('shops', 'allStaff'));
    }

    public function show($id)
    {
        $shop = Shop::with(['staff', 'jobs.assignedTo', 'jobs.assignedBy'])->findOrFail($id);
        
        // Get only staff members who have shop_management permissions with permissable checked
        $allStaff = Staff::where('is_active', true)
            ->whereHas('staffPermissions', function($query) {
                $query->where('module', 'shop_management')
                      ->where('permissable', true);
            })
            ->get();
            
        $jobTypes = StaffJob::$jobTypes;
        
        return view('admin.shop-management.show', compact('shop', 'allStaff', 'jobTypes'));
    }

    public function assignJob(Request $request, $shopId)
    {
        $request->validate([
            'assigned_to' => 'required|exists:staff,id',
            'description' => 'nullable|string',
            'scheduled_date' => 'nullable|date',
            'scheduled_time' => 'nullable|date_format:H:i',
            'notes' => 'nullable|string',
        ]);

        $shop = Shop::findOrFail($shopId);
        
        StaffJob::create([
            'shop_id' => $shop->id,
            'assigned_by' => auth('admin')->id(),
            'assigned_to' => $request->assigned_to,
            'job_type' => 'general', // Default job type
            'description' => $request->description,
            'scheduled_date' => $request->scheduled_date,
            'scheduled_time' => $request->scheduled_time,
            'notes' => $request->notes,
            'status' => 'pending',
        ]);

        return $this->redirectWithMessage(RedirectType::UPDATE->value);
    }

    public function getStaffWithPermissions(Request $request)
    {
        $module = $request->module;
        $action = $request->action ?? 'can_view';
        
        $staff = Staff::where('is_active', true)
            ->whereHas('permissions', function($query) use ($module, $action) {
                $query->where('module', $module)->where($action, true);
            })
            ->get();

        return response()->json($staff);
    }

    public function Shopindex() {
        $categories = ShopCategory::latest()->paginate(10);
        return view('admin.shop-management.categories', compact('categories'));
    }

  public function store(Request $request)
{
    $request->validate([
        'name' => 'required|unique:shop_categories|max:255',
        'is_active' => 'required|boolean',
    ]);

    ShopCategory::create([
        'name' => $request->name,
        'is_active' => $request->is_active
    ]);

    return response()->json(['message' => 'Category created successfully!']);
}

// Status Update
public function updateStatus($id)
{
    $category = ShopCategory::findOrFail($id);
    $category->is_active = !$category->is_active;
    $category->save();
    
    $status = $category->is_active ? 'activated' : 'deactivated';
    return response()->json(['message' => "Status updated successfully"]);
}

// Delete
public function destroy($id)
{
    $category = ShopCategory::findOrFail($id);
    
    $category->delete();
    return response()->json(['message' => 'Deleted successfully!']);
}

    
}
