<section class="topup-section pt-120">
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
                    <div class="col-xl-2 col-lg-3 col-md-3 col-sm-4 col-xs-6 mb-30">
                        <a href="{{ route('top.up.details', $top_up_game->slug) }}">
                            <div class="topup-item">
                                <div class="topup-thumb">
                                    <img src="{{ get_image(@$top_up_game->profile_image, 'top-up-game') }}"
                                        alt="topup">
                                </div>
                                <div class="topup-content">
                                    <h5 class="title">{{ @$top_up_game->title }}</h5>
                                    <p>{{ $top_up_game->created_at->format('M d, Y') }}</p>
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-12 mb-30 text-center">
                        <h6>{{ __('No Data Found') }}</h6>
                    </div>
                @endforelse

            </div>
        </div>
        <div class="topup-btn-area pt-60 text-center">
            <a href="{{ route('topup') }}" class="btn--base">{{ __('view All') }}</a>
        </div>
    </div>
</section>
