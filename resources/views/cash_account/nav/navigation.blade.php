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
@can('cash_account-list')
    <a href="{{ route('cash_account.index') }}"
        class="me-3 {{ request()->routeIs('cash_account.index') ? 'underline-active' : '' }}">
        {{ __('word.cash_account_search') }}
    </a>
@endcan

@can('cash_account-create')
    <a href="{{ route('cash_account.create') }}"
        class="me-3 {{ request()->routeIs('cash_account.create') ? 'underline-active' : '' }}">
        {{ __('word.cash_account_add') }}
    </a>
@endcan
