@extends('admin.layouts.master')

@push('css')
@endpush

@section('page-title')
    @include('admin.components.page-title', ['title' => __($page_title)])
@endsection

@section('breadcrumb')
    @include('admin.components.breadcrumb', [
        'breadcrumbs' => [
            [
                'name' => __('Dashboard'),
                'url' => setRoute('admin.dashboard'),
            ],
        ],
        'active' => __('Dashboard'),
    ])
@endsection

@section('content')
    <div class="dashboard-area">
        <div class="dashboard-item-area">
            <div class="row">
                <div class="col-xxxl-4 col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-15">
                    <div class="dashbord-item">
                        <div class="dashboard-content">
                            <div class="left">
                                <h6 class="title">{{ __('Total Review Top Up') }}</h6>
                                <div class="user-info">
                                    <h2 class="user-count">{{ get_default_currency_code() }} {{ get_amount($total_review_topup_amount) }}</h2>
                                </div>
                                <div class="user-badge">
                                    <span class="badge badge--warning">{{ __('Review Payment:') }}
                                        {{ $total_review_topup_count }}</span>
                                </div>
                            </div>
                            <div class="right">
                                <div class="chart" id="chart7" data-percent="{{ get_amount($percent_review_topup) }}">
                                    <span>{{ get_amount($percent_review_topup) }}%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxxl-4 col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-15">
                    <div class="dashbord-item">
                        <div class="dashboard-content">
                            <div class="left">
                                <h6 class="title">{{ __('Total Pending Top Up') }}</h6>
                                <div class="user-info">
                                    <h2 class="user-count">{{ get_default_currency_code() }} {{ get_amount($total_pending_topup_amount) }}</h2>
                                </div>
                                <div class="user-badge">
                                    <span class="badge badge--warning">{{ __('Pending') }}:
                                        {{ $total_pending_topup_count }}</span>
                                </div>
                            </div>
                            <div class="right">
                                <div class="chart" id="chart77" data-percent="{{ get_amount($percent_pending_topup) }}">
                                    <span>{{ get_amount($percent_pending_topup) }}%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxxl-4 col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-15">
                    <div class="dashbord-item">
                        <div class="dashboard-content">
                            <div class="left">
                                <h6 class="title">{{ __('Total Settled Top Up') }}</h6>
                                <div class="user-info">
                                    <h2 class="user-count">{{ get_default_currency_code() }} {{ get_amount($total_settled_topup_amount) }}</h2>
                                </div>
                                <div class="user-badge">
                                    <span class="badge badge--warning">{{ __('Settled') }}:
                                        {{ $total_settled_topup_count }}</span>
                                </div>
                            </div>
                            <div class="right">
                                <div class="chart" id="chart78" data-percent="{{ get_amount($percent_settled_topup) }}">
                                    <span>{{ get_amount($percent_settled_topup) }}%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxxl-4 col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-15">
                    <div class="dashbord-item">
                        <div class="dashboard-content">
                            <div class="left">
                                <h6 class="title">{{ __('Total Complete Top Up') }}</h6>
                                <div class="user-info">
                                    <h2 class="user-count">{{ get_default_currency_code() }} {{ get_amount($total_complete_topup_amount) }}</h2>
                                </div>
                                <div class="user-badge">
                                    <span class="badge badge--warning">{{ __('Complete:') }}
                                        {{ $total_complete_topup_count }}</span>
                                </div>
                            </div>
                            <div class="right">
                                <div class="chart" id="chart79"
                                    data-percent="{{ get_amount($percent_complete_topup) }}">
                                    <span>{{ get_amount($percent_complete_topup) }}%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xxxl-4 col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-15">
                    <div class="dashbord-item">
                        <div class="dashboard-content">
                            <div class="left">
                                <h6 class="title">{{ __('Add Money Balance') }}</h6>
                                <div class="user-info">
                                    <h2 class="user-count">{{ get_default_currency_code() }} {{ get_amount($total_addmoney) }}</h2>
                                </div>
                                <div class="user-badge">
                                    <span class="badge badge--info">{{ __('Success:') }}
                                        ${{ get_amount($success_addmoney) }}</span>
                                    <span class="badge badge--warning">{{ __('Pending') }}:
                                        ${{ get_amount($panding_addmoney) }}</span>
                                </div>
                            </div>
                            <div class="right">
                                <div class="chart" id="chart80" data-percent="{{ get_amount($percent_addmoney) }}">
                                    <span>{{ get_amount($percent_addmoney) }}%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xxxl-4 col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-15">
                    <div class="dashbord-item">
                        <div class="dashboard-content">
                            <div class="left">
                                <h6 class="title">{{ __('User Active Tickets') }}</h6>
                                <div class="user-info">
                                    <h2 class="user-count">{{ $total_tickets }}</h2>
                                </div>
                                <div class="user-badge">
                                    <span class="badge badge--warning">{{ __('Pending') }} {{ $pending_tickets }}</span>
                                    <span class="badge badge--success">{{ __('Solved') }} {{ $active_tickets }}</span>
                                </div>
                            </div>
                            <div class="right">
                                <div class="chart" id="chart10" data-percent="{{ get_amount($ticket_perchant) }}">
                                    <span>{{ get_amount($ticket_perchant) }}%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxxl-4 col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-15">
                    <div class="dashbord-item">
                        <div class="dashboard-content">
                            <div class="left">
                                <h6 class="title">{{ __('Total Users') }}</h6>
                                <div class="user-info">
                                    <h2 class="user-count">{{ $total_users }}</h2>
                                </div>
                                <div class="user-badge">
                                    <span class="badge badge--info">{{ __('Active') }} {{ $active_users }}</span>
                                    <span class="badge badge--warning">{{ __('Unverified') }}
                                        {{ $unverified_users }}</span>
                                </div>
                            </div>
                            <div class="right">
                                <div class="chart" id="chart11" data-percent="{{ get_amount($user_perchant) }}">
                                    <span>{{ get_amount($user_perchant) }}%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="chart-area mt-15">
        <div class="row mb-15-none">
            <div class="col-xxl-6 col-xl-6 col-lg-6 mb-15">
                <div class="chart-wrapper">
                    <div class="chart-area-header">
                        <h5 class="title">{{ __('Monthly Add Money Chart') }}</h5>
                    </div>
                    <div class="chart-container">

                        <div id="chart1" data-chart_one_data="{{ json_encode($data['chart_one_data']) }}"
                            data-month_day="{{ json_encode($data['month_day']) }}" class="sales-chart"></div>

                    </div>
                </div>
            </div>

            <div class="col-xxl-6 col-xl-6 col-lg-6 mb-15">
                <div class="chart-wrapper">
                    <div class="chart-area-header">
                        <h5 class="title">{{ __('Monthly Top Up Chart') }}</h5>
                    </div>
                    <div class="chart-container">
                        <div id="chart21" data-chart_four_data="{{ json_encode($data['chart_four_data']) }}"
                            data-month_day="{{ json_encode($data['month_day']) }}" class="sales-chart"></div>
                    </div>
                </div>
            </div>

            <div class="col-xxxl-6 col-xxl-3 col-xl-6 col-lg-6 mb-15">
                <div class="chart-wrapper">
                    <div class="chart-area-header">
                        <h5 class="title">{{ __('User Analytics') }}</h5>
                    </div>
                    <div class="chart-container">

                        <div id="chart4" data-chart_four="{{ json_encode($chart_four) }}" class="balance-chart">
                        </div>

                    </div>
                    <div class="chart-area-footer">
                        <div class="chart-btn">
                            <a href="{{ setRoute('admin.users.index') }}"
                                class="btn--base w-100">{{ __('View Users') }}</a>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="table-area mt-15">
        <div class="table-wrapper">
            <div class="table-header">
                <h5 class="title">{{ __('Latest Add Money') }}</h5>
            </div>
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>{{ __('Full Name') }}</th>
                            <th>{{ __('Email') }}</th>
                            <th>{{ __('Username') }}</th>
                            <th>{{ __('Phone') }}</th>
                            <th>{{ __('Amount') }}</th>
                            <th>{{ __('Method') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Time') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($latest_add_moneys as $latest_add_money)
                            <tr>
                                <td><span>{{ @$latest_add_money->user->firstname }}
                                        {{ @$latest_add_money->user->lastname }}</span></td>
                                <td>{{ @$latest_add_money->user->email }}</td>
                                <td>{{ @$latest_add_money->user->username }}</td>
                                <td>{{ @$latest_add_money->user->mobile }}</td>
                                <td>{{ @$latest_add_money->request_amount }}</td>
                                <td><span class="text--info">{{ @$latest_add_money->currency->name }}</span></td>
                                <td><span
                                        class="badge {{ $latest_add_money->stringStatus->class }}">{{ __($latest_add_money->stringStatus->value) }}</span>
                                </td>
                                <td>{{ @$latest_add_money->created_at }}</td>

                            </tr>
                        @endforeach


                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        //chart1 start
        var chart1 = $('#chart1');
        var chart_one_data = chart1.data('chart_one_data');
        var month_day = chart1.data('month_day');
        var options = {
            series: [{
                name: "{{ __('Pending') }}",
                color: "#5A5278",
                data: chart_one_data.pending_data
            }, {
                name: "{{ __('Completed') }}",
                color: "#6F6593",
                data: chart_one_data.success_data
            }, {
                name: "{{ __('Canceled') }}",
                color: "#8075AA",
                data: chart_one_data.hold_data
            }, {
                name: "{{ __('Hole') }}",
                color: "#A192D9",
                data: chart_one_data.hold_data
            }],
            chart: {
                type: 'bar',
                height: 350,
                stacked: true,
                toolbar: {
                    show: false
                },
                zoom: {
                    enabled: true
                }
            },
            responsive: [{
                breakpoint: 480,
                options: {
                    legend: {
                        position: 'bottom',
                        offsetX: -10,
                        offsetY: 0
                    }
                }
            }],
            plotOptions: {
                bar: {
                    horizontal: false,
                    borderRadius: 10
                },
            },
            xaxis: {
                type: 'datetime',
                categories: month_day,

            },
            legend: {
                position: 'bottom',
                offsetX: 40
            },
            fill: {
                opacity: 1
            }
        };
        var chart = new ApexCharts(document.querySelector("#chart1"), options);
        chart.render();
        //chart1 end

        //chart21 start
        var chart21 = $('#chart21');
        var chart_four_data = chart21.data('chart_four_data');
        var month_day = chart21.data('month_day');
        var options = {
            series: [{
                name: "{{ __('Review') }}",
                color: "#5A5278",
                data: chart_four_data.review_data
            }, {
                name: "{{ __('Pending') }}",
                color: "#6F6593",
                data: chart_four_data.pending_data
            }, {
                name: "{{ __('Completed') }}",
                color: "#8075AA",
                data: chart_four_data.completed_data
            }, {
                name: "{{ __('Settled') }}",
                color: "#A192D9",
                data: chart_four_data.settled_data
            }],
            chart: {
                type: 'bar',
                height: 350,
                stacked: true,
                toolbar: {
                    show: false
                },
                zoom: {
                    enabled: true
                }
            },
            responsive: [{
                breakpoint: 480,
                options: {
                    legend: {
                        position: 'bottom',
                        offsetX: -10,
                        offsetY: 0
                    }
                }
            }],
            plotOptions: {
                bar: {
                    horizontal: false,
                    borderRadius: 10
                },
            },
            xaxis: {
                type: 'datetime',
                categories: month_day,

            },
            legend: {
                position: 'bottom',
                offsetX: 40
            },
            fill: {
                opacity: 1
            }
        };
        var chart = new ApexCharts(document.querySelector("#chart21"), options);
        chart.render();
        //chart21 end


        var chart4 = $('#chart4');
        var chart_four = chart4.data('chart_four');
        var options = {
            series: chart_four,
            chart: {
                width: 350,
                type: 'pie'
            },
            colors: ['#5A5278', '#6F6593', '#8075AA', '#A192D9'],
            labels: ["{{ __('Active') }}", "{{ __('Unverified') }}", "{{ __('Banned') }}", "{{ __('All') }}"],
            responsive: [{
                breakpoint: 1480,
                options: {
                    chart: {
                        width: 280
                    },
                    legend: {
                        position: 'bottom'
                    }
                },
                breakpoint: 1199,
                options: {
                    chart: {
                        width: 380
                    },
                    legend: {
                        position: 'bottom'
                    }
                },
                breakpoint: 575,
                options: {
                    chart: {
                        width: 280
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }],
            legend: {
                position: 'bottom'
            },
        };

        var chart = new ApexCharts(document.querySelector("#chart4"), options);
        chart.render();
    </script>
@endpush
