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

    protected $fillable = [
        'url_address',
        'installment_amount',
        'installment_date',
        'paid_amount',
        'contract_id',
        'installment_id',
        'user_id_create',
        'user_id_update',
        'sequence_number',
    ];

    protected $casts = [
        'installment_amount' => 'float',
        'paid_amount' => 'float',
        'installment_date' => 'date',
        'sequence_number' => 'integer',
    ];

    protected $attributes = [
        'paid_amount' => 0,
    ];

    // ============================================================================
    // RELATIONSHIPS
    // ============================================================================

    /**
     * Get the payment associated with this installment
     * Note: This assumes one payment per installment. If you allow multiple
     * payments per installment, change to hasMany()
     */
    public function payment()
    {
        return $this->hasOne(Payment::class, 'contract_installment_id', 'id');
    }

    /**
     * Get all payments for this installment (if supporting multiple payments)
     */
    public function payments()
    {
        return $this->hasMany(Payment::class, 'contract_installment_id', 'id');
    }

    /**
     * Get the contract this installment belongs to
     */
    public function contract()
    {
        return $this->belongsTo(Contract::class, 'contract_id', 'id');
    }

    /**
     * Get the installment type (down payment, monthly, key, etc.)
     */
    public function installment()
    {
        return $this->belongsTo(Installment::class, 'installment_id', 'id');
    }

    // ============================================================================
    // QUERY SCOPES
    // ============================================================================

    /**
     * Scope to get only paid installments (with approved payments)
     */
    public function scopePaid($query)
    {
        return $query->whereHas('payment', function ($q) {
            $q->where('approved', true);
        });
    }

    /**
     * Scope to get unpaid installments
     */
    public function scopeUnpaid($query)
    {
        return $query->whereDoesntHave('payment', function ($q) {
            $q->where('approved', true);
        });
    }

    /**
     * Scope to get overdue installments
     */
    public function scopeOverdue($query)
    {
        return $query->unpaid()
            ->where('installment_date', '<', now());
    }

    /**
     * Scope to get upcoming installments (due in X days)
     */
    public function scopeUpcoming($query, int $days = 30)
    {
        return $query->unpaid()
            ->where('installment_date', '>=', now())
            ->where('installment_date', '<=', now()->addDays($days));
    }

    /**
     * Scope to filter by installment type
     */
    public function scopeOfType($query, $installmentId)
    {
        return $query->where('installment_id', $installmentId);
    }

    /**
     * Scope to order by sequence
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sequence_number')->orderBy('installment_date');
    }

    // ============================================================================
    // PAYMENT STATUS METHODS
    // ============================================================================

    /**
     * Check if installment is fully paid
     */
    public function isFullyPaid(): bool
    {
        // Check if has approved payment
        return $this->payment && $this->payment->approved === true;
    }

    /**
     * Check if installment is partially paid
     */
    public function isPartiallyPaid(): bool
    {
        return $this->paid_amount > 0 && !$this->isFullyPaid();
    }

    /**
     * Check if installment is unpaid
     */
    public function isUnpaid(): bool
    {
        return !$this->payment || $this->payment->approved !== true;
    }

    /**
     * Check if installment is overdue
     */
    public function isOverdue(): bool
    {
        return $this->isUnpaid() && $this->isDue();
    }

    /**
     * Check if installment is due (past due date)
     */
    public function isDue(): bool
    {
        return Carbon::parse($this->installment_date)->isPast();
    }

    /**
     * Get days until/since due date
     * Negative = overdue, Positive = upcoming
     */
    public function getDaysUntilDue(): int
    {
        return now()->diffInDays(Carbon::parse($this->installment_date), false);
    }

    // ============================================================================
    // AMOUNT CALCULATION METHODS
    // ============================================================================

    /**
     * Get remaining amount to be paid
     */
    public function getRemainingAmount(): float
    {
        if ($this->isFullyPaid()) {
            return 0;
        }

        return max(0, $this->installment_amount - $this->paid_amount);
    }

    /**
     * Get payment progress percentage (0-100)
     */
    public function getPaymentProgress(): float
    {
        if ($this->installment_amount == 0) {
            return 0;
        }

        $progress = ($this->paid_amount / $this->installment_amount) * 100;
        return min(100, max(0, $progress));
    }

    /**
     * Get the paid amount (from approved payment or paid_amount field)
     */
    public function getPaidAmount(): float
    {
        if ($this->payment && $this->payment->approved) {
            return (float) $this->payment->payment_amount;
        }

        return (float) $this->paid_amount;
    }

    // ============================================================================
    // PAYMENT OPERATIONS
    // ============================================================================

    /**
     * Add a payment amount to this installment
     * 
     * @param float $amount
     * @param bool $autoSave
     * @return bool Whether installment is now fully paid
     */
    public function addPayment(float $amount, bool $autoSave = true): bool
    {
        if ($amount <= 0) {
            throw new \InvalidArgumentException('Payment amount must be positive');
        }

        $this->paid_amount = min(
            $this->installment_amount,
            $this->paid_amount + $amount
        );

        if ($autoSave) {
            $this->save();
        }

        return $this->isFullyPaid();
    }

    /**
     * Remove a payment amount from this installment
     * 
     * @param float $amount
     * @param bool $autoSave
     * @return void
     */
    public function removePayment(float $amount, bool $autoSave = true): void
    {
        if ($amount <= 0) {
            throw new \InvalidArgumentException('Payment amount must be positive');
        }

        $this->paid_amount = max(0, $this->paid_amount - $amount);

        if ($autoSave) {
            $this->save();
        }
    }

    /**
     * Reset payment status (mark as unpaid)
     */
    public function resetPayment(): void
    {
        $this->paid_amount = 0;
        $this->save();
    }

    /**
     * Mark as fully paid
     */
    public function markAsPaid(float $amount = null): void
    {
        $this->paid_amount = $amount ?? $this->installment_amount;
        $this->save();
    }

    // ============================================================================
    // ATTRIBUTE ACCESSORS
    // ============================================================================

    /**
     * Get formatted installment amount
     */
    public function getFormattedAmountAttribute(): string
    {
        return number_format($this->installment_amount, 2);
    }

    /**
     * Get formatted paid amount
     */
    public function getFormattedPaidAmountAttribute(): string
    {
        return number_format($this->getPaidAmount(), 2);
    }

    /**
     * Get formatted remaining amount
     */
    public function getFormattedRemainingAmountAttribute(): string
    {
        return number_format($this->getRemainingAmount(), 2);
    }

    /**
     * Get status badge text
     */
    public function getStatusAttribute(): string
    {
        if ($this->isFullyPaid()) {
            return 'paid';
        }

        if ($this->isOverdue()) {
            return 'overdue';
        }

        if ($this->isPartiallyPaid()) {
            return 'partial';
        }

        if ($this->isDue()) {
            return 'due';
        }

        return 'upcoming';
    }

    /**
     * Get status in Arabic
     */
    public function getStatusArabicAttribute(): string
    {
        return match ($this->status) {
            'paid' => 'مدفوع',
            'overdue' => 'متأخر',
            'partial' => 'دفع جزئي',
            'due' => 'مستحق',
            'upcoming' => 'قادم',
            default => 'غير معروف',
        };
    }

    // ============================================================================
    // UTILITY METHODS
    // ============================================================================

    /**
     * Check if this is a down payment installment
     */
    public function isDownPayment(): bool
    {
        if (!$this->installment) {
            return false;
        }

        return in_array($this->installment->installment_name, [
            'دفعة مقدمة',
            'دفعة مقدمة نقداً',
            'دفعة مقدمة مؤجلة'
        ]);
    }

    /**
     * Check if this is a monthly installment
     */
    public function isMonthlyPayment(): bool
    {
        if (!$this->installment) {
            return false;
        }

        return in_array($this->installment->installment_name, [
            'دفعة شهرية',
            'قسط مرن'
        ]);
    }

    /**
     * Check if this is a key payment installment
     */
    public function isKeyPayment(): bool
    {
        if (!$this->installment) {
            return false;
        }

        return in_array($this->installment->installment_name, [
            'دفعة المفتاح',
            'دفعة مفتاح'
        ]);
    }

    /**
     * Get the next installment in sequence
     */
    public function nextInstallment()
    {
        return static::where('contract_id', $this->contract_id)
            ->where('sequence_number', '>', $this->sequence_number)
            ->ordered()
            ->first();
    }

    /**
     * Get the previous installment in sequence
     */
    public function previousInstallment()
    {
        return static::where('contract_id', $this->contract_id)
            ->where('sequence_number', '<', $this->sequence_number)
            ->ordered()
            ->orderByDesc('sequence_number')
            ->first();
    }
}
