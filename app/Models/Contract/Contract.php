<?php

namespace App\Models\Contract;

use App\Models\Building\Building;
use App\Models\Customer\Customer;
use App\Models\Payment\Payment;
use App\Models\Payment\Payment_Method;
use App\Models\Payment\Service;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;
    protected $table = 'contracts';

    public function contract_installments()
    {
        return $this->hasMany(Contract_Installment::class, 'contract_id', 'id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'payment_contract_id', 'id');
    }

    public function transfers()
    {
        return $this->hasMany(Contract_Transfer_History::class, 'contract_id', 'id');
    }

    public function Services()
    {
        return $this->hasMany(Service::class, 'service_contract_id', 'id');
    }

    public function unpaidInstallments()
    {
        return $this->hasMany(Contract_Installment::class, 'contract_id', 'id')
            ->where(function ($query) {
                $query->where('paid', false)
                    ->orWhereRaw('paid_amount < installment_amount');
            });
    }

    public function partiallyPaidInstallments()
    {
        return $this->hasMany(Contract_Installment::class, 'contract_id', 'id')
            ->whereRaw('paid_amount > 0 AND paid_amount < installment_amount');
    }

    public function fullyPaidInstallments()
    {
        return $this->hasMany(Contract_Installment::class, 'contract_id', 'id')
            ->where('paid', true)
            ->whereRaw('paid_amount >= installment_amount');
    }

    public function hasPayments()
    {
        return $this->payments()->exists();
    }

    public function building()
    {
        return $this->belongsTo(Building::class, 'contract_building_id', 'id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'contract_customer_id', 'id');
    }

    public function payment_method()
    {
        return $this->belongsTo(Payment_Method::class, 'contract_payment_method_id', 'id');
    }

    public function images()
    {
        return $this->hasMany(ContractArchive::class, 'contract_id', 'id');
    }

    public function user_create()
    {
        return $this->belongsTo(User::class, 'user_id_create', 'id');
    }

    public function user_update()
    {
        return $this->belongsTo(User::class, 'user_id_update', 'id');
    }

    protected $fillable = [
        'url_address',
        'contract_date',
        'contract_amount',
        'discount',
        'contract_note',
        'stage',
        'temporary_at',
        'accepted_at',
        'authenticated_at',
        'terminated_at', // Added for termination timestamp
        'contract_customer_id',
        'contract_building_id',
        'contract_payment_method_id',
        'user_id_create',
        'user_id_update',
    ];

    const STAGES = [
        'temporary',
        'accepted',
        'authenticated',
        'terminated', // Added terminated stage
    ];

    public function getCurrentStageIndex()
    {
        return array_search($this->stage, self::STAGES);
    }

    // Transition to accepted stage
    public function accept()
    {
        $this->stage = 'accepted';
        $this->accepted_at = now();
        $this->save();
    }

    // Transition to final contract stage
    public function authenticat()
    {
        $this->stage = 'authenticated';
        $this->authenticated_at = now();
        $this->save();
    }

    // Transition to temporary stage
    public function temporary()
    {
        $this->stage = 'temporary';
        $this->accepted_at = now();
        $this->save();
    }

    // Transition to terminated stage
    public function terminate()
    {
        $this->stage = 'terminated';
        $this->terminated_at = now();
        $this->save();
    }
}
