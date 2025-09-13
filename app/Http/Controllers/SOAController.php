<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class SOAController extends Controller
{
    public function index(Request $request)
    {
        $employees = Employee::orderBy('first_name')->get();

        $employee = null;
        $payrolls = $incentives = $advances = collect();
        $month = $request->query('month');
        $year = $request->query('year');

        if ($request->query('employee_id')) {
            $employee = Employee::find($request->query('employee_id'));
            if ($employee) {
                $payrolls = $employee->payrolls()
                    ->when($month, fn($q) => $q->where('month', $month))
                    ->when($year, fn($q) => $q->where('year', $year))
                    ->orderBy('year', 'desc')->orderBy('month', 'desc')->get();

                $incentives = $employee->incentives()
                    ->when($month, fn($q) => $q->whereMonth('date', $month))
                    ->when($year, fn($q) => $q->whereYear('date', $year))
                    ->orderBy('date', 'desc')->get();

                $advances = $employee->advances()
                    ->when($month, fn($q) => $q->whereMonth('date', $month))
                    ->when($year, fn($q) => $q->whereYear('date', $year))
                    ->orderBy('date', 'desc')->get();
            }
        }

        return view('hr.soa.show', compact('employees', 'employee', 'payrolls', 'incentives', 'advances', 'month', 'year'));
    }
}
