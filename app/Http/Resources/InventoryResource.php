<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InventoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'staff' => [
                'id' => $this->staff->id,
                'name' => $this->staff->name,
            ],
            'provider' => [
                'id' => $this->provider->id,
                'name' => $this->provider->name,
            ],
            'detail' => $this->inventoryDetails->map(function($detail) {
                return [
                    'price' => $detail->price,
                    'quantity' => $detail->quantity,
                    'product' => [
                        'id' => $detail->product->id,
                        'name' => $detail->product->product_name,
                        'image' => $detail->product->image,
                        'category' => [
                            'id' => $detail->product->category->id,
                            'name' => $detail->product->category->category_name,
                        ],
                        'product-variant' => [
                            'id' => optional($detail->product->productVariants->first())->id
                        ],
                    ],
                ];
            }),
        ];
    }
}
