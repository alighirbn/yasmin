<x-app-layout>

    <x-slot name="header">
        @include('hr.nav.navigation')
    </x-slot>

    <div class="bg-custom py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="text-xl font-bold mb-4">قائمة الرواتب</h2>

                    <a href="{{ route('hr.payrolls.create') }}"
                        class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">
                        ➕ إضافة راتب
                    </a>
                    <a href="{{ route('hr.payrolls.currentMonth') }}"
                        class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">
                        📊 رواتب الشهر الحالي حسب القسم
                    </a>
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
                            @foreach ($payrolls as $item)
                                <tr class="{{ $loop->even ? 'bg-gray-50' : '' }}">
                                    <td class="border px-2 py-1">{{ $item->employee->first_name }}
                                        {{ $item->employee->last_name }}</td>
                                    <td class="border px-2 py-1">{{ $item->month }}</td>
                                    <td class="border px-2 py-1">{{ $item->year }}</td>
                                    <td class="border px-2 py-1">{{ number_format($item->basic_salary, 2) }}</td>
                                    <td class="border px-2 py-1">{{ number_format($item->total_incentives, 2) }}</td>
                                    <td class="border px-2 py-1">{{ number_format($item->total_deductions, 2) }}</td>
                                    <td class="border px-2 py-1 font-bold">{{ number_format($item->net_salary, 2) }}
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
                            @endforeach
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
