<?php

namespace App\Http\Controllers;

use App\DataTables\PaymentDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentRequest;
use App\Models\Cash\Cash_Account;
use App\Models\Cash\Transaction;
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
            // Approve the payment
            $payment->approve();

            // Get the cash account (assuming main account with ID 1)
            $cashAccount = Cash_Account::find(1); // or find based on your logic

            // Adjust the cash account balance by crediting the payment amount
            $cashAccount->adjustBalance($payment->payment_amount, 'credit');

            // Create a transaction for the approved payment
            Transaction::create([
                'url_address' => $this->get_random_string(60),
                'cash_account_id' => $cashAccount->id,
                'transactionable_id' => $payment->id,
                'transactionable_type' => Payment::class,
                'transaction_amount' => $payment->payment_amount,
                'transaction_date' => now(),
                'transaction_type' => 'credit', // Since it's a payment
            ]);

            return redirect()->route('contract.show', $payment->contract->url_address)
                ->with('success', 'تم قبول الدفعة بنجاح وتم تسجيل المعاملة في الحساب النقدي.');
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
            ->with('success', 'تمت تعديل الدفعة  بنجاح ');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $url_address)
    {
        $payment = Payment::where('url_address', $url_address)->first();

        if (isset($payment)) {
            if ($payment->approved) {
                // Adjust the cash account balance by debiting the payment amount
                $cashAccount = Cash_Account::find(1); // or find based on your logic
                $cashAccount->adjustBalance($payment->payment_amount, 'debit');

                // Delete related transactions
                $payment->transactions()->delete();
            }

            // Delete the payment
            $payment->delete();

            return redirect()->route('payment.index')
                ->with('success', 'تمت حذف الدفعة بنجاح ');
        } else {
            $ip = $this->getIPAddress();
            return view('payment.accessdenied', ['ip' => $ip]);
        }
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
    function get_random_string($length)
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
}
