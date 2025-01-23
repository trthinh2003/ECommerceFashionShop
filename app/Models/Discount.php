<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'percent_discount',
        'start_date',
        'end_date'
    ];

    //1 KM cho n SP
    public function Products() {
        return $this->hasMany(Product::class);
    }
}
