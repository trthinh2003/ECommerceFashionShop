@extends('admin.master')
@section('title', 'Sửa thông tin')

@section('content')
    <form method="POST" action="{{ route('category.update', $data->id) }}">
        @csrf @method('PUT')
        <div class="form-group">
            <label for="">Tên loại</label>
            <input type="text" name="name" id="" class="form-control" placeholder="" value="{{ $data->category_name }}">
            @error('name')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="form-group">
            <div class="form-check">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="status" value="1" @checked($data->status == 1)>
                    Hiển thị
                </label>
            </div>
            <div class="form-check">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="status" value="0" @checked($data->status == 0)>
                    Ẩn
                </label>
            </div>
            @error('status')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <input class="btn btn-primary" name="" type="submit" value="Lưu thông tin">
    </form>
@endsection
