@extends('admin.master')
@section('title', 'Thông tin Khuyến mãi')
@section('content')
    @if (Session::has('addSuccess'))
        <div class="shadow-lg p-2 move-from-top js-div-dissappear" style="width: 28rem; display:flex; text-align:center">
            <i
                class="fas fa-check p-2 bg-success text-white rounded-circle pe-2 mx-2"></i>{{ Session::get('addSuccess') }}
        </div>
    @endif
    <div class="card">
        <div class="card-body">
            <div class="card-sub">
                <form method="GET" class="form-inline row" action="{{ route('discount.search') }}">
                    @csrf
                    <div
                        class="col-9 navbar navbar-header-left navbar-expand-lg navbar-form nav-search p-0 d-none d-lg-flex">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <button type="submit" class="btn btn-search pe-1">
                                    <i class="fa fa-search search-icon"></i>
                                </button>
                            </div>
                            <input name="query" type="text" placeholder="Nhập vào tên chương trình khuyến mãi..."
                                class="form-control" />
                        </div>
                    </div>
                    <div class="col-3">
                        <a href="{{ route('discount.create') }}" type="submit" class="btn btn-success"><i
                                class="fa fa-plus"></i>Thêm mới</a>
                    </div>
                </form>
            </div>
            <table class="table mt-3">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Tên chương trình</th>
                        <th scope="col">Phần trăm khuyến mãi</th>
                        <th scope="col">Ngày bắt đầu</th>
                        <th scope="col">Ngày kết thúc</th>
                        <th scope="col" class="text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $model)
                        <tr>
                            <td>{{ $model->id }}</td>
                            <td>{{ $model->name }}</td>
                            <td>{{ round($model->percent_discount, 2)*100 }}%</td>
                            <td>{{ $model->start_date->format('d/m/Y H:i:s') }}</td>
                            <td>{{ $model->end_date->format('d/m/Y H:i:s') }}</td>
                            <td class="text-center">
                                <form method="post" action="{{ route('discount.destroy', $model->id) }}">
                                    @csrf @method('DELETE')
                                    <a class="btn btn-sm btn-primary" href=""><i class="fa fa-edit pe-2"></i>Sửa</a>
                                    <button class="btn btn-sm btn-danger"
                                        onclick="return confirm('Bạn có chắc muốn xóa không?')">
                                        <i class="fa fa-trash pe-2"></i>
                                        Xóa
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    {{ $data->links() }}
@endsection


@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/message.css') }}" />
@endsection

@section('js')
    <script src="{{ asset('assets/js/message.js') }}"></script>
@endsection
