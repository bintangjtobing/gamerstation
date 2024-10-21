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
        'active' => __('Add Coin'),
    ])
@endsection

@section('content')
    <div class="table-area">
        <div class="table-wrapper">
            <div class="table-header">
                <h5 class="title">{{ __('Add Coin') }}</h5>
                <div class="table-btn-area">

                    @include('admin.components.link.add-default', [
                        'href' => '#coin-add',
                        'class' => 'modal-btn',
                        'text' => __('Add Coin'),
                        'permission' => 'admin.add.coin.store',
                    ])
                </div>
            </div>
            <div class="table-responsive">
                @include('admin.components.data-table.coin-table', compact('add_coins'))
            </div>
        </div>
        {{ get_paginate($add_coins) }}
    </div>

    {{-- Admin Add Modal --}}
    @include('admin.components.modals.add-coin')

    {{-- Admin Edit Modal --}}
    @include('admin.components.modals.edit-coin', compact('add_coins'))
@endsection

@push('script')
    <script>
        $(document).on("click", ".delete-modal-button", function() {
            var oldData = JSON.parse($(this).parents("tr").attr("data-item"));
            var actionRoute = "{{ route('admin.add.coin.delete') }}";
            var target = oldData.id;
            var message = `Are you sure to remove <strong>${oldData.coin} Coins</strong>?`;
            openDeleteModal(actionRoute, target, message);
        });

        // Switcher
        switcherAjax("{{ route('admin.admins.admin.status.update') }}");

        itemSearch($("input[name=admin_search]"), $(".admin-search-table"), "{{ setRoute('admin.admins.search') }}");
    </script>
@endpush
