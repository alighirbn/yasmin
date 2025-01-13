<?php

namespace App\Http\Controllers;

use App\DataTables\CashTransferDataTable;
use App\Http\Requests\CashTransferRequest;
use App\Models\Cash\CashTransfer;
use App\Models\Cash\Cash_Account;
use App\Models\Cash\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class CashTransferController extends Controller
{
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
