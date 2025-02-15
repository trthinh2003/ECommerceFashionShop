@can('salers')
    @extends('admin.master')
    @section('title', 'Sửa Thông Tin Sản phẩm')
    @section('content')
        <form method="POST" action="{{ route('product.update', $product->id) }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="row form-group">
                <div class="col-5">
                    <label for="">Tên sản phẩm:</label>
                    <input type="text" name="name" id="" class="form-control" placeholder="" value="{{ $product->product_name }}">
                    @error('name')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-4">
                    <label for="">Giá niêm yết:</label>
                    <input type="number" name="price" id="" class="form-control" placeholder="">
                    @error('price')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-3">
                    <label for="">Cập nhật thông tin cho từng kích cỡ:</label><br />
                    <input type="button" name="price_sizes" id="" class="btn btn-success" placeholder="" value="Cập nhật">
                    @error('price')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="row form-group">
                <div class="col-6">
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
                <div class="col-6">
                    <label for="">Hình ảnh:</label>
                    <input type="file" name="image" id="" class="form-control" placeholder="">
                    <img src="uploads/{{ $product->image }}" alt="{{ $product->image }}" width="150">
                    <br />
                    @error('image')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="">Tag:</label>
                <input type="text" data-role="tagsinput" name="product_tags" class="form-control">
            </div>
            @error('product_tags')
                <small class="text-danger">{{ $message }}</small>
            @enderror

            <div class="row form-group">
                <div class="col-6">
                    <label for="">Chất liệu:</label>
                    <input type="text" name="material" id="" class="form-control" placeholder="">
                </div>
                @error('material')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
                <div class="col-6">
                    <label for="">Màu sắc:</label>
                    <input type="text" name="color" id="" class="form-control" placeholder="">
                </div>
                @error('color')
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
    @section('css')
        <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-tagsinput.css') }}" />
    @endsection
    @section('js')
        <script src="{{ asset('assets/js/plugin/bootstrap-tagsinput/bootstrap-tagsinput.min.js') }}"></script>
    @endsection
@else
    {{ abort(403, 'Bạn không có quyền truy cập trang này!') }}
@endcan
