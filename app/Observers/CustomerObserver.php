<?php

namespace App\Observers;

use App\Models\Customer\Customer;
use App\Models\ModelHistory;
use App\Mail\CustomerActionNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Exception\TransportException;

class CustomerObserver
{
    public function created(Customer $customer)
    {
        ModelHistory::create([
            'model_type' => Customer::class,
            'model_id' => $customer->id,
            'action' => 'add',
            'new_data' => $customer->getAttributes(),
            'note' => $customer->customer_full_name,
            'user_id' => auth()->id(),
        ]);

        /* 
        try {
            Mail::to('alighirbn@gmail.com')->send(new CustomerActionNotification($customer, 'created'));
        } catch (TransportException $e) {
            // Handle the transport exception when mail can't be sent due to connectivity issues
            Log::error('Email could not be sent: ' . $e->getMessage());
            // Optionally, notify the user or retry later
        } */
    }

    public function updated(Customer $customer)
    {
        $newData = $customer->getDirty();
        $oldData = [];
        $note = null;

        foreach ($newData as $key => $newValue) {
            $oldData[$key] = $customer->getOriginal($key);

            if ($key === 'customer_full_name') {
                $note = 'القديم: ' . $oldData[$key] . ' | الجديد: ' . $newValue;
            }
        }

        if (!$note && isset($newData['customer_full_name'])) {
            $note = $newData['customer_full_name'];
        }

        ModelHistory::create([
            'model_type' => Customer::class,
            'model_id' => $customer->id,
            'action' => 'edit',
            'old_data' => $oldData,
            'new_data' => $newData,
            'note' => $note,
            'user_id' => auth()->id(),
        ]);
        /*   try {
            Mail::to('alighirbn@gmail.com')->send(new CustomerActionNotification($customer, 'updated', $note));
        } catch (TransportException $e) {
            // Handle the transport exception when mail can't be sent due to connectivity issues
            Log::error('Email could not be sent: ' . $e->getMessage());
            // Optionally, notify the user or retry later
        } */
    }

    public function deleted(Customer $customer)
    {
        ModelHistory::create([
            'model_type' => Customer::class,
            'model_id' => $customer->id,
            'action' => 'delete',
            'old_data' => $customer->getAttributes(),
            'note' => $customer->customer_full_name,
            'user_id' => auth()->id(),
        ]);

        /*  try {
            Mail::to('alighirbn@gmail.com')->send(new CustomerActionNotification($customer, 'deleted'));
        } catch (TransportException $e) {
            // Handle the transport exception when mail can't be sent due to connectivity issues
            Log::error('Email could not be sent: ' . $e->getMessage());
            // Optionally, notify the user or retry later
        } */
    }
}
