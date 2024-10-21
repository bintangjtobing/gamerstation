@extends('user.layouts.master')
@push('css')
@endpush

@section('breadcrumb')
    @include('user.components.breadcrumb', [
        'breadcrumbs' => [
            [
                'name' => __('Dashboard'),
                'url' => setRoute('user.dashboard'),
            ],
        ],
        'active' => __(@$page_title),
    ])
@endsection

@section('content')
    <div class="body-wrapper">
        <div class="dash-payment-item-wrapper">
            <div class="dash-payment-item active">
                <div class="dash-payment-title-area">
                    <span class="dash-payment-badge">!</span>
                    <h5 class="title">{{__('Add Money Summary')}}</h5>
                </div>
                <div class="dash-payment-body">
                    <form action="{{ route('user.add.money.preview.submit') }}" method="post">
                        @csrf
                        <input type="hidden" name="amount" value="{{ $tempData->data->enter_amount }}">
                        <input type="hidden" name="currency" value="{{ $tempData->data->alias }}">
                        <input type="hidden" name="token" value="{{ $token }}">
                        <div class="preview-list-wrapper">
                            <div class="preview-list-item">
                                <div class="preview-list-left">
                                    <div class="preview-list-user-wrapper">
                                        <div class="preview-list-user-icon">
                                            <i class="las la-receipt"></i>
                                        </div>
                                        <div class="preview-list-user-content">
                                            <span>{{__('Entered Amount')}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="preview-list-right">
                                    <span class="text--warning">{{ $tempData->data->enter_amount }}
                                        {{ $tempData->data->currency_code }}</span>
                                </div>
                            </div>
                            <div class="preview-list-item">
                                <div class="preview-list-left">
                                    <div class="preview-list-user-wrapper">
                                        <div class="preview-list-user-icon">
                                            <i class="las la-battery-half"></i>
                                        </div>
                                        <div class="preview-list-user-content">
                                            <span>{{__('Total Fees & Charges')}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="preview-list-right">
                                    <span>{{ $tempData->data->total_charge }} {{ $tempData->data->currency_code }}</span>
                                </div>
                            </div>
                            <div class="preview-list-item">
                                <div class="preview-list-left">
                                    <div class="preview-list-user-wrapper">
                                        <div class="preview-list-user-icon">
                                            <i class="lab la-get-pocket"></i>
                                        </div>
                                        <div class="preview-list-user-content">
                                            <span>{{__('Will Get')}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="preview-list-right">
                                    <span>{{ $tempData->data->witl_get }} {{ $tempData->data->currency_code }}</span>
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
                                    <span class="text--base last">{{ $tempData->data->total_payable }}
                                        {{ $tempData->data->currency_code }}</span>
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
@endsection
@push('script')
@endpush
