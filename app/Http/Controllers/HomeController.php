<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    public function home()
    {
        if (Session::has('success_payment')) {
            Session::forget('success_payment');
        }
        $data = Blog::with('staff')->paginate(5);
        return view('sites.home.index', compact('data'));
    }

    public function shop(Request $request)
    {
        // dd($request->all());
        $query = Product::with('category', 'Discount', 'ProductVariants');

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
            } else if ($price === '1.000.000') {
                $minPrice = str_replace('.', '', $price);
            }
            empty($maxPrice) ? $query->where('price', '>=', $minPrice) : $query->whereBetween('price', [$minPrice, $maxPrice]);
        }

        if ($request->has('tag')) {
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
        $data = Blog::with('staff')->paginate(5);
        return view('sites.blog.blog', compact('data'));
    }



    public function aboutUs()
    {
        return view('sites.pages.aboutUs');
    }

    public function blogDetail($slug)
    {
        $blogDetail = Blog::where('slug', $slug)->with('staff')->firstorfail();
        return view('sites.pages.blogDetail', compact('blogDetail'));
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
            ->with(['ProductVariants', 'Category', 'Discount'])
            ->firstOrFail();
        $prices = $productDetail->ProductVariants->pluck('price');
        // Lấy danh sách size của sản phẩm
        $sizes = $productDetail->ProductVariants->pluck('size')->unique();
        // Lấy danh sách màu của sản phẩm
        $colors = $productDetail->ProductVariants->pluck('color')->unique();

        // Lấy danh sách bình luận của khách hàng
        $commentCustomers = DB::table('orders as o')
            ->join('customers as c', 'o.customer_id', '=', 'c.id')
            ->join('order_details as od', 'o.id', '=', 'od.order_id')
            ->join('product_variants as pv', 'pv.id', '=', 'od.product_variant_id')
            ->join('products as p', 'p.id', '=', 'pv.product_id')
            ->join('comments as r', function ($join) {
                $join->on('r.product_id', '=', 'p.id')
                    ->on('r.customer_id', '=', 'c.id')
                    ->on('r.order_id', '=', 'o.id');
            })
            ->where('p.slug', $slug)
            ->select(
                'o.id as order_id',
                'r.*',
                'c.name as customer_name',
                'p.product_name as product_name',
                'p.id as product_id',
                'p.image',
                'pv.size',
                'pv.color'
            )
            ->distinct()
            ->get();

        // Nếu không có bình luận thì trả về mảng rỗng
        if ($commentCustomers->isEmpty()) {
            $commentCustomers = [];
        }

        // Lấy sao trung bình của sản phẩm
        $starAvg = DB::table('products as p')
            ->join('comments as r', 'r.product_id', '=', 'p.id')
            ->where('p.slug', $slug)
            ->select(
                'p.id as product_id',
                DB::raw('AVG(r.star) as star_avg')
            )
            ->groupBy('p.id')
            ->distinct()
            ->first();

        // Nếu không có sao trung bình thì mặc định là 0
        $starAvg = $starAvg ? $starAvg->star_avg : 0;


        return view('sites.product.product_detail', compact('productDetail', 'sizes', 'colors', 'commentCustomers', 'starAvg'));
    }

    public function successPayment()
    {
        return view('sites.success.payment');
    }
}
