@can('salers')
    @extends('admin.master')
    @section('title', 'Sửa Thông Tin Sản phẩm')

    @section('content')
        <form method="POST" action="{{ route('product.update', $product->id) }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="form-group">
                <label for="">Tên sản phẩm:</label>
                <input type="text" name="name" id="" class="form-control" placeholder="" value="{{ $product->product_name }}">
                @error('name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="">Giá:</label>
                <input type="text" name="price" id="" class="form-control" placeholder="" value="{{ $product->price }}">
                @error('price')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="">Danh mục:</label>
                <select class="form-control" name="category_id">
                    <option>Chọn danh mục</option>
                    @foreach ($cats as $cat)
                        <option value="{{ $cat->id }}" @selected($cat->id == $product->category_id)>{{ $cat->category_name }}</option>
                    @endforeach
                </select>
                @error('category_id')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="">Hình ảnh:</label>
                <input type="file" name="image" id="" class="form-control" placeholder="">
                <img src="uploads/{{ $product->image }}" alt="{{ $product->image }}" width="200">
                <br />
                @error('image')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="">Mô tả:</label>
                <textarea class="form-control" name="description" placeholder="Mô tả sản phẩm...">{{ $product->description }}</textarea>
            </div>
            @error('description')
                <small class="text-danger">{{ $message }}</small>
            @enderror

            <div class="form-group">
                <div class="form-check">
                    <label class="form-check-label">
                        <input type="radio" class="form-check-input" name="status" value="1" @checked($product->status == 1)>
                        Hiển thị
                    </label>
                </div>
                <div class="form-check">
                    <label class="form-check-label">
                        <input type="radio" class="form-check-input" name="status" value="0" @checked($product->status == 0)>
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
@else
    {{ abort(403, 'Bạn không có quyền truy cập trang này!') }}
@endcan
