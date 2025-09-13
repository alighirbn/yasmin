<x-app-layout>
    <x-slot name="header">
        @include('hr.nav.navigation')
    </x-slot>

    <div class="bg-custom py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="text-xl font-bold mb-4">📑 كشف حساب الموظف</h2>

                    <!-- Filter Form -->
                    <form method="GET" class="mb-4 flex gap-2 items-end">
                        <div>
                            <label>الموظف</label>
                            <select name="employee_id" class="border rounded w-full">
                                @foreach ($employees as $emp)
                                    <option value="{{ $emp->id }}"
                                        {{ isset($employee) && $employee->id == $emp->id ? 'selected' : '' }}>
                                        {{ $emp->first_name }} {{ $emp->last_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label>الشهر</label>
                            <input type="number" name="month" min="1" max="12"
                                class="border rounded w-full" value="{{ $month ?? '' }}">
                        </div>

                        <div>
                            <label>السنة</label>
                            <input type="number" name="year" min="2000" max="2100"
                                class="border rounded w-full" value="{{ $year ?? '' }}">
                        </div>

                        <div>
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">عرض</button>
                        </div>
                    </form>

                    @if (isset($employee))
                        <h3 class="text-lg font-semibold mb-2">الموظف: {{ $employee->first_name }}
                            {{ $employee->last_name }}</h3>

                        <!-- Payrolls Table -->
                        <h4 class="font-bold mt-4">الرواتب</h4>
                        <table class="w-full mt-2 border-collapse border">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="border px-2 py-1">الشهر</th>
                                    <th class="border px-2 py-1">السنة</th>
                                    <th class="border px-2 py-1">الراتب الأساسي</th>
                                    <th class="border px-2 py-1">إجمالي الحوافز</th>
                                    <th class="border px-2 py-1">إجمالي الخصومات</th>
                                    <th class="border px-2 py-1">الصافي</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($payrolls as $p)
                                    <tr class="{{ $loop->even ? 'bg-gray-50' : '' }}">
                                        <td class="border px-2 py-1">{{ $p->month }}</td>
                                        <td class="border px-2 py-1">{{ $p->year }}</td>
                                        <td class="border px-2 py-1">{{ number_format($p->basic_salary, 2) }}</td>
                                        <td class="border px-2 py-1">{{ number_format($p->total_incentives, 2) }}</td>
                                        <td class="border px-2 py-1">{{ number_format($p->total_deductions, 2) }}</td>
                                        <td class="border px-2 py-1 font-bold">{{ number_format($p->net_salary, 2) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="border px-2 py-1 text-center">لا توجد رواتب لهذا
                                            الموظف</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <!-- Incentives Table -->
                        <h4 class="font-bold mt-4">الحوافز والاستقطاعات</h4>
                        <table class="w-full mt-2 border-collapse border">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="border px-2 py-1">النوع</th>
                                    <th class="border px-2 py-1">المبلغ</th>
                                    <th class="border px-2 py-1">السبب</th>
                                    <th class="border px-2 py-1">التاريخ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($incentives as $i)
                                    <tr class="{{ $loop->even ? 'bg-gray-50' : '' }}">
                                        <td class="border px-2 py-1">{{ $i->type == 'incentive' ? 'حافز' : 'استقطاع' }}
                                        </td>
                                        <td class="border px-2 py-1">{{ number_format($i->amount, 2) }}</td>
                                        <td class="border px-2 py-1">{{ $i->reason }}</td>
                                        <td class="border px-2 py-1">
                                            {{ \Carbon\Carbon::parse($i->date)->format('Y-m-d') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="border px-2 py-1 text-center">لا توجد حوافز أو
                                            استقطاعات</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <!-- Advances Table -->
                        <h4 class="font-bold mt-4">السلف</h4>
                        <table class="w-full mt-2 border-collapse border">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="border px-2 py-1">المبلغ</th>
                                    <th class="border px-2 py-1">التاريخ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($advances as $a)
                                    <tr class="{{ $loop->even ? 'bg-gray-50' : '' }}">
                                        <td class="border px-2 py-1">{{ number_format($a->amount, 2) }}</td>
                                        <td class="border px-2 py-1">
                                            {{ \Carbon\Carbon::parse($a->date)->format('Y-m-d') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="border px-2 py-1 text-center">لا توجد سلف</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    @endif

                </div>
            </div>
        </div>
    </div>

</x-app-layout>
