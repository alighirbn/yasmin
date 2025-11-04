<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'id',
            'url_address' => ['required'],
            'user_id_create' => ['Numeric'],
            'user_id_update' => ['Numeric'],

            //foreign id and reference
            'payment_contract_id' => ['required'],
            'contract_installment_id' => ['nullable', 'sometimes'],

            //normal fields
            'payment_date' => ['required', 'date_format:Y-m-d'],
            'payment_amount' => [
                'required',
                'numeric',
                function ($attribute, $value, $fail) {
                    // Value must not be zero
                    if ($value == 0) {
                        $fail('مبلغ الدفعة لا يمكن أن يكون صفراً.');
                        return;
                    }

                    // Validate against installment if linked
                    if ($this->contract_installment_id) {
                        $installment = \App\Models\Contract\Contract_Installment::find($this->contract_installment_id);

                        if ($installment) {
                            // For positive payments (receiving money)
                            if ($value > 0) {
                                $remaining = $installment->getRemainingAmount();

                                // For updates, add back the current payment amount to remaining
                                if ($this->route('url_address')) {
                                    $currentPayment = \App\Models\Payment\Payment::where('url_address', $this->route('url_address'))->first();
                                    if ($currentPayment && $currentPayment->contract_installment_id == $this->contract_installment_id) {
                                        $remaining += $currentPayment->payment_amount;
                                    }
                                }

                                if ($value > $remaining) {
                                    $fail('مبلغ الدفعة (' . number_format($value) . ') يتجاوز المبلغ المتبقي للقسط (' . number_format($remaining) . ')');
                                }
                            }
                            // For negative payments (refunds/withdrawals)
                            elseif ($value < 0) {
                                $paidAmount = $installment->paid_amount;

                                // For updates, subtract the current payment amount from paid amount
                                if ($this->route('url_address')) {
                                    $currentPayment = \App\Models\Payment\Payment::where('url_address', $this->route('url_address'))->first();
                                    if ($currentPayment && $currentPayment->contract_installment_id == $this->contract_installment_id) {
                                        $paidAmount -= $currentPayment->payment_amount;
                                    }
                                }

                                $refundAmount = abs($value);
                                if ($refundAmount > $paidAmount) {
                                    $fail('مبلغ السحب (' . number_format($refundAmount) . ') يتجاوز المبلغ المدفوع للقسط (' . number_format($paidAmount) . ')');
                                }
                            }
                        }
                    }
                }
            ],
            'payment_note' => ['required', 'max:200'],
        ];
    }

    protected function prepareForValidation()
    {
        //add url address
        $this->mergeIfMissing(['url_address' => $this->get_random_string(60)]);

        //add user_id base on route
        if (request()->routeIs('payment.store')) {
            $this->mergeIfMissing(['user_id_create' => auth()->user()->id]);
        } elseif (request()->routeIs('payment.update')) {
            $this->mergeIfMissing(['user_id_update' =>  auth()->user()->id]);
        }

        // Convert empty contract_installment_id to null
        if ($this->has('contract_installment_id') && $this->contract_installment_id === '') {
            $this->merge(['contract_installment_id' => null]);
        }
    }

    function get_random_string($length)
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
