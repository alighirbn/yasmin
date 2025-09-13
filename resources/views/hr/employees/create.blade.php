<x-app-layout>
    <x-slot name="header">
        @include('hr.nav.navigation')
    </x-slot>

    <div class="bg-custom py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg bg-white p-6 text-black">

                @if ($message = Session::get('success'))
                    <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
                        {{ $message }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <h2 class="text-xl font-bold mb-4">إضافة موظف جديد</h2>

                <form action="{{ route('hr.employees.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="block mb-1">كود الموظف</label>
                        <input type="text" name="employee_code" value="{{ old('employee_code') }}"
                            class="border rounded w-full px-3 py-2 text-black">
                        @error('employee_code')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block mb-1">الاسم الأول</label>
                        <input type="text" name="first_name" value="{{ old('first_name') }}"
                            class="border rounded w-full px-3 py-2 text-black">
                        @error('first_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block mb-1">الاسم الأخير</label>
                        <input type="text" name="last_name" value="{{ old('last_name') }}"
                            class="border rounded w-full px-3 py-2 text-black">
                        @error('last_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block mb-1">القسم</label>
                        <input type="text" name="department" value="{{ old('department') }}"
                            class="border rounded w-full px-3 py-2 text-black">
                        @error('department')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block mb-1">الوظيفة</label>
                        <input type="text" name="position" value="{{ old('position') }}"
                            class="border rounded w-full px-3 py-2 text-black">
                        @error('position')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block mb-1">الراتب الأساسي</label>
                        <input type="number" name="basic_salary" value="{{ old('basic_salary') }}" step="0.01"
                            class="border rounded w-full px-3 py-2 text-black">
                        @error('basic_salary')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block mb-1">تاريخ التوظيف</label>
                        <input type="date" name="hire_date" value="{{ old('hire_date') }}"
                            class="border rounded w-full px-3 py-2 text-black">
                        @error('hire_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        حفظ الموظف
                    </button>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>
