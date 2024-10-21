@extends('layouts.master')

@push('css')
@endpush

@section('content')
    <div class="account-section active">
        <div class="account-area change-form page">
            <div class="account-form-area">
                <div class="account-logo text-start">
                    <a href="{{ route('index') }}" class="site-logo">
                        <img src="{{ get_logo($basic_settings, 'dark') }}" alt="logo">
                    </a>
                </div>
                <h5 class="title">{{__('Forgot Password?')}}</h5>
                <p>{{__("Enter your email and we'll send you a otp to reset your password.")}}</p>
                <form action="{{ setRoute('user.password.forgot.send.code') }}" class="account-form" method="POST">
                    @csrf
                    <div class="row ml-b-20">
                        <div class="col-lg-12 form-group">
                            @include('admin.components.form.input', [
                                'name' => 'credentials',
                                'placeholder' => 'Email Address',
                                'required' => true,
                            ])
                        </div>

                        <div class="col-lg-12 form-group text-center">
                            <button type="submit" class="btn--base w-100">{{ __('Send OTP') }}</button>
                        </div>


                        <div class="col-lg-12 text-center">
                            <div class="account-item">
                                <label>{{__('Already Have An Account?')}} <a href="{{ setRoute('index') }}"
                                        class="account-control-btn">{{__('Login Now')}}</a></label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
@endpush
