<?php

namespace App\Services;

use App\Models\Building\Building;
use App\Models\Contract\Contract;
use App\Models\Contract\Installment;
use Carbon\Carbon;

class ContractUpdateService
{
    /**
     * Update all contracts based on building classification price.
     */
    public function updateAllContracts()
    {
        // Fetch all buildings with related contracts and classifications
        $buildings = Building::with(['contract.payments', 'classification'])->get();

        foreach ($buildings as $building) {
            $contract = $building->contract;

            // Skip if the contract has payments or no classification
            if (!$contract || !$building->classification) {
                continue;
            }

            $newContractAmount = $building->calculatePrice();

            // Update the contract amount
            $contract->update(['contract_amount' => $newContractAmount]);

            // Update installments based on the payment method
            $this->updateInstallments($contract, $newContractAmount);
        }
    }

    /**
     * Update installments for the given contract.
     *
     * @param Contract $contract
     * @param float $newContractAmount
     */
    private function updateInstallments(Contract $contract, float $newContractAmount)
    {
        $paymentMethodId = $contract->contract_payment_method_id;

        if ($paymentMethodId == 2) {
            // Payment method 2: 12 installments
            $installments = $contract->contract_installments;
            if ($installments->count() == 12) {
                foreach ($installments as $installment) {
                    $installment->update([
                        'installment_amount' => $installment->installment->installment_percent * $newContractAmount,
                        'installment_date' => Carbon::parse($contract->contract_date)->addMonth($installment->installment->installment_period),
                    ]);
                }
            } else {
                $this->recreateInstallments($contract, $newContractAmount, 2);
            }
        } elseif ($paymentMethodId == 1) {
            // Payment method 1: Single installment
            $installments = $contract->contract_installments;
            if ($installments->count() == 1) {
                $installments->first()->update([
                    'installment_amount' => $newContractAmount,
                    'installment_date' => $contract->contract_date,
                ]);
            } else {
                $this->recreateInstallments($contract, $newContractAmount, 1);
            }
        }
    }

    /**
     * Recreate installments for the given contract.
     *
     * @param Contract $contract
     * @param float $newContractAmount
     * @param int $paymentMethodId
     */
    private function recreateInstallments(Contract $contract, float $newContractAmount, int $paymentMethodId)
    {
        $contract->contract_installments()->delete();

        $installments = Installment::where('payment_method_id', $paymentMethodId)->get();

        foreach ($installments as $installment) {
            $contract->contract_installments()->create([
                'url_address' => $this->random_string(60),
                'installment_amount' => $installment->installment_percent * $newContractAmount,
                'installment_date' => Carbon::parse($contract->contract_date)->addMonth($installment->installment_period),
                'installment_id' => $installment->id,
                'user_id_create' => auth()->id(), // Replace with appropriate user ID
            ]);
        }
    }

    /**
     * Generate a random string.
     *
     * @param int $length
     * @return string
     */
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
}
