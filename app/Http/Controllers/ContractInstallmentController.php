<?php

namespace App\Http\Controllers;

use App\Models\Contract\Contract;
use App\Models\Contract\Contract_Installment;
use App\Models\Contract\Installment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ContractInstallmentController extends Controller
{
    /**
     * Show the form for editing a specific installment.
     */
    public function edit(string $url_address)
    {
        $installment = Contract_Installment::with(['contract', 'installment'])
            ->where('url_address', $url_address)
            ->firstOrFail();

        // Check if installment has payments - prevent editing if paid
        if ($installment->isFullyPaid()) {
            return redirect()->back()
                ->with('error', 'لا يمكن تعديل قسط مدفوع بالكامل');
        }

        return view('contract.installment.edit', compact('installment'));
    }

    /**
     * Update the specified installment.
     */
    public function update(Request $request, string $url_address)
    {
        $installment = Contract_Installment::where('url_address', $url_address)->firstOrFail();

        // Validate the request
        $validated = $request->validate([
            'installment_amount' => [
                'required',
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) use ($installment) {
                    // Ensure new amount is not less than paid amount
                    if ($value < $installment->paid_amount) {
                        $fail('مبلغ القسط لا يمكن أن يكون أقل من المبلغ المدفوع (' . number_format($installment->paid_amount, 0) . ' IQD)');
                    }
                }
            ],
            'installment_date' => [
                'required',
                'date',
                function ($attribute, $value, $fail) use ($installment) {
                    // Optionally prevent changing date if partially paid
                    if ($installment->isPartiallyPaid()) {
                        $originalDate = Carbon::parse($installment->installment_date);
                        $newDate = Carbon::parse($value);

                        // Allow only small date adjustments (e.g., within 30 days)
                        if (abs($originalDate->diffInDays($newDate)) > 30) {
                            $fail('لا يمكن تغيير تاريخ القسط المدفوع جزئياً بأكثر من 30 يوماً');
                        }
                    }
                }
            ],
            'user_id_update' => 'nullable|exists:users,id',
        ]);

        DB::beginTransaction();

        try {
            // Update the installment
            $installment->update([
                'installment_amount' => $validated['installment_amount'],
                'installment_date' => $validated['installment_date'],
                'user_id_update' => auth()->id(),
            ]);

            // Optionally: Validate contract total still matches
            $this->validateContractTotal($installment->contract);

            DB::commit();

            return redirect()->route('contract.show', $installment->contract->url_address)
                ->with('success', 'تم تعديل القسط بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء تعديل القسط: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing all installments of a contract.
     */
    public function editBulk(string $contract_url_address)
    {
        $contract = Contract::with(['contract_installments.installment', 'contract_installments.payment'])
            ->where('url_address', $contract_url_address)
            ->firstOrFail();

        // Get all installments ordered by sequence
        $installments = $contract->contract_installments()
            ->with(['installment', 'payment'])
            ->orderBy('sequence_number')
            ->orderBy('installment_date')
            ->get();

        return view('contract.installment.edit_bulk', compact('contract', 'installments'));
    }

    /**
     * Update multiple installments at once.
     * Supports: amount/date changes, reordering, type changes, deletions, and adding new installments
     */
    public function updateBulk(Request $request, string $contract_url_address)
    {
        $contract = Contract::where('url_address', $contract_url_address)->firstOrFail();

        $validated = $request->validate([
            'installments' => 'required|array',
            'installments.*.id' => 'required|exists:contract_installments,id',
            'installments.*.installment_id' => 'required|exists:installments,id',
            'installments.*.installment_amount' => 'required|numeric|min:0',
            'installments.*.installment_date' => 'required|date',
            'installments.*.sequence_number' => 'required|integer|min:1',
            'deleted_installments' => 'nullable|json',
            // Validation for new installments
            'new_installments' => 'nullable|array',
            'new_installments.*.temp_id' => 'nullable|string',
            'new_installments.*.installment_id' => 'required|exists:installments,id',
            'new_installments.*.installment_amount' => 'required|numeric|min:0',
            'new_installments.*.installment_date' => 'required|date',
            'new_installments.*.sequence_number' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();

        try {
            // Handle deletions first
            $deletedInstallments = [];
            if (!empty($validated['deleted_installments'])) {
                $deletedInstallments = json_decode($validated['deleted_installments'], true);

                foreach ($deletedInstallments as $deletedId) {
                    $installment = Contract_Installment::find($deletedId);

                    if ($installment && $installment->contract_id === $contract->id) {
                        // Verify installment can be deleted
                        if ($installment->paid_amount > 0 || $installment->payment) {
                            throw new \Exception('لا يمكن حذف قسط مدفوع أو له دفعات مرتبطة');
                        }

                        $installment->delete();
                    }
                }
            }

            // Update existing installments
            foreach ($validated['installments'] as $data) {
                $installment = Contract_Installment::findOrFail($data['id']);

                // Validate installment belongs to this contract
                if ($installment->contract_id !== $contract->id) {
                    throw new \Exception('القسط لا ينتمي لهذا العقد');
                }

                // Validate amount is not below paid amount
                if ($data['installment_amount'] < $installment->paid_amount) {
                    throw new \Exception(
                        'مبلغ القسط رقم ' . $installment->sequence_number .
                            ' لا يمكن أن يكون أقل من المبلغ المدفوع (' .
                            number_format($installment->paid_amount, 0) . ' IQD)'
                    );
                }

                // Validate installment type belongs to the same payment method
                $newInstallmentType = \App\Models\Contract\Installment::findOrFail($data['installment_id']);
                if ($newInstallmentType->payment_method_id !== $contract->contract_payment_method_id) {
                    throw new \Exception(
                        'نوع القسط المختار لا يتوافق مع طريقة الدفع الخاصة بالعقد'
                    );
                }

                // Update the installment with all new data
                $installment->update([
                    'installment_id' => $data['installment_id'],
                    'installment_amount' => $data['installment_amount'],
                    'installment_date' => $data['installment_date'],
                    'sequence_number' => $data['sequence_number'],
                    'user_id_update' => auth()->id(),
                ]);
            }

            // Handle new installments
            $newInstallmentsCount = 0;
            if (!empty($validated['new_installments'])) {
                foreach ($validated['new_installments'] as $newData) {
                    // Validate installment type belongs to the same payment method
                    $installmentType = \App\Models\Contract\Installment::findOrFail($newData['installment_id']);
                    if ($installmentType->payment_method_id !== $contract->contract_payment_method_id) {
                        throw new \Exception(
                            'نوع القسط الجديد لا يتوافق مع طريقة الدفع الخاصة بالعقد'
                        );
                    }

                    // Create new installment
                    Contract_Installment::create([
                        'url_address' => $this->random_string(60),
                        'installment_amount' => $newData['installment_amount'],
                        'installment_date' => $newData['installment_date'],
                        'paid_amount' => 0,
                        'contract_id' => $contract->id,
                        'installment_id' => $newData['installment_id'],
                        'sequence_number' => $newData['sequence_number'],
                        'user_id_create' => auth()->id(),
                    ]);

                    $newInstallmentsCount++;
                }
            }

            // Ensure at least one installment remains
            $totalInstallments = $contract->contract_installments()->count();
            if ($totalInstallments === 0) {
                throw new \Exception('لا يمكن حذف جميع الأقساط. يجب أن يبقى قسط واحد على الأقل.');
            }

            // Validate contract total
            $this->validateContractTotal($contract);

            DB::commit();

            $message = 'تم تعديل الأقساط بنجاح';
            $details = [];

            if (count($deletedInstallments) > 0) {
                $details[] = 'تم حذف ' . count($deletedInstallments) . ' قسط';
            }

            if ($newInstallmentsCount > 0) {
                $details[] = 'تمت إضافة ' . $newInstallmentsCount . ' قسط جديد';
            }

            if (!empty($details)) {
                $message .= ' (' . implode('، ', $details) . ')';
            }

            return redirect()->route('contract.show', $contract->url_address)
                ->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء تعديل الأقساط: ' . $e->getMessage());
        }
    }

    /**
     * Delete a specific installment (only if unpaid and not the last one).
     */
    public function destroy(string $url_address)
    {
        $installment = Contract_Installment::where('url_address', $url_address)->firstOrFail();
        $contract = $installment->contract;

        // Check if installment has any payments
        if ($installment->paid_amount > 0 || $installment->payment) {
            return redirect()->back()
                ->with('error', 'لا يمكن حذف قسط مدفوع أو له دفعات مرتبطة');
        }

        // Ensure at least one installment remains
        if ($contract->contract_installments()->count() <= 1) {
            return redirect()->back()
                ->with('error', 'لا يمكن حذف آخر قسط في العقد');
        }

        DB::beginTransaction();

        try {
            $installment->delete();

            // Resequence remaining installments
            $this->resequenceInstallments($contract);

            DB::commit();

            return redirect()->route('contract.show', $contract->url_address)
                ->with('success', 'تم حذف القسط بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'حدث خطأ أثناء حذف القسط: ' . $e->getMessage());
        }
    }

    /**
     * Show form to add a new installment to a contract.
     */
    public function create(string $contract_url_address)
    {
        $contract = Contract::where('url_address', $contract_url_address)->firstOrFail();

        // Get installment types for the contract's payment method
        $installmentTypes = Installment::where('payment_method_id', $contract->contract_payment_method_id)
            ->get();

        return view('contract.installment.create', compact('contract', 'installmentTypes'));
    }

    /**
     * Store a new installment.
     */
    public function store(Request $request, string $contract_url_address)
    {
        $contract = Contract::where('url_address', $contract_url_address)->firstOrFail();

        $validated = $request->validate([
            'installment_id' => 'required|exists:installments,id',
            'installment_amount' => 'required|numeric|min:0',
            'installment_date' => 'required|date',
            'sequence_number' => 'nullable|integer|min:1',
        ]);

        DB::beginTransaction();

        try {
            // Get the next sequence number if not provided
            $sequenceNumber = $validated['sequence_number'] ??
                ($contract->contract_installments()->max('sequence_number') + 1);

            // Create the new installment
            Contract_Installment::create([
                'url_address' => $this->random_string(60),
                'installment_amount' => $validated['installment_amount'],
                'installment_date' => $validated['installment_date'],
                'paid_amount' => 0,
                'contract_id' => $contract->id,
                'installment_id' => $validated['installment_id'],
                'sequence_number' => $sequenceNumber,
                'user_id_create' => auth()->id(),
            ]);

            // Resequence if needed
            $this->resequenceInstallments($contract);

            DB::commit();

            return redirect()->route('contract.show', $contract->url_address)
                ->with('success', 'تمت إضافة القسط بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء إضافة القسط: ' . $e->getMessage());
        }
    }

    /**
     * Validate that contract total matches sum of installments.
     */
    private function validateContractTotal(Contract $contract)
    {
        $contract->refresh();
        $installmentsTotal = $contract->contract_installments->sum('installment_amount');
        $contractAmount = $contract->contract_amount;

        $difference = abs($contractAmount - $installmentsTotal);

        // Allow small rounding difference (1 IQD)
        if ($difference > 1) {
            throw new \Exception(
                'مجموع الأقساط (' . number_format($installmentsTotal, 0) . ' IQD) ' .
                    'لا يتطابق مع مبلغ العقد (' . number_format($contractAmount, 0) . ' IQD). ' .
                    'الفرق: ' . number_format($difference, 0) . ' IQD'
            );
        }
    }

    /**
     * Resequence installments after deletion or addition.
     */
    private function resequenceInstallments(Contract $contract)
    {
        $installments = $contract->contract_installments()
            ->orderBy('installment_date')
            ->orderBy('id')
            ->get();

        $sequence = 1;
        foreach ($installments as $installment) {
            $installment->update(['sequence_number' => $sequence]);
            $sequence++;
        }
    }

    /**
     * Generate random string for URL address.
     */
    private function random_string($length)
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
