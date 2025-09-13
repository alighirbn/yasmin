<x-app-layout>

    <x-slot name="header">
        @include('hr.nav.navigation')
    </x-slot>

    <div class="bg-custom py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <h2 class="text-xl font-bold mb-4">قائمة السلف</h2>

                    <a href="{{ route('hr.advances.create') }}"
                        class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">
                        ➕ إضافة سلفة
                    </a>

                    <table class="w-full border-collapse border">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="border px-2 py-1">الموظف</th>
                                <th class="border px-2 py-1">المبلغ</th>
                                <th class="border px-2 py-1">التاريخ</th>
                                <th class="border px-2 py-1">مُسددة؟</th>
                                <th class="border px-2 py-1">إجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $item)
                                <tr class="{{ $loop->even ? 'bg-gray-50' : '' }}">
                                    <td class="border px-2 py-1">{{ $item->employee->first_name }}
                                        {{ $item->employee->last_name }}</td>
                                    <td class="border px-2 py-1">{{ number_format($item->amount, 2) }}</td>
                                    <td class="border px-2 py-1">
                                        {{ \Carbon\Carbon::parse($item->date)->format('Y-m-d') }}</td>
                                    <td class="border px-2 py-1">{{ $item->settled ? 'نعم' : 'لا' }}</td>
                                    <td class="border px-2 py-1">
                                        <a href="{{ route('hr.advances.edit', $item) }}"
                                            class="text-green-600 hover:underline">تعديل</a>
                                        |
                                        <form action="{{ route('hr.advances.destroy', $item) }}" method="POST"
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
                        {{ $items->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>

</x-app-layout>
