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

@can('cash_transfer-list')
    <a href="{{ route('cash_transfer.index') }}"
        class="me-3 {{ request()->routeIs('cash_transfer.index') && !request('onlyPending') ? 'underline-active' : '' }}">
        {{ __('word.cash_transfer_search') }}
    </a>
@endcan

@can('cash_transfer-create')
    <a href="{{ route('cash_transfer.create') }}"
        class="me-3 {{ request()->routeIs('cash_transfer.create') ? 'underline-active' : '' }}">
        {{ __('word.cash_transfer_add') }}
    </a>
@endcan

@can('cash_transfer-list')
    <a href="{{ route('cash_transfer.index', ['onlyPending' => true]) }}"
        class="me-3 {{ request()->routeIs('cash_transfer.index') && request('onlyPending') ? 'underline-active' : '' }}">
        {{ __('word.cash_transfer_pending') }}
    </a>
@endcan
