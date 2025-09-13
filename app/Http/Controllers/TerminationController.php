<?php

namespace App\Http\Controllers;

use App\Models\Termination;
use App\Models\Employee;
use Illuminate\Http\Request;

class TerminationController extends Controller
{
    // Show all terminations (paginated)
    public function index()
    {
        $terminations = Termination::with('employee')
            ->orderBy('termination_date', 'desc')
            ->paginate(25);

        return view('hr.terminations.index', compact('terminations'));
    }

    // Show create form
    public function create()
    {
        $employees = Employee::where('status', 'active')->orderBy('first_name')->get();
        return view('hr.terminations.create', compact('employees'));
    }

    // Store termination
    public function store(Request $request)
    {
        $data = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'termination_date' => 'required|date',
            'reason' => 'nullable|string',
        ]);

        $term = Termination::create($data);

        $employee = Employee::find($data['employee_id']);
        $employee->update([
            'status' => 'terminated',
            'termination_date' => $data['termination_date']
        ]);

        return redirect()->route('hr.terminations.index')->with('success', 'Employee terminated.');
    }

    // Show edit form
    public function edit(Termination $termination)
    {
        $employees = Employee::where('status', 'active')
            ->orWhere('id', $termination->employee_id)
            ->orderBy('first_name')
            ->get();

        return view('hr.terminations.edit', compact('termination', 'employees'));
    }

    // Update termination
    public function update(Request $request, Termination $termination)
    {
        $data = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'termination_date' => 'required|date',
            'reason' => 'nullable|string',
        ]);

        $termination->update($data);

        $employee = Employee::find($data['employee_id']);
        $employee->update([
            'status' => 'terminated',
            'termination_date' => $data['termination_date']
        ]);

        return redirect()->route('hr.terminations.index')->with('success', 'Termination updated.');
    }

    // Delete termination
    public function destroy(Termination $termination)
    {
        $employee = $termination->employee;

        // Reset employee status if needed
        $employee->update([
            'status' => 'active',
            'termination_date' => null
        ]);

        $termination->delete();

        return redirect()->route('hr.terminations.index')->with('success', 'Termination deleted.');
    }

    // Show single termination
    public function show(Termination $termination)
    {
        return view('hr.terminations.show', compact('termination'));
    }
}
