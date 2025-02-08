<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'color',
        'size',
        'image',
        'price',
        'stock',
        'product_id'
    ];

    //1 MoTaSP cho 1 SP
    public function Product() {
        return $this->belongsTo(Product::class);
    }
}
