<?php

namespace App\Http\Controllers;

use App\Http\Resources\InventoryResource;
use App\Models\Category;
use App\Models\Discount;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Staff;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function apiStatus($data, $status_code, $total = 0, $message = null) {
        return response()->json([
            'data' => $data,
            'status_code' => $status_code,
            'total' => $total,
            'message' => $message
        ]);
    }

    public function staff($id){
        $staff = Staff::find($id);
        if ($staff) {
            return $this->apiStatus($staff, 200, 1, 'ok');
        }
        return $this->apiStatus(null, 404, $staff->count(), 'Data not found.');
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
        return $this->apiStatus(null, 404, $discount->count(), 'Data not found.');
    }

    public function categories()
    {
        $categories = Category::orderBy('id', 'ASC')->get();
        return $this->apiStatus($categories, 200, $categories->count(), 'ok');
    }

    public function inventories()
    {
        $inventories = Inventory::with([
            'Staff',
            'Provider',
            'InventoryDetails.Product.Category',
            'InventoryDetails.Product.ProductVariants'
        ])->paginate(2);
        $inventoriesResource = InventoryResource::collection($inventories);
        return $this->apiStatus($inventoriesResource, 200, $inventoriesResource->count(), 'ok');
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
            return $this->apiStatus($inventoriesResource, 200, $inventoriesResource->count(), 'ok');
        }
        else {
            return $this->apiStatus(null, 404, $inventoriesResource->count(), 'Data not found.');
        }
    }

    public function products()
    {
        $products = Product::orderBy('id', 'ASC')->paginate(5);
        return $this->apiStatus($products, 200, $products->count(), 'ok');
    }

    public function productVariants()
    {
        $productVariants = ProductVariant::orderBy('id', 'ASC')->paginate(2);
        return $this->apiStatus($productVariants, 200, $productVariants->count(), 'ok');
    }
}
