<?php

namespace App\Http\Controllers;

use App\DataTables\ContractTransferHistoryDataTable;
use App\Models\Contract\Contract;
use App\Models\Customer\Customer;
use App\Http\Controllers\Controller;
use App\Http\Requests\ContractTransferHistoryRequest;
use App\Http\Requests\CustomerRequest;
use App\Models\Contract\Contract_Transfer_History;
use App\Models\User;
use App\Notifications\TransferNotify;
use Illuminate\Support\Facades\Storage;

class ContractTransferHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ContractTransferHistoryDataTable $dataTable)
    {
        return $dataTable->render('contract.transfer.index');
    }


    public function create($contract_id = null)
    {
        $contracts = Contract::all();
        $customers = Customer::all();

        return view('contract.transfer.create', compact(['customers', 'contracts', 'contract_id']));
    }

    public function store(ContractTransferHistoryRequest $request)
    {
        $validated = $request->validated();

        // Process old_customer_picture if present
        if ($request->has('old_customer_picture')) {
            $imageData = $request->input('old_customer_picture');
            $imageData = str_replace('data:image/jpeg;base64,', '', $imageData);
            $imageData = str_replace(' ', '+', $imageData);
            $imageName = 'old_customer_picture_' . time() . '.jpeg';
            $imagePath = 'public/transfer/' . $imageName;

            // Save the image to storage
            Storage::put($imagePath, base64_decode($imageData));

            // Add the image path to validated data
            $validated['old_customer_picture'] = str_replace('public/', 'storage/', $imagePath);
        }

        // Process new_customer_picture if present
        if ($request->has('new_customer_picture')) {
            $imageData = $request->input('new_customer_picture');
            $imageData = str_replace('data:image/jpeg;base64,', '', $imageData);
            $imageData = str_replace(' ', '+', $imageData);
            $imageName = 'new_customer_picture_' . time() . '.jpeg';
            $imagePath = 'public/transfer/' . $imageName;

            // Save the image to storage
            Storage::put($imagePath, base64_decode($imageData));

            // Add the image path to validated data
            $validated['new_customer_picture'] = str_replace('public/', 'storage/', $imagePath);
        }

        // Create a new transfer history record
        $transfer = Contract_Transfer_History::create($validated);

        // Notify all users with 'accountant' role
        $accountants = User::role('accountant')->get(); // Assuming you're using a role system
        foreach ($accountants as $accountant) {
            $accountant->notify(new TransferNotify($transfer));
        }

        return redirect()->route('transfer.index')
            ->with('success', 'تم إرسال طلب التناقل للموافقة.');
    }

    public function approve(string $url_address)
    {
        $transferHistory = Contract_Transfer_History::where('url_address', $url_address)->first();

        $transferHistory->approve();
        $transferHistory->contract->update([
            'contract_customer_id' => $transferHistory->new_customer_id,
        ]);

        return redirect()->route('transfer.index')
            ->with('success', 'تمت الموافقة على نقل التناقل بنجاح.');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function customercreate()
    {
        return view('contract.transfer.customercreate');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function customerstore(CustomerRequest $request)
    {
        $customer = Customer::create($request->validated());

        //inform the user
        return redirect()->route('transfer.create')
            ->with('success', 'تمت أضافة الزبون بنجاح ');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $url_address)
    {
        $transfer = Contract_Transfer_History::with(['contract', 'newCustomer', 'oldCustomer'])->where('url_address', '=', $url_address)->first();

        if (isset($transfer)) {
            return view('contract.transfer.show', compact(['transfer']));
        } else {
            $ip = $this->getIPAddress();
            return view('contract.transfer.accessdenied', ['ip' => $ip]);
        }
    }

    public function print(string $url_address)
    {
        $transfer = Contract_Transfer_History::with(['contract', 'newCustomer', 'oldCustomer'])->where('url_address', '=', $url_address)->first();

        if (isset($transfer)) {
            return view('contract.transfer.print', compact(['transfer']));
        } else {
            $ip = $this->getIPAddress();
            return view('contract.transfer.accessdenied', ['ip' => $ip]);
        }
    }




    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $url_address)
    {
        $transfer = Contract_Transfer_History::where('url_address', '=', $url_address)->first();

        if (!$transfer) {
            $ip = $this->getIPAddress();
            return view('contract.transfer.accessdenied', ['ip' => $ip]);
        }

        if ($transfer->approved) {
            return redirect()->route('transfer.index')
                ->with('error', 'تمت الموافقة على التناقل ولا يمكن تعديل البيانات.');
        }

        $customers = Customer::all();
        $contracts = Contract::all();

        return view('contract.transfer.edit', compact('contracts', 'customers', 'transfer'));
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(ContractTransferHistoryRequest $request, string $url_address)
    {
        // Find the existing transfer record
        $transfer = Contract_Transfer_History::where('url_address', $url_address)->first();

        if (!$transfer) {
            $ip = $this->getIPAddress();
            return view('contract.transfer.accessdenied', ['ip' => $ip]);
        }

        // Validate the request
        $validated = $request->validated();

        // Process old_customer_picture if present
        if ($request->has('old_customer_picture')) {
            $oldCustomerPicture = $request->input('old_customer_picture');
            if (strpos($oldCustomerPicture, 'data:image/jpeg;base64,') === 0) {
                $imageData = str_replace('data:image/jpeg;base64,', '', $oldCustomerPicture);
                $imageData = str_replace(' ', '+', $imageData);
                $imageName = 'old_customer_picture_' . time() . '.jpeg';
                $imagePath = 'public/transfer/' . $imageName;

                // Save the image to storage
                Storage::put($imagePath, base64_decode($imageData));

                // Add the image path to validated data
                $validated['old_customer_picture'] = str_replace('public/', 'storage/', $imagePath);
            }
        }

        // Process new_customer_picture if present
        if ($request->has('new_customer_picture')) {
            $newCustomerPicture = $request->input('new_customer_picture');
            if (strpos($newCustomerPicture, 'data:image/jpeg;base64,') === 0) {
                $imageData = str_replace('data:image/jpeg;base64,', '', $newCustomerPicture);
                $imageData = str_replace(' ', '+', $imageData);
                $imageName = 'new_customer_picture_' . time() . '.jpeg';
                $imagePath = 'public/transfer/' . $imageName;

                // Save the image to storage
                Storage::put($imagePath, base64_decode($imageData));

                // Add the image path to validated data
                $validated['new_customer_picture'] = str_replace('public/', 'storage/', $imagePath);
            }
        }


        // Update the transfer record
        $transfer->update($validated);

        return redirect()->route('transfer.index')
            ->with('success', 'تمت تعديل بيانات التناقل  ');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $url_address)
    {
        // Find the transfer record
        $transfer = Contract_Transfer_History::where('url_address', $url_address)->first();

        if (!$transfer) {
            return redirect()->route('transfer.index')
                ->with('error', 'بيانات التناقل غير موجودة.');
        }

        // Get the associated contract
        $contract = $transfer->contract;

        // Find the last transfer for the contract (based on created_at or id)
        $lastTransfer = Contract_Transfer_History::where('contract_id', $contract->id)
            ->where('approved', 1)  // Only consider approved transfers
            ->orderBy('created_at', 'desc') // Assuming the latest transfer is the last one by date
            ->first();

        // Check if the current transfer is the last approved transfer
        if ($lastTransfer && $lastTransfer->id == $transfer->id) {
            // If this is the last approved transfer, revert the contract's customer to the old customer
            $contract->update([
                'contract_customer_id' => $transfer->old_customer_id
            ]);
        }

        // Now delete the transfer record
        $transfer->delete();

        return redirect()->route('transfer.index')
            ->with('success', 'تمت حذف بيانات التناقل بنجاح وتم إعادة الزبون القديم.');
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
