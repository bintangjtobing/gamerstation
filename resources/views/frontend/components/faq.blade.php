@php
    //get selected language
    $lang = selectedLang();
    // FAQ section
    $faq_slug = Illuminate\Support\Str::slug(App\Constants\SiteSectionConst::FAQ_SECTION);
    $faq = App\Models\Admin\SiteSections::getData($faq_slug)->first();
@endphp
<section class="faq-section ptb-120">
    <div class="container">
        <div class="row justify-content-center">
            {{-- @dd($faq->value) --}}
            @if (isset($faq->value->items))
                @foreach ($faq->value->items as $item)
                    <div class="col-xl-6 col-lg-6 mb-20">
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
</section>
