@extends('admin.layouts.master')

@push('css')
    <style>
        .fileholder {
            min-height: 194px !important;
        }

        .fileholder-files-view-wrp.accept-single-file .fileholder-single-file-view,
        .fileholder-files-view-wrp.fileholder-perview-single .fileholder-single-file-view {
            height: 150px !important;
        }

        .select2-results__option.select2-results__option--selectable.select2-results__option--selected {
            display: none;
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
        'active' => __('Top Up Game'),
    ])
@endsection

@section('content')
    <div class="custom-card mb-10">
        <div class="card-header">
            <h6 class="title">{{ __('Top Up Create') }}</h6>
        </div>
        <div class="card-body">
            <form class="modal-form" method="POST" action="{{ route('admin.top.up.game.store') }}"
                enctype="multipart/form-data">
                @csrf
                <div class="row mb-10-none">
                    <div class="col-xl-6 col-lg-6 form-group">
                        @include('admin.components.form.input-file', [
                            'label' => __('Profile Image') . '(178X178 px)*',
                            'class' => 'file-holder',
                            'name' => 'profile_image',
                            'old_files_path' => files_asset_path('top-up-game'),
                            'old_files' => old('profile_image'),
                        ])
                    </div>
                    <div class="col-xl-6 col-lg-6 form-group">
                        @include('admin.components.form.input-file', [
                            'label' => __('Cover Image') . '(640X241 px)*',
                            'class' => 'file-holder',
                            'name' => 'cover_image',
                            'old_files_path' => files_asset_path('top-up-game'),
                            'old_files' => old('cover_image'),
                        ])
                    </div>

                    <div class="col-xl-12 col-lg-12 form-group">
                        @include('admin.components.form.input', [
                            'label' => __('Title') . '*',
                            'name' => 'title',
                            'placeholder' => __('Enter a title'),
                            'type' => 'text',
                            'value' => old('title'),
                        ])
                    </div>

                    <div class="col-xl-12 col-lg-12 form-group">
                        @include('admin.components.form.textarea', [
                            'label' => __('Description') . '*',
                            'name' => 'description',
                            'placeholder' => __('Enter a Description'),
                            'value' => old('description'),
                        ])
                    </div>
                    <div class="col-xl-12 col-lg-12 form-group">
                        <div class="custom-inner-card input-field-generator"
                            data-source="top_up_game_player_id_credentials_field">
                            <div class="card-inner-header">
                                <h6 class="title">{{ __('Player ID') }}</h6>
                                <button type="button" class="btn--base add-row-btn"><i class="fas fa-plus"></i>
                                    {{ __('Add') }}</button>
                            </div>
                            <div class="card-inner-body">
                                <div class="results">
                                    <div class="row align-items-end">
                                        <div class="col-xl-6 col-lg-6 form-group">
                                            @include('admin.components.form.input', [
                                                'label' => 'Label*',
                                                'name' => 'label[]',
                                            ])
                                        </div>
                                        <div class="col-xl-6 col-lg-6 form-group">
                                            @include('admin.components.form.input', [
                                                'label' => 'Name*',
                                                'name' => 'name[]',
                                            ])
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-12 col-lg-12 form-group">
                        <div class="custom-inner-card input-field-generator"
                            data-source="top_up_game_recharge_credentials_field">
                            <div class="card-inner-header">
                                <h6 class="title">{{ __('Recharge') }}</h6>
                                <button type="button" class="btn--base add-row-btn-recharge"><i class="fas fa-plus"></i>
                                    {{ __('Add') }}</button>
                            </div>
                            <div class="card-inner-body">
                                <div class="results">
                                    <div class="row align-items-end">
                                        <div class="col-xl-4 col-lg-4 form-group">
                                            <label for="coin_type">Type*</label>
                                            <select class="form-select" id="coin_type" class="coin_type" name="type[]">
                                                <option value="coin">COIN</option>
                                                <option value="gem">GEM</option>
                                                <option value="diamond">DIAMOND</option>
                                                <option value="credit">CREDIT</option>
                                                <option value="code">CODE</option>
                                                <option value="point">POINT</option>
                                            </select>
                                        </div>
                                        <div class="col-xl-4 col-lg-4 form-group">
                                            <label for="">{{ __('Recharge Amount') }}*</label>
                                            <div class="d-flex">
                                                @include('admin.components.form.input', [
                                                    'name' => 'credit_amount[]',
                                                    'type' => 'number',
                                                ])
                                                <span class="input-group-text coin_type_show">-</span>

                                            </div>
                                        </div>

                                        <div class="col-xl-4 col-lg-4 form-group">
                                            <label for="">{{ __('Price') }}*</label>
                                            <div class="d-flex">
                                                @include('admin.components.form.input', [
                                                    'name' => 'currency_amount[]',
                                                    'type' => 'number',
                                                ])
                                                <span class="input-group-text recharge_currency">-</span>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h6>{{ __('Download App') }}</h6>
                    <div class="col-xl-6 col-lg-6 form-group">
                        @include('admin.components.form.input', [
                            'name' => 'google_store',
                            'placeholder' => 'Enter a Google Store Game Link',
                            'type' => 'text',
                            'value' => old('google_store'),
                        ])
                    </div>
                    <div class="col-xl-6 col-lg-6 form-group">
                        @include('admin.components.form.input', [
                            'name' => 'apple_store',
                            'placeholder' => __('Enter a Apple Store Game Link'),
                            'type' => 'text',
                            'value' => old('apple_store'),
                        ])
                    </div>
                    <button type="submit" class="btn btn--base my-3">{{ __('Submit') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('script')
    <script>
        // Switcher
        switcherAjax("{{ route('admin.admins.admin.status.update') }}");

        itemSearch($("input[name=admin_search]"), $(".admin-search-table"), "{{ setRoute('admin.admins.search') }}");

        let default_currency = "{{ get_default_currency_code() }}";
        $('.recharge_currency').text(default_currency);

        //recharge
        let coin_type = $('#coin_type').val();
        let coin_type_show = $('.coin_type_show').text(coin_type.toUpperCase());
        $('#coin_type').on('change', function() {
            let coin_type = $(this).val();
            let coin_type_show = $('.coin_type_show').text(coin_type.toUpperCase());
        });


        $('.input-field-generator').on('click', '.add-row-btn-recharge', function() {
            var source = $(this).parents('.input-field-generator').attr("data-source");
            $(this).closest('.input-field-generator').find('.results').children().removeClass("last-add");
            $(this).closest('.input-field-generator').find('.results').append(storedHtmlMarkup[source]);
            var lastAddedElement = $(this).closest('.input-field-generator').find('.results').children().first();
            lastAddedElement.addClass("last-add");

            $('.recharge_currency').text(default_currency);

            let coin_type = $('#coin_type').val();
            let coin_type_show = $('.coin_type_show').text(coin_type.toUpperCase());

            var inputTypeField = lastAddedElement.find(".field-input-type");
            if (inputTypeField.length > 0) {
                inputFieldValidationRuleFieldsShow(inputTypeField);
            }
        });
    </script>
@endpush
