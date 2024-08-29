@can('role-list')
    <a href="{{ route('role.index') }}" class="me-3">
        {{ __('word.role_search') }}
    </a>
@endcan
@can('role-create')
    <a href=" {{ route('role.create') }}" class="me-3">
        {{ __('word.role_add') }}
    </a>
@endcan
