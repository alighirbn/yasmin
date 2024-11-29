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
        foreach ($newData as $key => $newValue) {
            // Get the original value of the changed field
            $oldData[$key] = $customer->getOriginal($key);
        }

        // Store changes in the ModelHistory table
        ModelHistory::create([
            'model_type' => Customer::class,
            'model_id' => $customer->id,
            'action' => 'edit',
            'old_data' => $oldData,  // Store only changed old data
            'new_data' => $newData,  // Store only changed new data
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
            'user_id' => auth()->id(),
        ]);
    }
}
