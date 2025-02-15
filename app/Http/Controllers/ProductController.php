<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ProductController extends Controller
{
    // public function __construct()
    // {
    //     if (!Gate::allows('salers')) {
    //         return abort(403);
    //     }
    // }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Product::orderBy('id', 'ASC')->paginate(5); //SELECT * FROM PRODUCT
        return view('admin.product.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cats = Category::all();
        // dd($cats);
        return view('admin.product.create', compact('cats'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|unique:products,product_name|min:3|max:100',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required',
            'status' => 'required',
            'image' => 'required|mimes:jpg,jpeg,gif,png,webp'
        ], [
            'name.required' => 'Tên sản phẩm không được để trống.',
            'image.mimes' => 'Định dạng ảnh phải là *.jpg, *.jpeg, *.gif, *.png, *.webp.'
        ]);
        // $data = $request->only('product_name', 'price', 'description', 'status', 'category_id');
        // //Xu ly anh
        // // $file_name = $request->image->getClientOriginalName();
        // $file_name = $request->image->hashName();
        // $request->image->move(public_path('uploads'), $file_name);
        // $data['image'] = $file_name;
        // // dd($file_name);
        // if (Product::create($data)) {
        //     return redirect()->route('product.index');
        // }
        // return redirect()->back();

        // dd($data['status']);
        $product = new Product();
        $product->product_name = $data['name'];
        $product->description = $data['description'];
        $product->price = $data['price'];

        //Xu ly anh
        $file_name = $request->image->hashName();
        $request->image->move(public_path('uploads'), $file_name);
        $product->image = $file_name;

        $product->status = $data['status'];
        $product->category_id = $data['category_id'];
        $product->save();
        return redirect()->route('product.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $cats = Category::all();
        return view('admin.product.edit', compact('product', 'cats'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => 'required|min:3|max:100|unique:products,product_name,' . $product->id,
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required',
            'status' => 'required',
            'image' => 'required|mimes:jpg,jpeg,gif,png,webp'
        ], [
            'name.required' => 'Tên sản phẩm không được để trống.',
            'image.mimes' => 'Định dạng ảnh phải là *.jpg, *.jpeg, *.gif, *.png, *.webp.'
        ]);
        $product->product_name = $data['name'];
        $product->description = $data['description'];
        $product->price = $data['price'];

        //Xu ly anh
        $file_name = $request->image->hashName();
        $request->image->move(public_path('uploads'), $file_name);
        $product->image = $file_name;

        $product->status = $data['status'];
        $product->category_id = $data['category_id'];
        $product->save();
        return redirect()->route('product.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->back();
    }

    /**
     * Search Engine the specified resource from storage.
     */
    public function search(Request $request)
    {
        $keyword = $request->input('query');
        $data = Product::where('product_name', 'like', "%$keyword%")->paginate();
        return view('admin.product.index', compact('data', 'keyword'));
    }
}
