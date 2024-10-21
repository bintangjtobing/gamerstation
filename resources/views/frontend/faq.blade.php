@extends('frontend.layouts.master')
@php
    //get selected language
    $lang = selectedLang();
    // FAQ section
    $faq_slug = Illuminate\Support\Str::slug(App\Constants\SiteSectionConst::FAQ_SECTION);
    $faq = App\Models\Admin\SiteSections::getData($faq_slug)->first();
@endphp
@push('css')
@endpush

@section('content')
    <section class="faq-section ptb-120">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="row justify-content-center">
                        @if (isset($faq->value->items))
                            @foreach ($faq->value->items as $item)
                                <div class="col-xl-12 col-lg-12 mb-20">
                                    <div class="faq-wrapper">
                                        <div class="faq-item">
                                            <h3 class="faq-title"><span class="title">{{ $item->language->$lang->title }}
                                                </span><span class="right-icon"></span></h3>
                                            <div class="faq-content" style="display: none;">
                                                <p>{{ $item->language->$lang->description }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </section>
    @include('frontend.components.testimonial')
@endsection


@push('script')
@endpush
