<?php

namespace App\Http\Controllers;

use App\DataTables\CustomerDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRequest;
use App\Models\Customer\Customer;



class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(CustomerDataTable $dataTable)
    {
        return $dataTable->render('customer.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('customer.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CustomerRequest $request)
    {
        Customer::create($request->validated());

        //inform the user
        return redirect()->route('customer.index')
            ->with('success', 'تمت أضافة البيانات بنجاح ');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $url_address)
    {
        $customer = Customer::where('url_address', '=', $url_address)->first();
        if (isset($customer)) {
            return view('customer.show', compact('customer'));
        } else {
            $ip = $this->getIPAddress();
            return view('customer.accessdenied', ['ip' => $ip]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $url_address)
    {

        $customer = Customer::where('url_address', '=', $url_address)->first();
        if (isset($customer)) {
            return view('customer.edit', compact('customer'));
        } else {
            $ip = $this->getIPAddress();
            return view('customer.accessdenied', ['ip' => $ip]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CustomerRequest $request, string $url_address)
    {
        // insert the user input into model and lareval insert it into the database.
        Customer::where('url_address', $url_address)->update($request->validated());

        //inform the user
        return redirect()->route('customer.index')
            ->with('success', 'تمت تعديل البيانات  بنجاح ');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $url_address)
    {
        $affected = Customer::where('url_address', $url_address)->delete();
        return redirect()->route('customer.index')
            ->with('success', 'تمت حذف البيانات بنجاح ');
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
