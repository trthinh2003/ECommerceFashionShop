<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    public function home()
    {
        if (Session::has('success_payment')) {
            Session::forget('success_payment');
        }
        return view('sites.home.index');
    }

    public function shop(Request $request)
    {
        // dd($request->all());
        $query = Product::with('category');

        if ($request->has('q')) {
            $search = $request->q;
            $query->where('product_name', 'LIKE', "%$search%");
        }

        if ($request->has('category')) {
            $categoryName = $request->category;
            $query->whereHas('category', function ($q) use ($categoryName) {
                $q->where('category_name', $categoryName);
            });
        }

        if ($request->has('brand')) {
            $brandName = $request->brand;
            $query->where('brand', $brandName);
        }

        if ($request->has('price')) {
            $price = $request->price;
            if (strpos($price, '-') !== false) {
                $items = explode('-', $price);
                $minPrice = str_replace('.', '', $items[0]);
                $maxPrice = str_replace('.', '', $items[1]);
            }
            else if ($price === '1.000.000') {
                $minPrice = str_replace('.', '', $price);
            }
            empty($maxPrice) ? $query->where('price', '>=', $minPrice) : $query->whereBetween('price', [$minPrice, $maxPrice]);
        }

        if($request->has('tag')) {
            $tag = str_replace('-', ' ', $request->tag);
            $query->where('tags', 'like', "%$tag%");

        }
        //  dd($minPrice, $maxPrice ?? 0);
        // dd($tag);
        $products = $query->paginate(12);

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

    public function successPayment() {
        return view('sites.success.payment');
    }
}
