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
        'active' => __('Top Up Logs'),
    ])
@endsection

@section('content')
    <div class="table-area">
        <div class="table-wrapper">
            <div class="table-header">
                <h5 class="title">{{ __($page_title) }}</h5>
            </div>
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>{{ __('TRX') }}</th>
                            <th>{{ __('Email') }}</th>
                            <th>{{ __('Top Up Coin') }}</th>
                            <th>{{ __('Price') }}</th>
                            <th>{{ __('Method') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Time') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions  as $key => $item)
                            <tr>
                                <td>{{ @$item->trx_id }}</td>
                                <td>{{ @$item->user->email ?? 'N/A' }}</td>
                                <td>{{ @$item->details->tempData->recharge_coin[0] }}
                                    {{ strtoupper(@$item->details->tempData->coin_type) }}</td>
                                <td>{{ @$item->details->tempData->recharge_coin[1] }}
                                    {{ strtoupper(@$item->details->tempData->currency) }}</td>
                                </td>
                                <td><span class="text--info">{{ @$item->currency->name ?? 'N/A' }}</span></td>
                                <td>
                                    <span
                                        class="{{ $item->stringStatus->class }}">{{ __($item->stringStatus->value) }}</span>
                                </td>
                                <td>{{ dateFormat('d M y h:i:s A', $item->created_at) }}</td>
                                <td>
                                    @include('admin.components.link.info-default', [
                                        'href' => setRoute('admin.top.up.log.details', $item->id),
                                        'permission' => 'admin.top.up.log.details',
                                    ])
                                </td>
                            </tr>
                        @empty
                            @include('admin.components.alerts.empty', ['colspan' => 8])
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ get_paginate($transactions) }}
        </div>
    </div>
@endsection

@push('script')
@endpush
