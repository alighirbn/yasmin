<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::orderBy('id', 'desc')->paginate(15);
        return view('hr.employees.index', compact('employees'));
    }

    public function create()
    {
        return view('hr.employees.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'employee_code' => 'required|unique:employees,employee_code|max:50',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'department' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'basic_salary' => 'nullable|numeric|min:0',
            'hire_date' => 'nullable|date',
        ], [
            'employee_code.required' => 'كود الموظف مطلوب',
            'employee_code.unique' => 'كود الموظف موجود مسبقًا',
            'first_name.required' => 'الاسم الأول مطلوب',
            'last_name.required' => 'الاسم الأخير مطلوب',
            'basic_salary.numeric' => 'الراتب يجب أن يكون رقم',
        ]);

        Employee::create($data);

        return redirect()->route('hr.employees.index')->with('success', 'تم إنشاء الموظف بنجاح.');
    }

    public function show(Employee $employee)
    {
        return view('hr.employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        return view('hr.employees.edit', compact('employee'));
    }

    public function update(Request $request, Employee $employee)
    {
        $data = $request->validate([
            'employee_code' => 'required|unique:employees,employee_code,' . $employee->id . '|max:50',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'department' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'basic_salary' => 'nullable|numeric|min:0',
            'hire_date' => 'nullable|date',
        ]);

        $employee->update($data);

        return redirect()->route('hr.employees.index')->with('success', 'تم تحديث بيانات الموظف بنجاح.');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('hr.employees.index')->with('success', 'تم حذف الموظف بنجاح.');
    }
}
