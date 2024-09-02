<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Building\Building;
use App\Models\Contract\Contract;
use App\Models\Customer\Customer;
use App\Http\Controllers\Controller;
use App\Models\Contract\Installment;
use App\DataTables\ContractDataTable;
use App\Http\Requests\ContractRequest;
use App\Http\Requests\ContractTransferHistoryRequest;
use App\Http\Requests\CustomerRequest;
use App\Models\Payment\Payment_Method;
use App\Models\Contract\Contract_Installment;
use App\Models\Contract\Contract_Transfer_History;
use App\Models\Payment\Payment;
use Illuminate\Http\Request;

class ContractController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ContractDataTable $dataTable)
    {
        return $dataTable->render('contract.contract.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function customercreate()
    {
        return view('contract.contract.customercreate');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function customerstore(CustomerRequest $request)
    {
        Customer::create($request->validated());

        //inform the user
        return redirect()->route('contract.create')
            ->with('success', 'تمت أضافة الزبون بنجاح ');
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::all();
        $buildings = Building::doesntHave('contract')->get();
        // $buildings = Building::has('contract')->get();
        $payment_methods = Payment_Method::all();

        return view('contract.contract.create', compact(['customers', 'buildings', 'payment_methods']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ContractRequest $request)
    {
        $contract = Contract::create($request->validated());

        $payment_method = $request->input('contract_payment_method_id');


        if ($payment_method == 2) {
            $installments = Installment::where('payment_method_id', 2)->get();
            foreach ($installments as $installment) {
                Contract_Installment::create([
                    'url_address' => $this->random_string(60),
                    'installment_amount' => $installment->installment_percent * $request->contract_amount,
                    'installment_date' => Carbon::parse($contract->contract_date)->addMonth($installment->installment_period),
                    'contract_id' => $contract->id,
                    'installment_id' => $installment->id,
                    'user_id_create' => $request->user_id_create,
                ]);
            }
        } else {
            $installments = Installment::where('payment_method_id', 1)->get();
            foreach ($installments as $installment) {
                Contract_Installment::create([
                    'url_address' => $this->random_string(60),
                    'installment_amount' => $request->contract_amount,
                    'installment_date' => $request->contract_date,
                    'contract_id' => $contract->id,
                    'installment_id' => $installment->id,
                    'user_id_create' => $request->user_id_create,
                ]);
            }
        }

        //inform the user
        return redirect()->route('contract.show', $contract->url_address)
            ->with('success', 'تمت أضافة العقد بنجاح ');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $url_address)
    {
        $contract = Contract::with(['customer', 'building', 'payment_method'])->where('url_address', '=', $url_address)->first();
        $contract_installments = Contract_Installment::with(['installment', 'payment'])->where('contract_id', $contract->id)->get();
        if (isset($contract)) {
            return view('contract.contract.show', compact(['contract', 'contract_installments']));
        } else {
            $ip = $this->getIPAddress();
            return view('contract.contract.accessdenied', ['ip' => $ip]);
        }
    }

    public function print(string $url_address)
    {
        $contract = Contract::with(['customer', 'building', 'payment_method'])->where('url_address', '=', $url_address)->first();
        $contract_installments = Contract_Installment::with(['installment', 'payment'])->where('contract_id', $contract->id)->get();
        if (isset($contract)) {
            return view('contract.contract.print', compact(['contract', 'contract_installments']));
        } else {
            $ip = $this->getIPAddress();
            return view('contract.contract.accessdenied', ['ip' => $ip]);
        }
    }

    public function add_payment(string $url_address)
    {
        // Retrieve the contract installment with the related contract, building, and customer
        $contract_installment = Contract_Installment::with(['contract.building', 'contract.customer'])
            ->where('url_address', '=', $url_address)
            ->first();

        // Check if the contract installment exists
        if (isset($contract_installment)) {

            // Check if the installment has already been paid
            if ($contract_installment->payment) {
                // If the installment has been paid, redirect to the payment details page
                return redirect()->route('payment.show', $contract_installment->payment->url_address)
                    ->with('error', 'تم استلام هذه الدفعة مسبقا يرجى التأكد');
            }

            // Create the payment record
            $payment = Payment::create([
                'url_address' => $this->random_string(60),
                'payment_amount' => $contract_installment->installment_amount,
                'payment_date' => Carbon::now()->format('Y-m-d'),
                'payment_contract_id' => $contract_installment->contract_id,
                'contract_installment_id' => $contract_installment->id,
                'user_id_create' => auth()->user()->id,
            ]);

            // Redirect to the payment details page
            return redirect()->route('payment.show', $payment->url_address);
        } else {
            // If the contract installment doesn't exist, redirect to the access denied view
            $ip = $this->getIPAddress();
            return view('contract.contract.accessdenied', ['ip' => $ip]);
        }
    }



    /**
     * Display the statement of account for the given contract.
     *
     * @param  string $url_address
     * @return \Illuminate\Http\Response
     */
    public function statement(string $url_address)
    {
        // Retrieve the contract with necessary relationships and aggregate sums

        $contract = Contract::with([
            'customer',
            'building',
            'payment_method',
            'services',
            'contract_installments.installment',
        ])
            ->withSum('contract_installments', 'installment_amount') // Sum of installment amounts
            ->withSum('services', 'service_amount') // Sum of service amounts
            ->where('url_address', $url_address)
            ->first();

        // Sum of approved payments
        $total_approved_payments = $contract->payments()->where('approved', true)->sum('payment_amount');


        // Prepare the data for the view
        $data = [
            'contract' => $contract,
            'total_installments' => $contract->contract_installments_sum_installment_amount,
            'total_payments' => $total_approved_payments,
            'total_services' => $contract->services_sum_service_amount,
            'outstanding_amount' =>
            $contract->contract_installments_sum_installment_amount +
                $contract->services_sum_service_amount -
                $total_approved_payments,
        ];



        // Return the view with the prepared data
        return view('contract.contract.statement', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $url_address)
    {
        $contract = Contract::where('url_address', '=', $url_address)
            ->with('payments', 'building') // Ensure 'building' is loaded
            ->first();

        if (!$contract) {
            $ip = $this->getIPAddress();
            return view('contract.contract.accessdenied', ['ip' => $ip]);
        }

        // Check if the contract has any payments
        if ($contract->payments->count() > 0) {
            return redirect()->route('contract.index')
                ->with('error', 'لا يمكن تعديل العقد لأنه يحتوي على دفعات.');
        }

        $customers = Customer::all();

        // Get buildings that either:
        // 1. Don't have any contracts, OR
        // 2. Are associated with the current contract
        $buildings = Building::whereDoesntHave('contract')
            ->orWhere('id', $contract->contract_building_id)
            ->get();

        $payment_methods = Payment_Method::all();

        return view('contract.contract.edit', compact('contract', 'customers', 'buildings', 'payment_methods'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(ContractRequest $request, string $url_address)
    {
        $contract = Contract::where('url_address', $url_address)
            ->with(['payments', 'contract_installments.installment'])
            ->first();

        if (!$contract) {
            $ip = $this->getIPAddress();
            return view('contract.contract.accessdenied', ['ip' => $ip]);
        }

        // Check if the contract has any payments
        if ($contract->payments->count() > 0) {
            return redirect()->route('contract.index')
                ->with('error', 'لا يمكن تعديل العقد لأنه يحتوي على دفعات.');
        }

        // Update the contract
        $contract->update($request->validated());

        // Retrieve the payment method
        $payment_method_id = $request->input('contract_payment_method_id');


        // Update the contract installments based on the payment method
        if ($payment_method_id == 2 && $contract->contract_installments->count() == 11) {
            // For payment method 2, update each installment
            foreach ($contract->contract_installments as $contract_installment) {
                $contract_installment->update([
                    'installment_amount' => $contract_installment->installment->installment_percent * $request->contract_amount,
                    'installment_date' => Carbon::parse($contract->contract_date)->addMonth($contract_installment->installment->installment_period),
                ]);
            }
        } elseif ($payment_method_id == 1 && $contract->contract_installments->count() == 1) {
            // For other payment methods, update each installment
            foreach ($contract->contract_installments as $contract_installment) {
                $contract_installment->update([
                    'installment_amount' => $request->contract_amount,
                    'installment_date' => $request->contract_date,
                ]);
            }
        } elseif ($payment_method_id == 2 && $contract->contract_installments->count() != 11) {
            $contract->contract_installments()->delete();
            $installments = Installment::where('payment_method_id', 2)->get();
            foreach ($installments as $installment) {
                Contract_Installment::create([
                    'url_address' => $this->random_string(60),
                    'installment_amount' => $installment->installment_percent * $request->contract_amount,
                    'installment_date' => Carbon::parse($contract->contract_date)->addMonth($installment->installment_period),
                    'contract_id' => $contract->id,
                    'installment_id' => $installment->id,
                    'user_id_create' => $request->user_id_update,
                ]);
            }
        } elseif ($payment_method_id == 1 && $contract->contract_installments->count() != 1) {
            $contract->contract_installments()->delete();
            $installments = Installment::where('payment_method_id', 1)->get();
            foreach ($installments as $installment) {
                Contract_Installment::create([
                    'url_address' => $this->random_string(60),
                    'installment_amount' => $request->contract_amount,
                    'installment_date' => $request->contract_date,
                    'contract_id' => $contract->id,
                    'installment_id' => $installment->id,
                    'user_id_create' => $request->user_id_create,
                ]);
            }
        }


        return redirect()->route('contract.index')
            ->with('success', 'تمت تعديل بيانات العقد وأقساطه بنجاح');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $url_address)
    {
        $affected = Contract::where('url_address', $url_address)->first();

        Contract_Installment::where('contract_id', $affected->id)->delete();
        $affected->delete();
        return redirect()->route('contract.index')
            ->with('success', 'تمت حذف بيانات العقد بنجاح ');
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

    public function add_months($date, $installment_months)
    {

        $timestamp = $date->addMonth($installment_months)->format('d/m/Y');

        return $timestamp;
    }
}
