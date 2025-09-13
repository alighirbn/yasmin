<x-app-layout>

    <x-slot name="header">
        @include('hr.nav.navigation')
    </x-slot>

    <div class="bg-custom py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class='text-xl font-bold mb-4'>تفاصيل الراتب</h2>

                    <ul class="mb-4">
                        <li><strong>الموظف:</strong> {{ $payroll->employee->first_name }}
                            {{ $payroll->employee->last_name }}</li>
                        <li><strong>الشهر:</strong> {{ $payroll->month }}</li>
                        <li><strong>السنة:</strong> {{ $payroll->year }}</li>
                        <li><strong>الراتب الأساسي:</strong> {{ number_format($payroll->basic_salary, 2) }}</li>
                        <li><strong>إجمالي الحوافز:</strong> {{ number_format($payroll->total_incentives, 2) }}</li>
                        <li><strong>إجمالي الاستقطاعات:</strong> {{ number_format($payroll->total_deductions, 2) }}</li>
                        <li><strong>الصافي:</strong> {{ number_format($payroll->net_salary, 2) }}</li>
                    </ul>

                    <a href="{{ route('hr.payrolls.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">رجوع
                        للقائمة</a>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
