<table class="custom-table admin-search-table">
    <thead>
        <tr>
            <th>{{ __('Profile Image') }}</th>
            <th>{{ __('Title') }}</th>
            <th>{{ __('Create Date') }}</th>
            <th>{{ __('Status') }}</th>
            <th>{{ __('Action') }}</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($top_up_games ?? [] as $item)
            <tr data-item="{{ $item }}">

                <td>
                    <ul class="user-list">
                        <li>
                            <img src="{{ get_image($item->profile_image, 'top-up-game') }}" alt="Profile Image">
                        </li>
                    </ul>

                </td>
                <td>{{ @$item->title }}</td>
                <td>{{ @$item->created_at->format('M d,Y') }}</td>
                <td>
                    @include('admin.components.form.switcher', [
                        'name' => 'status',
                        'value' => $item->status,
                        'options' => [__('Enable') => 1, __('Disable') => 0],
                        'onload' => true,
                        'data_target' => $item->id,
                    ])
                </td>

                <td>
                    <a class="edit-modal-button btn btn--base btn--primary"
                        href="{{ route('admin.top.up.game.edit', $item->slug) }}"><i class="las la-pencil-alt"></i></a>

                    @include('admin.components.link.delete-default', [
                        'class' => 'delete-modal-button',
                        'permission' => 'admin.admins.admin.delete',
                    ])
                </td>
            </tr>
        @empty
            @include('admin.components.alerts.empty', ['colspan' => 8])
        @endforelse
    </tbody>
</table>
