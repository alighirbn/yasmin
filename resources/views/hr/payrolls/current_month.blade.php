<x-app-layout>
    <x-slot name="header">
        <!-- app css-->
        <link rel="stylesheet" type="text/css" href="{{ url('/css/app.css') }}" />
        @include('hr.nav.navigation')
    </x-slot>

    <div class="bg-custom py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-900">
                <div class="header-buttons">
                    <a href="{{ url()->previous() }}" class="btn btn-custom-back">
                        {{ __('word.back') }}
                    </a>

                    <button id="print" class="btn btn-custom-print" onclick="window.print();">
                        {{ __('word.print') }}
                    </button>
                </div>

                <div class="print-container a4-width mx-auto bg-white">

                    @php
                        $grandBasic = 0;
                        $grandIncentives = 0;
                        $grandDeductions = 0;
                        $grandAdvances = 0;
                        $grandNet = 0;
                    @endphp

                    @foreach ($payrolls as $department => $deptPayrolls)
                        <div class="flex items-center">
                            <div class="mx-2 my-2 w-full">
                                {!! QrCode::size(90)->generate($payrolls->count()) !!}
                            </div>
                            <div class="mx-2 my-2 w-full">
                                <img src="{{ asset('images/ba.png') }}" alt="Logo" class="h-6 max-w-full"
                                    style="height: auto;">
                            </div>
                            <div class="mx-2 my-2 w-full text-center">
                                <h2 class="text-xl font-bold">
                                    ادارة العمليات والموارد البشرية
                                </h2>
                            </div>
                        </div>

                        <h2 class="text-xl font-bold mb-4">
                            رواتب الشهر الحالي ({{ $currentMonth }}/{{ $currentYear }})
                        </h2>

                        <div class="mb-6">
                            <h3 class="font-semibold text-lg mb-2">القسم: {{ $department }}</h3>
                            <table class="w-full border-collapse border mb-4">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="border px-2 py-1">التسلسل</th>
                                        <th class="border px-2 py-1">الموظف</th>
                                        <th class="border px-2 py-1">الوظيفة</th>
                                        <th class="border px-2 py-1">الراتب الأساسي</th>
                                        <th class="border px-2 py-1">الحوافز</th>
                                        <th class="border px-2 py-1">الخصومات</th>
                                        <th class="border px-2 py-1">السلف</th>
                                        <th class="border px-2 py-1">الصافي</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totalBasic = 0;
                                        $totalIncentives = 0;
                                        $totalDeductions = 0;
                                        $totalAdvances = 0;
                                        $totalNet = 0;
                                    @endphp

                                    @foreach ($deptPayrolls as $index => $payroll)
                                        @php
                                            $advancesTotal = $payroll->employee
                                                ->advances()
                                                ->whereYear('date', $currentYear)
                                                ->whereMonth('date', $currentMonth)
                                                ->sum('amount');
                                            $netSalary =
                                                $payroll->basic_salary +
                                                $payroll->total_incentives -
                                                $payroll->total_deductions -
                                                $advancesTotal;
                                        @endphp
                                        <tr class="{{ $loop->even ? 'bg-gray-50' : '' }}">
                                            <td class="border px-2 py-1 text-center">{{ $index + 1 }}</td>
                                            <td class="border px-2 py-1">{{ $payroll->employee->full_name }}</td>
                                            <td class="border px-2 py-1">{{ $payroll->employee->position }}</td>
                                            <td class="border px-2 py-1">{{ number_format($payroll->basic_salary, 0) }}
                                            </td>
                                            <td class="border px-2 py-1">
                                                {{ number_format($payroll->total_incentives, 0) }}</td>
                                            <td class="border px-2 py-1">
                                                {{ number_format($payroll->total_deductions, 0) }}</td>
                                            <td class="border px-2 py-1">{{ number_format($advancesTotal, 0) }}</td>
                                            <td class="border px-2 py-1 font-bold">{{ number_format($netSalary, 0) }}
                                            </td>
                                        </tr>

                                        @php
                                            $totalBasic += $payroll->basic_salary;
                                            $totalIncentives += $payroll->total_incentives;
                                            $totalDeductions += $payroll->total_deductions;
                                            $totalAdvances += $advancesTotal;
                                            $totalNet += $netSalary;
                                        @endphp
                                    @endforeach

                                    <tr class="bg-gray-500 font-semibold text-white">
                                        <td class="border px-2 py-1 text-center">#</td>
                                        <td class="border px-2 py-1 text-center">مجموع القسم</td>
                                        <td class="border px-2 py-1">-</td>
                                        <td class="border px-2 py-1">{{ number_format($totalBasic, 0) }}</td>
                                        <td class="border px-2 py-1">{{ number_format($totalIncentives, 0) }}</td>
                                        <td class="border px-2 py-1">{{ number_format($totalDeductions, 0) }}</td>
                                        <td class="border px-2 py-1">{{ number_format($totalAdvances, 0) }}</td>
                                        <td class="border px-2 py-1 font-bold">{{ number_format($totalNet, 0) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        @php
                            $grandBasic += $totalBasic;
                            $grandIncentives += $totalIncentives;
                            $grandDeductions += $totalDeductions;
                            $grandAdvances += $totalAdvances;
                            $grandNet += $totalNet;
                        @endphp

                        <br>
                    @endforeach

                    <!-- جدول المجموع العام -->
                    <div class="mt-8">
                        <h3 class="font-bold text-lg mb-2">المجموع الكلي (جميع الأقسام)</h3>
                        <table class="w-full border-collapse border">
                            <thead class="bg-gray-700 text-white">
                                <tr>
                                    <th class="border px-2 py-1">الراتب الأساسي</th>
                                    <th class="border px-2 py-1">الحوافز</th>
                                    <th class="border px-2 py-1">الخصومات</th>
                                    <th class="border px-2 py-1">السلف</th>
                                    <th class="border px-2 py-1">الصافي</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="bg-gray-100 font-bold">
                                    <td class="border px-2 py-1">{{ number_format($grandBasic, 0) }}</td>
                                    <td class="border px-2 py-1">{{ number_format($grandIncentives, 0) }}</td>
                                    <td class="border px-2 py-1">{{ number_format($grandDeductions, 0) }}</td>
                                    <td class="border px-2 py-1">{{ number_format($grandAdvances, 0) }}</td>
                                    <td class="border px-2 py-1">{{ number_format($grandNet, 0) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
