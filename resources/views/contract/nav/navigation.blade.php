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

@can('contract-list')
    <a href="{{ route('contract.index') }}" class="me-3 {{ request()->routeIs('contract.index') ? 'underline-active' : '' }}">
        {{ __('word.contract_search') }}
    </a>
@endcan

@can('contract-create')
    <a href="{{ route('contract.create') }}"
        class="me-3 {{ request()->routeIs('contract.create') ? 'underline-active' : '' }}">
        {{ __('word.contract_add') }}
    </a>
@endcan

@can('contract-due')
    <a href="{{ route('contract.due') }}" class="me-3 {{ request()->routeIs('contract.due') ? 'underline-active' : '' }}">
        {{ __('word.contract_due') }}
    </a>
@endcan

@can('contract-due')
    <a href="{{ route('contract.dueByDays') }}"
        class="me-3 {{ request()->routeIs('contract.dueByDays') ? 'underline-active' : '' }}">
        الأقساط حسب الأيام
    </a>
@endcan

@can('transfer-list')
    <a href="{{ route('transfer.index') }}"
        class="me-3 {{ request()->routeIs('transfer.index') ? 'underline-active' : '' }}">
        {{ __('word.transfer_search') }}
    </a>
@endcan

@can('transfer-create')
    <a href="{{ route('transfer.create') }}"
        class="me-3 {{ request()->routeIs('transfer.create') ? 'underline-active' : '' }}">
        {{ __('word.transfer_add') }}
    </a>
@endcan
