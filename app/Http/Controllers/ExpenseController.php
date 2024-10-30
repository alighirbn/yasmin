<?php

namespace App\Http\Controllers;

use App\DataTables\ExpenseDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\ExpenseRequest;
use App\Models\Cash\Expense;
use App\Models\Cash\Transaction;
use App\Models\Cash\Cash_Account;
use App\Models\Cash\Expense_Type;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(ExpenseDataTable $dataTable, Request $request)
    {
        $onlyPending = $request->input('onlyPending');
        return $dataTable->onlyPending($onlyPending)->render('expense.index');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $expense_types = Expense_Type::all();
        return view('expense.create', compact('expense_types'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ExpenseRequest $request)
    {
        $expense = Expense::create($request->validated());

        // Return a success message and redirect
        return redirect()->route('expense.index')
            ->with('success', 'تمت إضافة المصروف بنجاح، في انتظار الموافقة.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $url_address)
    {
        $expense = Expense::where('url_address', $url_address)->first();

        if (isset($expense)) {
            $cash_accounts = Cash_Account::all();
            return view('expense.show', compact(['expense', 'cash_accounts']));
        } else {
            $ip = $this->getIPAddress();
            return view('expense.accessdenied', ['ip' => $ip]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $url_address)
    {
        $expense = Expense::where('url_address', $url_address)->first();

        if (isset($expense)) {
            if ($expense->approved) {
                return redirect()->route('expense.index')
                    ->with('error', 'لا يمكن تعديل مصروف تمت الموافقة عليه.');
            }
            $expense_types = Expense_Type::all();
            return view('expense.edit', compact(['expense', 'expense_types']));
        } else {
            $ip = $this->getIPAddress();
            return view('expense.accessdenied', ['ip' => $ip]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ExpenseRequest $request, string $url_address)
    {
        $expense = Expense::where('url_address', $url_address)->first();

        if (isset($expense)) {
            $expense->update($request->validated());

            return redirect()->route('expense.index')
                ->with('success', 'تمت تعديل المصروف بنجاح.');
        } else {
            $ip = $this->getIPAddress();
            return view('expense.accessdenied', ['ip' => $ip]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $url_address)
    {
        $expense = Expense::where('url_address', $url_address)->first();

        if (isset($expense)) {
            // Adjust cash account balance if necessary
            if ($expense->approved) {
                $cashAccount = Cash_Account::find(1); // or find based on your logic
                $cashAccount->adjustBalance($expense->expense_amount, 'credit');
            }

            // Delete related transactions
            $expense->transactions()->delete();

            // Delete the expense
            $expense->delete();

            return redirect()->route('expense.index')
                ->with('success', 'تمت حذف المصروف بنجاح.');
        } else {
            $ip = $this->getIPAddress();
            return view('expense.accessdenied', ['ip' => $ip]);
        }
    }


    /**
     * Approve the expense and create a transaction.
     */
    public function approve(Request $request, string $url_address)
    {
        $expense = Expense::where('url_address', $url_address)->first();

        if (isset($expense)) {
            // Approve the expense
            $expense->approve();

            $cash_account_id = $request->cash_account_id;
            $expense->cash_account_id = $cash_account_id;
            $expense->save(); // Save the updated payment model


            // Adjust cash account balance
            $cashAccount = Cash_Account::find($cash_account_id); // or find based on your logic
            $cashAccount->adjustBalance($expense->expense_amount, 'debit');

            // Create a transaction for the approved expense
            Transaction::create([
                'url_address' => $this->get_random_string(60),
                'cash_account_id' => $cashAccount->id,
                'transactionable_id' => $expense->id,
                'transactionable_type' => Expense::class,
                'transaction_amount' => $expense->expense_amount,
                'transaction_date' => now(),
                'transaction_type' => 'debit', // Since it's an expense
            ]);

            return redirect()->route('expense.index')
                ->with('success', 'تمت الموافقة على المصروف وتم تسجيل المعاملة في الحساب النقدي.');
        } else {
            $ip = $this->getIPAddress();
            return view('expense.accessdenied', ['ip' => $ip]);
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
