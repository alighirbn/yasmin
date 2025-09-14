<x-app-layout>

    <x-slot name="header">
        @include('hr.nav.navigation')
    </x-slot>

    <div class="bg-custom py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="text-xl font-bold mb-4">قائمة الرواتب</h2>

                    <!-- Navigation Buttons -->
                    <nav class="flex gap-4 mb-6">
                        <a href="{{ route('hr.payrolls.generateAll') }}"
                            class="bg-blue-500 text-white px-6 py-3 rounded inline-block"
                            onclick="return confirm('هل أنت متأكد من إنشاء الرواتب لجميع الموظفين لهذا الشهر؟');">
                            🧾 إنشاء رواتب جميع الموظفين للشهر الحالي
                        </a>

                        <a href="{{ route('hr.payrolls.create') }}"
                            class="bg-blue-500 text-white px-6 py-3 rounded inline-block">
                            ➕ إضافة راتب
                        </a>

                        <a href="{{ route('hr.payrolls.currentMonth') }}"
                            class="bg-blue-500 text-white px-6 py-3 rounded inline-block">
                            📊 رواتب الشهر الحالي حسب القسم
                        </a>
                    </nav>

                    <!-- Filter Form -->
                    <form method="GET" action="{{ route('hr.payrolls.index') }}"
                        class="mb-6 bg-gray-50 p-6 rounded-lg border shadow-sm">

                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                            <!-- Name -->
                            <div>
                                <label class="block text-sm font-medium mb-2">اسم الموظف</label>
                                <input type="text" name="name" value="{{ request('name') }}"
                                    class="border rounded-lg px-3 py-2 w-full focus:ring focus:ring-blue-200">
                            </div>

                            <!-- Month -->
                            <div>
                                <label class="block text-sm font-medium mb-2">الشهر</label>
                                <select name="month"
                                    class="border rounded-lg px-3 py-2 w-full focus:ring focus:ring-blue-200">
                                    <option value="">الكل</option>
                                    @for ($m = 1; $m <= 12; $m++)
                                        <option value="{{ $m }}"
                                            {{ request('month') == $m ? 'selected' : '' }}>
                                            {{ $m }}
                                        </option>
                                    @endfor
                                </select>
                            </div>

                            <!-- Year -->
                            <div>
                                <label class="block text-sm font-medium mb-2">السنة</label>
                                <select name="year"
                                    class="border rounded-lg px-3 py-2 w-full focus:ring focus:ring-blue-200">
                                    <option value="">الكل</option>
                                    @for ($y = now()->year; $y >= 2000; $y--)
                                        <option value="{{ $y }}"
                                            {{ request('year') == $y ? 'selected' : '' }}>
                                            {{ $y }}
                                        </option>
                                    @endfor
                                </select>
                            </div>

                            <!-- Buttons -->
                            <div class="flex items-end gap-3">
                                <button type="submit"
                                    class="bg-blue-500 hover:bg-blue-600 text-white px-5 py-2 rounded-lg shadow">
                                    🔍 بحث
                                </button>

                                <a href="{{ route('hr.payrolls.index') }}"
                                    class="bg-blue-500 hover:bg-gray-500 text-white px-5 py-2 rounded-lg shadow">
                                    🔄 إعادة تعيين
                                </a>
                            </div>
                        </div>
                    </form>

                    <!-- Payrolls Table -->
                    <table class="w-full mt-4 border-collapse border">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="border px-2 py-1">الموظف</th>
                                <th class="border px-2 py-1">الشهر</th>
                                <th class="border px-2 py-1">السنة</th>
                                <th class="border px-2 py-1">الراتب الأساسي</th>
                                <th class="border px-2 py-1">إجمالي الحوافز</th>
                                <th class="border px-2 py-1">إجمالي الخصومات</th>
                                <th class="border px-2 py-1">الصافي</th>
                                <th class="border px-2 py-1">إجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($payrolls as $item)
                                <tr class="{{ $loop->even ? 'bg-gray-50' : '' }}">
                                    <td class="border px-2 py-1">
                                        {{ $item->employee->first_name }} {{ $item->employee->last_name }}
                                    </td>
                                    <td class="border px-2 py-1">{{ $item->month }}</td>
                                    <td class="border px-2 py-1">{{ $item->year }}</td>
                                    <td class="border px-2 py-1">{{ number_format($item->basic_salary, 0) }}</td>
                                    <td class="border px-2 py-1">{{ number_format($item->total_incentives, 0) }}</td>
                                    <td class="border px-2 py-1">{{ number_format($item->total_deductions, 0) }}</td>
                                    <td class="border px-2 py-1 font-bold">{{ number_format($item->net_salary, 0) }}
                                    </td>
                                    <td class="border px-2 py-1">
                                        <a href="{{ route('hr.payrolls.show', $item) }}"
                                            class="text-blue-600 hover:underline">عرض</a>
                                        |
                                        <a href="{{ route('hr.payrolls.edit', $item) }}"
                                            class="text-green-600 hover:underline">تعديل</a>
                                        |
                                        <form action="{{ route('hr.payrolls.destroy', $item) }}" method="POST"
                                            class="inline" onsubmit="return confirm('هل أنت متأكد من الحذف؟');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline">حذف</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-gray-500">لا توجد بيانات مطابقة</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $payrolls->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>

</x-app-layout>
