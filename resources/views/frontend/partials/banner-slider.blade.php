<section class="banner-slider">
    <div class="swiper-wrapper">
        @foreach ($header_slider->value->items as $item)
            <div class="swiper-slide">
                <div class="banner-section bg_img" data-background="{{ get_image(@$item->image, 'site-section') }}">
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-xl-6 col-lg-8 col-md-9">
                                <div class="banner-content">

                                    <div class="banner-title-area">
                                        <h1 class="title"><span
                                                class="text--base">{{ @$item->language->$lang->item_title }}</span></h1>
                                        <p>{{ @$item->language->$lang->item_description }}</p>
                                    </div>
                                    <div class="banner-btn-area">
                                        <a href="{{ @$item->language->$lang->left_button_link }}"
                                            class="banner-btn style-gap">{{ @$item->language->$lang->left_button_text }}</a>
                                        <a href="{{ @$item->language->$lang->right_button_link }}"
                                            class="banner-btn">{{ @$item->language->$lang->right_button_text }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

    </div>
    <div class="slider-prev slider-nav">
        <i class="las la-angle-left"></i>
    </div>
    <div class="slider-next slider-nav">
        <i class="las la-angle-right"></i>
    </div>
    <div class="swiper-pagination"></div>
</section>
