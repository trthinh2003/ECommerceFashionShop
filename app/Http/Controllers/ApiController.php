<?php

namespace App\Http\Controllers;

use App\Http\Resources\InventoryResource;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Discount;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
    public function apiStatus($data, $status_code, $total = 0, $message = null)
    {
        return response()->json([
            'data' => $data,
            'status_code' => $status_code,
            'total' => $total,
            'message' => $message
        ]);
    }

    public function staff($id)
    {
        $staff = Staff::find($id);
        if ($staff) {
            return $this->apiStatus($staff, 200, 1, 'ok');
        }
        return $this->apiStatus(null, 404, 0, 'Data not found.');
    }

    public function discounts()
    {
        $discounts = Discount::orderBy('id', 'ASC')->get();
        return $this->apiStatus($discounts, 200, $discounts->count(), 'ok');
    }

    public function discount($id)
    {
        $discount = Discount::find($id);
        if ($discount) {
            return $this->apiStatus($discount, 200, 1, 'ok');
        }
        return $this->apiStatus(null, 404, 0, 'Data not found.');
    }


    public function getDiscountByCode($code)
    {
        $discount = Discount::where('code', $code)->first();

        if (!empty($discount)) {
            return $this->apiStatus($discount, 200, 1, 'ok');
        }

        return $this->apiStatus(null, 404, 0, 'Data not found.');
    }


    public function categories()
    {
        // $categories = Category::orderBy('id', 'ASC')->get();
        $categories = Category::withCount('products')->get();
        return $this->apiStatus($categories, 200, $categories->count(), 'ok');
    }

    public function inventories()
    {
        $inventories = Inventory::with([
            'Staff',
            'Provider',
            'InventoryDetails.Product.Category',
            'InventoryDetails.Product.ProductVariants'
        ])->paginate(10);

        return response()->json([
            'status_code' => 200,
            'data' => InventoryResource::collection($inventories),
            'pagination' => [
                'current_page' => $inventories->currentPage(),
                'last_page' => $inventories->lastPage(),
                'total' => $inventories->total(),
                'per_page' => $inventories->perPage(),
                'next_page_url' => $inventories->nextPageUrl(),
                'prev_page_url' => $inventories->previousPageUrl(),
            ],
        ]);
    }


    public function inventory($id)
    {
        $inventories = Inventory::with([
            'Staff',
            'Provider',
            'InventoryDetails.Product.Category',
            'InventoryDetails.Product.ProductVariants'
        ])->find($id);
        $inventoriesResource = new InventoryResource($inventories);
        if ($inventories) {
            return $this->apiStatus($inventoriesResource, 200, 1, 'ok');
        } else {
            return $this->apiStatus(null, 404, 0, 'Data not found.');
        }
    }

    // public function inventoriesSearch(Request $request)
    // {
    //     $query = Inventory::with([
    //         'Staff',
    //         'Provider',
    //         'InventoryDetails.Product.Category',
    //         'InventoryDetails.Product.ProductVariants'
    //     ]);

    //     if ($request->has('id')) {
    //         $query->where('id', $request->id);
    //     }

    //     if ($request->has('staff_name')) {
    //         $query->whereHas('Staff', function ($q) use ($request) {
    //             $q->where('name', 'like', '%' . $request->staff_name . '%');
    //         });
    //     }

    //     $inventories = $query->paginate(10);

    //     return response()->json([
    //         'status_code' => 200,
    //         'message' => 'Thành công',
    //         'data' => InventoryResource::collection($inventories),
    //         'pagination' => [
    //             'current_page' => $inventories->currentPage(),
    //             'last_page' => $inventories->lastPage(),
    //             'total' => $inventories->total(),
    //             'per_page' => $inventories->perPage(),
    //             'next_page_url' => $inventories->nextPageUrl(),
    //             'prev_page_url' => $inventories->previousPageUrl(),
    //         ]
    //     ]);
    // }


    public function inventoryDetail($id)
    {
        $inventories = Inventory::with([
            'Staff',
            'Provider',
            'InventoryDetails.Product.Category',
            'InventoryDetails.Product.ProductVariants'
        ])->find($id);
        $inventoriesResource = new InventoryResource($inventories);
        if ($inventories) {
            return $this->apiStatus($inventoriesResource, 200, 1, 'ok');
        } else {
            return $this->apiStatus(null, 404, 0, 'Data not found.');
        }
    }

    public function products()
    {
        $products = Product::orderBy('id', 'ASC')->paginate(5);
        return $this->apiStatus($products, 200, $products->count(), 'ok');
    }

    public function getProductsClient()
    {
        $products = Product::orderBy('id', 'ASC')->get();
        return $this->apiStatus($products, 200, $products->count(), 'ok');
    }


    public function product($id)
    {
        $products = Product::with('Category', 'ProductVariants')->find($id);
        $productResource = new ProductResource($products);
        if ($productResource) {
            return $this->apiStatus($productResource, 200, 1, 'ok');
        } else {
            return $this->apiStatus(null, 404, 0, 'Data not found.');
        }
    }

    public function brands()
    {
        $brands = DB::select('SELECT DISTINCT brand FROM products');
        return $this->apiStatus($brands, 200, 0, 'ok');
    }

    public function productVariants()
    {
        $productVariants = ProductVariant::orderBy('id', 'ASC')->paginate(2);
        return $this->apiStatus($productVariants, 200, $productVariants->count(), 'ok');
    }

    // public function test($id){
    //     $data = DB::table('orders as o')
    //     ->join('customers as c', 'o.customer_id', '=', 'c.id')
    //     ->join('order_details as od', 'o.id', '=', 'od.order_id')
    //     ->join('products as p', 'p.id', '=', 'od.product_id')
    //     ->join('product_variants as pv', 'pv.product_id', '=', 'p.id')
    //     ->where('o.id', $id)
    //     ->select('o.*', 'c.name as customer_name', 'p.product_name as product_name', 'pv.size', 'pv.color', 'od.quantity', 'od.price')
    //     ->get();
    //     return $this->apiStatus($data, 200, 1, 'ok');
    // }
}
