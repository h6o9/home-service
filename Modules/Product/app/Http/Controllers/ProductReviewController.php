<?php

namespace Modules\Product\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Product\app\Models\ProductReview;

class ProductReviewController extends Controller
{
    /**
     * @return mixed
     */
    public function index(Request $request)
    {
        checkAdminHasPermissionAndThrowException('product.reviews.view');

        $reviews = ProductReview::with('user', 'product')->orderBy('id', 'desc')
            ->when($request->filled('product'), function ($q) use ($request) {
                $q->whereHas('product', function ($query) use ($request) {
                    $query->where('slug', $request->product);
                });
            })
            ->paginate(20);

        return view('product::products.reviews.index', compact('reviews'));
    }

    /**
     * @param $id
     */
    public function show($id)
    {
        checkAdminHasPermissionAndThrowException('product.reviews.view');

        $review = ProductReview::findOrFail($id);

        return view('product::products.reviews.show', compact('review'));
    }

    /**
     * @param $id
     */
    public function status($id)
    {
        checkAdminHasPermissionAndThrowException('product.reviews.update');

        $review         = ProductReview::findOrFail($id);
        $review->status = !$review->status;
        $review->save();

        return response()->json([
            'status'  => true,
            'message' => __('Review status updated successfully.'),
        ]);
    }

    /**
     * @param $id
     */
    public function delete($id)
    {
        checkAdminHasPermissionAndThrowException('product.reviews.delete');

        ProductReview::findOrFail($id)->delete();

        return back()->with([
            'alert-type' => 'success',
            'message'    => __('Review deleted successfully.'),
        ]);
    }
}
