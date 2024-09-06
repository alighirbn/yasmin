@can('payment-list')
    <a href="{{ route('payment.index') }}" class="me-3">
        {{ __('word.payment_search') }}
    </a>
@endcan
@can('payment-create')
    <a href="{{ route('payment.create') }}" class="me-3">
        {{ __('word.payment_add') }}
    </a>
@endcan
