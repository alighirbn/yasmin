<?php

namespace App\Http\Controllers;

use App\DataTables\IncomeDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\IncomeRequest;
use App\Models\Cash\Income;
use App\Models\Cash\Transaction;
use App\Models\Cash\Cash_Account;
use App\Models\Cash\Income_Type;
use Illuminate\Http\Request;

class IncomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(IncomeDataTable $dataTable, Request $request)
    {
        $onlyPending = $request->input('onlyPending');
        return $dataTable->onlyPending($onlyPending)->render('income.index');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $income_types = Income_Type::all();
        return view('income.create', compact('income_types'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(IncomeRequest $request)
    {
        $income = Income::create($request->validated());

        // Return a success message and redirect
        return redirect()->route('income.show', $income->url_address)
            ->with('success', 'تمت إضافة الايراد بنجاح، في انتظار الموافقة.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $url_address)
    {
        $income = Income::where('url_address', $url_address)->first();

        if (isset($income)) {
            $cash_accounts = Cash_Account::all();
            return view('income.show', compact(['income', 'cash_accounts']));
        } else {
            $ip = $this->getIPAddress();
            return view('income.accessdenied', ['ip' => $ip]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $url_address)
    {
        $income = Income::where('url_address', $url_address)->first();

        if (isset($income)) {
            if ($income->approved) {
                return redirect()->route('income.index')
                    ->with('error', 'لا يمكن تعديل مصروف تمت الموافقة عليه.');
            }
            $income_types = Income_Type::all();
            return view('income.edit', compact(['income', 'income_types']));
        } else {
            $ip = $this->getIPAddress();
            return view('income.accessdenied', ['ip' => $ip]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(IncomeRequest $request, string $url_address)
    {
        $income = Income::where('url_address', $url_address)->first();

        if (isset($income)) {
            $income->update($request->validated());

            return redirect()->route('income.index')
                ->with('success', 'تمت تعديل الايراد بنجاح.');
        } else {
            $ip = $this->getIPAddress();
            return view('income.accessdenied', ['ip' => $ip]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $url_address)
    {
        $income = Income::where('url_address', $url_address)->first();

        if (isset($income)) {
            // Adjust cash account balance if necessary
            if ($income->approved) {
                $cashAccount = Cash_Account::find($income->cash_account_id); // or find based on your logic
                $cashAccount->adjustBalance($income->income_amount, 'debit');
            }

            // Delete related transactions
            $income->transactions()->delete();

            // Delete the income
            $income->delete();

            return redirect()->route('income.index')
                ->with('success', 'تمت حذف الايراد بنجاح.');
        } else {
            $ip = $this->getIPAddress();
            return view('income.accessdenied', ['ip' => $ip]);
        }
    }


    /**
     * Approve the income and create a transaction.
     */
    public function approve(Request $request, string $url_address)
    {
        $income = Income::where('url_address', $url_address)->first();

        if (isset($income)) {
            // Approve the income
            $income->approve();

            $cash_account_id = $request->cash_account_id;
            $income->cash_account_id = $cash_account_id;
            $income->save(); // Save the updated payment model


            // Adjust cash account balance
            $cashAccount = Cash_Account::find($cash_account_id); // or find based on your logic
            $cashAccount->adjustBalance($income->income_amount, 'credit');

            // Create a transaction for the approved income
            Transaction::create([
                'url_address' => $this->get_random_string(60),
                'cash_account_id' => $cashAccount->id,
                'transactionable_id' => $income->id,
                'transactionable_type' => Income::class,
                'transaction_amount' => $income->income_amount,
                'transaction_date' => now(),
                'transaction_type' => 'credit', // Since it's an income
            ]);

            return redirect()->route('income.index')
                ->with('success', 'تمت الموافقة على الايراد وتم تسجيل المعاملة في الحساب النقدي.');
        } else {
            $ip = $this->getIPAddress();
            return view('income.accessdenied', ['ip' => $ip]);
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
