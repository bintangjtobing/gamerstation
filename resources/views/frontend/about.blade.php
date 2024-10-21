@extends('frontend.layouts.master')

@push('css')
@endpush

@section('content')
    @include('frontend.components.about-company')
    @include('frontend.components.why-choose')
    @include('frontend.components.testimonial')
@endsection


@push('script')
@endpush
