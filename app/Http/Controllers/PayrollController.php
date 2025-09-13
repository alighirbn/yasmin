<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Models\Employee;
use App\Models\Incentive;
use App\Models\Advance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PayrollController extends Controller
{
    public function index()
    {
        $payrolls = Payroll::with('employee')->orderBy('year', 'desc')->orderBy('month', 'desc')->paginate(25);
        return view('hr.payrolls.index', compact('payrolls'));
    }

    public function create()
    {
        $employees = Employee::where('status', 'active')->orderBy('first_name')->get();
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

        // Get totals
        $incTotal = Incentive::where('employee_id', $employee->id)
            ->whereYear('date', $data['year'])
            ->whereMonth('date', $data['month'])
            ->where('type', 'incentive')
            ->sum('amount');

        $paycutTotal = Incentive::where('employee_id', $employee->id)
            ->whereYear('date', $data['year'])
            ->whereMonth('date', $data['month'])
            ->where('type', 'paycut')
            ->sum('amount');

        // Only consider **unsettled advances** for the payroll month
        $advances = Advance::where('employee_id', $employee->id)
            ->whereYear('date', $data['year'])
            ->whereMonth('date', $data['month'])
            ->where('settled', false)
            ->get();

        $advancesTotal = $advances->sum('amount');

        $basic = $employee->basic_salary;
        $totalIncentives = floatval($incTotal);
        $totalDeductions = floatval($paycutTotal) + floatval($advancesTotal);
        $net = $basic + $totalIncentives - $totalDeductions;

        // Create payroll
        $payroll = Payroll::create([
            'employee_id' => $employee->id,
            'month' => $data['month'],
            'year' => $data['year'],
            'basic_salary' => $basic,
            'total_incentives' => $totalIncentives,
            'total_deductions' => $totalDeductions,
            'net_salary' => $net,
        ]);

        // **Mark advances as settled**
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

        // Recalculate totals for the new month/year
        $incTotal = Incentive::where('employee_id', $employee->id)
            ->whereYear('date', $data['year'])
            ->whereMonth('date', $data['month'])
            ->where('type', 'incentive')
            ->sum('amount');

        $paycutTotal = Incentive::where('employee_id', $employee->id)
            ->whereYear('date', $data['year'])
            ->whereMonth('date', $data['month'])
            ->where('type', 'paycut')
            ->sum('amount');

        // Only consider unsettled advances for that month/year
        $advances = Advance::where('employee_id', $employee->id)
            ->whereYear('date', $data['year'])
            ->whereMonth('date', $data['month'])
            ->where('settled', false)
            ->get();

        $advancesTotal = $advances->sum('amount');

        $basic = $employee->basic_salary;
        $totalIncentives = floatval($incTotal);
        $totalDeductions = floatval($paycutTotal) + floatval($advancesTotal);
        $net = $basic + $totalIncentives - $totalDeductions;

        // Update payroll
        $payroll->update([
            'month' => $data['month'],
            'year'  => $data['year'],
            'basic_salary' => $basic,
            'total_incentives' => $totalIncentives,
            'total_deductions' => $totalDeductions,
            'net_salary' => $net,
        ]);

        // Mark advances as settled for this payroll month
        foreach ($advances as $adv) {
            $adv->update(['settled' => true]);
        }

        return redirect()->route('hr.payrolls.index')->with('success', 'Payroll updated and advances settled.');
    }


    public function show(Payroll $payroll)
    {
        return view('hr.payrolls.show', compact('payroll'));
    }

    public function currentMonth()
    {
        $currentMonth = now()->month;
        $currentYear  = now()->year;

        // Load payrolls for current month and group by employee.department string
        $payrolls = Payroll::with('employee')
            ->where('month', $currentMonth)
            ->where('year', $currentYear)
            ->get()
            ->groupBy(fn($p) => $p->employee->department ?? 'غير محدد');

        return view('hr.payrolls.current_month', compact('payrolls', 'currentMonth', 'currentYear'));
    }


    public function destroy(Payroll $payroll)
    {
        $payroll->delete();
        return redirect()->route('hr.payrolls.index')->with('success', 'Deleted.');
    }
}
