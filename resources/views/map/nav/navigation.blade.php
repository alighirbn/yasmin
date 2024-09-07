<style>
    a.underline-active {
        position: relative;
    }

    a.underline-active::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: -5px;
        /* Adjust this value to control how far down the underline appears */
        width: 100%;
        height: 3px;
        /* Thickness of the underline */
        background-color: #e8f8ff;
        /* Color of the underline */
    }
</style>
@can('map-index')
    <a href="{{ route('map.building') }}" class="me-3 {{ request()->routeIs('map.building') ? 'underline-active' : '' }}">
        {{ __('word.map_buildings') }}
    </a>
@endcan

@can('map-index')
    <a href="{{ route('map.empty_building') }}"
        class="me-3 {{ request()->routeIs('map.empty_building') ? 'underline-active' : '' }}">
        {{ __('word.map_empty_buildings') }}
    </a>
@endcan

@can('map-index')
    <a href="{{ route('map.index') }}" class="me-3 {{ request()->routeIs('map.index') ? 'underline-active' : '' }}">
        {{ __('word.map_contracts') }}
    </a>
@endcan

@can('map-index')
    <a href="{{ route('map.due_installments', ['days_before_due' => 0]) }}"
        class="me-3 {{ request()->fullUrlIs(route('map.due_installments', ['days_before_due' => 0])) ? 'underline-active' : '' }}">
        {{ __('word.map_due_installments_0') }}
    </a>
@endcan

@can('map-index')
    <a href="{{ route('map.due_installments', ['days_before_due' => 30]) }}"
        class="me-3 {{ request()->fullUrlIs(route('map.due_installments', ['days_before_due' => 30])) ? 'underline-active' : '' }}">
        {{ __('word.map_due_installments_30') }}
    </a>
@endcan

@can('map-index')
    <a href="{{ route('map.due_installments', ['days_before_due' => 60]) }}"
        class="me-3 {{ request()->fullUrlIs(route('map.due_installments', ['days_before_due' => 60])) ? 'underline-active' : '' }}">
        {{ __('word.map_due_installments_60') }}
    </a>
@endcan
