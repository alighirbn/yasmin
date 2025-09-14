<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Services\WiaScanner;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    private $scanner;

    public function __construct(WiaScanner $scanner)
    {
        $this->scanner = $scanner;
    }

    /**
     * Display the list of available scanner devices.
     */
    public function scancreate(Employee $employee)
    {
        try {
            $devices = $this->scanner->listDevices();
            return view('hr.employees.scanner', compact('devices', 'employee'));
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to list devices. Please try again later.'], 500);
        }
    }

    /**
     * Initiate the scan process for the selected scanner device.
     */
    public function scanstore(Request $request, Employee $employee): JsonResponse
    {
        try {
            $deviceId = $request->input('device_id');
            if (empty($deviceId)) {
                return response()->json(['error' => 'Device ID is required.'], 400);
            }

            $this->scanner->connect($deviceId);

            $scansDirectory = storage_path('app/public/scans/');
            if (!is_dir($scansDirectory)) {
                mkdir($scansDirectory, 0755, true);
            }

            $filename = uniqid() . '.png';
            $outputPath = $scansDirectory . $filename;

            $scannedImagePath = $this->scanner->scan($outputPath);

            // Save the scanned image and associate it with the employee
            $employee->images()->create([
                'image_path' => 'storage/scans/' . $filename,
                'user_id_create' => auth()->id(),
            ]);

            return response()->json([
                'message' => 'Scan successful and image associated with employee.',
                'image_path' => asset('storage/scans/' . basename($scannedImagePath))
            ], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to scan the document. Please try again later.'], 500);
        }
    }

    public function archivecreate(Employee $employee)
    {
        return view('hr.employees.archivecreate', compact('employee'));
    }

    public function archivestore(Request $request, Employee $employee)
    {
        $request->validate([
            'images' => 'required|array',
            'images.*' => 'string',
        ]);

        foreach ($request->input('images') as $image) {
            $image = str_replace('data:image/jpeg;base64,', '', $image);
            $image = str_replace(' ', '+', $image);
            $imageName = 'employee_image_' . time() . '_' . uniqid() . '.jpeg';
            $imagePath = 'public/employee_images/' . $imageName;

            Storage::put($imagePath, base64_decode($image));

            $employee->images()->create([
                'image_path' => str_replace('public/', 'storage/', $imagePath),
                'user_id_create' => auth()->id(),
            ]);
        }

        return redirect()->route('hr.employees.index', $employee)->with('success', 'تم ارشفة الصور بنجاح');
    }

    public function archiveshow(Employee $employee)
    {
        $employee->load('images');

        if ($employee) {
            return view('hr.employees.archiveshow', compact('employee'));
        } else {
            $ip = $this->getIPAddress();
            return view('employee.accessdenied', ['ip' => $ip]);
        }
    }

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
            'first_name'    => 'required|string|max:255',
            'last_name'     => 'required|string|max:255',
            'department'    => 'nullable|string|max:255',
            'position'      => 'nullable|string|max:255',
            'basic_salary'  => 'nullable|numeric|min:0',
            'hire_date'     => 'nullable|date',
        ], [
            'employee_code.required' => 'كود الموظف مطلوب',
            'employee_code.unique'   => 'كود الموظف موجود مسبقًا',
            'first_name.required'    => 'الاسم الأول مطلوب',
            'last_name.required'     => 'الاسم الأخير مطلوب',
            'basic_salary.numeric'   => 'الراتب يجب أن يكون رقم',
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
            'first_name'    => 'required|string|max:255',
            'last_name'     => 'required|string|max:255',
            'department'    => 'nullable|string|max:255',
            'position'      => 'nullable|string|max:255',
            'basic_salary'  => 'nullable|numeric|min:0',
            'hire_date'     => 'nullable|date',
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
