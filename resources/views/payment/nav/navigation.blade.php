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
@can('payment-list')
    <a href="{{ route('payment.index') }}" class="me-3 {{ request()->routeIs('payment.index') ? 'underline-active' : '' }}">
        {{ __('word.payment_search') }}
    </a>
@endcan

@can('payment-create')
    <a href="{{ route('payment.create') }}" class="me-3 {{ request()->routeIs('payment.create') ? 'underline-active' : '' }}">
        {{ __('word.payment_add') }}
    </a>
@endcan
