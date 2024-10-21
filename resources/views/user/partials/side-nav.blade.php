@php
    $current_route = Route::currentRouteName();

    $current_url = URL::current();

    $transaction_childs = [route('user.transactions.index', 'add-money'), route('user.transactions.index', 'top-up')];
@endphp
<div class="sidebar">
    <div class="sidebar-inner">
        <div class="sidebar-menu-inner-wrapper">
            <div class="sidebar-logo">
                <a href="{{ route('index') }}" class="sidebar-main-logo">
                    <img src="{{ get_logo($basic_settings, 'dark') }}"
                        data-white_img="{{ get_logo($basic_settings, 'white') }}"
                        data-dark_img="{{ get_logo($basic_settings, 'dark') }}" alt="logo">
                </a>
                <button class="sidebar-menu-bar">
                    <i class="las la-exchange-alt"></i>
                </button>
            </div>
            <div class="sidebar-menu-wrapper">
                <ul class="sidebar-menu">
                    <li class="sidebar-menu-item @if ($current_route == 'user.dashboard' ? 'active' : '')  @endif">
                        <a href="{{ route('user.dashboard') }}">
                            <i class="menu-icon las la-palette"></i>
                            <span class="menu-title">{{ __('Dashboard') }}</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-item {{ $current_route == 'user.game.topup.index' ? 'active' : '' }}">
                        <a href="{{ route('user.game.topup.index') }}">
                            <i class="menu-icon las la-gamepad"></i>
                            <span class="menu-title">{{ __('Game Topup') }}</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-item {{ $current_route == 'user.add.money.index' ? 'active' : '' }}">
                        <a href="{{ route('user.add.money.index') }}">
                            <i class="menu-icon las la-plus-circle"></i>
                            <span class="menu-title">{{ __('Add Money') }}</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-item sidebar-dropdown @if (in_array($current_url, $transaction_childs)) active @endif">
                        <a href="javascript:void(0)">
                            <i class="menu-icon las la-arrows-alt-h"></i>
                            <span class="menu-title">{{ __('Transaction') }}</span>
                        </a>
                        <ul class="sidebar-submenu" @if (in_array($current_url, $transaction_childs)) style="display: block" @endif>
                            <li class="sidebar-menu-item">
                                <a href="{{ setRoute('user.transactions.index', 'add-money') }}"
                                    class="nav-link @if ($current_url == route('user.transactions.index', 'add-money')) active @endif">
                                    <i class="menu-icon las la-ellipsis-h"></i>
                                    <span class="menu-title">{{ __('Add Money Logs') }}</span>
                                </a>

                                <a href="{{ setRoute('user.transactions.index', 'top-up') }}"
                                    class="nav-link @if ($current_url == setRoute('user.transactions.index', 'top-up')) active @endif">
                                    <i class="menu-icon las la-ellipsis-h"></i>
                                    <span class="menu-title">{{ __('Purchase Logs') }}</span>
                                </a>

                            </li>
                        </ul>
                    </li>
                    <li class="sidebar-menu-item @if ($current_route == 'user.security.google.2fa') ? 'active' : '' @endif">
                        <a href="{{ route('user.security.google.2fa') }}">
                            <i class="menu-icon las la-qrcode"></i>
                            <span class="menu-title">{{ __('2fa Security') }}</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-item">
                        <a href="javascript:void(0)" class="logout-btn">
                            <i class="menu-icon las la-sign-out-alt"></i>
                            <span class="menu-title">{{ __('Logout') }}</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="side-doc-wrapper">
            <div class="sidebar-doc-box bg_img"
                data-background="{{ asset('public/frontend') }}/images/product-item/1.jpg"
                style="background-image: url(&quot;{{ asset('public/frontend') }}/images/product-item/1.jpg&quot;);">
                <div class="sidebar-doc-icon">
                    <i class="las la-headset"></i>
                </div>
                <div class="sidebar-doc-content">
                    <h4 class="title">{{ __('Help Center') }}</h4>
                    <p>{{ __('How can we help you?') }}</p>
                    <div class="sidebar-doc-btn">
                        <a href="{{ route('user.support.ticket.index') }}" class="w-100">{{ __('Get Support') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
