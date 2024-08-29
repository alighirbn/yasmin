@can('building-list')
    <a href="{{ route('building.index') }}" class="me-3">
        {{ __('word.building_search') }}
    </a>
@endcan
@can('building-create')
    <a href="{{ route('building.create') }}" class="me-3">
        {{ __('word.building_add') }}
    </a>
@endcan
