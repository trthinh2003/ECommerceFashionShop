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
                <form method="GET" action="#">
                    <div class="input-group">
                        <input name="query" type="text" class="form-control"
                            placeholder="Nhập ID đơn hàng hoặc số điện thoại...">
                        <button type="submit" class="btn btn-primary me-3">
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
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->customer_name }}</td>
                            <td>{{ $item->address }}</td>
                            <td>{{ $item->phone }}</td>
                            <td>{{ number_format($item->total, 0, ',', '.') }} đ</td>
                            <td><span class="badge bg-warning">{{ $item->status }}</span></td>
                            <td>{{ $item->created_at }}</td>
                            @if ($item->status === 'Chờ xử lý')
                                <td class="text-center">
                                    <div class="d-flex justify-content-center">
                                        <a href="{{ route('sites.showOrderDetailOfCustomer', $item->id) }}"
                                            class="btn btn-sm btn-secondary">
                                            <i class="fa fa-eye"></i> Xem
                                        </a>
                                        <form action="{{ route('sites.cancelOrder', $item->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="_method" value="PUT">
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fa fa-times"></i> Hủy
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            @else
                            <td class="text-center">
                                <div class="d-flex justify-content-center">
                                    <a href="{{ route('sites.showOrderDetailOfCustomer', $item->id) }}"
                                        class="btn btn-sm btn-secondary">
                                        <i class="fa fa-eye"></i> Xem
                                    </a>
                                </div>
                            </td>
                            @endif
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>

        <!-- Phân trang (Dữ liệu mẫu, không có phân trang thật) -->
        {{-- <div class="d-flex justify-content-center mt-3">
        <nav>
            <ul class="pagination">
                <li class="page-item disabled"><a class="page-link" href="#">«</a></li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item"><a class="page-link" href="#">»</a></li>
            </ul>
        </nav>
    </div> --}}

    </div>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/message.css') }}" />
@endsection

@section('js')
    @if (Session::has('success'))
        <script src="{{ asset('assets/js/message.js') }}"></script>
    @endif
@endsection
