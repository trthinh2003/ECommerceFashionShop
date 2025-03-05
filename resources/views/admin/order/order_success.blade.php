@can('salers')
    @extends('admin.master')
    @section('title', 'Đơn hàng đã thanh toán thành công')
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="card-sub">
                <form method="GET" class="form-inline row" action="{{ route('order.search') }}">
                    @csrf
                    <div
                        class="col-9 navbar navbar-header-left navbar-expand-lg navbar-form nav-search p-0 d-none d-lg-flex">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <button type="submit" class="btn btn-search pe-1">
                                    <i class="fa fa-search search-icon"></i>
                                </button>
                            </div>
                            <input name="query" type="text"
                                placeholder="Nhập vào id đơn hàng hoặc số điện thoại để tìm kiếm..." class="form-control" />
                        </div>
                    </div>
                </form>
            </div>
            <table class="table mt-3">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Tên khách hàng</th>
                        <th scope="col">Địa chỉ</th>
                        <th scope="col">Số điện thoại</th>
                        <th scope="col">Tổng tiền</th>
                        <th scope="col">Trạng thái</th>
                        <th scope="col">Ngày tạo</th>
                        <th scope="col" class="text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $model)
                        <tr>
                            <td>{{ $model->id }}</td>
                            <td>{{ $model->customer_name }}</td>
                            <td>{{ $model->address }}</td>
                            <td>{{ $model->phone }}</td>
                            <td>{{ $model->total }}</td>
                            <td>{{ $model->status }}</td>
                            <td>{{ $model->created_at }}</td>
                            <td>
                                <a href="{{ route('order.show', $model->id) }}" class="btn btn-sm btn-secondary"><i
                                        class="fa fa-edit"></i>Xem</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    </hr>
    {{ $data->links() }}
@endsection
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/message.css') }}" />
@endsection

@section('js')
    @if (Session::has('success'))
        <script src="{{ asset('assets/js/message.js') }}"></script>
    @endif
@endsection
@else
{{ abort(403, 'Bạn không có quyền truy cập trang này!') }}
@endcan
