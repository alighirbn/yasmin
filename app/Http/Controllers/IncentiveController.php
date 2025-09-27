<?php

namespace App\Http\Controllers;

use App\Models\Incentive;
use App\Models\Employee;
use Illuminate\Http\Request;

class IncentiveController extends Controller
{
    public function index()
    {
        $items = Incentive::with('employee')->orderBy('date', 'desc')->paginate(25);
        return view('hr.incentives.index', compact('items'));
    }

    public function show(Incentive $incentive)
    {
        return view('hr.incentives.show', ['item' => $incentive]);
    }


    public function create()
    {
        $employees = Employee::orderBy('first_name')->get();
        return view('hr.incentives.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'type' => 'required|in:incentive,paycut',
            'amount' => 'required|numeric',
            'reason' => 'nullable|string',
            'date' => 'required|date',
        ]);

        Incentive::create($data);
        return redirect()->route('hr.incentives.index')->with('success', 'Saved.');
    }

    public function edit(Incentive $incentive)
    {
        $employees = Employee::orderBy('first_name')->get();
        return view('hr.incentives.edit', compact('incentive', 'employees'));
    }

    public function update(Request $request, Incentive $incentive)
    {
        $data = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'type' => 'required|in:incentive,paycut',
            'amount' => 'required|numeric',
            'reason' => 'nullable|string',
            'date' => 'required|date',
        ]);

        $incentive->update($data);
        return redirect()->route('hr.incentives.index')->with('success', 'Updated.');
    }

    public function destroy(Incentive $incentive)
    {
        $incentive->delete();
        return redirect()->route('hr.incentives.index')->with('success', 'Deleted.');
    }
}
