<x-app-layout>

    <x-slot name="header">
        @include('hr.nav.navigation')
    </x-slot>

    <div class="bg-custom py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="text-xl font-bold mb-4">إضافة راتب</h2>

                    @if ($errors->any())
                        <div class="mb-4">
                            <ul class="list-disc list-inside text-red-500">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('hr.payrolls.store') }}" method="POST">
                        @csrf

                        <div class="mb-2">
                            <label>الموظف</label>
                            <select name="employee_id" class="border rounded w-full">
                                <option value="">-- اختر موظف --</option>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}">{{ $employee->first_name }}
                                        {{ $employee->last_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-2">
                            <label>الشهر</label>
                            <input type="number" name="month" class="border rounded w-full" min="1"
                                max="12">
                        </div>

                        <div class="mb-2">
                            <label>السنة</label>
                            <input type="number" name="year" class="border rounded w-full" min="2000"
                                max="2100">
                        </div>

                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">حفظ</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
