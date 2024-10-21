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
        'active' => __('Top Up Details'),
    ])
@endsection

@section('content')
    <div class="custom-card">
        <div class="card-header">
            <h6 class="title">{{ __('Top Up Details') }}</h6>
        </div>
        <div class="card-body">
            <form class="card-form">
                <div class="row align-items-center mb-10-none">
                    <div class="col-xl-4 col-lg-4 form-group">
                        <ul class="user-profile-list-two">
                            <li class="two">{{ __('TRX ID') }}: <span>{{ @$data->trx_id }}</span></li>
                            <li class="three">{{ __('Email') }}:
                                <span>{{ isset($data->user) ? $data->user->email : 'N/A' }}</span>
                            </li>
                            <li class="four">{{ __('Method') }}: <span>{{ @$data->currency->name }}</span></li>
                            <li class="five">{{ __('Amount') }}: <span>{{ number_format(@$data->request_amount, 2) }}
                                    {{ $data->details->tempData->currency }}</span></li>
                        </ul>
                    </div>
                    <div class="col-xl-4 col-lg-4 form-group">
                        <div class="user-profile-thumb">
                            <img src="{{ get_image(@$data->user->image, 'user-profile') }}" alt="payment">
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 form-group">
                        <ul class="user-profile-list two">

                            <li class="one">{{ __('Charge') }}:
                                <span>{{ number_format(@$data->details->tempData->total_charge, 2) }}
                                    {{ @$data->details->tempData->currency }}</span>
                            </li>

                            <li class="three">{{ __('Recharge') }}: <span>
                                    {{ @$data->details->tempData->recharge_coin[0] }}
                                    {{ strtoupper($data->details->tempData->coin_type) }}</span></li>
                            <li class="two">{{ __('Payable') }}:
                                <span>{{ number_format(@$data->details->tempData->payable, 2) }}
                                    {{ @$data->details->tempData->currency }}</span>
                            </li>

                            <li class="five">{{ __('Status') }}: <span
                                    class="{{ @$data->stringStatus->class }}">{{ __(@$data->stringStatus->value) }}</span>
                            </li>
                        </ul>
                    </div>

                </div>
                <div class="row mt-30 mb-30-none">
                    <div class="col-lg-6 mb-30">
                        <div class="billing-area">
                            <h4 class="title"> {{ __('Billing Details') }}</h4>
                            <hr style="margin-bottom: 10px">
                            <div class="content">
                                <div class="list-wrapper">
                                    <ul class="list">

                                        <li>{{ __('First Name') }}:<span>{{ $data->details->order_details->first_name ?? 'N/A' }}</span>
                                        </li>
                                        <li>{{ __('Last Name') }}:<span>{{ $data->details->order_details->last_name ?? 'N/A' }}</span>
                                        </li>
                                        <li>{{ __('Email') }}:<span>{{ $data->details->order_details->email ?? 'N/A' }}</span>
                                        </li>
                                        <li>{{ __('Phone Number') }}:<span>{{ $data->details->order_details->phone_number ?? 'N/A' }}</span>
                                        </li>
                                        <li>{{ __('Address') }}:<span>{{ $data->details->order_details->address ?? 'N/A' }}</span>
                                        </li>
                                        <li>{{ __('City') }}:<span>{{ $data->details->order_details->city ?? 'N/A' }}</span>
                                        </li>
                                        <li>{{ __('State') }}:<span>{{ $data->details->order_details->state ?? 'N/A' }}</span>
                                        </li>
                                        <li>{{ __('Zip Code') }}:<span>{{ $data->details->order_details->zip ?? 'N/A' }}</span>
                                        </li>


                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-30">
                        <div class="billing-area">
                            <h4 class="title"> {{ __('Player ID') }}</h4>
                            <hr style="margin-bottom: 10px">
                            <div class="content">
                                <div class="list-wrapper">
                                    <ul class="list">
                                        @foreach ($data->details->tempData->player_id as $item)
                                            <li>{{ __($item->label) }}:<span>{{ $item->value }}</span></li>
                                        @endforeach

                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>


    <div class="custom-card mt-15">
        <div class="card-body">
            <h3 class="title"> {{ __('Progress of Top Up Transactions') }}</h3>
            <hr style="margin-bottom: 35px">
            @foreach ($data->details->sender_input ?? [] as $item)
                <li>
                    @if ($item->type == 'file')
                        @php
                            $file_link = get_file_link('kyc-files', $item->value);
                        @endphp
                        <span class="kyc-title">{{ $item->label }}:</span>
                        @if (its_image($item->value))
                            <div class="kyc-image ">
                                <img class="img-fluid" width="200px" src="{{ $file_link }}" alt="{{ $item->label }}">
                            </div>
                        @else
                            <span class="text--danger">
                                @php
                                    $file_info = get_file_basename_ext_from_link($file_link);
                                @endphp
                                <a href="{{ setRoute('file.download', ['kyc-files', $item->value]) }}">
                                    {{ Str::substr($file_info->base_name ?? '', 0, 20) . '...' . $file_info->extension ?? '' }}
                                </a>
                            </span>
                        @endif
                    @else
                        <span class="kyc-title">{{ $item->label }}:</span>
                        <span>{{ $item->value }}</span>
                    @endif
                </li>
            @endforeach
            </ul>

            <ul class="product-sales-info ">
                @foreach ($data->details->total_info->receiver_details ?? [] as $item)
                    <li>
                        @if ($item->type == 'file')
                            @php
                                $file_link = get_file_link('kyc-files', $item->value);
                            @endphp
                            <span class="kyc-title">{{ $item->label }}:</span>
                            @if (its_image($item->value))
                                <div class="kyc-image ">
                                    <img class="img-fluid" width="200px" src="{{ $file_link }}"
                                        alt="{{ $item->label }}">
                                </div>
                            @else
                                <span class="text--danger">
                                    @php
                                        $file_info = get_file_basename_ext_from_link($file_link);
                                    @endphp
                                    <a href="{{ setRoute('file.download', ['kyc-files', $item->value]) }}">
                                        {{ Str::substr($file_info->base_name ?? '', 0, 20) . '...' . $file_info->extension ?? '' }}
                                    </a>
                                </span>
                            @endif
                        @else
                            <span class="kyc-title">{{ $item->label }}:</span>
                            <span>{{ $item->value }}</span>
                        @endif
                    </li>
                @endforeach
            </ul>

            <form action="{{ route('admin.top.up.log.status.update', $data->id) }}" method="post">
                @csrf
                @method('PUT')
                <div class="transaction-area">

                    <div class="content pt-0">

                        <div class="radio-area ">
                            <div class="row justify-content-start">
                                <div class="radio-wrapper col-md-3 form-group">
                                    <div class="radio-item">
                                        <input type="radio" id="level-1" value="1"
                                            @if ($data->status == 1) checked @endif name="status">
                                        <label for="level-1">{{ __('Under Review') }}</label>
                                    </div>
                                </div>
                                <div class="radio-wrapper col-md-3 form-group">
                                    <div class="radio-item">
                                        <input type="radio" id="level-2" value="2"
                                            @if ($data->status == 2) checked @endif name="status">
                                        <label for="level-2">{{ __('Pending') }}</label>
                                    </div>
                                </div>
                                <div class="radio-wrapper col-md-3 form-group">
                                    <div class="radio-item">
                                        <input type="radio" id="level-3" value="3"
                                            @if ($data->status == 3) checked @endif name="status">
                                        <label for="level-3">{{ __('In Progress') }}</label>
                                    </div>
                                </div>
                                <div class="radio-wrapper col-md-3 form-group">
                                    <div class="radio-item">
                                        <input type="radio" id="level-4" value="4"
                                            @if ($data->status == 4) checked @endif name="status">
                                        <label for="level-4">{{ __('On Hold') }}</label>
                                    </div>
                                </div>
                                <div class="radio-wrapper col-md-3 form-group">
                                    <div class="radio-item">
                                        <input type="radio" id="level-5" value="5"
                                            @if ($data->status == 5) checked @endif name="status">
                                        <label for="level-5">{{ __('Settled') }}</label>
                                    </div>
                                </div>
                                <div class="radio-wrapper col-md-3 form-group">
                                    <div class="radio-item">
                                        <input type="radio" id="level-6" value="6"
                                            @if ($data->status == 6) checked @endif name="status">
                                        <label for="level-6">{{ __('Completed') }}</label>
                                    </div>
                                </div>
                                <div class="radio-wrapper col-md-3 form-group">
                                    <div class="radio-item">
                                        <input type="radio" id="level-7" value="7"
                                            @if ($data->status == 7) checked @endif name="status">
                                        <label for="level-7">{{ __('Canceled') }}</label>
                                    </div>
                                </div>
                                <div class="radio-wrapper col-md-3 form-group">
                                    <div class="radio-item">
                                        <input type="radio" id="level-8" value="8"
                                            @if ($data->status == 8) checked @endif name="status">
                                        <label for="level-8">{{ __('Failed') }}</label>
                                    </div>
                                </div>
                                <div class="radio-wrapper col-md-3 form-group">
                                    <div class="radio-item">
                                        <input type="radio" id="level-9" value="9"
                                            @if ($data->status == 9) checked @endif name="status">
                                        <label for="level-9">{{ __('Refunded') }}</label>
                                    </div>
                                </div>
                                <div class="radio-wrapper col-md-3 form-group">
                                    <div class="radio-item">
                                        <input type="radio" id="level-10" value="10"
                                            @if ($data->status == 10) checked @endif name="status">
                                        <label for="level-10">{{ __('Delayed') }}</label>
                                    </div>
                                </div>
                                <div class="radio-wrapper col-md-3 form-group">
                                    <div class="radio-item" data-rejected="{{ $data->id }}" id="rejectedBtn">
                                        <input type="radio" id="level-11" value="11"
                                            @if ($data->status == 11) checked @endif name="status">
                                        <label for="level-11">{{ __('Rejected') }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-20">
                            @include('admin.components.button.form-btn', [
                                'class' => 'w-100 btn-loading',
                                'text' => 'Update',
                            ])
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    {{-- manual payment required data --}}
    @if ('MANUAL' == $data->currency->gateway->type)
        <div class="custom-card mt-15">
            <div class="card-header">
                <h6 class="title">{{ __('Information of Logs') }}</h6>
            </div>
            <div class="card-body">
                <ul class="product-sales-info">
                    @php
                        $user_data = get_object_vars($data->details);
                    @endphp

                    @foreach ($user_data['manual_user_data'] ?? [] as $item)
                        <li>

                            @if ($item->type == 'file')
                                @php
                                    $file_link = get_file_link('kyc-files', $item->value);
                                @endphp
                                <span class="kyc-title">{{ $item->label }}:</span>
                                @if (its_image($item->value))
                                    <div class="kyc-image ">
                                        <img class="img-fluid" width="200px" src="{{ $file_link }}"
                                            alt="{{ $item->label }}">
                                    </div>
                                @else
                                    <span class="text--danger">
                                        @php
                                            $file_info = get_file_basename_ext_from_link($file_link);
                                        @endphp
                                        <a href="{{ setRoute('file.download', ['kyc-files', $item->value]) }}">
                                            {{ Str::substr($file_info->base_name ?? '', 0, 20) . '...' . $file_info->extension ?? '' }}
                                        </a>
                                    </span>
                                @endif
                            @else
                                <span class="kyc-title">{{ $item->label }}:</span>
                                <span>{{ $item->value }}</span>
                            @endif
                        </li>
                    @endforeach
                </ul>

            </div>
        </div>
    @endif
    <!-- Rejection Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form method="POST" action="{{ route('admin.top.up.log.rejected') }}">
                    <div class="modal-body">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="rejected_id" id="rejected_id">
                        <div class="form-group">
                            <label for="description">{{ __('Rejection Reason') }}</label>
                            <textarea class="form--control" placeholder="Enter Refection Reason" name="rejection_reason">{{ $data->reject_reason }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn--base bg--info"
                            data-bs-dismiss="modal">{{ __('Close') }}</button>
                        <button type="submit" class="btn--base">{{ __('Rejected') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            @if ($errors->any())
                var modal = $('#rejectModal');
                modal.modal('show');
            @endif

            $('.approvedBtn').on('click', function() {
                var modal = $('#approvedModal');
                modal.modal('show');
            });
            $('.rejectBtn').on('click', function() {
                var modal = $('#rejectModal');
                modal.modal('show');
            });
        });

        $('#rejectedBtn').on('click', function() {
            let rejected_id = $(this).data('rejected');
            $('#rejected_id').val(rejected_id);
            $('.modal').modal('show');

        });
    </script>
@endpush
