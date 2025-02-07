@can('salers')
    @extends('admin.master')
    @section('title', 'Thông tin Danh mục')

    @section('content')
        <div class="card">
            <div class="card-body">
                <div class="card-sub">
                    <form method="GET" class="form-inline row" action="{{ route('category.search') }}">
                        @csrf
                        <div
                            class="col-9 navbar navbar-header-left navbar-expand-lg navbar-form nav-search p-0 d-none d-lg-flex">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <button type="submit" class="btn btn-search pe-1">
                                        <i class="fa fa-search search-icon"></i>
                                    </button>
                                </div>
                                <input name="query" type="text" placeholder="Nhập vào tên danh mục cần tìm..."
                                    class="form-control" />
                            </div>
                        </div>
                        <div class="col-3">
                            <a href="{{ route('category.create') }}" type="submit" class="btn btn-success"><i
                                    class="fa fa-plus"></i>Thêm mới</a>
                        </div>
                    </form>
                </div>
                <table class="table mt-3">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Tên danh mục</th>
                            <th scope="col">Trạng thái</th>
                            <th scope="col">Số lượng</th>
                            <th scope="col">Ngày thêm</th>
                            <th scope="col" class="text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $model)
                            <tr>
                                <td>{{ $model->id }}</td>
                                <td>{{ $model->category_name }}</td>
                                <td>{{ $model->status == 0 ? 'Ẩn' : 'Hiển thị' }}</td>
                                <td>{{ $model->Products->count() }}</td>
                                <td>{{ $model->created_at->format('d/m/Y') }}</td>
                                <td class="text-center">
                                    <form method="post" action="{{ route('category.destroy', $model->id) }}">
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
@else
    {{ abort(403, 'Bạn không có quyền truy cập trang này!') }}
@endcan
