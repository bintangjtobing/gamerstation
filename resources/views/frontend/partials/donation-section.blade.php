<section class="service-section bg-overlay-section pt-20 bg_img"
    data-background="{{ asset('public/frontend') }}/images/section-element/donation_bg.jpg">

    <div class="container">
        <div class="row mb-30-none justify-content-center  align-items-center">
            <div class="col-xl-6 col-lg-6 col-md-12 mb-30">
                <div class="section-header">
                    <span class="section-sub-titel"><i class="{{ @$donetion->value->language->$lang->social_icon }}"></i>
                        {{ @$donetion->value->language->$lang->sub_heading }}</span>
                    <h2 class="section-title"><span
                            class="text--base">{{ @$donetion->value->language->$lang->heading }}</span></h2>

                    <p>{{ @$donetion->value->language->$lang->description }}</p>
                    <div class="section-cmn-btn">
                        <a href="{{ @$donetion->value->language->$lang->button_link }}"
                            class="btn--base">{{ @$donetion->value->language->$lang->button_text }}</a>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-lg-6 col-md-12 mb-30">
                <div class="section-image">
                    <img src="{{ get_image(@$donetion->value->image, 'site-section') }}" alt="image">
                </div>
            </div>
        </div>
    </div>
</section>
