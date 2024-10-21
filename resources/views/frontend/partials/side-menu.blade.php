@php
    $menues = DB::table('setup_pages')
        ->where('status', 1)
        ->get();
    
    $current_route = Route::currentRouteName();
@endphp

<div class="main-side-menu">
    <div class="main-side-menu-logo-area">
        <div class="thumb-logo">
            <img src="{{ get_logo($basic_settings, 'dark') }}" alt="logo">
        </div>
        <span class="main-side-menu-cross"><i class="las la-times"></i></span>
    </div>
    <ul class="main-side-menu-list">
        <li>
            <a href="{{ route('index') }}" class="{{ $current_route == 'index' ? 'active' : '' }}">
                <div class="main-side-menu-item">
                    <i class="las la-th-large"></i> {{ __('Home') }}
                </div>
                <span><i class="las la-angle-right"></i></span>
            </a>
        </li>

        <li>
            <a href="{{ route('topup') }}" class="{{ $current_route == 'topup' ? 'active' : '' }}">
                <div class="main-side-menu-item">
                    <i class="las la-coins"></i> {{ __('Topup') }}
                </div>
                <span><i class="las la-angle-right"></i></span>
            </a>
        </li>
        <li>
            <a href="{{ route('about') }}" class="{{ $current_route == 'about' ? 'active' : '' }}">
                <div class="main-side-menu-item">
                    <i class="las la-info-circle"></i> {{ __('About') }}
                </div>
                <span><i class="las la-angle-right"></i></span>
            </a>
        </li>
        <li>
            <a href="{{ route('blog') }}" class="{{ $current_route == 'blog' ? 'active' : '' }}">
                <div class="main-side-menu-item">
                    <i class="las la-comments"></i> {{ __('Blog') }}
                </div>
                <span><i class="las la-angle-right"></i></span>
            </a>
        </li>
        <li>
            <a href="{{ route('faq') }}" class="{{ $current_route == 'faq' ? 'active' : '' }}">
                <div class="main-side-menu-item">
                    <i class="las la-question-circle"></i> {{ __('FAQ') }}
                </div>
                <span><i class="las la-angle-right"></i></span>
            </a>
        </li>
        <li>
            <a href="{{ route('contact') }}" class="{{ $current_route == 'contact' ? 'active' : '' }}">
                <div class="main-side-menu-item">
                    <i class="las la-headset"></i> {{ __('Contact') }}
                </div>
                <span><i class="las la-angle-right"></i></span>
            </a>
        </li>
    </ul>
</div>
