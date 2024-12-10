<?php

namespace App\Observers;

use App\Mail\SendContractEmail;
use App\Models\Contract\Contract;
use App\Models\ModelHistory;
use App\Models\Payment\Payment;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Exception\TransportException;

class PaymentObserver
{
    public function created(Payment $payment)
    {


        // Replace contract_customer_id with customer_full_name for history logging
        if ($payment->payment_contract_id) {
            $payment_contract_id = Contract::find($payment->payment_contract_id);
            if ($payment_contract_id) {
                $payment->contract_building_number = $payment_contract_id->building->building_number;
                $payment->customer_full_name = $payment_contract_id->customer->customer_full_name;
            }
        }

        // Log the creation in ModelHistory
        ModelHistory::create([
            'model_type' => Payment::class,
            'model_id' => $payment->id,
            'action' => 'add',
            'new_data' => $payment->getAttributes(),
            'note' => $payment->contract->building->building_number . ' ' . $payment->contract->customer->customer_full_name,
            'user_id' => auth()->id(),
        ]);
        /*   $changeData = [
            'type' => 'Created',
            'contract_data' => $payment->getAttributes(),
        ];

        try {
            Mail::to('alighirbn@gmail.com')->send(new SendContractEmail($contract, $changeData));
        } catch (TransportException $e) {
            Log::error('Email could not be sent: ' . $e->getMessage());
        } */
    }

    public function updated(Payment $payment)
    {


        // Initialize old and new data arrays
        $oldData = [];
        $newData = [];

        // Loop through all dirty attributes to track changes
        foreach ($payment->getDirty() as $key => $newValue) {
            $oldValue = $payment->getOriginal($key); // Get the old value from original data

            // Track changes for customer and building fields
            if ($key === 'payment_contract_id') {

                $oldCustomer = Contract::find($oldValue);
                $newCustomer = Contract::find($newValue);

                $oldData['contract_customer_full_name'] = $oldCustomer ? $oldCustomer->customer->customer_full_name : null;
                $newData['contract_customer_full_name'] = $newCustomer ? $newCustomer->customer->customer_full_name : null;

                $oldData['contract_building_number'] = $oldCustomer ? $oldCustomer->building->building_number : null;
                $newData['contract_building_number'] = $newCustomer ? $newCustomer->building->building_number : null;
            } else {
                // For all other fields, add the old and new values
                $oldData[$key] = $oldValue;
                $newData[$key] = $newValue;
            }
        }

        // Log the update in ModelHistory
        ModelHistory::create([
            'model_type' => Payment::class,
            'model_id' => $payment->id,
            'action' => 'edit',
            'old_data' => json_encode($oldData, JSON_UNESCAPED_UNICODE), // Store old attributes
            'new_data' => json_encode($newData, JSON_UNESCAPED_UNICODE), // Store new attributes
            'note' => $payment->contract->building->building_number . ' ' . $payment->contract->customer->customer_full_name,
            'user_id' => auth()->id(),
        ]);
        /*     // Prepare the change data
        $changeData = [
            'type' => 'Updated',
            'old_data' => $oldData,
            'new_data' => $newData,
        ];

        try {
            Mail::to('alighirbn@gmail.com')->send(new SendContractEmail($contract, $changeData));
        } catch (TransportException $e) {
            Log::error('Email could not be sent: ' . $e->getMessage());
        } */
    }

    public function deleted(Payment $payment)
    {
        // Get the original contract data
        $oldData = $payment->getAttributes();

        // Format payment_amount
        if (isset($oldData['payment_amount'])) {
            $oldData['payment_amount'] = number_format($oldData['payment_amount'], 0, '.', ',');
        }

        // Replace contract_customer_id with customer_full_name for history logging
        if (isset($oldData['payment_contract_id'])) {
            $contract = Contract::find($oldData['payment_contract_id']);
            if ($contract) {
                $oldData['contract_building_number'] = $contract->building->building_number;
                $oldData['customer_full_name'] = $contract->customer->customer_full_name;
            }
            unset($oldData['payment_contract_id']); // Remove the old customer ID
        }

        // Log the deletion in ModelHistory
        ModelHistory::create([
            'model_type' => Payment::class,
            'model_id' => $payment->id,
            'action' => 'delete',
            'old_data' => $oldData, // Store full attributes with customer full name and building number
            'note' =>  $payment->contract->building->building_number . ' ' . $payment->contract->customer->customer_full_name,
            'user_id' => auth()->id(),
        ]);
        /*  $changeData = [
            'type' => 'Deleted',
            'contract_data' => $oldData,
        ];

        try {
            Mail::to('alighirbn@gmail.com')->send(new SendContractEmail($contract, $changeData));
        } catch (TransportException $e) {
            Log::error('Email could not be sent: ' . $e->getMessage());
        } */
    }
}
