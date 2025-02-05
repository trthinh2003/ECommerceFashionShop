@extends('admin.master')
@section('title', 'Thông tin Nhân viên')
@section('content')
    @if (Session::has('success'))
        <div class="shadow-lg p-2 move-from-top js-div-dissappear" style="width: 25rem; display:flex; text-align:center">
            <i class="fas fa-check p-2 bg-success text-white rounded-circle pe-2 mx-2"></i>{{ Session::get('success') }}
        </div>
    @endif
    <div class="card">
        <div class="card-body">
            <div class="card-sub">
                <form method="GET" class="form-inline row" action="{{ route('staff.search') }}">
                    @csrf
                    <div
                        class="col-9 navbar navbar-header-left navbar-expand-lg navbar-form nav-search p-0 d-none d-lg-flex">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <button type="submit" class="btn btn-search pe-1">
                                    <i class="fa fa-search search-icon"></i>
                                </button>
                            </div>
                            <input name="query" type="text" placeholder="Nhập vào tên nhân viên..."
                                class="form-control" />
                        </div>
                    </div>
                    <div class="col-3">
                        <a href="{{ route('staff.create') }}" type="submit" class="btn btn-success"><i
                                class="fa fa-plus"></i>Thêm mới</a>
                    </div>
                </form>
            </div>
            <table class="table mt-3">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Họ tên nhân viên</th>
                        <th scope="col">Số điện thoại</th>
                        <th scope="col">Địa chỉ</th>
                        <th scope="col">Email</th>
                        <th scope="col">Giới tính</th>
                        {{-- <th scope="col">Tài khoản</th>
                        <th scope="col">Mật khẩu</th> --}}
                        <th scope="col">Chức vụ</th>
                        {{-- <th scope="col">Role</th> --}}
                        <th scope="col">Trạng thái</th>
                        {{-- <th scope="col">Ngày Thêm</th> --}}
                        <th scope="col" class="text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $model)
                        <tr>
                            <td>{{ $model->id }}</td>
                            <td>{{ $model->name }}</td>
                            <td>{{ $model->phone }}</td>
                            <td>{{ $model->address }}</td>
                            <td>{{ $model->email }}</td>
                            <td>{{ $model->sex == 1 ? "Nam" : "Nữ" }}</td>
                            {{-- <td>{{ $model->username }}</td>
                            <td>{{ $model->password }}</td> --}}
                            <td>{{ $model->position }}</td>
                            {{-- <td>{{ $model->role }}</td> --}}
                            <td>{{ $model->status }}</td>
                            {{-- <td>{{ $model->created_at->format('d/m/Y') }}</td> --}}
                            <td class="text-center">
                                <form method="post" action="{{ route('staff.destroy', $model->id) }}">
                                    @csrf @method('DELETE')
                                    <a class="btn btn-sm btn-secondary" href=""><i class="fa fa-edit pe-2"></i>Chi
                                        tiết</a>
                                    <a class="btn btn-sm btn-primary" href="{{ route('staff.edit', $model->id) }}"><i
                                            class="fa fa-edit pe-2"></i>Sửa</a>
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
