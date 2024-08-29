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
