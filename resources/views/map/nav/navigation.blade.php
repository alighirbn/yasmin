@can('map-index')
    <a href="{{ route('map.index') }}" class="me-3">
        {{ __('word.map_contracts') }}
    </a>
@endcan
@can('map-index')
    <a href="{{ route('map.due_installments') }}" class="me-3">
        {{ __('word.map_due_installments') }}
    </a>
@endcan
