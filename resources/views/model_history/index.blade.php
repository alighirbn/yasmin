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
                            <h2>السجل</h2>

                            <!-- Filters Form -->
                            <form method="GET" action="{{ route('history.all') }}" class="mb-4">
                                <div class="input-group">
                                    <!-- Search by keyword -->
                                    <input type="text" name="search" placeholder="Search..."
                                        value="{{ $search ?? '' }}">

                                    <!-- Filter by Date -->
                                    <input type="date" name="start_date" placeholder="Start Date"
                                        value="{{ $startDate ?? '' }}">
                                    <input type="date" name="end_date" placeholder="End Date"
                                        value="{{ $endDate ?? '' }}">

                                    <!-- Filter by User -->
                                    <select name="user_id">
                                        <option value="">Select User</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}"
                                                {{ $userId == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <!-- Search Button -->
                                    <button type="submit" class="btn">بحث</button>
                                </div>
                            </form>

                            <!-- Display Applied Filters -->
                            <div class="mb-4">
                                @if ($search)
                                    <p>Search: "{{ $search }}"</p>
                                @endif

                                @if ($startDate || $endDate)
                                    <p>Filtered by Date:
                                        {{ $startDate ? $startDate : 'Any' }} to {{ $endDate ? $endDate : 'Any' }}
                                    </p>
                                @endif

                                @if ($userId)
                                    <p>Filtered by User: {{ optional($users->find($userId))->name }}</p>
                                @endif
                            </div>

                            <!-- History Logs Table -->
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Model</th>
                                        <th>Model ID</th>
                                        <th>Note</th>
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
                                            <td>{{ $log->note }}</td>
                                            <td>{{ ucfirst($log->action) }}</td>
                                            <td>{{ $log->user->name ?? 'System' }}</td>
                                            <td>
                                                <div class="changes-container">
                                                    @if ($log->action == 'edit')
                                                        <div>
                                                            <strong>البيانات القديمة:</strong>
                                                            <table class="table table-sm">
                                                                @foreach ((is_string($log->old_data) ? json_decode($log->old_data, true) : $log->old_data) ?? [] as $key => $value)
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
                                                                @foreach ((is_string($log->new_data) ? json_decode($log->new_data, true) : $log->new_data) ?? [] as $key => $value)
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
                                                            @foreach ((is_string($log->new_data) ? json_decode($log->new_data, true) : $log->new_data) ?? [] as $key => $value)
                                                                <tr>
                                                                    <td><strong>{{ __('word.' . $key) }}:</strong></td>
                                                                    <td>{{ is_array($value) ? json_encode($value) : $value }}
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </table>
                                                    @elseif ($log->action == 'delete')
                                                        <strong>البيانات القديمة (حذفت):</strong>
                                                        <table class="table table-sm">
                                                            @foreach ((is_string($log->old_data) ? json_decode($log->old_data, true) : $log->old_data) ?? [] as $key => $value)
                                                                <tr>
                                                                    <td><strong>{{ __('word.' . $key) }}:</strong></td>
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
                                {{ $history->appends(request()->query())->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
