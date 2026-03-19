<?php

namespace Modules\Coupon\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\DB;
use Modules\Coupon\app\Http\Requests\CouponRequest;
use Modules\Coupon\app\Models\Coupon;
use Modules\Coupon\app\Models\CouponHistory;
use Modules\Coupon\app\Services\CouponService;
use Modules\Product\app\Services\ProductCategoryService;
use Modules\Product\app\Services\ProductService;

class CouponController extends Controller
{
    /**
     * @param ProductService         $productService
     * @param ProductCategoryService $categoryService
     * @param CouponService          $couponService
     */
    public function __construct(private ProductService $productService, private ProductCategoryService $categoryService, private CouponService $couponService)
    {}

    public function index()
    {
        checkAdminHasPermissionAndThrowException('coupon.management');

        $coupons = Coupon::latest()->paginate();

        return view('coupon::index', compact('coupons'));
    }

    public function create()
    {
        checkAdminHasPermissionAndThrowException('coupon.management');

        $products   = $this->productService->allActiveProducts(request());
        $products   = $products->get();
        $categories = $this->categoryService->getAllProductCategoriesForSelect();

        return view('coupon::create', compact('products', 'categories'));
    }

    /**
     * @param CouponRequest $request
     */
    public function store(CouponRequest $request)
    {
        checkAdminHasPermissionAndThrowException('coupon.management');

        DB::beginTransaction();
        try {
            $coupon = $this->couponService->store($request);

            $notification = __('Created Successfully');
            $notification = ['message' => $notification, 'alert-type' => 'success'];

            DB::commit();

            return redirect()->route('admin.coupon.edit', ['coupon' => $coupon->id, 'code' => getSessionLanguage()])->with($notification);
        } catch (Exception $ex) {
            logError("Coupon Store Error", $ex);
            DB::rollBack();

            return redirect()->back()->with([
                'message'    => $ex->getMessage(),
                'alert-type' => 'error',
            ]);
        }
    }

    /**
     * @param $id
     */
    public function edit($id)
    {
        checkAdminHasPermissionAndThrowException('coupon.management');
        $coupon     = Coupon::find($id);
        $products   = $this->productService->allActiveProducts(request());
        $products   = $products->get();
        $categories = $this->categoryService->getAllProductCategoriesForSelect();

        return view('coupon::edit', compact('coupon', 'products', 'categories'));
    }

    /**
     * @param CouponRequest $request
     * @param $id
     */
    public function update(CouponRequest $request, $id)
    {
        checkAdminHasPermissionAndThrowException('coupon.management');

        DB::beginTransaction();
        try {

            $this->couponService->update($request, $id);
            DB::commit();
            $notification = __('Updated Successfully');
            $notification = ['message' => $notification, 'alert-type' => 'success'];

            return redirect()->back()->with($notification);
        } catch (Exception $ex) {
            logError("Coupon Update Error", $ex);
            DB::rollBack();

            return redirect()->back()->with([
                'message'    => $ex->getMessage(),
                'alert-type' => 'error',
            ]);
        }
    }

    /**
     * @param $id
     */
    public function destroy($id)
    {
        checkAdminHasPermissionAndThrowException('coupon.management');

        try {

            $this->couponService->destroy($id);

            $notification = __('Deleted Successfully');
            $notification = ['message' => $notification, 'alert-type' => 'success'];

            return redirect()->back()->with($notification);
        } catch (Exception $ex) {
            logError("Coupon Delete Error", $ex);

            return redirect()->back()->with([
                'message'    => $ex->getMessage(),
                'alert-type' => 'error',
            ]);
        }
    }

    public function coupon_history()
    {
        checkAdminHasPermissionAndThrowException('coupon.management');

        $coupon_histories = CouponHistory::where(['author_id' => 0])->latest()->get();

        return view('coupon::history', ['coupon_histories' => $coupon_histories]);
    }

    /**
     * @param $id
     */
    public function statusUpdate($id)
    {
        checkAdminHasPermissionAndThrowException('coupon.management');

        $coupon = Coupon::find($id);
        $status = $coupon->status == 1 ? 0 : 1;
        $coupon->update(['status' => $status]);

        $notification = __('Updated Successfully');

        return response()->json([
            'success' => true,
            'message' => $notification,
        ]);
    }
}
