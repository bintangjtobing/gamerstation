    @isset($buy_coins)
        @forelse ($buy_coins as $item)
            <div class="dashboard-list-item-wrapper">
                <div class="dashboard-list-item sent">
                    <div class="dashboard-list-left">
                        <div class="dashboard-list-user-wrapper">
                            <div class="dashboard-list-user-icon">
                                <i class="las la-arrow-up"></i>
                            </div>
                            <div class="dashboard-list-user-content">
                                <h4 class="title">{{ __('Buy Coin') }} </h4>
                                <span class="{{ $item->StringStatus->class }}">{{ $item->StringStatus->value }}</span>
                            </div>


                        </div>
                    </div>
                    <div class="dashboard-list-right">

                        <h4 class="main-money text--base">
                            {{ get_amount($item->price, get_default_currency_code()) }}</h4>
                        <h6 class="exchange-money">{{ get_amount($item->total_amount, get_default_currency_code()) }}
                        </h6>
                    </div>
                </div>
                <div class="preview-list-wrapper">
                    <div class="preview-list-item">
                        <div class="preview-list-left">
                            <div class="preview-list-user-wrapper">
                                <div class="preview-list-user-icon">
                                    <i class="las la-clock"></i>
                                </div>
                                <div class="preview-list-user-content">
                                    <span>{{ __('Time & Date') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="preview-list-right">
                            <span>{{ dateFormat('d M Y, h:i:s A', $item->created_at) }}</span>
                        </div>
                    </div>

                    <div class="preview-list-item">
                        <div class="preview-list-left">
                            <div class="preview-list-user-wrapper">
                                <div class="preview-list-user-icon">
                                    <i class="las la-exchange-alt"></i>
                                </div>
                                <div class="preview-list-user-content">
                                    <span>{{ __('Login Vender') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="preview-list-right">

                            <span>{{ $item->login_vender }}</span>
                        </div>
                    </div>
                    <div class="preview-list-item">
                        <div class="preview-list-left">
                            <div class="preview-list-user-wrapper">
                                <div class="preview-list-user-icon">
                                    <i class="las la-exchange-alt"></i>
                                </div>
                                <div class="preview-list-user-content">
                                    <span>{{ __('User/Email') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="preview-list-right">

                            <span>{{ $item->username_or_email }}</span>
                        </div>
                    </div>
                    <div class="preview-list-item">
                        <div class="preview-list-left">
                            <div class=" preview-list-user-wrapper">
                                <div class="preview-list-user-icon">
                                    <i class="las la-battery-half"></i>
                                </div>
                                <div class="preview-list-user-content">
                                    <span>{{ __('Fees & Charge') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="preview-list-right">
                            <span class="text--danger">{{ $item->total_amount - $item->price }}</span>
                        </div>
                    </div>
                    <div class="preview-list-item">
                        <div class="preview-list-left">
                            <div class="preview-list-user-wrapper">
                                <div class="preview-list-user-icon">
                                    <i class="las la-exchange-alt"></i>
                                </div>
                                <div class="preview-list-user-content">
                                    <span>{{ __('Coin') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="preview-list-right">

                            <span>{{ $item->coin }}</span>
                        </div>
                    </div>
                    
                    <div class="preview-list-item">
                        <div class="preview-list-left">
                            <div class="preview-list-user-wrapper">
                                <div class="preview-list-user-icon">
                                    <i class="las la-smoking"></i>
                                </div>
                                <div class="preview-list-user-content">
                                    <span>{{ __('Status') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="preview-list-right">
                            <span class="{{ $item->StringStatus->class }}">{{ $item->StringStatus->value }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="alert alert-primary text-center">
                {{ __('No Record Found!') }}
            </div>
        @endforelse
    @endisset
