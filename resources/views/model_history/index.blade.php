<x-app-layout>
    <x-slot name="header">
        @include('model_history.nav.navigation')
    </x-slot>

    <div class="py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="d-flex-column justify-content-around mt-4">
                        <div class="container">
                            <h2>All History Logs</h2>

                            <!-- Search Form -->
                            <form method="GET" action="{{ route('history.all') }}" class="mb-4">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" placeholder="Search..."
                                        value="{{ $search ?? '' }}">
                                    <button type="submit" class="btn btn-primary">Search</button>
                                </div>
                            </form>

                            <!-- History Logs Table -->
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Model</th>
                                        <th>Model ID</th>
                                        <th>Action</th>
                                        <th>Performed By</th>
                                        <th>Changes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($history as $log)
                                        <tr>
                                            <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                                            <td>{{ class_basename($log->model_type) }}</td>
                                            <td>{{ $log->model_id }}</td>
                                            <td>{{ ucfirst($log->action) }}</td>
                                            <td>{{ $log->user->name ?? 'System' }}</td>
                                            <td>
                                                <div class="changes-container">
                                                    @if ($log->action == 'edit')
                                                        <div>
                                                            <strong>البيانات القديمة:</strong>
                                                            <table class="table table-sm">
                                                                @foreach (json_decode($log->old_data, true) ?? [] as $key => $value)
                                                                    <tr>
                                                                        <td><strong>{{ __('word.' . $key) }}:</strong>
                                                                        </td>
                                                                        <td>{{ is_array($value) ? json_encode($value) : $value }}
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </table>
                                                        </div>
                                                        <div>
                                                            <strong>البيانات الجديدة:</strong>
                                                            <table class="table table-sm">
                                                                @foreach (json_decode($log->new_data, true) ?? [] as $key => $value)
                                                                    <tr>
                                                                        <td><strong>{{ __('word.' . $key) }}:</strong>
                                                                        </td>
                                                                        <td>{{ is_array($value) ? json_encode($value) : $value }}
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </table>
                                                        </div>
                                                    @elseif ($log->action == 'add')
                                                        <strong>البيانات الجديدة:</strong>
                                                        <table class="table table-sm">
                                                            @foreach (json_decode($log->new_data, true) ?? [] as $key => $value)
                                                                <tr>
                                                                    <td><strong>{{ __('word.' . $key) }}:</strong>
                                                                    </td>
                                                                    <td>{{ is_array($value) ? json_encode($value) : $value }}
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </table>
                                                    @elseif ($log->action == 'delete')
                                                        <strong>البيانات القديمة (حذفت):</strong>
                                                        <table class="table table-sm">
                                                            @foreach (json_decode($log->old_data, true) ?? [] as $key => $value)
                                                                <tr>
                                                                    <td><strong>{{ __('word.' . $key) }}:</strong>
                                                                    </td>
                                                                    <td>{{ is_array($value) ? json_encode($value) : $value }}
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </table>
                                                    @endif
                                                </div>
                                            </td>

                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">No history logs found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>

                            <!-- Pagination Links -->
                            <div class="d-flex justify-content-center">
                                {{ $history->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
