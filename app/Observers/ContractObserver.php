<?php

namespace App\Observers;

use App\Models\Contract\Contract;
use App\Models\Customer\Customer;
use App\Models\Building\Building;
use App\Models\ModelHistory;

class ContractObserver
{
    public function created(Contract $contract)
    {
        // Format contract_amount
        if ($contract->contract_amount !== null) {
            $contract->contract_amount = number_format($contract->contract_amount, 0, '.', ',');
        }

        // Replace contract_customer_id with customer_full_name for history logging
        if ($contract->contract_customer_id) {
            $customer = Customer::find($contract->contract_customer_id);
            if ($customer) {
                $contract->contract_customer_full_name = $customer->customer_full_name;
            }
        }

        // Replace contract_building_id with building_number for history logging
        if ($contract->contract_building_id) {
            $building = Building::find($contract->contract_building_id);
            if ($building) {
                $contract->contract_building_number = $building->building_number;
            }
        }

        // Log the creation in ModelHistory
        ModelHistory::create([
            'model_type' => Contract::class,
            'model_id' => $contract->id,
            'action' => 'add',
            'new_data' => $contract->getAttributes(),
            'user_id' => auth()->id(),
        ]);
    }

    public function updated(Contract $contract)
    {
        // Format contract_amount
        if ($contract->contract_amount !== null) {
            $contract->contract_amount = number_format($contract->contract_amount, 0, '.', ',');
        }

        // Initialize old and new data arrays
        $oldData = [];
        $newData = [];

        // Loop through all dirty attributes to track changes
        foreach ($contract->getDirty() as $key => $newValue) {
            $oldValue = $contract->getOriginal($key); // Get the old value from original data

            // Track changes for customer and building fields
            if ($key === 'contract_customer_id') {
                // Find old and new customer
                $oldCustomer = Customer::find($oldValue);
                $newCustomer = Customer::find($newValue);

                $oldData['contract_customer_full_name'] = $oldCustomer ? $oldCustomer->customer_full_name : null;
                $newData['contract_customer_full_name'] = $newCustomer ? $newCustomer->customer_full_name : null;
            } elseif ($key === 'contract_building_id') {
                // Find old and new building
                $oldBuilding = Building::find($oldValue);
                $newBuilding = Building::find($newValue);

                $oldData['contract_building_number'] = $oldBuilding ? $oldBuilding->building_number : null;
                $newData['contract_building_number'] = $newBuilding ? $newBuilding->building_number : null;
            } else {
                // For all other fields, add the old and new values
                $oldData[$key] = $oldValue;
                $newData[$key] = $newValue;
            }
        }

        // Log the update in ModelHistory
        ModelHistory::create([
            'model_type' => Contract::class,
            'model_id' => $contract->id,
            'action' => 'edit',
            'old_data' => json_encode($oldData, JSON_UNESCAPED_UNICODE), // Store old attributes
            'new_data' => json_encode($newData, JSON_UNESCAPED_UNICODE), // Store new attributes
            'user_id' => auth()->id(),
        ]);
    }

    public function deleted(Contract $contract)
    {
        // Get the original contract data
        $oldData = $contract->getAttributes();

        // Format contract_amount
        if (isset($oldData['contract_amount'])) {
            $oldData['contract_amount'] = number_format($oldData['contract_amount'], 0, '.', ',');
        }

        // Replace contract_customer_id with customer_full_name for history logging
        if (isset($oldData['contract_customer_id'])) {
            $customer = Customer::find($oldData['contract_customer_id']);
            if ($customer) {
                $oldData['contract_customer_full_name'] = $customer->customer_full_name;
            }
            unset($oldData['contract_customer_id']); // Remove the old customer ID
        }

        // Replace contract_building_id with building_number for history logging
        if (isset($oldData['contract_building_id'])) {
            $building = Building::find($oldData['contract_building_id']);
            if ($building) {
                $oldData['contract_building_number'] = $building->building_number;
            }
            unset($oldData['contract_building_id']); // Remove the old building ID
        }

        // Log the deletion in ModelHistory
        ModelHistory::create([
            'model_type' => Contract::class,
            'model_id' => $contract->id,
            'action' => 'delete',
            'old_data' => $oldData, // Store full attributes with customer full name and building number
            'user_id' => auth()->id(),
        ]);
    }
}
