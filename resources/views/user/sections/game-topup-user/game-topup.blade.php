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
                <h3 class="title">{{ __('Game Topup') }}</h3>
            </div>
            <div class="dash-topup-wrapper">
                <div class="topup-area">
                    <div class="row justify-content-center mb-30-none">
                        @forelse ($top_up_games as $top_up_game)
                            <div class="col-xl-2 col-lg-3 col-md-3 col-sm-4 col-xs-6 mb-30">
                                <a href="{{ route('top.up.details', $top_up_game->slug) }}">
                                    <div class="topup-item">
                                        <div class="topup-thumb">
                                            <img src="{{ get_image(@$top_up_game->profile_image, 'top-up-game') }}"
                                                alt="topup">
                                        </div>
                                        <div class="topup-content">
                                            <h5 class="title">{{ @$top_up_game->title }}</h5>
                                            <p>{{ $top_up_game->created_at->format('M d, Y') }}</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @empty
                            <div class="col-xl-2 col-lg-3 col-md-3 col-sm-4 col-xs-6 mb-30">
                                <h6>{{__('No Data Found!')}}</h6>
                            </div>
                        @endforelse

                    </div>
                </div>
                <div class="topup-btn-area pt-60 d-flex justify-content-center">
                    {{ get_paginate($top_up_games) }}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
@endpush
