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
        'active' => __('Buy Coin Logs'),
    ])
@endsection

@section('content')
    <div class="table-area">
        <div class="table-wrapper">
            <div class="table-header">
                <h5 class="title">{{ $page_title }}</h5>
            </div>
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>{{ __('Login Vender') }}</th>
                            <th>{{ __('Username or Email') }}</th>
                            <th>{{ __('Coin') }}</th>
                            <th>{{ __('Price') }}</th>
                            <th>{{ __('Total Payable') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Time') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($buy_coin_logs  as $key => $item)
                            <tr>
                                <td>{{ $item->login_vender }}</td>
                                <td>{{ $item->username_or_email ?? 'N/A' }}</td>
                                <td>{{ $item->coin }}</td>
                                <td>{{ get_amount($item->price, get_default_currency_code()) }}</td>
                                <td>{{ get_amount($item->total_amount, get_default_currency_code()) }}</td>
                                <td>
                                    <span class="{{ $item->stringStatus->class }}">{{ $item->stringStatus->value }}</span>
                                </td>
                                <td>{{ dateFormat('d M y h:i:s A', $item->created_at) }}</td>
                                <td>
                                    @if ($item->status == 2)
                                        <button type="button" class="btn btn--base bg--success"><i
                                                class="las la-check-circle"></i></button>
                                        <a href="{{ setRoute('admin.buy.coin.logs.details', $item->id) }}"
                                            class="btn btn--base"><i class="las la-expand"></i></a>
                                    @else
                                        @include('admin.components.link.info-default', [
                                            'href' => setRoute('admin.buy.coin.logs.details', $item->id),
                                            'permission' => 'admin.buy.coin.logs.details',
                                        ])
                                    @endif
                                </td>
                            </tr>
                        @empty
                            @include('admin.components.alerts.empty', ['colspan' => 8])
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ get_paginate($buy_coin_logs) }}
        </div>
    </div>
@endsection

@push('script')
@endpush
