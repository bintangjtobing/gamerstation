@extends('user.layouts.master')

@section('breadcrumb')
    @include('user.components.breadcrumb', [
        'breadcrumbs' => [
            [
                'name' => __('Dashboard'),
                'url' => setRoute('user.dashboard'),
            ],
        ],
        'active' => __('Buy Coin'),
    ])
@endsection

@section('content')
    <form action="{{ route('user.buy.coin.submit') }}" method="POST" class="buy-coin-form">
        @csrf
        <div class="dash-payment-item-wrapper">
            <div class="dash-payment-item active">
                <div class="dash-payment-title-area">
                    <span class="dash-payment-badge">01</span>
                    <h5 class="title">{{ __('Enter TikTok ID') }}</h5>
                </div>
                <div class="dash-payment-body">
                    <div class="row">
                        <div class="col-xl-4">
                            <div class="form-group">
                                <label>{{ __('Login Vender') }}</label>
                                <select class="form--control nice-select" name="login_vender">
                                    <option value="google">Google</option>
                                    <option value="facebook">Facebook</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xl-4">
                            <div class="form-group">
                                <label>{{ __('Username Or Email') }}</label>
                                <input type="text" class="form--control" name="username_or_email"
                                    placeholder="Enter Username Or Email" required>
                            </div>
                        </div>
                        <div class="col-xl-4">
                            <div class="form-group">
                                <label>{{ __('Password') }}</label>
                                <input type="password" class="form--control" name="password" placeholder="Enter Password"
                                    required>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="dash-payment-item-wrapper">
            <div class="dash-payment-item active">
                <div class="dash-payment-title-area">
                    <span class="dash-payment-badge">02</span>
                    <h5 class="title">{{ __('Select Recharge') }}</h5>
                </div>
                <div class="dash-payment-body">
                    <div class="recharge-option">
                        {{-- @dd($add_coins) --}}
                        @forelse ($add_coins as $add_coin)
                            <div class="recharge-item">
                                <div class="recharge-inner">

                                    <input type="radio" name="recharge"
                                        value="{{ $add_coin->coin }}|{{ $add_coin->price }}" class="hide-input"
                                        id="recharge_{{ $add_coin->id }}">

                                    <label for="recharge_{{ $add_coin->id }}" class="package--amount">
                                        <strong>{{ $add_coin->coin }} {{ __('Coin') }}</strong>
                                        <sup>{{ get_default_currency_code() }}{{ $add_coin->price }}</sup>
                                    </label>
                                </div>
                            </div>
                        @empty
                            <div class="alert alert-primary text-center w-100">
                                {{ __('No Record Found!') }}
                            </div>
                        @endforelse


                    </div>
                </div>
            </div>
        </div>

        <div class="dash-payment-btn">
            <button type="submit" class="btn--base w-100">{{ __('Buy Now') }}</button>
        </div>
    </form>
@endsection


@push('script')
@endpush
