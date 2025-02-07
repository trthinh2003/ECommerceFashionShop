@can('warehouse workers')
    @extends('admin.master')
    @section('title', 'Thêm Phiếu nhập')

    @section('content')
        <form method="POST" action="{{ route('inventory.store') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" value="{{ auth()->user()->id - 1 }}">

            <div class="row form-group">
                <div class="col-6 ">
                    <label for="">Tên sản phẩm:</label>
                    <input type="text" name="product_name" id="" class="form-control" placeholder="">
                    @error('product_name')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-6">
                    <label for="">Hình ảnh:</label>
                    <input type="file" name="image" id="fileInput" class="form-control" placeholder="" accept="image/*">
                    @error('image')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                    <div id="preview">
                        <img class="img-thumbnail rounded m-3 d-none" id="previewImg" src="" alt=""
                            width="200">
                    </div>
                </div>
            </div>

            <div class="row form-group">
                <div class="col-6">
                    <label for="">Danh mục:</label>
                    <select class="form-control" name="category_id">
                        <option>--Chọn danh mục--</option>
                        @foreach ($cats as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->category_name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-6">
                    <label for="">Chọn nhà cung cấp:</label>
                    <select class="form-control" name="provider_id">
                        <option>--Chọn nhà cung cấp--</option>
                        @foreach ($providers as $provider)
                            <option value="{{ $provider->id }}">{{ $provider->name }}</option>
                        @endforeach
                    </select>
                    @error('provider_id')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="row form-group">
                <div class="col-6">
                    <label for="">Giá nhập:</label>
                    <input type="number" name="price" id="priceInput" class="form-control" placeholder="">
                    @error('price')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-6 ">
                    <label for="">Số lượng nhập:</label>
                    <input type="number" name="quantity" id="quantityInput" class="form-control" placeholder="">
                    @error('quantity')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="row form-group">
                <div class="col-6">
                    <label for="">Màu:</label>
                    <input type="text" name="color" id="priceInput" class="form-control" placeholder="">
                    @error('color')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-6">
                    <label>Chọn kích cỡ:</label>
                    <select class="form-control" name="sizes[]" id="sizes" multiple="multiple">
                        <option value="XS">XS</option>
                        <option value="S">S</option>
                        <option value="M">M</option>
                        <option value="L">L</option>
                        <option value="XL">XL</option>
                        <option value="XXL">XXL</option>
                    </select>
                    @error('sizes')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <input class="btn btn-primary m-3" name="" type="submit" value="Lưu thông tin">
        </form>
    @endsection

    @section('js')
        <script src="{{ asset('assets/js/plugin/select2/select2.full.min.js') }}"></script>
        <script>
            $(document).ready(function() {
                //Xử lý input nhập file hình ảnh
                $("#fileInput").change(function(e) {
                    var file = e.target.files[0];
                    var validTypes = ["image/jpg", "image/jpeg", "image/png", "image/gif", "image/webp"];

                    if (file) {
                        if (validTypes.includes(file.type)) {
                            var reader = new FileReader();
                            reader.onload = function(e) {
                                $("#previewImg").attr("src", e.target.result).show();
                                $("#previewImg").removeClass('d-none');
                            };
                            reader.readAsDataURL(file);
                        } else {
                            $(this).val("");
                            $("#previewImg").hide();
                        }
                    }
                });
                //Xử lý input nhập kích cỡ
                $("#sizes").select2({
                    tags: true,
                    tokenSeparators: [','],
                    placeholder: "  Chọn hoặc nhập kích cỡ sản phẩm..."
                });
                //

            });
        </script>
        {{-- <script>
            $(document).ready(function() {
                $('#priceInput').on('input', function() {
                    let value = $(this).val().replace(/,/g, '');
                    if (!isNaN(value) && value !== '') {
                        $(this).val(Number(value).toLocaleString('en-US'));
                    }
                });
            });
        </script> --}}
    @endsection
@else
    {{ abort(403, 'Bạn không có quyền truy cập trang này!') }}
@endcan

