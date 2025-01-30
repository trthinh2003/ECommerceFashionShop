@extends('admin.master')
@section('title', 'Thêm Danh mục')

@section('content')
    <form method="POST" action="{{ route('category.store') }}">
        @csrf
        <div class="form-group">
            <label for="">Tên danh mục:</label>
            <input type="text" name="name" id="" class="form-control" placeholder="">
            @error('name')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="form-group">
            <div class="form-check">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="status" value="1" checked>
                    Hiển thị
                </label>
            </div>
            <div class="form-check">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="status" value="0" checked>
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
