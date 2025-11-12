<x-app-layout>
    <x-slot name="header">
        @include('model_history.nav.navigation')
        <style>
            body {
                font-family: "Cairo", sans-serif;
                color: #111 !important;
                /* make text fully black */
            }

            /* Force readable text in Tailwind gray shades */
            .text-gray-500,
            .text-gray-600,
            .text-gray-700,
            .text-gray-800,
            .text-gray-900 {
                color: #111 !important;
            }

            /* Ensure contrast inside cards and table cells */
            .bg-white,
            .bg-gray-50,
            .bg-gray-100 {
                color: #111 !important;
            }

            /* Labels and form inputs */
            label {
                color: #222 !important;
            }

            input,
            select {
                color: #000 !important;
            }

            /* Pagination and filters */
            .text-blue-600 {
                color: #0056b3 !important;
            }
        </style>

    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900">سجل النشاطات</h1>
                <p class="mt-2 text-sm text-gray-600">عرض وتتبع جميع التغييرات والأنشطة في النظام</p>
            </div>

            <!-- Filters Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('history.all') }}" id="filterForm">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <!-- Search Input -->
                            <div>
                                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-search text-gray-300"></i> بحث
                                </label>
                                <input type="text" name="search" id="search" placeholder="ابحث في السجلات..."
                                    value="{{ $search ?? '' }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                            </div>

                            <!-- Start Date -->
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-calendar-alt text-gray-300"></i> من تاريخ
                                </label>
                                <input type="date" name="start_date" id="start_date" value="{{ $startDate ?? '' }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                            </div>

                            <!-- End Date -->
                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-calendar-alt text-gray-300"></i> إلى تاريخ
                                </label>
                                <input type="date" name="end_date" id="end_date" value="{{ $endDate ?? '' }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                            </div>

                            <!-- User Filter -->
                            <div>
                                <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-user text-gray-300"></i> المستخدم
                                </label>
                                <select name="user_id" id="user_id"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                                    <option value="">جميع المستخدمين</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}"
                                            {{ $userId == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-3 mt-6">
                            <button type="submit"
                                class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-sm transition duration-150 ease-in-out">
                                <i class="fas fa-filter"></i> تطبيق الفلاتر
                            </button>
                            <a href="{{ route('history.all') }}"
                                class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition duration-150 ease-in-out">
                                <i class="fas fa-redo"></i> إعادة تعيين
                            </a>
                        </div>
                    </form>

                    <!-- Active Filters Display -->
                    @if ($search || $startDate || $endDate || $userId)
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <div class="flex flex-wrap gap-2">
                                <span class="text-sm font-medium text-gray-700">الفلاتر النشطة:</span>

                                @if ($search)
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-blue-100 text-blue-800">
                                        <i class="fas fa-search mr-2"></i> {{ $search }}
                                        <a href="{{ route('history.all', array_merge(request()->except('search'))) }}"
                                            class="mr-2">
                                            <i class="fas fa-times cursor-pointer"></i>
                                        </a>
                                    </span>
                                @endif

                                @if ($startDate || $endDate)
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-green-100 text-green-800">
                                        <i class="fas fa-calendar mr-2"></i>
                                        {{ $startDate ?: 'البداية' }} - {{ $endDate ?: 'النهاية' }}
                                        <a href="{{ route('history.all', array_merge(request()->except(['start_date', 'end_date']))) }}"
                                            class="mr-2">
                                            <i class="fas fa-times cursor-pointer"></i>
                                        </a>
                                    </span>
                                @endif

                                @if ($userId)
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-purple-100 text-purple-800">
                                        <i class="fas fa-user mr-2"></i>
                                        {{ optional($users->find($userId))->name }}
                                        <a href="{{ route('history.all', array_merge(request()->except('user_id'))) }}"
                                            class="mr-2">
                                            <i class="fas fa-times cursor-pointer"></i>
                                        </a>
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Results Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Results Count -->
                    <div class="mb-4 flex justify-between items-center">
                        <p class="text-sm text-gray-600">
                            عرض <span class="font-semibold">{{ $history->firstItem() ?? 0 }}</span>
                            إلى <span class="font-semibold">{{ $history->lastItem() ?? 0 }}</span>
                            من <span class="font-semibold">{{ $history->total() }}</span> سجل
                        </p>
                    </div>

                    <!-- History Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        التاريخ والوقت
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        النموذج
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        الإجراء
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        المستخدم
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        ملاحظات
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        التفاصيل
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($history as $log)
                                    <tr class="hover:bg-gray-50 transition">
                                        <!-- Date -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ $log->created_at->format('Y-m-d') }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                {{ $log->created_at->format('H:i:s') }}
                                            </div>
                                        </td>

                                        <!-- Model -->
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ class_basename($log->model_type) }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                ID: {{ $log->model_id }}
                                            </div>
                                        </td>

                                        <!-- Action Badge -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $actionColors = [
                                                    'add' => 'bg-green-100 text-green-800',
                                                    'edit' => 'bg-blue-100 text-blue-800',
                                                    'delete' => 'bg-red-100 text-red-800',
                                                ];
                                                $actionIcons = [
                                                    'add' => 'fa-plus',
                                                    'edit' => 'fa-edit',
                                                    'delete' => 'fa-trash',
                                                ];
                                            @endphp
                                            <span
                                                class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $actionColors[$log->action] ?? 'bg-gray-100 text-gray-800' }}">
                                                <i class="fas {{ $actionIcons[$log->action] ?? 'fa-info' }} ml-1"></i>
                                                {{ ucfirst($log->action) }}
                                            </span>
                                        </td>

                                        <!-- User -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div
                                                    class="flex-shrink-0 h-8 w-8 bg-gray-200 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-user text-gray-500"></i>
                                                </div>
                                                <div class="mr-3">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $log->user->name ?? 'النظام' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Note -->
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900 max-w-xs truncate"
                                                title="{{ $log->note }}">
                                                {{ $log->note ?: '-' }}
                                            </div>
                                        </td>

                                        <!-- Details Button -->
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <button type="button"
                                                onclick="toggleDetails('details-{{ $log->id }}')"
                                                class="text-blue-600 hover:text-blue-900 font-medium">
                                                <i class="fas fa-eye"></i> عرض
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Details Row (Hidden by default) -->
                                    <tr id="details-{{ $log->id }}" class="hidden bg-gray-50">
                                        <td colspan="6" class="px-6 py-4">
                                            <div class="space-y-4">
                                                @if ($log->action == 'edit')
                                                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                                        <!-- Old Data -->
                                                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                                                            <h4
                                                                class="text-sm font-semibold text-red-800 mb-3 flex items-center">
                                                                <i class="fas fa-arrow-left ml-2"></i>
                                                                البيانات القديمة
                                                            </h4>
                                                            <div class="space-y-2">
                                                                @foreach ((is_string($log->old_data) ? json_decode($log->old_data, true) : $log->old_data) ?? [] as $key => $value)
                                                                    <div class="flex justify-between text-sm">
                                                                        <span
                                                                            class="font-medium text-gray-700">{{ __('word.' . $key) }}:</span>
                                                                        <span
                                                                            class="text-gray-900">{{ is_array($value) ? json_encode($value, JSON_UNESCAPED_UNICODE) : $value }}</span>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>

                                                        <!-- New Data -->
                                                        <div
                                                            class="bg-green-50 border border-green-200 rounded-lg p-4">
                                                            <h4
                                                                class="text-sm font-semibold text-green-800 mb-3 flex items-center">
                                                                <i class="fas fa-arrow-right ml-2"></i>
                                                                البيانات الجديدة
                                                            </h4>
                                                            <div class="space-y-2">
                                                                @foreach ((is_string($log->new_data) ? json_decode($log->new_data, true) : $log->new_data) ?? [] as $key => $value)
                                                                    <div class="flex justify-between text-sm">
                                                                        <span
                                                                            class="font-medium text-gray-700">{{ __('word.' . $key) }}:</span>
                                                                        <span
                                                                            class="text-gray-900">{{ is_array($value) ? json_encode($value, JSON_UNESCAPED_UNICODE) : $value }}</span>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                @elseif ($log->action == 'add')
                                                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                                        <h4
                                                            class="text-sm font-semibold text-green-800 mb-3 flex items-center">
                                                            <i class="fas fa-plus-circle ml-2"></i>
                                                            البيانات المضافة
                                                        </h4>
                                                        <div class="space-y-2">
                                                            @foreach ((is_string($log->new_data) ? json_decode($log->new_data, true) : $log->new_data) ?? [] as $key => $value)
                                                                <div class="flex justify-between text-sm">
                                                                    <span
                                                                        class="font-medium text-gray-700">{{ __('word.' . $key) }}:</span>
                                                                    <span
                                                                        class="text-gray-900">{{ is_array($value) ? json_encode($value, JSON_UNESCAPED_UNICODE) : $value }}</span>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @elseif ($log->action == 'delete')
                                                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                                                        <h4
                                                            class="text-sm font-semibold text-red-800 mb-3 flex items-center">
                                                            <i class="fas fa-trash-alt ml-2"></i>
                                                            البيانات المحذوفة
                                                        </h4>
                                                        <div class="space-y-2">
                                                            @foreach ((is_string($log->old_data) ? json_decode($log->old_data, true) : $log->old_data) ?? [] as $key => $value)
                                                                <div class="flex justify-between text-sm">
                                                                    <span
                                                                        class="font-medium text-gray-700">{{ __('word.' . $key) }}:</span>
                                                                    <span
                                                                        class="text-gray-900 line-through">{{ is_array($value) ? json_encode($value, JSON_UNESCAPED_UNICODE) : $value }}</span>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center justify-center">
                                                <i class="fas fa-inbox text-gray-300 text-5xl mb-4"></i>
                                                <p class="text-gray-500 text-lg">لا توجد سجلات مطابقة</p>
                                                <p class="text-gray-100 text-sm mt-2">جرب تغيير معايير البحث</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if ($history->hasPages())
                        <div class="mt-6">
                            {{ $history->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for Toggle Details -->
    <script>
        function toggleDetails(id) {
            const element = document.getElementById(id);
            element.classList.toggle('hidden');
        }
    </script>

    <!-- Font Awesome (if not already included) -->
    @push('styles')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @endpush
</x-app-layout>
