<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name',
        'brand',
        'sku',
        'description',
        'tags',
        'price',
        'image',
        'status',
        'category_id',
        'short_description',
        'slug',
        'material'
    ];

    //1 SP thuoc 1 DM
    public function Category()
    {
        return $this->belongsTo(Category::class);
    }

    //1 SP co n MoTaSP
    public function ProductVariants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    //1 SP co 1 KM
    public function Discount()
    {
        return $this->belongsTo(Discount::class);
    }

    //1 SP chua nhieu CT DonHang
    public function OrderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    //1 SP chua nhieu CT PhieuNhap
    public function InventoryDetails()
    {
        return $this->hasMany(InventoryDetail::class);
    }
}
