<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class ContractRequest extends FormRequest
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
        $rules = [
            'id' => ['nullable'],
            'url_address' => ['required'],
            'user_id_create' => ['numeric', 'nullable'],
            'user_id_update' => ['numeric', 'nullable'],
            'contract_customer_id' => ['required'],
            'contract_building_id' => ['required'],
            'contract_payment_method_id' => ['required'],
            'contract_date' => ['required', 'date_format:Y-m-d'],
            'contract_amount' => ['required', 'numeric', 'min:0'],
            'contract_note' => ['max:200', 'nullable'],
        ];

        if ($this->input('contract_payment_method_id') == 3) {
            // دفعات متغيرة → الخصم اختياري
            $rules['discount'] = ['nullable', 'numeric', 'min:0', 'max:100'];

            $rules = array_merge($rules, [
                'down_payment_amount' => ['required', 'numeric', 'min:0'],
                'monthly_installment_amount' => ['required', 'numeric', 'min:0'],
                'number_of_months' => ['required', 'integer', 'min:1'],
                'key_payment_amount' => ['required', 'numeric', 'min:0'],

                // تحقق من توازن الدفعات مع العقد (بعد الخصم إن وجد)
                'down_payment_amount' => ['required', 'numeric', function ($attribute, $value, $fail) {
                    $contract_amount = $this->input('contract_amount');



                    $down_payment = $value;
                    $monthly_amount = $this->input('monthly_installment_amount') ?? 0;
                    $months = $this->input('number_of_months') ?? 0;
                    $key_payment = $this->input('key_payment_amount') ?? 0;

                    $total = $down_payment + ($monthly_amount * $months) + $key_payment;

                    if (abs($total - $contract_amount) > 0.01) {
                        $fail('مجموع الدفعة المقدمة، الأقساط الشهرية، ودفعة المفتاح يجب أن يساوي مبلغ العقد بعد الخصم (إن وُجد).');
                    }
                }],
            ]);
        } else {
            // طرق الدفع الأخرى → الخصم إلزامي
            $rules['discount'] = ['required', 'numeric', 'min:0', 'max:100'];
        }

        return $rules;
    }



    protected function prepareForValidation()
    {
        $this->mergeIfMissing(['url_address' => $this->get_random_string(60)]);
        if (request()->routeIs('contract.store')) {
            $this->mergeIfMissing(['user_id_create' => auth()->user()->id]);
        } elseif (request()->routeIs('contract.update')) {
            $this->mergeIfMissing(['user_id_update' => auth()->user()->id]);
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
