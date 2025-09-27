<?php

namespace App\Http\Controllers;

use App\Models\Advance;
use App\Models\Employee;
use Illuminate\Http\Request;

class AdvanceController extends Controller
{
    public function index()
    {
        $items = Advance::with('employee')->orderBy('date', 'desc')->paginate(25);
        return view('hr.advances.index', compact('items'));
    }

    public function create()
    {
        $employees = Employee::orderBy('first_name')->get();
        return view('hr.advances.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'amount' => 'required|numeric',
            'date' => 'required|date',
        ]);

        Advance::create($data + ['settled' => false]);
        return redirect()->route('hr.advances.index')->with('success', 'Advance created.');
    }

    public function show(Advance $advance)
    {
        return view('hr.advances.show', ['item' => $advance]);
    }

    public function edit(Advance $advance)
    {
        $employees = Employee::orderBy('first_name')->get();
        return view('hr.advances.edit', compact('advance', 'employees'));
    }

    public function update(Request $request, Advance $advance)
    {
        $data = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'amount' => 'required|numeric',
            'date' => 'required|date',
            'settled' => 'nullable|boolean',
        ]);

        $advance->update($data);
        return redirect()->route('hr.advances.index')->with('success', 'Updated.');
    }

    public function destroy(Advance $advance)
    {
        $advance->delete();
        return redirect()->route('hr.advances.index')->with('success', 'Deleted.');
    }
}
