<!-- Page Preloder -->
<div id="preloder">
    <div class="loader"></div>
</div>
@php
    if (Session::has('cart')) {
        $cartQuantity = count(Session::get('cart'));
    }
@endphp


<!-- Offcanvas Menu Begin Menu ẩn nhé-->
<div class="offcanvas-menu-overlay"></div>
<div class="offcanvas-menu-wrapper">
    <div class="offcanvas__option">
        <div class="offcanvas__links">
            <a href="{{ route('user.login') }}">Đăng nhập</a>
            <a href="#">Hỏi Đáp</a>
        </div>
        <div class="offcanvas__top__hover">
            <span>Langue<i class="arrow_carrot-down"></i></span>
            <ul>
                <li>VN</li>
                <li>EN</li>
            </ul>
        </div>
    </div>
    <div class="offcanvas__nav__option">
        <a href="#" class="search-switch"><img src="{{ asset('client/img/icon/search.png') }}" alt=""></a>
        <a href="{{ route('sites.wishlist') }}"><img src="{{ asset('client/img/icon/heart.png') }}" alt=""></a>
        <a href="{{ route('sites.cart') }}"><img src="{{ asset('client/img/icon/cart.png') }}" alt="">
            <span>{{ $cartQuantity ?? 0 }}</span></a>
        {{-- <div class="price">$0.00</div> --}}
    </div>
    <div id="mobile-menu-wrap"></div>
    <div class="offcanvas__text">
        <p>Miễn phí vận chuyển, hỗ trợ đổi trả trong vòng 30 ngày</p>
    </div>
</div>
<!-- Offcanvas Menu End -->

<!-- Header Section Begin -->
<header class="header">
    <div class="header__top">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-7">
                    <div class="header__top__left">
                        <p>Miễn phí vận chuyển, hỗ trợ đổi trả trong vòng 30 ngày</p>
                    </div>
                </div>
                <div class="col-lg-6 col-md-5">
                    <div class="header__top__right">
                        <div class="header__top__hover">
                            @php
                                $avatarUrl = asset('client/img/user.jpg'); // Ảnh mặc định

                                if (Auth::guard('customer')->check()) {
                                    $user = Auth::guard('customer')->user();

                                    if ($user && !empty($user->image)) {
                                        if (filter_var($user->image, FILTER_VALIDATE_URL)) {
                                            // Nếu ảnh là URL (Google/Facebook)
                                            $avatarUrl = $user->image;
                                        } else {
                                            // Nếu ảnh được lưu trong thư mục client/img
                                            $avatarUrl = asset('client/img/' . $user->image);
                                        }
                                    }
                                }
                            @endphp


                            @if (Auth::guard('customer')->check())
                                <img src="{{ $avatarUrl }}" alt="User Avatar" width="30" alt=""
                                    class="rounded-circle">
                                {{-- <img src="{{ asset('client/img/' . Auth::guard('customer')->user()->image) }}"
                                    width="30" alt="" class="rounded-circle"> --}}
                                <span>Xin chào, {{ Auth::guard('customer')->user()->name }}<i
                                        class="arrow_carrot-down"></i></span>
                                <ul>
                                    <li><a class="text-dark" href="{{ route('user.profile') }}">Hồ sơ cá nhân</a></li>
                                    <li><a class="text-dark" href="{{ route('sites.getHistoryOrder') }}">Lịch sử giao
                                            dịch</a>
                                    </li>
                                    <li><a class="text-dark" href="{{ route('user.logout') }}">Đăng Xuất</a></li>
                                </ul>
                            @else
                                <span class="text-white"><a href="{{ route('user.login') }}">Đăng nhập</a></span>
                            @endif
                        </div>
                        <div class="ms-3 header__top__hover">
                            <span>Ngôn ngữ<i class="arrow_carrot-down"></i></span>
                            <ul>
                                <li>VI</li>
                                <li>EN</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-3">
                <div class="header__logo">
                    <a href="{{ route('sites.home') }}" class="text-dark font-weight-bold text-uppercase">
                        <img class="rounded-circle" src="{{ asset('assets/img/TSTShop/TST_Shop.webp') }}"
                            alt="Logo" width="35">
                        TST Fashion Shop
                    </a>
                </div>
            </div>

            <div class="col-lg-6 col-md-6">
                <nav class="header__menu mobile-menu">
                    <ul>
                        <li class="{{ request()->routeIs('sites.home') ? 'active' : '' }}">
                            <a href="{{ route('sites.home') }}">Home</a>
                        </li>
                        <li class="{{ request()->routeIs('sites.shop') ? 'active' : '' }}">
                            <a href="{{ route('sites.shop') }}">Shop</a>
                        </li>
                        <li
                            class="{{ request()->routeIs('sites.aboutUs', 'sites.shopDetail', 'sites.shoppingCart', 'sites.checkout', 'sites.blogDetail') ? 'active' : '' }}">
                            <a href="{{ route('sites.aboutUs') }}">Pages</a>
                            <ul class="dropdown">
                                <li class="{{ request()->routeIs('sites.aboutUs') ? 'active' : '' }}">
                                    <a href="{{ route('sites.aboutUs') }}">About Us</a>
                                </li>
                                <li class="{{ request()->routeIs('sites.shopDetail') ? 'active' : '' }}">
                                    <a href="{{ route('sites.shopDetail') }}">Shop Details</a>
                                </li>
                                <li class="{{ request()->routeIs('sites.shoppingCart') ? 'active' : '' }}">
                                    <a href="{{ route('sites.shoppingCart') }}">Shopping Cart</a>
                                </li>
                                <li class="{{ request()->routeIs('sites.checkout') ? 'active' : '' }}">
                                    <a href="{{ route('sites.checkout') }}">Check Out</a>
                                </li>
                                <li class="{{ request()->routeIs('sites.blogDetail') ? 'active' : '' }}">
                                    <a href="{{ route('sites.blogDetail') }}">Blog Details</a>
                                </li>
                            </ul>
                        </li>
                        <li class="{{ request()->routeIs('sites.blog') ? 'active' : '' }}">
                            <a href="{{ route('sites.blog') }}">Blog</a>
                        </li>
                        <li class="{{ request()->routeIs('sites.contact') ? 'active' : '' }}">
                            <a href="{{ route('sites.contact') }}">Contacts</a>
                        </li>
                    </ul>
                </nav>
            </div>
            <div class="col-lg-3 col-md-3">
                <div class="header__nav__option">
                    <a class="search-btn" style="cursor: pointer;">
                        <i class="fa fa-fw fa-search text-dark"></i>
                    </a>
                    <a href="{{ route('sites.wishlist') }}"><img src="{{ asset('client/img/icon/heart.png') }}" alt=""></a>
                    <a href="{{ route('sites.cart') }}"><img src="{{ asset('client/img/icon/cart.png') }}"
                            alt="">
                        <span class="cart-quantity-header">{{ $cartQuantity ?? 0 }}</span></a>
                    {{-- <div class="price">$0.00</div> --}}
                </div>
            </div>
        </div>
        <div class="canvas__open"><i class="fa fa-bars"></i></div>
    </div>
</header>
<!-- Header Section End -->

@section('css')
    <link rel="stylesheet" href="{{ asset('client/css/header.css') }}">

    {{-- <link rel="stylesheet" href="{{ asset('assets/css/message.css') }}" /> --}}
@endsection

@section('js')
    {{-- <script>
        setTimeout(() => {
            searchBtn = document.querySelector(".search-btn");
            console.log(searchBtn);
        }, 2000);
    </script> --}}

    {{-- @if (Session::has('success'))
        <script src="{{ asset('assets/js/message.js') }}"></script>
    @endif --}}
@endsection
