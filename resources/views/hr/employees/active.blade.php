<x-app-layout>

    <x-slot name="header">
        @include('hr.nav.navigation')
    </x-slot>

    <div class="bg-custom py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="text-xl font-bold mb-4">قائمة الموظفين بالخدمة</h2>
                    <a href="{{ route('hr.employees.index') }}"
                        class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block ml-2">👥 جميع الموظفين </a>
                    <table class="w-full mt-4 border">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="border px-2 py-1">الكود</th>
                                <th class="border px-2 py-1">الاسم الأول</th>
                                <th class="border px-2 py-1">الاسم الأخير</th>
                                <th class="border px-2 py-1">القسم</th>
                                <th class="border px-2 py-1">الراتب</th>
                                <th class="border px-2 py-1">إجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($employees as $item)
                                <tr>
                                    <td class="border px-2 py-1">{{ $item->employee_code }}</td>
                                    <td class="border px-2 py-1">{{ $item->first_name }}</td>
                                    <td class="border px-2 py-1">{{ $item->last_name }}</td>
                                    <td class="border px-2 py-1">{{ $item->department ?? 'غير محدد' }}</td>
                                    <td class="border px-2 py-1">{{ number_format($item->basic_salary, 0) }}</td>
                                    <td class="border px-2 py-1 space-x-2">
                                        <a href="{{ route('hr.employees.show', $item) }}"
                                            class="text-blue-600 hover:underline">عرض</a> |
                                        <a href="{{ route('hr.employees.edit', $item) }}"
                                            class="text-green-600 hover:underline">تعديل</a> |
                                        <a href="{{ route('hr.employees.scan.create', $item) }}"
                                            class="text-purple-600 hover:underline">مسح ضوئي</a> |
                                        <a href="{{ route('hr.employees.archive.show', $item) }}"
                                            class="text-yellow-600 hover:underline">أرشيف</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $employees->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
