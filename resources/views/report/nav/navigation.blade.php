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
@can('report-category')
    <a href="{{ route('report.category') }}"
        class="me-3 {{ request()->routeIs('report.category') ? 'underline-active' : '' }}">
        {{ __('word.report_category') }}
    </a>
@endcan

@can('report-due_installments')
    <a href="{{ route('report.due_installments') }}"
        class="me-3 {{ request()->routeIs('report.due_installments') ? 'underline-active' : '' }}">
        {{ __('word.report_due_installments') }}
    </a>
@endcan

@can('report-first_installment')
    <a href="{{ route('report.first_installment') }}"
        class="me-3 {{ request()->routeIs('report.first_installment') ? 'underline-active' : '' }}">
        {{ __('word.report_first_installment') }}
    </a>
@endcan
