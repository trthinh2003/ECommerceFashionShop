@extends('admin.master')
@section('title', 'Thêm Sản phẩm')

@section('content')
    <form method="POST" action="{{ route('product.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="">Tên sản phẩm:</label>
            <input type="text" name="name" id="" class="form-control" placeholder="">
            @error('name')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="">Danh mục:</label>
            <select class="form-control" name="category_id">
                <option>Chọn danh mục</option>
                @foreach ($cats as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->category_name }}</option>
                @endforeach
            </select>
            @error('category_id')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="">Giá:</label>
            <input type="text" name="price" id="" class="form-control" placeholder="">
            @error('price')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="">Hình ảnh:</label>
            <input type="file" name="image" id="" class="form-control" placeholder="">
            @error('image')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="">Mô tả:</label>
            <textarea class="form-control" name="description" placeholder="Mô tả sản phẩm..."></textarea>
        </div>
        @error('description')
            <small class="text-danger">{{ $message }}</small>
        @enderror

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
