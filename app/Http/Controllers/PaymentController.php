<?php

namespace App\Http\Controllers;

use App\DataTables\PaymentDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentRequest;
use App\Models\Contract\Contract;
use App\Models\Payment\Payment;
use App\Models\User;
use App\Notifications\PaymentNotify;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(PaymentDataTable $dataTable, Request $request)
    {
        // Check if a contract ID is provided in the request
        $contractId = $request->input('contract_id');

        // Pass the contract ID to the DataTable if it exists
        return $dataTable->forContract($contractId)->render('payment.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $contracts = Contract::with(['building.building_category', 'customer'])->get();
        return view('payment.create', compact(['contracts']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PaymentRequest $request)
    {
        $payment = Payment::create($request->validated());

        // Notify all users with 'accountant' role
        $accountants = User::role('accountant')->get(); // Assuming you're using a role system
        foreach ($accountants as $accountant) {
            $accountant->notify(new PaymentNotify($payment));
        }

        return redirect()->route('payment.index')
            ->with('success', 'تمت أضافة الدفعة بنجاح في انتظار الموافقة عليها ');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $url_address)
    {
        $payment = Payment::with(['contract.customer', 'contract.building.building_category', 'contract_installment.installment'])->where('url_address', '=', $url_address)->first();

        if (isset($payment)) {
            return view('payment.show', compact('payment'));
        } else {
            $ip = $this->getIPAddress();
            return view('payment.accessdenied', ['ip' => $ip]);
        }
    }
    public function approve(string $url_address)
    {
        $payment = Payment::where('url_address', '=', $url_address)->first();

        if (isset($payment)) {
            $payment->approve();
            return redirect()->route('contract.show', $payment->contract->url_address)->with('success', 'تم قبول الدفعة بنجاح');
        } else {
            $ip = $this->getIPAddress();
            return view('payment.accessdenied', ['ip' => $ip]);
        }
    }

    public function pending($url_address)
    {
        // Retrieve the contract by its url_address
        $contract = Contract::where('url_address', $url_address)->first();

        if (!$contract) {
            // Handle the case where the contract is not found
            return redirect()->route('contract.index')
                ->with('error', 'حدث خطا العقد غير موجود');
        }

        // Fetch all payments associated with the contract that are 'approved' is false
        $pendingPayments = Payment::where('payment_contract_id', $contract->id)
            ->where('approved', false)
            ->get();

        // Return the view with pending payments and contract details
        return view('payment.pending', compact(['pendingPayments', 'contract']));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $url_address)
    {
        $contracts = Contract::with(['building.building_category', 'customer'])->get();
        $payment = Payment::where('url_address', '=', $url_address)->first();

        if (isset($payment)) {
            if ($payment->approved) {
                return redirect()->route('payment.index')
                    ->with('error', 'لا يمكن تعديل دفعة موافق عليها.');
            }

            return view('payment.edit', compact(['payment', 'contracts']));
        } else {
            $ip = $this->getIPAddress();
            return view('payment.accessdenied', ['ip' => $ip]);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(PaymentRequest $request, string $url_address)
    {
        // insert the user input into model and lareval insert it into the database.
        Payment::where('url_address', $url_address)->update($request->validated());

        //inform the user
        return redirect()->route('payment.index')
            ->with('success', 'تمت تعديل الخدمة  بنجاح ');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $url_address)
    {
        $affected = Payment::where('url_address', $url_address)->delete();
        return redirect()->route('payment.index')
            ->with('success', 'تمت حذف السند بنجاح ');
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
