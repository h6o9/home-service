<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\ShopPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ShopController extends Controller
{
    /**
     * Display a listing of the shops.
     */
    public function index()
    {
        $shops = Shop::with('photos')->latest()->paginate(10);
        return view('staff.shop.index', compact('shops'));
    }

    /**
     * Show the form for creating a new shop.
     */
    public function create()
    {
        return view('staff.shop.create');
    }

    /**
     * Store a newly created shop in storage.
     */
    public function store(Request $request)
    {
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
        ]);

        // Handle multiple photos upload
        if ($request->hasFile('shop_photos')) {
            foreach ($request->file('shop_photos') as $photo) {
                // Store photo
                $path = $photo->store('shop-photos', 'public');

                // Create photo record
                ShopPhoto::create([
                    'shop_id' => $shop->id,
                    'photo_path' => $path,
                    'is_primary' => false,
                ]);
            }

            // Set the first photo as primary
            if ($shop->photos()->count() > 0) {
                $firstPhoto = $shop->photos()->first();
                $firstPhoto->update(['is_primary' => true]);
            }
        }

        // Redirect to staff shop index (not admin)
        return redirect()->route('staff.shop.index')
            ->with('success', 'Shop created successfully!');
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
        $shop = Shop::with('photos')->findOrFail($id);
        return view('staff.shop.edit', compact('shop'));
    }

    /**
     * Update the specified shop in storage.
     */
    public function update(Request $request, $id)
    {
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
                $path = $photo->store('shop-photos', 'public');
                ShopPhoto::create([
                    'shop_id' => $shop->id,
                    'photo_path' => $path,
                    'is_primary' => false,
                ]);
            }
        }

        // Redirect to staff shop index
        return redirect()->route('staff.shop.index')
            ->with('success', 'Shop updated successfully!');
    }

    /**
     * Remove the specified shop from storage.
     */
    public function destroy($id)
    {
        $shop = Shop::findOrFail($id);

        // Delete all associated photos
        foreach ($shop->photos as $photo) {
            // Delete file from storage
            Storage::disk('public')->delete($photo->photo_path);
            // Delete database record
            $photo->delete();
        }

        // Delete shop
        $shop->delete();

        return redirect()->route('staff.shop.index')
            ->with('success', 'Shop deleted successfully!');
    }

    /**
     * Delete a specific photo from a shop.
     */
    public function deletePhoto($photoId)
    {
        $photo = ShopPhoto::findOrFail($photoId);
        $shopId = $photo->shop_id;

        // Delete file from storage
        Storage::disk('public')->delete($photo->photo_path);

        // Delete database record
        $photo->delete();

        // If the deleted photo was primary and there are other photos, set a new primary
        if ($photo->is_primary) {
            $newPrimary = ShopPhoto::where('shop_id', $shopId)->first();
            if ($newPrimary) {
                $newPrimary->update(['is_primary' => true]);
            }
        }

        return redirect()->back()->with('success', 'Photo deleted successfully!');
    }

    /**
     * Set a photo as primary for the shop.
     */
    public function setPrimaryPhoto($photoId)
    {
        $photo = ShopPhoto::findOrFail($photoId);

        // Remove primary status from all photos of this shop
        ShopPhoto::where('shop_id', $photo->shop_id)->update(['is_primary' => false]);

        // Set this photo as primary
        $photo->update(['is_primary' => true]);

        return redirect()->back()->with('success', 'Primary photo updated successfully!');
    }
}