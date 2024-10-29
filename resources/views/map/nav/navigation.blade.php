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
@can('map-map')
    <a href="{{ route('map.map') }}" class="me-3 {{ request()->routeIs('map.map') ? 'underline-active' : '' }}">
        {{ __('word.map_buildings') }}
    </a>
@endcan

@can('map-draw')
    <a href="{{ route('map.draw') }}" class="me-3 {{ request()->routeIs('map.draw') ? 'underline-active' : '' }}">
        {{ __('word.map_draw') }}
    </a>
@endcan
@can('map-empty')
    <a href="{{ route('map.empty') }}" class="me-3 {{ request()->routeIs('map.empty') ? 'underline-active' : '' }}">
        {{ __('word.map_empty_buildings') }}
    </a>
@endcan

@can('map-contract')
    <a href="{{ route('map.contract') }}" class="me-3 {{ request()->routeIs('map.contract') ? 'underline-active' : '' }}">
        {{ __('word.map_contracts') }}
    </a>
@endcan

@can('map-due')
    <a href="{{ route('map.due', ['days_before_due' => 0]) }}"
        class="me-3 {{ request()->fullUrlIs(route('map.due', ['days_before_due' => 0])) ? 'underline-active' : '' }}">
        {{ __('word.map_due_installments_0') }}
    </a>
@endcan

@can('map-due')
    <a href="{{ route('map.due', ['days_before_due' => 30]) }}"
        class="me-3 {{ request()->fullUrlIs(route('map.due', ['days_before_due' => 30])) ? 'underline-active' : '' }}">
        {{ __('word.map_due_installments_30') }}
    </a>
@endcan

@can('map-due')
    <a href="{{ route('map.due', ['days_before_due' => 60]) }}"
        class="me-3 {{ request()->fullUrlIs(route('map.due', ['days_before_due' => 60])) ? 'underline-active' : '' }}">
        {{ __('word.map_due_installments_60') }}
    </a>
@endcan
@can('map-hidden')
    <a href="{{ route('map.hidden') }}" class="me-3 {{ request()->routeIs('map.hidden') ? 'underline-active' : '' }}">
        {{ __('word.map_hidden') }}
    </a>
@endcan
@can('map-edit')
    <a href="{{ route('map.edit') }}" class="me-3 {{ request()->routeIs('map.edit') ? 'underline-active' : '' }}">
        {{ __('word.map_edit') }}
    </a>
@endcan
