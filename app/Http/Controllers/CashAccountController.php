<?php

namespace App\Http\Controllers;

use App\DataTables\CashAccountDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\CashAccountRequest;
use App\Models\Cash\Cash_Account;
use App\Models\Cash\Transaction;



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

        if (isset($cash_account)) {
            return view('cash_account.show', compact('cash_account'));
        } else {
            $ip = $this->getIPAddress();
            return view('cash_account.accessdenied', ['ip' => $ip]);
        }
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

            // Delete the cash_account
            $cash_account->delete();

            return redirect()->route('cash_account.index')
                ->with('success', 'تمت حذف الصندوق بنجاح.');
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
