@extends('admin.master')
@section('title', 'Thêm nhân viên')

@section('content')
    <form method="POST" action="{{ route('staff.store') }}">
        @csrf
        <div class="form-group">
            <label for="">Tên nhân viên</label>
            <input type="text" name="name" id="" class="form-control" placeholder="">
            @error('name')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="form-group">
            <label for="">Số điện thoại</label>
            <input type="text" name="phone" id="" class="form-control" placeholder="">
            @error('phone')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="form-group">
            <label for="">Địa chỉ nhân viên</label>
            <input type="text" name="address" id="" class="form-control" placeholder="">
            @error('address')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="form-group">
            <label for="">Email nhân viên</label>
            <input type="text" name="email" id="" class="form-control" placeholder="">
            @error('email')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="form-group">
            <div class="form-check">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="sex" value="1" checked>
                    Nam
                </label>
            </div>
            <div class="form-check">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="sex" value="0" checked>
                    Nữ
                </label>
            </div>
            @error('sex')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="form-group">
            <label for="">Tài khoản</label>
            <input type="text" name="username" id="" class="form-control" placeholder="">
            @error('username')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="form-group">
            <label for="">Mật khẩu</label>
            <input type="text" name="password" id="" class="form-control" placeholder="">
            @error('password')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="form-group">
            <label for="">Chức vụ</label>
            <input type="text" name="position" id="" class="form-control" placeholder="">
            @error('position')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="form-group">
            <label for="">Role</label>
            <input type="text" name="role" id="" class="form-control" placeholder="">
            @error('role')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="form-group">
            <label for="">Trạng thái làm việc</label>
            <input type="text" name="status" id="" class="form-control" placeholder="">
            @error('status')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <input class="btn btn-primary" name="" type="submit" value="Lưu thông tin">
    </form>
@endsection
