@can('service-list')
    <a href="{{ route('service.index') }}" class="me-3">
        {{ __('word.service_search') }}
    </a>
@endcan
@can('service-create')
    <a href="{{ route('service.create') }}" class="me-3">
        {{ __('word.service_add') }}
    </a>
@endcan
