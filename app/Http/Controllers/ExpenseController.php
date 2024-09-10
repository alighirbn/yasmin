<?php

namespace App\Http\Controllers;

use App\DataTables\ExpenseDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\ExpenseRequest;
use App\Models\Cash\Expense;
use App\Models\Cash\Transaction;
use App\Models\Cash\Cash_Account;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(ExpenseDataTable $dataTable)
    {
        return $dataTable->render('expense.index');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('expense.create');
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
            return view('expense.show', compact('expense'));
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

            return view('expense.edit', compact('expense'));
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
    public function approve(string $url_address)
    {
        $expense = Expense::where('url_address', $url_address)->first();

        if (isset($expense)) {
            // Approve the expense
            $expense->approve();

            // Adjust cash account balance
            $cashAccount = Cash_Account::find(1); // or find based on your logic
            $cashAccount->adjustBalance($expense->expense_amount, 'debit');

            // Create a transaction for the approved expense
            Transaction::create([
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
}
