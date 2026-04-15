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
            
        // Get all jobs ordered by status (pending first, done last)
        $jobs = StaffJob::with(['shop', 'assignedTo', 'assignedBy'])
            ->orderByRaw("CASE WHEN status = 'pending' THEN 0 ELSE 1 END")
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        return view('admin.shop-management.index', compact('jobs', 'allStaff'));
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

        return response()->json(['message' => 'Job assigned successfully!']);
    }

    public function shopList()
    {
        // Get only staff members who have shop_management permissions with permissable checked
        $allStaff = Staff::where('is_active', true)
            ->whereHas('staffPermissions', function($query) {
                $query->where('module', 'shop_management')
                      ->where('permissable', true);
            })
            ->get();
            
        // Get all shops with their relationships
        $shops = Shop::with(['staff', 'jobs.assignedTo'])->latest()->paginate(10);
            
        return view('admin.shop-management.shopindex', compact('shops', 'allStaff'));
    }

    public function jobDetails(Request $request)
    {
        $job = StaffJob::with(['shop', 'assignedTo', 'assignedBy'])->findOrFail($request->id);
        
        $html = '
            <div class="row">
                <div class="col-md-6">
                    <h6>' . __('Shop Information') . '</h6>
                    <p><strong>' . __('Shop Name') . ':</strong> ' . $job->shop->shop_name . '</p>
                    <p><strong>' . __('Owner Name') . ':</strong> ' . $job->shop->owner_name . '</p>
                    <p><strong>' . __('Phone') . ':</strong> ' . $job->shop->phone_number . '</p>
                    <p><strong>' . __('Address') . ':</strong> ' . $job->shop->address . '</p>
                </div>
                <div class="col-md-6">
                    <h6>' . __('Job Information') . '</h6>
                    <p><strong>' . __('Assigned To') . ':</strong> ' . $job->assignedTo->name . '</p>
                    <p><strong>' . __('Assigned By') . ':</strong> ' . $job->assignedBy->name . '</p>
                    <p><strong>' . __('Status') . ':</strong> <span class="badge badge-' . ($job->status == 'pending' ? 'warning' : 'success') . '">' . ucfirst($job->status) . '</span></p>
                    <p><strong>' . __('Created At') . ':</strong> ' . $job->created_at->format('Y-m-d H:i') . '</p>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12">
                    <h6>' . __('Description') . '</h6>
                    <p>' . ($job->description ?: 'No description provided') . '</p>
                </div>
            </div>';
            
        if($job->scheduled_date) {
            $html .= '
            <div class="row mt-3">
                <div class="col-12">
                    <h6>' . __('Schedule') . '</h6>
                    <p><strong>' . __('Date') . ':</strong> ' . $job->scheduled_date . '</p>';
            if($job->scheduled_time) {
                $html .= '<p><strong>' . __('Time') . ':</strong> ' . $job->scheduled_time . '</p>';
            }
            $html .= '</div>
            </div>';
        }
        
        return response()->json(['html' => $html]);
    }

    public function jobNotes(Request $request)
    {
        $job = StaffJob::findOrFail($request->id);
        
        $html = '
            <div class="row">
                <div class="col-12">
                    <p>' . ($job->notes ?: 'No additional notes provided') . '</p>
                </div>
            </div>';
        
        return response()->json(['html' => $html]);
    }

    public function toggleJobStatus($id)
    {
        $job = StaffJob::findOrFail($id);
        $job->status = $job->status == 'pending' ? 'done' : 'pending';
        $job->save();
        
        return response()->json([
            'message' => 'Job status updated successfully!',
            'status' => $job->status
        ]);
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
