<?php

namespace App\Http\Controllers;

use App\Models\Termination;
use App\Models\Employee;
use Illuminate\Http\Request;

class TerminationController extends Controller
{

    public function index()
    {
        // احصل على جميع عمليات إنهاء الخدمة مع بيانات الموظف، ويمكنك إضافة Pagination
        $terminations = Termination::with('employee')->orderBy('termination_date', 'desc')->paginate(25);

        return view('hr.terminations.index', compact('terminations'));
    }


    public function create()
    {
        $employees = Employee::where('status', 'active')->get();
        return view('hr.terminations.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'termination_date' => 'required|date',
            'reason' => 'nullable|string',
        ]);

        $term = Termination::create($data);

        $employee = Employee::find($data['employee_id']);
        $employee->update(['status' => 'terminated', 'termination_date' => $data['termination_date']]);

        return redirect()->route('hr.employees.index')->with('success', 'Employee terminated.');
    }
}
