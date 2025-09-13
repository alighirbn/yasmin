<x-app-layout>

    <x-slot name="header">
        @include('hr.nav.navigation')
    </x-slot>

    <div class="bg-custom py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="text-xl font-bold mb-4">إضافة حافز/استقطاع</h2>

                    @if ($errors->any())
                        <div class="mb-4">
                            <ul class="list-disc list-inside text-red-500">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('hr.incentives.store') }}" method="POST">
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
                            <label>النوع</label>
                            <select name="type" class="border rounded w-full">
                                <option value="">-- اختر النوع --</option>
                                <option value="incentive">حافز</option>
                                <option value="paycut">استقطاع</option>
                            </select>
                        </div>

                        <div class="mb-2">
                            <label>المبلغ</label>
                            <input type="number" step="0.01" name="amount" class="border rounded w-full">
                        </div>

                        <div class="mb-2">
                            <label>السبب</label>
                            <input type="text" name="reason" class="border rounded w-full">
                        </div>

                        <div class="mb-2">
                            <label>التاريخ</label>
                            <input type="date" name="date" class="border rounded w-full">
                        </div>

                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">حفظ</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
