<?php

namespace App\Http\Controllers;

use App\DataTables\CashTransferDataTable;
use App\Http\Requests\CashTransferRequest;
use App\Models\Cash\CashTransfer;
use App\Models\Cash\Cash_Account;
use App\Models\Cash\Transaction;
use App\Services\WiaScanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;

class CashTransferController extends Controller
{
    private $scanner;

    public function __construct(WiaScanner $scanner)
    {
        $this->scanner = $scanner;
    }

    /**
     * Display the list of available scanner devices.
     *
     * @return \Illuminate\View\View
     */
    public function scancreate(string $url_address)
    {
        try {
            // Retrieve the cash_transfer with necessary relationships
            $cash_transfer = CashTransfer::where('url_address', '=', $url_address)->first();
            // Attempt to get the list of devices
            $devices = $this->scanner->listDevices();
            return view('cash_transfer.scanner', compact(['devices', 'cash_transfer']));
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

            // Retrieve the cash_transfer based on the provided URL address
            $cash_transfer = CashTransfer::where('url_address', '=', $url_address)->first();
            if (!$cash_transfer) {
                return response()->json(['error' => 'cash_transfer not found for the given URL address.'], 404);
            }


            // Save the scanned image and associate it with the contract
            $cash_transfer->images()->create([
                'image_path' => 'storage/scans/' . $filename,
                'user_id_create' => auth()->id(), // Assuming you're tracking the creator
            ]);

            // Return the URL to the scanned image
            return response()->json([
                'message' => 'Scan successful and image associated with cash_transfer.',
                'image_path' => asset('storage/scans/' . basename($scannedImagePath))
            ], 200);
        } catch (Exception $e) {
            // Log and return detailed error message on failure

            return response()->json(['error' => 'Failed to scan the document. Please try again later.'], 500);
        }
    }

    public function archivecreate(string $url_address)
    {
        // Retrieve the cash_transfer with necessary relationships
        $cash_transfer = CashTransfer::where('url_address', '=', $url_address)->first();

        return view('cash_transfer.archivecreate', compact(['cash_transfer']));
    }

    public function archivestore(Request $request, string $url_address)
    {
        // Retrieve the cash_transfer
        $cash_transfer = CashTransfer::where('url_address', '=', $url_address)->first();

        $request->validate([
            'images' => 'required|array',
            'images.*' => 'string', // Expecting an array of base64 strings
        ]);

        foreach ($request->input('images') as $image) {
            // Decode the base64 string
            $image = str_replace('data:image/jpeg;base64,', '', $image);
            $image = str_replace(' ', '+', $image);
            $imageName = 'cash_transfer_image_' . time() . '_' . uniqid() . '.jpeg'; // Unique names for each image
            $imagePath = 'public/cash_transfer_images/' . $imageName;
            // Save the image to storage
            Storage::put($imagePath, base64_decode($image));

            // Store the image path in the database
            $cash_transfer->images()->create([
                'image_path' => str_replace('public/', 'storage/', $imagePath),
                'user_id_create' => auth()->id(), // Assuming you're tracking the creator
            ]);
        }

        return redirect()->route('cash_transfer.show', $cash_transfer->url_address)->with('success', 'تم ارشفة الصور بنجاح');
    }


    public function archiveshow(string $url_address)
    {
        // Retrieve the cash_transfer with necessary relationships
        $cash_transfer = CashTransfer::with(['images'])->where('url_address', '=', $url_address)->first();

        if (isset($cash_transfer)) {


            return view('cash_transfer.archiveshow', compact(['cash_transfer']));
        } else {
            $ip = $this->getIPAddress();
            return view('cash_transfer.accessdenied', ['ip' => $ip]);
        }
    }

    public function index(CashTransferDataTable $dataTable, Request $request)
    {
        $onlyPending = $request->input('onlyPending');
        return $dataTable->onlyPending($onlyPending)->render('cash_transfer.index');
    }

    public function create()
    {
        $accounts = Cash_Account::all();
        return view('cash_transfer.create', compact('accounts'));
    }

    public function store(CashTransferRequest $request)
    {
        try {
            $cash_transfer = CashTransfer::create($request->validated());

            // Optionally, you can add a transaction here if needed

            return Redirect::route('cash_transfer.index')
                ->with('success', 'تمت إضافة التحويل بنجاح.');
        } catch (\Exception $e) {
            return Redirect::back()->with('error', 'فشل إضافة التحويل: ' . $e->getMessage());
        }
    }

    public function show($url_address)
    {
        // Fetch the cash transfer by its URL address
        $cashTransfer = CashTransfer::where('url_address', $url_address)->firstOrFail();

        // Return the view with the cash transfer data
        return view('cash_transfer.show', compact('cashTransfer'));
    }

    public function edit($url_address)
    {
        $transfer = CashTransfer::where('url_address', $url_address)->first();
        if (!$transfer) {
            return Redirect::back()->with('error', 'لم يتم العثور على التحويل.');
        }

        $accounts = Cash_Account::all();
        return view('cash_transfer.edit', compact('transfer', 'accounts'));
    }

    public function update(CashTransferRequest $request, $url_address)
    {
        $transfer = CashTransfer::where('url_address', $url_address)->first();

        if (!$transfer) {
            return Redirect::back()->with('error', 'لم يتم العثور على التحويل.');
        }

        if ($transfer->approved) {
            return Redirect::back()->with('error', 'لا يمكن تعديل تحويل تمت الموافقة عليه.');
        }

        try {
            $transfer->update($request->validated());

            return Redirect::route('cash_transfer.index')
                ->with('success', 'تم تحديث التحويل بنجاح.');
        } catch (\Exception $e) {
            return Redirect::back()->with('error', 'فشل تحديث التحويل: ' . $e->getMessage());
        }
    }

    public function approve($url_address)
    {
        $transfer = CashTransfer::where('url_address', $url_address)->first();

        if (!$transfer) {
            return Redirect::back()->with('error', 'لم يتم العثور على التحويل.');
        }

        if ($transfer->approved) {
            return Redirect::back()->with('error', 'تمت الموافقة على هذا التحويل بالفعل.');
        }

        $fromAccount = Cash_Account::find($transfer->from_account_id);
        $toAccount = Cash_Account::find($transfer->to_account_id);

        if (!$fromAccount || !$toAccount) {
            return Redirect::back()->with('error', 'لم يتم العثور على حسابات نقدية.');
        }

        $amount = $transfer->amount;

        // Debit transaction for fromAccount
        $this->createTransaction($fromAccount, $amount, 'debit', $transfer);

        // Credit transaction for toAccount
        $this->createTransaction($toAccount, $amount, 'credit', $transfer);

        $transfer->approved = true;
        $transfer->user_id_update = Auth::id();
        $transfer->save();

        $fromAccount->recalculateBalance();
        $toAccount->recalculateBalance();

        return Redirect::route('cash_transfer.index')->with('success', 'تمت الموافقة على التحويل بنجاح.');
    }

    private function createTransaction($account, $amount, $type, $transfer)
    {
        $transaction = new Transaction();
        $transaction->url_address = $this->random_string(60);
        $transaction->cash_account_id = $account->id;
        $transaction->transaction_amount = $amount;
        $transaction->transaction_date = $transfer->transfer_date;
        $transaction->transaction_type = $type;
        $transaction->transactionable_id = $transfer->id;
        $transaction->transactionable_type = CashTransfer::class;
        $transaction->save();
    }

    public function destroy($url_address)
    {
        $transfer = CashTransfer::where('url_address', $url_address)->first();

        if (!$transfer) {
            return Redirect::back()->with('error', 'لم يتم العثور على التحويل.');
        }
        $amount = $transfer->amount;
        $fromAccount = Cash_Account::find($transfer->from_account_id);
        $toAccount = Cash_Account::find($transfer->to_account_id);

        if ($transfer->approved) {
            // Delete related transactions
            $transfer->transactions()->delete();
            $fromAccount->recalculateBalance();
            $toAccount->recalculateBalance();
        }

        $transfer->delete();

        return Redirect::route('cash_transfer.index')->with('success', 'تم حذف التحويل بنجاح.');
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
}
