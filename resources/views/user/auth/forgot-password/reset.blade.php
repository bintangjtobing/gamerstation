@extends('layouts.master')

@push('css')
@endpush

@section('content')
    <div class="account-section active">
        <div class="account-area change-form page">
            <div class="account-form-area">
                <div class="account-logo text-center">
                    <a href="{{ route('index') }}" class="site-logo">
                        <img src="{{ get_logo($basic_settings, 'dark') }}" alt="logo">
                    </a>
                </div>
                <h5 class="title">Set New Password</h5>
                <p>Please Enter your new password and get login access on your Dashboard.</p>
                <form action="{{ setRoute('user.password.reset', $token) }}" class="account-form" method="POST">
                    @csrf
                    <div class="row ml-b-20">
                        {{-- <div class="col-lg-12 form-group">
                            @include('admin.components.form.input', [
                                'name' => 'password',
                                'type' => 'password',
                                'placeholder' => 'Enter New Password',
                                'required' => true,
                            ])
                        </div> --}}
                        <div class="col-xl-12 col-lg-12 form-group show_hide_password">
                            <input type="password" name="password" class="form--control" placeholder="New password"
                                required>
                            <a href="javascript:void(0)" class="show-pass"><i class="la la-eye-slash"
                                    aria-hidden="true"></i></a>
                        </div>
                        {{-- <div class="col-lg-12 form-group">
                            @include('admin.components.form.input', [
                                'name' => 'password_confirmation',
                                'type' => 'password',
                                'placeholder' => 'Enter Confirm Password',
                                'required' => true,
                            ])
                        </div> --}}
                        <div class="col-xl-12 col-lg-12 form-group show_hide_password">
                            <input type="password" name="password_confirmation" class="form--control"
                                placeholder="Confirm password" required>
                            <a href="" class="show-pass"><i class="la la-eye-slash" aria-hidden="true"></i></a>
                        </div>

                        <div class="col-lg-12 form-group text-center">
                            <button type="submit" class="btn--base w-100">{{ __('Reset') }}</button>
                        </div>

                        <div class="col-lg-12 text-center">
                            <div class="account-item">
                                <label>Already Have An Account? <a href="{{ route('index') }}"
                                        class="account-control-btn">Login
                                        Now</a></label>
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
