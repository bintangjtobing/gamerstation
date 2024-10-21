@if (admin_permission_by_name('admin.add.coin.store'))
    <div id="coin-add" class="mfp-hide large">
        <div class="modal-data">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Add Coin') }}</h5>
            </div>
            <div class="modal-form-data">
                <form class="modal-form" method="POST" action="{{ route('admin.add.coin.store') }}"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row mb-10-none">


                        <div class="col-xl-6 col-lg-6 form-group">
                            @include('admin.components.form.input', [
                                'label' => __('Quantity')."*",
                                'name' => 'coin',
                                'placeholder' => 'Coin',
                                'type' => 'number',
                                'value' => old('coin'),
                            ])
                        </div>

                        <div class="col-xl-6 col-lg-6 form-group">
                            <label>{{ __('Price') }}*</label>
                            <div class="input-group">
                                <input type="number" class="form--control" name="price" placeholder="Price">
                                <span class="input-group-text selcted-currency"> {{ get_default_currency_code() }}
                                </span>
                            </div>
                        </div>

                        <div
                            class="col-xl-12 col-lg-12 form-group d-flex align-items-center justify-content-between mt-4">
                            <button type="button" class="btn btn--danger modal-close">{{ __('Cancel') }}</button>
                            <button type="submit" class="btn btn--base">{{ __('Add') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('script')
        <script>
            openModalWhenError("coin-add", "#coin-add");

            function placeRandomPassword(clickedButton, placeInput) {
                $(clickedButton).click(function() {
                    var generateRandomPassword = makeRandomString(10);
                    $(placeInput).val(generateRandomPassword);
                });
            }
            placeRandomPassword(".rand_password_generator", ".place_random_password");
        </script>
    @endpush
@endif
