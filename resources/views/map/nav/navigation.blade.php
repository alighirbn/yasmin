@can('map-index')
    <a href="{{ route('map.building') }}" class="me-3">
        {{ __('word.map_buildings') }}
    </a>
@endcan
@can('map-index')
    <a href="{{ route('map.empty_building') }}" class="me-3">
        {{ __('word.map_empty_buildings') }}
    </a>
@endcan
@can('map-index')
    <a href="{{ route('map.index') }}" class="me-3">
        {{ __('word.map_contracts') }}
    </a>
@endcan
@can('map-index')
    <a href="{{ route('map.due_installments', ['days_before_due' => 0]) }}" class="me-3">
        {{ __('word.map_due_installments_0') }}
    </a>
@endcan
@can('map-index')
    <a href="{{ route('map.due_installments', ['days_before_due' => 30]) }}" class="me-3">
        {{ __('word.map_due_installments_30') }}
    </a>
@endcan
@can('map-index')
    <a href="{{ route('map.due_installments', ['days_before_due' => 60]) }}" class="me-3">
        {{ __('word.map_due_installments_60') }}
    </a>
@endcan
