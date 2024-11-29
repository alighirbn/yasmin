<?php

namespace App\Observers;

use App\Models\Customer\Customer;
use App\Models\ModelHistory;
use Illuminate\Support\Facades\Log;

class CustomerObserver
{
    /**
     * Handle the Customer "created" event.
     *
     * @param  \App\Models\Customer\Customer  $customer
     * @return void
     */
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
    }

    /**
     * Handle the Customer "updated" event.
     *
     * @param  \App\Models\Customer\Customer  $customer
     * @return void
     */
    public function updated(Customer $customer)
    {
        // Get the new data (only changed fields)
        $newData = $customer->getDirty();

        // Get the old data (only for the changed fields)
        $oldData = [];
        $note = null; // Initialize the note

        foreach ($newData as $key => $newValue) {
            // Get the original value of the changed field
            $oldData[$key] = $customer->getOriginal($key);

            // Check if 'customer_full_name' has changed
            if ($key === 'customer_full_name') {
                // Set the note to include both old and new full names
                $note = 'القديم: ' . $oldData[$key] . ' | الجديد: ' . $newValue;
            }
        }

        // If 'customer_full_name' hasn't changed, fall back to just the current customer's name
        if (!$note && isset($newData['customer_full_name'])) {
            $note = $newData['customer_full_name'];
        }

        // Store changes in the ModelHistory table
        ModelHistory::create([
            'model_type' => Customer::class,
            'model_id' => $customer->id,
            'action' => 'edit',
            'old_data' => $oldData,  // Store only changed old data
            'new_data' => $newData,  // Store only changed new data
            'note' => $note,  // Store both old and new customer name if changed
            'user_id' => auth()->id(),
        ]);
    }



    /**
     * Handle the Customer "deleted" event.
     *
     * @param  \App\Models\Customer\Customer  $customer
     * @return void
     */
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
    }
}
