@extends('layouts.master')

@push('css')
@endpush

@section('content')
    <section class="account-section bg_img" data-background="{{ asset('public/frontend/images/element/account.png') }}">
        <div class="right float-end">
            <div class="account-header text-center">
                <a class="site-logo" href="{{ setRoute('index') }}"><img
                        src="{{ asset('public/frontend/images/logo/logo.png') }}" alt="logo"></a>
            </div>
            <div class="account-middle">
                <div class="account-form-area">
                    <h3 class="title">{{ __('Login Information') }}</h3>
                    <p>{{ __('Please input your username and password and login to your account to get access to your dashboard.') }}
                    </p>
                    <form action="{{ setRoute('user.login.submit') }}" class="account-form" method="POST">
                        @csrf
                        <div class="row ml-b-20">
                            <div class="col-lg-12 form-group">
                                @include('admin.components.form.input', [
                                    'name' => 'credentials',
                                    'placeholder' => 'Username OR Email Address',
                                    'required' => true,
                                ])
                            </div>
                            <div class="col-lg-12 form-group" id="show_hide_password">
                                <input type="password" class="form-control form--control" name="password"
                                    placeholder="Password" required>
                                <a href="javascript:void(0)" class="show-pass"><i class="fa fa-eye-slash"
                                        aria-hidden="true"></i></a>
                            </div>
                            <div class="col-lg-12 form-group">
                                <div class="forgot-item">
                                    <label><a href="{{ setRoute('user.password.forgot') }}"
                                            class="text--base">{{ __('Forgot Password?') }}</a></label>
                                </div>
                            </div>
                            <div class="col-lg-12 form-group text-center">
                                <button type="submit" class="btn--base w-100">{{ __('Login Now') }}</button>
                            </div>
                            <div class="col-lg-12 text-center">
                                <div class="account-item mt-10">
                                    <label>{{ __("Don't Have An Account?") }} <a href="{{ setRoute('user.register') }}"
                                            class="text--base">{{ __('Register Now') }}</a></label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="account-footer text-center">
                <p>{{ __('Copyright') }} © {{ date('Y', time()) }} {{ __('All Rights Reserved.') }}</a></p>
            </div>
        </div>
    </section>
@endsection

@push('script')
@endpush
