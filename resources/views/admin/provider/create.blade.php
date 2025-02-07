@can('warehouse workers')
    @extends('admin.master')
    @section('title', 'Thêm nhà cung cấp')

    @section('content')
        <form method="POST" action="{{ route('provider.store') }}">
            @csrf
            <div class="form-group">
                <label for="">Tên nhà cung cấp</label>
                <input type="text" name="name" id="" class="form-control" placeholder="">
                @error('name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group">
                <label for="">Địa chỉ nhà cung cấp</label>
                <input type="text" name="address" id="" class="form-control" placeholder="">
                @error('address')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group">
                <label for="">Số điện thoại nhà cung cấp</label>
                <input type="text" name="phone" id="" class="form-control" placeholder="">
                @error('phone')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <input class="btn btn-primary" name="" type="submit" value="Lưu thông tin">
        </form>
    @endsection
@else
    {{ abort(403, 'Bạn không có quyền truy cập trang này!') }}
@endcan
