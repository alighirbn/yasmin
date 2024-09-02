<?php

namespace App\Http\Controllers;

use App\DataTables\ContractTransferHistoryDataTable;
use App\Models\Contract\Contract;
use App\Models\Customer\Customer;
use App\Http\Controllers\Controller;
use App\Http\Requests\ContractTransferHistoryRequest;
use App\Http\Requests\CustomerRequest;
use App\Models\Contract\Contract_Transfer_History;
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

        // Process webcam_image if present
        if ($request->has('webcam_image')) {
            $imageData = $request->input('webcam_image');
            $imageData = str_replace('data:image/jpeg;base64,', '', $imageData);
            $imageData = str_replace(' ', '+', $imageData);
            $imageName = 'webcam_' . time() . '.jpeg';
            $imagePath = 'public/transfer/' . $imageName;

            // Save the image to storage
            Storage::put($imagePath, base64_decode($imageData));

            // Add the image path to validated data
            $validated['webcam_image'] = str_replace('public/', 'storage/', $imagePath);
        }

        // Create a new transfer history record
        Contract_Transfer_History::create($validated);


        return redirect()->route('transfer.index')
            ->with('success', 'تم إرسال طلب  التناقل للموافقة.');
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

        $customers = Customer::all();



        $contracts = Contract::all();

        return view('contract.transfer.edit', compact('contracts', 'customers',  'transfer'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(ContractTransferHistoryRequest $request, string $url_address)
    {
        $transfer = Contract_Transfer_History::where('url_address', $url_address)->first();

        if (!$transfer) {
            $ip = $this->getIPAddress();
            return view('contract.transfer.accessdenied', ['ip' => $ip]);
        }

        // Update the contract
        $transfer->update($request->validated());
        return redirect()->route('transfer.index')
            ->with('success', 'تمت تعديل بيانات التناقل وأقساطه بنجاح');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $url_address)
    {
        $affected = Contract_Transfer_History::where('url_address', $url_address)->first();
        $affected->delete();
        return redirect()->route('transfer.index')
            ->with('success', 'تمت حذف بيانات التناقل بنجاح ');
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
