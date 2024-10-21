@php
    //get selected language
    $lang = selectedLang();
    // about
    $about_slug = Illuminate\Support\Str::slug(App\Constants\SiteSectionConst::ABOUT_SECTION);
    $about = App\Models\Admin\SiteSections::getData($about_slug)->first();
@endphp

<section class="about-section ptb-120">
    <div class="container">
        <div class="row mb-30-none align-items-center">
            <div class="col-xl-6 col-lg-6 col-md-12 mb-30">
                <div class="about-content-wrapper">
                    <div class="about-content-area">
                        <div class="section-header">
                            <span class="section-sub-titel"><i
                                    class="{{ @$about->value->language->$lang->social_icon }}"></i>
                                {{ @$about->value->language->$lang->sub_heading }}</span>
                            <h2 class="section-title"><span
                                    class="text--base">{{ @$about->value->language->$lang->heading }}</span></h2>
                            <p>{{ @$about->value->language->$lang->description }}</p>
                        </div>
                    </div>
                    <div class="about-feature-area">
                        <ul class="feature-list">
                            @foreach ($about->value->items as $item)
                                <li>{{ @$item->language->$lang->item_title }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="feature-statistics-wrapper">
                        <div class="row mb-30-none">
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 mb-30">
                                <div class="statistics-item">
                                    <div class="statistics-content">
                                        <div class="odo-area">
                                            <h3 class="odo-title odometer"
                                                data-odometer-final="{{ @$about->value->language->$lang->users }}">0</h3>
                                            <h3 class="title">{{ formatNumberInKNotation($about->value->language->$lang->users ?? 0) }}</h3>
                                        </div>
                                        <p>{{__('Total Users')}}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 mb-30">
                                <div class="statistics-item">
                                    <div class="statistics-content">
                                        <div class="odo-area">
                                            <h3 class="odo-title odometer"
                                                data-odometer-final="{{ @$about->value->language->$lang->transactions }}">0</h3>
                                            <h3 class="title">
                                                {{ formatNumberInKNotation($about->value->language->$lang->transactions ?? 0) }}
                                            </h3>
                                        </div>
                                        <p>{{__('Total Transactions')}}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 mb-30">
                                <div class="statistics-item">
                                    <div class="statistics-content">
                                        <div class="odo-area">
                                            <h3 class="odo-title odometer"
                                                data-odometer-final="{{ @$about->value->language->$lang->country }}">0</h3>
                                            <h3 class="title">
                                                {{ formatNumberInKNotation($about->value->language->$lang->country ?? 0) }}
                                            </h3>
                                        </div>
                                        <p>{{__('Available Country')}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-lg-6 col-md-12 mb-30">
                <div class="about-thumb text-md-center">
                    <img src="{{ get_image(@$about->value->image, 'site-section') }}" alt="about">
                </div>
            </div>
        </div>
    </div>
</section>
