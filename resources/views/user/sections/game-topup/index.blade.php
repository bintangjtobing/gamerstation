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
        <div class="dashboard-area mt-10">
            <div class="dashboard-header-wrapper">
                <h3 class="title">{{ __('Add Money') }}</h3>
            </div>
        </div>
        <div class="row mb-30-none">
            <div class="col-lg-12 mb-30">
                <div class="dash-payment-item-wrapper">
                    <div class="dash-payment-item active">
                        <div class="dash-payment-title-area">
                            <span class="dash-payment-badge">!</span>
                            <h5 class="title">{{ __($page_title) }}</h5>
                        </div>
                        <div class="dash-payment-body">
                            <form class="card-form" action="{{ setRoute('user.add.money.submit') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-xl-12 col-lg-12 form-group text-center">
                                        <div class="exchange-area">
                                            <code class="d-block text-center"><span>{{ __('Exchange Rate') }}</span> <span
                                                    class="rate-show">--</span></code>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 form-group">
                                        <label>{{ __('Payment Gateway') }}<span>*</span></label>
                                        <select class="form--control nice-select gateway-select" name="currency">
                                            {{-- <option disabled selected>Select Gateway</option> --}}
                                            @foreach ($payment_gateways_currencies ?? [] as $item)
                                                <option value="{{ $item->alias }}"
                                                    data-currency="{{ $item->currency_code }}"
                                                    data-min_amount="{{ $item->min_limit }}"
                                                    data-max_amount="{{ $item->max_limit }}"
                                                    data-percent_charge="{{ $item->percent_charge }}"
                                                    data-fixed_charge="{{ $item->fixed_charge }}"
                                                    data-rate="{{ $item->rate }}">
                                                    {{ $item->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-xl-6 col-lg-6 form-group">
                                        <label>{{ __('Amount') }}<span>*</span></label>
                                        <div class="input-group">
                                            <input type="number" class="form--control" placeholder="Enter Amount"
                                                name="amount" value="{{ old('amount') }}" required>
                                            <select class="form--control nice-select">
                                                <option value="{{ get_default_currency_code() }}">
                                                    {{ get_default_currency_code() }}</option>
                                            </select>
                                        </div>

                                    </div>
                                    <div class="col-xl-12 col-lg-12 form-group">
                                        <div class="note-area">
                                            <code class="d-block fees-show">--</code>
                                            <code class="d-block limit-show">--</code>
                                            <code class="d-block mt-10 text-end text--warning balance-show">Available
                                                Balance
                                                {{ authWalletBalance() }} {{ get_default_currency_code() }}</code>
                                        </div>
                                    </div>

                                    <div class="col-xl-12 col-lg-12 mt-50">
                                        <button type="submit" class="btn--base w-100 btn-loading">{{ __('Add Money') }} <i
                                                class="fas fa-plus-circle ms-1"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection

@push('script')
    <script>
        var defualCurrency = "{{ get_default_currency_code() }}";
        var defualCurrencyRate = "{{ get_default_currency_rate() }}";

        $('select[name=currency]').on('change', function() {
            getExchangeRate($(this));
            getLimit();
            getFees();
            getPreview();
        });
        $(document).ready(function() {
            getExchangeRate();
            getLimit();
            getFees();
            // getPreview();
        });
        $("input[name=amount]").keyup(function() {
            getFees();
            getPreview();
        });

        function getExchangeRate(event) {
            var element = event;
            var currencyCode = acceptVar().currencyCode;
            var currencyRate = acceptVar().currencyRate;
            var currencyMinAmount = acceptVar().currencyMinAmount;
            var currencyMaxAmount = acceptVar().currencyMaxAmount;
            $('.rate-show').html("1 " + defualCurrency + " = " + parseFloat(currencyRate).toFixed(2) + " " + currencyCode);
        }

        function getLimit() {
            var sender_currency = acceptVar().currencyCode;
            var sender_currency_rate = acceptVar().currencyRate;
            var min_limit = acceptVar().currencyMinAmount;
            var max_limit = acceptVar().currencyMaxAmount;
            if ($.isNumeric(min_limit) || $.isNumeric(max_limit)) {
                var min_limit_calc = parseFloat(min_limit * defualCurrencyRate).toFixed(2);
                var max_limit_clac = parseFloat(max_limit * defualCurrencyRate).toFixed(2);
                $('.limit-show').html("Limit " + min_limit_calc + " " + defualCurrency + " - " + max_limit_clac + " " +
                    defualCurrency);
                return {
                    minLimit: min_limit_calc,
                    maxLimit: max_limit_clac,
                };
            } else {
                $('.limit-show').html("--");
                return {
                    minLimit: 0,
                    maxLimit: 0,
                };
            }
        }

        function acceptVar() {
            var selectedVal = $("select[name=currency] :selected");
            var currencyCode = $("select[name=currency] :selected").attr("data-currency");
            var currencyRate = $("select[name=currency] :selected").attr("data-rate");
            var currencyMinAmount = $("select[name=currency] :selected").attr("data-min_amount");
            var currencyMaxAmount = $("select[name=currency] :selected").attr("data-max_amount");
            var currencyFixedCharge = $("select[name=currency] :selected").attr("data-fixed_charge");
            var currencyPercentCharge = $("select[name=currency] :selected").attr("data-percent_charge");

            // var sender_select = $("input[name=from_wallet_id] :selected");

            return {
                currencyCode: currencyCode,
                currencyRate: currencyRate,
                currencyMinAmount: currencyMinAmount,
                currencyMaxAmount: currencyMaxAmount,
                currencyFixedCharge: currencyFixedCharge,
                currencyPercentCharge: currencyPercentCharge,
                selectedVal: selectedVal,

            };
        }

        function feesCalculation() {
            var sender_currency = acceptVar().currencyCode;
            var sender_currency_rate = acceptVar().currencyRate;
            var sender_amount = $("input[name=amount]").val();
            sender_amount == "" ? (sender_amount = 0) : (sender_amount = sender_amount);

            var fixed_charge = acceptVar().currencyFixedCharge;
            var percent_charge = acceptVar().currencyPercentCharge;
            if ($.isNumeric(percent_charge) && $.isNumeric(fixed_charge) && $.isNumeric(sender_amount)) {
                // Process Calculation
                var fixed_charge_calc = parseFloat(sender_currency_rate * fixed_charge);
                var percent_charge_calc = parseFloat(sender_currency_rate) * (parseFloat(sender_amount) / 100) * parseFloat(
                    percent_charge);
                var total_charge = parseFloat(fixed_charge_calc) + parseFloat(percent_charge_calc);
                total_charge = parseFloat(total_charge).toFixed(2);
                // return total_charge;
                return {
                    total: total_charge,
                    fixed: fixed_charge_calc,
                    percent: percent_charge_calc,
                };
            } else {
                // return "--";
                return false;
            }
        }

        function getFees() {
            var sender_currency = acceptVar().currencyCode;
            var percent = acceptVar().currencyPercentCharge;
            var charges = feesCalculation();
            if (charges == false) {
                return false;
            }
            $(".fees-show").html("Charge: " + parseFloat(charges.fixed).toFixed(2) + " " + sender_currency + " + " +
                parseFloat(percent).toFixed(2) + "% = " + parseFloat(charges.total).toFixed(2) + " " +
                sender_currency);
        }

        function getPreview() {
            var senderAmount = $("input[name=amount]").val();
            var sender_currency = acceptVar().currencyCode;
            var sender_currency_rate = acceptVar().currencyRate;
            // var receiver_currency = acceptVar().rCurrency;
            senderAmount == "" ? senderAmount = 0 : senderAmount = senderAmount;

            // Sending Amount
            $('.request-amount').text(senderAmount + " " + defualCurrency);

            // Fees
            var charges = feesCalculation();
            // console.log(total_charge + "--");
            $('.fees').text(charges.total + " " + sender_currency);

            // will get amount
            // var willGet = parseFloat(senderAmount) - parseFloat(charges.total);
            var willGet = parseFloat(senderAmount).toFixed(2);
            $('.will-get').text(willGet + " " + defualCurrency);

            // Pay In Total
            var totalPay = parseFloat(senderAmount) * parseFloat(sender_currency_rate)
            var pay_in_total = parseFloat(charges.total) + parseFloat(totalPay);
            $('.pay-in-total').text(parseFloat(pay_in_total).toFixed(2) + " " + sender_currency);

        }
    </script>
@endpush
