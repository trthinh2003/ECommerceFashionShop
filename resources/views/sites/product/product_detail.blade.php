@php
    $totalProduct = 0;
    if (Session::has('cart')) {
        foreach (Session::get('cart') as $item) {
            $totalProduct += $item->quantity;
        }
    } else {
        $totalProduct = 0;
    }
    // Xử lý màu sắc (hơi mủ)
    function getColorHex($color)
    {
        $colorMap = [
            'Đen' => '#000000',
            'Vàng' => '#FFD700',
            'Trắng' => '#FFFFFF',
            'Xanh' => '#007BFF',
            'Xanh lá' => '#28a745',
            'Đỏ' => '#FF0000',
            'Hồng' => '#FFC0CB',
            'Cam' => '#FFA500',
        ];
        return $colorMap[$color] ?? '#CCCCCC';
    }

@endphp

@extends('sites.master')
@section('title', $productDetail->product_name)
@section('content')
    <!-- Shop Details Section Begin -->
    <section class="shop-details">
        <div class="product__details__pic">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="product__details__breadcrumb">
                            <a href="{{ route('sites.home') }}">Home</a>
                            <a href="{{ route('sites.shop') }}">Shop</a>
                            <span>{{ $productDetail->product_name }}</span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-md-3">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                {{-- hình gốc của sản phẩm (list thumnail) --}}
                                <a class="nav-link active" data-toggle="tab" href="#tabs-1" role="tab">
                                    <div class="product__thumb__pic set-bg"
                                        data-setbg="{{ asset('uploads/' . $productDetail->image) }}">
                                    </div>
                                </a>
                            </li>

                            {{-- các hình của varians --}}
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#tabs-2" role="tab">
                                    <div class="product__thumb__pic set-bg"
                                        data-setbg="{{ 'client/img/shop-details/thumb-2.png' }}">
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#tabs-3" role="tab">
                                    <div class="product__thumb__pic set-bg"
                                        data-setbg="{{ 'client/img/shop-details/thumb-3.png' }}">
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#tabs-4" role="tab">
                                    <div class="product__thumb__pic set-bg"
                                        data-setbg="{{ 'client/img/shop-details/thumb-4.png' }}">
                                        <i class="fa fa-play"></i>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-lg-6 col-md-9">
                        <div class="tab-content">
                            {{-- hình gốc của sản phẩm (panel) --}}
                            <div class="tab-pane active" id="tabs-1" role="tabpanel">
                                <div class="product__details__pic__item">
                                    <img src="{{ asset('uploads/' . $productDetail->image) }}" alt="">
                                </div>
                            </div>
                            <div class="tab-pane" id="tabs-2" role="tabpanel">
                                <div class="product__details__pic__item">
                                    <img src="{{ 'client/img/shop-details/product-big-3.png' }}" alt="">
                                </div>
                            </div>
                            <div class="tab-pane" id="tabs-3" role="tabpanel">
                                <div class="product__details__pic__item">
                                    <img src="{{ 'client/img/shop-details/product-big.png' }}" alt="">
                                </div>
                            </div>
                            <div class="tab-pane" id="tabs-4" role="tabpanel">
                                <div class="product__details__pic__item">
                                    <img src="{{ 'client/img/shop-details/product-big-4.png' }}" alt="">
                                    <a href="https://www.youtube.com/watch?v=8PJ3_p7VqHw&list=RD8PJ3_p7VqHw&start_radio=1"
                                        class="video-popup"><i class="fa fa-play"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="product__details__content">
            <div class="container">
                <div class="row d-flex justify-content-center">

                    <div class="col-lg-8">
                        <form action="{{ route('sites.addFromDetail', $productDetail->id) }}" method="post">
                            @csrf
                            <div class="product__details__text">
                                <h4>{{ $productDetail->product_name }}</h4>
                                <div class="rating">
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star-o"></i>
                                    <span> - 5 Đánh Giá</span>
                                </div>
                                <h3>{{ number_format($productDetail->price) }}đ<span>{{ number_format($productDetail->price) }}</span>
                                </h3>
                                <p>{{ $productDetail->short_description }}</p>
                                <div class="product__details__option">
                                    <div class="product__details__option__size">
                                        <span>Kích cỡ:</span>
                                        @foreach ($sizes as $size)
                                            <label for="size-{{ $size }}">{{ $size }}
                                                <input type="radio" name="size" id="size-{{ $size }}"
                                                    value="{{ $size }}" required>
                                                <input type="radio" name="size" value="" hidden checked>
                                            </label>
                                        @endforeach
                                    </div>
                                    <div class="product__details__option__color">
                                        <span>Màu Sắc:</span>
                                        @foreach ($colors as $index => $color)
                                            <label class="color-box" style="background-color: {{ getColorHex($color) }};"
                                                for="color-{{ $index }}" title="{{ $color }}">
                                                <input type="radio" name="color" id="color-{{ $index }}"
                                                    class="color-choice-item" value="{{ $color }}" required>
                                            </label>
                                        @endforeach
                                    </div>

                                    <script>
                                        document.querySelectorAll('.color-choice-item').forEach(item => {
                                            item.addEventListener('change', async (e) => {
                                                // Xóa viền tất cả label trước khi thêm viền mới
                                                document.querySelectorAll('.color-box').forEach(label => label.style.border = 'none');

                                                // Thêm viền xanh cho label được chọn
                                                let selectedLabel = document.querySelector(`label[for="${e.target.id}"]`);
                                                if (selectedLabel) {
                                                    selectedLabel.style.border = '3px solid blue';
                                                }

                                                // Lấy ID sản phẩm từ Laravel Blade
                                                let productId = @json($productDetail->id);

                                                try {
                                                    let response = await fetch(
                                                        `http://127.0.0.1:8000/api/product-variant-size/${item.value}/${productId}`);
                                                    let data = await response.json();

                                                    if (data.status_code === 200) {
                                                        let availableSizes = data.data.map(variant => variant
                                                            .size); // Lấy danh sách size có sẵn

                                                        document.querySelectorAll('.product__details__option__size label').forEach(
                                                            label => {
                                                                let input = label.querySelector('input[type="radio"]');
                                                                if (availableSizes.includes(input.value)) {
                                                                    input.disabled = false;
                                                                    label.style.textDecoration = "none";
                                                                    label.style.opacity = "1";
                                                                    document.querySelector('.quantity-input').setAttribute("max", )

                                                                } else {
                                                                    input.disabled = true;
                                                                    label.style.textDecoration = "line-through";
                                                                    label.style.opacity = "0.5";
                                                                }
                                                            });
                                                    }
                                                } catch (error) {
                                                    console.error("Lỗi khi fetch dữ liệu:", error);
                                                }
                                            });
                                        });
                                    </script>
                                </div>
                                <div class="product__details__cart__option">
                                    <div class="quantity">
                                        <div class="pro-qty">
                                            <input class="quantity-input" type="text" name="quantity" value="1"
                                                min="1" max="{{ $productDetail->stock }}">
                                        </div>
                                        @error('quantity')
                                            <script>
                                                alert(@json($message));
                                            </script>
                                        @enderror
                                    </div>
                                    <input type="submit" class="site-btn" name="add_to_cart" value="Thêm vào giỏ hàng">
                                    {{-- <a href="{{route('sites.add', $productDetail->id, 1, 'true')}}" class="primary-btn">Thêm vào giỏ hàng</a> --}}
                                </div>
                                <div class="product__details__btns__option">
                                    <a href="{{ route('sites.addToWishList', $productDetail->id) }}"><i
                                            class="fa fa-heart"></i>Thêm vào yêu thích</a>
                                    <a href="#"><i class="fa fa-exchange"></i>So sánh giá</a>
                                </div>
                                <div class="product__details__last__option">
                                    <h5><span>Các phương thức thanh toán:</span></h5>
                                    <img src="{{ 'client/img/shop-details/details-payment.png' }}" alt="">
                                    <ul>
                                        <li><span>SKU: </span>{{ $productDetail->sku }}</li>
                                        <li><span>Danh mục: </span>{{ $productDetail->category->category_name }}</li>
                                        <li><span>Tag: </span>{{ str_replace(',', ', ', $productDetail->tags) }}</li>
                                    </ul>
                                </div>
                            </div>
                    </div>
                    </form>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="product__details__tab">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#tabs-5" role="tab">Mô tả sản
                                        phẩm</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#tabs-6" role="tab">Đánh giá của
                                        khách hàng(5)</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tabs-5" role="tabpanel">
                                    <div class="product__details__tab__content">
                                        <p class="note">Mô tả ngắn: {{ $productDetail->short_description }}</p>
                                        <div class="product__details__tab__content__item">
                                            <h5>Mô tả sản phẩm</h5>
                                            <p>{{ $productDetail->description }}</p>
                                        </div>
                                        <div class="product__details__tab__content__item">
                                            <h5>Chất liệu được sử dụng</h5>
                                            <p>{{ $productDetail->material }}</p>
                                        </div>
                                    </div>
                                </div>
                                {{-- phần đánh giá nữa chỉnh giao diện sau --}}
                                <div class="tab-pane" id="tabs-6" role="tabpanel">
                                    <div class="product__details__tab__content">
                                        <!-- Đánh giá sao -->
                                        <div class="review-section">
                                            <h5>Đánh giá sản phẩm</h5>
                                            <div class="rating">
                                                <span class="star" data-value="1">&#9733;</span>
                                                <span class="star" data-value="2">&#9733;</span>
                                                <span class="star" data-value="3">&#9733;</span>
                                                <span class="star" data-value="4">&#9733;</span>
                                                <span class="star" data-value="5">&#9733;</span>
                                                <input type="hidden" id="rating-value" value="0">
                                            </div>
                                            <textarea id="review-comment" placeholder="Nhập bình luận của bạn..." rows="3"></textarea>
                                            <button id="submit-review">Gửi đánh giá</button>
                                        </div>

                                        <!-- Danh sách bình luận -->
                                        <div class="comment-section">
                                            <h5>Bình luận</h5>
                                            <ul id="review-list">
                                                <li>
                                                    <strong>Nguyễn Văn A</strong>
                                                    <div class="stars">⭐⭐⭐⭐⭐</div>
                                                    <p>Nội dung: Sản phẩm rất tốt, chất lượng ổn!</p>
                                                    <p class="comment-date">Ngày đăng: 2023-08-01</p>
                                                    <button class="edit-comment">Sửa</button>
                                                    <button class="delete-comment">Xóa</button>
                                                </li>
                                                <li>
                                                    <strong>Nguyễn Văn A</strong>
                                                    <div class="stars">⭐⭐⭐⭐⭐</div>
                                                    <p>Sản phẩm rất tốt, chất lượng ổn!</p>
                                                </li>
                                                <li>
                                                    <strong>Nguyễn Văn A</strong>
                                                    <div class="stars">⭐⭐⭐⭐⭐</div>
                                                    <p>Sản phẩm rất tốt, chất lượng ổn!</p>
                                                </li>
                                                <li>
                                                    <strong>Nguyễn Văn A</strong>
                                                    <div class="stars">⭐⭐⭐⭐⭐</div>
                                                    <p>Sản phẩm rất tốt, chất lượng ổn!</p>
                                                </li>
                                                <li>
                                                    <strong>Nguyễn Văn A</strong>
                                                    <div class="stars">⭐⭐⭐⭐⭐</div>
                                                    <p>Sản phẩm rất tốt, chất lượng ổn!</p>
                                                </li>
                                                <li>
                                                    <strong>Trần Thị B</strong>
                                                    <div class="stars">⭐⭐⭐⭐</div>
                                                    <p>Giao hàng nhanh, sản phẩm đẹp nhưng hộp hơi móp.</p>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Shop Details Section End -->

        <!-- Icon giỏ hàng -->
        <div class="cart-icon" id="cartIcon" onclick="toggleCart()">
            <i class="fas fa-id-card-alt"></i><span class="cart-badge" id="cartCount">{{ $totalProduct }}</span>
        </div>
    
        <!-- Danh sách sản phẩm trong giỏ -->
        <div class="cart-items" id="cartItems" style="display: none;">
            <strong>Các sản phẩm đã thêm:</strong>
            <div id="cartList">
                @if (Session::has('cart') && count(Session::get('cart')) > 0)
                    @foreach (Session::get('cart') as $items)
                        <div class="cart-item p-1">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <img src="uploads/{{ $items->image }}" alt="{{ $items->name }}" class="cart-item-img"
                                        width="50">
                                    <div class="d-inline-block flex-col">
                                        <span>{{ Str::words($items->name, 5) }}</span></br>
                                        <span
                                            class="font-weight-bold">{{ number_format($items->price, 0, ',', '.') . ' đ' }}</span>
                                    </div>
                                </div>
                                <span
                                    class="cart-item-quantity-{{ $items->id }} quantity-badge">{{ $items->quantity }}</span>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
            <div class="cart-footer">
                <button class="btn btn-success w-100" onclick="goToCartPage()">Đến trang Giỏ hàng</button>
            </div>
        </div>

    <!-- Related Section Begin -->
    <section class="related spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h3 class="related-title">Sản phẩm liên quan mà có thể bạn sẽ thích</h3>
                </div>
            </div>

            <div class="row" id="suggestion-list-product">
                {{-- ******** danh sách này nằm trong _chatbot_search.blade.php do bỏ đây mất chatbot và se ********** --}}
            </div>
        </div>
    </section>
    <!-- Related Section End -->
@endsection


@section('css')
    <link rel="stylesheet" href="{{ asset('client/css/comment.css') }}">
    <link rel="stylesheet" href="{{ asset('client/css/cart-add.css') }}">
@endsection
