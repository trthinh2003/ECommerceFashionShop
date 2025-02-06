<?php

namespace App\Http\Controllers;

use App\Http\Resources\InventoryResource;
use App\Models\Category;
use App\Models\Discount;
use App\Models\Inventory;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function apiStatus($data, $status_code, $message = null) {
        return response()->json([
            'data' => $data,
            'status_code' => $status_code,
            'message' => $message
        ]);
    }

    public function discounts()
    {
        $discounts = Discount::orderBy('id', 'ASC')->get();
        return $this->apiStatus($discounts, 200, 'ok');
    }

    public function discount($id)
    {
        $discount = Discount::find($id);
        if ($discount) {
            return $this->apiStatus($discount, 200, 'ok');
        }
        return $this->apiStatus(null, 404, 'Data not found.');
    }

    public function categories()
    {
        $categories = Category::orderBy('id', 'ASC')->get();
        return $this->apiStatus($categories, 200, 'ok');
    }

    public function inventories()
    {
        $inventories = Inventory::with([
            'Staff',
            'Provider',
            'InventoryDetails.Product.Category',
            'InventoryDetails.Product.ProductVariants'
        ])->get();
        $inventoriesResource = InventoryResource::collection($inventories);
        return $this->apiStatus($inventoriesResource, 200, 'ok');
    }
}
