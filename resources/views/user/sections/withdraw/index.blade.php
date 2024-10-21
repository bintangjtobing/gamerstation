@extends('user.layouts.master')

@section('breadcrumb')
    @include('user.components.breadcrumb', [
        'breadcrumbs' => [
            [
                'name' => __('Dashboard'),
                'url' => setRoute('user.dashboard'),
            ],
        ],
        'active' => __('Money Out'),
    ])
@endsection
@push('css')
    <style>
        .input-group input {
            color: #ffffff;
        }
    </style>
@endpush
@section('content')
    <div class="row mb-20-none">
        <div class="col-xl-7 col-lg-7 mb-20">
            <div class="custom-card mt-10">
                <div class="dashboard-header-wrapper">
                    <h5 class="title">{{ __($page_title) }}</h5>
                </div>
                <div class="card-body">
                    <form class="card-form bounce-safe" action="{{ setRoute('user.withdraw.submit') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-xl-12 col-lg-12 form-group text-center">
                                <div class="exchange-area">
                                    <code class="d-block text-center"><span>{{ __('Exchange Rate') }}</span> <span
                                            class="rate-show">--</span> </code>
                                </div>
                            </div>
                            <div class="col-xl-12 col-lg-12 form-group">
                                <label>{{ __('Payment Gateway') }}<span>*</span></label>
                                <select class="form--control nice-select" name="gateway">
                                    <option selected disabled>{{__('Select Gateway')}}</option>
                                    @foreach ($payment_gateways as $item)
                                        <option value="{{ $item->alias }}"
                                            data-item="{{ json_encode($item->currencies()->select(['name', 'alias', 'rate', 'currency_code', 'percent_charge', 'fixed_charge', 'min_limit', 'max_limit'])->first()) }}">
                                            {{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-xl-12 col-lg-12 form-group">
                                <label>{{ __('Amount') }}<span>*</span></label>
                                <div class="input-group">
                                    <input type="number" class="form--control" placeholder="Enter Amount" name="amount"
                                        value="{{ old('amount') }}" required>
                                    <input type="hidden" name="sender_currency">



                                    <select class="form--control nice-select" name="sender_currency">
                                        @foreach ($user_wallets as $item)
                                            <option class="custom-option" data-item='{{ json_encode($item->currency) }}'
                                                selected value="{{ $item->currency->code }}">{{ $item->currency->code }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-12 col-lg-12 form-group">
                                <div class="note-area">
                                    <code class="d-block limit-show"></code>
                                    <code class="d-block fees-show"></code>
                                </div>
                            </div>


                            <div class="col-xl-12 col-lg-12 form-group">
                                <div class="note-area">
                                    <code class="d-block text-end balance-show"></code>
                                </div>
                            </div>

                        </div>
                        <div class="col-xl-12 col-lg-12">
                            <button type="submit" class="btn--base w-100">{{ __('Money Out') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-xl-5 col-lg-5 mb-20">
            <div class="custom-card mt-10">
                <div class="dashboard-header-wrapper">
                    <h5 class="title">{{ __('Summary') }}</h5>
                </div>
                <div class="card-body">
                    <div class="preview-list-wrapper">
                        <div class="preview-list-item">
                            <div class="preview-list-left">
                                <div class="preview-list-user-wrapper">
                                    <div class="preview-list-user-icon">
                                        <i class="las la-receipt"></i>
                                    </div>
                                    <div class="preview-list-user-content">
                                        <span>{{ __('Entered Amount') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="preview-list-right">
                                <span class="text--success request-amount">--</span>
                            </div>
                        </div>
                        <div class="preview-list-item">
                            <div class="preview-list-left">
                                <div class="preview-list-user-wrapper">
                                    <div class="preview-list-user-icon">
                                        <i class="las la-receipt"></i>
                                    </div>
                                    <div class="preview-list-user-content">
                                        <span>{{ __('Conversion Amount') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="preview-list-right">
                                <span class="text--success conversion-amount">--</span>
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
                                <span class="text--danger fees">--</span>
                            </div>
                        </div>
                        <div class="preview-list-item">
                            <div class="preview-list-left">
                                <div class="preview-list-user-wrapper">
                                    <div class="preview-list-user-icon">
                                        <i class="lab la-get-pocket"></i>
                                    </div>
                                    <div class="preview-list-user-content">
                                        <span>{{ __('Will Get') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="preview-list-right">
                                <span class="text--warning will-get">--</span>
                            </div>
                        </div>
                        <div class="preview-list-item">
                            <div class="preview-list-left">
                                <div class="preview-list-user-wrapper">
                                    <div class="preview-list-user-icon">
                                        <i class="las la-money-check-alt"></i>
                                    </div>
                                    <div class="preview-list-user-content">
                                        <span class="last">{{ __('Total Payable Amount') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="preview-list-right">
                                <span class="text--info last pay-in-total">--</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="dashboard-list-area mt-20">
        <div class="dashboard-header-wrapper">
            <h5 class="title">{{ __('Money Out') }}</h5>
            <div class="dashboard-btn-wrapper">
                <div class="dashboard-btn">
                    <a href="{{ setRoute('user.transactions.index', 'withdraw') }}"
                        class="btn--base">{{ __('View More') }}</a>
                </div>
            </div>
        </div>
        <div class="dashboard-list-wrapper">
            @include('user.components.transaction-log', compact('transactions'))
        </div>
    </div>
@endsection


@push('script')
    <script>
        var default_currency = "{{ get_default_currency_code() }}";
        var userBalanceRoute = "{{ setRoute('user.wallets.balance') }}";

        var fixedCharge = "{{ $charges->fixed_charge ?? 0 }}";
        var percentCharge = "{{ $charges->percent_charge ?? 0 }}";
        var minLimit = "{{ $charges->min_limit ?? 0 }}";
        var maxLimit = "{{ $charges->max_limit ?? 0 }}";


        $("select[name=gateway]").change(function() {
            var selectedGateway = $(this).find(":selected").attr("data-item");
            run(JSON.parse(selectedGateway), JSON.parse(adSelectActiveItem("input[name=sender_currency]")));
        });

        $("input[name=amount]").keyup(function() {
            var selectedGateway = $("select[name=gateway]").find(":selected").attr("data-item");
            run(JSON.parse(selectedGateway), JSON.parse(adSelectActiveItem("input[name=sender_currency]")));
        });

        $(document).on("click", ".custom-option", function() {
            var selectedGateway = $("select[name=gateway]").find(":selected").attr("data-item");
            run(JSON.parse(selectedGateway), JSON.parse(adSelectActiveItem("input[name=sender_currency]")));
        });

        function run(gateway, senderCurrency) {
            // console.log(gateway, senderCurrency);


            if (senderCurrency == false) {
                return false;
            }

            if (gateway == undefined || gateway == null || gateway == "") {
                return false;
            }


            function acceptVar() {
                var gatewayCurrency = gateway.currency_code ?? "";
                var gatewayCurrencyRate = gateway.rate ?? 1;
                var gatewayFixedCharge = gateway.fixed_charge ?? 0;
                var gatewayPercentCharge = gateway.percent_charge ?? 0;
                var gatewayMinLimit = gateway.min_limit ?? 0;
                var gatewayMaxLimit = gateway.max_limit ?? 0;

                var senderCountry = senderCurrency.code ?? "";
                var senderCurrencyRate = senderCurrency.rate ?? 0;

                return {
                    gCurrency: gatewayCurrency,
                    gCurrencyRate: gatewayCurrencyRate,
                    gPercentCharge: gatewayPercentCharge,
                    gFixedCharge: gatewayFixedCharge,
                    gMinLimit: gatewayMinLimit,
                    gMaxLimit: gatewayMaxLimit,

                    sCurrency: senderCountry,
                    sCurrencyRate: senderCurrencyRate,
                };
            }
            // console.log(acceptVar());

            function getExchangeRate() {
                var senderCurrency = acceptVar().sCurrency;
                var senderCurrencyRate = acceptVar().sCurrencyRate;
                var gatewayCurrency = acceptVar().gCurrency;
                var gatewayCurrencyRate = acceptVar().gCurrencyRate;

                var rate = parseFloat(senderCurrencyRate) / parseFloat(gatewayCurrencyRate);

                $('.rate-show').html("1 " + senderCurrency + " = " + parseFloat(gatewayCurrencyRate).toFixed(4) + " " +
                    gatewayCurrency);
                return rate;
            }
            getExchangeRate();

            function getLimit() {
                var gatewayCurrency = acceptVar().gCurrency;
                var gatewayCurrencyRate = acceptVar().gCurrencyRate;

                var senderCurrency = acceptVar().sCurrency;
                var senderCurrencyRate = acceptVar().sCurrencyRate;

                var exchangeRate = getExchangeRate();

                var minLimit = acceptVar().gMinLimit;
                var maxLimit = acceptVar().gMaxLimit;

                if ($.isNumeric(minLimit) && $.isNumeric(maxLimit)) {
                    var min_limit_calc = parseFloat(minLimit * exchangeRate).toFixed(2);
                    var max_limit_clac = parseFloat(maxLimit * exchangeRate).toFixed(2);
                    $('.limit-show').html("Limit " + min_limit_calc + " " + senderCurrency + " - " + max_limit_clac + " " +
                        senderCurrency);
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
            getLimit();
            // console.log(getLimit());

            function feesCalculation() {
                var senderCurrency = acceptVar().sCurrency;
                var senderCurrencyRate = acceptVar().sCurrencyRate;

                var senderAmount = $("input[name=amount]").val();
                senderAmount == "" ? senderAmount = 0 : senderAmount = senderAmount;

                var fixedCharge = acceptVar().gFixedCharge;
                var percentCharge = acceptVar().gPercentCharge;

                var exchangeRate = getExchangeRate();

                if ($.isNumeric(percentCharge) && $.isNumeric(fixedCharge) && $.isNumeric(senderAmount)) {
                    // Process Calculation
                    var fixedChargeCalc = parseFloat(exchangeRate * fixedCharge);
                    var percentChargeCalc = (parseFloat(senderAmount) / 100) * parseFloat(percentCharge);
                    var totalCharge = parseFloat(fixedChargeCalc) + parseFloat(percentChargeCalc);
                    totalCharge = parseFloat(totalCharge).toFixed(2);
                    // return totalCharge;
                    return {
                        total: totalCharge,
                        fixed: fixedChargeCalc,
                        percent: percentChargeCalc,
                    };
                } else {
                    // return "--";
                    return false;
                }
            }

            function getFees() {
                var senderCurrency = acceptVar().sCurrency;
                var percent = acceptVar().gPercentCharge;

                var gatewayCurrency = acceptVar().gCurrency;

                var gFixcharge = acceptVar().gFixedCharge

                // console.log(percent, gFixcharge)

                var charges = feesCalculation();
                if (charges == false) {
                    return false;
                }

                var amount = $("input[name=amount]").val();
                var conversion_amount = amount * acceptVar().gCurrencyRate;

                var g_percent = ((parseFloat(conversion_amount)) * percent) / 100;

                var total_charge = parseFloat(gFixcharge) + g_percent;

                $('.conversion-amount').html(conversion_amount.toFixed(2) + " " + gatewayCurrency);
                $('.fees-show').html("Charge: " + parseFloat(gFixcharge).toFixed(2) + " " + gatewayCurrency + " + " +
                    parseFloat(percent).toFixed(0) + "%");

                $('.fees').text(parseFloat(total_charge).toFixed(2) + " " + gatewayCurrency);
            }
            getFees();

            function getUserBalance() {
                var selectedCurrency = acceptVar().sCurrency;
                var CSRF = $("meta[name=csrf-token]").attr("content");
                var data = {
                    _token: CSRF,
                    target: selectedCurrency,
                };
                // Make AJAX request for getting user balance
                $.post(userBalanceRoute, data, function() {
                    // success
                }).done(function(response) {
                    var balance = response.data;
                    // alert(balance)
                    balance = parseFloat(balance).toFixed(2);
                    $(".balance-show").html("Available Balance: " + balance + " " + selectedCurrency);

                }).fail(function(response) {
                    var response = JSON.parse(response.responseText);
                    throwMessage(response.type, response.message.error);
                });
            }

            getUserBalance();
            // console.log(getUserBalance);


            function getPreview() {
                var senderAmount = $("input[name=amount]").val();
                var senderCurrency = acceptVar().sCurrency;
                senderAmount == "" ? senderAmount = 0 : senderAmount = senderAmount;

                // Sending Amount
                $('.request-amount').text(senderAmount + " " + senderCurrency);

                var gatewayCurrency = acceptVar().gCurrency;
                var exchangeRate = getExchangeRate();

                var amount = $("input[name=amount]").val();
                var conversion_amount = amount * acceptVar().gCurrencyRate;


                var percent = acceptVar().gPercentCharge;
                var gFixcharge = acceptVar().gFixedCharge;
                var g_percent = ((parseFloat(conversion_amount)) * percent) / 100;
                var total_charge = parseFloat(gFixcharge) + g_percent;

                // var willGet = parseFloat(senderAmount) / parseFloat(exchangeRate);
                willGet = parseFloat(conversion_amount) - parseFloat(total_charge);

                if (willGet < 0) {
                    willGet = 0;
                }

                $('.will-get').text(willGet.toFixed(2) + " " + gatewayCurrency);

                // Fees
                var charges = feesCalculation();
                // console.log(total_charge + "--");
                // $('.fees').text(charges.total + " " + senderCurrency);

                // Pay In Total
                var pay_in_total = parseFloat(charges.total) + parseFloat(senderAmount);
                $('.pay-in-total').text(parseFloat(senderAmount).toFixed(2) + " " + senderCurrency);

            }
            getPreview();
            // console.log(getPreview())

            function getAmount() {
                var senderAmount = $("input[name=amount]").val();
                var senderCurrency = acceptVar().sCurrency;
                // console.log(senderCurrency)
                // alert(senderCurrency);
                senderAmount == "" ? senderAmount = 0 : senderAmount = senderAmount;

                // Sending Amount
                $('.user-amount').html("Your Money Out Amount: " + senderAmount + " " + senderCurrency);
            }
            getAmount();
        }

        $(".ad-select .custom-select-search").keyup(function() {
            var searchText = $(this).val().toLowerCase();
            var itemList = $(this).parents(".ad-select").find(".custom-option");
            $.each(itemList, function(index, item) {
                var text = $(item).find(".custom-currency").text().toLowerCase();
                var country = $(item).find(".custom-country").text().toLowerCase();

                var match = text.match(searchText);
                var countryMatch = country.match(searchText);

                if (match == null && countryMatch == null) {
                    $(item).addClass("d-none");
                } else {
                    $(item).removeClass("d-none");
                }
            });
        });

        function setAdSelectInputValue(data) {
            var data = JSON.parse(data);
            return data.code;
        }

        function adSelectActiveItem(input) {
            // var adSelect = $(input).parents(".ad-select");
            // var selectedItem = adSelect.find(".custom-option.active");
            var selectedItem = $("select[name=sender_currency]").find(":selected").attr("data-item");
            if (selectedItem != undefined) {
                return selectedItem;
            }
            return false;
        }
    </script>
@endpush
