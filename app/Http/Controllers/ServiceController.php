<?php

namespace App\Http\Controllers;

use App\DataTables\ServiceDataTable;
use App\Http\Controllers\Controller;
use App\Models\Payment\Service;
use App\Http\Requests\ServiceRequest;
use App\Models\Contract\Contract;
use App\Models\Payment\Service_Type;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ServiceDataTable $dataTable)
    {
        return $dataTable->render('service.index');
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $service_types = Service_Type::all();
        $contracts = Contract::with(['building.building_category', 'customer'])->get();
        return view('service.create', compact(['service_types', 'contracts']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ServiceRequest $request)
    {
        service::create($request->validated());

        //inform the user
        return redirect()->route('service.index')
            ->with('success', 'تمت أضافة الخدمة بنجاح ');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $url_address)
    {
        $service = Service::with(['contract.customer', 'service_type', 'contract.building.building_category'])->where('url_address', '=', $url_address)->first();

        if (isset($service)) {
            return view('service.show', compact('service'));
        } else {
            $ip = $this->getIPAddress();
            return view('service.accessdenied', ['ip' => $ip]);
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $url_address)
    {

        $service_types = Service_Type::all();
        $contracts = Contract::with(['building.building_category', 'customer'])->get();
        $service = service::where('url_address', '=', $url_address)->first();
        if (isset($service)) {
            return view('service.edit', compact(['service', 'service_types', 'contracts']));
        } else {
            $ip = $this->getIPAddress();
            return view('service.accessdenied', ['ip' => $ip]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ServiceRequest $request, string $url_address)
    {
        // insert the user input into model and lareval insert it into the database.
        service::where('url_address', $url_address)->update($request->validated());

        //inform the user
        return redirect()->route('service.index')
            ->with('success', 'تمت تعديل الخدمة  بنجاح ');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $url_address)
    {
        $affected = Service::where('url_address', $url_address)->delete();
        return redirect()->route('service.index')
            ->with('success', 'تمت حذف الخدمة بنجاح ');
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
