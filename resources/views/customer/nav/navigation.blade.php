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
@can('customer-list')
    <a href="{{ route('customer.index') }}" class="me-3 {{ request()->routeIs('customer.index') ? 'underline-active' : '' }}">
        {{ __('word.customer_search') }}
    </a>
@endcan

@can('customer-create')
    <a href="{{ route('customer.create') }}"
        class="me-3 {{ request()->routeIs('customer.create') ? 'underline-active' : '' }}">
        {{ __('word.customer_add') }}
    </a>
@endcan
