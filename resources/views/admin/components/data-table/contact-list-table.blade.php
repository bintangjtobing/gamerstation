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
        'active' => __('Contacts'),
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
                            <th>{{ __('ID') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Email') }}</th>
                            <th>{{ __('Subject') }}</th>
                            <th>{{ __('Message') }}</th>
                            <th>{{ __('Action') }}</th>

                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($contacts  as $key => $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->name ?? 'N/A' }}</td>
                                <td>{{ $item->email ?? 'N/A' }}</td>
                                <td>{{ $item->subject ?? 'N/A' }}</td>
                                <td><a id="item" data-item="{{ $item }}" class="btn btn-primary contact_message">
                                        <i class="las la-comment"></i></a></td>
                                <td>
                                    <a id="item" data-item="{{ $item }}" class="btn btn-primary contact_mail">
                                        <i class="las la-reply"></i>
                                    </a>
                                </td>



                            </tr>
                        @empty
                            @include('admin.components.alerts.empty', ['colspan' => 8])
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ get_paginate($contacts) }}
        </div>
    </div>

    {{-- Model for send mail  --}}
    <div class="modal fade" id="sendMail" role="dialog" aria-bs-labelledby="sendMail" aria-bs-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="card-header">

                </div>
                <div class="card-body">
                    <form class="card-form" action="{{ route('admin.contacts.send.mail') }}" method="post">
                        @csrf
                        <div class="row mb-10-none">
                            <input type="hidden" name="contact_id">
                            <div class="col-xl-12 col-lg-12 form-group">
                                @include('admin.components.form.input', [
                                    'label' => __('Subject') . '*',
                                    'name' => 'subject',
                                    // 'value' => old('subject'),
                                    'placeholder' => 'Write Here...',
                                ])
                            </div>
                            <div class="col-xl-12 col-lg-12 form-group">
                                @include('admin.components.form.input-text-rich', [
                                    'label' => __('Details') . '*',
                                    'name' => 'message',
                                    // 'value' => old('message'),
                                    'placeholder' => 'Write Here...',
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
    {{-- Model for message  --}}
    <div class="modal fade" id="contact_message" tabindex="-1" role="dialog" aria-bs-labelledby="sendMail"
        aria-bs-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="card-header">
                    <h6>{{ __('Contact Message') }}</h6>
                </div>
                <div class="card-body">
                    <p class="message"></p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $('.contact_mail').on('click', function() {
            let data = $(this).data('item');
            $('.card-header').html("<h6 class='title'> Email to " + data.name + "</h6>");
            let contact_id = data.id;
            $("input[name=contact_id]").val(contact_id)
            $('#sendMail').modal('show');
        });

        $('.contact_message').on('click', function() {
            let message = $(this).data('item');
            console.log(message)
            $('.message').html(message.message);

            $('#contact_message').modal('show');
        });
    </script>
@endpush
