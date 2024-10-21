@php
    //get selected language
    $lang = selectedLang();
    // Blog section
    $blogs = App\Models\Blog::get();
@endphp

{{-- <section class="blog-section ptb-120">
    <div class="container">
        <div class="row mb-30"> --}}
@forelse ($blogs as $blog)
    {{-- @dd($blog->id) --}}
    {{-- <div class="col-xl-8 col-lg-7 col-md-12 mb-30">
        <div class="row mb-30-none"> --}}
    {{-- <div class="col-xl-6 col-lg-12 col-md-6 mb-30"> --}}
    <div class="col-xl-4 col-lg-4 col-md-6 mb-30">
        <div class="blog-item">
            <div class="blog-item-thumb">
                <img src="{{ get_image(@$blog->image, 'blog') }}" alt="blog">
            </div>
            <div class="blog-item-content">
                <div class="blog-item-top-content">
                    <div class="title-area">
                        @foreach ($blog->tags ?? [] as $tag)
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
                            <img src="{{ get_image(@$blog->admin->image, 'user-profile') }}" alt="blog">
                        </div>
                        <div class="thumb-title">
                            <h5 class="thumb-title-text">{{ @$blog->admin->firstname }}
                                {{ @$blog->admin->lastname }}</h5>
                            <span>{{ date('d M Y', strtotime($blog->created_at)) }}</span>
                        </div>
                    </div>
                    <div class="blog-item-btn-area">
                        <a href="{{ route('blog.details', ['id' => $blog->id, 'slug' => $blog->slug]) }}">Read
                            More <i class="las la-angle-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- </div>
    </div> --}}
@empty
    <p>Data Not Found</p>
@endforelse


{{-- </div> --}}
{{-- <nav>
            <ul class="pagination">
                <li class="page-item disabled" aria-disabled="true" aria-label="« Previous">
                    <span class="page-link" aria-hidden="true">‹</span>
                </li>
                <li class="page-item active" aria-current="page"><span class="page-link">1</span></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item"><a class="page-link" href="#">4</a></li>
                <li class="page-item">
                    <a class="page-link next" href="#" rel="next" aria-label="Next »">›</a>
                </li>
            </ul>
        </nav> --}}
{{-- </div>
</section> --}}
