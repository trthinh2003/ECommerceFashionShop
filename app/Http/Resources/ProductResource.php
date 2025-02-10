<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'name' => $this->product_name,
            'price' => $this->price,
            'image' => $this->image,
            'description' => $this->description,
            'category' => new CategoryResource($this->Category),
            'product-variant' => ProductVariantResource::collection($this->ProductVariants),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
