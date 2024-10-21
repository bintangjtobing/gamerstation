@if (admin_permission_by_name('admin.add.coin.update'))
    <div id="coin-edit" class="mfp-hide large">
        <div class="modal-data">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Edit Coin') }}</h5>
            </div>
            <div class="modal-form-data">
                <form class="modal-form" method="POST" action="{{ route('admin.add.coin.update') }}"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="target" value="{{ old('target') }}">
                    <div class="row mb-10-none">


                        <div class="col-xl-6 col-lg-6 form-group">
                            @include('admin.components.form.input', [
                                'label' => __('Quantity')."*",
                                'name' => 'coin',
                                'placeholder' => 'Coin',
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
                            <button type="submit" class="btn btn--base">{{ __('Update') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('script')
        <script>
            openModalWhenError("coin-edit", "#coin-edit");

            $(document).on("click", ".edit-modal-button", function() {
                var oldData = JSON.parse($(this).parents("tr").attr("data-item"));
                console.log(oldData)
                var editModal = $("#coin-edit");

                editModal.find("form").first().find("input[name=target]").val(oldData.id);
                editModal.find("input[name=coin]").val(oldData.coin);
                editModal.find("input[name=price]").val(oldData.price);
                openModalBySelector("#coin-edit");

            });
        </script>
    @endpush
@endif
