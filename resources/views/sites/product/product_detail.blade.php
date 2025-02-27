{{-- @php
    // dd($productDetail);
    dd($colors);
@endphp --}}

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
                            <h3>{{ number_format($productDetail->price) }}đ<span>{{ number_format($productDetail->price) }}</span></h3>
                            <p>{{ $productDetail->short_description }}</p>
                            <div class="product__details__option">
                                <div class="product__details__option__size">
                                    <span>Kích cỡ:</span>
                                    @foreach ($sizes as $size)
                                        <label for="size-{{ $size }}">{{ $size }}
                                            <input type="radio" name="size" id="size-{{ $size }}"
                                                value="{{ $size }}">
                                        </label>
                                    @endforeach
                                </div>


                                @php
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


                                <div class="product__details__option__color">
                                    <span>Màu Sắc:</span>
                                    @foreach ($colors as $index => $color)
                                        <label class="color-box" style="background-color: {{ getColorHex($color) }};"
                                            for="color-{{ $index }}" title="{{ $color }}">
                                            <input type="radio" name="color" id="color-{{ $index }}" value="{{ $color }}">
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                            <div class="product__details__cart__option">
                                <div class="quantity">
                                    <div class="pro-qty">
                                        <input type="text" value="1">
                                    </div>
                                </div>
                                <a href="#" class="primary-btn">Thêm vào giỏ hàng</a>
                            </div>
                            <div class="product__details__btns__option">
                                <a href="#"><i class="fa fa-heart"></i>Thêm vào yêu thích</a>
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
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="product__details__tab">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#tabs-5" role="tab">Mô tả sản phẩm</a>
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

    <!-- Related Section Begin -->
    <section class="related spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h3 class="related-title">Sản phẩm liên quan mà có thể bạn sẽ thích</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6 col-sm-6">
                    <div class="product__item">
                        <div class="product__item__pic set-bg" data-setbg="{{ 'client/img/product/product-1.jpg' }}">
                            <span class="label">New</span>
                            <ul class="product__hover">
                                <li><a href="#"><img src="{{ 'client/img/icon/heart.png' }}" alt=""></a>
                                </li>
                                <li><a href="#"><img src="{{ 'client/img/icon/compare.png' }}" alt="">
                                        <span>Compare</span></a></li>
                                <li><a href="#"><img src="{{ 'client/img/icon/search.png' }}"
                                            alt=""></a></li>
                            </ul>
                        </div>
                        <div class="product__item__text">
                            <h6>Piqué Biker Jacket</h6>
                            <a href="#" class="add-cart">+ Add To Cart</a>
                            <div class="rating">
                                <i class="fa fa-star-o"></i>
                                <i class="fa fa-star-o"></i>
                                <i class="fa fa-star-o"></i>
                                <i class="fa fa-star-o"></i>
                                <i class="fa fa-star-o"></i>
                            </div>
                            <h5>$67.24</h5>
                            <div class="product__color__select">
                                <label for="pc-1">
                                    <input type="radio" id="pc-1">
                                </label>
                                <label class="active black" for="pc-2">
                                    <input type="radio" id="pc-2">
                                </label>
                                <label class="grey" for="pc-3">
                                    <input type="radio" id="pc-3">
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-sm-6">
                    <div class="product__item">
                        <div class="product__item__pic set-bg" data-setbg="{{ 'client/img/product/product-2.jpg' }}">
                            <ul class="product__hover">
                                <li><a href="#"><img src="{{ 'client/img/icon/heart.png' }}" alt=""></a>
                                </li>
                                <li><a href="#"><img src="{{ 'client/img/icon/compare.png' }}" alt="">
                                        <span>Compare</span></a></li>
                                <li><a href="#"><img src="{{ 'client/img/icon/search.png' }}"
                                            alt=""></a></li>
                            </ul>
                        </div>
                        <div class="product__item__text">
                            <h6>Piqué Biker Jacket</h6>
                            <a href="#" class="add-cart">+ Add To Cart</a>
                            <div class="rating">
                                <i class="fa fa-star-o"></i>
                                <i class="fa fa-star-o"></i>
                                <i class="fa fa-star-o"></i>
                                <i class="fa fa-star-o"></i>
                                <i class="fa fa-star-o"></i>
                            </div>
                            <h5>$67.24</h5>
                            <div class="product__color__select">
                                <label for="pc-4">
                                    <input type="radio" id="pc-4">
                                </label>
                                <label class="active black" for="pc-5">
                                    <input type="radio" id="pc-5">
                                </label>
                                <label class="grey" for="pc-6">
                                    <input type="radio" id="pc-6">
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-sm-6">
                    <div class="product__item sale">
                        <div class="product__item__pic set-bg" data-setbg="{{ 'client/img/product/product-3.jpg' }}">
                            <span class="label">Sale</span>
                            <ul class="product__hover">
                                <li><a href="#"><img src="{{ 'client/img/icon/heart.png' }}" alt=""></a>
                                </li>
                                <li><a href="#"><img src="{{ 'client/img/icon/compare.png' }}" alt="">
                                        <span>Compare</span></a></li>
                                <li><a href="#"><img src="{{ 'client/img/icon/search.png' }}"
                                            alt=""></a></li>
                            </ul>
                        </div>
                        <div class="product__item__text">
                            <h6>Multi-pocket Chest Bag</h6>
                            <a href="#" class="add-cart">+ Add To Cart</a>
                            <div class="rating">
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star-o"></i>
                            </div>
                            <h5>$43.48</h5>
                            <div class="product__color__select">
                                <label for="pc-7">
                                    <input type="radio" id="pc-7">
                                </label>
                                <label class="active black" for="pc-8">
                                    <input type="radio" id="pc-8">
                                </label>
                                <label class="grey" for="pc-9">
                                    <input type="radio" id="pc-9">
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-sm-6">
                    <div class="product__item">
                        <div class="product__item__pic set-bg" data-setbg="{{ 'client/img/product/product-4.jpg' }}">
                            <ul class="product__hover">
                                <li><a href="#"><img src="{{ 'client/img/icon/heart.png' }}" alt=""></a>
                                </li>
                                <li><a href="#"><img src="{{ 'client/img/icon/compare.png' }}" alt="">
                                        <span>Compare</span></a></li>
                                <li><a href="#"><img src="{{ 'client/img/icon/search.png' }}"
                                            alt=""></a></li>
                            </ul>
                        </div>
                        <div class="product__item__text">
                            <h6>Diagonal Textured Cap</h6>
                            <a href="#" class="add-cart">+ Add To Cart</a>
                            <div class="rating">
                                <i class="fa fa-star-o"></i>
                                <i class="fa fa-star-o"></i>
                                <i class="fa fa-star-o"></i>
                                <i class="fa fa-star-o"></i>
                                <i class="fa fa-star-o"></i>
                            </div>
                            <h5>$60.9</h5>
                            <div class="product__color__select">
                                <label for="pc-10">
                                    <input type="radio" id="pc-10">
                                </label>
                                <label class="active black" for="pc-11">
                                    <input type="radio" id="pc-11">
                                </label>
                                <label class="grey" for="pc-12">
                                    <input type="radio" id="pc-12">
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Related Section End -->
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('client/css/comment.css') }}">
@endsection
