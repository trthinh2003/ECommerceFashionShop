{{-- @php
    dd(Session::get('cart'));
@endphp --}}

@extends('sites.master')
@section('title', 'Trang chủ')
@section('content')
    @php
        $totalProduct = 0;
        if(Session::has('cart')){
            foreach (Session::get('cart') as $item) {
                $totalProduct += $item->quantity;
            }
        }
        else $totalProduct = 0;
    @endphp
    <!-- Hero Section Begin -->
    <section class="hero">
        <div class="hero__slider owl-carousel">
            <div class="hero__items set-bg" data-setbg="{{ asset('client/img/hero/hero-1.jpg') }}">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-5 col-lg-7 col-md-8">
                            <div class="hero__text">
                                <h6>Summer Collection</h6>
                                <h2>Fall - Winter Collections 2030</h2>
                                <p>A specialist label creating luxury essentials. Ethically crafted with an unwavering
                                    commitment to exceptional quality.</p>
                                <a href="#" class="primary-btn">Shop now <span class="arrow_right"></span></a>
                                <div class="hero__social">
                                    <a href="#"><i class="fa fa-facebook"></i></a>
                                    <a href="#"><i class="fa fa-twitter"></i></a>
                                    <a href="#"><i class="fa fa-pinterest"></i></a>
                                    <a href="#"><i class="fa fa-instagram"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="hero__items set-bg" data-setbg="{{ asset('client/img/hero/hero-2.jpg') }}">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-5 col-lg-7 col-md-8">
                            <div class="hero__text">
                                <h6>Summer Collection</h6>
                                <h2>Fall - Winter Collections 2030</h2>
                                <p>A specialist label creating luxury essentials. Ethically crafted with an unwavering
                                    commitment to exceptional quality.</p>
                                <a href="#" class="primary-btn">Shop now <span class="arrow_right"></span></a>
                                <div class="hero__social">
                                    <a href="#"><i class="fa fa-facebook"></i></a>
                                    <a href="#"><i class="fa fa-twitter"></i></a>
                                    <a href="#"><i class="fa fa-pinterest"></i></a>
                                    <a href="#"><i class="fa fa-instagram"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Hero Section End -->

    <!-- Banner Section Begin -->
    <section class="banner spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-7 offset-lg-4">
                    <div class="banner__item">
                        <div class="banner__item__pic">
                            <img src="{{ asset('client/img/banner/banner-1.jpg') }}" alt="">
                        </div>
                        <div class="banner__item__text">
                            <h2>Clothing Collections 2030</h2>
                            <a href="#">Shop now</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="banner__item banner__item--middle">
                        <div class="banner__item__pic">
                            <img src="{{ asset('client/img/banner/banner-2.jpg') }}" alt="">
                        </div>
                        <div class="banner__item__text">
                            <h2>Accessories</h2>
                            <a href="#">Shop now</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="banner__item banner__item--last">
                        <div class="banner__item__pic">
                            <img src="{{ asset('client/img/banner/banner-3.jpg') }}" alt="">
                        </div>
                        <div class="banner__item__text">
                            <h2>Shoes Spring 2030</h2>
                            <a href="#">Shop now</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Banner Section End -->

    <!-- Product Section Begin -->
    <section class="product spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <ul class="filter__controls">
                        <li class="active" data-filter="*">Best Sellers</li>
                        <li data-filter=".new-arrivals">New Arrivals</li>
                        <li data-filter=".hot-sales">Hot Sales</li>
                    </ul>
                </div>
            </div>
            <div class="row product__filter">
                <script>
                    async function fetchProduct() {
                        try {
                            let response = await fetch('http://127.0.0.1:8000/api/product-client');
                            let data = await response.json();
                            let products = data.data;

                            let container = document.querySelector('.product__filter');
                            container.innerHTML = "";

                            products.forEach((product, index) => {
                                let formattedPrice = new Intl.NumberFormat('vi-VN', {
                                    style: 'currency',
                                    currency: 'VND'
                                }).format(product.price ?? 0);

                                let productItem = document.createElement('div');
                                productItem.classList.add("col-lg-3", "col-md-6", "col-sm-6", "mix", index % 2 === 0 ?
                                    "new-arrivals" : "hot-sales");
                                productItem.innerHTML = `
                                        <div class="product__item" id="product-list-home">
                                            <div class="product__item__pic set-bg" data-setbg="{{ asset('uploads/${product.image}') }}">
                                                <span class="label">New</span>
                                                <ul class="product__hover">
                                                    <li><a href="#"><img src="{{ asset('client/img/icon/heart.png') }}" alt=""></a></li>
                                                    <li><a href="#"><img src="{{ asset('client/img/icon/compare.png') }}" alt=""><span>Compare</span></a></li>
                                                    <li><a href="{{ url('product') }}/${product.slug}"><img src="{{ asset('client/img/icon/search.png') }}" alt=""></a></li>
                                                </ul>
                                            </div>
                                            <div class="product__item__text">
                                                <h6>${product.product_name}</h6>
                                                 <a href="javascript:void(0);" class="add-cart" data-id="${product.id}">+ Add To Cart</a>
                                                <div class="rating">
                                                    <i class="fa fa-star-o"></i>
                                                    <i class="fa fa-star-o"></i>
                                                    <i class="fa fa-star-o"></i>
                                                    <i class="fa fa-star-o"></i>
                                                    <i class="fa fa-star-o"></i>
                                                </div>
                                                <h5>${formattedPrice}</h5>
                                                <div class="product__color__select">
                                                                            <label for="pc-${index * 3 + 1}">
                                                                                <input type="radio" id="pc-${index * 3 + 1}">
                                                                            </label>
                                                                            <label class="active black" for="pc-${index * 3 + 2}">
                                                                                <input type="radio" id="pc-${index * 3 + 2}">
                                                                            </label>
                                                                            <label class="grey" for="pc-${index * 3 + 3}">
                                                                                <input type="radio" id="pc-${index * 3 + 3}">
                                                                            </label>
                                                                        </div>
                                            </div>
                                        </div>
                                    `;
                                container.appendChild(productItem);
                            });
                        } catch (error) {
                            console.error("Lỗi API:", error);
                        }
                    }
                    // tìm tất cả các phần tử có class set-bg và cập nhật hình nền của chúng dựa vào giá trị data-setbg.
                    // function updateBackgroundImages() {
                    //     document.querySelectorAll('.set-bg').forEach(el => {
                    //         let bg = el.getAttribute('data-setbg');
                    //         el.style.backgroundImage = `url(${bg})`;
                    //     });
                    // }
                    // // gọi hàm sao khi lấy dữ liệu từ fetchProduct
                    // fetchProduct().then(() => {
                    //     updateBackgroundImages();
                    // });
                    fetchProduct();
                </script>
            </div>
        </div>
    </section>
    <!-- Product Section End -->

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
                                <img src="uploads/{{$items->image}}" alt="{{$items->name}}" class="cart-item-img" width="50">
                                <div class="d-inline-block flex-col">
                                    <span>{{$items->name}}</span> </br>
                                    <span class="font-weight-bold">{{number_format($items->price, 0, ',', '.') . ' đ'}}</span>
                                </div>
                            </div>
                            <span class="cart-item-quantity-{{$items->id}} quantity-badge">{{$items->quantity}}</span>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
        <div class="cart-footer">
            <button class="btn btn-success w-100" onclick="goToCartPage()">Đến trang Giỏ hàng</button>
        </div>
    </div>



    <!-- Categories Section Begin -->
    <section class="categories spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="categories__text">
                        <h2>Clothings Hot <br /> <span>Shoe Collection</span> <br /> Accessories</h2>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="categories__hot__deal">
                        <img src="{{ asset('client/img/product-sale.png') }}" alt="">
                        <div class="hot__deal__sticker">
                            <span>Sale Of</span>
                            <h5>$29.99</h5>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 offset-lg-1">
                    <div class="categories__deal__countdown">
                        <span>Deal Of The Week</span>
                        <h2>Multi-pocket Chest Bag Black</h2>
                        <div class="categories__deal__countdown__timer" id="countdown">
                            <div class="cd-item">
                                <span>3</span>
                                <p>Days</p>
                            </div>
                            <div class="cd-item">
                                <span>1</span>
                                <p>Hours</p>
                            </div>
                            <div class="cd-item">
                                <span>50</span>
                                <p>Minutes</p>
                            </div>
                            <div class="cd-item">
                                <span>18</span>
                                <p>Seconds</p>
                            </div>
                        </div>
                        <a href="#" class="primary-btn">Shop now</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Categories Section End -->

    <!-- Instagram Section Begin -->
    <section class="instagram spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="instagram__pic">
                        <div class="instagram__pic__item set-bg"
                            data-setbg="{{ asset('client/img/instagram/instagram-1.jpg') }}"></div>
                        <div class="instagram__pic__item set-bg"
                            data-setbg="{{ asset('client/img/instagram/instagram-2.jpg') }}"></div>
                        <div class="instagram__pic__item set-bg"
                            data-setbg="{{ asset('client/img/instagram/instagram-3.jpg') }}"></div>
                        <div class="instagram__pic__item set-bg"
                            data-setbg="{{ asset('client/img/instagram/instagram-4.jpg') }}"></div>
                        <div class="instagram__pic__item set-bg"
                            data-setbg="{{ asset('client/img/instagram/instagram-5.jpg') }}"></div>
                        <div class="instagram__pic__item set-bg"
                            data-setbg="{{ asset('client/img/instagram/instagram-6.jpg') }}"></div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="instagram__text">
                        <h2>Instagram</h2>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
                            labore et dolore magna aliqua.</p>
                        <h3>#Male_Fashion</h3>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Instagram Section End -->

    <!-- Latest Blog Section Begin -->
    <section class="latest spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <span>Latest News</span>
                        <h2>Fashion New Trends</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-6">
                    <div class="blog__item">
                        <div class="blog__item__pic set-bg" data-setbg="{{ asset('client/img/blog/blog-1.jpg') }}"></div>
                        <div class="blog__item__text">
                            <span><img src="{{ asset('client/img/icon/calendar.png') }}" alt=""> 16 February
                                2020</span>
                            <h5>What Curling Irons Are The Best Ones</h5>
                            <a href="#">Read More</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6">
                    <div class="blog__item">
                        <div class="blog__item__pic set-bg" data-setbg="{{ asset('client/img/blog/blog-2.jpg') }}"></div>
                        <div class="blog__item__text">
                            <span><img src="{{ asset('client/img/icon/calendar.png') }}" alt=""> 21 February
                                2020</span>
                            <h5>Eternity Bands Do Last Forever</h5>
                            <a href="#">Read More</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6">
                    <div class="blog__item">
                        <div class="blog__item__pic set-bg" data-setbg="{{ asset('client/img/blog/blog-3.jpg') }}"></div>
                        <div class="blog__item__text">
                            <span><img src="{{ asset('client/img/icon/calendar.png') }}" alt=""> 28 February
                                2020</span>
                            <h5>The Health Benefits Of Sunglasses</h5>
                            <a href="#">Read More</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Latest Blog Section End -->
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('client/css/cart-add.css') }}">
@endsection

@section('js')
    <script src="{{ asset('client/js/cart-add.js') }}"></script>
@endsection
