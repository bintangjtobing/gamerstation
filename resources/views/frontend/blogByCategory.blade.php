@extends('frontend.layouts.master')

@php
    $lang = selectedLang();
@endphp

@section('content')
    <section class="blog-section ptb-120">
        <div class="container">
            <div class="row mb-30">
                <h3 class="title  mb-30 text-center">{{ __(@$page_title) }}</h3>
                <div class="col-xl-8 col-lg-7 col-md-6 mb-30">
                    <div class="row mb-30-none">
                        @foreach ($blogs ?? [] as $blog)
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 mb-30">
                                <div class="blog-item">
                                    <div class="blog-thumb">
                                        <img src="{{ get_image(@$blog->image, 'blog') }}" alt="blog">
                                        <span
                                            class="category">{{ @$blog->short_title->language->$lang->short_title }}</span>
                                    </div>
                                    <div class="blog-item-content">
                                        <div class="blog-item-top-content">
                                            <div class="title-area">
                                                @foreach ($blog->lan_tags->language->$lang->tags ?? [] as $tag)
                                                    <a href="javascript:void()">#{{ $tag }}</a>
                                                @endforeach

                                            </div>
                                            <div class="time-area">
                                                <span><i class="las la-clock"></i>
                                                    {{ diffForHumans($blog->created_at) }}</span>
                                            </div>
                                        </div>
                                        <h4 class="title"><a
                                                href="{{ route('blog.details', ['id' => $blog->id, 'slug' => $blog->slug]) }}">{{ @$blog->name->language->$lang->name }}</a>
                                        </h4>
                                        <div class="blog-item-bottom-content">
                                            <div class="thumb-area">
                                                <div class="thumb">
                                                    <img src="{{ get_image(@$blog->admin->image, 'user-profile') }}"
                                                        alt="blog">
                                                </div>
                                                <div class="thumb-title">
                                                    <h5 class="thumb-title-text">{{ @$blog->admin->firstname }}
                                                        {{ @$blog->admin->lastname }}</h5>
                                                    <span>{{ date('d M Y', strtotime($blog->created_at)) }}</span>
                                                </div>
                                            </div>
                                            <div class="blog-item-btn-area">
                                                <a
                                                    href="{{ route('blog.details', ['id' => $blog->id, 'slug' => $blog->slug]) }}">{{ __('Read More') }}
                                                    <i class="las la-angle-right"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
                <div class="col-xl-4 col-lg-5 col-md-12 mb-30">
                    <div class="blog-sidebar">
                        <div class="widget-box mb-30">
                            <h4 class="widget-title">{{ __('Categories') }}</h4>
                            <div class="category-widget-box">
                                <ul class="category-list">
                                    @foreach ($categories ?? [] as $cat)
                                        @php
                                            $blogCount = App\Models\Blog::active()
                                                ->where('category_id', $cat->id)
                                                ->count();

                                        @endphp
                                        @if ($blogCount > 0)
                                            <li><a href="{{ setRoute('blog.by.category', [$cat->id, @$cat->slug]) }}">
                                                    {{ __($cat->data->language->$lang->name) }}<span>{{ @$blogCount }}</span></a>
                                            </li>
                                        @else
                                            <li><a href="javascript:void(0)">
                                                    {{ __($cat->data->language->$lang->name) }}<span>{{ @$blogCount }}</span></a>
                                            </li>
                                        @endif
                                    @endforeach

                                </ul>
                            </div>
                        </div>
                        <div class="widget-box mb-30">
                            <h4 class="widget-title">{{ __('Recent Posts') }}</h4>
                            <div class="popular-widget-box">
                                @foreach ($recentPost as $post)
                                    <div class="single-popular-item d-flex flex-wrap align-items-center">
                                        <div class="popular-item-thumb">
                                            <a href=" "><img src="{{ get_image(@$post->image, 'blog') }}"
                                                    alt="blog"></a>
                                        </div>
                                        <div class="popular-item-content">
                                            <span class="date">{{ $post->created_at->diffForHumans() }}</span>
                                            <h5 class="title"><a
                                                    href="{{ route('blog.details', [$post->id, @$post->slug]) }}">{{ @$post->name->language->$lang->name }}</a>
                                            </h5>

                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <nav>
                <ul class="pagination">
                    {{ get_paginate($blogs) }}
                </ul>
            </nav>
        </div>
    </section>
@endsection


@push('script')
@endpush
