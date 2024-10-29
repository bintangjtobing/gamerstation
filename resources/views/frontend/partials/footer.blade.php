@php
//get selected language
$lang = selectedLang();
// Footer section
$footer_slug = Illuminate\Support\Str::slug(App\Constants\SiteSectionConst::FOOTER_SECTION);
$footer = App\Models\Admin\SiteSections::getData($footer_slug)->first();
//contact section
$contact_slug = Illuminate\Support\Str::slug(App\Constants\SiteSectionConst::CONTACT_SECTION);
$contact = App\Models\Admin\SiteSections::getData($contact_slug)->first();

// app setting
$app_setting = App\Models\Admin\AppSettings::first();
$useful_link = App\Models\Admin\UsefulLink::get();

@endphp

<footer class="footer-section pt-60 bg_img" data-background="{{ asset('public/frontend') }}/images/element/bg1.jpg">
    <div class="container">
        <div class="footer-wrapper">
            <div class="row mb-30-none">
                <div class="col-xxl-4 col-xl-4 col-lg-4 col-md-6 col-sm-6 mb-30">
                    <div class="footer-widget">
                        <div class="footer-logo">
                            <a href="{{ url('/') }}" class="site-logo site-title" href="{{ url('/') }}"><img
                                    src="{{ get_logo($basic_settings, 'dark') }}" alt="site-logo"></a>
                        </div>
                        <div class="footer-content">
                            <p>{{ @$footer->value->language->$lang->footer_description }}</p>
                        </div>
                        <div class="footer-content-bottom">
                            <ul class="footer-list logo">
                                {{-- <li><a href="javascript:void()"><i class="las la-phone-volume me-1"></i>
                                        {{ @$contact->value->language->$lang->phone }}</a></li> --}}
                                <li><a href="javascript:void()"><i class="las la-envelope me-1"></i>
                                        {{ @$contact->value->language->$lang->email }}</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-2 col-xl-2 col-lg-2 col-md-6 col-sm-6 mb-30">
                    <div class="footer-widget">
                        <h4 class="widget-title">{{__('Useful Links')}}</h4>
                        <ul class="footer-list">
                            @if (url('link/'))
                            @foreach ($useful_link as $item)
                            <li><a href="{{ url('link/' . $item->slug) }}">{{ @$item->title->language->$lang->title
                                    }}</a>
                            </li>
                            @endforeach
                            @endif
                        </ul>
                    </div>
                </div>
                <div class="col-xxl-2 col-xl-2 col-lg-2 col-md-6 col-sm-6 mb-30">
                    <div class="footer-widget">
                        {{-- <h4 class="widget-title">{{__('Download App')}}</h4>
                        <p>{{ @$footer->value->language->$lang->app_description }}</p>
                        <ul class="footer-list two">
                            <li><a href="{{ $app_setting->android_url }}" class="app-img"><img
                                        src="{{ asset('public/frontend') }}/images/app/play_store.png" alt="app"></a>
                            </li>
                            <li><a href="{{ $app_setting->iso_url }}" class="app-img"><img
                                        src="{{ asset('public/frontend') }}/images/app/app_store.png" alt="app"></a>
                            </li>
                        </ul> --}}
                    </div>
                </div>
                <div class="col-xxl-4 col-xl-4 col-lg-4 col-md-6 col-sm-6 mb-30">
                    <div class="footer-widget">
                        <h4 class="widget-title">{{__('Newsletter')}}</h4>
                        <p>{{ @$footer->value->language->$lang->newsletter_description }}</p>
                        <ul class="footer-list two">
                            <form action="{{ route('subscribe') }}" method="post">
                                @csrf
                                <li>
                                    <input type="text" placeholder="Name" name="name" class="form--control">
                                    <span class="input-icon"><i class="las la-user"></i></span>
                                </li>
                                <li>
                                    <input type="email" name="email" placeholder="Email" class="form--control">
                                    <span class="input-icon"><i class="las la-envelope"></i></span>
                                </li>
                                <li>
                                    <button type="submit" class="btn--base sub-btn">{{__('Subscribe')}} <i
                                            class="las la-arrow-right ms-1"></i></button>
                                </li>
                            </form>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="copyright-area">
                <div class="copyright-wrapper">
                    <p>©{{__(' Created by')}} <span class="text--base">{{ $basic_settings->site_name }}</span>
                        {{ date('Y') }}.</p>
                    @if ($footer->value->items)
                    <ul class="footer-social-list">
                        @foreach ($footer->value->items as $item)
                        <li>
                            <a href="{{ @$item->language->$lang->item_link }}"><i
                                    class="{{ @$item->language->$lang->item_social_icon }}"></i>
                                {{ @$item->language->$lang->item_title }}</a>
                        </li>
                        @endforeach


                    </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</footer>
