@extends('frontend.layouts.master')

@push('css')
@endpush

@section('content')
    <div class="topup-details-section ptb-120">
        <div class="container">
            <div class="row justify-content-center mb-30-none">
                <div class="col-xl-12 mb-30">
                    <div class="dash-payment-item-wrapper">
                        <div class="dash-payment-item active">
                            <div class="dash-payment-title-area">
                                <span class="dash-payment-badge">!</span>
                                <h5 class="title">{{ __('Buy Game Credit') }}</h5>
                            </div>
                            <div class="dash-payment-body">
                                <form action="{{ route('top.up.preview.submit') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="token" value="{{ $token }}">
                                    <div class="preview-list-wrapper">
                                        @foreach ($temp_data->data->player_id as $item)
                                            <div class="preview-list-item">
                                                <div class="preview-list-left">
                                                    <div class="preview-list-user-wrapper">
                                                        <div class="preview-list-user-icon">
                                                            <i class="las la-sign-in-alt"></i>
                                                        </div>
                                                        <div class="preview-list-user-content">
                                                            <span>{{ $item->label }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="preview-list-right">
                                                    <span>{{ $item->value }}</span>
                                                </div>
                                            </div>
                                        @endforeach

                                        <div class="preview-list-item">
                                            <div class="preview-list-left">
                                                <div class="preview-list-user-wrapper">
                                                    <div class="preview-list-user-icon">
                                                        <i class="las la-coins"></i>
                                                    </div>
                                                    <div class="preview-list-user-content">
                                                        <span>{{ __('Recharge Amount') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="preview-list-right">
                                                <span>{{ $temp_data->data->recharge_coin[0] }}
                                                    {{ $temp_data->data->coin_type }}</span>
                                            </div>
                                        </div>
                                        <div class="preview-list-item">
                                            <div class="preview-list-left">
                                                <div class="preview-list-user-wrapper">
                                                    <div class="preview-list-user-icon">
                                                        <i class="las la-receipt"></i>
                                                    </div>
                                                    <div class="preview-list-user-content">
                                                        <span>{{ __('Price') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="preview-list-right">
                                                <span class="text--warning">{{ $temp_data->data->recharge_coin[1] }}
                                                    {{ $temp_data->data->currency }}</span>
                                            </div>
                                        </div>
                                        <div class="preview-list-item">
                                            <div class="preview-list-left">
                                                <div class="preview-list-user-wrapper">
                                                    <div class="preview-list-user-icon">
                                                        <i class="las la-battery-half"></i>
                                                    </div>
                                                    <div class="preview-list-user-content">
                                                        <span>{{__('Fees & Charges')}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="preview-list-right">
                                                <span>{{ $temp_data->data->total_charge }}
                                                    {{ $temp_data->data->currency }}</span>
                                            </div>
                                        </div>
                                        <div class="preview-list-item">
                                            <div class="preview-list-left">
                                                <div class="preview-list-user-wrapper">
                                                    <div class="preview-list-user-icon">
                                                        <i class="las la-money-check-alt"></i>
                                                    </div>
                                                    <div class="preview-list-user-content">
                                                        <span>{{__('Total Payable Amount')}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="preview-list-right">
                                                <span class="text--base last">{{ $temp_data->data->payable }}
                                                    {{ $temp_data->data->currency }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="withdraw-btn mt-20 text-center">
                                        <button class="btn--base w-75" type="submit">{{__('Confirm')}}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
