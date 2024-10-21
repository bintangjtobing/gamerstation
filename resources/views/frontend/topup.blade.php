@extends('frontend.layouts.master')

@push('css')
@endpush

@section('content')
    <section class="topup-section pt-80 pb-120">
        @include('frontend.components.topup')
    </section>
@endsection


@push('script')
@endpush
