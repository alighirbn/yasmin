<x-app-layout>

    <x-slot name="header">
        @include('hr.nav.navigation')
    </x-slot>

    <div class="bg-custom py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="text-xl font-bold mb-4">تعديل الراتب</h2>

                    @if ($errors->any())
                        <div class="mb-4">
                            <ul class="list-disc list-inside text-red-500">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('hr.payrolls.update', $payroll->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-2">
                            <label>الموظف</label>
                            <input type="text" class="border rounded w-full bg-gray-100"
                                value="{{ $payroll->employee->first_name }} {{ $payroll->employee->last_name }}"
                                disabled>
                        </div>

                        <div class="mb-2">
                            <label>الشهر</label>
                            <input type="number" name="month" class="border rounded w-full" min="1"
                                max="12" value="{{ old('month', $payroll->month) }}">
                        </div>

                        <div class="mb-2">
                            <label>السنة</label>
                            <input type="number" name="year" class="border rounded w-full" min="2000"
                                max="2100" value="{{ old('year', $payroll->year) }}">
                        </div>

                        <div class="mb-2">
                            <label>الراتب الأساسي</label>
                            <input type="text" class="border rounded w-full bg-gray-100"
                                value="{{ $payroll->basic_salary }}" disabled>
                        </div>

                        <div class="mb-2">
                            <label>إجمالي الحوافز</label>
                            <input type="text" class="border rounded w-full bg-gray-100"
                                value="{{ $payroll->total_incentives }}" disabled>
                        </div>

                        <div class="mb-2">
                            <label>إجمالي الخصومات</label>
                            <input type="text" class="border rounded w-full bg-gray-100"
                                value="{{ $payroll->total_deductions }}" disabled>
                        </div>

                        <div class="mb-2">
                            <label>الصافي</label>
                            <input type="text" class="border rounded w-full bg-gray-100"
                                value="{{ $payroll->net_salary }}" disabled>
                        </div>

                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">تحديث</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
