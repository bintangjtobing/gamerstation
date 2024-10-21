@extends('frontend.layouts.master')

@push('css')
@endpush
@php
    //get selected language
    $lang = selectedLang();
    // header slider
    $header_slider_slug = Illuminate\Support\Str::slug(App\Constants\SiteSectionConst::HEADER_SLIDERS_SECTION);
    $header_slider = App\Models\Admin\SiteSections::getData($header_slider_slug)->first();

    //searvice
    $service_slug = Illuminate\Support\Str::slug(App\Constants\SiteSectionConst::SERVICES_SECTION);
    $service = App\Models\Admin\SiteSections::getData($service_slug)->first();
    //recharge
    $recharge_slug = Illuminate\Support\Str::slug(App\Constants\SiteSectionConst::RECHARGE_SECTION);
    $recharge = App\Models\Admin\SiteSections::getData($recharge_slug)->first();

    // testimonial
    $testimonial_slug = Illuminate\Support\Str::slug(App\Constants\SiteSectionConst::TESTIMONIAL_SECTION);
    $testimonial = App\Models\Admin\SiteSections::getData($testimonial_slug)->first();
    // donetion
    $donetion_slug = Illuminate\Support\Str::slug(App\Constants\SiteSectionConst::DONETION_SECTION);
    $donetion = App\Models\Admin\SiteSections::getData($donetion_slug)->first();
    //blog
    $blog_slug = Illuminate\Support\Str::slug(App\Constants\SiteSectionConst::BLOG_SECTION);
    $blog = App\Models\Admin\SiteSections::getData($blog_slug)->first();
@endphp
@section('content')
    @php
        $login_message = session()->get('message');
        $auth_permission = session()->get('auth_permission');

    @endphp



    @include('frontend.partials.banner-slider')

    @include('frontend.partials.services')

    @include('frontend.partials.donation-section')

    @include('frontend.partials.top-up')

    @include('frontend.components.about-company')

    @include('frontend.components.why-choose')

    @include('frontend.components.testimonial')

    @include('frontend.partials.blog-section')
@endsection


@push('script')
    <script>
        var login_message = "{{ $login_message }}";
        var auth_permission = "{{ $auth_permission }}";

        console.log(login_message);

        if (login_message != '') {
            throwMessage('error', [login_message]);

        }
        if (auth_permission == true) {
            $('.account-section').addClass('active');
        }
    </script>
@endpush
