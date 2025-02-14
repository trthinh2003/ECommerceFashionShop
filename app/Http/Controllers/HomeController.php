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
        return view('sites.shop');
    }

    public function cart()
    {
        return view('sites.cart.index');
    }
}
