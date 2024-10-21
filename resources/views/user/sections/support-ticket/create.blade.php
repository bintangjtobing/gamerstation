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
        'active' => __('Add New Ticket'),
    ])
@endsection

@section('content')
    <div class="body-wrapper">
        <div class="row mb-20-none">
            <div class="col-xl-12 col-lg-12 mb-20">
                <div class="custom-card mt-10">
                    <div class="dashboard-header-wrapper">
                        <h4 class="title">{{ __('Add New Ticket') }}</h4>
                    </div>
                    <div class="card-body">
                        <form class="card-form" action="{{ route('user.support.ticket.store') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-xl-6 col-lg-6 form-group">
                                    @include('admin.components.form.input', [
                                        'label' => __('Name') . '<span>*</span>',
                                        'name' => 'name',
                                        'placeholder' => 'Enter Name...',
                                        'value' => auth()->user()->username,
                                        'attribute' => 'readonly',
                                    ])
                                </div>
                                <div class="col-xl-6 col-lg-6 form-group">
                                    @include('admin.components.form.input', [
                                        'label' => __('Email') . '<span>*</span>',
                                        'name' => 'email',
                                        'placeholder' => 'Enter Email...',
                                        'value' => auth()->user()->email,
                                        'attribute' => 'readonly',
                                    ])
                                </div>
                                <div class="col-xl-12 col-lg-12 form-group">
                                    @include('admin.components.form.input', [
                                        'label' => __('Subject') . '<span>*</span>',
                                        'name' => 'subject',
                                        'placeholder' => 'Enter Subject...',
                                        'required' => 'required',
                                    ])
                                </div>
                                <div class="col-xl-12 col-lg-12 form-group">
                                    @include('admin.components.form.textarea', [
                                        'label' => __('Message') . " <span class='text--base'>*</span>",
                                        'name' => 'desc',
                                        'placeholder' => 'Write Here…',
                                        'required' => 'required',
                                    ])
                                </div>
                                <div class="col-xl-4 col-lg-4 form-group">
                                    @include('admin.components.form.input-file', [
                                        'label' => 'Image',
                                        'class' => 'file-holder',
                                        'name' => 'attachment[]',
                                        'old_files_path' => files_asset_path('app-images'),
                                        'old_files' => old('old_image'),
                                    ])
                                </div>
                            </div>
                            <div class="col-xl-12 col-lg-12">
                                <button type="submit" class="btn--base w-100">{{ __('Add Support Ticket') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script></script>
@endpush
