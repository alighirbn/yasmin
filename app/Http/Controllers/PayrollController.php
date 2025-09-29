<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Models\Employee;
use App\Models\Incentive;
use App\Models\Advance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PayrollController extends Controller
{
    public function index(Request $request)
    {
        $query = Payroll::with('employee');

        // Filter by employee name
        if ($request->filled('name')) {
            $query->whereHas('employee', function ($q) use ($request) {
                $q->where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', '%' . $request->name . '%');
            });
        }

        // Filter by month
        if ($request->filled('month')) {
            $query->where('month', $request->month);
        }

        // Filter by year
        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        $payrolls = $query
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->paginate(25)
            ->appends($request->all()); // keep filters in pagination links

        return view('hr.payrolls.index', compact('payrolls'));
    }


    public function create()
    {
        $employees = Employee::where('status', 'active')
            ->orWhere('status', 'terminated') // allow terminated employees for current month payroll
            ->orderBy('first_name')
            ->get();

        return view('hr.payrolls.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2000|max:2100',
        ]);

        $employee = Employee::findOrFail($data['employee_id']);

        $payrollMonth = Carbon::create($data['year'], $data['month'], 1);
        $totalDaysInMonth = $payrollMonth->daysInMonth;

        // Check hire and termination dates
        $hireDate = Carbon::parse($employee->hire_date);
        $terminationDate = $employee->termination_date ? Carbon::parse($employee->termination_date) : null;

        // Determine start and end dates for the payroll period
        $startDay = ($hireDate->year == $data['year'] && $hireDate->month == $data['month'])
            ? $hireDate->day
            : 1;

        $endDay = ($terminationDate && $terminationDate->year == $data['year'] && $terminationDate->month == $data['month'])
            ? $terminationDate->day
            : $totalDaysInMonth;

        // Calculate days worked
        $daysWorked = $endDay - $startDay + 1;

        // Prorated basic salary
        $basic = $employee->basic_salary * ($daysWorked / $totalDaysInMonth);

        // Calculate totals (incentives, paycuts, advances) within the employment period
        $incTotal = Incentive::where('employee_id', $employee->id)
            ->where('type', 'incentive')
            ->whereYear('date', $data['year'])
            ->whereMonth('date', $data['month'])
            ->when($startDay > 1, function ($q) use ($startDay) {
                $q->whereDay('date', '>=', $startDay);
            })
            ->when($terminationDate && $terminationDate->month == $data['month'], function ($q) use ($terminationDate) {
                $q->whereDay('date', '<=', $terminationDate->day);
            })
            ->sum('amount');

        $paycutTotal = Incentive::where('employee_id', $employee->id)
            ->where('type', 'paycut')
            ->whereYear('date', $data['year'])
            ->whereMonth('date', $data['month'])
            ->when($startDay > 1, function ($q) use ($startDay) {
                $q->whereDay('date', '>=', $startDay);
            })
            ->when($terminationDate && $terminationDate->month == $data['month'], function ($q) use ($terminationDate) {
                $q->whereDay('date', '<=', $terminationDate->day);
            })
            ->sum('amount');

        $advances = Advance::where('employee_id', $employee->id)
            ->where('settled', false)
            ->whereYear('date', $data['year'])
            ->whereMonth('date', $data['month'])
            ->when($startDay > 1, function ($q) use ($startDay) {
                $q->whereDay('date', '>=', $startDay);
            })
            ->when($terminationDate && $terminationDate->month == $data['month'], function ($q) use ($terminationDate) {
                $q->whereDay('date', '<=', $terminationDate->day);
            })
            ->get();

        $advancesTotal = $advances->sum('amount');

        $net = $basic + $incTotal - $paycutTotal - $advancesTotal;

        $payroll = Payroll::create([
            'employee_id' => $employee->id,
            'month' => $data['month'],
            'year' => $data['year'],
            'basic_salary' => $basic,
            'total_incentives' => $incTotal,
            'total_deductions' => $paycutTotal,
            'net_salary' => $net,
        ]);

        // Mark advances as settled
        foreach ($advances as $adv) {
            $adv->update(['settled' => true]);
        }

        return redirect()->route('hr.payrolls.index')->with('success', 'Payroll created and advances settled.');
    }

    public function edit(Payroll $payroll)
    {
        return view('hr.payrolls.edit', compact('payroll'));
    }

    public function update(Request $request, Payroll $payroll)
    {
        $data = $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year'  => 'required|integer|min:2000|max:2100',
        ]);

        $employee = $payroll->employee;

        $payrollMonth = Carbon::create($data['year'], $data['month'], 1);
        $totalDaysInMonth = $payrollMonth->daysInMonth;

        // Check hire and termination dates
        $hireDate = Carbon::parse($employee->hire_date);
        $terminationDate = $employee->termination_date ? Carbon::parse($employee->termination_date) : null;

        // Determine start and end dates for the payroll period
        $startDay = ($hireDate->year == $data['year'] && $hireDate->month == $data['month'])
            ? $hireDate->day
            : 1;

        $endDay = ($terminationDate && $terminationDate->year == $data['year'] && $terminationDate->month == $data['month'])
            ? $terminationDate->day
            : $totalDaysInMonth;

        // Calculate days worked
        $daysWorked = $endDay - $startDay + 1;

        $basic = $employee->basic_salary * ($daysWorked / $totalDaysInMonth);

        $incTotal = Incentive::where('employee_id', $employee->id)
            ->where('type', 'incentive')
            ->whereYear('date', $data['year'])
            ->whereMonth('date', $data['month'])
            ->when($startDay > 1, function ($q) use ($startDay) {
                $q->whereDay('date', '>=', $startDay);
            })
            ->when($terminationDate && $terminationDate->month == $data['month'], function ($q) use ($terminationDate) {
                $q->whereDay('date', '<=', $terminationDate->day);
            })
            ->sum('amount');

        $paycutTotal = Incentive::where('employee_id', $employee->id)
            ->where('type', 'paycut')
            ->whereYear('date', $data['year'])
            ->whereMonth('date', $data['month'])
            ->when($startDay > 1, function ($q) use ($startDay) {
                $q->whereDay('date', '>=', $startDay);
            })
            ->when($terminationDate && $terminationDate->month == $data['month'], function ($q) use ($terminationDate) {
                $q->whereDay('date', '<=', $terminationDate->day);
            })
            ->sum('amount');

        $advances = Advance::where('employee_id', $employee->id)
            ->where('settled', false)
            ->whereYear('date', $data['year'])
            ->whereMonth('date', $data['month'])
            ->when($startDay > 1, function ($q) use ($startDay) {
                $q->whereDay('date', '>=', $startDay);
            })
            ->when($terminationDate && $terminationDate->month == $data['month'], function ($q) use ($terminationDate) {
                $q->whereDay('date', '<=', $terminationDate->day);
            })
            ->get();

        $advancesTotal = $advances->sum('amount');

        $net = $basic + $incTotal - $paycutTotal - $advancesTotal;

        $payroll->update([
            'month' => $data['month'],
            'year'  => $data['year'],
            'basic_salary' => $basic,
            'total_incentives' => $incTotal,
            'total_deductions' => $paycutTotal,
            'net_salary' => $net,
        ]);

        foreach ($advances as $adv) {
            $adv->update(['settled' => true]);
        }

        return redirect()->route('hr.payrolls.index')->with('success', 'Payroll updated and advances settled.');
    }

    public function show(Payroll $payroll)
    {
        return view('hr.payrolls.show', compact('payroll'));
    }

    public function currentMonth(Request $request)
    {
        $currentMonth = now()->month;
        $currentYear  = now()->year;

        // Get selected department from request (if any)
        $selectedDepartment = $request->input('department');

        $query = Payroll::with(['employee' => function ($q) {
            $q->orderBy('employee_code', 'asc');
        }])
            ->where('month', $currentMonth)
            ->where('year', $currentYear);

        // Apply department filter if selected
        if ($selectedDepartment) {
            $query->whereHas('employee', function ($q) use ($selectedDepartment) {
                $q->where('department', $selectedDepartment);
            });
        }

        $payrolls = $query->get()
            ->sortBy(fn($p) => $p->employee->employee_code)
            ->groupBy(fn($p) => $p->employee->department ?? 'غير محدد');

        // Get distinct department list for dropdown
        $departments = \App\Models\Employee::select('department')
            ->distinct()
            ->pluck('department');

        return view('hr.payrolls.current_month', compact(
            'payrolls',
            'currentMonth',
            'currentYear',
            'departments',
            'selectedDepartment'
        ));
    }



    public function generateAll()
    {
        $currentMonth = now()->month;
        $currentYear  = now()->year;

        $employees = Employee::where('status', 'active')
            ->orWhere('status', 'terminated') // include recently terminated employees
            ->get();

        foreach ($employees as $employee) {
            // Skip if payroll already exists for this employee
            if (Payroll::where('employee_id', $employee->id)
                ->where('month', $currentMonth)
                ->where('year', $currentYear)
                ->exists()
            ) {
                continue;
            }

            $payrollMonth = Carbon::create($currentYear, $currentMonth, 1);
            $totalDaysInMonth = $payrollMonth->daysInMonth;

            // Check hire and termination dates
            $hireDate = Carbon::parse($employee->hire_date);
            $terminationDate = $employee->termination_date ? Carbon::parse($employee->termination_date) : null;

            // Determine start and end dates for the payroll period
            $startDay = ($hireDate->year == $currentYear && $hireDate->month == $currentMonth)
                ? $hireDate->day
                : 1;

            $endDay = ($terminationDate && $terminationDate->year == $currentYear && $terminationDate->month == $currentMonth)
                ? $terminationDate->day
                : $totalDaysInMonth;

            // Calculate days worked
            $daysWorked = $endDay - $startDay + 1;

            $basic = $employee->basic_salary * ($daysWorked / $totalDaysInMonth);

            $incTotal = Incentive::where('employee_id', $employee->id)
                ->where('type', 'incentive')
                ->whereYear('date', $currentYear)
                ->whereMonth('date', $currentMonth)
                ->when($startDay > 1, function ($q) use ($startDay) {
                    $q->whereDay('date', '>=', $startDay);
                })
                ->when($terminationDate && $terminationDate->month == $currentMonth, function ($q) use ($terminationDate) {
                    $q->whereDay('date', '<=', $terminationDate->day);
                })
                ->sum('amount');

            $paycutTotal = Incentive::where('employee_id', $employee->id)
                ->where('type', 'paycut')
                ->whereYear('date', $currentYear)
                ->whereMonth('date', $currentMonth)
                ->when($startDay > 1, function ($q) use ($startDay) {
                    $q->whereDay('date', '>=', $startDay);
                })
                ->when($terminationDate && $terminationDate->month == $currentMonth, function ($q) use ($terminationDate) {
                    $q->whereDay('date', '<=', $terminationDate->day);
                })
                ->sum('amount');

            $advances = Advance::where('employee_id', $employee->id)
                ->where('settled', false)
                ->whereYear('date', $currentYear)
                ->whereMonth('date', $currentMonth)
                ->when($startDay > 1, function ($q) use ($startDay) {
                    $q->whereDay('date', '>=', $startDay);
                })
                ->when($terminationDate && $terminationDate->month == $currentMonth, function ($q) use ($terminationDate) {
                    $q->whereDay('date', '<=', $terminationDate->day);
                })
                ->get();

            $advancesTotal = $advances->sum('amount');

            $net = $basic + $incTotal - $paycutTotal - $advancesTotal;

            // Create payroll
            Payroll::create([
                'employee_id' => $employee->id,
                'month' => $currentMonth,
                'year' => $currentYear,
                'basic_salary' => $basic,
                'total_incentives' => $incTotal,
                'total_deductions' => $paycutTotal,
                'net_salary' => $net,
            ]);

            // Mark advances as settled
            foreach ($advances as $adv) {
                $adv->update(['settled' => true]);
            }
        }

        return redirect()->route('hr.payrolls.index')->with('success', 'تم إنشاء الرواتب لجميع الموظفين للشهر الحالي.');
    }

    public function destroy(Payroll $payroll)
    {
        $payroll->delete();
        return redirect()->route('hr.payrolls.index')->with('success', 'Deleted.');
    }
}
