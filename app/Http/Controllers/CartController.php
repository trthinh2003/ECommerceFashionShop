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
        if(request()->has('add_to_cart')){
            $productVariant = ProductVariant::where('product_id', $product->id)->where('size', request()->size)->where('color',request()->color)->first();
            if (!$productVariant) {
                return back()->with('error', 'Sản phẩm này hiện không có sẵn biến thể!');
            }
            request()->validate([
                'quantity' => 'required|numeric|min:1|max:'.$productVariant->stock
            ],
            [
                'quantity.required' => 'Vui lý nhập số lượng.',
                'quantity.numeric' => 'Số lượng phải là kiểu số.',
                'quantity.min' => 'Số lượng phải lớn hơn 1.',
                'quantity.max' => 'Số lượng không được vượt quá số lượng trong kho. Trong kho có số lượng '.$productVariant->stock
            ]);

            $cart->add($product, request()->quantity, $productVariant);
            return redirect()->route('sites.cart');
        }
        else{
            // dd(request()->all());
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

    }

    public function update($id, $quantity = 1)
    {
        return redirect()->route('sites.cart');
    }

    public function remove($key, Cart $cart)
    {
        // dd($key);
        $cart->remove($key);
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

        if (isset($cart[$request->product_id . '-' . $request->color . '-' . $request->size])) {
            $cart[$request->product_id . '-' . $request->color . '-' . $request->size]->quantity = (int) $request->quantity;
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
