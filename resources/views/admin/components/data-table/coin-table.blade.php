<table class="custom-table admin-search-table">
    <thead>
        <tr>
            <th>{{ __('Coin') }}</th>
            <th>{{ __('Price') }}</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @forelse ($add_coins ?? [] as $item)
            <tr data-item="{{ $item }}">


                <td>{{ $item->coin }}</td>
                <td>{{ $item->price }} {{ get_default_currency_code() }} </td>

                <td>
                    @include('admin.components.link.info-default', [
                        'class' => 'edit-modal-button',
                        'permission' => 'admin.add.coin.update',
                    ])
                    {{-- @if (!$item->isSuperAdmin()) --}}
                    @include('admin.components.link.delete-default', [
                        'class' => 'delete-modal-button',
                        'permission' => 'admin.admins.admin.delete',
                    ])
                    {{-- @endif --}}
                </td>
            </tr>
        @empty
            @include('admin.components.alerts.empty', ['colspan' => 8])
        @endforelse
    </tbody>
</table>
