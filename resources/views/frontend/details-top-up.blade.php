@extends('frontend.layouts.master')

@push('css')
@endpush
@section('content')
    <div class="topup-details-section pt-40 pb-120">
        <div class="container">
            <div class="row mb-30-none">
                <div class="col-xl-4 col-lg-5 mb-30">
                    <div class="topup-details-content-area">
                        <div class="topup-details-content-thumb">
                            <img src="{{ get_image(@$top_up_game->cover_image, 'top-up-game') }}" alt="thumb">
                        </div>
                        <div class="topup-details-content mt-20">
                            <h3 class="title">{{ @$top_up_game->title }}</h3>
                            <div class="topup-details-content-badge-area">
                                <span class="badge badge--base"><i class="las la-bolt"></i> {{{__('Instant Delivery')}}}</span>
                                <span class="badge badge--base"><i class="las la-check-circle"></i> {{__('Official
                                    Distributor')}}</span>
                            </div>
                            <p class="sub-title">{{ @$top_up_game->description }}</p>
                            @if ($top_up_game->google_store || $top_up_game->apple_store)
                            <div class="topup-details-download">
                                <p>{{__('Download')}} {{@$top_up_game->title}}</p>
                                <div class="topup-details-download-thumb">
                                    @if ($top_up_game->google_store)
                                        <a href="{{ @$top_up_game->google_store }}">
                                            <img src="{{ asset('public/frontend') }}/images/app/play_store.png"
                                                alt="download">
                                        </a>
                                    @endif
                                    @if ($top_up_game->apple_store)
                                        <a href="{{ @$top_up_game->apple_store }}">
                                            <img src="{{ asset('public/frontend') }}/images/app/app_store.png"
                                                alt="download">
                                        </a>
                                    @endif

                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-xl-8 col-lg-7 mb-30">
                    <form action="{{ route('top.up.submit') }}" method="POST" class="buy-coin-form">
                        @csrf
                        <input type="hidden" name="top_up_id" value="{{ $top_up_game->id }}">
                        <div class="dash-payment-item-wrapper">
                            <div class="dash-payment-item active">
                                <div class="dash-payment-title-area">
                                    <span class="dash-payment-badge">01</span>
                                    <h5 class="title">{{__('Player ID')}}</h5>
                                </div>
                                <div class="dash-payment-body">
                                    <div class="row">
                                        @forelse ($top_up_game->input_fields->input_fields_player_id as $item)
                                            <div class="col-xl-12">
                                                <div class="form-group">
                                                    <label for="{{ $item->name }}">{{ $item->label }}</label>
                                                    <input type="text" class="form--control" id="{{ $item->name }}"
                                                        name="{{ $item->name }}" placeholder="{{ $item->label }}"
                                                        required>
                                                </div>
                                            </div>

                                        @empty
                                            <p>{{__('No Data Found!')}}</p>
                                        @endforelse

                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="dash-payment-item-wrapper">
                            <div class="dash-payment-item active">
                                <div class="dash-payment-title-area">
                                    <span class="dash-payment-badge">02</span>
                                    <h5 class="title">{{__('Select Recharge')}}</h5>
                                </div>
                                <div class="dash-payment-body">
                                    <div class="recharge-option">
                                        @php
                                            $count = count($top_up_game->input_fields->input_fields_recharge);
                                            $number = 1;
                                        @endphp
                                        @forelse ($top_up_game->input_fields->input_fields_recharge as $item)
                                            <input type="hidden" name="coin_type" value="{{ $item->type }}">
                                            <div class="recharge-item">
                                                <div class="recharge-inner">
                                                    <input type="radio" name="recharge" class="hide-input"
                                                        id="recharge_{{ $number }}"
                                                        value="{{ $item->credit_amount }}|{{ $item->currency_amount }}"
                                                        {{ $loop->index == 0 ? 'checked' : '' }}>
                                                    <label for="recharge_{{ $number }}" class="package--amount">
                                                        <strong>{{ $item->credit_amount }} {{ $item->type }}</strong>
                                                        <sup>{{ get_default_currency_symbol() }}{{ $item->currency_amount }}</sup>
                                                    </label>
                                                </div>
                                            </div>

                                            @php
                                                $number++;
                                            @endphp
                                        @empty
                                            <p>{{__('Data Not Found!')}}</p>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="dash-payment-btn text-center">
                            <button type="submit" class="btn--base w-75">{{__('Buy Now')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
@endpush
