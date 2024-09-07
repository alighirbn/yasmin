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
@can('service-list')
    <a href="{{ route('service.index') }}" class="me-3 {{ request()->routeIs('service.index') ? 'underline-active' : '' }}">
        {{ __('word.service_search') }}
    </a>
@endcan

@can('service-create')
    <a href="{{ route('service.create') }}" class="me-3 {{ request()->routeIs('service.create') ? 'underline-active' : '' }}">
        {{ __('word.service_add') }}
    </a>
@endcan
