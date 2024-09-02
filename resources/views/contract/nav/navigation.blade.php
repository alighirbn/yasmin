@can('contract-list')
    <a href="{{ route('contract.index') }}" class="me-3">
        {{ __('word.contract_search') }}
    </a>
@endcan

@can('contract-create')
    <a href="{{ route('contract.create') }}" class="me-3">
        {{ __('word.contract_add') }}
    </a>
@endcan
@can('transfer-list')
    <a href="{{ route('transfer.index') }}" class="me-3">
        {{ __('word.transfer_search') }}
    </a>
@endcan

@can('transfer-create')
    <a href="{{ route('transfer.create') }}" class="me-3">
        {{ __('word.transfer_add') }}
    </a>
@endcan
