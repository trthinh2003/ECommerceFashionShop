<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Product::create(['product_name' => 'Áo Thun Nam', 'price' => 199000, 'description' => 'Áo thun chất liệu cotton']);
        Product::create(['product_name' => 'Quần Jean', 'price' => 399000, 'description' => 'Quần jean nam thời trang']);
        Product::create(['product_name' => 'Áo Sơ Mi Nữ', 'price' => 299000, 'description' => 'Áo sơ mi công sở nữ']);
        Product::create(['product_name' => 'Quần Short Nam', 'price' => 249000, 'description' => 'Quần short thoải mái']);
    }
}
