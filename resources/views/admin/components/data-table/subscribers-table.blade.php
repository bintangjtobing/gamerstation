@extends('admin.layouts.master')

@push('css')
    <style>
        .modal {
            z-index: 20 !important;
        }

        .modal-backdrop {
            z-index: 0 !important;
        }
    </style>
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
        'active' => __('Subscribers'),
    ])
@endsection

@section('content')
    <div class="table-area">
        <div class="table-wrapper">
            <div class="table-header">
                <h5 class="title">{{ __($page_title) }}</h5>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#sendMail">{{ __('Send Mail') }}</button>
            </div>
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>{{ __('ID') }}</th>
                            <th>{{ __('Email') }}</th>
                            <th>{{ __('Subscribe Time') }}</th>

                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($subscribers  as $key => $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->email ?? 'N/A' }}</td>
                                <td>{{ dateFormat('d-m-Y', $item->created_at) }}</td>

                            </tr>
                        @empty
                            @include('admin.components.alerts.empty', ['colspan' => 8])
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ get_paginate($subscribers) }}
        </div>
    </div>


    {{-- Model  --}}
    <div class="modal fade" id="sendMail" tabindex="-1" role="dialog" aria-bs-labelledby="sendMail"
        aria-bs-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="card-header">
                    <h6 class="title">{{ __('Email To Subscribers') }}</h6>
                </div>
                <div class="card-body">
                    <form class="card-form" action="{{ route('admin.subscriberssend.mail') }}" method="post">
                        @csrf
                        <div class="row mb-10-none">

                            <div class="col-xl-12 col-lg-12 form-group">
                                @include('admin.components.form.input', [
                                    'label' => __('Subject') . '*',
                                    'name' => 'subject',
                                    // 'value' => old('subject'),
                                    'placeholder' => __('Write Here...'),
                                ])
                            </div>
                            <div class="col-xl-12 col-lg-12 form-group">
                                @include('admin.components.form.input-text-rich', [
                                    'label' => __('Details') . '*',
                                    'name' => 'message',
                                    // 'value' => old('message'),
                                    'placeholder' => __('Write Here...'),
                                ])
                            </div>
                            <div class="col-xl-12 col-lg-12 form-group">
                                @include('admin.components.button.form-btn', [
                                    'class' => 'w-100 btn-loading',
                                    'text' => __('Send Email'),
                                ])
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
@endpush
