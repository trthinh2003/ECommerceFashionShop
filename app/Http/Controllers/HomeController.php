<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home()
    {
        return view('sites.home.index');
    }

    public function shop()
    {
        return view('sites.shop.shop');
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

    public function checkout()
    {
        return view('sites.pages.checkout');
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

    public function productDetail($slug, $id){
        return view('sites.product.product_detail');
    }

}
