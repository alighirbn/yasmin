<?php

namespace App\Http\Controllers;

use App\DataTables\PaymentDataTable;
use App\Http\Controllers\Controller;
use App\Models\Payment\Payment;



class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(PaymentDataTable $dataTable)
    {
        return $dataTable->render('payment.index');
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
