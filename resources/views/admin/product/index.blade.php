@can('salers')
    @extends('admin.master')
    @section('title', 'Thông tin Sản phẩm')
    @section('content')
        <div class="card">
            <div class="card-body">
                <div class="card-sub">
                    <form method="GET" class="form-inline row" action="{{ route('product.search') }}">
                        @csrf
                        <div class="col-9 navbar navbar-header-left navbar-expand-lg navbar-form nav-search p-0 d-none d-lg-flex">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <button type="submit" class="btn btn-search pe-1">
                                        <i class="fa fa-search search-icon"></i>
                                    </button>
                                </div>
                                <input name="query" type="text" placeholder="Nhập vào tên sản phẩm cần tìm..." class="form-control" />
                            </div>
                        </div>
                        <div class="col-3">
                            <a href="{{ route('product.create') }}" class="btn btn-success"><i class="fa fa-plus"></i>Thêm mới</a>
                        </div>
                    </form>
                </div>
                <table class="table mt-3">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Tên sản phẩm</th>
                            <th scope="col">Danh mục</th>
                            <th scope="col">Giá bán</th>
                            <th scope="col">Trạng thái</th>
                            <th scope="col">Ngày thêm</th>
                            <th scope="col">Hình ảnh</th>
                            <th scope="col" class="text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $model)
                            <tr>
                                <td>{{ $model->id }}</td>
                                <td>{{ $model->product_name }}</td>
                                <td>{{ $model->Category->category_name }}</td>
                                <td>{{ $model->price }}</td>
                                <td>{{ $model->status == 0 ? 'Ẩn' : 'Hiển thị' }}</td>
                                <td>{{ $model->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <img src="uploads/{{ $model->image }}" alt="{{ $model->image }}" width="45">
                                </td>
                                <td>
                                    <form method="post" action="{{ route('product.destroy', $model->id) }}">
                                        @csrf @method('DELETE')
                                        <a href="{{ route('product.edit', $model->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-edit pe-2"></i>Sửa</a>
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
        </hr>
        {{ $data->links() }}
    @endsection
@else
    {{ abort(403, 'Bạn không có quyền truy cập trang này!') }}
@endcan
