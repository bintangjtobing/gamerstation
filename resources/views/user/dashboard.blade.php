@extends('user.layouts.master')

@push('css')
    <style>
        .apexcharts-tooltip {
            color: #000;
        }
    </style>
@endpush

@section('breadcrumb')
    @include('user.components.breadcrumb', [
        'breadcrumbs' => [
            [
                'name' => __('Dashboard'),
                'url' => setRoute('user.dashboard'),
            ],
        ],
        'active' => __('Dashboard'),
    ])
@endsection

@section('content')
    <div class="body-wrapper">
        <div class="dashboard-area mt-10">
            <div class="dashboard-header-wrapper">
                <h3 class="title">{{ __('Overview') }}</h3>
            </div>
            <div class="dashboard-item-area">
                <div class="row mb-20-none">
                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-20">
                        <div class="dashbord-item">
                            <div class="dashboard-content">
                                <span class="sub-title">{{ __('Balance') }}</span>
                                <h3 class="title">{{ authWalletBalance() }} <span
                                        class="text--base">{{ get_default_currency_code() }}</span></h3>
                            </div>
                            <div class="dashboard-icon">
                                <img src="{{ get_image($default_currency->flag, 'currency-flag') }}" alt="flag">
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-20">
                        <div class="dashbord-item">
                            <div class="dashboard-content">
                                <span class="sub-title">{{ __('Total Top Up') }}</span>
                                <h3 class="title">{{ authTopUp() }} <span
                                        class="text--base">{{ get_default_currency_code() }}</span></h3>
                            </div>
                            <div class="dashboard-icon">
                                <i class="las la-coins"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-20">
                        <div class="dashbord-item">
                            <div class="dashboard-content">
                                <span class="sub-title">{{ __('Total Transaction') }}</span>
                                <h3 class="title">{{ authTotalTransaction() }} <span
                                        class="text--base">{{ get_default_currency_code() }}</span></h3>
                            </div>
                            <div class="dashboard-icon">
                                <i class="las la-arrows-alt-h"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="chart-area mt-30">
            <div class="row mb-20-none">
                <div class="col-xxl-6 col-xl-6 col-lg-6 mb-20">
                    <div class="chart-wrapper">
                        <div class="dashboard-header-wrapper">
                            <h4 class="title">{{ __('Add Money Chart') }}</h4>
                        </div>
                        <div class="chart-container">
                            <div id="chart1" data-add_money_chart={{ json_encode($data['add_money_chart']) }}
                                data-month_day="{{ json_encode($data['month_day']) }}" class="chart"></div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-6 col-xl-6 col-lg-6 mb-20">
                    <div class="chart-wrapper">
                        <div class="dashboard-header-wrapper">
                            <h4 class="title">{{ __('Top Up Games Chart') }}</h4>
                        </div>
                        <div class="chart-container">
                            <div id="chart2" data-month_day="{{ json_encode($data['month_day']) }}"
                                data-top_up_chart={{ json_encode($data['top_up_chart']) }} class="chart"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="dashboard-list-area mt-20">
            <div class="dashboard-header-wrapper">
                <h4 class="title">{{ __('Transaction Log') }}</h4>
                <div class="dashboard-btn-wrapper">
                    <div class="dashboard-btn">
                        <a href="{{ route('user.transactions.index') }}" class="btn--base">{{ __('View More') }}</a>
                    </div>
                </div>
            </div>
            <div class="dashboard-list-wrapper">
                @include('user.components.transaction-log', compact('transactions'))
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        //chart 1
        var chart1 = $('#chart1');
        var add_money_chart = chart1.data('add_money_chart');

        var month_day = chart1.data('month_day');
        var options = {
            series: [{
                name: "{{ __('Pending') }}",
                color: "#ff4800",
                data: add_money_chart.add_money_pending
            }, {
                name: "{{ __('Completed') }}",
                color: "#00abb3",
                data: add_money_chart.add_money_completed
            }, {
                name: "{{ __('Canceled') }}",
                color: "#00abb3",
                data: add_money_chart.add_money_canceled
            }],
            chart: {
                foreColor: '#000',
                height: 350,
                type: 'area',
                toolbar: {
                    show: false
                },
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth'
            },
            xaxis: {
                type: 'datetime',
                categories: month_day
            },
            tooltip: {
                x: {
                    format: 'dd/MM/yy HH:mm'
                },
            },
        };
        var chart = new ApexCharts(document.querySelector("#chart1"), options);
        chart.render();

        //chart 2
        var chart2 = $('#chart2');
        var top_up_chart = chart2.data('top_up_chart');
        var month_day = chart2.data('month_day');
        var options = {
            series: [{
                name: "{{ __('Review') }}",
                color: "#ff4800",
                data: top_up_chart.top_up_review
            }, {
                name: "{{ __('Pending') }}",
                color: "#00abb3",
                data: top_up_chart.top_up_pending
            }, {
                name: "{{ __('Completed') }}",
                color: "#00abb3",
                data: top_up_chart.top_up_completed
            }, {
                name: "{{ __('Settled') }}",
                color: "#00abb5",
                data: top_up_chart.top_up_settled
            }],
            chart: {
                foreColor: '#000',
                height: 350,
                type: 'area',
                toolbar: {
                    show: false
                },
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth'
            },
            xaxis: {
                type: 'datetime',
                categories: month_day

            },
            tooltip: {
                x: {
                    format: 'dd/MM/yy HH:mm'
                },
            },
        };
        var chart = new ApexCharts(document.querySelector("#chart2"), options);
        chart.render();
    </script>
@endpush
