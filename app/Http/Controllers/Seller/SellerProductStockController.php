<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Traits\RedirectHelperTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Product\app\Rules\DynamicProductInventoryFieldRule;
use Modules\Product\app\Services\BrandService;
use Modules\Product\app\Services\ProductCategoryService;
use Modules\Product\app\Services\ProductService;

class SellerProductStockController extends Controller
{
    use RedirectHelperTrait;

    /**
     * @param ProductService         $productService
     * @param ProductCategoryService $categoryService
     * @param BrandService           $brandService
     */
    public function __construct(
        private ProductService $productService,
        private ProductCategoryService $categoryService,
        private BrandService $brandService,
    ) {
    }

    public function productInventory()
    {
        try {
            $products = $this->productService->getProducts()->where('vendor_id', vendorId());

            if (request('par-page')) {
                $products = $products->paginate(request('par-page'));
                $products->appends(request()->query());
            } else {
                $products = $products->paginate(5);
            }

            $brands = $this->brandService->getActiveBrands();

            $categories = $this->categoryService->getAllProductCategoriesForSelect();

            return view('vendor::products.inventory.index', compact('products', 'brands', 'categories'));
        } catch (\Exception $ex) {
            logError('Product Prices Update Error on Seller Profile', $ex);

            return back()->with([
                'message'    => __('Something Went Wrong'),
                'alert-type' => 'error',
            ]);
        }
    }

    /**
     * @param Request $request
     */
    public function productInventoryStore(Request $request)
    {
        $validationRules = [
            'product_id'         => 'required|exists:products,id',
            'product_variant_id' => 'sometimes|exists:variants,id',
            'sku'                => 'required|string',
            'value'              => [
                'required',
                new DynamicProductInventoryFieldRule($request->input('field')),
            ],
            'field'              => 'required|in:manage_stock,stock_status,stock_qty',
        ];

        $validator = Validator::make($request->all(), $validationRules);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation Error',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $field = $request->get('field');

        if ($request->filled('product_variant_id') && $request->input('field') == 'stock_qty') {
            $variant = $this->productService->getProductVariant($request->product_variant_id);

            $product = $this->productService->getProduct($variant->product_id, function ($query) {
                return $query->where('vendor_id', vendorId());
            });

            if (!$product) {
                return response()->json([
                    'message' => __('Product not found'),
                    'status'  => false,
                ]);
            }

            $variantStock = $variant->manageStocks()->where('sku', $request->sku)->first();

            if (!$variant) {
                return response()->json([
                    'status'  => false,
                    'message' => __('Product Variant not found'),
                ]);
            }

            $variantStock->update(['quantity' => $request->value]);
        }

        if (!$request->filled('product_variant_id')) {
            $product = $this->productService->getProduct($request->product_id, function ($query) {
                return $query->where('vendor_id', vendorId());
            });

            if (!$product) {
                return response()->json([
                    'status'  => false,
                    'message' => __('Product not found'),
                ]);
            }

            if ($field !== 'stock_qty') {
                $product->$field = $request->value;
                $product->save();
            }

            if ($field == 'stock_qty') {
                $product->manageStocks()->update(['quantity' => $request->value]);
            }
        }

        $response = [
            'status'     => true,
            'field'      => $field,
            'product_id' => $request->product_id,
            'message'    => __(':field updated successfully', ['field' => str($request->input('field'))->replace('_', ' ')->title()]),
        ];

        if ($request->filled('product_variant_id')) {
            $response['variant_id'] = $request->product_variant_id;
        }

        return response()->json($response);
    }

}
