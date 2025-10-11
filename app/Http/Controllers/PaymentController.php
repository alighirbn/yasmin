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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(PaymentDataTable $dataTable, Request $request)
    {
        // Check if a contract ID is provided in the request
        $contractId = $request->input('contract_id');

        // Check if a contract ID is provided in the request
        $onlyPending = $request->input('onlyPending');

        // Pass the contract ID to the DataTable if it exists
        return $dataTable->forContract($contractId)->onlyPending($onlyPending)->render('payment.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $contracts = Contract::with(['building.building_category', 'customer'])
            ->whereNotIn('stage', ['terminated'])
            ->get();
        return view('payment.create', compact(['contracts']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PaymentRequest $request)
    {
        $payment = Payment::create($request->validated());

        return redirect()->route('payment.show', $payment->url_address)
            ->with('success', 'تمت أضافة الدفعة بنجاح في انتظار الموافقة عليها ');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $url_address)
    {
        try {
            $payment = Payment::with([
                'contract.customer',
                'contract.building.building_category',
                'contract_installment.installment'
            ])->where('url_address', $url_address)->firstOrFail();

            $user = auth()->user();

            $cash_accounts = $user->hasRole('admin|ahmed|all access')
                ? Cash_Account::all()
                : Cash_Account::where('id', 5)->get();

            return view('payment.show', compact('payment', 'cash_accounts'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            $ip = $this->getIPAddress();
            return view('payment.accessdenied', ['ip' => $ip]);
        }
    }

    public function approve(Request $request, string $url_address)
    {
        try {
            // Fetch payment with relationships for notifications and validation
            $payment = Payment::with(['contract', 'contract_installment.installment'])
                ->where('url_address', $url_address)
                ->first();

            if (!$payment) {
                $ip = $this->getIPAddress();
                return view('payment.accessdenied', ['ip' => $ip]);
            }

            // Prevent duplicate approval
            if ($payment->approved) {
                return redirect()->route('contract.show', $payment->contract->url_address)
                    ->with('error', 'تمت الموافقة على الدفعة مسبقًا.');
            }


            // Validate selected account
            $cash_account_id = $request->cash_account_id;
            $cashAccount = Cash_Account::find($cash_account_id);
            if (!$cashAccount) {
                return redirect()->route('contract.show', $payment->contract->url_address)
                    ->with('error', 'الحساب النقدي المحدد غير موجود.');
            }

            // Wrap everything in DB transaction for atomicity
            DB::beginTransaction();

            // ✅ Approve the payment
            $payment->approved = true;
            $payment->cash_account_id = $cash_account_id;
            $payment->save();

            // ✅ Adjust the cash account balance (safe fallback if method missing)
            if (method_exists($cashAccount, 'adjustBalance')) {
                $cashAccount->adjustBalance($payment->payment_amount, 'credit');
            } else {
                $cashAccount->balance = ($cashAccount->balance ?? 0) + $payment->payment_amount;
                $cashAccount->save();
            }

            // ✅ Create a corresponding transaction record
            $transaction = Transaction::create([
                'url_address' => $this->get_random_string(60),
                'cash_account_id' => $cashAccount->id,
                'transactionable_id' => $payment->id,
                'transactionable_type' => Payment::class,
                'transaction_amount' => $payment->payment_amount,
                'transaction_date' => now(),
                'transaction_type' => 'credit',
            ]);

            if (!$transaction) {
                throw new \Exception('فشل إنشاء سجل المعاملة المالية.');
            }

            // ✅ Mark the related installment as paid if it exists
            if ($payment->contract_installment) {
                $payment->contract_installment->paid = true;
                $payment->contract_installment->save();
            }

            // ✅ Notify lawyers, subaccounts, and admins for first 2 installments
            $installmentId = optional(optional($payment->contract_installment)->installment)->id;
            if ($installmentId && in_array($installmentId, [1, 2])) {
                $lawyers = User::role('lawyer')->get();
                $subaccounts = User::role('sub.accaunt')->get();
                $admins = User::role('admin')->get();

                foreach ([$lawyers, $subaccounts, $admins] as $group) {
                    foreach ($group as $user) {
                        $user->notify(new PaymentNotify($payment));
                    }
                }
            }

            // ✅ Commit DB changes
            DB::commit();

            return redirect()->route('contract.show', $payment->contract->url_address)
                ->with('success', '✅ تم قبول الدفعة بنجاح وتم إنشاء المعاملة المالية.');
        } catch (\Exception $e) {
            // Roll back in case of any issue
            DB::rollBack();

            // Log detailed error
            Log::error('Payment approval failed', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return redirect()->back()->with(
                'error',
                'حدث خطأ أثناء الموافقة على الدفعة: ' . $e->getMessage()
            );
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

        $contracts = Contract::with(['building.building_category', 'customer'])
            ->whereNotIn('stage', ['terminated'])
            ->get();
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
                $cashAccount = Cash_Account::find($payment->cash_account_id); // or find based on your logic
                $cashAccount->adjustBalance($payment->payment_amount, 'debit');

                // Delete related transactions
                $payment->transactions()->delete();
            }
            // Update the contract_installment->paid field to false
            if ($payment->contract_installment) {
                $payment->contract_installment->paid = false;
                $payment->contract_installment->save(); // Save the updated contract installment
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
