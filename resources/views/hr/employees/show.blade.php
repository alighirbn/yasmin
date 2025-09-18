<x-app-layout>

    <x-slot name="header">
        @include('hr.nav.navigation')
    </x-slot>

    <div class="bg-custom py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="text-xl font-bold mb-4">تفاصيل الموظف</h2>

                    <ul class="space-y-2">
                        <li><strong>الكود الوظيفي:</strong> {{ $employee->employee_code }}</li>
                        <li><strong>الاسم الأول:</strong> {{ $employee->first_name }}</li>
                        <li><strong>الاسم الأخير:</strong> {{ $employee->last_name }}</li>
                        <li><strong>القسم:</strong> {{ $employee->department ?? 'غير محدد' }}</li>
                        <li><strong>المنصب:</strong> {{ $employee->position ?? 'غير محدد' }}</li>
                        <li><strong>الراتب الأساسي:</strong> {{ number_format($employee->basic_salary, 0) }} دينار</li>
                        <li><strong>تاريخ التعيين:</strong>
                            {{ $employee->hire_date ? \Carbon\Carbon::parse($employee->hire_date)->format('Y-m-d') : 'غير متوفر' }}
                        </li>
                        <li><strong>تاريخ إنهاء الخدمة:</strong>
                            {{ $employee->termination_date ? \Carbon\Carbon::parse($employee->termination_date)->format('Y-m-d') : '—' }}
                        </li>
                        <li><strong>الحالة:</strong> {{ $employee->status }}</li>
                    </ul>

                    <div class="mt-6 space-x-4">
                        <a href="{{ route('hr.employees.edit', $employee) }}"
                            class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                            تعديل
                        </a>

                        <a href="{{ route('hr.employees.index') }}"
                            class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                            رجوع للقائمة
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
