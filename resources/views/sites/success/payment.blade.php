<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán thành công</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        .success-container {
            text-align: center;
            margin-top: 100px;
        }
        .success-icon {
            font-size: 80px;
            color: #28a745;
        }
        .card {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .vnpay-logo {
            max-width: 150px;
            display: block;
            margin: 0 auto 10px;
        }
    </style>
</head>
<body>
    <div class="container success-container">
        <div class="card">
            <div class="card-body">
                <img src="{{ asset('client/img/payment/' . Session::get('success_data')['logo']) }}" alt="Logo" class="vnpay-logo">
                <i class="success-icon">✔</i>
                <h2 class="mt-1">Thanh toán thành công!</h2>
                <p>Cảm ơn bạn đã mua hàng. Đơn hàng của bạn đang được xử lý.</p>
                <hr>
                <p><strong>Họ tên:</strong> {{Session::get('success_data')['receiver_name']}}</p>
                <p><strong>Mã đơn hàng:</strong>  {{Session::get('success_data')['order_id']}}</p>
                <p><strong>Thời gian thực hiện:</strong> {{ now()->format('d/m/Y - H:i') }}</p>
                <p><strong>Số tiền thanh toán:</strong> {{number_format(Session::get('success_data')['total'], 0, ',', '.') . ' đ'}}</p>
                <p><strong>Trạng thái giao dịch:</strong> Thành công</p>
                <hr>
                <p>📧 Email xác nhận đã được gửi đến bạn.</p>
                <p>Nếu có bất kỳ vấn đề nào, vui lòng liên hệ <strong>1900 1234</strong>.</p>
                <a href="{{ route('sites.home') }}" class="btn btn-primary mt-3">Quay lại trang chủ</a>
                <a href="#" class="btn btn-secondary mt-3">Xem đơn hàng</a>
                <a href="{{ route('sites.home') }}#product-list-home" class="btn btn-success mt-3">Tiếp tục mua sắm</a>
            </div>
        </div>
    </div>
</body>
</html>
