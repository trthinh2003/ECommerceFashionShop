<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'inventory_id',
        'quantity',
        'price'
    ];

    /** Tao QH Thuc the yeu **/
    //1 CT PhieuNhap thuoc 1 SP va 1 CT PhieuNhap thuoc 1 PhieuNhap => co 2 ham de thuc hien quan he nay
    public function Product() {
        return $this->belongsTo(Product::class);
    }
    public function Inventory() {
        return $this->belongsTo(Inventory::class);
    }
}
