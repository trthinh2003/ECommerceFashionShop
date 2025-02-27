@extends('sites.master')

@section('title', 'Danh sách mong muốn')

@section('content')

<div class="fr-bordered">
    {{-- 0 Sản phẩm
Không có sản phẩm nào trong danh sách mong muốn của bạn.
Nhấn vào dấu trái tim để thêm sản phẩm vào danh sách mong muốn của bạn. --}}
    <div>
        <div class="fr-wrapper mb-l" style="width: auto;">
            <div class="fr-text fr-system-text">Sản phẩm</div>
        </div>
        <div class="fr-list">
            @if (Session::has('wishlist') && count(Session::get('wishlist')) > 0)
                @foreach (Session::get('wishlist') as $item)
                <div>
                    <a href="{{url('product').'/'.$item->slug}}" style="">
                        <div class="fr-product-card list list-for-wishlist">
                            <div class="image-wrap w3-f">
                                <div class="fr-product-image fr-product-image-ecr-phase3">
                                    <div class="favorite large swiper-no-swiping">
                                        <button aria-label="Favorite">
                                            <span class="fr-icon active favorite_large" style="font-size: 24px;" role="img" aria-label="Favorite">
                                                <span class="fr-implicit">Thêm vào danh sách mong muốn</span>
                                            </span>
                                        </button>
                                    </div>
                                    <div class="ec-renewal-image-wrapper ecr-phase3-image-wrapper" 
                                         style="background-image: {{ asset('uploads/' . $items->image) }}; background-position: center center;">
                                        <img src="{{ asset('uploads/' . $items->image) }}" alt="Image not found" class="thumb-img" loading="lazy">
                                    </div>
                                </div>
                            </div>
                            <div class="info">
                                <div class="mb-s">
                                    <h5 class="fr-head h5 h5">
                                        <span class="title fr-no-uppercase">{{ $item->product_name }}</span>
                                    </h5>
                                </div>
                                <dl class="fr-definition-list inline minor">
                                    <dt class="fr-definition-list-term">Mã sản phẩm</dt>
                                    <dd class="fr-definition-list-description">{{ $item->id }}</dd>
                                </dl>
                                <dl class="fr-definition-list inline">
                                    <dt class="fr-definition-list-term">Màu sắc</dt>
                                    <dd class="fr-definition-list-description">{{ $item->color }}</dd>
                                </dl>
                                <dl class="fr-definition-list inline">
                                    <dt class="fr-definition-list-term">Kích cỡ</dt>
                                    <dd class="fr-definition-list-description">{{ $item->size }}</dd>
                                </dl>
                                <div class="mb-m"></div>
                            </div>
                        </div>
                    </a>
                    <div class="mt-l mb-l">
                        <div class="fr-h-rule full"><hr></div>
                    </div>
                </div>
                @endforeach
            @endif

        </div>
    </div>
</div>
@endsection
