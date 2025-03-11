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
                                    @elseif ($item->status === 'Đã thanh toán')
                                        <button type="button" class="btn btn-sm btn-success ms-2" onclick="openSidebar()">
                                            <i class="fa fa-comments"></i> Đánh giá
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

    <!-- Sidebar -->
    <div id="ratingSidebar" class="sidebar">
        <div class="sidebar-header">
            <h5 class="text-white fw-bold">Đánh Giá Sản Phẩm</h5>
            <button type="button" class="btn-close" onclick="closeSidebar()">×</button>
        </div>
        <div class="sidebar-body">
            <!-- Danh sách sản phẩm -->
            <div class="product-list">
                <div class="product-item d-flex align-items-center mb-3">
                    <img src="{{ asset('client/img/product/product-1.jpg') }}"  alt="Sản phẩm" class="product-image">
                    <div class="product-info w-100" style="margin-left: 20px">
                        <h6>Tên sản phẩm 1</h6>
                        <p>Màu: Đỏ | Size: L</p>
                        <div class="star-rating">
                            <input type="radio" id="star5-1" name="rating1" value="5"><label
                                for="star5-1">★</label>
                            <input type="radio" id="star4-1" name="rating1" value="4"><label
                                for="star4-1">★</label>
                            <input type="radio" id="star3-1" name="rating1" value="3"><label
                                for="star3-1">★</label>
                            <input type="radio" id="star2-1" name="rating1" value="2"><label
                                for="star2-1">★</label>
                            <input type="radio" id="star1-1" name="rating1" value="1"><label
                                for="star1-1">★</label>
                        </div>
                        <textarea class="form-control mt-2" rows="2" placeholder="Viết nhận xét..." id="comment1"></textarea>
                        <button type="button" class="btn btn-success mt-2 w-100" onclick="submitRating(1)">Gửi đánh
                            giá</button>
                    </div>
                </div>
            </div>
            <div class="product-list">
                <div class="product-item d-flex align-items-center mb-3">
                    <img src="{{ asset('client/img/product/product-1.jpg') }}"  alt="Sản phẩm" class="product-image">
                    <div class="product-info w-100" style="margin-left: 20px">
                        <h6>Tên sản phẩm 1</h6>
                        <p>Màu: Đỏ | Size: L</p>
                        <div class="star-rating">
                            <input type="radio" id="star5-1" name="rating1" value="5"><label
                                for="star5-1">★</label>
                            <input type="radio" id="star4-1" name="rating1" value="4"><label
                                for="star4-1">★</label>
                            <input type="radio" id="star3-1" name="rating1" value="3"><label
                                for="star3-1">★</label>
                            <input type="radio" id="star2-1" name="rating1" value="2"><label
                                for="star2-1">★</label>
                            <input type="radio" id="star1-1" name="rating1" value="1"><label
                                for="star1-1">★</label>
                        </div>
                        <textarea class="form-control mt-2" rows="2" placeholder="Viết nhận xét..." id="comment1"></textarea>
                        <button type="button" class="btn btn-success mt-2 w-100" onclick="submitRating(1)">Gửi đánh
                            giá</button>
                    </div>
                </div>
            </div>
            <div class="product-list">
                <div class="product-item d-flex align-items-center mb-3">
                    <img src="{{ asset('client/img/product/product-1.jpg') }}"  alt="Sản phẩm" class="product-image">
                    <div class="product-info w-100" style="margin-left: 20px">
                        <h6>Tên sản phẩm 1</h6>
                        <p>Màu: Đỏ | Size: L</p>
                        <div class="star-rating">
                            <input type="radio" id="star5-1" name="rating1" value="5"><label
                                for="star5-1">★</label>
                            <input type="radio" id="star4-1" name="rating1" value="4"><label
                                for="star4-1">★</label>
                            <input type="radio" id="star3-1" name="rating1" value="3"><label
                                for="star3-1">★</label>
                            <input type="radio" id="star2-1" name="rating1" value="2"><label
                                for="star2-1">★</label>
                            <input type="radio" id="star1-1" name="rating1" value="1"><label
                                for="star1-1">★</label>
                        </div>
                        <textarea class="form-control mt-2" rows="2" placeholder="Viết nhận xét..." id="comment1"></textarea>
                        <button type="button" class="btn btn-success mt-2 w-100" onclick="submitRating(1)">Gửi đánh
                            giá</button>
                    </div>
                </div>
            </div>
            <div class="product-list">
                <div class="product-item d-flex align-items-center mb-3">
                    <img src="{{ asset('client/img/product/product-1.jpg') }}"  alt="Sản phẩm" class="product-image">
                    <div class="product-info w-100" style="margin-left: 20px">
                        <h6>Tên sản phẩm 1</h6>
                        <p>Màu: Đỏ | Size: L</p>
                        <div class="star-rating">
                            <input type="radio" id="star5-1" name="rating1" value="5"><label
                                for="star5-1">★</label>
                            <input type="radio" id="star4-1" name="rating1" value="4"><label
                                for="star4-1">★</label>
                            <input type="radio" id="star3-1" name="rating1" value="3"><label
                                for="star3-1">★</label>
                            <input type="radio" id="star2-1" name="rating1" value="2"><label
                                for="star2-1">★</label>
                            <input type="radio" id="star1-1" name="rating1" value="1"><label
                                for="star1-1">★</label>
                        </div>
                        <textarea class="form-control mt-2" rows="2" placeholder="Viết nhận xét..." id="comment1"></textarea>
                        <button type="button" class="btn btn-success mt-2 w-100" onclick="submitRating(1)">Gửi đánh
                            giá</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Xác nhận Hủy -->
    <div class="modal fade cancel-order-modal" id="cancelOrderModal" tabindex="-1"
        aria-labelledby="cancelOrderModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="cancelOrderModalLabel">Xác nhận hủy đơn hàng</h5>
                    <button type="button" class="btn-close-modal bg-danger border-0 text-white fw-bold"
                        data-bs-dismiss="modal" aria-label="Close">X</button>
                </div>
                <div class="modal-body">
                    <p>Bạn có chắc chắn muốn hủy đơn hàng này không?</p>
                    <div class="mb-3">
                        <label for="reason" class="form-label">Lý do hủy</label>
                        <textarea id="reason" class="form-control" placeholder="Nhập lý do hủy..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-close-modal"
                        data-bs-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-danger" onclick="confirmCancel()">Xác nhận hủy</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/message.css') }}" />
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="{{ asset('client/css/order-history.css') }}">
@endsection


@section('js')
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> --}}
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
        }

        $(document).ready(function() {
            $('.btn-close-modal').on("click", function() {
                $("#cancelOrderModal").modal("hide");
            });
        });
    </script>

    <script>
        function openSidebar() {
            document.getElementById("ratingSidebar").classList.add("active");
        }

        function closeSidebar() {
            document.getElementById("ratingSidebar").classList.remove("active");
        }

        function submitRating(productId) {
            const rating = document.querySelector(`input[name="rating${productId}"]:checked`)?.value;
            const comment = document.getElementById(`comment${productId}`).value;

            if (!rating) {
                alert("Vui lòng chọn số sao!");
                return;
            }

            if (!comment.trim()) {
                alert("Vui lòng nhập nhận xét!");
                return;
            }

            alert(`Đã gửi đánh giá cho sản phẩm ${productId} với ${rating} sao và nhận xét: ${comment}`);
            // Xử lý gửi dữ liệu lên server tại đây (AJAX)
        }
    </script>
@endsection
