<section class="service-section ptb-120 bg_img" data-background="{{ asset('public/frontend') }}/images/element/bg1.jpg">
    <div class="element-area">
        <img src="{{ asset('public/frontend') }}/images/element/shadow-2.5ab01ec0.svg" alt="element">
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-6 col-lg-8">
                <div class="section-header text-center">
                    <span class="section-sub-titel"><i class="{{ @$service->value->language->$lang->social_icon }}"></i>
                        {{ @$service->value->language->$lang->sub_heading }}</span>
                    <h2 class="section-title"> <span
                            class="text--base">{{ @$service->value->language->$lang->heading }}</span> </h2>
                    <p>{{ @$service->value->language->$lang->description }}</p>
                </div>
            </div>
        </div>
        <div class="service-wrapper">
            <div class="row mb-30-none">
                @foreach ($service->value->items as $item)
                    <div class="col-xl-3 col-lg-3 col-md-6 mb-30">
                        <div class="service-item">
                            <div class="service-icon">
                                <span><i class="{{ @$item->language->$lang->item_social_icon }}"></i></span>
                            </div>
                            <h4 class="title">{{ @$item->language->$lang->item_title }}</h4>
                            <p>{{ @$item->language->$lang->item_description }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
