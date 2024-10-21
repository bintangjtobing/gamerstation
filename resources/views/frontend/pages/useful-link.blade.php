@php
    $app_local_lang = get_default_language_code();
@endphp
@extends('frontend.layouts.master')

@push('css')
@endpush


@section('content')
    <section class="how-we-do-section ptb-120">
        <div class="container">
            <h2 class="title mb-40">{{ $useful_link->title->language->$app_local_lang->title }}</span></h2>
            {!! $useful_link->content?->language?->$app_local_lang->content ?? '' !!}
        </div>
    </section>
@endsection


@push('script')
@endpush
