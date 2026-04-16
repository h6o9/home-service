<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\StaffJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobController extends Controller
{
    public function index()
    {
        // Check if staff has permission to view jobs
        $staff = Auth::guard('staff')->user();
        if (!$staff->hasPermission('my_jobs', 'can_view')) {
            abort(403, 'Unauthorized action.');
        }
        
        $jobs = StaffJob::with(['shop', 'assignedBy'])
            ->where('assigned_to', Auth::guard('staff')->id())
            ->orderByRaw("CASE WHEN status = 'pending' THEN 0 ELSE 1 END")
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        return view('staff.jobs.index', compact('jobs'));
    }
    
    public function markAsDone($id)
    {
        // Check if staff has permission to edit jobs
        $staff = Auth::guard('staff')->user();
        if (!$staff->hasPermission('my_jobs', 'can_edit')) {
            abort(403, 'Unauthorized action.');
        }
        
        $job = StaffJob::where('assigned_to', Auth::guard('staff')->id())
            ->findOrFail($id);
            
        $job->status = 'done';
        $job->save();
        
        return response()->json([
            'message' => 'Job marked as done successfully!',
            'status' => $job->status
        ]);
    }
    
    public function markAsUndone($id)
    {
        // Check if staff has permission to edit jobs
        $staff = Auth::guard('staff')->user();
        if (!$staff->hasPermission('my_jobs', 'can_edit')) {
            abort(403, 'Unauthorized action.');
        }
        
        $job = StaffJob::where('assigned_to', Auth::guard('staff')->id())
            ->findOrFail($id);
            
        $job->status = 'pending';
        $job->save();
        
        return response()->json([
            'message' => 'Job marked as pending successfully!',
            'status' => $job->status
        ]);
    }
    
    public function show($id)
    {
        // Check if staff has permission to view jobs
        $staff = Auth::guard('staff')->user();
        if (!$staff->hasPermission('my_jobs', 'can_view')) {
            abort(403, 'Unauthorized action.');
        }
        
        $job = StaffJob::with(['shop', 'assignedBy'])
            ->where('assigned_to', Auth::guard('staff')->id())
            ->findOrFail($id);
            
        return view('staff.jobs.show', compact('job'));
    }
}
