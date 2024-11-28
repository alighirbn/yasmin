@can('history-list')
    <a href="{{ route('history.all') }}" class="me-3">
        {{ __('word.history_all') }}
    </a>
@endcan
@can('history-user')
    <a href="{{ route('history.user') }}" class="me-3">
        {{ __('word.history_user') }}
    </a>
@endcan
