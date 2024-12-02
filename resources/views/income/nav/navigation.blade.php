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

@can('income-list')
    <a href="{{ route('income.index') }}"
        class="me-3 {{ request()->routeIs('income.index') && !request('onlyPending') ? 'underline-active' : '' }}">
        {{ __('word.income_search') }}
    </a>
@endcan

@can('income-create')
    <a href="{{ route('income.create') }}" class="me-3 {{ request()->routeIs('income.create') ? 'underline-active' : '' }}">
        {{ __('word.income_add') }}
    </a>
@endcan

@can('income-list')
    <a href="{{ route('income.index', ['onlyPending' => true]) }}"
        class="me-3 {{ request()->routeIs('income.index') && request('onlyPending') ? 'underline-active' : '' }}">
        {{ __('word.income_pending') }}
    </a>
@endcan
