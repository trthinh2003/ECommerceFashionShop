<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Barryvdh\DomPDF\Facade\Pdf;


use SebastianBergmann\CodeCoverage\Report\Xml\Totals;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = DB::table('orders as o')
            ->join('customers as c', 'o.customer_id', '=', 'c.id')
            ->whereIn('o.status', ['Chờ xử lý', 'Đã huỷ đơn hàng']) // Sửa lại đúng logic
            ->orderBy('o.id', 'ASC')
            ->select('o.*', 'c.name as customer_name')
            ->paginate(5);

        return view('admin.order.order_pending', compact('data'));
    }


    public function orderApproval()
    {
        $data = DB::table('orders as o')
            ->join('customers as c', 'o.customer_id', '=', 'c.id')
            ->where('o.status', 'Đã xử lý')
            ->orderBy('o.id', 'ASC')
            ->select('o.*', 'c.name as customer_name')
            ->paginate(5);
        return view('admin.order.order_approved', compact('data'));
    }

    public function orderSuccess()
    {
        $data = DB::table('orders as o')
            ->join('customers as c', 'o.customer_id', '=', 'c.id')
            ->where('o.status', 'Đã thanh toán')
            ->orderBy('o.id', 'ASC')
            ->select('o.*', 'c.name as customer_name')
            ->paginate(5);
        return view('admin.order.order_success', compact('data'));
    }


    public function exportInvoice($id)
    {
        $orderDetail = DB::table('orders as o')
            ->join('customers as c', 'o.customer_id', '=', 'c.id')
            ->join('order_details as od', 'o.id', '=', 'od.order_id')
            ->join('product_variants as pv', 'pv.id', '=', 'od.product_variant_id') // Thay đổi JOIN này
            ->join('products as p', 'p.id', '=', 'pv.product_id') // Lấy sản phẩm từ product_variants
            ->where('o.id', $id)
            ->select(
                'o.*',
                'c.name as customer_name',
                'c.email',
                'p.product_name',
                'p.id as product_id',
                'p.image',
                'od.quantity',
                'od.price',
                'od.code',
                'pv.size',
                'pv.color'
            )
            ->get();

        if ($orderDetail->isEmpty()) {
            return redirect()->back()->with('error', 'Đơn hàng không tồn tại!');
        }

        $pdf = Pdf::loadView('sites.export.pdf.invoice', compact('orderDetail'));
        return $pdf->download('invoice_order_' . $id . '.pdf');
    }





    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'address' => 'required',
            'phone' => 'required',
            'shipping_fee' => 'required|numeric',
            'total' => 'required|numeric',
            'note' => 'required',
            'receiver_name' => 'required',
            'email' => 'required|email',
            'VAT' => 'required|numeric',
            'customer_id' => 'required',
            'payment' => 'required',
        ], [
            'address.required' => 'Vui lòng nhập điểm giao hàng',
            'phone.required' => 'Vui lòng nhập số điện thoại',
            'note.required' => 'Vui lòng nhập ghi chú',
            'receiver_name.required' => 'Vui lòng nhập tên người nhận',
            'email.required' => 'Vui lòng nhập email hợp lệ',
        ]);
        // dd($request->all());
        // Tạo đơn hàng
        $order = new Order();
        $order->address = $data['address'];
        $order->phone = $data['phone'];
        $order->shipping_fee = $data['shipping_fee'];
        $order->total = $data['total'];
        $order->note = $data['note'];
        $order->receiver_name = $data['receiver_name'];
        $order->email = $data['email'];
        $order->VAT = $data['VAT'];
        $order->payment = $data['payment'];
        $order->customer_id = $data['customer_id'];
        $order->save();

        // Lấy giỏ hàng từ session
        $cart = session('cart', []);

        // Lọc ra các sản phẩm đã được chọn (checked = true)
        $selectedItems = array_filter($cart, function ($item) {
            return !empty($item->checked) && $item->checked;
        });

        if (empty($selectedItems)) {
            return redirect()->back()->with('error', 'Không có sản phẩm nào được chọn để thanh toán.');
        }

        // Tạo chi tiết đơn hàng từ các sản phẩm đã chọn
        foreach ($selectedItems as $item) {
            OrderDetail::create([
                'order_id' => $order->id,
                'product_id' => $item->id,
                'product_variant_id' => $item->product_variant_id,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'size_and_color' => $item->size . '-' . $item->color,
                'code' => session('percent_discount', 0),
            ]);
        }

        // Nếu tất cả sản phẩm trong giỏ đều đã chọn, xóa toàn bộ giỏ hàng
        if (count($selectedItems) === count($cart)) {
            session()->forget('cart');
        } else {
            // Cập nhật lại giỏ hàng chỉ giữ lại sản phẩm chưa chọn
            $cart = array_filter($cart, function ($item) {
                return empty($item->checked) || !$item->checked;
            });
            session(['cart' => $cart]);
        }

        session()->forget('percent_discount');

        // Xử lý trừ đi số lượng sản phẩm trong kho theo số lượng đã được đặt
        $orderDetails = OrderDetail::where('order_id', $order->id)->get();

        foreach ($orderDetails as $detail) {
            // Tách size và color từ chuỗi size_and_color
            [$size, $color] = array_map('trim', explode('-', $detail->size_and_color));

            // Tìm đúng variant của sản phẩm trong bảng variant theo product_id, size và color
            $variant = ProductVariant::where('product_id', $detail->product_id)
                ->where('size', trim($size))
                ->where('color', trim($color))
                ->first();


            if ($variant) {
                $variant->stock -= $detail->quantity;
                $variant->save();

                Session::put('success_data', [
                    'logo' => 'cod.png',
                    'receiver_name' => $order->receiver_name,
                    'order_id' => $order->id,
                    'total' => $order->total
                ]);
                Session::forget('order_data'); // Xóa session sau khi lưu vào db
                return redirect()->route('sites.success.payment');
            } else {
                // Xử lý trường hợp không tìm thấy variant
                Log::warning("Không tìm thấy variant cho sản phẩm ID: {$detail->product_id}, size: {$size}, color: {$color}");
            }
        }

        return redirect()->route('sites.home')->with('success', "Đặt hàng thành công!");
    }




    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $data = DB::table('orders as o')
            ->join('customers as c', 'o.customer_id', '=', 'c.id')
            ->join('order_details as od', 'o.id', '=', 'od.order_id')
            ->join('product_variants as pv', 'pv.id', '=', 'od.product_variant_id')
            ->join('products as p', 'p.id', '=', 'pv.product_id')
            ->where('o.id', $order->id)
            ->select('o.*', 'c.name as customer_name', 'p.product_name as product_name', 'p.image', 'pv.size', 'pv.color', 'od.quantity', 'od.price', 'od.code')
            ->get();
        return view('admin.order.order_detail', compact('data'));
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        // dd($order);
        $order->status = "Đã xử lý";
        $order->save();
        return redirect()->route('order.index')->with('success', "Duyệt đơn hàng thành công!");
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }

    public function search(Request $request) {}
}
