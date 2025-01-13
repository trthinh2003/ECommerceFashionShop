@extends('admin.master')

@section('title', 'Thông tin Loại sản phẩm');

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="card-title">Thông tin</div>
        </div>
        <div class="card-sub">
            <form class="form-inline row" action="">
                <div class="col-9">
                    <label for="inputCategory" class="visually-hidden">Category Name</label>
                    <input type="text" class="form-control" id="inputCategory" placeholder="Enter name...">
                </div>
                <div class="col-3">
                    <button type="submit" class="btn btn-success"><i class="fa fa-search"></i></button>
                    <a href="{{ route('category.create') }}" class="btn btn-warning"><i class="fa fa-plus"></i>Thêm mới</a>
                </div>
            </form>
        </div>
        <div class="card-body">
            <table class="table mt-3">
                <thead>
                    <tr>
                        <th scope="col">Mã loại</th>
                        <th scope="col">Tên</th>
                        <th scope="col">Trạng thái</th>
                        <th scope="col">Số lượng</th>
                        <th scope="col">Create at</th>
                        <th scope="col" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->category_name }}</td>
                        <td>{{ $item->status == 0 ? "Đang ẩn" : "Hiển thị" }}</td>
                        <td>{{ $item->Products->count() }}</td>
                        <td>{{ $item->created_at->format('d/m/y') }}</td>
                        <td class="text-center">
                            <form method="post" action="">
                                <a class="btn btn-sm btn-primary" href=""><i class="fa fa-edit pe-2"></i>Sửa</a>
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc muốn xóa không?')">
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
@endsection
