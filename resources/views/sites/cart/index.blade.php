{{-- @php
    dd(Session::get('cart'));
@endphp --}}

@extends('sites.master')
@section('content')
    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__text">
                        <h4>Giỏ Hàng</h4>
                        <div class="breadcrumb__links">
                            <a href="{{ route('sites.home') }}">Home</a>
                            <a href="{{ route('sites.shop') }}">Shop</a>
                            <span>Shopping Cart</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Shopping Cart Section Begin -->
    <section class="shopping-cart spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="shopping__cart__table">
                        <table>
                            <thead>
                                <tr>
                                    <th>Sản Phẩm</th>
                                    <th>Hình Ảnh</th>
                                    <th>Số Lượng</th>
                                    <th>Tổng tiền</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>

                                @if (Session::has('cart') && count(Session::get('cart')) > 0)
                                @foreach (Session::get('cart') as $items)
                                <tr class="cart-item" data-id="{{ $items->id }}">
                                    <td class="product__cart__item">
                                        <div class="product__cart__item__text">
                                            <h6>{{ $items->name }}</h6>
                                            <h5 class="product-price">
                                                {{ number_format($items->price, 0, ',', '.') . ' đ' }}
                                            </h5>
                                            <h6 class="mt-1">Màu sắc: {{ $items->color }}</h6>
                                            <h6>Size: {{ $items->size }}</h6>
                                        </div>
                                    </td>
                                    <td class="product__cart__item">
                                        <div class="product__cart__item__pic">
                                            <img src="{{ asset('uploads/' . $items->image) }}" width="80" alt="">
                                        </div>
                                    </td>
                                    <td class="quantity__item">
                                        <div class="quantity">
                                            <div class="input-group mt-3">
                                                <button class="btn btn-outline-secondary button-decrease" type="button">-</button>
                                                <input type="text" class="text-center product-quantity"
                                                    value="{{ $items->quantity }}" min="1" max="10" style="width: 30%">
                                                <button class="btn btn-outline-secondary button-increase" type="button">+</button>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="cart__price">{{ number_format($items->price * $items->quantity, 0, ',', '.') . ' đ' }}</td>
                                    <td class="cart__close">
                                        <a href="{{ route('sites.remove', $items->id) }}"
                                            onclick="return confirm('Bạn có chắc muốn xoá sản phẩm này khỏi giỏ hàng ?')">
                                            <i class="fa fa-close"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            
                                @else
                                    <tr>
                                        <td colspan="5" class="text-center text-muted" style="font-size: 1.35rem;">Giỏ
                                            hàng đang trống!</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="continue__btn">
                                {{-- đi đến chỗ danh sách sản phẩm bằng id=product-list-home --}}
                                <a href="{{ route('sites.home') }}#product-list-home">Tiếp tục mua hàng</a>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="continue__btn update__btn">
                                <a href="{{ route('sites.clear') }}">Xoá giỏ hàng</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="cart__discount">
                        <h6>Discount codes</h6>
                        <form action="#">
                            <input type="text" name="code_discount" placeholder="Coupon code">
                            <button id="apply-code-discount" type="submit">Apply</button>
                        </form>
                    </div>
                    <div class="cart__total">
                        <h6>Cart total</h6>
                        <ul>
                            <li>Tạm tính<span>$ 169.50</span></li>
                            <li>Phí Ship<span>$ 3.50</span></li>
                            <li>Thuế GTGT<span>$ 16.50</span></li>
                            <li>Thành tiền <span>$ 190</span></li>
                        </ul>
                        <a href="{{ route('sites.checkout') }}" class="primary-btn">Proceed to checkout</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Shopping Cart Section End -->
@endsection


@section('js')
    <script>
        $(document).ready(function() {
            $(".button-increase, .button-decrease").click(function() {
                let row = $(this).closest("tr");
                let input = row.find(".product-quantity");
                let productId = row.data("id");
                let productPrice = parseInt(row.find(".product-price").text().replace(/\D/g, ""));
                let currentQuantity = parseInt(input.val());
                let minValue = parseInt(input.attr("min")) || 1;
                let maxValue = parseInt(input.attr("max")) || 10;

                if ($(this).hasClass("button-increase") && currentQuantity < maxValue) {
                    currentQuantity++;
                } else if ($(this).hasClass("button-decrease") && currentQuantity > minValue) {
                    currentQuantity--;
                }

                input.val(currentQuantity);

                // Cập nhật tổng giá tiền
                let totalPrice = productPrice * currentQuantity;
                row.find(".cart__price").text(totalPrice.toLocaleString() + " đ");

                // Gọi AJAX để lưu session
                updateCartSession(productId, currentQuantity);
            });
        });

        function updateCartSession(productId, quantity) {
            $.ajax({
                url: "/cart/update-cart-session",
                method: "POST",
                data: {
                    product_id: productId,
                    quantity: quantity,
                    _token: $('meta[name="csrf-token"]').attr("content")
                },
                success: function(response) {
                    console.log("Session updated:", response);
                },
                error: function(xhr) {
                    console.error("Lỗi khi cập nhật session:", xhr.responseText);
                }
            });
        }
    </script>
    <script>
        $(document).ready(function() {
            $('#apply-code-discount').click(function(e) {
                e.preventDefault();
                var code = $('input[name="code_discount"]').val();
                $.ajax({
                    url: `http://127.0.0.1:8000/api/discount-code/${code}`,
                    type: "GET",
                    dataType: "json",
                    success: function(response) {
                        if (response.status_code === 200) {
                            let discount = response.data;
                            $('input[name="code_discount"]').attr('disabled', true);
                        } else {
                            alert('Mã code không tồn tại!');
                        }
                    },
                    error: function(error) {
                        alert('Lỗi API:');
                    }
                });
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('.input-quantity').change(function(e) {
                console.log('change');
            })
        });
    </script>
@endsection
