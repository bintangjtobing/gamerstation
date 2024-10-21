@extends('user.layouts.master')

@section('breadcrumb')
    @include('user.components.breadcrumb', [
        'breadcrumbs' => [
            [
                'name' => __('Dashboard'),
                'url' => setRoute('user.dashboard'),
            ],
        ],
        'active' => __('Buy Coin Preview'),
    ])
@endsection

@section('content')
    <div class="dash-payment-item-wrapper">
        <div class="dash-payment-item active">
            <div class="dash-payment-title-area">
                <span class="dash-payment-badge">!</span>
                <h5 class="title">{{ __('Buy TikTok Coin') }}</h5>
            </div>
            <div class="dash-payment-body">
                <div class="preview-list-wrapper">
                    <div class="preview-list-item">
                        <div class="preview-list-left">
                            <div class="preview-list-user-wrapper">
                                <div class="preview-list-user-icon">
                                    <i class="las la-sign-in-alt"></i>
                                </div>
                                <div class="preview-list-user-content">
                                    <span>{{ __('Login Vender') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="preview-list-right">
                            <span>{{ $validated['login_vender'] }}</span>
                        </div>
                    </div>
                    <div class="preview-list-item">
                        <div class="preview-list-left">
                            <div class="preview-list-user-wrapper">
                                <div class="preview-list-user-icon">
                                    <i class="las la-user-tag"></i>
                                </div>
                                <div class="preview-list-user-content">
                                    <span>{{ __('Username Or Email') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="preview-list-right">
                            <span>{{ $validated['username_or_email'] }}</span>
                        </div>
                    </div>
                    <div class="preview-list-item">
                        <div class="preview-list-left">
                            <div class="preview-list-user-wrapper">
                                <div class="preview-list-user-icon">
                                    <i class="las la-coins"></i>
                                </div>
                                <div class="preview-list-user-content">
                                    <span>{{ __('Recharge Coin') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="preview-list-right">
                            <span>{{ Session::get('coin') }} {{ __('Coin') }}</span>
                        </div>
                    </div>

                    <div class="preview-list-item">
                        <div class="preview-list-left">
                            <div class="preview-list-user-wrapper">
                                <div class="preview-list-user-icon">
                                    <i class="las la-battery-half"></i>
                                </div>
                                <div class="preview-list-user-content">
                                    <span>{{ __('Price') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="preview-list-right">
                            <span>{{ Session::get('price') }} {{ get_default_currency_code() }}</span>
                        </div>
                    </div>
                    <div class="preview-list-item">
                        <div class="preview-list-left">
                            <div class="preview-list-user-wrapper">
                                <div class="preview-list-user-icon">
                                    <i class="las la-battery-half"></i>
                                </div>
                                <div class="preview-list-user-content">
                                    <span>{{ __('Total Fees & Charges') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="preview-list-right">
                            <span>{{ $fixed_charge + $percent_charge }} {{ get_default_currency_code() }}</span>
                        </div>
                    </div>
                    <div class="preview-list-item">
                        <div class="preview-list-left">
                            <div class="preview-list-user-wrapper">
                                <div class="preview-list-user-icon">
                                    <i class="las la-money-check-alt"></i>
                                </div>
                                <div class="preview-list-user-content">
                                    <span>{{ __('Total Payable Amount') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="preview-list-right">
                            <span class="text--base last">{{ Session::get('price') + $fixed_charge + $percent_charge }}
                                {{ get_default_currency_code() }}</span>
                        </div>
                    </div>
                </div>
                <form action="{{ route('user.buy.coin.previewSubmit') }}" method="post">
                    @csrf
                    <div class="withdraw-btn mt-20">
                        <button type="submit" class="btn--base w-100">{{ __('Confirm') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


@push('script')
@endpush
