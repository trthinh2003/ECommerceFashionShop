<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name',
        'description',
        'price',
        'image',
        'status',
        'category_id',
        'short_description',
        'slug',
        'material'
    ];

    //1 SP thuoc 1 DM
    public function Category() {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }

    //1 SP co n MoTaSP
    public function ProductVariants() {
        return $this->hasMany(ProductVariant::class, 'product_id', 'id');
    }

    //1 SP co 1 KM
    public function Discount() {
        return $this->belongsTo(Discount::class);
    }
}
