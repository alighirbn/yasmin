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
@can('expense-list')
    <a href="{{ route('expense.index') }}" class="me-3 {{ request()->routeIs('expense.index') ? 'underline-active' : '' }}">
        {{ __('word.expense_search') }}
    </a>
@endcan

@can('expense-create')
    <a href="{{ route('expense.create') }}" class="me-3 {{ request()->routeIs('expense.create') ? 'underline-active' : '' }}">
        {{ __('word.expense_add') }}
    </a>
@endcan
