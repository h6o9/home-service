<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\District;
use Illuminate\Http\Request;

class DistrictController extends Controller
{
    /**
     * Display a listing of the districts.
     */
    public function index()
    {
        if (!auth('admin')->user()->hasPermissionTo('district.view')) {
        abort(403, 'Access Denied. You do not have permission.');
    }
        $districts = District::latest()->paginate(10);
        return view('admin.districts.index', compact('districts'));
    }

    /**
     * Show the form for creating a new district.
     */
    public function create()
    {
        return view('admin.districts.create');
    }

    /**
     * Store a newly created district in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:districts,name',
            'status' => 'required|in:active,inactive'
        ], [
            'name.required' => 'District name is required',
            'name.unique' => 'This district name already exists',
            'status.required' => 'Status is required'
        ]);

        District::create([
            'name' => $request->name,
            'status' => $request->status
        ]);

        return redirect()->route('admin.districts.index')
            ->with('success', 'District created successfully.');
    }

    /**
     * Display the specified district.
     */
    public function show($id)
    {
        $district = District::findOrFail($id);
        return view('admin.districts.show', compact('district'));
    }

    /**
     * Show the form for editing the specified district.
     */
    public function edit($id)
    {
        $district = District::findOrFail($id);
        return view('admin.districts.edit', compact('district'));
    }

    /**
     * Update the specified district in storage.
     */
    public function update(Request $request, $id)
    {
        $district = District::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:100|unique:districts,name,' . $id,
            'status' => 'required|in:active,inactive'
        ], [
            'name.required' => 'District name is required',
            'name.unique' => 'This district name already exists',
            'status.required' => 'Status is required'
        ]);

        $district->update([
            'name' => $request->name,
            'status' => $request->status
        ]);

        return redirect()->route('admin.district.index')
            ->with('success', 'District updated successfully.');
    }

    /**
     * Remove the specified district from storage.
     */
    public function destroy($id)
    {
        try {
            $district = District::findOrFail($id);

            $district->delete();

            return response()->json([
                'success' => true,
                'message' => 'District deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting district: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Change the status of the specified district.
     */
    public function changeStatus($id, Request $request)
    {
        try {
            $district = District::findOrFail($id);
            $district->status = $request->status;
            $district->save();

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating status: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Bulk delete districts.
     */
    public function bulkDelete(Request $request)
    {
        try {
            $ids = $request->ids;
            District::whereIn('id', $ids)->delete();

            return response()->json([
                'success' => true,
                'message' => 'Districts deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting districts: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get all active districts (for API/AJAX).
     */
    public function getActiveDistricts()
    {
        $districts = District::where('status', 'active')->get();
        return response()->json([
            'success' => true,
            'data' => $districts
        ]);
    }

    /**
     * Search districts.
     */
    public function search(Request $request)
    {
        $search = $request->search;
        $districts = District::where('name', 'like', '%' . $search . '%')
            ->paginate(10);

        if ($request->ajax()) {
            return view('admin.districts.partials.districts_table', compact('districts'))->render();
        }

        return view('admin.districts.index', compact('districts'));
    }
}