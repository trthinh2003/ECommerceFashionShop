<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function cart()
    {
        return view('sites.cart.index');
    }

    public function add(Cart $cart, Product $product, $quantity = 1)
    {
        $cart->add($product, $quantity);
        return redirect()->route('sites.cart');
    }

    public function update($id , $quantity = 1)
    {
        return redirect()->route('sites.cart');
    }

    public function remove($id)
    {
        return redirect()->route('sites.cart');
    }

    public function clear(){
        return redirect()->route('sites.cart');
    }

}
