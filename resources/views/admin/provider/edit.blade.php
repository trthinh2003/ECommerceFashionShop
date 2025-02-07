@can('warehouse workers')
    @extends('admin.master')
    @section('title', 'Sửa thông tin')

    @section('content')
        <form method="POST" action="{{ route('provider.update', $provider->id) }}">
            @csrf @method('PUT')
            <div class="form-group">
                <label for="">Tên nhà cung cấp</label>
                <input type="text" name="name" id="" class="form-control" placeholder="" value="{{ $provider->name }}">
                @error('name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group">
                <label for="">Địa chỉ nhà cung cấp</label>
                <input type="text" name="address" id="" class="form-control" placeholder="" value="{{$provider->address}}">
                @error('address')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group">
                <label for="">Số điện thoại nhà cung cấp</label>
                <input type="text" name="phone" id="" class="form-control" placeholder="" value="{{$provider->phone}}">
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
