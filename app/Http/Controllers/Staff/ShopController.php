<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\ShopPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Traits\RedirectHelper;
use App\Enums\RedirectType;
use App\Traits\RedirectHelperTrait;



class ShopController extends Controller
{
    use RedirectHelperTrait;
    
    /**
     * Display a listing of the shops.
     */
    public function index()
    {
        // Check if staff has permission to view shop list
        if (!auth('staff')->user()->hasPermission('shop_management', 'can_view')) {
            abort(403, 'You do not have permission to view shop list.');
        }

        $shops = Shop::with('photos')->latest()->paginate(10);
        return view('staff.shop.index', compact('shops'));
    }

    /**
     * Show the form for creating a new shop.
     */
    public function create()
    {
        // Check if staff has permission to create shops
        if (!auth('staff')->user()->hasPermission('shop_management', 'can_create')) {
            abort(403, 'You do not have permission to create shops.');
        }

        return view('staff.shop.create');
    }

    /**
     * Store a newly created shop in storage.
     */
    public function store(Request $request)
    {
        // Check if staff has permission to create shops
        if (!auth('staff')->user()->hasPermission('shop_management', 'can_create')) {
            abort(403, 'You do not have permission to create shops.');
        }

        // Validation rules
        $validated = $request->validate([
            'shop_name' => 'required|string|max:255',
            'category' => 'required|in:electrician,wifi_controller,solar,plumber',
            'owner_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'whatsapp_number' => 'required|string|max:20',
            'address' => 'required|string',
            'about_shop' => 'required|string',
            'shop_photos' => 'nullable|array',
            'shop_photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Create shop record
        $shop = Shop::create([
            'shop_name' => $validated['shop_name'],
            'category' => $validated['category'],
            'owner_name' => $validated['owner_name'],
            'phone_number' => $validated['phone_number'],
            'whatsapp_number' => $validated['whatsapp_number'],
            'address' => $validated['address'],
            'about_shop' => $validated['about_shop'],
            'slug' => Str::slug($validated['shop_name']) . '-' . uniqid(),
            'staff_id' => auth('staff')->id(), // Add staff_id
        ]);

        // Handle multiple photos upload
        if ($request->hasFile('shop_photos')) {
            foreach ($request->file('shop_photos') as $photo) {
                // Store photo with proper error handling
                try {
                    // Create public directory if it doesn't exist
                    $publicPath = public_path('storage/shop-photos');
                    if (!file_exists($publicPath)) {
                        mkdir($publicPath, 0755, true);
                    }
                    
                    // Generate unique filename
                    $filename = 'shop_' . $shop->id . '_' . time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
                    
                    // Move file to public directory
                    if ($photo->move($publicPath, $filename)) {
                        // Create photo record with public path
                        ShopPhoto::create([
                            'shop_id' => $shop->id,
                            'photo_path' => 'shop-photos/' . $filename,
                            'is_primary' => false,
                        ]);
                    }
                } catch (\Exception $e) {
                    // Log error but continue with other photos
                    \Log::error('Photo upload failed: ' . $e->getMessage());
                }
            }

            // Set the first photo as primary
            if ($shop->photos()->count() > 0) {
                $firstPhoto = $shop->photos()->first();
                $firstPhoto->update(['is_primary' => true]);
            }
        }

        // Check if request is AJAX (for dashboard modal)
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Created successfully!',
                'shop' => $shop
            ]);
        }

        // Redirect to staff shop index (not admin)
        return $this->redirectWithMessage(RedirectType::CREATE->value, 'staff.shop.index');
    }

    /**
     * Display the specified shop.
     */
    public function show($id)
    {
        $shop = Shop::with('photos')->findOrFail($id);
        return view('staff.shop.show', compact('shop'));
    }

    /**
     * Show the form for editing the specified shop.
     */
    public function edit($id)
    {
        // Check edit permission
        if (!auth('staff')->user()->hasPermission('shop_management', 'can_edit')) {
            abort(403, 'You do not have permission to edit shops.');
        }
        
        $shop = Shop::with('photos')->findOrFail($id);
        return view('staff.shop.edit', compact('shop'));
    }

    /**
     * Update the specified shop in storage.
     */
    public function update(Request $request, $id)
    {
        // Check edit permission
        if (!auth('staff')->user()->hasPermission('shop_management', 'can_edit')) {
            abort(403, 'You do not have permission to edit shops.');
        }
        
        $shop = Shop::findOrFail($id);

        $validated = $request->validate([
            'shop_name' => 'required|string|max:255',
            'category' => 'required|in:electrician,wifi_controller,solar,plumber',
            'owner_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'whatsapp_number' => 'required|string|max:20',
            'address' => 'required|string',
            'about_shop' => 'required|string',
            'shop_photos' => 'nullable|array',
            'shop_photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $shop->update([
            'shop_name' => $validated['shop_name'],
            'category' => $validated['category'],
            'owner_name' => $validated['owner_name'],
            'phone_number' => $validated['phone_number'],
            'whatsapp_number' => $validated['whatsapp_number'],
            'address' => $validated['address'],
            'about_shop' => $validated['about_shop'],
            'slug' => Str::slug($validated['shop_name']) . '-' . uniqid(),
        ]);

        // Handle new photos upload
        if ($request->hasFile('shop_photos')) {
            foreach ($request->file('shop_photos') as $photo) {
                // Store photo with proper error handling
                try {
                    // Create public directory if it doesn't exist
                    $publicPath = public_path('storage/shop-photos');
                    if (!file_exists($publicPath)) {
                        mkdir($publicPath, 0755, true);
                    }
                    
                    // Generate unique filename
                    $filename = 'shop_' . $shop->id . '_' . time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
                    
                    // Move file to public directory
                    if ($photo->move($publicPath, $filename)) {
                        ShopPhoto::create([
                            'shop_id' => $shop->id,
                            'photo_path' => 'shop-photos/' . $filename,
                            'is_primary' => false,
                        ]);
                    }
                } catch (\Exception $e) {
                    // Log error but continue with other photos
                    \Log::error('Photo upload failed: ' . $e->getMessage());
                }
            }
        }

        // Redirect to staff shop index
        return $this->redirectWithMessage(RedirectType::UPDATE->value, 'staff.shop.index');

    }

    /**
     * Remove the specified shop from storage.
     */
    public function destroy($id)
    {
        // Check delete permission
        if (!auth('staff')->user()->hasPermission('shop_management', 'can_delete')) {
            abort(403, 'You do not have permission to delete shops.');
        }
        
        $shop = Shop::findOrFail($id);

        // Delete all associated photos from public path
        foreach ($shop->photos as $photo) {
            $filePath = public_path('storage/' . $photo->photo_path);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            // Delete database record
            $photo->delete();
        }

        // Delete shop
        $shop->delete();

            return $this->redirectWithMessage(RedirectType::DELETE->value, 'staff.shop.index');

    }

    /**
     * Delete a specific photo from a shop.
     */
    public function deletePhoto($photoId)
    {
        // Check edit permission (photo deletion is part of editing)
        if (!auth('staff')->user()->hasPermission('shop_management', 'can_edit')) {
            abort(403, 'You do not have permission to edit shops.');
        }
        
        $photo = ShopPhoto::findOrFail($photoId);
        $shopId = $photo->shop_id;

        // Delete file from public path
        $filePath = public_path('storage/' . $photo->photo_path);
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // Delete database record
        $photo->delete();

        // If the deleted photo was primary and there are other photos, set a new primary
        if ($photo->is_primary) {
            $newPrimary = ShopPhoto::where('shop_id', $shopId)->first();
            if ($newPrimary) {
                $newPrimary->update(['is_primary' => true]);
            }
        }

     return $this->redirectWithMessage(RedirectType::DELETE->value, 'staff.shop.edit');
;
    }

    /**
     * Set a photo as primary.
     */
    public function setPrimaryPhoto($photoId)
    {
        // Check edit permission
        if (!auth('staff')->user()->hasPermission('shop_management', 'can_edit')) {
            abort(403, 'You do not have permission to edit shops.');
        }
        
        $photo = ShopPhoto::findOrFail($photoId);

        // Remove primary status from all photos of this shop
        ShopPhoto::where('shop_id', $photo->shop_id)->update(['is_primary' => false]);

        // Set this photo as primary
        $photo->update(['is_primary' => true]);

        return redirect()->back()->with('success', 'Primary photo updated successfully!');
    }
}