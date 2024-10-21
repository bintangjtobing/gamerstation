@php
    //get selected language
    $lang = selectedLang();
    // Blog section
    $blogs = App\Models\Blog::limit(3)->get();
@endphp
<section class="blog-section ptb-120">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-6">
                <div class="section-header text-center">
                    <span class="section-sub-titel"><i class="{{ @$blog->value->language->$lang->social_icon }}"></i>
                        {{ @$blog->value->language->$lang->sub_heading }}</span>
                    <h2 class="section-title"><span
                            class="text--base">{{ @$blog->value->language->$lang->heading }}</span>
                    </h2>
                </div>
            </div>
        </div>
        <div class="row mb-30-none justify-content-center">
            @forelse ($blogs as $blog)

                <div class="col-xl-4 col-lg-4 col-md-6 mb-30">
                    <div class="blog-item">
                        <div class="blog-item-thumb">
                            <img src="{{ get_image(@$blog->image, 'blog') }}" alt="blog">
                        </div>
                        <div class="blog-item-content">
                            <div class="blog-item-top-content">
                                <div class="title-area">
                                    @foreach (@$blog->lan_tags->language->$lang->tags ?? [] as $tag)
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
                                        <img src="{{ get_image(@$blog->admin->image, 'admin-profile') }}"
                                            alt="blog">
                                    </div>
                                    <div class="thumb-title">
                                        <h5 class="thumb-title-text">{{ @$blog->admin->firstname }}
                                            {{ @$blog->admin->lastname }}</h5>
                                        <span>{{ date('d M Y', strtotime($blog->created_at)) }}</span>
                                    </div>
                                </div>
                                <div class="blog-item-btn-area">
                                    <a href="{{ route('blog.details', ['id' => $blog->id, 'slug' => $blog->slug]) }}">{{ __('Read More') }}
                                        <i class="las la-angle-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <p>{{ __('Data Not Found') }}</p>
            @endforelse

        </div>
        <div class="blog-item-btn-area text-center mt-60">
            <a href="{{ route('blog') }}" class="btn--base"><i class="las la-gamepad"></i> {{ __('View More') }}</a>
        </div>
    </div>
</section>
