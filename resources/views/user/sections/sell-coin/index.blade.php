@extends('user.layouts.master')

@section('breadcrumb')
    @include('user.components.breadcrumb', [
        'breadcrumbs' => [
            [
                'name' => __('Dashboard'),
                'url' => setRoute('user.dashboard'),
            ],
        ],
        'active' => __('Sell Coin'),
    ])
@endsection

@section('content')
    <div class="row mb-20-none">
        <div class="col-xl-7 col-lg-7 mb-20">
            <div class="custom-card mt-10">
                <div class="dashboard-header-wrapper">
                    <h5 class="title">{{ __($page_title) }}</h5>
                </div>
                <div class="card-body">
                    <form class="card-form bounce-safe" action="{{ route('user.sell.coin.submit') }}" method="POST"
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
                                <select class="form--control nice-select sell_coin_gateway" name="gateway">
                                    @foreach ($payment_gateways as $item)
                                        <option value="{{ $item->alias }}"
                                            data-item="{{ json_encode($item->currencies()->select(['name', 'alias', 'rate', 'currency_code', 'percent_charge', 'fixed_charge', 'min_limit', 'max_limit'])->first()) }}">
                                            {{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-xl-12 col-lg-12 form-group">
                                <label>{{ __('Coin Amount') }}<span>*</span></label>
                                <div class="input-group">
                                    <input type="number" class="form--control sell_coin_amount"
                                        placeholder="Enter your coin to sell" name="amount" value="{{ old('amount') }}"
                                        required>

                                    <input type="hidden" name="sender_currency">

                                    <select class="form--control nice-select" name="sender_currency">
                                        @foreach ($receving_method->supported_currencies as $item)
                                            <option class="custom-option" data-item='{{ json_encode($item) }}' selected
                                                value="{{ $item }}">{{ $item }}
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
                            <button type="button" id="selling_coin_btn" class="btn--base w-100" data-bs-toggle="modal"
                                data-bs-target="#sellCoin">{{ __('Sell Coin') }}</button>
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
                                        <span>{{ __('Total convert amount') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="preview-list-right">
                                <span class="text--warning total-convert-amount">--</span>
                            </div>
                        </div>
                        <div class="preview-list-item">
                            <div class="preview-list-left">
                                <div class="preview-list-user-wrapper">
                                    <div class="preview-list-user-icon">
                                        <i class="las la-money-check-alt"></i>
                                    </div>
                                    <div class="preview-list-user-content">
                                        <span class="last">{{ __('Will Get') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="preview-list-right">
                                <span class="text--info last will-get">--</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="sellCoin" tabindex="-1" aria-labelledby="sellCoinModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content sell-modal">
                <div class="modal-body">
                    <div class="sell-coin-modal-wrapper">
                        <h5 class="title">{!! $receving_method->desc !!}</h5>

                        <div class="btn-wrapper">
                            <button type="submit" class="sell-btn proceed">Proceed</button>

                            <a href="{{ route('user.sell.coin.index') }}" class="sell-btn">Cancel</a>
                        </div>

                        <div class="sell-coin-modal-input-wrapper mt-20">
                            <form action="{{ route('user.sell.coin.receiving.method.submit') }}" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="receiving_gateway" value="" class="receiving_gateway">
                                <input type="hidden" name="receiving_amount" value="" class="receiving_amount">
                                @include('user.components.payment-gateway.generate-dy-input', [
                                    'input_fields' => array_reverse($receving_method->input_fields),
                                ])
                                <div class="btn-wrapper">
                                    <button type="submit" id="sell_coin_submit"
                                        class="btn--base w-100 mt-20">Confirm</button>
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
        let receiving_method = '{!! json_encode($receving_method->currencies->first()) !!}';
        receiving_method = JSON.parse(receiving_method);
        let default_currency_rate = "{{ get_default_currency_rate() }}";
        $('#selling_coin_btn').attr('disabled', true);
        console.log(receiving_method);

        let receiver_currency_code = receiving_method.currency_code;
        let r_fixed_charge = receiving_method.fixed_charge;
        let r_percent_charge = parseFloat(receiving_method.percent_charge);
        let r_fixed_percent_charge = (parseFloat(r_fixed_charge) + parseFloat(r_percent_charge));
        //initial fees and charge
        $('.fees-show').text("Charge: " + parseFloat(r_fixed_charge).toFixed(2) + " " + receiver_currency_code + " + " +
            parseFloat(
                r_percent_charge).toFixed(2) + "%");
        //limite
        $('.limit-show').html("Limit " + parseFloat(receiving_method.min_limit).toFixed(2) + " " + " - " +
            parseFloat(receiving_method.max_limit).toFixed(2) + " " + receiver_currency_code);


        //fees

        var initialSelectedGateway = $("select[name=gateway]").find(":selected").attr("data-item");

        initialSelectedGateway = JSON.parse(initialSelectedGateway);
        let base_rate = default_currency_rate;
        let s_rate = base_rate / initialSelectedGateway.rate;
        let r_rate = base_rate / receiving_method.rate;
        let exchange_rate = r_rate / s_rate;
        // exchange rate
        $('.rate-show').text("1 " + receiver_currency_code + " = " + parseFloat(exchange_rate).toFixed(2) +
            " " + initialSelectedGateway.currency_code);


        $("select[name=gateway]").on('change', function() {
            let selectedGateway = $(this).find(":selected").attr("data-item");
            let receiver_amount = $("input[name=amount]").val();


            selectedGateway = JSON.parse(selectedGateway);
            let base_rate = default_currency_rate;
            let s_rate = base_rate / selectedGateway.rate;
            let r_rate = base_rate / receiving_method.rate;
            let exchange_rate = r_rate / s_rate;
            // exchange rate
            $('.rate-show').text("1 " + receiver_currency_code + " = " + parseFloat(exchange_rate).toFixed(2) +
                " " + selectedGateway.currency_code);

            // fees and charge
            let r_fixed_charge = (receiving_method.fixed_charge) ?? 0;
            let r_percent_charge = (parseFloat(receiving_method.percent_charge) * parseFloat(receiver_amount)) /
                100;
            let r_fixed_percent_charge = (parseFloat(r_fixed_charge) + parseFloat(r_percent_charge)) ?? 0;
            $('.fees').text(r_fixed_percent_charge + " " + receiving_method.currency_code);
            if (receiver_amount == '') {
                receiver_amount = 0;
            }
            // amount
            $('.request-amount').text(receiver_amount + ' ' + receiving_method.currency_code);



            if (parseFloat(receiver_amount) < parseFloat(r_fixed_percent_charge) || parseFloat(receiver_amount) ==
                '') {
                $('.total-convert-amount').text('0' + ' ' + receiving_method.currency_code);

                //will get
                $('.will-get').text('0' + " " + selectedGateway.currency_code);
                //fees
                $('.fees').text(parseFloat(r_fixed_charge).toFixed(2) + " " + receiving_method.currency_code);

                return false;
            }
            $('.total-convert-amount').text((parseFloat(receiver_amount) - parseFloat(r_fixed_percent_charge))
                .toFixed(2) +
                ' ' + receiving_method.currency_code);

            //will get
            let total_conver_amount = parseFloat(receiver_amount) - parseFloat(r_fixed_percent_charge)
            $('.will-get').text((parseFloat(exchange_rate) * parseFloat(total_conver_amount)).toFixed(2) +
                " " +
                selectedGateway.currency_code);

        });


        $("input[name=amount]").keyup(function() {
            // sell coin receving method
            receiver_amount = $("input[name=amount]").val();

            let receiver_currency_code = receiving_method.currency_code;
            let r_fixed_charge = (receiving_method.fixed_charge) ?? 0;
            let r_percent_charge = (parseFloat(receiving_method.percent_charge) * parseFloat(receiver_amount)) /
                100;
            let r_fixed_percent_charge = (parseFloat(r_fixed_charge) + parseFloat(r_percent_charge)) ?? 0;
            let max_limit = parseFloat(receiving_method.max_limit).toFixed(2);
            let min_limit = parseFloat(receiving_method.min_limit).toFixed(2);

            // amount
            if (!receiver_amount) {
                receiver_amount = 0;
            }
            $('.request-amount').text(receiver_amount + ' ' + receiver_currency_code);


            // fees and charge
            $('.fees').text(r_fixed_percent_charge + " " +
                receiver_currency_code);

            //total-convert-amount
            $('.total-convert-amount').text((parseFloat(receiver_amount) - parseFloat(r_fixed_percent_charge))
                .toFixed(2) +
                ' ' + receiving_method.currency_code);

            //will get
            var initialSelectedGateway = $("select[name=gateway]").find(":selected").attr("data-item");

            initialSelectedGateway = JSON.parse(initialSelectedGateway);
            let base_rate = default_currency_rate;
            let s_rate = base_rate / initialSelectedGateway.rate;
            let r_rate = base_rate / receiving_method.rate;
            let exchange_rate = r_rate / s_rate;
            var selectedGateway = $("select[name=gateway] :selected").data("item");
            let total_conver_amount = parseFloat(receiver_amount) - parseFloat(r_fixed_percent_charge)
            $('.will-get').text((parseFloat(exchange_rate) * parseFloat(total_conver_amount)).toFixed(2) +
                " " +
                selectedGateway.currency_code);

            if (receiver_amount == ' ') {
                // throwMessage('error', ["Please enter amount"]);
                $('#selling_coin_btn').attr('disabled', true);
                //total-convert-amount
                $('.total-convert-amount').text(0 + ' ' + receiving_method.currency_code);
                //will get
                $('.will-get').text(0 + " " + selectedGateway.currency_code);
                // fees and charge
                $('.fees').text(0 + " " +
                    receiver_currency_code);
                $('#selling_coin_btn').attr('disabled', true);

            } else if (receiver_amount < r_fixed_percent_charge) {
                // throwMessage('error', ["Please increase amount"]);
                $('#selling_coin_btn').attr('disabled', true);
                //total-convert-amount
                $('.total-convert-amount').text(0 + ' ' + receiving_method.currency_code);
                //will get
                $('.will-get').text(0 + " " + selectedGateway.currency_code);

            } else {
                $('#selling_coin_btn').attr('disabled', false)
            }


        });



        $("input[name=amount]").on('focusout', function(e) {

            var min_limit = receiving_method.min_limit;
            var max_limit = receiving_method.max_limit;
            var sender_amount = parseFloat($(".sell_coin_amount").val());

            if (sender_amount < min_limit) {
                throwMessage('error', ["Please follow the mimimum limit"]);
                $('#selling_coin_btn').attr('disabled', true)
            } else if (sender_amount > max_limit) {
                throwMessage('error', ["Please follow the maximum limit"]);
                $('#selling_coin_btn').attr('disabled', true)
            }

        });

        $('#selling_coin_btn').click(function() {
            var gateway = $('.sell_coin_gateway').val();
            var amount = $('.sell_coin_amount').val();

            var r_gateway = $('.receiving_gateway').val(gateway);
            var r_amount = $('.receiving_amount').val(amount);
        });


        // console.log(JSON.parse(receiver_amount));

        var default_currency = "{{ get_default_currency_code() }}";
        var userBalanceRoute = "{{ setRoute('user.wallets.balance') }}";

        var fixedCharge = "{{ $charges->fixed_charge ?? 0 }}";
        var percentCharge = "{{ $charges->percent_charge ?? 0 }}";
        var minLimit = "{{ $charges->min_limit ?? 0 }}";
        var maxLimit = "{{ $charges->max_limit ?? 0 }}";



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

                $('.rate-show').html("1 " + gatewayCurrency + " = " + parseFloat(rate).toFixed(4) + " " +
                    senderCurrency);
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
                    $('.limit-show').html("Limit " + min_limit_calc + " " + senderCurrency + " - " +
                        max_limit_clac + " " +
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

                var charges = feesCalculation();
                if (charges == false) {
                    return false;
                }
                $('.fees-show').html("Charge: " + parseFloat(charges.fixed).toFixed(2) + " " + senderCurrency +
                    " + " +
                    parseFloat(charges.percent).toFixed(2) + "% = " + parseFloat(charges.total).toFixed(2) +
                    " " +
                    senderCurrency);
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

                var willGet = parseFloat(senderAmount) / parseFloat(exchangeRate);
                willGet = parseFloat(willGet).toFixed(2);
                $('.will-get').text(willGet + " " + gatewayCurrency);

                // Fees
                var charges = feesCalculation();
                $('.fees').text(charges.total + " " + senderCurrency);

                // Pay In Total
                var pay_in_total = parseFloat(charges.total) + parseFloat(senderAmount);
                $('.pay-in-total').text(parseFloat(pay_in_total).toFixed(2) + " " + senderCurrency);

            }
            getPreview();


            function getAmount() {
                var senderAmount = $("input[name=amount]").val();
                var senderCurrency = acceptVar().sCurrency;

                senderAmount == "" ? senderAmount = 0 : senderAmount = senderAmount;

                // Sending Amount
                $('.user-amount').html("Your Withdraw Amount: " + senderAmount + " " + senderCurrency);
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
            var adSelect = $(input).parents(".ad-select");
            var selectedItem = adSelect.find(".custom-option.active");
            var selectedItem = $("select[name=sender_currency]").find(":selected").attr("data-item");
            if (selectedItem != undefined) {
                return selectedItem;
            }
            // return false;
        }
    </script>
@endpush
