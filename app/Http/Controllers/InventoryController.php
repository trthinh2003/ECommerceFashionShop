<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Inventory;
use App\Models\InventoryDetail;
use App\Models\Provider;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            'brand_name' => 'required|max:100',
            'image' => 'required',
            'category_id' => 'required|exists:categories,id',
            'provider_id' => 'required|exists:providers,id',
            'price' => 'required|numeric|min:1',
            'color' => 'required',
            'sizes' => 'required',
        ], [
            'product_name.required' => 'Tên sản phẩm không được để trống.',
            'product_name.min' => 'Tên sản phẩm phải có ít nhất 3 ký tự.',
            'product_name.max' => 'Tên sản phẩm đã vượt quá 150 ký tự.',
            'product_name.unique' => 'Tên sản phẩm này đã tồn tại.',
            'image.required' => 'Vui lòng chọn hình ảnh.',
            'category_id.required' => 'Vui lòng chọn danh mục.',
            'category_id.exists' => 'Tên danh mục không hợp lệ.',
            'provider_id.required' => 'Vui lòng chọn tên nhà cung cấp.',
            'provider_id.exists' => 'Tên nhà cung cấp không hợp lệ.',
            'price.required' => 'Vui lòng điền giá nhập.',
            'price.min' => 'Giá tiền phải lớn hơn 0.',
            'color.required' => 'Vui lòng nhập màu sắc cho sản phẩm.',
            'sizes.required' => 'Vui lòng chọn kích cỡ cho sản phẩm.',
        ]);

        //Thêm vào Phiếu nhập hàng
        // dd($request->formatted_sizes);
        $inventory = new Inventory();
        $inventory->provider_id = $data['provider_id'];
        $inventory->staff_id = $data['id'];
        //Xử lý chuỗi {size}-{số lượng}
        $size_and_quantitys = explode(',', $request->formatted_sizes);
        // dd($size_and_quantitys);
        $totalQuantity = 0;
        $sizes = "";
        $size_assoc = [];
        foreach ($size_and_quantitys as $size_and_quantity) {
            $item = explode('-', $size_and_quantity);
            list($key, $value) = $item;
            $size_assoc[$key] = (int)$value;
            $sizes .= $item[0] . ",";
            $totalQuantity += $item[1];
        }
        $sizes = rtrim($sizes, ',');
        // dd($sizes, $totalQuantity, $size_assoc);
        $inventory->total = $totalQuantity * $data['price'];
        $inventory->save();

        //Thêm vào Sản phẩm
        $product = new Product();
        $product->product_name = $data['product_name'];
        $product->brand = $data['brand_name'];
        $product->sku = strtoupper(Str::random(6));
        $product->category_id = $data['category_id'];
        //Xu ly anh
        $file_name = $request->image->hashName();
        $request->image->move(public_path('uploads'), $file_name);
        $product->image = $file_name;
        $product->slug = Str::slug($data['product_name']); //"Áo thun Polo XXL"->"ao-thun-polo-xxl"
        $product->save();

        // dd($query)
        //Thêm vào Mô tả sản phẩm

        foreach (explode(',', $sizes) as $size) {
            ProductVariant::firstOrCreate([
                'color' => $data['color'],
                'size' => $size,
                'stock' => $size_assoc[$size],
                'product_id' => $product->id,
            ]);
        };

        //Thêm vào Chi tiết Phiếu nhập
        $inventoryDetail = new InventoryDetail();
        $inventoryDetail->product_id = $product->id;
        $inventoryDetail->inventory_id = $inventory->id;
        $inventoryDetail->price = $data['price'];
        $inventoryDetail->quantity = $totalQuantity;
        //Xử lý chuỗi sizes
        $inventoryDetail->size = preg_replace('/([^,]+)/', '$1-' . $data['color'], join(',', $size_and_quantitys));
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

    public function add_extra() {
        $cats = Category::all();
        $providers = Provider::all();
        return view('admin.inventory.add-extra', compact('cats', 'providers'));
    }

    public function post_add_extra(Request $request) {
        $data = $request->validate([
            'id' => 'required',
            'product_name' => 'min:3|max:150',
            'image' => 'mimes:jpg,jpeg,gif,png,webp',
            'category_id' => 'string',
            'provider_id' => 'required|exists:providers,id',
            'price' => 'required|numeric|min:1',
            'color' => 'required',
            'sizes' => 'required',
        ], [
            'product_name.min' => 'Tên sản phẩm phải có ít nhất 3 ký tự.',
            'product_name.max' => 'Tên sản phẩm đã vượt quá 150 ký tự.',
            'category_id.exists' => 'Tên danh mục không hợp lệ.',
            'provider_id.required' => 'Vui lòng chọn tên nhà cung cấp.',
            'provider_id.exists' => 'Tên nhà cung cấp không hợp lệ.',
            'price.required' => 'Vui lòng điền giá nhập.',
            'price.min' => 'Giá tiền phải lớn hơn 0.',
            'color.required' => 'Vui lòng nhập màu sắc cho sản phẩm.',
            'sizes.required' => 'Vui lòng chọn kích cỡ cho sản phẩm.',
        ]);
        $inventory = new Inventory();
        $inventory->provider_id = $data['provider_id'];
        $inventory->staff_id = $data['id'];
        //Xử lý chuỗi {size}-{số lượng}
        $size_and_quantitys = explode(',', $request->formatted_sizes);
        // dd($size_and_quantitys);
        $totalQuantity = 0;
        $size_assoc = [];
        foreach ($size_and_quantitys as $size_and_quantity) {
            $item = explode('-', $size_and_quantity);
            list($key, $value) = $item;
            $size_assoc[$key] = (int)$value;
            $totalQuantity += $item[1];
        }

        // dd($request->variant["XXL"] ?? 0, $size_assoc["XXL"]);
        $current_sizesAndStocks = [];
        // foreach ($current_sizesAndStocks as $size => $value) {
        //     $current_sizesAndStocks[$size] += $stock;
        // };
        $allKeys = array_unique(array_merge(array_keys($request->variant), array_keys($size_assoc)));

        foreach ($allKeys as $size) {
            $value1 = $request->variant[$size] ?? 0;
            $value2 = $size_assoc[$size] ?? 0;
            $current_sizesAndStocks[$size] = $value1 + $value2;
        }
        // dd($request->variant, $size_assoc, $current_sizesAndStocks, $new_sizesAndStocks);
        // dd($size_assoc, $size_assoc["S"] - $request->variant["S"]);
        // dd([$data['color'], $size, $size_assoc["XS"], $request->product_id]);
        $color = $data['color'];
        $checkColor = ProductVariant::where('color', 'like', $color)
                                    ->where('product_id', $request->product_id)
                                    ->where('size', 'like', $size)
                                    ->first();
        // dd($request->all(), $size_assoc, $allKeys, $current_sizesAndStocks, $checkColor, preg_replace('/([^,]+)/', '$1-' . $color, $request->formatted_sizes));
        if ($checkColor == null) {
            foreach ($size_assoc as $size => $stock) {
                DB::statement('INSERT INTO product_variants (color, size, stock, product_id)
                               VALUES (?, ?, ?, ?)', [$color, $size, $stock, $request->product_id]);
            };
        }
        else {
            foreach ($current_sizesAndStocks as $size => $stock) {
                DB::statement('INSERT INTO product_variants (color, size, stock, product_id)
                               VALUES (?, ?, ?, ?)
                               ON DUPLICATE KEY UPDATE stock = ?', [$color, $size, $stock, $request->product_id, $stock]);
            };
        }

        $inventory->total = $totalQuantity * $data['price'];
        $inventory->save();

        //Thêm vào Chi tiết Phiếu nhập
        $inventoryDetail = new InventoryDetail();
        $inventoryDetail->product_id = $request->product_id;
        $inventoryDetail->inventory_id = $inventory->id;
        $inventoryDetail->price = $data['price'];
        $inventoryDetail->quantity = $totalQuantity;
        //Xử lý chuỗi sizes
        $inventoryDetail->size = preg_replace('/([^,]+)/', '$1-' . $color, $request->formatted_sizes);
                                            // tìm từng phần tử trước dấu phẩy (,) và thêm chuỗi "-$color"
                                            //VD: "XL-1, XXL-2" + "Xanh" -> "XL-1-Xanh, XXL-2-Xanh"
        $inventoryDetail->save();

        return redirect()->route('inventory.index')->with('success', "Thêm phiếu nhập mới thành công!");
    }

    public function search(Request $request) {
    }
}
