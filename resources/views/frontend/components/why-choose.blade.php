@php
    //get selected language
    $lang = selectedLang();
    // why choose
    $why_choose_slug = Illuminate\Support\Str::slug(App\Constants\SiteSectionConst::WHY_CHOOSE_SECTION);
    $why_choose = App\Models\Admin\SiteSections::getData($why_choose_slug)->first();
@endphp

<section class="feature-section ptb-100 bg_img"
    data-background="{{ asset('public/frontend') }}/images/section-element/bg2home.jpg">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-6 col-lg-8">
                <div class="section-header text-center">
                    <span class="section-sub-titel"><i
                            class="{{ @$why_choose->value->language->$lang->social_icon }} text-white"></i>
                        {{ @$why_choose->value->language->$lang->sub_heading }}</span>
                    <h2 class="section-title"> {{ @$why_choose->value->language->$lang->heading }}</h2>
                </div>
            </div>
        </div>
        <div class="feature-wrapper">
            <div class="row justify-content-center mb-30-none">
                @foreach ($why_choose->value->items as $item)
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 mb-30">
                        <div class="feature-item">
                            <div class="feature-icon-wrapper">
                                <div class="feature-icon">
                                    <i class="{{ @$item->language->$lang->item_social_icon }}"></i>
                                </div>
                            </div>
                            <div class="feature-content">
                                <h4 class="title">{{ @$item->language->$lang->item_title }}</h4>
                                <p>{{ @$item->language->$lang->item_description }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach


            </div>
        </div>
    </div>
</section>
