@extends('frontend.layouts.master')

@push('css')
    <style>
        .jp-card .jp-card-back,
        .jp-card .jp-card-front {

            background-image: linear-gradient(160deg, #2583C5 0%, #813FD6 100%) !important;
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
        'active' => __('Manual Payment'),
    ])
@endsection

@section('content')
    <section class="payment-confirmation-section pb-120 pt-80">
        <div class="container">
            <div class="row mb-30-none justify-content-center">
                <div class="col-lg-12 mb-30">
                    <div class="dash-payment-item-wrapper">
                        <div class="dash-payment-item active">
                            <div class="dash-payment-body pt-0">
                                <h3 class="title mb-30">{{ __(@$page_title) }}</h3>
                                <div class="description">
                                    {!! @$gateway->desc !!}
                                </div>
                                <form class="card-form" action="{{ route('top.up.manual.payment.confirmed') }}"
                                    method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        @foreach ($gateway->input_fields as $item)
                                            @if ($item->type == 'select')
                                                <div class="col-lg-12 form-group">
                                                    <label for="{{ $item->name }}">{{ $item->label }}</label>
                                                    <select name="{{ $item->name }}" id="{{ $item->name }}"
                                                        class="form--control nice-select">
                                                        <option selected disabled>{{__('Choose One')}}</option>
                                                        @foreach ($item->validation->options as $innerItem)
                                                            <option value="{{ $innerItem }}">{{ $innerItem }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error($item->name)
                                                        <span class="invalid-feedback d-block" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            @elseif ($item->type == 'file')
                                                <div class="col-lg-12 form-group">
                                                    @include('admin.components.form.input-dynamic', [
                                                        'label' => $item->label,
                                                        'name' => $item->name,
                                                        'type' => $item->type,
                                                        // 'class'      => 'file-holder',
                                                        'value' => old($item->name),
                                                        'required' => 'required',
                                                    ])
                                                </div>
                                            @elseif ($item->type == 'text')
                                                <div class="col-lg-12 form-group">
                                                    @include('admin.components.form.input-dynamic', [
                                                        'label' => $item->label,
                                                        'name' => $item->name,
                                                        'type' => $item->type,
                                                        'value' => old($item->name),
                                                        'required' => 'required',
                                                    ])
                                                </div>
                                            @elseif ($item->type == 'textarea')
                                                <div class="col-lg-12 form-group">
                                                    @include('admin.components.form.textarea', [
                                                        'label' => $item->label,
                                                        'name' => $item->name,
                                                        'value' => old($item->name),
                                                    ])
                                                </div>
                                            @endif
                                        @endforeach
                                        <div class="col-xl-12 col-lg-12 text-center">
                                            <button type="submit" class="btn--base w-75 btn-loading">
                                                {{ __('Confirm Payment') }}

                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </section>
@endsection

@push('script')
@endpush
