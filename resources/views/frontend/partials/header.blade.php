    <header class="header-section home">
        <div class="header">
            <div class="header-bottom-area">
                <div class="container-fluid">
                    <div class="header-menu-content">
                        <div class="logo-wrapper">
                            <a class="site-logo site-title" href="{{ setRoute('index') }}">
                                <img src="{{ get_logo($basic_settings, 'dark') }}" alt="site-logo">
                            </a>
                            <button class="logo-btn"><i class="las la-bars"></i></button>
                        </div>
                        <div class="header-search">
                            <div class="header-search-area">
                                <input type="search" class="top-up-search" placeholder="{{ __('Search For Top Up') }}">
                                <span><i class="las la-search"></i></span>
                            </div>
                            <div class="header-mobile-search-area">
                                <a href="#0" class="header-mobile-search-btn">
                                    <i class="las la-search"></i>
                                </a>
                                <div class="header-mobile-search-form-area">
                                    <input type="search" placeholder="{{ __('Search For Top Up') }}">
                                    <span><i class="las la-search"></i></span>
                                </div>
                            </div>
                            <ul class="header-search-result">

                            </ul>
                        </div>
                        <div class="header-action">
                            <div class="lan-swicth" style="padding-right: 50px">
                                @php
                                    $session_lan = session('local') ?? get_default_language_code();

                                @endphp
                                <select class="form--control langSel nice-select">
                                    @foreach ($__languages as $item)
                                        <option value="{{ $item->code }}"
                                            @if ($session_lan == $item->code) selected @endif>{{ __($item->name) }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>

                            <div class="action-wrapper">
                                <div class="notify-wrapper">
                                    <div class="notify-btn-area">
                                        <button><i class="las la-bell"></i></button>
                                    </div>
                                    <div class="notification-wrapper" id="notification-wrapper">
                                        <div class="notification-header">
                                            <h6 class="title">{{ __('Notification') }}</h6>
                                            @guest

                                                <span class="sub-title">{{ __('Please') }}
                                                    <a href="javascript:void(0)" style="color:#FF3C41"
                                                        class="account-area-btn">{{ __('Login') }}</a>
                                                    {{ __('Your Account') }}
                                                </span>
                                            @endguest
                                        </div>
                                        <ul class="notification-list">
                                            @auth
                                                @foreach (get_user_notifications() as $item)
                                                    <li>
                                                        <div class="thumb">
                                                            <img src="{{ auth()->user()->userImage }}" alt="user">
                                                        </div>
                                                        <div class="content">
                                                            <div class="title-area">
                                                                <h5 class="title">{{ $item->message->title }}</h5>
                                                                <span
                                                                    class="time">{{ $item->created_at->diffForHumans() }}</span>
                                                            </div>
                                                            <span class="sub-title">{{ $item->message->message }}</span>
                                                        </div>

                                                    </li>
                                                @endforeach
                                            @endauth
                                        </ul>
                                    </div>
                                </div>


                                <div class="account-thumb-area">
                                    @if ($basic_settings->user_registration == 1)
                                        @auth
                                            <a href="{{ setRoute('user.dashboard') }}" class="header-account-button-area">
                                                <div class="header-account-button-thumb-area">
                                                    <img src="{{ auth()->user()->userImage }}" alt="account">
                                                </div>
                                                <span class="title">{{ __('Dashboard') }}</span>
                                            </a>
                                        @else
                                            <a href="javascript:void(0)"
                                                class="header-account-button-area account-area-btn">
                                                <div class="header-account-button-thumb-area">
                                                    <img src="{{ asset('public/frontend') }}/images/user/account.png"
                                                        alt="account">
                                                </div>
                                                <span class="title">{{ __('Login/Register') }}</span>
                                            </a>
                                        @endauth
                                    @else
                                        @auth
                                            <a href="{{ setRoute('user.dashboard') }}" class="header-account-button-area">
                                                <div class="header-account-button-thumb-area">
                                                    <img src="{{ auth()->user()->userImage }}" alt="account">
                                                </div>
                                                <span class="title">{{ __('Dashboard') }}</span>
                                            </a>
                                        @else
                                            <a href="javascript:void(0)"
                                                class="header-account-button-area account-area-btn">
                                                <div class="header-account-button-thumb-area">
                                                    <img src="{{ asset('public/frontend') }}/images/user/account.png"
                                                        alt="account">
                                                </div>
                                                <span class="title">{{ __('Login/Register') }}</span>
                                            </a>
                                        @endauth
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
