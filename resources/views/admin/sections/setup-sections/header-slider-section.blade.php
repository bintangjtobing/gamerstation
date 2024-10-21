@php
    $default_lang_code = language_const()::NOT_REMOVABLE;
    $system_default_lang = get_default_language_code();
    $languages_for_js_use = $languages->toJson();
@endphp

@extends('admin.layouts.master')

@push('css')
    <link rel="stylesheet" href="{{ asset('public/backend/css/fontawesome-iconpicker.css') }}">
    <style>
        .fileholder {
            min-height: 374px !important;
        }

        .fileholder-files-view-wrp.accept-single-file .fileholder-single-file-view,
        .fileholder-files-view-wrp.fileholder-perview-single .fileholder-single-file-view {
            height: 330px !important;
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
        'active' => __('Setup Section'),
    ])
@endsection

@section('content')
    <div class="table-area mt-15">
        <div class="table-wrapper">
            <div class="table-header justify-content-end">
                <div class="table-btn-area">
                    <a href="#header-slider-add" class="btn--base modal-btn"><i class="fas fa-plus me-1"></i>
                        {{ __('Add Slider') }}</a>
                </div>
            </div>
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>{{ __('Image') }}</th>
                            <th>{{ __('Title') }}</th>
                            <th>{{ __('Left Button Text') }}</th>
                            <th>{{ __('Left Button Link') }}</th>
                            <th>{{ __('Right Button Text') }}</th>
                            <th>{{ __('Right Button Link') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data->value->items ?? [] as $key => $item)
                            <tr data-item="{{ json_encode($item) }}">
                                <td>
                                    <ul class="user-list">
                                        <li><img src="{{ get_image($item->image ?? '', 'site-section') }}" alt="product">
                                        </li>

                                    </ul>
                                </td>
                                <td>
                                    {{ $item->language->$system_default_lang->item_title }}
                                </td>
                                <td>
                                    {{ $item->language->$system_default_lang->left_button_text }}
                                </td>
                                <td>
                                    {{ $item->language->$system_default_lang->left_button_link }}
                                </td>
                                <td>
                                    {{ $item->language->$system_default_lang->right_button_text }}
                                </td>
                                <td>
                                    {{ $item->language->$system_default_lang->right_button_link }}
                                </td>

                                <td>
                                    <button class="btn btn--base edit-modal-button"><i
                                            class="las la-pencil-alt"></i></button>
                                    <button class="btn btn--base btn--danger delete-modal-button"><i
                                            class="las la-trash-alt"></i></button>
                                </td>
                            </tr>
                        @empty
                            @include('admin.components.alerts.empty', ['colspan' => 8])
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @include('admin.components.modals.site-section.add-header-slider-item')

    {{-- Header Slider Item Edit Modal --}}
    <div id="header-slider-edit" class="mfp-hide large">
        <div class="modal-data">
            <div class="modal-header px-0">
                <h5 class="modal-title">{{ __('Edit Header Slider') }}</h5>
            </div>
            <div class="modal-form-data">
                <form class="modal-form" method="POST"
                    action="{{ setRoute('admin.setup.sections.section.item.update', $slug) }}"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="target" value="{{ old('target') }}">
                    <div class="row mb-10-none mt-3">
                        <div class="language-tab">
                            <nav>
                                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                    <button class="nav-link @if (get_default_language_code() == language_const()::NOT_REMOVABLE) active @endif"
                                        id="edit-modal-english-tab" data-bs-toggle="tab"
                                        data-bs-target="#edit-modal-english" type="button" role="tab"
                                        aria-controls="edit-modal-english" aria-selected="false">English</button>
                                    @foreach ($languages as $item)
                                        <button class="nav-link @if (get_default_language_code() == $item->code) active @endif"
                                            id="edit-modal-{{ $item->name }}-tab" data-bs-toggle="tab"
                                            data-bs-target="#edit-modal-{{ $item->name }}" type="button" role="tab"
                                            aria-controls="edit-modal-{{ $item->name }}"
                                            aria-selected="true">{{ $item->name }}</button>
                                    @endforeach

                                </div>
                            </nav>
                            <div class="tab-content" id="nav-tabContent">

                                <div class="tab-pane @if (get_default_language_code() == language_const()::NOT_REMOVABLE) fade show active @endif"
                                    id="edit-modal-english" role="tabpanel" aria-labelledby="edit-modal-english-tab">
                                    <div class="form-group">
                                        @include('admin.components.form.input', [
                                            'label' => __('Title') . '*',
                                            'name' => $default_lang_code . '_item_title_edit',
                                            'value' => old(
                                                $default_lang_code . '_item_title_edit',
                                                $data->value->language->$default_lang_code->item_title ?? ''),
                                        ])
                                    </div>
                                    <div class="row">
                                        <div class="form-group col">
                                            @include('admin.components.form.input', [
                                                'label' => __('Left Button Text') . '*',
                                                'name' => $default_lang_code . '_left_button_text_edit',
                                                'value' => old(
                                                    $default_lang_code . '_left_button_text_edit',
                                                    $data->value->language->$default_lang_code->left_button_text ??
                                                        ''),
                                            ])
                                        </div>
                                        <div class="form-group col">
                                            @include('admin.components.form.input', [
                                                'label' => __('Left Button Link') . '*',
                                                'name' => $default_lang_code . '_left_button_link_edit',
                                                'value' => old(
                                                    $default_lang_code . '_left_button_link_edit',
                                                    $data->value->language->$default_lang_code->left_button_link ??
                                                        ''),
                                            ])
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="form-group col">
                                            @include('admin.components.form.input', [
                                                'label' => __('Right Button Text') . '*',
                                                'name' => $default_lang_code . '_right_button_text_edit',
                                                'value' => old(
                                                    $default_lang_code . '_right_button_text_edit',
                                                    $data->value->language->$default_lang_code->right_button_text ?? ''),
                                            ])
                                        </div>
                                        <div class="form-group col">
                                            @include('admin.components.form.input', [
                                                'label' => __('Right Button Link') . '*',
                                                'name' => $default_lang_code . '_right_button_link_edit',
                                                'value' => old(
                                                    $default_lang_code . '_right_button_link_edit',
                                                    $data->value->language->$default_lang_code->right_button_link ?? ''),
                                            ])
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        @include('admin.components.form.textarea', [
                                            'label' => __('Description') . '*',
                                            'name' => $default_lang_code . '_item_description_edit',
                                            'value' => old(
                                                $default_lang_code . '_item_description_edit',
                                                $data->value->language->$default_lang_code->description ?? ''),
                                        ])
                                    </div>

                                </div>

                                @foreach ($languages as $item)
                                    @php
                                        $lang_code = $item->code;
                                    @endphp
                                    <div class="tab-pane @if (get_default_language_code() == $item->code) fade show active @endif"
                                        id="edit-modal-{{ $item->name }}" role="tabpanel"
                                        aria-labelledby="edit-modal-{{ $item->name }}-tab">
                                        <div class="form-group">
                                            @include('admin.components.form.input', [
                                                'label' => __('Title') . '*',
                                                'name' => $lang_code . '_item_title_edit',
                                                'value' => old(
                                                    $lang_code . '_item_title_edit',
                                                    $data->value->language->$lang_code->item_title ?? ''),
                                            ])
                                        </div>
                                        <div class="row">
                                            <div class="form-group col">
                                                @include('admin.components.form.input', [
                                                    'label' => __('Left Button Text') . '*',
                                                    'name' => $item->code . '_left_button_text_edit',
                                                    'value' => old(
                                                        $item->code . '_left_button_text_edit',
                                                        $data->value->language->$lang_code->left_button_text ??
                                                            ''),
                                                ])
                                            </div>
                                            <div class="form-group col">
                                                @include('admin.components.form.input', [
                                                    'label' => __('Left Button Link') . '*',
                                                    'name' => $item->code . '_left_button_link_edit',
                                                    'value' => old(
                                                        $item->code . '_left_button_link_edit',
                                                        $data->value->language->$lang_code->left_button_link ??
                                                            ''),
                                                ])
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="form-group col">
                                                @include('admin.components.form.input', [
                                                    'label' => __('Right Button Text') . '*',
                                                    'name' => $item->code . '_right_button_text_edit',
                                                    'value' => old(
                                                        $item->code . '_left_button_text_edit',
                                                        $data->value->language->$lang_code->right_button_text ??
                                                            ''),
                                                ])
                                            </div>
                                            <div class="form-group col">
                                                @include('admin.components.form.input', [
                                                    'label' => __('Right Button Link')."*",
                                                    'name' => $item->code . '_right_button_link_edit',
                                                    'value' => old(
                                                        $item->code . '_left_button_link_edit',
                                                        $data->value->language->$lang_code->right_button_link ??
                                                            ''),
                                                ])
                                            </div>

                                        </div>
                                        <div class="form-group">
                                            @include('admin.components.form.textarea', [
                                                'label' => __('Description')."*",
                                                'name' => $item->code . '_item_description_edit',
                                                'value' => old(
                                                    $item->code . '_item_description_edit',
                                                    $data->value->language->$lang_code->description ?? ''),
                                            ])
                                        </div>

                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-xl-12 col-lg-12 form-group">
                            @include('admin.components.form.input-file', [
                                'label' => __('Image'),
                                'name' => 'image',
                                'class' => 'file-holder',
                                'old_files_path' => files_asset_path('site-section'),
                                'old_files' => old('old_image'),
                            ])
                        </div>

                        <div class="col-xl-12 col-lg-12 form-group d-flex align-items-center justify-content-between mt-4">
                            <button type="button" class="btn btn--danger modal-close">{{ __('Cancel') }}</button>
                            <button type="submit" class="btn btn--base">{{ __('Update') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ asset('public/backend/js/fontawesome-iconpicker.js') }}"></script>
    <script>
        // icon picker
        $('.icp-auto').iconpicker();
    </script>
    <script>
        openModalWhenError("header-slider-add", "#header-slider-add");
        openModalWhenError("header-slider-edit", "#header-slider-edit");

        var default_language = "{{ $default_lang_code }}";
        var system_default_language = "{{ $system_default_lang }}";
        var languages = "{{ $languages_for_js_use }}";
        languages = JSON.parse(languages.replace(/&quot;/g, '"'));

        $(".edit-modal-button").click(function() {
            var oldData = JSON.parse($(this).parents("tr").attr("data-item"));
            var editModal = $("#header-slider-edit");

            console.log(oldData);

            editModal.find("form").first().find("input[name=target]").val(oldData.id);

            editModal.find("input[name=" + default_language + "_item_title_edit]").val(oldData.language[
                default_language].item_title);
            editModal.find("input[name=" + default_language + "_left_button_text_edit]").val(oldData.language[
                default_language].left_button_text);
            editModal.find("input[name=" + default_language + "_left_button_link_edit]").val(oldData.language[
                default_language].left_button_link);
            editModal.find("input[name=" + default_language + "_right_button_text_edit]").val(oldData.language[
                default_language].right_button_text);
            editModal.find("input[name=" + default_language + "_right_button_link_edit]").val(oldData.language[
                default_language].right_button_link);
            editModal.find("textarea[name=" + default_language + "_item_description_edit]").val(oldData.language[
                default_language].item_description);


            $.each(languages, function(index, item) {
                editModal.find("input[name=" + item.code + "_item_title_edit]").val(oldData.language[item
                    .code]?.item_title);
                editModal.find("input[name=" + item.code + "_left_button_text_edit]").val(oldData.language[
                    item.code]?.left_button_text);
                editModal.find("input[name=" + item.code + "_left_button_link_edit]").val(oldData.language[
                    item.code]?.left_button_link);
                editModal.find("input[name=" + item.code + "_right_button_text_edit]").val(oldData.language[
                    item.code]?.right_button_text);
                editModal.find("input[name=" + item.code + "_right_button_link_edit]").val(oldData.language[
                    item.code]?.right_button_link);
                editModal.find("textarea[name=" + item.code + "_item_description_edit]").val(oldData
                    .language[item.code]?.item_description);
            });

            editModal.find("input[name=image]").attr("data-preview-name", oldData.image);
            fileHolderPreviewReInit("#header-slider-edit input[name=image]");
            openModalBySelector("#header-slider-edit");

        });

        $(".delete-modal-button").click(function() {
            var oldData = JSON.parse($(this).parents("tr").attr("data-item"));

            var actionRoute = "{{ setRoute('admin.setup.sections.section.item.delete', $slug) }}";
            var target = oldData.id;
            var message = `Are you sure to <strong>delete</strong> item?`;

            openDeleteModal(actionRoute, target, message);
        });
    </script>
@endpush
