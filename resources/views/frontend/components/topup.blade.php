@php
    //get selected language
    $lang = selectedLang();
    //recharge
    $recharge_slug = Illuminate\Support\Str::slug(App\Constants\SiteSectionConst::RECHARGE_SECTION);
    $recharge = App\Models\Admin\SiteSections::getData($recharge_slug)->first();

    $top_up_games = App\Models\Admin\TopUpGame::where('status', 1)
        ->latest()
        ->paginate(12);

@endphp
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
            Start topup
     ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->

<div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-6 col-lg-8">
            <div class="section-header text-center">
                <span class="section-sub-titel"><i class="{{ @$recharge->value->language->$lang->social_icon }}"></i>
                    {{ @$recharge->value->language->$lang->sub_heading }}</span>
                <h2 class="section-title"> <span
                        class="text--base">{{ @$recharge->value->language->$lang->heading }}</span></h2>
            </div>
        </div>
    </div>
    <div class="topup-area">
        <div class="row justify-content-center mb-30-none">
            @forelse ($top_up_games as $top_up_game)
                {{-- @dd($top_up_game) --}}
                <div class="col-xl-2 col-lg-3 col-md-3 col-sm-4 col-xs-6 mb-30">
                    <a href="{{ route('top.up.details', $top_up_game->slug) }}">
                        <div class="topup-item">
                            <div class="topup-thumb">
                                <img src="{{ get_image(@$top_up_game->profile_image, 'top-up-game') }}" alt="topup">
                            </div>
                            <div class="topup-content">
                                <h5 class="title">{{ @$top_up_game->title }}</h5>
                                <p>{{ $top_up_game->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-xl-2 col-lg-3 col-md-3 col-sm-4 col-xs-6 mb-30">
                    <h6>{{__('No Data Found!')}}</h6>
                </div>
            @endforelse

        </div>
    </div>
    <div class="d-flex justify-content-center">
        {{ get_paginate($top_up_games) }}
    </div>
</div>

<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
               End topup
        ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
