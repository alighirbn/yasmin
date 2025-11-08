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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


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
            $dp_start_date = Carbon::parse($request->down_payment_deferred_start_date ?? $contract_date->copy()->addMonth());

            // Installments table IDs
            $dp_cash = Installment::where(['payment_method_id' => 4, 'installment_name' => 'دفعة مقدمة نقداً'])->first();
            $dp_deferred = Installment::where(['payment_method_id' => 4, 'installment_name' => 'دفعة مقدمة مؤجلة'])->first();
            $mi = Installment::where(['payment_method_id' => 4, 'installment_name' => 'قسط مرن'])->first();
            $kp = Installment::where(['payment_method_id' => 4, 'installment_name' => 'دفعة مفتاح'])->first();


            if (!$dp_cash || !$dp_deferred || !$mi || !$kp) {
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
                    'installment_id' => $dp_cash->id,
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
                        'installment_date' => $dp_start_date->copy()->addMonths(($i - 1) * $freq),
                        'contract_id' => $contract->id,
                        'installment_id' => $dp_deferred->id,
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
    public function edit(Request $request, string $url_address)
    {
        $contract = Contract::where('url_address', $url_address)
            ->with(['payments', 'building', 'contract_installments.installment', 'contract_installments.payment'])
            ->first();

        if (!$contract) {
            $ip = $this->getIPAddress();
            return view('contract.contract.accessdenied', ['ip' => $ip]);
        }

        // Block edits if contract has approved payments and is not temporary
        $approvedPayments = $contract->payments->where('approved', true);

        if ($approvedPayments->count() > 0 && $contract->stage !== 'temporary') {
            return redirect()->route('contract.show', $contract->url_address)
                ->with('error', 'لا يمكن تعديل العقد لأنه يحتوي على دفعات وتم قبوله.');
        }

        // Load base data
        $customers = Customer::all();
        $buildings = $this->getAvailableBuildings($contract);
        $payment_methods = Payment_Method::all();

        // ✅ Calculate ACTUAL paid amount from payments table
        $paidAmount = $contract->contract_installments
            ->sum(function ($ci) {
                return $ci->payments()
                    ->where('approved', true)
                    ->sum('payment_amount');
            });

        // Get payment details based on current method
        // This extracts SCHEDULED amounts from installments
        $variable_payment_details = $this->extractPaymentDetails($contract, $paidAmount);

        return view('contract.contract.edit', compact(
            'contract',
            'customers',
            'buildings',
            'payment_methods',
            'variable_payment_details',
            'paidAmount'
        ));
    }

    // ============================================================================
    // HELPER METHODS
    // ============================================================================

    /**
     * Get buildings available for assignment to this contract
     */
    private function getAvailableBuildings($contract)
    {
        return Building::where(function ($query) use ($contract) {
            $query->doesntHave('contract')
                ->orWhereDoesntHave('contract', function ($subQuery) {
                    $subQuery->whereNotIn('stage', ['terminated']);
                })
                ->orWhere('id', $contract->contract_building_id);
        })->get();
    }

    /**
     * Extract payment details based on contract payment method
     * 
     * IMPORTANT: This extracts SCHEDULED amounts from installments,
     * NOT paid amounts. The $paidAmount parameter is used only for
     * validation and default values.
     */
    private function extractPaymentDetails($contract, float $paidAmount): array
    {
        $contractDate = Carbon::parse($contract->contract_date);

        // Default values - for migration scenarios
        $defaults = [
            'down_payment_amount' => 0,
            'down_payment_installment' => $paidAmount, // Use paid amount as default for migrations
            'down_payment_deferred_installment' => 0,
            'down_payment_deferred_frequency' => 1,
            'down_payment_deferred_start_date' => $contractDate->copy()->addMonth()->format('Y-m-d'),
            'monthly_installment_amount' => 0,
            'number_of_months' => 0,
            'monthly_frequency' => 1,
            'monthly_start_date' => $contractDate->copy()->addMonth()->format('Y-m-d'),
            'key_payment_amount' => 0,
        ];

        // Route to appropriate method
        switch ($contract->contract_payment_method_id) {
            case 3:
                return $this->extractMethod3Details($contract, $contractDate, $defaults, $paidAmount);
            case 4:
                return $this->extractMethod4Details($contract, $contractDate, $defaults, $paidAmount);
            case 2:
                // For Method 2 with payments (migration scenario)
                return $paidAmount > 0 ? $defaults : $defaults;
            default:
                return $defaults;
        }
    }

    /**
     * Extract payment details for Method 3 (Variable Payment Plan)
     * 
     * Returns SCHEDULED amounts that should appear in the form fields
     */
    private function extractMethod3Details($contract, Carbon $contractDate, array $defaults, float $paidAmount): array
    {
        $installments = $contract->contract_installments()
            ->with('installment', 'payment')
            ->orderBy('installment_date')
            ->get();

        // Get installment types
        $types = $this->getInstallmentTypesByMethod(3, [
            'dp' => 'دفعة مقدمة',
            'mi' => 'دفعة شهرية',
            'kp' => 'دفعة المفتاح'
        ]);

        if (empty($types)) {
            return $defaults;
        }

        $downPayments = $installments->where('installment_id', $types['dp']->id);
        $monthlyInstallments = $installments->where('installment_id', $types['mi']->id);
        $keyPayment = $installments->where('installment_id', $types['kp']->id)->first();

        // ✅ SCHEDULED Down payment totals (what the contract says they should pay)
        $downTotal = $downPayments->sum('installment_amount');

        // ✅ The form should show SCHEDULED cash down payment (first installment)
        // NOT what was paid - that's shown separately as $paidAmount
        $downCash = $downPayments->sortBy('installment_date')->first()?->installment_amount ?? 0;

        // Deferred down payment (SCHEDULED amounts)
        $deferredPayments = $downPayments->sortBy('installment_date')->skip(1);
        $deferredAmount = $deferredPayments->first()?->installment_amount ?? 0;
        $deferredStart = $deferredPayments->first()
            ? Carbon::parse($deferredPayments->first()->installment_date)->format('Y-m-d')
            : $contractDate->copy()->addMonth()->format('Y-m-d');
        $deferredFreq = $this->calculateFrequency($deferredPayments);

        // Monthly installments (SCHEDULED amounts)
        $monthlyAmount = $monthlyInstallments->first()?->installment_amount ?? 0;
        $monthlyStart = $monthlyInstallments->first()
            ? Carbon::parse($monthlyInstallments->first()->installment_date)->format('Y-m-d')
            : $contractDate->copy()->addMonth()->format('Y-m-d');
        $monthlyFreq = $this->calculateFrequency($monthlyInstallments);

        // Key payment (SCHEDULED amount)
        $keyAmount = $keyPayment?->installment_amount ?? 0;

        return $this->formatDates([
            'down_payment_amount' => $downTotal,
            'down_payment_installment' => $downCash, // SCHEDULED, not paid
            'down_payment_deferred_installment' => $deferredAmount,
            'down_payment_deferred_frequency' => $deferredFreq,
            'down_payment_deferred_start_date' => $deferredStart,
            'monthly_installment_amount' => $monthlyAmount,
            'number_of_months' => $monthlyInstallments->count(),
            'monthly_frequency' => $monthlyFreq,
            'monthly_start_date' => $monthlyStart,
            'key_payment_amount' => $keyAmount,
        ]);
    }

    /**
     * Extract payment details for Method 4 (Flexible Payment Plan)
     * 
     * Returns SCHEDULED amounts that should appear in the form fields
     */
    private function extractMethod4Details($contract, Carbon $contractDate, array $defaults, float $paidAmount): array
    {
        $installments = $contract->contract_installments()
            ->with('installment', 'payment')
            ->orderBy('installment_date')
            ->get();

        // Get installment types
        $types = $this->getInstallmentTypesByMethod(4, [
            'dp_cash' => 'دفعة مقدمة نقداً',
            'dp_deferred' => 'دفعة مقدمة مؤجلة',
            'mi' => 'قسط مرن',
            'kp' => 'دفعة مفتاح'
        ]);

        if (empty($types)) {
            return $defaults;
        }

        $downCashInstallments = $installments->where('installment_id', $types['dp_cash']->id);
        $downDeferredInstallments = $installments->where('installment_id', $types['dp_deferred']->id);
        $monthlyInstallments = $installments->where('installment_id', $types['mi']->id);
        $keyPayment = $installments->where('installment_id', $types['kp']->id)->first();

        // ✅ SCHEDULED Down payment totals (not paid amounts)
        $downCashTotal = $downCashInstallments->sum('installment_amount');
        $downDeferredTotal = $downDeferredInstallments->sum('installment_amount');
        $downTotal = $downCashTotal + $downDeferredTotal;

        // Deferred installment (SCHEDULED)
        $deferredAmount = $downDeferredInstallments->first()?->installment_amount ?? 0;
        $deferredStart = $downDeferredInstallments->first()
            ? Carbon::parse($downDeferredInstallments->first()->installment_date)->format('Y-m-d')
            : $contractDate->copy()->addMonth()->format('Y-m-d');
        $deferredFreq = $this->calculateFrequency($downDeferredInstallments);

        // Monthly installments (SCHEDULED)
        $monthlyAmount = $monthlyInstallments->first()?->installment_amount ?? 0;
        $monthlyStart = $monthlyInstallments->first()
            ? Carbon::parse($monthlyInstallments->first()->installment_date)->format('Y-m-d')
            : $contractDate->copy()->addMonth()->format('Y-m-d');
        $monthlyFreq = $this->calculateFrequency($monthlyInstallments);

        // Key payment (SCHEDULED)
        $keyAmount = $keyPayment?->installment_amount ?? 0;

        return $this->formatDates([
            'down_payment_amount' => $downTotal,
            'down_payment_installment' => $downCashTotal, // SCHEDULED, not paid
            'down_payment_deferred_installment' => $deferredAmount,
            'down_payment_deferred_frequency' => $deferredFreq,
            'down_payment_deferred_start_date' => $deferredStart,
            'monthly_installment_amount' => $monthlyAmount,
            'number_of_months' => $monthlyInstallments->count(),
            'monthly_frequency' => $monthlyFreq,
            'monthly_start_date' => $monthlyStart,
            'key_payment_amount' => $keyAmount,
        ]);
    }

    /**
     * Helper: Get installment types by payment method ID
     */
    private function getInstallmentTypesByMethod(int $methodId, array $types): array
    {
        $result = [];

        foreach ($types as $key => $name) {
            $installment = Installment::where([
                'payment_method_id' => $methodId,
                'installment_name' => $name
            ])->first();

            if (!$installment) {
                return []; // Return empty if any required type is missing
            }

            $result[$key] = $installment;
        }

        return $result;
    }

    /**
     * Helper: Calculate frequency (in months) between installments
     */
    private function calculateFrequency($installments): int
    {
        if ($installments->count() < 2) {
            return 1;
        }

        $first = $installments->first();
        $second = $installments->skip(1)->first();

        if (!$first || !$second) {
            return 1;
        }

        $date1 = Carbon::parse($first->installment_date);
        $date2 = Carbon::parse($second->installment_date);

        return max(1, $date1->diffInMonths($date2));
    }

    /**
     * Helper: Format all date fields to Y-m-d only
     */
    private function formatDates(array $details): array
    {
        foreach ($details as $key => $value) {
            if (is_string($value) && Str::contains($key, 'date') && !empty($value)) {
                $details[$key] = Carbon::parse($value)->format('Y-m-d');
            }
        }
        return $details;
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(ContractRequest $request, string $url_address)
    {
        $contract = Contract::where('url_address', $url_address)
            ->with(['payments', 'contract_installments.installment', 'contract_installments.payment'])
            ->first();

        if (!$contract) {
            $ip = $this->getIPAddress();
            return view('contract.contract.accessdenied', ['ip' => $ip]);
        }

        $oldMethod = $contract->contract_payment_method_id;
        $newMethod = (int) $request->input('contract_payment_method_id');

        // Validate edit permissions
        if (!$this->canEditContract($contract, $oldMethod, $newMethod)) {
            return redirect()->route('contract.show', $contract->url_address)
                ->with('error', 'لا يمكن تعديل العقد لأنه يحتوي على دفعات وتم قبوله.');
        }

        // Route to appropriate handler
        try {
            if ($oldMethod == 2 && $newMethod == 3) {
                return $this->migrateMethod2To3($contract, $request);
            }

            if ($oldMethod == 2 && $newMethod == 4) {
                return $this->migrateMethod2To4($contract, $request);
            }

            if ($newMethod == 3) {
                return $this->updateMethod3($contract, $request);
            }

            if ($newMethod == 4) {
                return $this->updateMethod4($contract, $request);
            }

            if ($newMethod == 2) {
                return $this->updateMethod2($contract, $request);
            }

            if ($newMethod == 1) {
                return $this->updateMethod1($contract, $request);
            }

            return redirect()->route('contract.show', $contract->url_address)
                ->with('error', 'طريقة الدفع غير مدعومة.');
        } catch (\Throwable $e) {
            Log::error('Contract update failed', [
                'contract_id' => $contract->id,
                'old_method' => $oldMethod,
                'new_method' => $newMethod,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', $e->getMessage());
        }
    }

    // ============================================================================
    // HELPER METHODS
    // ============================================================================

    private function canEditContract($contract, int $oldMethod, int $newMethod): bool
    {
        // Allow edits if no approved payments or still temporary
        $approvedPayments = $contract->payments->where('approved', true);

        if ($approvedPayments->count() === 0 || $contract->stage === 'temporary') {
            return true;
        }

        // Allow specific migrations and same-method updates
        $allowedTransitions = [
            [2, 3],
            [2, 4],  // Migration from method 2
            [3, 3],
            [4, 4],  // Same method updates
        ];

        foreach ($allowedTransitions as [$old, $new]) {
            if ($oldMethod == $old && $newMethod == $new) {
                return true;
            }
        }

        return false;
    }

    private function cleanAmount($value): float
    {
        return (float) str_replace(',', '', $value ?? 0);
    }

    private function getInstallmentTypes(int $methodId, array $types): array
    {
        $result = [];

        foreach ($types as $key => $name) {
            $installment = Installment::where([
                'payment_method_id' => $methodId,
                'installment_name' => $name
            ])->first();

            if (!$installment) {
                throw new \Exception("نوع القسط '{$name}' غير موجود في النظام.");
            }

            $result[$key] = $installment;
        }

        return $result;
    }

    /**
     * Delete only completely unpaid installments (preserves partial payments)
     */
    private function deleteUnpaidInstallments($contract): void
    {
        $contract->contract_installments()
            ->where('paid_amount', '=', 0)
            ->whereDoesntHave('payment', fn($q) => $q->where('approved', true))
            ->delete();
    }

    private function getNextSequence($contract): int
    {
        return $contract->contract_installments()->max('sequence_number') ?? 0;
    }

    /**
     * Validate that new amount is not less than already paid amount
     */
    private function validateAmountNotBelowPaid(float $newAmount, float $paidAmount, string $fieldName): void
    {
        if (round($newAmount, 2) < round($paidAmount, 2)) {
            throw new \Exception(
                "لا يمكن تقليل {$fieldName} إلى " . number_format($newAmount, 2) .
                    " لأن المبلغ المدفوع سابقاً " . number_format($paidAmount, 2)
            );
        }
    }

    /**
     * Validate total with paid amounts
     */
    private function validateTotal($contract): bool
    {
        $totalScheduled = (float) Contract_Installment::where('contract_id', $contract->id)
            ->sum('installment_amount');

        $totalPaid = (float) Contract_Installment::where('contract_id', $contract->id)
            ->sum('paid_amount');

        $contractAmount = $this->cleanAmount($contract->contract_amount);

        // Total scheduled must equal contract amount
        // Total paid must not exceed contract amount
        return abs($totalScheduled - $contractAmount) <= 0.01
            && $totalPaid <= $contractAmount + 0.01;
    }

    private function throwValidationError($contract): void
    {
        $totalScheduled = (float) Contract_Installment::where('contract_id', $contract->id)
            ->sum('installment_amount');

        $totalPaid = (float) Contract_Installment::where('contract_id', $contract->id)
            ->sum('paid_amount');

        $contractAmount = $this->cleanAmount($contract->contract_amount);

        throw new \Exception(
            "خطأ في مجاميع الأقساط:\n" .
                "مبلغ العقد: " . number_format($contractAmount, 2) . "\n" .
                "مجموع الأقساط المجدولة: " . number_format($totalScheduled, 2) . "\n" .
                "مجموع المدفوع: " . number_format($totalPaid, 2)
        );
    }

    // ============================================================================
    // METHOD 2 → 3 MIGRATION
    // ============================================================================

    private function migrateMethod2To3($contract, $request)
    {
        DB::beginTransaction();

        try {
            $contract->update($request->validated());
            $contractDate = Carbon::parse($request->contract_date);

            // Get installment types
            $types = $this->getInstallmentTypes(3, [
                'dp' => 'دفعة مقدمة',
                'mi' => 'دفعة شهرية',
                'kp' => 'دفعة المفتاح'
            ]);

            // Get all installments (including partially paid)
            $allInstallments = $contract->contract_installments()
                ->with('payment', 'installment')
                ->get();

            // Calculate ACTUAL paid amounts (not installment amounts)
            $paidDownAmount = $allInstallments->sum('paid_amount');

            // Update all existing installments to down payment type
            foreach ($allInstallments as $inst) {
                $inst->update(['installment_id' => $types['dp']->id]);
            }

            // Delete only completely unpaid installments
            $this->deleteUnpaidInstallments($contract);

            // Parse request inputs
            $downPaymentTotal = $this->cleanAmount($request->down_payment_amount);
            $downPaymentCash = $this->cleanAmount($request->down_payment_installment);
            $monthlyAmount = $this->cleanAmount($request->monthly_installment_amount);
            $numberOfMonths = (int) $request->input('number_of_months', 36);
            $deferredType = $request->input('deferred_type', 'none');
            $deferredMonths = (int) $request->input('deferred_months', 0);
            $keyPayment = $this->cleanAmount($request->key_payment_amount);

            // Validate: can't reduce down payment below paid
            $this->validateAmountNotBelowPaid($downPaymentCash, $paidDownAmount, 'الدفعة المقدمة النقدية');

            // Calculate deferred amount (only what wasn't paid yet)
            $deferredRaw = max(0, $downPaymentTotal - $downPaymentCash);
            $deferredAmount = max(0, $deferredRaw - max(0, $paidDownAmount - $downPaymentCash));

            // Calculate deferred distribution
            $deferredPerMonth = 0;
            $deferredRemainder = 0;

            if ($deferredType === 'spread' && $deferredMonths > 0 && $deferredAmount > 0) {
                $deferredPerMonth = floor($deferredAmount / $deferredMonths);
                $deferredRemainder = $deferredAmount % $deferredMonths;
            }

            // Determine monthly start date
            $monthlyStart = $contractDate->copy()->addMonth();

            $sequence = $this->getNextSequence($contract);

            // Create cash down payment (only remaining amount)
            if ($downPaymentCash > $paidDownAmount) {
                $remainingCash = $downPaymentCash - $paidDownAmount;

                Contract_Installment::create([
                    'url_address' => $this->random_string(60),
                    'installment_amount' => $remainingCash,
                    'paid_amount' => 0,
                    'installment_date' => $contractDate,
                    'contract_id' => $contract->id,
                    'installment_id' => $types['dp']->id,
                    'user_id_update' => $request->user_id_update,
                    'sequence_number' => ++$sequence,
                ]);
            }

            // Create monthly installments with deferred spread
            for ($i = 1; $i <= $numberOfMonths; $i++) {
                $extra = 0;

                if ($deferredAmount > 0) {
                    if ($deferredType === 'spread' && $i <= $deferredMonths) {
                        $extra = $deferredPerMonth;
                        if ($i === $deferredMonths) {
                            $extra += $deferredRemainder;
                        }
                    } elseif ($deferredType === 'lump-6' && $i === 6) {
                        $extra = $deferredAmount;
                    } elseif ($deferredType === 'lump-7' && $i === 7) {
                        $extra = $deferredAmount;
                    }
                }

                Contract_Installment::create([
                    'url_address' => $this->random_string(60),
                    'installment_amount' => $monthlyAmount + $extra,
                    'paid_amount' => 0,
                    'installment_date' => $monthlyStart->copy()->addMonths($i - 1),
                    'contract_id' => $contract->id,
                    'installment_id' => $types['mi']->id,
                    'user_id_update' => $request->user_id_update,
                    'sequence_number' => ++$sequence,
                ]);
            }

            // Create key payment
            if ($keyPayment > 0) {
                Contract_Installment::create([
                    'url_address' => $this->random_string(60),
                    'installment_amount' => $keyPayment,
                    'paid_amount' => 0,
                    'installment_date' => $monthlyStart->copy()->addMonths($numberOfMonths),
                    'contract_id' => $contract->id,
                    'installment_id' => $types['kp']->id,
                    'user_id_update' => $request->user_id_update,
                    'sequence_number' => ++$sequence,
                ]);
            }

            // Validate totals
            if (!$this->validateTotal($contract)) {
                $this->throwValidationError($contract);
            }

            DB::commit();

            return redirect()->route('contract.show', $contract->url_address)
                ->with('success', 'تم تحويل العقد من الطريقة 2 إلى 3 بنجاح.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    // ============================================================================
    // METHOD 2 → 4 MIGRATION
    // ============================================================================

    private function migrateMethod2To4($contract, $request)
    {
        DB::beginTransaction();

        try {
            $contract->update($request->validated());
            $contractDate = Carbon::parse($request->contract_date);

            // Get installment types
            $types = $this->getInstallmentTypes(4, [
                'dp_cash' => 'دفعة مقدمة نقداً',
                'dp_deferred' => 'دفعة مقدمة مؤجلة',
                'mi' => 'قسط مرن',
                'kp' => 'دفعة مفتاح'
            ]);

            // Get all installments
            $allInstallments = $contract->contract_installments()
                ->with('payment', 'installment')
                ->get();

            $paidAmount = $allInstallments->sum('paid_amount');

            // Update all to cash down payment type
            foreach ($allInstallments as $inst) {
                $inst->update(['installment_id' => $types['dp_cash']->id]);
            }

            // Delete unpaid installments
            $this->deleteUnpaidInstallments($contract);

            // Parse inputs
            $downTotal = $this->cleanAmount($request->down_payment_amount);
            $downCash = $this->cleanAmount($request->down_payment_installment);
            $monthly = $this->cleanAmount($request->monthly_installment_amount);
            $months = (int) $request->number_of_months;
            $key = $this->cleanAmount($request->key_payment_amount);

            // Validate
            $this->validateAmountNotBelowPaid($downCash, $paidAmount, 'الدفعة المقدمة النقدية');

            $deferredTotal = max(0, $downTotal - $downCash);
            $deferredPiece = $this->cleanAmount($request->down_payment_deferred_installment ?? 0);
            if ($deferredTotal > 0 && $deferredPiece <= 0) {
                $deferredPiece = $deferredTotal;
            }

            $deferredFreq = max(1, (int)($request->down_payment_deferred_frequency ?? 1));
            $monthlyFreq = max(1, (int)($request->monthly_frequency ?? 1));

            // Determine start dates
            $monthlyStart = $request->monthly_start_date
                ? Carbon::parse($request->monthly_start_date)
                : $contractDate->copy()->addMonth();

            $deferredStart = $request->down_payment_deferred_start_date
                ? Carbon::parse($request->down_payment_deferred_start_date)
                : $contractDate->copy()->addMonth();

            $sequence = $this->getNextSequence($contract);

            // Create cash down payment (remaining)
            if ($downCash > $paidAmount) {
                Contract_Installment::create([
                    'url_address' => $this->random_string(60),
                    'installment_amount' => $downCash - $paidAmount,
                    'paid_amount' => 0,
                    'installment_date' => $contractDate,
                    'contract_id' => $contract->id,
                    'installment_id' => $types['dp_cash']->id,
                    'user_id_update' => $request->user_id_update,
                    'sequence_number' => ++$sequence,
                ]);
            }

            // Create deferred down payments
            if ($deferredTotal > 0) {
                $count = (int) ceil($deferredTotal / $deferredPiece);
                $remaining = $deferredTotal;

                for ($i = 1; $i <= $count; $i++) {
                    $amount = min($deferredPiece, $remaining);
                    $remaining -= $amount;

                    Contract_Installment::create([
                        'url_address' => $this->random_string(60),
                        'installment_amount' => $amount,
                        'paid_amount' => 0,
                        'installment_date' => $deferredStart->copy()->addMonths(($i - 1) * $deferredFreq),
                        'contract_id' => $contract->id,
                        'installment_id' => $types['dp_deferred']->id,
                        'user_id_update' => $request->user_id_update,
                        'sequence_number' => ++$sequence,
                    ]);
                }
            }

            // Create monthly installments
            for ($i = 0; $i < $months; $i++) {
                Contract_Installment::create([
                    'url_address' => $this->random_string(60),
                    'installment_amount' => $monthly,
                    'paid_amount' => 0,
                    'installment_date' => $monthlyStart->copy()->addMonths($i * $monthlyFreq),
                    'contract_id' => $contract->id,
                    'installment_id' => $types['mi']->id,
                    'user_id_update' => $request->user_id_update,
                    'sequence_number' => ++$sequence,
                ]);
            }

            // Create key payment
            if ($key > 0) {
                $keyDate = $monthlyStart->copy()->addMonths(max(0, $months - 1) * $monthlyFreq)->addMonth();

                Contract_Installment::create([
                    'url_address' => $this->random_string(60),
                    'installment_amount' => $key,
                    'paid_amount' => 0,
                    'installment_date' => $keyDate,
                    'contract_id' => $contract->id,
                    'installment_id' => $types['kp']->id,
                    'user_id_update' => $request->user_id_update,
                    'sequence_number' => ++$sequence,
                ]);
            }

            // Validate totals
            if (!$this->validateTotal($contract)) {
                $this->throwValidationError($contract);
            }

            DB::commit();

            return redirect()->route('contract.show', $contract->url_address)
                ->with('success', 'تم تحويل العقد من الطريقة 2 إلى 4 بنجاح.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    // ============================================================================
    // METHOD 3 UPDATE
    // ============================================================================

    private function updateMethod3($contract, $request)
    {
        DB::beginTransaction();

        try {
            $contract->update($request->validated());
            $contractDate = Carbon::parse($request->contract_date);

            // Get installment types
            $types = $this->getInstallmentTypes(3, [
                'dp' => 'دفعة مقدمة',
                'mi' => 'دفعة شهرية',
                'kp' => 'دفعة المفتاح'
            ]);

            // Get all installments (including partially paid)
            $allInstallments = $contract->contract_installments()
                ->with('payment', 'installment')
                ->get();

            // Calculate ACTUAL paid amounts by type (using paid_amount field)
            $paidDown = $allInstallments
                ->where('installment_id', $types['dp']->id)
                ->sum('paid_amount');

            $paidMonthly = $allInstallments
                ->where('installment_id', $types['mi']->id)
                ->sum('paid_amount');

            $paidKey = $allInstallments
                ->where('installment_id', $types['kp']->id)
                ->sum('paid_amount');

            // Count FULLY paid monthly installments
            $fullyPaidMonthlyCount = $allInstallments
                ->where('installment_id', $types['mi']->id)
                ->filter(function ($inst) {
                    return $inst->payment && $inst->payment->approved
                        && $inst->paid_amount >= $inst->installment_amount;
                })
                ->count();

            // Parse inputs
            $downPayment = $this->cleanAmount($request->down_payment_installment);
            $monthly = $this->cleanAmount($request->monthly_installment_amount);
            $totalMonths = max(1, (int) $request->number_of_months);
            $key = $this->cleanAmount($request->key_payment_amount);

            // Validate: Can't reduce amounts below what's paid
            $this->validateAmountNotBelowPaid($downPayment, $paidDown, 'الدفعة المقدمة');
            $this->validateAmountNotBelowPaid($key, $paidKey, 'دفعة المفتاح');

            // Validate: Can't reduce months below fully paid count
            if ($totalMonths < $fullyPaidMonthlyCount) {
                throw new \Exception(
                    "لا يمكن تقليل عدد الأشهر إلى {$totalMonths} لأن عدد الأشهر المدفوعة بالكامل {$fullyPaidMonthlyCount}"
                );
            }

            // Delete only COMPLETELY unpaid installments
            $this->deleteUnpaidInstallments($contract);

            // Refresh to get updated installments
            $contract->load('contract_installments');

            $sequence = $this->getNextSequence($contract);

            // Handle down payment
            $existingDownPayments = $contract->contract_installments()
                ->where('installment_id', $types['dp']->id)
                ->get();

            $totalExistingDown = $existingDownPayments->sum('installment_amount');

            if ($downPayment > $totalExistingDown) {
                // Create additional down payment installment
                Contract_Installment::create([
                    'url_address' => $this->random_string(60),
                    'installment_amount' => $downPayment - $totalExistingDown,
                    'paid_amount' => 0,
                    'installment_date' => $contractDate,
                    'contract_id' => $contract->id,
                    'installment_id' => $types['dp']->id,
                    'user_id_update' => $request->user_id_update,
                    'sequence_number' => ++$sequence,
                ]);
            } elseif ($downPayment < $totalExistingDown) {
                // Reduce down payment - update the last unpaid/partially paid one
                $adjustableDown = $existingDownPayments
                    ->sortByDesc('installment_date')
                    ->first(fn($inst) => $inst->paid_amount < $inst->installment_amount);

                if ($adjustableDown) {
                    $newAmount = $adjustableDown->installment_amount - ($totalExistingDown - $downPayment);
                    if ($newAmount >= $adjustableDown->paid_amount) {
                        $adjustableDown->update(['installment_amount' => $newAmount]);
                    }
                }
            }

            // Calculate remaining monthly installments
            $remainingMonths = max(0, $totalMonths - $fullyPaidMonthlyCount);

            // Find partially paid monthly installment
            $partiallyPaidMonthly = $allInstallments
                ->where('installment_id', $types['mi']->id)
                ->first(function ($inst) {
                    return $inst->paid_amount > 0
                        && $inst->paid_amount < $inst->installment_amount;
                });

            // Determine start date
            $lastFullyPaidMonthly = $allInstallments
                ->where('installment_id', $types['mi']->id)
                ->filter(fn($inst) => $inst->paid_amount >= $inst->installment_amount)
                ->sortBy('installment_date')
                ->last();

            if ($partiallyPaidMonthly) {
                // Update partially paid installment
                $partiallyPaidMonthly->update([
                    'installment_amount' => $monthly,
                ]);

                $monthlyStart = Carbon::parse($partiallyPaidMonthly->installment_date)->addMonth();
                $remainingMonths = max(0, $remainingMonths - 1);
            } elseif ($lastFullyPaidMonthly) {
                $monthlyStart = Carbon::parse($lastFullyPaidMonthly->installment_date)->addMonth();
            } else {
                $monthlyStart = $contractDate->copy()->addMonth();
            }

            // Create remaining monthly installments
            for ($i = 0; $i < $remainingMonths; $i++) {
                Contract_Installment::create([
                    'url_address' => $this->random_string(60),
                    'installment_amount' => $monthly,
                    'paid_amount' => 0,
                    'installment_date' => $monthlyStart->copy()->addMonths($i),
                    'contract_id' => $contract->id,
                    'installment_id' => $types['mi']->id,
                    'user_id_update' => $request->user_id_update,
                    'sequence_number' => ++$sequence,
                ]);
            }

            // Handle key payment
            $existingKeyPayments = $contract->contract_installments()
                ->where('installment_id', $types['kp']->id)
                ->get();

            $totalExistingKey = $existingKeyPayments->sum('installment_amount');

            if ($key > $totalExistingKey) {
                $keyDate = $monthlyStart->copy()->addMonths($remainingMonths);

                Contract_Installment::create([
                    'url_address' => $this->random_string(60),
                    'installment_amount' => $key - $totalExistingKey,
                    'paid_amount' => 0,
                    'installment_date' => $keyDate,
                    'contract_id' => $contract->id,
                    'installment_id' => $types['kp']->id,
                    'user_id_update' => $request->user_id_update,
                    'sequence_number' => ++$sequence,
                ]);
            } elseif ($key < $totalExistingKey) {
                // Reduce key payment
                $adjustableKey = $existingKeyPayments
                    ->first(fn($inst) => $inst->paid_amount < $inst->installment_amount);

                if ($adjustableKey) {
                    $newAmount = $adjustableKey->installment_amount - ($totalExistingKey - $key);
                    if ($newAmount >= $adjustableKey->paid_amount) {
                        $adjustableKey->update(['installment_amount' => $newAmount]);
                    }
                }
            }

            // Validate totals
            if (!$this->validateTotal($contract)) {
                $this->throwValidationError($contract);
            }

            DB::commit();

            return redirect()->route('contract.show', $contract->url_address)
                ->with('success', 'تم تعديل العقد بنجاح.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    // ============================================================================
    // METHOD 4 UPDATE
    // ============================================================================

    private function updateMethod4($contract, $request)
    {
        DB::beginTransaction();

        try {
            $contract->update($request->validated());
            $contractDate = Carbon::parse($request->contract_date);

            // Get installment types
            $types = $this->getInstallmentTypes(4, [
                'dp_cash' => 'دفعة مقدمة نقداً',
                'dp_deferred' => 'دفعة مقدمة مؤجلة',
                'mi' => 'قسط مرن',
                'kp' => 'دفعة مفتاح'
            ]);

            // Get all installments
            $allInstallments = $contract->contract_installments()
                ->with('payment', 'installment')
                ->get();

            // Calculate ACTUAL paid amounts
            $paidCash = $allInstallments
                ->where('installment_id', $types['dp_cash']->id)
                ->sum('paid_amount');

            $paidDeferred = $allInstallments
                ->where('installment_id', $types['dp_deferred']->id)
                ->sum('paid_amount');

            $paidMonthly = $allInstallments
                ->where('installment_id', $types['mi']->id)
                ->sum('paid_amount');

            $paidKey = $allInstallments
                ->where('installment_id', $types['kp']->id)
                ->sum('paid_amount');

            // Parse inputs
            $downTotal = $this->cleanAmount($request->down_payment_amount);
            $downCash = $this->cleanAmount($request->down_payment_installment);
            $monthly = $this->cleanAmount($request->monthly_installment_amount);
            $months = max(0, (int) $request->number_of_months);
            $key = $this->cleanAmount($request->key_payment_amount);

            $deferredTotal = max(0, $downTotal - $downCash);

            // Validate: Can't reduce below paid
            $this->validateAmountNotBelowPaid($downCash, $paidCash, 'الدفعة المقدمة النقدية');
            $this->validateAmountNotBelowPaid($deferredTotal, $paidDeferred, 'الدفعة المقدمة المؤجلة');
            $this->validateAmountNotBelowPaid($key, $paidKey, 'دفعة المفتاح');

            // Delete unpaid installments
            $this->deleteUnpaidInstallments($contract);

            // Refresh
            $contract->load('contract_installments');

            $deferredPiece = $this->cleanAmount($request->down_payment_deferred_installment ?? 0);
            if ($deferredTotal > 0 && $deferredPiece <= 0) {
                $deferredPiece = $deferredTotal;
            }

            $deferredFreq = max(1, (int)($request->down_payment_deferred_frequency ?? 1));
            $monthlyFreq = max(1, (int)($request->monthly_frequency ?? 1));

            // Determine start dates
            $lastPaidMonthly = $allInstallments
                ->where('installment_id', $types['mi']->id)
                ->filter(fn($inst) => $inst->paid_amount >= $inst->installment_amount)
                ->sortBy('installment_date')
                ->last();

            if ($lastPaidMonthly) {
                $monthlyStart = Carbon::parse($lastPaidMonthly->installment_date)->addMonths($monthlyFreq);
            } elseif ($request->monthly_start_date) {
                $monthlyStart = Carbon::parse($request->monthly_start_date);
            } else {
                $monthlyStart = $contractDate->copy()->addMonth();
            }

            $deferredStart = $request->down_payment_deferred_start_date
                ? Carbon::parse($request->down_payment_deferred_start_date)
                : $monthlyStart->copy();

            $sequence = $this->getNextSequence($contract);

            // Handle cash down payment
            $existingCash = $contract->contract_installments()
                ->where('installment_id', $types['dp_cash']->id)
                ->get();

            $totalExistingCash = $existingCash->sum('installment_amount');

            if ($downCash > $totalExistingCash) {
                Contract_Installment::create([
                    'url_address' => $this->random_string(60),
                    'installment_amount' => round($downCash - $totalExistingCash, 2),
                    'paid_amount' => 0,
                    'installment_date' => $contractDate,
                    'contract_id' => $contract->id,
                    'installment_id' => $types['dp_cash']->id,
                    'user_id_update' => $request->user_id_update,
                    'sequence_number' => ++$sequence,
                ]);
            }

            // Handle deferred down payments
            $existingDeferred = $contract->contract_installments()
                ->where('installment_id', $types['dp_deferred']->id)
                ->get();

            $totalExistingDeferred = $existingDeferred->sum('installment_amount');

            if ($deferredTotal > $totalExistingDeferred) {
                $remainingDeferred = $deferredTotal - $totalExistingDeferred;
                $count = (int) ceil($remainingDeferred / $deferredPiece);
                $remaining = $remainingDeferred;

                $startIndex = $existingDeferred->count();

                for ($i = 1; $i <= $count; $i++) {
                    $amount = min($deferredPiece, $remaining);
                    $remaining -= $amount;

                    Contract_Installment::create([
                        'url_address' => $this->random_string(60),
                        'installment_amount' => $amount,
                        'paid_amount' => 0,
                        'installment_date' => $deferredStart->copy()->addMonths(($startIndex + $i - 1) * $deferredFreq),
                        'contract_id' => $contract->id,
                        'installment_id' => $types['dp_deferred']->id,
                        'user_id_update' => $request->user_id_update,
                        'sequence_number' => ++$sequence,
                    ]);
                }
            }

            // Create monthly installments
            for ($i = 0; $i < $months; $i++) {
                Contract_Installment::create([
                    'url_address' => $this->random_string(60),
                    'installment_amount' => $monthly,
                    'paid_amount' => 0,
                    'installment_date' => $monthlyStart->copy()->addMonths($i * $monthlyFreq),
                    'contract_id' => $contract->id,
                    'installment_id' => $types['mi']->id,
                    'user_id_update' => $request->user_id_update,
                    'sequence_number' => ++$sequence,
                ]);
            }

            // Handle key payment
            $existingKey = $contract->contract_installments()
                ->where('installment_id', $types['kp']->id)
                ->get();

            $totalExistingKey = $existingKey->sum('installment_amount');

            if ($key > $totalExistingKey) {
                $keyDate = $monthlyStart->copy()
                    ->addMonths(max(0, $months - 1) * $monthlyFreq)
                    ->addMonth();

                Contract_Installment::create([
                    'url_address' => $this->random_string(60),
                    'installment_amount' => $key - $totalExistingKey,
                    'paid_amount' => 0,
                    'installment_date' => $keyDate,
                    'contract_id' => $contract->id,
                    'installment_id' => $types['kp']->id,
                    'user_id_update' => $request->user_id_update,
                    'sequence_number' => ++$sequence,
                ]);
            }

            // Validate totals
            if (!$this->validateTotal($contract)) {
                $this->throwValidationError($contract);
            }

            DB::commit();

            return redirect()->route('contract.show', $contract->url_address)
                ->with('success', 'تم تعديل العقد بنجاح.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    // ============================================================================
    // METHOD 2 UPDATE
    // ============================================================================

    private function updateMethod2($contract, $request)
    {
        DB::beginTransaction();

        try {
            $contract->update($request->validated());
            $contractDate = Carbon::parse($request->contract_date);
            $contractAmount = $this->cleanAmount($request->contract_amount);

            if ($contract->contract_installments->count() === 12) {
                // Update existing installments
                foreach ($contract->contract_installments as $inst) {
                    $inst->update([
                        'installment_amount' => $inst->installment->installment_percent * $contractAmount,
                        'installment_date' => $contractDate->copy()->addMonths($inst->installment->installment_period),
                        'sequence_number' => $inst->installment->installment_number,
                    ]);
                }
            } else {
                // Recreate installments
                $contract->contract_installments()->delete();

                $installments = Installment::where('payment_method_id', 2)->get();

                if ($installments->isEmpty()) {
                    throw new \Exception('اعدادات الاقساط غير موجودة في النظام.');
                }

                foreach ($installments as $installment) {
                    Contract_Installment::create([
                        'url_address' => $this->random_string(60),
                        'installment_amount' => $installment->installment_percent * $contractAmount,
                        'paid_amount' => 0,
                        'installment_date' => $contractDate->copy()->addMonths($installment->installment_period),
                        'contract_id' => $contract->id,
                        'installment_id' => $installment->id,
                        'user_id_update' => $request->user_id_update,
                        'sequence_number' => $installment->installment_number,
                    ]);
                }
            }

            if (!$this->validateTotal($contract)) {
                $this->throwValidationError($contract);
            }

            DB::commit();

            return redirect()->route('contract.show', $contract->url_address)
                ->with('success', 'تم تعديل العقد بنجاح.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    // ============================================================================
    // METHOD 1 UPDATE
    // ============================================================================

    private function updateMethod1($contract, $request)
    {
        DB::beginTransaction();

        try {
            $contract->update($request->validated());
            $contractDate = Carbon::parse($request->contract_date);
            $contractAmount = $this->cleanAmount($request->contract_amount);

            if ($contract->contract_installments->count() === 1) {
                // Update existing installment
                $contract->contract_installments->first()->update([
                    'installment_amount' => $contractAmount,
                    'installment_date' => $contractDate,
                    'sequence_number' => 1,
                ]);
            } else {
                // Recreate installment
                $contract->contract_installments()->delete();

                $installments = Installment::where('payment_method_id', 1)->get();

                if ($installments->isEmpty()) {
                    throw new \Exception('اعدادات الاقساط غير موجودة في النظام.');
                }

                foreach ($installments as $installment) {
                    Contract_Installment::create([
                        'url_address' => $this->random_string(60),
                        'installment_amount' => $contractAmount,
                        'paid_amount' => 0,
                        'installment_date' => $contractDate,
                        'contract_id' => $contract->id,
                        'installment_id' => $installment->id,
                        'user_id_update' => $request->user_id_update,
                        'sequence_number' => $installment->installment_number,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('contract.show', $contract->url_address)
                ->with('success', 'تم تعديل العقد بنجاح.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
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
