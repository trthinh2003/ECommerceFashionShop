<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Inventory;
use App\Models\InventoryDetail;
use App\Models\Provider;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.inventory.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cats = Category::all();
        $providers = Provider::all();
        return view('admin.inventory.create', compact('cats', 'providers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'id' => 'required',
            'product_name' => 'required|min:3|max:150|unique:products,product_name',
            'image' => 'required|mimes:jpg,jpeg,gif,png,webp',
            'category_id' => 'required|exists:categories,id',
            'provider_id' => 'required|exists:providers,id',
            'price' => 'required|numeric|min:1',
            'quantity' => 'required|numeric|min:1',
            'color' => 'required',
            'sizes' => 'required',
        ], [
            'product_name.required' => 'Tên sản phẩm không được để trống.',
            'product_name.min' => 'Tên sản phẩm phải có ít nhất 3 ký tự.',
            'product_name.max' => 'Tên sản phẩm đã vượt quá 150 ký tự.',
            'product_name.unique' => 'Tên sản phẩm này đã tồn tại.',
            'category_id.required' => 'Vui lòng chọn danh mục.',
            'provider_id.required' => 'Vui lòng chọn tên nhà cung cấp.',
            'provider_id.exists' => 'Tên nhà cung cấp không hợp lệ.',
            'price.required' => 'Vui lòng điền giá nhập.',
            'price.min' => 'Giá tiền phải lớn hơn 0.',
            'quantity.required' => 'Vui lòng điền số lượng nhập.',
            'quantity.min' => 'Số lượng nhập phải lớn hơn 0.',
            'color.required' => 'Vui lòng nhập màu sắc cho sản phẩm.',
            'sizes.required' => 'Vui lòng chọn kích cỡ cho sản phẩm.',
        ]);

        //Thêm vào Phiếu nhập hàng
        $inventory = new Inventory();
        $inventory->provider_id = $data['provider_id'];
        $inventory->staff_id = $data['id'];
        $inventory->total = $data['quantity'] * $data['price'];
        $inventory->save();

        //Thêm vào Sản phẩm
        $product = new Product();
        $product->product_name = $data['product_name'];
        $product->category_id = $data['category_id'];
        //Xu ly anh
        $file_name = $request->image->hashName();
        $request->image->move(public_path('uploads'), $file_name);
        $product->image = $file_name;
        $product->slug = Str::slug($data['product_name']); //"Áo thun Polo XXL"->"ao-thun-polo-xxl"
        $product->save();

        // dd($query)
        //Thêm vào Mô tả sản phẩm
        foreach ($request->sizes as $size) { //S, M, XL
            ProductVariant::firstOrCreate([
                'color' => $data['color'],
                'size' => $size,
                'product_id' => $product->id
            ]);
        };

        //Thêm vào Chi tiết Phiếu nhập
        $inventoryDetail = new InventoryDetail();
        $inventoryDetail->product_id = $product->id;
        $inventoryDetail->inventory_id = $inventory->id;
        $inventoryDetail->price = $data['price'];
        $inventoryDetail->quantity = $data['quantity'];
        //Xử lý chuỗi sizes
        $inventoryDetail->size = join(', ', $data['sizes']);
        $inventoryDetail->save();

        return redirect()->route('inventory.index')->with('success', "Thêm phiếu nhập mới thành công!");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function search(Request $request) {

    }
}
