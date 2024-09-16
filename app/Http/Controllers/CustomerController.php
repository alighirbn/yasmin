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
    public function statement(string $url_address)
    {
        // Fetch the customer along with all related contracts, payments, installments, services, and transfers
        $customer = Customer::with([
            'contracts.payments',
            'contracts.contract_installments',
            'contracts.building',
            'contracts.services', // Include services
            'contracts.transfers' // Include transfers
        ])->where('url_address', '=', $url_address)->first();

        // Prepare the data for the statement of account
        $data = [];
        $grandTotal = 0; // Initialize grand total for all contracts

        foreach ($customer->contracts as $contract) {
            $contractTotal = 0; // Initialize contract total
            $runningTotal = 0;  // Initialize running total

            $entries = [];

            // Collect all entries
            foreach ($contract->payments->where('approved', true) as $payment) {
                $entries[] = [
                    'type' =>  'دفعة بالعدد ' . $payment->id,
                    'amount' => $payment->payment_amount, // Store amount as raw number
                    'date' => $payment->payment_date,
                    'status' => $payment->approved ? __('word.approved') : __('word.pending'),
                    'transaction_type' => 'payment'
                ];
            }

            foreach ($contract->contract_installments as $installment) {
                $entries[] = [
                    'type' => 'قسط ( ' . __('word.installment') . ' ' . $installment->installment->installment_name . ' ) ',
                    'amount' => $installment->installment_amount, // Store amount as raw number
                    'date' => $installment->installment_date,
                    'status' => $installment->payment ? __('word.paid') : __('word.unpaid'),
                    'transaction_type' => 'installment'
                ];
            }

            foreach ($contract->transfers->where('approved', true) as $transfer) {
                $entries[] = [
                    'type' => __('word.transfer') . ' بالعدد ' . $transfer->id,
                    'amount' => $transfer->transfer_amount, // Store amount as raw number
                    'date' => $transfer->transfer_date,
                    'status' => __('word.approved'),
                    'transaction_type' => 'transfer'
                ];
            }

            foreach ($contract->services as $service) {
                $entries[] = [
                    'type' => __('word.service') . ' بالعدد ' . $service->id . ' ( ' . $service->service_type->type_name . ' )',
                    'amount' => $service->service_amount, // Store amount as raw number
                    'date' => $service->service_date, // Assuming service_date is stored in the pivot table
                    'status' => '',
                    'transaction_type' => 'service'
                ];
            }

            // Sort the entries by date
            usort($entries, function ($a, $b) {
                return strtotime($a['date']) - strtotime($b['date']);
            });

            // Calculate running total and contract total
            foreach ($entries as &$entry) {
                if ($entry['transaction_type'] === 'payment') {
                    $runningTotal -= $entry['amount'];
                } else {
                    $runningTotal += $entry['amount'];
                }

                $entry['running_total'] = $runningTotal; // Store running total as raw number
            }

            // Add the contract total and grand total
            $contractTotal = $runningTotal; // Since running total is cumulative
            $data[] = [
                'contract_id' => $contract->id,
                'contract_date' => $contract->contract_date,
                'building_number' => $contract->building->building_number,
                'contract_amount' => $contract->contract_amount, // Store amount as raw number
                'entries' => $entries,
                'contract_total' => $contractTotal,
            ];

            $grandTotal += $contractTotal;
        }

        // Pass raw totals to the view for formatting
        return view('customer.statement', compact('customer', 'data', 'grandTotal'));
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
