<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Building\Building;
use App\Models\Contract\Contract;
use App\Models\Customer\Customer;
use App\Http\Controllers\Controller;
use App\Models\Contract\Installment;
use App\DataTables\ContractDataTable;
use App\Http\Requests\ContractRequest;
use App\Services\WiaScanner;
use App\Http\Requests\CustomerRequest;
use App\Models\Payment\Payment_Method;
use App\Models\Contract\Contract_Installment;
use Illuminate\Http\JsonResponse;
use App\Models\Payment\Payment;
use App\Models\User;
use App\Notifications\ContractAuthNotify;
use App\Notifications\ContractNotify;
use App\Notifications\ContractTerminateNotify;
use App\Notifications\PaymentNotify;
use App\Services\ContractUpdateService;
use App\Services\ZainSmsService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class ContractController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ContractDataTable $dataTable)
    {
        return $dataTable->render('contract.contract.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function customerstore(CustomerRequest $request)
    {
        $customer = Customer::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'تمت إضافة الزبون بنجاح',
            'customer' => $customer // Return the created customer data
        ]);
    }

    private $scanner;
    protected $smsService;

    public function __construct(WiaScanner $scanner, ZainSmsService $smsService)
    {
        $this->scanner = $scanner;
        $this->smsService = $smsService;
    }

    /**
     * Display the list of available scanner devices.
     *
     * @return \Illuminate\View\View
     */
    public function scancreate(string $url_address)
    {
        try {
            // Retrieve the contract with necessary relationships
            $contract = Contract::where('url_address', '=', $url_address)->first();
            // Attempt to get the list of devices
            $devices = $this->scanner->listDevices();
            return view('contract.contract.scanner', compact(['devices', 'contract']));
        } catch (Exception $e) {
            // Handle error if listing devices fails

            return response()->json(['error' => 'Failed to list devices. Please try again later.'], 500);
        }
    }

    /**
     * Initiate the scan process for the selected scanner device.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function scanstore(Request $request): JsonResponse
    {
        try {
            // Validate the provided device ID
            $deviceId = $request->input('device_id');
            if (empty($deviceId)) {
                return response()->json(['error' => 'Device ID is required.'], 400);
            }

            // Connect to the selected scanner device
            $this->scanner->connect($deviceId);

            // Ensure that the directory for storing scanned images exists
            $scansDirectory = storage_path('app/public/scans/');
            if (!is_dir($scansDirectory)) {
                mkdir($scansDirectory, 0755, true);
            }

            $filename = uniqid() . '.png';
            // Generate a unique file path for saving the scanned image
            $outputPath = $scansDirectory . $filename;

            // Perform the scan and save the image
            $scannedImagePath = $this->scanner->scan($outputPath);

            // Retrieve the URL address from the request (assuming it's passed)
            $url_address = $request->input('url_address');
            if (empty($url_address)) {
                return response()->json(['error' => 'URL address is required.'], 400);
            }

            // Retrieve the contract based on the provided URL address
            $contract = Contract::where('url_address', '=', $url_address)->first();
            if (!$contract) {
                return response()->json(['error' => 'Contract not found for the given URL address.'], 404);
            }


            // Save the scanned image and associate it with the contract
            $contract->images()->create([
                'image_path' => 'storage/scans/' . $filename,
                'customer_id' => $contract->contract_customer_id, // Add the customer ID
                'user_id_create' => auth()->id(), // Assuming you're tracking the creator
            ]);

            // Return the URL to the scanned image
            return response()->json([
                'message' => 'Scan successful and image associated with contract.',
                'image_path' => asset('storage/scans/' . basename($scannedImagePath))
            ], 200);
        } catch (Exception $e) {
            // Log and return detailed error message on failure

            return response()->json(['error' => 'Failed to scan the document. Please try again later.'], 500);
        }
    }

    public function archivecreate(string $url_address)
    {
        // Retrieve the contract with necessary relationships
        $contract = Contract::where('url_address', '=', $url_address)->first();

        return view('contract.contract.archivecreate', compact(['contract']));
    }

    public function archivestore(Request $request, string $url_address)
    {
        // Retrieve the contract
        $contract = Contract::where('url_address', '=', $url_address)->first();

        $request->validate([
            'images' => 'required|array',
            'images.*' => 'string', // Expecting an array of base64 strings
        ]);

        foreach ($request->input('images') as $image) {
            // Decode the base64 string
            $image = str_replace('data:image/jpeg;base64,', '', $image);
            $image = str_replace(' ', '+', $image);
            $imageName = 'contract_image_' . time() . '_' . uniqid() . '.jpeg'; // Unique names for each image
            $imagePath = 'public/contract_images/' . $imageName;
            // Save the image to storage
            Storage::put($imagePath, base64_decode($image));

            // Store the image path in the database
            $contract->images()->create([
                'image_path' => str_replace('public/', 'storage/', $imagePath),
                'customer_id' => $contract->contract_customer_id, // Add the customer ID here
                'user_id_create' => auth()->id(), // Assuming you're tracking the creator
            ]);
        }

        return redirect()->route('contract.show', $contract->url_address)->with('success', 'تم ارشفة الصور بنجاح');
    }


    public function archiveshow(string $url_address)
    {
        // Retrieve the contract with necessary relationships
        $contract = Contract::with(['images.customer'])->where('url_address', '=', $url_address)->first();

        if (isset($contract)) {
            // Group images by customer_full_name
            $groupedImages = $contract->images->groupBy(function ($image) {
                return $image->customer->customer_full_name;
            });

            return view('contract.contract.archiveshow', compact(['contract', 'groupedImages']));
        } else {
            $ip = $this->getIPAddress();
            return view('contract.contract.accessdenied', ['ip' => $ip]);
        }
    }


    public function sendSms(Request $request)
    {
        $request->validate([
            'phone_number' => 'required',
            'name' => 'required',
            'amount' => 'required',
            'due_date' => 'required',
            'contract_url' => 'required'
        ]);

        // Send SMS
        $response = $this->smsService->send(
            $request->phone_number,
            $request->name,
            $request->amount,
            $request->due_date
        );

        if ($response['status']) {
            return Redirect::route('contract.show', $request->contract_url)
                ->with('success', 'تم ارسال الرسالة بنجاح');
        } else {
            return Redirect::route('contract.show', $request->contract_url)
                ->with('error', __('word.sms_sent_failed') . ': ' . ($response['error'] ?? ''));
        }
    }





    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // Get all customers and payment methods
        $customers = Customer::all();
        $payment_methods = Payment_Method::all();

        // Get the building_id from the request
        $building_id = $request->input('building_id');

        // If building_id is provided, check if it has an existing non-terminated contract
        if ($building_id) {
            $building = Building::find($building_id);

            // Check if the building exists and has a non-terminated contract
            if ($building && $building->contract()->whereNotIn('stage', ['terminated'])->exists()) {
                // Return an error if the building has a non-terminated contract
                return redirect()->back()->with('error', 'تم حجز العقار مسبقا بواسطة عقد فعال.');
            }
        }

        // Fetch all buildings that are not hidden and have either no contracts or only terminated contracts
        $buildings = Building::where('hidden', false)
            ->where(function ($query) {
                $query->doesntHave('contract')
                    ->orWhereDoesntHave('contract', function ($subQuery) {
                        $subQuery->whereNotIn('stage', ['terminated']);
                    });
            })->get();

        return view('contract.contract.create', compact('customers', 'buildings', 'payment_methods', 'building_id'));
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(ContractRequest $request)
    {
        // Helper: Clean numeric values
        $clean = fn($v) => (float) str_replace(',', '', $v ?? 0);

        $building_id = $request->input('contract_building_id');

        // Business rule: check if building is already contracted
        if ($building_id) {
            $building = Building::find($building_id);
            if ($building && $building->contract()->whereNotIn('stage', ['terminated'])->exists()) {
                return redirect()->route('map.empty')
                    ->with('error', 'تم حجز العقار مسبقا بواسطة عقد فعال.');
            }
        }

        // Create Contract
        $contract = Contract::create($request->validated());
        $method = (int) $request->input('contract_payment_method_id');
        $contract_amount = $clean($request->contract_amount);
        $contract_date = Carbon::parse($request->contract_date);

        // ========== ✅ Method 3: Variable Payments ==========
        if ($method == 3) {

            $down = $clean($request->down_payment_amount);
            $down_now = $clean($request->down_payment_installment);
            $monthly = $clean($request->monthly_installment_amount);
            $months = (int) $request->number_of_months;
            $key = $clean($request->key_payment_amount);

            $deferred_type = $request->deferred_type ?? 'none';
            $deferred_months = (int) ($request->deferred_months ?? 0);
            $deferred_total = $down - $down_now;

            $per_deferred = ($deferred_type === "spread" && $deferred_months > 0)
                ? floor($deferred_total / $deferred_months)
                : 0;
            $remainder = ($deferred_type === "spread" && $deferred_months > 0)
                ? $deferred_total % $deferred_months
                : 0;

            $monthly_total = 0;
            $seq = 1;

            // DB Installment records
            $dp = Installment::where(['payment_method_id' => 3, 'installment_name' => 'دفعة مقدمة'])->first();
            $mi = Installment::where(['payment_method_id' => 3, 'installment_name' => 'دفعة شهرية'])->first();
            $kp = Installment::where(['payment_method_id' => 3, 'installment_name' => 'دفعة المفتاح'])->first();

            if (!$dp || !$mi || !$kp) {
                return back()->with('error', 'اعدادات الاقساط غير موجودة في النظام.');
            }

            // Down payment NOW
            if ($down_now > 0) {
                Contract_Installment::create([
                    'url_address' => $this->random_string(60),
                    'installment_amount' => $down_now,
                    'installment_date' => $contract_date,
                    'contract_id' => $contract->id,
                    'installment_id' => $dp->id,
                    'user_id_create' => $request->user_id_create,
                    'sequence_number' => $seq++,
                ]);
            }

            // Monthly loop
            for ($i = 1; $i <= $months; $i++) {
                $extra = 0;
                if ($deferred_type === 'spread' && $i <= $deferred_months) {
                    $extra = $per_deferred;
                    if ($i === $deferred_months && $remainder > 0) {
                        $extra += $remainder;
                    }
                } elseif ($deferred_type === "lump-6" && $i === 6) {
                    $extra = $deferred_total;
                } elseif ($deferred_type === "lump-7" && $i === 7) {
                    $extra = $deferred_total;
                }

                $amount = $monthly + $extra;
                $monthly_total += $amount;

                Contract_Installment::create([
                    'url_address' => $this->random_string(60),
                    'installment_amount' => $amount,
                    'installment_date' => $contract_date->copy()->addMonths($i),
                    'contract_id' => $contract->id,
                    'installment_id' => $mi->id,
                    'user_id_create' => $request->user_id_create,
                    'sequence_number' => $seq++,
                ]);
            }

            // Key
            if ($key > 0) {
                Contract_Installment::create([
                    'url_address' => $this->random_string(60),
                    'installment_amount' => $key,
                    'installment_date' => $contract_date->copy()->addMonths($months + 1),
                    'contract_id' => $contract->id,
                    'installment_id' => $kp->id,
                    'user_id_create' => $request->user_id_create,
                    'sequence_number' => $seq++,
                ]);
            }

            // Validate total
            $total = $down_now + $monthly_total + $key;
            if (abs($total - $contract_amount) > 0.01) {
                Contract_Installment::where('contract_id', $contract->id)->delete();
                $contract->delete();
                return back()->with(
                    "error",
                    "مجموع الخطة لا يساوي مبلغ العقد:<br>
             نقد: " . number_format($down_now) . "<br>
             مؤجل: " . number_format($deferred_total) . "<br>
             أقساط: " . number_format($monthly_total) . "<br>
             مفتاح: " . number_format($key) . "<br>
             ─────────────<br>
             مجموع الخطة: " . number_format($total) . "<br>
             مبلغ العقد: " . number_format($contract_amount)
                );
            }
        }

        // ========== ✅ Method 4: Flexible Plan ==========
        elseif ($method == 4) {

            $down = $clean($request->down_payment_amount);
            $down_now = $clean($request->down_payment_installment);
            $monthly = $clean($request->monthly_installment_amount);
            $months = (int) $request->number_of_months;
            $key = $clean($request->key_payment_amount);

            $deferred_total = max(0, $down - $down_now);
            $piece = $clean($request->down_payment_deferred_installment ?? 0);
            $freq = max(1, (int)($request->down_payment_deferred_frequency ?? 1));

            $monthly_freq = max(1, (int)($request->monthly_frequency ?? 1));
            $start_date = Carbon::parse($request->monthly_start_date ?? $contract_date->copy()->addMonth());

            // Installments table IDs
            $dp = Installment::where(['payment_method_id' => 4, 'installment_name' => 'دفعة مقدمة'])->first();
            $mi = Installment::where(['payment_method_id' => 4, 'installment_name' => 'دفعة شهرية'])->first();
            $kp = Installment::where(['payment_method_id' => 4, 'installment_name' => 'دفعة المفتاح'])->first();

            // Fallback to method 3 setup if method 4 installment types don't exist
            if (!$dp) {
                $dp = Installment::where(['payment_method_id' => 3, 'installment_name' => 'دفعة مقدمة'])->first();
            }
            if (!$mi) {
                $mi = Installment::where(['payment_method_id' => 3, 'installment_name' => 'دفعة شهرية'])->first();
            }
            if (!$kp) {
                $kp = Installment::where(['payment_method_id' => 3, 'installment_name' => 'دفعة المفتاح'])->first();
            }

            if (!$dp || !$mi || !$kp) {
                return back()->with('error', 'اعدادات الاقساط غير موجودة في النظام.');
            }

            $seq = 1;

            // Down payment NOW (cash portion)
            if ($down_now > 0) {
                Contract_Installment::create([
                    'url_address' => $this->random_string(60),
                    'installment_amount' => $down_now,
                    'installment_date' => $contract_date,
                    'contract_id' => $contract->id,
                    'installment_id' => $dp->id,
                    'user_id_create' => $request->user_id_create,
                    'sequence_number' => $seq++,
                ]);
            }

            // Deferred down payment (spread over multiple installments)
            if ($deferred_total > 0) {
                // If piece amount not specified, pay all deferred in one installment
                if ($piece <= 0) {
                    $piece = $deferred_total;
                }

                $cnt = (int)ceil($deferred_total / $piece);
                $remain = $deferred_total;

                for ($i = 1; $i <= $cnt; $i++) {
                    $x = min($piece, $remain);
                    $remain -= $x;

                    Contract_Installment::create([
                        'url_address' => $this->random_string(60),
                        'installment_amount' => $x,
                        'installment_date' => $contract_date->copy()->addMonths($i * $freq),
                        'contract_id' => $contract->id,
                        'installment_id' => $dp->id,
                        'user_id_create' => $request->user_id_create,
                        'sequence_number' => $seq++,
                    ]);
                }
            }

            // Monthly installments with custom frequency
            for ($i = 0; $i < $months; $i++) {
                Contract_Installment::create([
                    'url_address' => $this->random_string(60),
                    'installment_amount' => $monthly,
                    'installment_date' => $start_date->copy()->addMonths($i * $monthly_freq),
                    'contract_id' => $contract->id,
                    'installment_id' => $mi->id,
                    'user_id_create' => $request->user_id_create,
                    'sequence_number' => $seq++,
                ]);
            }

            // Key payment (after last monthly installment)
            if ($key > 0) {
                $lastMonthly = $start_date->copy()->addMonths(max(0, $months - 1) * $monthly_freq);
                $keyDate = $lastMonthly->copy()->addMonth();

                Contract_Installment::create([
                    'url_address' => $this->random_string(60),
                    'installment_amount' => $key,
                    'installment_date' => $keyDate,
                    'contract_id' => $contract->id,
                    'installment_id' => $kp->id,
                    'user_id_create' => $request->user_id_create,
                    'sequence_number' => $seq++,
                ]);
            }

            // Final validation check
            $total = $down_now + $deferred_total + ($monthly * $months) + $key;
            if (abs($total - $contract_amount) > 0.01) {
                Contract_Installment::where('contract_id', $contract->id)->delete();
                $contract->delete();
                return back()->with(
                    "error",
                    "مجموع الخطة لا يساوي مبلغ العقد:<br>
             نقد: " . number_format($down_now) . "<br>
             مؤجل: " . number_format($deferred_total) . "<br>
             أقساط: " . number_format($monthly * $months) . " (" . $months . " × " . number_format($monthly) . ")<br>
             مفتاح: " . number_format($key) . "<br>
             ─────────────<br>
             مجموع الخطة: " . number_format($total) . "<br>
             مبلغ العقد: " . number_format($contract_amount) . "<br>
             الفارق: " . number_format($total - $contract_amount)
                );
            }
        }

        // ========== ✅ Method 1: Full Payment ==========
        elseif ($method == 1) {

            $installments = Installment::where('payment_method_id', 1)->get();

            if ($installments->isEmpty()) {
                return back()->with('error', 'اعدادات الاقساط غير موجودة في النظام.');
            }

            $seq = 1;

            foreach ($installments as $ins) {
                Contract_Installment::create([
                    'url_address' => $this->random_string(60),
                    'installment_amount' => $contract_amount,
                    'installment_date' => $contract_date,
                    'contract_id' => $contract->id,
                    'installment_id' => $ins->id,
                    'user_id_create' => $request->user_id_create,
                    'sequence_number' => $seq++,
                ]);
            }
        }

        // ========== ✅ Method 2: Percentage / Fixed Plan ==========
        elseif ($method == 2) {

            $installments = Installment::where('payment_method_id', 2)->get();

            if ($installments->isEmpty()) {
                return back()->with('error', 'اعدادات الاقساط غير موجودة في النظام.');
            }

            $seq = 1;

            foreach ($installments as $ins) {
                $amount = $ins->installment_percent * $contract_amount;

                Contract_Installment::create([
                    'url_address' => $this->random_string(60),
                    'installment_amount' => $amount,
                    'installment_date' => $contract_date->copy()->addMonths($ins->installment_period),
                    'contract_id' => $contract->id,
                    'installment_id' => $ins->id,
                    'user_id_create' => $request->user_id_create,
                    'sequence_number' => $seq++,
                ]);
            }
        }

        return redirect()->route('contract.temp', $contract->url_address)
            ->with('success', 'تم إنشاء العقد بنجاح.');
    }



    public function accept(string $url_address)
    {
        $contract = Contract::where('url_address', '=', $url_address)->first();
        if ($contract->stage === 'temporary') {
            $contract->accept();
            return redirect()->route('contract.show', $contract->url_address)
                ->with('success', 'تم قبول العقد.');
        }

        $ip = $this->getIPAddress();
        return view('contract.contract.accessdenied', ['ip' => $ip]);
    }

    public function authenticat(string $url_address)
    {
        $contract = Contract::where('url_address', '=', $url_address)->first();
        if ($contract->stage === 'accepted') {
            $contract->authenticat();


            return redirect()->route('contract.show', $contract->url_address)
                ->with('success', 'تم مصادقة العقد.');
        }

        $ip = $this->getIPAddress();
        return view('contract.contract.accessdenied', ['ip' => $ip]);
    }

    public function temporary(string $url_address)
    {
        $contract = Contract::where('url_address', '=', $url_address)->first();
        if ($contract->stage !== 'temporary') {
            $contract->temporary();

            $admins = User::role('admin')->get(); // Fetch admins

            // Notify admins
            foreach ($admins as $admin) {
                $admin->notify(new ContractAuthNotify($contract));
            }
            return redirect()->route('contract.show', $contract->url_address)
                ->with('success', ' تم ارجاع العقد لمرحلة الحجز الاولي .');
        }

        $ip = $this->getIPAddress();
        return view('contract.contract.accessdenied', ['ip' => $ip]);
    }


    /**
     * Terminate the specified contract while preserving associated payments.
     */
    public function terminate(string $url_address)
    {
        $contract = Contract::where('url_address', '=', $url_address)->first();

        if (!$contract) {
            $ip = $this->getIPAddress();
            return view('contract.contract.accessdenied', ['ip' => $ip]);
        }

        if ($contract->stage === 'terminated') {
            return redirect()->route('contract.show', $contract->url_address)
                ->with('error', 'العقد مفسوخ مسبقاً.');
        }

        $contract->terminate();

        $admins = User::role('admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new ContractTerminateNotify($contract));
        }

        return redirect()->route('contract.show', $contract->url_address)
            ->with('success', 'تم فسخ العقد بنجاح مع الاحتفاظ بالدفعات السابقة.');
    }
    /**
     * Display the specified resource.
     */
    public function show(string $url_address)
    {
        // Retrieve the contract with necessary relationships
        $contract = Contract::with(['customer', 'building', 'payment_method', 'images'])
            ->where('url_address', '=', $url_address)
            ->first();

        if (!$contract) {
            $ip = $this->getIPAddress();
            return view('contract.contract.accessdenied', ['ip' => $ip]);
        }

        // Retrieve and sort installments for the contract, joining with installments table
        $contract_installments = Contract_Installment::with(['installment', 'payment'])
            ->where('contract_id', $contract->id)
            ->join('installments', 'contract_installments.installment_id', '=', 'installments.id')
            ->select('contract_installments.*') // Select only contract_installments columns
            ->get();

        // Initialize variable payment details
        $variable_payment_details = [
            'down_payment_amount' => 0,
            'monthly_installment_amount' => 0,
            'number_of_months' => 1,
            'key_payment_amount' => 0,
        ];

        // Populate variable payment details if payment method is "دفعات متغيرة"
        if ($contract->payment_method->method_name === 'دفعات متغيرة') {
            foreach ($contract_installments as $installment) {
                $installment_name = $installment->installment->installment_name;
                if ($installment_name === 'الدفعة المقدمة') {
                    $variable_payment_details['down_payment_amount'] = $installment->installment_amount;
                } elseif ($installment_name === 'شهري') {
                    $variable_payment_details['monthly_installment_amount'] = $installment->installment_amount;
                    $variable_payment_details['number_of_months'] = $contract_installments->where('installment.installment_name', 'شهري')->count();
                } elseif ($installment_name === 'دفعة المفتاح') {
                    $variable_payment_details['key_payment_amount'] = $installment->installment_amount;
                }
            }
        }

        // Count due installments
        $currentDate = Carbon::now()->format('Y-m-d');
        $due_installments_count = $contract_installments->filter(function ($installment) use ($currentDate) {
            // Check if the installment date is due and no payment is made or payment is not approved
            return $installment->installment_date <= $currentDate &&
                (!$installment->payment || !$installment->payment->approved);
        })->count();

        // Count pending payments (not approved yet)
        $pending_payments_count = $contract->payments()->where('approved', false)->count();

        // Check if the contract exists and render the view
        if (isset($contract)) {
            return view('contract.contract.show', compact(
                'contract',
                'contract_installments',
                'due_installments_count',
                'pending_payments_count',
                'variable_payment_details'
            ));
        } else {
            $ip = $this->getIPAddress();
            return view('contract.contract.accessdenied', ['ip' => $ip]);
        }
    }

    public function temp(string $url_address)
    {
        // Retrieve the contract with necessary relationships
        $contract = Contract::with(['customer', 'building', 'payment_method', 'images'])
            ->where('url_address', '=', $url_address)
            ->first();

        if (isset($contract)) {

            return view('contract.contract.temp', compact(['contract']));
        } else {
            $ip = $this->getIPAddress();
            return view('contract.contract.accessdenied', ['ip' => $ip]);
        }
    }

    public function appendix(string $url_address)
    {
        $contract = Contract::with(['customer', 'building', 'payment_method'])
            ->where('url_address', '=', $url_address)
            ->first();

        if (!$contract) {
            $ip = $this->getIPAddress();
            return view('contract.contract.accessdenied', ['ip' => $ip]);
        }

        $contract_installments = Contract_Installment::with(['installment', 'payment'])
            ->where('contract_id', $contract->id)
            ->orderBy('installment_date')
            ->get();

        // ✅ متغيرات خطة الدفع المتغيرة
        $variable_payment_details = [
            'down_payment_amount' => 0,
            'monthly_installment_amount' => 0,
            'number_of_months' => 0,
            'key_payment_amount' => 0,
        ];

        // ✅ Check for BOTH payment method 3 AND 4 (دفعات متغيرة AND خطة دفع مرنة)
        if (in_array($contract->contract_payment_method_id, [3, 4])) {

            // ✅ احسب إجمالي الدفعات المقدمة (sum of all down payments)
            $variable_payment_details['down_payment_amount'] = $contract_installments
                ->where('installment.installment_name', 'دفعة مقدمة')
                ->sum('installment_amount');

            // ✅ احسب الأقساط الشهرية
            $monthlyInstallments = $contract_installments
                ->where('installment.installment_name', 'دفعة شهرية');

            if ($monthlyInstallments->count() > 0) {
                $variable_payment_details['monthly_installment_amount'] = $monthlyInstallments->first()->installment_amount;
                $variable_payment_details['number_of_months'] = $monthlyInstallments->count();
            }

            // ✅ احسب إجمالي دفعات المفتاح (sum of all key payments)
            $variable_payment_details['key_payment_amount'] = $contract_installments
                ->where('installment.installment_name', 'دفعة المفتاح')
                ->sum('installment_amount');

            // ✅ إذا ما عندي دفعة مقدمة لكن عندي مدفوعات، استخدم المدفوعات
            if ($variable_payment_details['down_payment_amount'] == 0) {
                $paidAmount = $contract->payments()
                    ->where('approved', true)
                    ->sum('payment_amount');

                if ($paidAmount > 0) {
                    $variable_payment_details['down_payment_amount'] = $paidAmount;
                }
            }
        }

        // ✅ معالجة مجموع العقد
        $calculatedTotal =
            $variable_payment_details['down_payment_amount'] +
            ($variable_payment_details['monthly_installment_amount'] * $variable_payment_details['number_of_months']) +
            $variable_payment_details['key_payment_amount'];

        if ($calculatedTotal != $contract->contract_amount && $calculatedTotal > 0) {
            $variable_payment_details['down_payment_amount'] =
                $contract->contract_amount -
                (
                    ($variable_payment_details['monthly_installment_amount'] * $variable_payment_details['number_of_months']) +
                    $variable_payment_details['key_payment_amount']
                );

            $calculatedTotal =
                $variable_payment_details['down_payment_amount'] +
                ($variable_payment_details['monthly_installment_amount'] * $variable_payment_details['number_of_months']) +
                $variable_payment_details['key_payment_amount'];

            $variable_payment_details['calculated_total'] = $calculatedTotal;
        }

        return view('contract.contract.appendix', compact(
            'contract',
            'contract_installments',
            'variable_payment_details'
        ));
    }

    public function reserve(string $url_address)
    {
        // Retrieve the contract with necessary relationships
        $contract = Contract::with(['customer', 'building', 'payment_method', 'images'])
            ->where('url_address', '=', $url_address)
            ->first();

        if (isset($contract)) {

            return view('contract.contract.reserve', compact(['contract']));
        } else {
            $ip = $this->getIPAddress();
            return view('contract.contract.accessdenied', ['ip' => $ip]);
        }
    }

    public function print(string $url_address)
    {
        $contract = Contract::with(['customer', 'building', 'payment_method'])
            ->where('url_address', '=', $url_address)
            ->first();

        if (!$contract) {
            $ip = $this->getIPAddress();
            return view('contract.contract.accessdenied', ['ip' => $ip]);
        }

        $contract_installments = Contract_Installment::with(['installment', 'payment'])
            ->where('contract_id', $contract->id)
            ->get();

        // ✅ Add this block
        $variable_payment_details = [
            'down_payment_amount' => 0,
            'monthly_installment_amount' => 0,
            'number_of_months' => 1,
            'key_payment_amount' => 0,
        ];

        if ($contract->payment_method->method_name === 'دفعات متغيرة') {
            // احسب المبالغ المدفوعة فعلياً
            $paidAmount = $contract->payments()
                ->where('approved', true)
                ->sum('payment_amount');

            foreach ($contract_installments as $installment) {
                $installment_name = $installment->installment->installment_name;

                if ($installment_name === 'دفعة مقدمة') {
                    $variable_payment_details['down_payment_amount'] = $installment->installment_amount;
                } elseif ($installment_name === 'دفعة شهرية') {
                    $variable_payment_details['monthly_installment_amount'] = $installment->installment_amount;
                    $variable_payment_details['number_of_months'] = $contract_installments
                        ->where('installment.installment_name', 'دفعة شهرية')
                        ->count();
                } elseif ($installment_name === 'دفعة المفتاح') {
                    $variable_payment_details['key_payment_amount'] = $installment->installment_amount;
                }
            }

            // ✅ إذا ما عندي "دفعة مقدمة" لكن عندي مبالغ مسددة → اعتبرها دفعة مقدمة
            if ($variable_payment_details['down_payment_amount'] == 0 && $paidAmount > 0) {
                $variable_payment_details['down_payment_amount'] = $paidAmount;
            }
        }

        $calculatedTotal =
            $variable_payment_details['down_payment_amount'] +
            ($variable_payment_details['monthly_installment_amount'] * $variable_payment_details['number_of_months']) +
            $variable_payment_details['key_payment_amount'];

        if ($calculatedTotal != $contract->contract_amount) {
            // اجعل الدفعة المقدمة = المبلغ الكلي - (الأقساط + دفعة المفتاح)
            $variable_payment_details['down_payment_amount'] =
                $contract->contract_amount -
                (
                    ($variable_payment_details['monthly_installment_amount'] * $variable_payment_details['number_of_months']) +
                    $variable_payment_details['key_payment_amount']
                );

            // أعِد حساب المجموع بعد التعديل
            $calculatedTotal =
                $variable_payment_details['down_payment_amount'] +
                ($variable_payment_details['monthly_installment_amount'] * $variable_payment_details['number_of_months']) +
                $variable_payment_details['key_payment_amount'];

            $variable_payment_details['calculated_total'] = $calculatedTotal;
        }
        return view('contract.contract.print', compact(
            'contract',
            'contract_installments',
            'variable_payment_details'
        ));
    }



    public function add_payment(string $url_address)
    {
        $contract_installment = Contract_Installment::with(['contract.building', 'contract.customer'])
            ->where('url_address', '=', $url_address)
            ->first();

        if (isset($contract_installment)) {
            // NEW: Check if the contract uses the variable payment plan and ensure down payment is paid first
            if ($contract_installment->contract->contract_payment_method_id == 3) {
                $down_payment = Contract_Installment::where('contract_id', $contract_installment->contract_id)
                    ->whereHas('installment', function ($query) {
                        $query->where('installment_name', 'دفعة مقدمة');
                    })->first();

                if ($down_payment && !$down_payment->paid && $contract_installment->id != $down_payment->id) {
                    return redirect()->route('contract.show', $contract_installment->contract->url_address)
                        ->with('error', 'يجب دفع الدفعة المقدمة أولاً.');
                }
            }

            if ($contract_installment->payment) {
                return redirect()->route('payment.show', $contract_installment->payment->url_address)
                    ->with('error', 'تم استلام هذه الدفعة مسبقا يرجى التأكد');
            }

            $payment = Payment::create([
                'url_address' => $this->random_string(60),
                'payment_amount' => $contract_installment->installment_amount,
                'payment_date' => Carbon::now()->format('Y-m-d'),
                'payment_contract_id' => $contract_installment->contract_id,
                'contract_installment_id' => $contract_installment->id,
                'user_id_create' => auth()->user()->id,
            ]);

            $accountants = User::role('accountant')->get();

            foreach ($accountants as $accountant) {
                $accountant->notify(new PaymentNotify($payment));
            }

            return redirect()->route('payment.show', $payment->url_address);
        } else {
            $ip = $this->getIPAddress();
            return view('contract.contract.accessdenied', ['ip' => $ip]);
        }
    }

    public function dueInstallments($contract_id = null)
    {
        $currentDate = Carbon::now()->format('Y-m-d');

        // Initialize the query for due installments, excluding terminated contracts
        $query = Contract_Installment::with(['contract.customer', 'contract.building', 'payment'])
            ->leftJoin('payments', 'contract_installments.id', '=', 'payments.contract_installment_id')
            ->where('contract_installments.installment_date', '<=', $currentDate)
            ->whereHas('contract', function ($contractQuery) {
                $contractQuery->whereNotIn('stage', ['terminated']);
            })
            ->where(function ($query) {
                $query->whereNull('payments.id')
                    ->orWhere('payments.approved', false);
            })
            ->select('contract_installments.*');

        // If a contract_id is provided, filter by that contract
        if ($contract_id) {
            $query->where('contract_id', $contract_id);
        }

        // Execute the query and group by customer and contract
        $dueInstallments = $query->get()
            ->groupBy(function ($installment) {
                return $installment->contract->customer->id;
            })
            ->map(function ($installments) {
                return $installments->groupBy(function ($installment) {
                    return $installment->contract->id;
                });
            });

        // Return view with the due installments grouped by customer and contract
        return view('contract.contract.due_installments', compact(['dueInstallments', 'contract_id']));
    }

    /**
     * Display the statement of account for the given contract.
     *
     * @param  string $url_address
     * @return \Illuminate\Http\Response
     */
    public function statement(string $url_address)
    {
        // Retrieve the contract with necessary relationships and aggregate sums

        $contract = Contract::with([
            'customer',
            'building',
            'payment_method',
            'services',
            'contract_installments.installment',
        ])
            ->withSum('contract_installments', 'installment_amount') // Sum of installment amounts
            ->withSum('services', 'service_amount') // Sum of service amounts
            ->withSum('transfers', 'transfer_amount')
            ->where('url_address', $url_address)
            ->first();

        // Sum of approved payments
        $total_approved_payments = $contract->payments()->where('approved', true)->sum('payment_amount');


        // Prepare the data for the view
        $data = [
            'contract' => $contract,
            'total_installments' => $contract->contract_installments_sum_installment_amount,
            'total_payments' => $total_approved_payments,
            'total_services' => $contract->services_sum_service_amount,
            'total_transfers' => $contract->transfers_sum_transfer_amount,
            'outstanding_amount' =>
            $contract->contract_installments_sum_installment_amount +
                $contract->services_sum_service_amount +
                $contract->transfers_sum_transfer_amount -
                $total_approved_payments,
        ];



        // Return the view with the prepared data
        return view('contract.contract.statement', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, string $url_address)
    {
        $contract = Contract::where('url_address', '=', $url_address)
            ->with('payments', 'building')
            ->first();

        if (!$contract) {
            $ip = $this->getIPAddress();
            return view('contract.contract.accessdenied', ['ip' => $ip]);
        }

        $oldMethod = $contract->contract_payment_method_id;

        // 🚨 Block edits if contract has payments and not temporary,
        // EXCEPT when migrating 2 → 3
        if ($contract->payments->count() > 0 && $contract->stage != 'temporary' && !($oldMethod == 2)) {
            return redirect()->route('contract.index')
                ->with('error', 'لا يمكن تعديل العقد لأنه يحتوي على دفعات وتم قبوله.');
        }

        // 🔑 If temporary contract with payments → require password
        if ($contract->payments->count() > 0 && $contract->stage == 'temporary') {
            if ($request->has('password')) {
                if (!Hash::check($request->password, auth()->user()->password)) {
                    return redirect()->route('contract.index')
                        ->with('error', 'كلمة المرور غير صحيحة.');
                }
            } else {
                return redirect()->route('contract.index')
                    ->with('error', 'لا يمكن تعديل العقد لأنه يحتوي على دفعات وتم قبوله. يرجى إدخال كلمة المرور لتأكيد التعديل.');
            }
        }

        $customers = Customer::all();
        $buildings = Building::where(function ($query) use ($contract) {
            $query->doesntHave('contract')
                ->orWhereDoesntHave('contract', function ($subQuery) {
                    $subQuery->whereNotIn('stage', ['terminated']);
                })
                ->orWhere('id', $contract->contract_building_id);
        })->get();

        $payment_methods = Payment_Method::all();

        // 📝 Fetch variable payment plan details for editing
        $variable_payment_details = [];
        if ($contract->contract_payment_method_id == 3) {
            $contract_installments = $contract->contract_installments()->with('installment')->get();
            $down_payment = $contract_installments->where('installment.installment_name', 'دفعة مقدمة')->first();
            $monthly_installments = $contract_installments->where('installment.installment_name', 'دفعة شهرية');
            $key_payment = $contract_installments->where('installment.installment_name', 'دفعة المفتاح')->first();

            $variable_payment_details = [
                'down_payment_amount' => $down_payment ? $down_payment->installment_amount : 0,
                'monthly_installment_amount' => $monthly_installments->first() ? $monthly_installments->first()->installment_amount : 0,
                'number_of_months' => $monthly_installments->count(),
                'key_payment_amount' => $key_payment ? $key_payment->installment_amount : 0,
            ];
        }

        // NEW: Pre-calculate paid installments if contract is method 2
        $paidAmount = 0;
        if ($contract->contract_payment_method_id == 2 && $contract->payments->count() > 0) {
            $paidAmount = $contract->payments()->where('approved', true)->sum('payment_amount');
        }

        return view('contract.contract.edit', compact(
            'contract',
            'customers',
            'buildings',
            'payment_methods',
            'variable_payment_details',
            'paidAmount' // 👈 now available in Blade
        ));
    }


    /**
     * Update the specified resource in storage.
     */
    /**
     * Update the specified resource in storage.
     */
    public function update(ContractRequest $request, string $url_address)
    {
        $contract = Contract::where('url_address', $url_address)
            ->with(['payments', 'contract_installments.installment'])
            ->first();

        if (!$contract) {
            $ip = $this->getIPAddress();
            return view('contract.contract.accessdenied', ['ip' => $ip]);
        }

        $oldMethod = $contract->contract_payment_method_id;
        $newMethod = (int) $request->input('contract_payment_method_id');

        // Prevent edits if already has payments and not temporary,
        // EXCEPT when migrating 2 → 3 or 2 → 4
        if ($contract->payments->count() > 0 && $contract->stage != 'temporary' && !($oldMethod == 2 && in_array($newMethod, [3, 4]))) {
            return redirect()->route('contract.index')
                ->with('error', 'لا يمكن تعديل العقد لأنه يحتوي على دفعات وتم قبوله.');
        }

        $contract->update($request->validated());
        $contract_date = Carbon::parse($request->contract_date);

        // Helper function
        $clean = fn($v) => (float) str_replace(',', '', $v ?? 0);

        // ======================================================
        // CASE 1: Migration from Method 2 → 3
        // ======================================================
        if ($oldMethod == 2 && $newMethod == 3) {
            $paidInstallments = $contract->contract_installments()
                ->whereHas('payment', function ($q) {
                    $q->where('approved', true);
                })
                ->orderBy('installment_date')
                ->get();

            $paidAmount = $paidInstallments->sum('installment_amount');

            // Delete only unpaid installments
            $contract->contract_installments()
                ->whereDoesntHave('payment', function ($q) {
                    $q->where('approved', true);
                })
                ->delete();

            $down_payment_amount = $clean($request->down_payment_amount);
            $down_payment_installment = $clean($request->down_payment_installment);
            $monthly_installment_amount = $clean($request->monthly_installment_amount);
            $number_of_months = (int) $request->input('number_of_months', 36);
            $deferred_type = $request->input('deferred_type', 'none');
            $deferred_months = (int) $request->input('deferred_months', 0);
            $key_payment_amount = $clean($request->key_payment_amount);
            $deferred = $down_payment_amount - $down_payment_installment;

            $down_payment_installment_record = Installment::where('payment_method_id', 3)
                ->where('installment_name', 'دفعة مقدمة')->first();
            $monthly_installment = Installment::where('payment_method_id', 3)
                ->where('installment_name', 'دفعة شهرية')->first();
            $key_payment_installment = Installment::where('payment_method_id', 3)
                ->where('installment_name', 'دفعة المفتاح')->first();

            if (!$down_payment_installment_record || !$monthly_installment || !$key_payment_installment) {
                return back()->with('error', 'اعدادات الاقساط غير موجودة في النظام.');
            }

            $sequence = $contract->contract_installments()->max('sequence_number') ?? 0;

            // If nothing is paid yet → create down payment at contract_date
            if ($paidAmount == 0 && $down_payment_installment > 0) {
                Contract_Installment::create([
                    'url_address'        => $this->random_string(60),
                    'installment_amount' => $down_payment_installment,
                    'installment_date'   => Carbon::parse($request->contract_date),
                    'contract_id'        => $contract->id,
                    'installment_id'     => $down_payment_installment_record->id,
                    'user_id_update'     => $request->user_id_update,
                    'sequence_number'    => ++$sequence,
                ]);
            }

            // Start monthly installments AFTER last paid installment date, or from contract_date if none
            $startDate = $paidInstallments->count() > 0
                ? Carbon::parse($paidInstallments->last()->installment_date)
                : Carbon::parse($request->contract_date);
            $deferred_per_month = $deferred_type === 'spread' && $deferred_months > 0 ? floor($deferred / $deferred_months) : 0;
            $remainder = $deferred_type === 'spread' && $deferred_months > 0 ? $deferred % $deferred_months : 0;

            for ($i = 1; $i <= $number_of_months; $i++) {
                $extra = 0;
                if ($deferred_type === 'spread' && $deferred > 0 && $i <= $deferred_months) {
                    $extra = $deferred_per_month;
                    if ($i === $deferred_months && $remainder > 0) {
                        $extra += $remainder;
                    }
                } elseif ($deferred_type === 'lump-6' && $i === 6 && $deferred > 0) {
                    $extra = $deferred;
                } elseif ($deferred_type === 'lump-7' && $i === 7 && $deferred > 0) {
                    $extra = $deferred;
                }
                $monthly_amount = $monthly_installment_amount + $extra;

                Contract_Installment::create([
                    'url_address'        => $this->random_string(60),
                    'installment_amount' => $monthly_amount,
                    'installment_date'   => $startDate->copy()->addMonths($i),
                    'contract_id'        => $contract->id,
                    'installment_id'     => $monthly_installment->id,
                    'user_id_update'     => $request->user_id_update,
                    'sequence_number'    => ++$sequence,
                ]);
            }

            // Key payment after the last monthly installment
            if ($key_payment_amount > 0) {
                Contract_Installment::create([
                    'url_address'        => $this->random_string(60),
                    'installment_amount' => $key_payment_amount,
                    'installment_date'   => $startDate->copy()->addMonths($number_of_months + 1),
                    'contract_id'        => $contract->id,
                    'installment_id'     => $key_payment_installment->id,
                    'user_id_update'     => $request->user_id_update,
                    'sequence_number'    => ++$sequence,
                ]);
            }
        }

        // ======================================================
        // CASE 1B: Migration from Method 2 → 4 (NEW)
        // ======================================================
        elseif ($oldMethod == 2 && $newMethod == 4) {
            $paidInstallments = $contract->contract_installments()
                ->whereHas('payment', function ($q) {
                    $q->where('approved', true);
                })
                ->orderBy('installment_date')
                ->get();

            $paidAmount = $paidInstallments->sum('installment_amount');

            // Delete only unpaid installments
            $contract->contract_installments()
                ->whereDoesntHave('payment', function ($q) {
                    $q->where('approved', true);
                })
                ->delete();

            $down = $clean($request->down_payment_amount);
            $down_now = $clean($request->down_payment_installment);
            $monthly = $clean($request->monthly_installment_amount);
            $months = (int) $request->number_of_months;
            $key = $clean($request->key_payment_amount);

            $deferred_total = max(0, $down - $down_now);
            $piece = $clean($request->down_payment_deferred_installment ?? 0);
            $freq = max(1, (int)($request->down_payment_deferred_frequency ?? 1));

            $monthly_freq = max(1, (int)($request->monthly_frequency ?? 1));
            $start_date = Carbon::parse($request->monthly_start_date ?? $contract_date->copy()->addMonth());

            // Installments table IDs
            $dp = Installment::where(['payment_method_id' => 4, 'installment_name' => 'دفعة مقدمة'])->first();
            $mi = Installment::where(['payment_method_id' => 4, 'installment_name' => 'دفعة شهرية'])->first();
            $kp = Installment::where(['payment_method_id' => 4, 'installment_name' => 'دفعة المفتاح'])->first();

            // Fallback to method 3
            if (!$dp) $dp = Installment::where(['payment_method_id' => 3, 'installment_name' => 'دفعة مقدمة'])->first();
            if (!$mi) $mi = Installment::where(['payment_method_id' => 3, 'installment_name' => 'دفعة شهرية'])->first();
            if (!$kp) $kp = Installment::where(['payment_method_id' => 3, 'installment_name' => 'دفعة المفتاح'])->first();

            if (!$dp || !$mi || !$kp) {
                return back()->with('error', 'اعدادات الاقساط غير موجودة في النظام.');
            }

            $sequence = $contract->contract_installments()->max('sequence_number') ?? 0;

            // If nothing is paid yet → create down payment at contract_date
            if ($paidAmount == 0 && $down_now > 0) {
                Contract_Installment::create([
                    'url_address'        => $this->random_string(60),
                    'installment_amount' => $down_now,
                    'installment_date'   => $contract_date,
                    'contract_id'        => $contract->id,
                    'installment_id'     => $dp->id,
                    'user_id_update'     => $request->user_id_update,
                    'sequence_number'    => ++$sequence,
                ]);
            }

            // Deferred down payment
            if ($deferred_total > 0) {
                if ($piece <= 0) {
                    $piece = $deferred_total;
                }
                $cnt = (int)ceil($deferred_total / $piece);
                $remain = $deferred_total;

                for ($i = 1; $i <= $cnt; $i++) {
                    $x = min($piece, $remain);
                    $remain -= $x;

                    Contract_Installment::create([
                        'url_address'        => $this->random_string(60),
                        'installment_amount' => $x,
                        'installment_date'   => $contract_date->copy()->addMonths($i * $freq),
                        'contract_id'        => $contract->id,
                        'installment_id'     => $dp->id,
                        'user_id_update'     => $request->user_id_update,
                        'sequence_number'    => ++$sequence,
                    ]);
                }
            }

            // Monthly installments
            for ($i = 0; $i < $months; $i++) {
                Contract_Installment::create([
                    'url_address'        => $this->random_string(60),
                    'installment_amount' => $monthly,
                    'installment_date'   => $start_date->copy()->addMonths($i * $monthly_freq),
                    'contract_id'        => $contract->id,
                    'installment_id'     => $mi->id,
                    'user_id_update'     => $request->user_id_update,
                    'sequence_number'    => ++$sequence,
                ]);
            }

            // Key payment
            if ($key > 0) {
                $lastMonthly = $start_date->copy()->addMonths(max(0, $months - 1) * $monthly_freq);
                $keyDate = $lastMonthly->copy()->addMonth();

                Contract_Installment::create([
                    'url_address'        => $this->random_string(60),
                    'installment_amount' => $key,
                    'installment_date'   => $keyDate,
                    'contract_id'        => $contract->id,
                    'installment_id'     => $kp->id,
                    'user_id_update'     => $request->user_id_update,
                    'sequence_number'    => ++$sequence,
                ]);
            }
        }

        // ======================================================
        // CASE 2: Normal Method 3 update (regenerate plan)
        // ======================================================
        elseif ($newMethod == 3) {
            $contract->contract_installments()->delete();

            $down_payment_amount = $clean($request->down_payment_amount);
            $down_payment_installment = $clean($request->down_payment_installment);
            $monthly_installment_amount = $clean($request->monthly_installment_amount);
            $number_of_months = (int) $request->input('number_of_months');
            $deferred_type = $request->input('deferred_type', 'none');
            $deferred_months = (int) $request->input('deferred_months', 0);
            $key_payment_amount = $clean($request->key_payment_amount);
            $deferred = $down_payment_amount - $down_payment_installment;

            $down_payment_installment_record = Installment::where('payment_method_id', 3)
                ->where('installment_name', 'دفعة مقدمة')->first();
            $monthly_installment = Installment::where('payment_method_id', 3)
                ->where('installment_name', 'دفعة شهرية')->first();
            $key_payment_installment = Installment::where('payment_method_id', 3)
                ->where('installment_name', 'دفعة المفتاح')->first();

            if (!$down_payment_installment_record || !$monthly_installment || !$key_payment_installment) {
                return back()->with('error', 'اعدادات الاقساط غير موجودة في النظام.');
            }

            $sequence = 1;

            // Down payment
            if ($down_payment_installment > 0) {
                Contract_Installment::create([
                    'url_address'        => $this->random_string(60),
                    'installment_amount' => $down_payment_installment,
                    'installment_date'   => $contract_date,
                    'contract_id'        => $contract->id,
                    'installment_id'     => $down_payment_installment_record->id,
                    'user_id_update'     => $request->user_id_update,
                    'sequence_number'    => $sequence++,
                ]);
            }

            // Monthly installments
            $deferred_per_month = $deferred_type === 'spread' && $deferred_months > 0 ? floor($deferred / $deferred_months) : 0;
            $remainder = $deferred_type === 'spread' && $deferred_months > 0 ? $deferred % $deferred_months : 0;

            for ($i = 1; $i <= $number_of_months; $i++) {
                $extra = 0;
                if ($deferred_type === 'spread' && $deferred > 0 && $i <= $deferred_months) {
                    $extra = $deferred_per_month;
                    if ($i === $deferred_months && $remainder > 0) {
                        $extra += $remainder;
                    }
                } elseif ($deferred_type === 'lump-6' && $i === 6 && $deferred > 0) {
                    $extra = $deferred;
                } elseif ($deferred_type === 'lump-7' && $i === 7 && $deferred > 0) {
                    $extra = $deferred;
                }
                $monthly_amount = $monthly_installment_amount + $extra;

                Contract_Installment::create([
                    'url_address'        => $this->random_string(60),
                    'installment_amount' => $monthly_amount,
                    'installment_date'   => $contract_date->copy()->addMonths($i),
                    'contract_id'        => $contract->id,
                    'installment_id'     => $monthly_installment->id,
                    'user_id_update'     => $request->user_id_update,
                    'sequence_number'    => $sequence++,
                ]);
            }

            // Key payment
            if ($key_payment_amount > 0) {
                Contract_Installment::create([
                    'url_address'        => $this->random_string(60),
                    'installment_amount' => $key_payment_amount,
                    'installment_date'   => $contract_date->copy()->addMonths($number_of_months + 1),
                    'contract_id'        => $contract->id,
                    'installment_id'     => $key_payment_installment->id,
                    'user_id_update'     => $request->user_id_update,
                    'sequence_number'    => $sequence++,
                ]);
            }
        }

        // ======================================================
        // CASE 2B: Normal Method 4 update (NEW)
        // ======================================================
        elseif ($newMethod == 4) {
            $contract->contract_installments()->delete();

            $down = $clean($request->down_payment_amount);
            $down_now = $clean($request->down_payment_installment);
            $monthly = $clean($request->monthly_installment_amount);
            $months = (int) $request->number_of_months;
            $key = $clean($request->key_payment_amount);

            $deferred_total = max(0, $down - $down_now);
            $piece = $clean($request->down_payment_deferred_installment ?? 0);
            $freq = max(1, (int)($request->down_payment_deferred_frequency ?? 1));

            $monthly_freq = max(1, (int)($request->monthly_frequency ?? 1));
            $start_date = Carbon::parse($request->monthly_start_date ?? $contract_date->copy()->addMonth());

            // Installments table IDs
            $dp = Installment::where(['payment_method_id' => 4, 'installment_name' => 'دفعة مقدمة'])->first();
            $mi = Installment::where(['payment_method_id' => 4, 'installment_name' => 'دفعة شهرية'])->first();
            $kp = Installment::where(['payment_method_id' => 4, 'installment_name' => 'دفعة المفتاح'])->first();

            // Fallback to method 3
            if (!$dp) $dp = Installment::where(['payment_method_id' => 3, 'installment_name' => 'دفعة مقدمة'])->first();
            if (!$mi) $mi = Installment::where(['payment_method_id' => 3, 'installment_name' => 'دفعة شهرية'])->first();
            if (!$kp) $kp = Installment::where(['payment_method_id' => 3, 'installment_name' => 'دفعة المفتاح'])->first();

            if (!$dp || !$mi || !$kp) {
                return back()->with('error', 'اعدادات الاقساط غير موجودة في النظام.');
            }

            $sequence = 1;

            // Down payment NOW
            if ($down_now > 0) {
                Contract_Installment::create([
                    'url_address'        => $this->random_string(60),
                    'installment_amount' => $down_now,
                    'installment_date'   => $contract_date,
                    'contract_id'        => $contract->id,
                    'installment_id'     => $dp->id,
                    'user_id_update'     => $request->user_id_update,
                    'sequence_number'    => $sequence++,
                ]);
            }

            // Deferred down payment
            if ($deferred_total > 0) {
                if ($piece <= 0) {
                    $piece = $deferred_total;
                }
                $cnt = (int)ceil($deferred_total / $piece);
                $remain = $deferred_total;

                for ($i = 1; $i <= $cnt; $i++) {
                    $x = min($piece, $remain);
                    $remain -= $x;

                    Contract_Installment::create([
                        'url_address'        => $this->random_string(60),
                        'installment_amount' => $x,
                        'installment_date'   => $contract_date->copy()->addMonths($i * $freq),
                        'contract_id'        => $contract->id,
                        'installment_id'     => $dp->id,
                        'user_id_update'     => $request->user_id_update,
                        'sequence_number'    => $sequence++,
                    ]);
                }
            }

            // Monthly installments
            for ($i = 0; $i < $months; $i++) {
                Contract_Installment::create([
                    'url_address'        => $this->random_string(60),
                    'installment_amount' => $monthly,
                    'installment_date'   => $start_date->copy()->addMonths($i * $monthly_freq),
                    'contract_id'        => $contract->id,
                    'installment_id'     => $mi->id,
                    'user_id_update'     => $request->user_id_update,
                    'sequence_number'    => $sequence++,
                ]);
            }

            // Key payment
            if ($key > 0) {
                $lastMonthly = $start_date->copy()->addMonths(max(0, $months - 1) * $monthly_freq);
                $keyDate = $lastMonthly->copy()->addMonth();

                Contract_Installment::create([
                    'url_address'        => $this->random_string(60),
                    'installment_amount' => $key,
                    'installment_date'   => $keyDate,
                    'contract_id'        => $contract->id,
                    'installment_id'     => $kp->id,
                    'user_id_update'     => $request->user_id_update,
                    'sequence_number'    => $sequence++,
                ]);
            }
        }

        // ======================================================
        // CASE 3: Method 1 & 2 logic (original code unchanged)
        // ======================================================
        elseif ($newMethod == 2 && $contract->contract_installments->count() == 12) {
            foreach ($contract->contract_installments as $contract_installment) {
                $contract_installment->update([
                    'installment_amount' => $contract_installment->installment->installment_percent * $request->contract_amount,
                    'installment_date'   => Carbon::parse($contract->contract_date)->addMonth($contract_installment->installment->installment_period),
                    'sequence_number'    => $contract_installment->installment->installment_number,
                ]);
            }
        } elseif ($newMethod == 1 && $contract->contract_installments->count() == 1) {
            foreach ($contract->contract_installments as $contract_installment) {
                $contract_installment->update([
                    'installment_amount' => $request->contract_amount,
                    'installment_date'   => $request->contract_date,
                    'sequence_number'    => $contract_installment->installment->installment_number,
                ]);
            }
        } elseif ($newMethod == 2 && $contract->contract_installments->count() != 12) {
            $contract->contract_installments()->delete();
            $installments = Installment::where('payment_method_id', 2)->get();

            if ($installments->isEmpty()) {
                return back()->with('error', 'اعدادات الاقساط غير موجودة في النظام.');
            }

            foreach ($installments as $installment) {
                Contract_Installment::create([
                    'url_address'        => $this->random_string(60),
                    'installment_amount' => $installment->installment_percent * $request->contract_amount,
                    'installment_date'   => Carbon::parse($contract->contract_date)->addMonth($installment->installment_period),
                    'contract_id'        => $contract->id,
                    'installment_id'     => $installment->id,
                    'user_id_update'     => $request->user_id_update,
                    'sequence_number'    => $installment->installment_number,
                ]);
            }
        } elseif ($newMethod == 1 && $contract->contract_installments->count() != 1) {
            $contract->contract_installments()->delete();
            $installments = Installment::where('payment_method_id', 1)->get();

            if ($installments->isEmpty()) {
                return back()->with('error', 'اعدادات الاقساط غير موجودة في النظام.');
            }

            foreach ($installments as $installment) {
                Contract_Installment::create([
                    'url_address'        => $this->random_string(60),
                    'installment_amount' => $request->contract_amount,
                    'installment_date'   => $request->contract_date,
                    'contract_id'        => $contract->id,
                    'installment_id'     => $installment->id,
                    'user_id_update'     => $request->user_id_update,
                    'sequence_number'    => $installment->installment_number,
                ]);
            }
        }

        return redirect()->route('contract.show', ['url_address' => $contract->url_address])
            ->with('success', 'تم تعديل العقد بنجاح.');
    }




    /**
     * Update all contracts and installments.
     *
     * @param ContractUpdateService $contractUpdateService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateAllContracts(ContractUpdateService $contractUpdateService)
    {
        $contractUpdateService->updateAllContracts();

        return redirect()->route('contract.index')->with('success', 'All contracts and installments updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $url_address)
    {
        $contract = Contract::where('url_address', $url_address)->first();

        // Check if the contract exists
        if (!$contract) {
            return redirect()->route('contract.index')
                ->with('error', 'العقد غير موجود');
        }

        // Check if the contract has associated payments
        if ($contract->payments()->exists()) {
            return redirect()->route('contract.index')
                ->with('error', 'لا يمكن حذف العقد لأن هناك مدفوعات مرتبطة به');
        }

        // Delete associated installments
        Contract_Installment::where('contract_id', $contract->id)->delete();

        // Delete the contract
        $contract->delete();

        return redirect()->route('contract.index')
            ->with('success', 'تمت حذف بيانات العقد بنجاح');
    }

    public function onmap(string $url_address)
    {
        $contract = Contract::with('building')->where('url_address', $url_address)->first();

        if (isset($contract)) {
            return view('contract.contract.onmap', compact(['contract']));
        } else {
            $ip = $this->getIPAddress();
            return view('contract.contract.accessdenied', ['ip' => $ip]);
        }
    }

    public function random_string($length)
    {
        $array = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
        $text = "";
        $length = rand(22, $length);

        for ($i = 0; $i < $length; $i++) {
            $random = rand(0, 61);
            $text .= $array[$random];
        }
        return $text;
    }
    public function getIPAddress()
    {
        //whether ip is from the share internet
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
        //whether ip is from the proxy
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        //whether ip is from the remote address
        else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    public function add_months($date, $installment_months)
    {

        $timestamp = $date->addMonth($installment_months)->format('d/m/Y');

        return $timestamp;
    }
}
