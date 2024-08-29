@can('customer-list')
    <a href="{{ route('customer.index') }}" class="me-3">
        {{ __('word.customer_search') }}
    </a>
@endcan
@can('customer-create')
    <a href="{{ route('customer.create') }}" class="me-3">
        {{ __('word.customer_add') }}
    </a>
@endcan
