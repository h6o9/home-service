<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    //

	public function store(Request $request)
{
    try {
        // Validate request
        $validated = $request->validate([
            'shop_name' => 'required|string|max:255',
            'category' => 'required|string',
            'owner_name' => 'required|string|max:255',
            'phone' => 'required|string',
            'whatsapp' => 'nullable|string',
            'address' => 'required|string',
            'about' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'country' => 'nullable|string',
            'photos.*' => 'nullable|image|max:2048'
        ]);

        // Save shop to database
        // Handle photo uploads
        
        return response()->json([
            'success' => true,
            'message' => 'Shop added successfully'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}

}
