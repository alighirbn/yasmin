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
    <a href="{{ route('payment.index') }}"
        class="me-3 {{ request()->routeIs('payment.index') && !request()->has('onlyPending') ? 'underline-active' : '' }}">
        {{ __('word.payment_search') }}
    </a>
@endcan

@can('payment-create')
    <a href="{{ route('payment.create') }}" class="me-3 {{ request()->routeIs('payment.create') ? 'underline-active' : '' }}">
        {{ __('word.payment_add') }}
    </a>
@endcan

@can('payment-list')
    <a href="{{ route('payment.index', ['onlyPending' => true]) }}"
        class="me-3 {{ request()->routeIs('payment.index') && request()->has('onlyPending') ? 'underline-active' : '' }}">
        {{ __('word.payment_pending') }}
    </a>
@endcan

@can('cash_account-list')
    <a href="{{ route('payment.report') }}"
        class="me-3 {{ request()->routeIs('payment.report') ? 'underline-active' : '' }}">
        {{ __('word.payment_report') }}
    </a>
@endcan
