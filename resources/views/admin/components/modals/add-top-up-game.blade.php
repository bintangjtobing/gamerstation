@if (admin_permission_by_name('admin.top.up.game.store'))
    <div id="top-up-game-add" class="mfp-hide large">
        <div class="modal-data">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Add Top UP Game') }}</h5>
            </div>
            <div class="modal-form-data">
                <form class="modal-form" method="POST" action="{{ route('admin.top.up.game.store') }}"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row mb-10-none">

                        <div class="col-xl-12 col-lg-12 form-group">
                            @include('admin.components.form.input', [
                                'label' => __('Title') . '*',
                                'name' => 'title',
                                'placeholder' => __('Enter a title'),
                                'type' => 'text',
                                'value' => old('title'),
                            ])
                        </div>
                        <div class="col-xl-6 col-lg-6 form-group">
                            @include('admin.components.form.input', [
                                'label' => __('Google Store') . '*',
                                'name' => 'google_store',
                                'placeholder' => __('Enter a Google Store Game Link'),
                                'type' => 'text',
                                'value' => old('google_store'),
                            ])
                        </div>
                        <div class="col-xl-6 col-lg-6 form-group">
                            @include('admin.components.form.input', [
                                'label' => __('Apple Store') . '*',
                                'name' => 'apple_store',
                                'placeholder' => __('Enter a Apple Store Game Link'),
                                'type' => 'text',
                                'value' => old('apple_store'),
                            ])
                        </div>

                        <div class="col-xl-12 col-lg-12 form-group">
                            @include('admin.components.form.textarea', [
                                'label' => __('Description') . '*',
                                'name' => 'description',
                                'placeholder' => __('Enter a Description'),
                                'value' => old('description'),
                            ])
                        </div>
                        <div class="col-xl-6 col-lg-6 form-group">
                            @include('admin.components.form.input-file', [
                                'label' => __('Feature Image'),
                                'class' => 'file-holder',
                                'name' => 'feature_image',
                                'old_files_path' => files_asset_path('top-up-game'),
                                'old_files' => old('feature_image'),
                            ])
                        </div>
                        <div class="col-xl-6 col-lg-6 form-group">
                            @include('admin.components.form.input-file', [
                                'label' => __('Background Image'),
                                'class' => 'file-holder',
                                'name' => 'background_image',
                                'old_files_path' => files_asset_path('top-up-game'),
                                'old_files' => old('background_image'),
                            ])
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
