@extends('sites.master')
@section('title', 'Lịch sử đơn hàng')

@section('content')
    @if (Session::has('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-lg p-2 move-from-top js-div-dissappear"
            role="alert" style="width: 26rem; display:flex; text-align:center">
            <i class="fas fa-check p-2 bg-success text-white rounded-circle pe-2 mx-2"></i>
            {{ Session::get('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="container mt-4">
        <h3 class="mb-4 text-center">Lịch sử đơn hàng của bạn</h3>

        <!-- Form tìm kiếm -->
        <div class="row mb-3">
            <div class="col-md-8 mx-auto">
                <form method="GET" action="{{ route('sites.getHistoryOrder') }}">
                    <div class="input-group">
                        <input name="query" type="number" class="form-control"
                            placeholder="Nhập ID đơn hàng hoặc số điện thoại...">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-search"></i> Tìm kiếm
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Bảng lịch sử đơn hàng -->
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Tên khách hàng</th>
                        <th>Địa chỉ</th>
                        <th>SĐT</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Ngày đặt</th>
                        <th class="text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($historyOrder as $item)
                        <tr id="orderRow{{ $item->id }}">
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->customer_name }}</td>
                            <td>{{ $item->address }}</td>
                            <td>{{ $item->phone }}</td>
                            <td>{{ number_format($item->total, 0, ',', '.') }} đ</td>
                            <td>
                                <span id="status{{ $item->id }}" class="badge bg-warning">{{ $item->status }}</span>
                            </td>
                            <td>{{ $item->created_at }}</td>
                            <td class="text-center" id="action{{ $item->id }}">
                                <div class="d-flex justify-content-center action-buttons">
                                    <a href="{{ route('sites.showOrderDetailOfCustomer', $item->id) }}"
                                        class="btn btn-sm btn-secondary">
                                        <i class="fa fa-eye"></i> Xem
                                    </a>
                                    @if ($item->status === 'Chờ xử lý')
                                        <button type="button" class="btn btn-sm btn-danger ms-2"
                                            onclick="openCancelModal({{ $item->id }})">
                                            <i class="fa fa-times"></i> Hủy
                                        </button>
                                    @endif
                                </div>
                            </td>

                        </tr>
                    @endforeach
                    @if ($historyOrder->isEmpty())
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="fa fa-box-open fa-2x"></i>
                                <p class="mt-2">Không có đơn hàng nào</p>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    <div class="d-flex justify-content-center mt-3 mb-3">
        {{ $historyOrder->links() }}
    </div>

    <!-- Modal Xác nhận Hủy -->
    <div class="modal fade" id="cancelOrderModal" tabindex="-1" aria-labelledby="cancelOrderModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="cancelOrderModalLabel">Xác nhận hủy đơn hàng</h5>
                    <button type="button" class="btn-close btn-close-modal" data-bs-dismiss="modal"
                        aria-label="Close">X</button>
                </div>
                <div class="modal-body">
                    <p>Bạn có chắc chắn muốn hủy đơn hàng này không?</p>
                    <div class="mb-3">
                        <label for="reason" class="form-label">Lý do hủy</label>
                        <textarea id="reason" class="form-control" placeholder="Nhập lý do hủy..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-close-modal" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-danger" onclick="confirmCancel()">Xác nhận hủy</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/message.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <style>
        .input-group .btn {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }

        .input-group input {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }

        .modal-dialog {
            display: flex;
            align-items: center;
            justify-content: center;
            margin: auto;
            width: 600px;
        }

        .modal-content {
            width: 100%;
        }

        .action-buttons a,
        .action-buttons button {
            margin: 0 5px;
        }

        .toast-success {
            background-color: #6dff8f;
        }

        .toast-error {
            background-color: #dc3545;
        }
    </style>
@endsection


@section('js')
    @if (Session::has('success'))
        <script src="{{ asset('assets/js/message.js') }}"></script>
    @endif

    <!-- Toastr -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        let currentOrderId = null;

        function openCancelModal(orderId) {
            currentOrderId = orderId;
            $('#reason').val(''); // Xóa nội dung cũ
            $('#cancelOrderModal').modal('show');
        }

        function showToast(type, message) {
            toastr[type](message);
        }

        function confirmCancel() {
            const reason = document.getElementById('reason').value;

            if (!reason.trim()) {
                showToast('error', 'Vui lòng nhập lý do hủy!');
                return;
            }

            $.ajax({
                url: "{{ route('sites.cancelOrder', ':id') }}".replace(':id', currentOrderId),
                type: "PUT",
                data: {
                    _token: "{{ csrf_token() }}",
                    reason: reason
                },
                success: function(response) {
                    showToast('success', response.message);
                    $("#status" + currentOrderId).text("Đã hủy");
                    $("#action" + currentOrderId).html(`
                        <div class="action-buttons">
                            <a href="{{ route('sites.showOrderDetailOfCustomer', '') }}/${currentOrderId}"
                                class="btn btn-sm btn-secondary">
                                <i class="fa fa-eye"></i> Xem
                            </a>
                        </div>
                    `);
                    $('#cancelOrderModal').modal('hide');
                    currentOrderId = null;
                },
                error: function(xhr) {
                    showToast('error', 'Có lỗi xảy ra, vui lòng thử lại!');
                }
            });

            $('.btn-close-modal').modal('hide');
        }
    </script>
@endsection
