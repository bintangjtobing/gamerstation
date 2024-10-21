@extends('frontend.layouts.master')

@push('css')
@endpush

@section('content')
    <section class="checkout-section ptb-120">
        <div class="container">
            <div class="row justify-content-center mb-30-none">
                <div class="col-xl-9 col-lg-9 mb-30">
                    <div class="checkout-form-area">
                        <div class="checkout-form-header">
                            <h3 class="title">{{ __('Billing Details') }}</h3>
                        </div>
                        <form class="checkout-form" method="POST" action="{{ route('top.up.order') }}">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">
                            <input type="hidden" name="payable" value="{{ $temp_data->data->payable }}">
                            <input type="hidden" name="coin" value="{{ $temp_data->data->recharge_coin[0] }}">
                            <input type="hidden" name="coin_type" value="{{ $temp_data->data->coin_type }}">
                            <input type="hidden" name="amount" value="{{ $temp_data->data->recharge_coin[1] }}">
                            <div class="row justify-content-center mb-10-none">
                                <div class="col-lg-6 form-group">

                                    <label>{{ __('First Name') }}*</label>
                                    <input type="text" name="first_name" class="form--control"
                                        placeholder="Enter first name"
                                        value="{{ auth()->user()->firstname ?? old('first_name') }}">
                                </div>
                                <div class="col-lg-6 form-group">
                                    <label>{{ __('Last Name') }}</label>
                                    <input type="text" name="last_name" class="form--control"
                                        placeholder="Enter last name"
                                        value="{{ auth()->user()->lastname ?? old('last_name') }}">
                                </div>
                                <div class="col-lg-6 form-group">
                                    <label>{{ __('Email') }}*</label>
                                    <input type="email" name="email" class="form--control"
                                        placeholder="Enter Your Email" value="{{ auth()->user()->email ?? old('email') }}">
                                </div>
                                <div class="col-lg-6 form-group">
                                    <label>{{ __('Phone Number') }}</label>
                                    <input type="text" name="phone_number" class="form--control"
                                        placeholder="Enter phone number" value="{{ old('phone_number') }}">
                                </div>

                                <div class="col-lg-12 form-group">
                                    <label>{{ __('Address') }}</label>
                                    <input type="text" name="address" class="form--control" placeholder="Enter address"
                                        value="{{ old('address') }}">
                                </div>
                                <div class="col-lg-4 form-group">
                                    <label>{{ __('City') }}</label>
                                    <input type="text" name="city" class="form--control" placeholder="Enter city"
                                        value="{{ old('city') }}">
                                </div>
                                <div class="col-lg-4 form-group">
                                    <label>{{ __('State / Province / Region') }}</label>
                                    <input type="text" name="state" class="form--control" placeholder="Enter"
                                        value="{{ old('state') }}">
                                </div>
                                <div class="col-lg-4 form-group">
                                    <label>{{ __('Zip / Postal Code') }}</label>
                                    <input type="text" name="zip" class="form--control" placeholder="Enter zip"
                                        value="{{ old('zip') }}">
                                </div>
                                <div class="col-lg-12">
                                    <label>{{ __('select Payment Gateway') }}*</label>

                                    <select class="form--control nice-select gateway-select" name="currency">
                                        @foreach ($payment_gateways_currencies ?? [] as $item)
                                            <option value="{{ $item->alias }}" data-currency="{{ $item->currency_code }}"
                                                data-min_amount="{{ $item->min_limit }}"
                                                data-max_amount="{{ $item->max_limit }}"
                                                data-percent_charge="{{ $item->percent_charge }}"
                                                data-fixed_charge="{{ $item->fixed_charge }}"
                                                data-rate="{{ $item->rate }}">
                                                {{ $item->name }}
                                            </option>
                                        @endforeach
                                        @auth
                                            @php
                                                $user_id = auth()->user();
                                                if (!empty($user_id)) {
                                                    $user_wallet = App\Models\UserWallet::where(
                                                        'user_id',
                                                        $user_id->id,
                                                    )->first();
                                                }
                                            @endphp
                                            <option value="wallet_balance">{{ __('Wallet Balance') }}
                                                ({{ $user_wallet->balance }}
                                                {{ get_default_currency_code() }})
                                            </option>
                                        @endauth
                                    </select>

                                </div>
                            </div>

                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 mb-30">
                    <div class="order-samary-wrapper">
                        <div class="title-area">
                            <h4>{{ __('Summary') }}</h4>
                        </div>
                        <div class="your-order-area">
                            <ul class="your-order-list">
                                <li class="your-order">{{ __('Your Order') }} <span>{{ get_default_currency_symbol() }}
                                        {{ $temp_data->data->recharge_coin[1] }}</span></li>
                                <li class="charge">{{ __('Charge') }} <span>{{ get_default_currency_symbol() }}
                                        {{ $temp_data->data->total_charge }}</span></li>
                                <li class="total">{{ __('Total') }} : <span>{{ get_default_currency_symbol() }}
                                        {{ $temp_data->data->payable }}</span></li>
                            </ul>
                            <div class="pay-securely-btn text-center">
                                <button type="submit" class="btn--base w-75 mt-10">{{ __('Pay Now') }} <i
                                        class="las la-lock ms-1"></i></button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </section>
@endsection
