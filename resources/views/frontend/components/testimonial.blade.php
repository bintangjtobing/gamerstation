@php
    //get selected language
    $lang = selectedLang();
    // testimonial
    $testimonial_slug = Illuminate\Support\Str::slug(App\Constants\SiteSectionConst::TESTIMONIAL_SECTION);
    $testimonial = App\Models\Admin\SiteSections::getData($testimonial_slug)->first();

@endphp

<section class="testimonial-section ptb-120 bg_img"
    data-background="{{ asset('public/frontend') }}/images/element/bg1.jpg"
    style="background-image: url(&quot;{{ asset('public/frontend') }}/images/element/bg1.jpg&quot;);">
    <div class="element-area">
        <img src="{{ asset('public/frontend') }}/images/element/shadow-2.5ab01ec0.svg" alt="element">
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-6">
                <div class="section-header text-center">
                    <span class="section-sub-titel"><i
                            class="{{ @$testimonial->value->language->$lang->social_icon }}"></i>
                        {{ @$testimonial->value->language->$lang->sub_heading }}</span>
                    <h2 class="section-title"><span
                            class="text--base">{{ @$testimonial->value->language->$lang->heading }}</span></h2>
                </div>
            </div>
        </div>
        <div class="testimonial-slider-wrapper">
            <div class="testimonial-slider swiper-container-horizontal">
                <div class="swiper-wrapper">
                    @foreach ($testimonial->value->items as $item)
                        <div class="swiper-slide">
                            <div class="testimonial-item">
                                <div class="testimonial-user-area">
                                    <div class="user-area">
                                        <img src="{{ get_image(@$item->image, 'site-section') }}" alt="user">
                                    </div>
                                    <div class="title-area">
                                        <h5>{{ @$item->language->$lang->item_name }}</h5>
                                        <span class="testimonial-date"><i class="las la-history"></i>
                                            {{ date('d-m-Y', strtotime($item->created_at)) }}</span>
                                    </div>
                                </div>
                                <h4 class="testimonial-title">{{ @$item->language->$lang->item_testimonial_title }}</h4>
                                <p>{{ @$item->language->$lang->item_testimonial_description }}</p>
                                <div class="testimonial-bottom-wrapper">
                                    <ul class="testimonial-icon-list">
                                        @php
                                            $rating = $item->language->$lang->item_testimonial_rating ?? 0;
                                        @endphp
                                        @for ($number = 1; $number <= $rating; $number++)
                                            <li><i class="las la-star"></i></li>
                                        @endfor

                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endforeach


                </div>
                <div class="slider-nav-area">
                    <div class="slider-prev slider-nav">
                        <i class="las la-arrow-left"></i>
                    </div>
                    <div class="slider-next slider-nav">
                        <i class="las la-arrow-right"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
