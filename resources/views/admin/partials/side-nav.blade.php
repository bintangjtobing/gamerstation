<div class="sidebar">
    <div class="sidebar-inner">
        <div class="sidebar-logo">
            <a href="{{ setRoute('admin.dashboard') }}" class="sidebar-main-logo">
                <img src="{{ get_logo($basic_settings) }}" data-white_img="{{ get_logo($basic_settings, 'white') }}"
                    data-dark_img="{{ get_logo($basic_settings, 'dark') }}" alt="logo">
            </a>
            <button class="sidebar-menu-bar">
                <i class="fas fa-exchange-alt"></i>
            </button>
        </div>
        <div class="sidebar-user-area">
            <div class="sidebar-user-thumb">
                <a href="{{ setRoute('admin.profile.index') }}"><img
                        src="{{ get_image(Auth::user()->image, 'admin-profile', 'profile') }}" alt="user"></a>
            </div>
            <div class="sidebar-user-content">
                <h6 class="title">{{ Auth::user()->fullname }}</h6>
                <span class="sub-title">{{ Auth::user()->getRolesString() }}</span>
            </div>
        </div>
        @php
            $current_route = Route::currentRouteName();
        @endphp
        <div class="sidebar-menu-wrapper">
            <ul class="sidebar-menu">

                @include('admin.components.side-nav.link', [
                    'route' => 'admin.dashboard',
                    'title' => 'Dashboard',
                    'icon' => 'menu-icon las la-rocket',
                ])

                {{-- Section Default --}}
                @include('admin.components.side-nav.link-group', [
                    'group_title' => 'Default',
                    'group_links' => [
                        [
                            'title' => 'Setup Currency',
                            'route' => 'admin.currency.index',
                            'icon' => 'menu-icon las la-coins',
                        ],
                        [
                            'title' => 'Fees & Charges',
                            'route' => 'admin.trx.settings.index',
                            'icon' => 'menu-icon las la-wallet',
                        ],
                    ],
                ])
                {{-- top up game --}}
                <li class="sidebar-menu-item @if (URL::current() == setRoute('admin.top.up.game.index')) active @endif">
                    <a href="{{ route('admin.top.up.game.index') }}">
                        <i class="menu-icon las la-coins"></i>
                        <span class="menu-title">{{ __('Setup Top Up Game') }}</span>
                    </a>
                </li>

                {{-- Section Transaction & Logs --}}
                @include('admin.components.side-nav.link-group', [
                    'group_title' => 'Transactions & Logs',
                    'group_links' => [
                        'dropdown' => [
                            [
                                'title' => 'Add Money Logs',
                                'icon' => 'menu-icon las la-calculator',
                                'links' => [
                                    [
                                        'title' => 'Review Payment Logs',
                                        'route' => 'admin.add.money.review.payment',
                                    ],
                                    [
                                        'title' => 'Pending Logs',
                                        'route' => 'admin.add.money.pending',
                                    ],
                                    [
                                        'title' => 'Confirm Payment Logs',
                                        'route' => 'admin.add.money.confirm.payment',
                                    ],
                                    [
                                        'title' => 'On Hold Logs',
                                        'route' => 'admin.add.money.onhold',
                                    ],
                                    [
                                        'title' => 'Settled Logs',
                                        'route' => 'admin.add.money.settled',
                                    ],
                                    [
                                        'title' => 'Completed Logs',
                                        'route' => 'admin.add.money.completed',
                                    ],
                                    [
                                        'title' => 'Canceled Logs',
                                        'route' => 'admin.add.money.canceled',
                                    ],
                                    [
                                        'title' => 'Failed Logs',
                                        'route' => 'admin.add.money.failed',
                                    ],
                                    [
                                        'title' => 'Refunded Logs',
                                        'route' => 'admin.add.money.refunded',
                                    ],
                                    [
                                        'title' => 'Delayed Logs',
                                        'route' => 'admin.add.money.delayed',
                                    ],
                                    [
                                        'title' => 'All Logs',
                                        'route' => 'admin.add.money.index',
                                    ],
                                ],
                            ],
                
                            [
                                'title' => 'Top Up Logs',
                                'icon' => 'menu-icon las la-sign-out-alt',
                                'links' => [
                                    [
                                        'title' => 'Review Payment Logs',
                                        'route' => 'admin.top.up.log.review.payment',
                                    ],
                                    [
                                        'title' => 'Pending Logs',
                                        'route' => 'admin.top.up.log.pending',
                                    ],
                                    [
                                        'title' => 'Confirm Payment Logs',
                                        'route' => 'admin.top.up.log.confirm.payment',
                                    ],
                                    [
                                        'title' => 'On Hold Logs',
                                        'route' => 'admin.top.up.log.onhold',
                                    ],
                                    [
                                        'title' => 'Settled Logs',
                                        'route' => 'admin.top.up.log.settled',
                                    ],
                                    [
                                        'title' => 'Completed Logs',
                                        'route' => 'admin.top.up.log.completed',
                                    ],
                                    [
                                        'title' => 'Canceled Logs',
                                        'route' => 'admin.top.up.log.canceled',
                                    ],
                                    [
                                        'title' => 'Failed Logs',
                                        'route' => 'admin.top.up.log.failed',
                                    ],
                                    [
                                        'title' => 'Refunded Logs',
                                        'route' => 'admin.top.up.log.refunded',
                                    ],
                                    [
                                        'title' => 'Delayed Logs',
                                        'route' => 'admin.top.up.log.delayed',
                                    ],
                                    [
                                        'title' => 'All Logs',
                                        'route' => 'admin.top.up.log.index',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ])
                {{-- Interface Panel --}}
                @include('admin.components.side-nav.link-group', [
                    'group_title' => 'Interface Panel',
                    'group_links' => [
                        'dropdown' => [
                            [
                                'title' => 'User Care',
                                'icon' => 'menu-icon las la-user-edit',
                                'links' => [
                                    [
                                        'title' => 'Active Users',
                                        'route' => 'admin.users.active',
                                    ],
                                    [
                                        'title' => 'Email Unverified',
                                        'route' => 'admin.users.email.unverified',
                                    ],
                                    [
                                        'title' => 'All Users',
                                        'route' => 'admin.users.index',
                                    ],
                                    [
                                        'title' => 'Email To Users',
                                        'route' => 'admin.users.email.users',
                                    ],
                                    [
                                        'title' => 'Banned Users',
                                        'route' => 'admin.users.banned',
                                    ],
                                ],
                            ],
                            [
                                'title' => 'Admin Care',
                                'icon' => 'menu-icon las la-user-shield',
                                'links' => [
                                    [
                                        'title' => 'All Admin',
                                        'route' => 'admin.admins.index',
                                    ],
                                    [
                                        'title' => 'Admin Role',
                                        'route' => 'admin.admins.role.index',
                                    ],
                                    [
                                        'title' => 'Role Permission',
                                        'route' => 'admin.admins.role.permission.index',
                                    ],
                                    [
                                        'title' => 'Email To Admin',
                                        'route' => 'admin.admins.email.admins',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ])

                {{-- Section Settings --}}
                @include('admin.components.side-nav.link-group', [
                    'group_title' => 'Settings',
                    'group_links' => [
                        'dropdown' => [
                            [
                                'title' => 'Web Settings',
                                'icon' => 'menu-icon lab la-safari',
                                'links' => [
                                    [
                                        'title' => 'Basic Settings',
                                        'route' => 'admin.web.settings.basic.settings',
                                    ],
                                    [
                                        'title' => 'Image Assets',
                                        'route' => 'admin.web.settings.image.assets',
                                    ],
                                    [
                                        'title' => 'Setup SEO',
                                        'route' => 'admin.web.settings.setup.seo',
                                    ],
                                ],
                            ],
                            [
                                'title' => 'App Settings',
                                'icon' => 'menu-icon las la-mobile',
                                'links' => [
                                    [
                                        'title' => 'Splash Screen',
                                        'route' => 'admin.app.settings.splash.screen',
                                    ],
                                    [
                                        'title' => 'Onboard Screen',
                                        'route' => 'admin.app.settings.onboard.screens',
                                    ],
                                    [
                                        'title' => 'App URLs',
                                        'route' => 'admin.app.settings.urls',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ])

                @include('admin.components.side-nav.link', [
                    'route' => 'admin.languages.index',
                    'title' => 'Languages',
                    'icon' => 'menu-icon las la-language',
                ])

                {{-- Verification Center --}}
                @include('admin.components.side-nav.link-group', [
                    'group_title' => 'Verification Center',
                    'group_links' => [
                        'dropdown' => [
                            [
                                'title' => 'Setup Email',
                                'icon' => 'menu-icon las la-envelope-open-text',
                                'links' => [
                                    [
                                        'title' => 'Email Method',
                                        'route' => 'admin.setup.email.config',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ])

                @if (admin_permission_by_name('admin.setup.sections.section'))
                    <li class="sidebar-menu-header">{{ __('Setup Web Content') }}</li>
                    @php
                        $current_url = URL::current();

                        $setup_section_childs = [
                            setRoute('admin.setup.sections.section', 'footer'),
                            setRoute('admin.setup.sections.section', 'faq'),
                            setRoute('admin.setup.sections.section', 'about'),
                            setRoute('admin.setup.sections.section', 'contact'),
                            setRoute('admin.setup.sections.section', 'why-choose'),
                            setRoute('admin.setup.sections.section', 'testimonial'),
                            setRoute('admin.setup.sections.section', 'banner'),
                            setRoute('admin.setup.sections.section', 'solutions'),
                            setRoute('admin.setup.sections.section', 'monitoring'),
                            setRoute('admin.setup.sections.section', 'best-item'),
                            setRoute('admin.setup.sections.section', 'latest-item'),
                            setRoute('admin.setup.sections.section', 'header-slider'),
                            setRoute('admin.setup.sections.section', 'service-provider'),
                            setRoute('admin.setup.sections.section', 'intro'),
                            setRoute('admin.setup.sections.section', 'services'),
                        ];
                    @endphp

                    <li class="sidebar-menu-item sidebar-dropdown @if (in_array($current_url, $setup_section_childs)) active @endif">
                        <a href="javascript:void(0)">
                            <i class="menu-icon las la-terminal"></i>
                            <span class="menu-title">{{ __('Setup Section') }}</span>
                        </a>
                        <ul class="sidebar-submenu">
                            <li class="sidebar-menu-item">


                                <a href="{{ setRoute('admin.setup.sections.section', 'header-slider') }}"
                                    class="nav-link @if ($current_url == setRoute('admin.setup.sections.section', 'header-slider')) active @endif">
                                    <i class="menu-icon las la-ellipsis-h"></i>
                                    <span class="menu-title">{{ __('Header Slider') }}</span>
                                </a>

                                <a href="{{ setRoute('admin.setup.sections.section', 'services') }}"
                                    class="nav-link @if ($current_url == setRoute('admin.setup.sections.section', 'services')) active @endif">
                                    <i class="menu-icon las la-ellipsis-h"></i>
                                    <span class="menu-title">{{ __('Services') }}</span>
                                </a>
                                <a href="{{ setRoute('admin.setup.sections.section', 'donetion') }}"
                                    class="nav-link @if ($current_url == setRoute('admin.setup.sections.section', 'donetion')) active @endif">
                                    <i class="menu-icon las la-ellipsis-h"></i>
                                    <span class="menu-title">{{ __('Donetion') }}</span>
                                </a>
                                <a href="{{ setRoute('admin.setup.sections.section', 'recharge') }}"
                                    class="nav-link @if ($current_url == setRoute('admin.setup.sections.section', 'recharge')) active @endif">
                                    <i class="menu-icon las la-ellipsis-h"></i>
                                    <span class="menu-title">{{ __('Recharge') }}</span>
                                </a>
                                <a href="{{ setRoute('admin.setup.sections.section', 'about') }}"
                                    class="nav-link @if ($current_url == setRoute('admin.setup.sections.section', 'about')) active @endif">
                                    <i class="menu-icon las la-ellipsis-h"></i>
                                    <span class="menu-title">{{ __('About') }}</span>
                                </a>
                                <a href="{{ setRoute('admin.setup.sections.section', 'why-choose') }}"
                                    class="nav-link @if ($current_url == setRoute('admin.setup.sections.section', 'why-choose')) active @endif">
                                    <i class="menu-icon las la-ellipsis-h"></i>
                                    <span class="menu-title">{{ __('Why Choose') }}</span>
                                </a>
                                <a href="{{ setRoute('admin.setup.sections.section', 'testimonial') }}"
                                    class="nav-link @if ($current_url == setRoute('admin.setup.sections.section', 'testimonial')) active @endif">
                                    <i class="menu-icon las la-ellipsis-h"></i>
                                    <span class="menu-title">{{ __('Testimonial') }}</span>
                                </a>
                                <a href="{{ setRoute('admin.setup.sections.section', 'contact') }}"
                                    class="nav-link @if ($current_url == setRoute('admin.setup.sections.section', 'contact')) active @endif">
                                    <i class="menu-icon las la-ellipsis-h"></i>
                                    <span class="menu-title">{{ __('Contact') }}</span>
                                </a>
                                <a href="{{ setRoute('admin.setup.sections.section', 'footer') }}"
                                    class="nav-link @if ($current_url == setRoute('admin.setup.sections.section', 'footer')) active @endif">
                                    <i class="menu-icon las la-ellipsis-h"></i>
                                    <span class="menu-title">{{ __('Footer') }}</span>
                                </a>
                                <a href="{{ setRoute('admin.setup.sections.section', 'faq') }}"
                                    class="nav-link @if ($current_url == setRoute('admin.setup.sections.section', 'faq')) active @endif">
                                    <i class="menu-icon las la-ellipsis-h"></i>
                                    <span class="menu-title">{{ __('FAQ') }}</span>
                                </a>
                                <a href="{{ setRoute('admin.setup.sections.section', 'category') }}"
                                    class="nav-link @if ($current_url == setRoute('admin.setup.sections.section', 'category')) active @endif">
                                    <i class="menu-icon las la-ellipsis-h"></i>
                                    <span class="menu-title">{{ __('Category') }}</span>
                                </a>
                                <a href="{{ setRoute('admin.setup.sections.section', 'blog-section') }}"
                                    class="nav-link @if ($current_url == setRoute('admin.setup.sections.section', 'blog-section')) active @endif">
                                    <i class="menu-icon las la-ellipsis-h"></i>
                                    <span class="menu-title">{{ __('Blog') }}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

                @include('admin.components.side-nav.link', [
                    'route' => 'admin.extensions.index',
                    'title' => 'Extensions',
                    'icon' => 'menu-icon las la-puzzle-piece',
                ])

                @if (admin_permission_by_name('admin.payment.gateway.view'))
                    <li class="sidebar-menu-header">{{ __('Payment Methods') }}</li>
                    @php
                        $payment_add_money_childs = [
                            setRoute('admin.payment.gateway.view', ['add-money', 'automatic']),
                            setRoute('admin.payment.gateway.view', ['add-money', 'manual']),
                        ];
                    @endphp
                    <li class="sidebar-menu-item sidebar-dropdown @if (in_array($current_url, $payment_add_money_childs)) active @endif">
                        <a href="javascript:void(0)">
                            <i class="menu-icon las la-funnel-dollar"></i>
                            <span class="menu-title">{{ __('Add Money') }}</span>
                        </a>
                        <ul class="sidebar-submenu">
                            <li class="sidebar-menu-item">
                                <a href="{{ setRoute('admin.payment.gateway.view', ['add-money', 'automatic']) }}"
                                    class="nav-link @if ($current_url == setRoute('admin.payment.gateway.view', ['add-money', 'automatic'])) active @endif">
                                    <i class="menu-icon las la-ellipsis-h"></i>
                                    <span class="menu-title">{{ __('Automatic') }}</span>
                                </a>
                                <a href="{{ setRoute('admin.payment.gateway.view', ['add-money', 'manual']) }}"
                                    class="nav-link @if ($current_url == setRoute('admin.payment.gateway.view', ['add-money', 'manual'])) active @endif">
                                    <i class="menu-icon las la-ellipsis-h"></i>
                                    <span class="menu-title">{{ __('Manual') }}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

                @include('admin.components.side-nav.link', [
                    'route' => 'admin.useful.links.index',
                    'title' => 'Useful Links',
                    'icon' => 'menu-icon las la-link',
                ])

                <li class="sidebar-menu-header">{{ __('Contact') }}</li>
                <li class="sidebar-menu-item @if (URL::current() == setRoute('admin.subscribers')) active @endif">
                    <a href="{{ route('admin.subscribers') }}">
                        <i class="menu-icon las la-envelope"></i>
                        <span class="menu-title">{{ __('Subscriber List') }}</span>
                    </a>
                </li>
                <li class="sidebar-menu-item @if (URL::current() == setRoute('admin.contacts.index')) active @endif">
                    <a href="{{ route('admin.contacts.index') }}">
                        <i class="menu-icon las la-comment-dots"></i>

                        <span class="menu-title">{{ __('Contact List') }}</span>
                    </a>
                </li>

                {{-- Notifications --}}
                @include('admin.components.side-nav.link-group', [
                    'group_title' => 'Notification',
                    'group_links' => [
                        'dropdown' => [
                            [
                                'title' => 'Push Notification',
                                'icon' => 'menu-icon las la-bell',
                                'links' => [
                                    [
                                        'title' => 'Setup Notification',
                                        'route' => 'admin.push.notification.config',
                                    ],
                                    [
                                        'title' => 'Send Notification',
                                        'route' => 'admin.push.notification.index',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ])

                @php
                    $bonus_routes = ['admin.cookie.index', 'admin.server.info.index', 'admin.cache.clear'];
                @endphp

                @if (admin_permission_by_name_array($bonus_routes))
                    <li class="sidebar-menu-header">{{ __('Bonus') }}</li>
                @endif

                @include('admin.components.side-nav.link', [
                    'route' => 'admin.cookie.index',
                    'title' => 'GDPR Cookie',
                    'icon' => 'menu-icon las la-cookie-bite',
                ])

                @include('admin.components.side-nav.link', [
                    'route' => 'admin.server.info.index',
                    'title' => 'Server Info',
                    'icon' => 'menu-icon las la-sitemap',
                ])

                @include('admin.components.side-nav.link', [
                    'route' => 'admin.cache.clear',
                    'title' => 'Clear Cache',
                    'icon' => 'menu-icon las la-broom',
                ])
            </ul>
        </div>
    </div>
</div>
