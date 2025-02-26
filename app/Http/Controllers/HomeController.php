<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home()
    {
        return view('sites.home.index');
    }

    public function shop()
    {
        $products = Product::orderBy('id', 'ASC')->paginate(12);
        return view("sites.shop.shop", compact('products'));
    }

    public function cart()
    {
        return view('sites.cart.index');
    }

    public function contact()
    {
        return view('sites.contact.contact');
    }

    public function blog()
    {
        return view('sites.blog.blog');
    }



    public function aboutUs()
    {
        return view('sites.pages.aboutUs');
    }

    public function blogDetail()
    {
        return view('sites.pages.blogDetail');
    }

    public function shopDetail()
    {
        return view('sites.pages.shopDetail');
    }

    public function shoppingCart()
    {
        return view('sites.pages.shoppingCart');
    }

    public function checkout()
    {
        return view('sites.pages.checkout');
    }

    public function productDetail($slug)
    {
        $productDetail = Product::where('slug', $slug)
            ->with(['ProductVariants', 'Category'])
            ->firstorfail();
        $prices = $productDetail->ProductVariants->pluck('price');
        // Lấy danh sách size của sản phẩm
        $sizes = $productDetail->ProductVariants->pluck('size')->unique();
        // Lấy danh sách màu của sản phẩm
        $colors = $productDetail->ProductVariants->pluck('color')->unique();
        return view('sites.product.product_detail', compact('productDetail', 'sizes', 'colors'));
    }
}
