@can('user-list')
    <a href="{{ route('user.index') }}" class="me-3">
        {{ __('word.user_search') }}
    </a>
@endcan
@can('user-create')
    <a href="{{ route('user.create') }}" class="me-3">
        {{ __('word.user_add') }}
    </a>
@endcan
