<x-app-layout>

    <x-slot name="header">
        @include('hr.nav.navigation')
    </x-slot>

    <div class="bg-custom py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class='text-xl font-bold mb-4'>قائمة الموظفين</h2>
                    <a href="{{ route('hr.employees.create') }}"
                        class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">➕ إضافة موظف</a>
                    <a href="{{ route('hr.employees.active') }}"
                        class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block ml-2">👥 الموظفين بالخدمة</a>
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
                                    <td class='border px-2 py-1'>{{ number_format($item->basic_salary, 0) }}</td>
                                    <td class='border px-2 py-1 space-x-2'>
                                        <!-- View Employee -->
                                        <a href="{{ route('hr.employees.show', $item) }}"
                                            class="text-blue-600 hover:underline">عرض</a> |
                                        <!-- Edit Employee -->
                                        <a href="{{ route('hr.employees.edit', $item) }}"
                                            class="text-green-600 hover:underline">تعديل</a> |
                                        <!-- Scan Employee -->
                                        <a href="{{ route('hr.employees.scan.create', $item) }}"
                                            class="text-purple-600 hover:underline">مسح ضوئي</a> |
                                        <!-- Archive Employee -->
                                        <a href="{{ route('hr.employees.archive.show', $item) }}"
                                            class="text-yellow-600 hover:underline">أرشيف</a> |
                                        <!-- Delete Employee -->
                                        <form action="{{ route('hr.employees.destroy', $item) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline"
                                                onclick="return confirm('هل أنت متأكد من حذف هذا الموظف؟')">حذف</button>
                                        </form>
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
