<x-app-layout>
    <x-slot name="header">
        @include('hr.nav.navigation')
    </x-slot>

    <div class="bg-custom py-6">
        <div class="max-w-lg mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-900">

                <h2 class="text-xl font-bold mb-4">إضافة إنهاء خدمة</h2>

                <form action="{{ route('hr.terminations.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="block mb-1">الموظف</label>
                        <select name="employee_id" class="border rounded w-full p-2">
                            <option value="">اختر الموظف</option>
                            @foreach ($employees as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->full_name }}</option>
                            @endforeach
                        </select>
                        @error('employee_id')
                            <span class="text-red-600">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block mb-1">تاريخ الإنهاء</label>
                        <input type="date" name="termination_date" class="border rounded w-full p-2">
                        @error('termination_date')
                            <span class="text-red-600">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block mb-1">السبب</label>
                        <textarea name="reason" class="border rounded w-full p-2" rows="3"></textarea>
                        @error('reason')
                            <span class="text-red-600">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">حفظ</button>

                </form>

            </div>
        </div>
    </div>
</x-app-layout>
