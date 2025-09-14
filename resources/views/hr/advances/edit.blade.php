<x-app-layout>

    <x-slot name="header">
        @include('hr.nav.navigation')
    </x-slot>

    <div class="bg-custom py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="text-xl font-bold mb-4">تعديل سلفة</h2>

                    @if ($errors->any())
                        <div class="mb-4">
                            <ul class="list-disc list-inside text-red-500">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('hr.advances.update', $advance) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-2">
                            <label>الموظف</label>
                            <select name="employee_id" class="border rounded w-full">
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}"
                                        {{ $advance->employee_id == $employee->id ? 'selected' : '' }}>
                                        {{ $employee->first_name }} {{ $employee->last_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-2">
                            <label>المبلغ</label>
                            <input type="number" step="1000" name="amount" class="border rounded w-full"
                                value="{{ old('amount', $advance->amount) }}">
                        </div>

                        <div class="mb-2">
                            <label>التاريخ</label>
                            <input type="date" name="date" class="border rounded w-full"
                                value="{{ old('date', $advance->date) }}">
                        </div>

                        <div class="mb-2">
                            <label>مُسددة؟</label>
                            <input type="checkbox" name="settled" value="1"
                                {{ $advance->settled ? 'checked' : '' }}>
                        </div>

                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">تحديث</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
