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
            'staff_id' => auth('staff')->id(),
        ]);

        // Handle multiple photos upload
        if ($request->hasFile('shop_photos')) {
            foreach ($request->file('shop_photos') as $photo) {
                try {
                    $publicPath = public_path('storage/shop-photos');
                    if (!file_exists($publicPath)) {
                        mkdir($publicPath, 0755, true);
                    }
                    
                    $filename = 'shop_' . $shop->id . '_' . time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
                    
                    if ($photo->move($publicPath, $filename)) {
                        ShopPhoto::create([
                            'shop_id' => $shop->id,
                            'photo_path' => 'shop-photos/' . $filename,
                            'is_primary' => false,
                        ]);
                    }
                } catch (\Exception $e) {
                    \Log::error('Photo upload failed: ' . $e->getMessage());
                }
            }

            // Set the first photo as primary
            if ($shop->photos()->count() > 0) {
                $firstPhoto = $shop->photos()->first();
                $firstPhoto->update(['is_primary' => true]);
            }
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Created successfully!',
                'shop' => $shop
            ]);
        }

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
                try {
                    $publicPath = public_path('storage/shop-photos');
                    if (!file_exists($publicPath)) {
                        mkdir($publicPath, 0755, true);
                    }
                    
                    $filename = 'shop_' . $shop->id . '_' . time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
                    
                    if ($photo->move($publicPath, $filename)) {
                        ShopPhoto::create([
                            'shop_id' => $shop->id,
                            'photo_path' => 'shop-photos/' . $filename,
                            'is_primary' => false,
                        ]);
                    }
                } catch (\Exception $e) {
                    \Log::error('Photo upload failed: ' . $e->getMessage());
                }
            }
        }

        return $this->redirectWithMessage(RedirectType::UPDATE->value, 'staff.shop.index');
    }

    /**
     * Remove the specified shop from storage.
     */
    public function destroy($id)
    {
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
            $photo->delete();
        }

        $shop->delete();

        return $this->redirectWithMessage(RedirectType::DELETE->value, 'staff.shop.index');
    }

    /**
     * Delete a specific photo from a shop.
     */
    public function deletePhoto($photoId)
    {
        // Check edit permission
        if (!auth('staff')->user()->hasPermission('shop_management', 'can_edit')) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to delete photos.'
            ], 403);
        }
        
        try {
            $photo = ShopPhoto::findOrFail($photoId);
            $shopId = $photo->shop_id;
            
            // Delete the file from public path (CORRECTED PATH)
            $filePath = public_path('storage/' . $photo->photo_path);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            
            // Delete the record
            $photo->delete();
            
            // Check if there are any photos left
            $remainingPhotos = ShopPhoto::where('shop_id', $shopId)->count();
            
            // If there are photos but no primary photo, set the first one as primary
            if($remainingPhotos > 0) {
                $hasPrimary = ShopPhoto::where('shop_id', $shopId)->where('is_primary', true)->exists();
                if(!$hasPrimary) {
                    $firstPhoto = ShopPhoto::where('shop_id', $shopId)->first();
                    $firstPhoto->is_primary = true;
                    $firstPhoto->save();
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Photo deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete photo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Set primary photo
     */
    public function setPrimaryPhoto(Request $request)
    {
        // Check edit permission
        if (!auth('staff')->user()->hasPermission('shop_management', 'can_edit')) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to edit shops.'
            ], 403);
        }
        
        try {
            // Check if photo_id exists in request
            if (!$request->has('photo_id')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Photo ID is required.'
                ], 400);
            }
            
            $photoId = $request->photo_id;
            
            // Check if photo exists
            $photo = ShopPhoto::find($photoId);
            if (!$photo) {
                return response()->json([
                    'success' => false,
                    'message' => 'Photo not found.'
                ], 404);
            }
            
            // Remove primary status from all photos of this shop
            ShopPhoto::where('shop_id', $photo->shop_id)->update(['is_primary' => false]);
            
            // Set this photo as primary
            $photo->is_primary = true;
            $photo->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Primary photo updated successfully!'
            ]);
        } catch (\Exception $e) {
            \Log::error('Set primary photo error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to set primary photo: ' . $e->getMessage()
            ], 500);
        }
    }
}