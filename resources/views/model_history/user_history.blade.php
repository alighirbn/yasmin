<x-app-layout>

    <x-slot name="header">
        <!-- app css-->
        <link rel="stylesheet" type="text/css" href="{{ url('/css/app.css') }}" />
        @include('model_history.nav.navigation')

    </x-slot>

    <div class="py-4">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="container">
                        <h2>Your History Logs</h2>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Model</th>
                                    <th>Model ID</th>
                                    <th>Action</th>
                                    <th>Changes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($history as $log)
                                    <tr>
                                        <td>{{ $log->created_at }}</td>
                                        <td>{{ class_basename($log->model_type) }}</td>
                                        <td>{{ $log->model_id }}</td>
                                        <td>{{ $log->action }}</td>
                                        <td>
                                            @if ($log->action == 'edit')
                                                <strong>Old Data:</strong>
                                                <pre>{!! nl2br(e($log->old_data)) !!}</pre>
                                                <strong>New Data:</strong>
                                                <pre>{!! nl2br(e($log->new_data)) !!}</pre>
                                            @elseif($log->action == 'add')
                                                <strong>New Data:</strong>
                                                <pre>{!! nl2br(e($log->new_data)) !!}</pre>
                                            @else
                                                <strong>Old Data (Deleted):</strong>
                                                <pre>{!! nl2br(e($log->old_data)) !!}</pre>
                                            @endif

                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5">You have no history logs.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
