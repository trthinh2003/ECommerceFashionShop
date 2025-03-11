@extends('sites.master')
@section('title', 'Chi tiết bài viết')
@section('content')
    <!-- Blog Details Hero Begin -->
    <section class="blog-hero spad">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="col-lg-9 text-center">
                    <div class="blog__hero__text">
                        <h2>{{$blogDetail->title}}</h2>
                        <ul>
                            <li>By {{$blogDetail->staff->name}}</li>
                            <li>{{$blogDetail->created_at}}</li>
                            <li>8 Bình luận</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Blog Details Hero End -->

    <!-- Blog Details Section Begin -->
    <section class="blog-details spad">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="col-lg-12">
                    <div class="blog__details__pic">
                        <img src="{{ asset('uploads/'.$blogDetail->image) }}" alt="">
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="blog__details__content">
                        <div class="blog__details__share">
                            <span>Chia sẻ</span>
                            <ul>
                                <li><a href="https://www.facebook.com/?locale=vi_VN"><i class="fa fa-facebook"></i></a></li>
                                <li><a href="https://x.com/?lang=vi" class="twitter"><i class="fa fa-twitter"></i></a></li>
                                <li><a href="https://www.youtube.com/?app=desktop&hl=vi" class="youtube"><i class="fa fa-youtube-play"></i></a></li>
                                <li><a href="https://www.linkedin.com/" class="linkedin"><i class="fa fa-linkedin"></i></a></li>
                            </ul>
                        </div>
                        <div class="blog__details__text">
                            <p>{{$blogDetail->content}}</p>
                        </div>
                        <div class="blog__details__quote">
                            <i class="fa fa-quote-left"></i>
                            <p>“He he he, ha ha ha”</p>
                            <h6>_ {{$blogDetail->staff->name}} _</h6>
                        </div>
                        <div class="blog__details__text">
                            <p>{{$blogDetail->content}}</p>
                        </div>
                        <div class="blog__details__option">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="blog__details__author">
                                        <div class="blog__details__author__pic">
                                            <img src="{{ ('client/img/blog/details/blog-author.jpg') }}" alt="">
                                        </div>
                                        <div class="blog__details__author__text">
                                            <h5>{{$blogDetail->staff->name}}</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="blog__details__tags">
                                        @php
                                            $tags = explode(',', $blogDetail->tags);
                                        @endphp
                                        @foreach ($tags as $tag)
                                            <a href="#">#{{$tag}}</a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="blog__details__btns">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <a href="" class="blog__details__btns__item">
                                        <p><span class="arrow_left"></span>Tin trước đó</p>
                                        <h5>It S Classified How To Utilize Free Classified Ad Sites</h5>
                                    </a>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <a href="" class="blog__details__btns__item blog__details__btns__item--next">
                                        <p>Tin tiếp theo<span class="arrow_right"></span></p>
                                        <h5>Tips For Choosing The Perfect Gloss For Your Lips</h5>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="blog__details__comment">
                            <h4>Để lại đánh giá</h4>
                            <form action="#">
                                <div class="row">
                                    <div class="col-lg-4 col-md-4">
                                        <input type="text" placeholder="Name">
                                    </div>
                                    <div class="col-lg-4 col-md-4">
                                        <input type="text" placeholder="Email">
                                    </div>
                                    <div class="col-lg-4 col-md-4">
                                        <input type="text" placeholder="Phone">
                                    </div>
                                    <div class="col-lg-12 text-center">
                                        <textarea placeholder="Comment"></textarea>
                                        <button type="submit" class="site-btn">Gửi đánh giá</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Blog Details Section End -->
@endsection
