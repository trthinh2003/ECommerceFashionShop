<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\ProductVariant;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller
{
    public function checkout(Request $request)
    {
        switch ($request->payment) {
            case 'vnpay':
                // dd($request->all());
                return $this->checkoutVnpay($request);
                break;
            case 'momo':
                return $this->checkoutMomo($request);
                break;
            case 'zalopay':
                return $this->checkoutZaloPay($request);
                break;
            default:
                return abort('404', 'Trang không tồn tại');
                break;
        }
    }

    // thông tin test Ngân hàng: NCB
    // Số thẻ: 9704198526191432198
    // Tên chủ thẻ:NGUYEN VAN A
    // Ngày phát hành:07/15
    // Mật khẩu OTP:123456

    // Chưa làm trang thông báo giao dịch thành công và xem lại đơn hàng ạ nên về sẽ về trang chủ khi giao dịch thành công

    public function checkoutVnpay(Request $request)
    {
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        Session::put('order_data', $request->all()); // Lưu toàn bộ dữ liệu vào session trước khi chuyển hướng VNPAY
        // link trả về trang checkout
        $vnp_Returnurl = "http://127.0.0.1:8000/payment";

        $vnp_TmnCode = "N528S1UI"; //Mã website tại VNPAY 
        $vnp_HashSecret = "DJONF4NR7QM5BQ0RYCJNFLDOGSZGPZMN"; //Chuỗi bí mật
        // mã ảo
        $vnp_TxnRef = Str::random(10); //Mã đơn hàng. Trong thực tế Merchant cần insert đơn hàng vào DB và gửi mã này sang VNPAY
        $vnp_OrderInfo = "Thanh toán đơn hàng test";
        $vnp_OrderType = "billpayment";
        $vnp_Amount = ($request->total) * 100;
        $vnp_Locale = 'vn';
        $vnp_BankCode = "NCB";
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];
        //Add Params of 2.0.1 Version
        // $vnp_ExpireDate = $_POST['txtexpire'];
        //Billing
        // $vnp_Bill_Mobile = $_POST['txt_billing_mobile'];
        // $vnp_Bill_Email = $_POST['txt_billing_email'];
        // $fullName = trim($_POST['txt_billing_fullname']);
        // if (isset($fullName) && trim($fullName) != '') {
        //     $name = explode(' ', $fullName);
        //     $vnp_Bill_FirstName = array_shift($name);
        //     $vnp_Bill_LastName = array_pop($name);
        // }
        // $vnp_Bill_Address = $_POST['txt_inv_addr1'];
        // $vnp_Bill_City = $_POST['txt_bill_city'];
        // $vnp_Bill_Country = $_POST['txt_bill_country'];
        // $vnp_Bill_State = $_POST['txt_bill_state'];
        // // Invoice
        // $vnp_Inv_Phone = $_POST['txt_inv_mobile'];
        // $vnp_Inv_Email = $_POST['txt_inv_email'];
        // $vnp_Inv_Customer = $_POST['txt_inv_customer'];
        // $vnp_Inv_Address = $_POST['txt_inv_addr1'];
        // $vnp_Inv_Company = $_POST['txt_inv_company'];
        // $vnp_Inv_Taxcode = $_POST['txt_inv_taxcode'];
        // $vnp_Inv_Type = $_POST['cbo_inv_type'];
        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
            // "vnp_ExpireDate" => $vnp_ExpireDate,
            // "vnp_Bill_Mobile" => $vnp_Bill_Mobile,
            // "vnp_Bill_Email" => $vnp_Bill_Email,
            // "vnp_Bill_FirstName" => $vnp_Bill_FirstName,
            // "vnp_Bill_LastName" => $vnp_Bill_LastName,
            // "vnp_Bill_Address" => $vnp_Bill_Address,
            // "vnp_Bill_City" => $vnp_Bill_City,
            // "vnp_Bill_Country" => $vnp_Bill_Country,
            // "vnp_Inv_Phone" => $vnp_Inv_Phone,
            // "vnp_Inv_Email" => $vnp_Inv_Email,
            // "vnp_Inv_Customer" => $vnp_Inv_Customer,
            // "vnp_Inv_Address" => $vnp_Inv_Address,
            // "vnp_Inv_Company" => $vnp_Inv_Company,
            // "vnp_Inv_Taxcode" => $vnp_Inv_Taxcode,
            // "vnp_Inv_Type" => $vnp_Inv_Type
        );

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }
        if (isset($vnp_Bill_State) && $vnp_Bill_State != "") {
            $inputData['vnp_Bill_State'] = $vnp_Bill_State;
        }

        //var_dump($inputData);
        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret); //  
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }
        $returnData = array(
            'code' => '00',
            'message' => 'success',
            'data' => $vnp_Url
        );
        if ($request->input('redirect')) {
            header('Location: ' . $vnp_Url);
            die();
        } else {
            echo json_encode($returnData);
        }
        return redirect()->away($vnp_Url);
    }

    public function vnpayReturn(Request $request)
    {
        $vnp_ResponseCode = $request->vnp_ResponseCode; // Mã phản hồi từ VNPAY
        $vnp_TxnRef = $request->vnp_TxnRef; // Mã giao dịch đơn hàng
        $vnp_Amount = $request->vnp_Amount / 100; // Số tiền thanh toán (chuyển về đơn vị VNĐ)

        if ($vnp_ResponseCode == "00") { // Thanh toán thành công
            if (Session::has('order_data')) {
                $data = Session::get('order_data');
                // dd($data);

                // Tạo đơn hàng lưu vào db
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
                $order->status = 'Đã thanh toán'; // Đánh dấu đơn hàng đã thanh toán
                $order->transaction_id = $vnp_TxnRef; // Lưu mã giao dịch VNPAY
                $order->save();

                // Lưu chi tiết đơn hàng vào db
                if (Session::has('cart') && count(Session::get('cart')) > 0) {
                    foreach (Session::get('cart') as $items) {
                        OrderDetail::create([
                            'order_id' => $order->id,
                            'product_id' => $items->id,
                            'quantity' => $items->quantity,
                            'price' => $items->price,
                            'size_and_color' => $items->size . '-' . $items->color
                        ]);
                    }
                    Session::forget('cart');
                }

                // Cập nhật số lượng tồn kho sau khi tạo đơn hàng
                $orderDetails = OrderDetail::where('order_id', $order->id)->get();
                foreach ($orderDetails as $detail) {
                    [$size, $color] = explode('-', $detail->size_and_color);
                    $variant = ProductVariant::where('product_id', $detail->product_id)
                        ->where('size', trim($size))
                        ->where('color', trim($color))
                        ->first();

                    if ($variant) {
                        $variant->stock -= $detail->quantity;
                        $variant->save();
                    }
                }

                Session::forget('order_data'); // Xóa session sau khi lưu vào db
                return redirect()->route('sites.home')->with('success', 'Thanh toán thành công! Đơn hàng của bạn đã được lưu.');
            }
        } else {
            return redirect()->route('sites.cart')->with('error', 'Thanh toán thất bại hoặc bị hủy!');
        }
    }


    public function checkoutMomo(Request $request) {}

    public function checkoutZaloPay(Request $request) {}
}
