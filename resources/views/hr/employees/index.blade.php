<x-app-layout>

    <x-slot name="header">
        @include('hr.nav.navigation')
    </x-slot>

    <div class="bg-custom py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class='text-xl font-bold mb-4'>قائمة الموظفين</h2>
                    <a href="{{ route('hr.employees.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">➕ إضافة
                        موظف</a>
                    <table class='w-full mt-4 border'>
                        <thead>
                            <tr>
                                <th class='border px-2 py-1'>الاسم الأول</th>
                                <th class='border px-2 py-1'>الاسم الأخير</th>
                                <th class='border px-2 py-1'>القسم</th>
                                <th class='border px-2 py-1'>الراتب</th>
                                <th class='border px-2 py-1'>إجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($employees as $item)
                                <tr>
                                    <td class='border px-2 py-1'>{{ $item->first_name }}</td>
                                    <td class='border px-2 py-1'>{{ $item->last_name }}</td>
                                    <td class='border px-2 py-1'>{{ $item->department }}</td>
                                    <td class='border px-2 py-1'>{{ $item->basic_salary }}</td>
                                    <td class='border px-2 py-1'>
                                        <a href="{{ route('hr.employees.edit', $item) }}"
                                            class="text-green-600">تعديل</a> |
                                        <form action="{{ route('hr.employees.destroy', $item) }}" method="POST"
                                            class="inline">@csrf @method('DELETE')<button type="submit"
                                                class="text-red-600">حذف</button></form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
