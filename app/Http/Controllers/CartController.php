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


    // Thêm vào giỏ hàng mặc định lấy theo id sản phẩm
    public function add(Cart $cart, Product $product, $quantity = 1)
    {
        $productVariant = ProductVariant::where('product_id', $product->id)->first();
        if (!$productVariant) {
            return back()->with('error', 'Sản phẩm này hiện không có sẵn biến thể!');
        }
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


    public function addToCartFromProduct(Request $request, Cart $cart, Product $product)
    {
        dd($request->all());
        $productId = $request->input('product_id');
        $size = $request->input('selected_size');
        $color = $request->input('selected_color');
        $quantity = (int) $request->input('selected_quantity', 1);

        // Tìm biến thể sản phẩm
        $productVariant = ProductVariant::where('product_id', $productId)
            ->where('size', $size)
            ->where('color', $color)
            ->first();

        // Thêm vào giỏ hàng
        $cart->add($product, $quantity, $productVariant);

        return redirect()->route('sites.cart')->with('success', 'Sản phẩm đã được thêm vào giỏ hàng!');
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

    public function createPercentDiscountSession(Request $request)
    {
        // Kiểm tra dữ liệu đầu vào
        if (!$request->has('percent_discount')) {
            return response()->json(['message' => 'Thiếu dữ liệu!'], 400);
        }

        // Lưu giá trị percent_discount vào session
        Session::put('percent_discount', (floatval($request->percent_discount)));

        return response()->json([
            'message' => 'percent_discount được cập nhật!',
            'data' => Session::get('percent_discount')
        ]);
    }
}
