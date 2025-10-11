<?php

namespace App\Http\Controllers;

use App\DataTables\CashAccountDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\CashAccountRequest;
use App\Models\Cash\Cash_Account;
use App\Models\Cash\CashTransfer;
use App\Models\Cash\Transaction;
use App\Models\Payment\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CashAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(CashAccountDataTable $dataTable)
    {
        return $dataTable->render('cash_account.index');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('cash_account.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CashAccountRequest $request)
    {
        $cash_account = Cash_Account::create($request->validated());

        // Return a success message and redirect
        return redirect()->route('cash_account.index')
            ->with('success', 'تمت إضافة الصندوق بنجاح.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $url_address)
    {
        $cash_account = Cash_Account::where('url_address', $url_address)->first();

        $newBalance = $cash_account->recalculateBalance();

        if (isset($cash_account)) {
            return view('cash_account.show', compact('cash_account'));
        } else {
            $ip = $this->getIPAddress();
            return view('cash_account.accessdenied', ['ip' => $ip]);
        }
    }

    public function statement(Request $request, $url_address)
    {
        // Retrieve the cash account by its URL address
        $cashAccount = Cash_Account::where('url_address', $url_address)->firstOrFail();

        // 🧩 Ensure every approved payment under this cash account has a transaction
        $cashAccount->payments()
            ->where('approved', true)
            ->each(function ($payment) {
                if (!$payment->transactions()->exists()) {
                    Transaction::create([
                        'url_address' => $this->get_random_string(60),
                        'cash_account_id' => $payment->cash_account_id,
                        'transaction_amount' => $payment->payment_amount,
                        'transaction_type' => 'credit',
                        'transaction_date' => $payment->payment_date,
                        'transactionable_type' => Payment::class,
                        'transactionable_id' => $payment->id,
                        'user_id_create' => $payment->user_id_create ?? auth()->id(),
                    ]);
                }
            });

        // 🔄 Recalculate the account balance (if you have this logic implemented)
        $newBalance = $cashAccount->recalculateBalance();

        // Get all transactions for the cash account, sorted by date
        $transactions = $cashAccount->transactions()
            ->orderBy('transaction_date', 'asc')
            ->get();

        // Initialize the running balance
        $runningBalance = 0;

        // Iterate over transactions to calculate the running balance
        $transactions->each(function ($transaction) use (&$runningBalance) {
            if ($transaction->transaction_type === 'credit') {
                $runningBalance += $transaction->transaction_amount;
            } elseif ($transaction->transaction_type === 'debit') {
                $runningBalance -= $transaction->transaction_amount;
            }

            // Attach the running balance for display
            $transaction->running_balance = $runningBalance;

            // Load the polymorphic relation (e.g., Payment, Expense)
            $transaction->transactionable;
        });

        // Get start and end dates from the request for filtering
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        return view('cash_account.statement', compact('cashAccount', 'transactions', 'startDate', 'endDate'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $url_address)
    {
        $cash_account = Cash_Account::where('url_address', $url_address)->first();

        if (isset($cash_account)) {
            return view('cash_account.edit', compact(['cash_account']));
        } else {
            $ip = $this->getIPAddress();
            return view('cash_account.accessdenied', ['ip' => $ip]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CashAccountRequest $request, string $url_address)
    {
        $cash_account = Cash_Account::where('url_address', $url_address)->first();

        if (isset($cash_account)) {
            $cash_account->update($request->validated());
            return redirect()->route('cash_account.index')
                ->with('success', 'تمت تعديل الصندوق بنجاح.');
        } else {
            $ip = $this->getIPAddress();
            return view('cash_account.accessdenied', ['ip' => $ip]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $url_address)
    {
        $cash_account = Cash_Account::where('url_address', $url_address)->first();

        if (isset($cash_account)) {
            // Check if the cash account ID is 1
            if ($cash_account->id == 1) {
                return redirect()->route('cash_account.index')
                    ->with('error', 'لا يمكن حذف هذا الصندوق.');
            }

            // Delete the cash_account
            $cash_account->delete();

            return redirect()->route('cash_account.index')
                ->with('success', 'تم حذف الصندوق بنجاح.');
        } else {
            $ip = $this->getIPAddress();
            return view('cash_account.accessdenied', ['ip' => $ip]);
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
