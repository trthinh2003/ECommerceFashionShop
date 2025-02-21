<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function cart()
    {
        return view('sites.cart.index');
    }

    public function checkout()
    {
        return view('sites.pages.checkout');
    }

    public function add(Cart $cart, Product $product, $quantity = 1)
    {
        $productVariant = ProductVariant::find($product->id);
        $cart->add($product, $quantity, $productVariant);

        $totalItems = collect(session()->get('cart', []))->sum('quantity');

        // Nếu gửi đi là request từ AJAX thì trả về JSON
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'cart' => $cart,
                'cart_count' => $totalItems,
                'cart_product_count' => count(Session::get('cart'))
            ]);
        }

        return redirect()->route('sites.cart');
    }


    public function update($id, $quantity = 1)
    {
        return redirect()->route('sites.cart');
    }

    public function remove($id, Cart $cart)
    {
        $cart->remove($id);
        return redirect()->route('sites.cart');
    }

    public function clear()
    {
        if (session()->has('cart')) {
            session()->forget('cart');
        }
        return redirect()->route('sites.cart');
    }

    public function updateCartSession(Request $request)
    {
        if (!Session::has('cart')) {
            return response()->json(['message' => 'Không có giỏ hàng!'], 400);
        }

        $cart = Session::get('cart');

        if (isset($cart[$request->product_id])) {
            $cart[$request->product_id]->quantity = (int) $request->quantity;
            Session::put('cart', $cart); // Cập nhật session
        }

        return response()->json(['message' => 'Giỏ hàng đã được cập nhật!']);
    }
}
