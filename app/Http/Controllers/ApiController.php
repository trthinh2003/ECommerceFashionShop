<?php

namespace App\Http\Controllers;

use App\Http\Resources\InventoryResource;
use App\Models\Category;
use App\Models\Discount;
use App\Models\Inventory;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function discounts()
    {
        $discounts = Discount::orderBy('id', 'ASC')->get();
        return response()->json([
            'data' => $discounts,
            'status_code' => 200,
            'message' => 'ok'
        ]);
    }

    public function discount($id)
    {
        $discount = Discount::find($id);
        if ($discount) {
            return response()->json([
                'data' => $discount,
                'status_code' => 200,
                'message' => 'ok'
            ]);
        }
        return response()->json([
            'data' => null,
            'status_code' => 404,
            'message' => 'Data not found.'
        ]);
    }

    public function categories()
    {
        $categories = Category::orderBy('id', 'ASC')->get();
        return response()->json([
            'data' => $categories,
            'status_code' => 200,
            'message' => 'ok'
        ]);
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
        return response()->json([
            'data' => $inventoriesResource,
            'status_code' => 200,
            'message' => 'ok'
        ]);
    }
}
