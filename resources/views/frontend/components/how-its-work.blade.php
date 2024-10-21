@php
    //get selected language
    $lang = selectedLang();
    // header slider
    $how_its_works_slug = Illuminate\Support\Str::slug(App\Constants\SiteSectionConst::HOW_ITS_WORKS_SECTION);
    $how_its_works = App\Models\Admin\SiteSections::getData($how_its_works_slug)->first();
@endphp
<div class="container">
    <div class="row">
        <div class="col-xl-6 col-lg-8">
            <div class="section-header">
                <span class="section-sub-titel"><i class="{{ @$how_its_works->value->language->$lang->social_icon }}"></i>
                    {{ @$how_its_works->value->language->$lang->sub_heading }}</span>
                <h2 class="section-title"> {{ @$how_its_works->value->language->$lang->heading }}</h2>
                <p>{{ @$how_its_works->value->language->$lang->description }}</p>
            </div>
        </div>
    </div>
    <div class="how-it-works-wrapper">
        <div class="row justify-content-center mb-30-none">
            @php
                $counter = count((array) $how_its_works->value->items);
            @endphp
            @foreach ($how_its_works->value->items ?? [] as $key => $item)
                @php
                    $counter--;
                @endphp
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 mb-30">
                    <div class="how-it-works-item">
                        <div class="how-it-works-icon-wrapper">
                            <div class="how-it-works-icon">
                                <i class="{{ @$item->language->$lang->item_social_icon }}"></i>
                            </div>
                        </div>
                        <div class="how-it-works-content">
                            <span class="sub-title">{{ @$item->language->$lang->item_step_number }}</span>
                            <h4 class="title">{{ @$item->language->$lang->item_title }}</h4>
                            <p>{{ @$item->language->$lang->item_description }}</p>
                        </div>
                        @if ($counter == 0)
                        @else
                            <span class="process-devider"></span>
                        @endif
                    </div>
                </div>
            @endforeach


        </div>
    </div>
    </section>
