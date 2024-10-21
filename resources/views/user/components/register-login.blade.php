
<div class="account-section">
    <div class="account-bg"></div>
    <div class="account-area change-form">
        <div class="account-close"></div>
        <div class="account-form-area">
            <div class="account-logo text-start">
                <a href="{{ route('index') }}" class="site-logo">

                    <img src="{{ get_logo($basic_settings, 'dark') }}" alt="logo">
                </a>
            </div>
            <h5 class="title">{{__('Login Information')}}</h5>
            <p>{{__('Please input your email and password and login to your account to get access to your dashboard.')}}</p>
            <form action="{{ route('user.login.submit') }}" method="POST" class="account-form">
                @csrf
                <div class="row">
                    <div class="col-lg-12 form-group">
                        <input type="email" class="form-control form--control" name="credentials"
                            placeholder="Enter Email..." required>
                    </div>
                    <div class="col-lg-12 form-group show_hide_password">
                        <input type="password" required class="form-control form--control" name="password"
                            placeholder="Password" required>
                        <a href="" class="show-pass"><i class="la la-eye-slash" aria-hidden="true"></i></a>
                    </div>
                    <div class="col-lg-12 form-group">
                        <div class="forgot-item">
                            <label><a href="{{ route('user.password.forgot') }}">{{__('Forgot Password?')}}</a></label>
                        </div>
                    </div>
                    <div class="col-lg-12 form-group text-center">
                        <button type="submit" class="btn--base w-75">{{__('Login Now')}}</button>
                    </div>
                    @if ($basic_settings->user_registration == true)
                        <div class="col-lg-12 text-center">
                            <div class="account-item">
                                <label>{{__("Don't Have An Account?")}} <a href="javascript:void(0)"
                                        class="account-control-btn">{{__('Register Now')}}</a></label>
                            </div>
                        </div>
                    @endif

                </div>
            </form>
        </div>
    </div>
    <div class="account-area">
        <div class="account-close"></div>
        <div class="account-form-area">
            <div class="account-logo text-start">
                <a class="site-logo" href="{{ route('index') }}"><img src="{{ get_logo($basic_settings, 'dark') }}"
                        alt="logo"></a>
            </div>
            <h5 class="title">{{__('Register Information')}}</h5>
            <p>{{__('Please input your details and register to your account to get access to your dashboard.')}}</p>
            <form action="{{ route('user.register.submit') }}" method="POST" class="account-form">
                @csrf
                <div class="row">
                    <div class="col-lg-6 col-md-12 form-group">
                        <input type="text" class="form-control form--control" name="firstname"
                            placeholder="First Name" required>
                    </div>
                    <div class="col-lg-6 col-md-12 form-group">
                        <input type="text" class="form-control form--control" name="lastname"
                            placeholder="Last Name">
                    </div>
                    <div class="col-lg-12 form-group">
                        <input type="email" class="form-control form--control" name="email" placeholder="Email"
                            required>
                    </div>
                    <div class="col-lg-12 form-group show_hide_password">
                        <input type="password" class="form-control form--control" name="password" placeholder="Password"
                            required>
                        <a href="" class="show-pass"><i class="la la-eye-slash" aria-hidden="true"></i></a>
                    </div>
                    @if ($basic_settings->agree_policy == true)
                        <div class="col-lg-12 form-group">
                            <div class="custom-check-group">
                                <input type="checkbox" id="level-1" name="agree">
                                <label for="level-1">{{__('I have agreed with')}} <a href="{{ url('link/privacy-policy') }}"
                                        class="text--base">{{__('Privacy Policy')}}</a></label>
                            </div>
                        </div>
                    @endif

                    <div class="col-lg-12 form-group text-center">
                        <button type="submit" class="btn--base w-75">{{__('Register Now')}}</button>
                    </div>
                    <div class="col-lg-12 text-center">
                        <div class="account-item">
                            <label>{{__('Already Have An Account?')}} <a href="#0" class="account-control-btn">{{__('Login Now')}}</a></label>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
