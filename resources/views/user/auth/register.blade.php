@extends('layouts.master')

@push('css')
@endpush

@section('content')
    <section class="account-section bg_img" data-background="{{ asset('public/frontend/images/element/account.png') }}">
        <div class="right float-end">
            <div class="account-header text-center">
                <a class="site-logo" href="{{ route('index') }}"><img src="{{ get_logo($basic_settings) }}" alt="logo"></a>
            </div>
            <div class="account-middle">
                <div class="account-form-area">
                    <h3 class="title">{{ __('Register Information') }}</h3>
                    <p>{{ __('Please input your details and register to your account to get access to your dashboard.') }}
                    </p>
                    <form action="{{ setRoute('user.register.submit') }}" class="account-form" method="POST">
                        @csrf
                        <div class="row ml-b-20">
                            <div class="col-lg-6 form-group">
                                @include('admin.components.form.input', [
                                    'name' => 'firstname',
                                    'placeholder' => 'First Name',
                                    'value' => old('firstname'),
                                ])
                            </div>
                            <div class="col-lg-6 form-group">
                                @include('admin.components.form.input', [
                                    'name' => 'lastname',
                                    'placeholder' => 'Last Name',
                                    'value' => old('lastname'),
                                ])
                            </div>
                            <div class="col-lg-6 form-group">
                                <select name="country" class="form--control country-select"
                                    data-old="{{ old('country', $user_country) }}">
                                    <option selected disabled>Select Country</option>
                                </select>
                            </div>
                            <div class="col-lg-6 form-group">
                                <div class="input-group">
                                    <div class="input-group-text phone-code">--</div>
                                    <input class="phone-code" type="hidden" name="phone_code" />
                                    <input type="text" class="form--control" placeholder="Enter Phone" name="phone"
                                        value="{{ old('phone') }}">
                                </div>
                                {{-- @error('phone')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror --}}
                            </div>
                            <div class="col-lg-12 form-group">
                                @include('admin.components.form.input', [
                                    'type' => 'email',
                                    'name' => 'email',
                                    'placeholder' => 'Email',
                                    'value' => old('email'),
                                ])
                            </div>
                            <div class="col-lg-12 form-group" id="show_hide_password">
                                <input type="password" class="form--control" name="password" placeholder="Password"
                                    required>
                                <a href="javascript:void(0)" class="show-pass"><i class="fa fa-eye-slash"
                                        aria-hidden="true"></i></a>
                                {{-- @error('password')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror --}}
                            </div>
                            @dd($agree_policy)
                            @if ($agree_policy == 1)
                                <div class="col-lg-12 form-group">
                                    <div class="custom-check-group mb-0">
                                        <input type="checkbox" id="level-1" name="agree">
                                        <label for="level-1" class="mb-0">{{ __('I have read agreed with the') }} <a
                                                href="#0"
                                                class="text--base">{{ __('Terms Of Use , Privacy Policy & Warning') }}</a></label>
                                    </div>
                                    {{-- @error('agree')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror --}}
                                </div>
                            @endif

                            <div class="col-lg-12 form-group text-center">
                                <button type="submit" class="btn--base w-100">{{ __('Register Now') }}</button>
                            </div>
                            <div class="col-lg-12 text-center">
                                <div class="account-item mt-10">
                                    <label>{{ __('Already Have An Account?') }} <a href="{{ setRoute('user.login') }}"
                                            class="text--base">{{ _('Login Now') }}</a></label>
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
    <script>
        getAllCountries("{{ setRoute('global.countries') }}", $(".country-select"));
        $(document).ready(function() {
            $("select[name=country]").change(function() {
                var phoneCode = $("select[name=country] :selected").attr("data-mobile-code");
                placePhoneCode(phoneCode);
            });

            setTimeout(() => {
                var phoneCodeOnload = $("select[name=country] :selected").attr("data-mobile-code");
                placePhoneCode(phoneCodeOnload);
            }, 400);
        });
    </script>
@endpush
