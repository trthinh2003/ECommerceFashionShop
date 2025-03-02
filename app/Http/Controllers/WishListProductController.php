<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishListProductController extends Controller
{
    //

    public function index()
    {
        return view('sites.wishlist.wishlist_product');
    }

    public function addToWishList(Wishlist $wishlist, Product $product){
        $productVariant = ProductVariant::where('product_id', $product->id)->first();
        if (!$productVariant) {
            return back()->with('error', 'Sản phẩm này hiện không có sẵn biến thể!');
        }
        $wishlist->addToWishlist($product, $productVariant);
        return redirect()->route('sites.wishlist')->with('success', 'Đã thêm sản phẩm vào danh sách yêu thích!');
    }

    // public function addToWishList(Wishlist $wishlist, Product $product)
    // {
    //     $productVariant = ProductVariant::where('product_id', $product->id)->first();
    //     if (!$productVariant) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Sản phẩm này hiện không có sẵn biến thể!'
    //         ]);
    //     }

    //     // Kiểm tra nếu sản phẩm đã tồn tại trong wishlist
    //     if (session()->has('wishlist') && isset(session('wishlist')[$product->id])) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Sản phẩm đã có trong danh sách yêu thích!'
    //         ]);
    //     }

    //     $wishlist->addToWishlist($product, $productVariant);

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Đã thêm sản phẩm vào danh sách yêu thích!'
    //     ]);
    // }


    public function removefromWishList($id, Wishlist $wishlist)
    {
        $wishlist->removefromWishList($id);
        return redirect()->route('sites.wishlist');
    }
}
