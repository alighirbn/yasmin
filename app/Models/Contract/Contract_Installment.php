<?php

namespace App\Models\Contract;

use App\Models\Payment\Payment;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract_Installment extends Model
{
    use HasFactory;
    protected $table = 'contract_installments';

    public function payment()
    {
        return $this->hasOne(Payment::class, 'contract_installment_id', 'id');
    }

    public function isDue($installmentDate)
    {
        return Carbon::parse($installmentDate)->isPast();
    }

    public function contract()
    {
        return $this->belongsTo(Contract::class, 'contract_id', 'id');
    }

    public function installment()
    {
        return $this->belongsTo(Installment::class, 'installment_id', 'id');
    }

    protected $fillable = [
        'url_address',
        'installment_amount',
        'installment_date',
        'paid_amount', // 🔹 Track partial payments
        'contract_id',
        'installment_id',
        'user_id_create',
        'user_id_update',
        'sequence_number', // 🔹 Added here
    ];

    /**
     * Check if installment is fully paid
     */
    public function isFullyPaid()
    {
        return $this->paid_amount >= $this->installment_amount;
    }

    /**
     * Get remaining amount to be paid
     */
    public function getRemainingAmount()
    {
        return max(0, $this->installment_amount - $this->paid_amount);
    }

    /**
     * Get payment progress percentage
     */
    public function getPaymentProgress()
    {
        if ($this->installment_amount == 0) {
            return 0;
        }
        return min(100, ($this->paid_amount / $this->installment_amount) * 100);
    }

    /**
     * Add a payment amount to this installment
     */
    public function addPayment($amount)
    {
        $this->paid_amount += $amount;

        // Auto-update paid status when fully paid
        if ($this->isFullyPaid()) {
            $this->paid = true;
        }

        $this->save();
    }

    /**
     * Remove a payment amount from this installment
     */
    public function removePayment($amount)
    {
        $this->paid_amount = max(0, $this->paid_amount - $amount);

        // Update paid status
        $this->paid = $this->isFullyPaid();

        $this->save();
    }
}
