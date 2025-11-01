<?php

namespace App\Http\Requests;

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
        // دالة تنظيف للقيم الرقمية مع إزالة الفواصل
        $clean = fn($v) => (float) str_replace(',', '', $v ?? 0);

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

        // =====================================
        // ✅ دفعات متغيرة (Method 3)
        // =====================================
        if ($this->input('contract_payment_method_id') == 3) {
            $rules['discount'] = ['nullable', 'numeric', 'min:0', 'max:100'];

            $rules = array_merge($rules, [
                'down_payment_amount' => [
                    'required',
                    'numeric',
                    'min:0',
                    function ($attribute, $value, $fail) {
                        $contract_amount = (float) $this->input('contract_amount');
                        $down_payment = (float) $value;
                        $down_installment = (float) $this->input('down_payment_installment', 0);
                        $monthly = (float) $this->input('monthly_installment_amount', 0);
                        $months = (int) $this->input('number_of_months', 0);
                        $deferred_type = $this->input('deferred_type', 'none');
                        $deferred_months = (int) $this->input('deferred_months', 0);
                        $key_payment = (float) $this->input('key_payment_amount', 0);
                        $deferred = $down_payment - $down_installment;

                        // Check if down payment installment exceeds down payment
                        if ($down_installment > $down_payment) {
                            $fail('قسط الدفعة المقدمة يتجاوز الدفعة المقدمة.');
                        }

                        // Check if deferred type is provided when there is a deferred amount
                        if ($deferred > 0 && $deferred_type === 'none') {
                            $fail('نوع التأجيل مطلوب إذا كان هناك مبلغ مؤجل.');
                        }

                        // Check if deferred months are provided for spread type
                        if ($deferred_type === 'spread' && $deferred_months <= 0 && $deferred > 0) {
                            $fail('عدد أشهر التأجيل مطلوب إذا كان نوع التأجيل توزيع.');
                        }

                        // Check if deferred months exceed total months for spread type
                        if ($deferred_type === 'spread' && $deferred_months > $months) {
                            $fail('عدد أشهر التأجيل يتجاوز عدد الأشهر الكلي.');
                        }

                        // Check if lump sum month exceeds total months
                        if (str_starts_with($deferred_type, 'lump-')) {
                            $lump_month = (int) explode('-', $deferred_type)[1];
                            if ($lump_month > $months) {
                                $fail('شهر الدفعة الواحدة يتجاوز عدد الأشهر الكلي.');
                            }
                        }

                        // Validate total payment
                        $monthly_total = 0;
                        if ($deferred_type === 'spread' && $deferred_months > 0) {
                            $monthly_total = ($monthly + floor($deferred / $deferred_months)) * $deferred_months + $monthly * ($months - $deferred_months);
                            if ($deferred % $deferred_months > 0) {
                                $monthly_total += $deferred % $deferred_months;
                            }
                        } elseif (str_starts_with($deferred_type, 'lump-') && $deferred > 0) {
                            $monthly_total = $monthly * $months + $deferred;
                        } else {
                            $monthly_total = $monthly * $months;
                        }

                        $total = $down_installment + $monthly_total + $key_payment;
                        if (abs($total - $contract_amount) > 0.01) {
                            $fail('مجموع الدفعة المقدمة، الأقساط الشهرية، ودفعة المفتاح يجب أن يساوي مبلغ العقد بعد الخصم (إن وُجد). الإجمالي: ' . number_format($total, 2) . ', العقد: ' . number_format($contract_amount, 2));
                        }
                    },
                ],
                'down_payment_installment' => ['required', 'numeric', 'min:0'],
                'monthly_installment_amount' => ['required', 'numeric', 'min:0'],
                'number_of_months' => ['required', 'integer', 'min:1'],
                'deferred_type' => ['required', 'in:none,spread,lump-6,lump-7'],
                'deferred_months' => ['required', 'integer', 'min:0'],
                'key_payment_amount' => ['required', 'numeric', 'min:0'],
            ]);
        }

        // =====================================
        // ✅ دفعات مرنة (Method 4)
        // =====================================
        if ($this->input('contract_payment_method_id') == 4) {
            $rules['discount'] = ['nullable', 'numeric', 'min:0', 'max:100'];

            $rules = array_merge($rules, [
                'down_payment_amount' => [
                    'required',
                    'numeric',
                    'min:0',
                    function ($attribute, $value, $fail) use ($clean) {
                        $contract = $clean($this->input('contract_amount'));
                        $down_total = $clean($value);
                        $down_cash = $clean($this->input('down_payment_installment'));
                        $deferred_total = max(0, $down_total - $down_cash);

                        $monthly = $clean($this->input('monthly_installment_amount'));
                        $months = (int) $this->input('number_of_months', 0);
                        $key = $clean($this->input('key_payment_amount'));

                        $monthly_total = $monthly * $months;
                        $total = $down_cash + $deferred_total + $monthly_total + $key;
                        $diff = $contract - $total;

                        // Check if cash payment exceeds total down payment
                        if ($down_cash > $down_total) {
                            $fail("المبلغ النقدي أكبر من إجمالي الدفعة المقدمة.");
                        }

                        // Validate total equals contract amount
                        if (abs($total - $contract) > 0.01) {
                            $fail(
                                "مجموع الخطة لا يساوي مبلغ العقد:\n"
                                    . "────────────────────────\n"
                                    . "نقد: " . number_format($down_cash) . "\n"
                                    . "مؤجل: " . number_format($deferred_total) . "\n"
                                    . "أقساط: " . number_format($monthly_total) . " (" . $months . " × " . number_format($monthly) . ")\n"
                                    . "مفتاح: " . number_format($key) . "\n"
                                    . "────────────────────────\n"
                                    . "مجموع الخطة: " . number_format($total) . "\n"
                                    . "مبلغ العقد: " . number_format($contract) . "\n"
                                    . "الفارق: " . number_format($diff) . "\n"
                            );
                        }
                    }
                ],
                'down_payment_installment' => ['required', 'numeric', 'min:0'],
                'down_payment_deferred_installment' => ['nullable', 'numeric', 'min:0'],
                'down_payment_deferred_frequency' => ['nullable', 'integer', 'in:1,2,3,4,5,6'],
                'monthly_installment_amount' => ['required', 'numeric', 'min:0'],
                'number_of_months' => ['required', 'integer', 'min:1'],
                'monthly_frequency' => ['required', 'integer', 'in:1,2,3,4,5,6'],
                'monthly_start_date' => ['required', 'date_format:Y-m-d'],
                'key_payment_amount' => ['required', 'numeric', 'min:0'],
            ]);
        }

        // ==============================
        // ✅ طرق الدفع العادية (Methods 1, 2, etc.)
        // ==============================
        if (!in_array($this->input('contract_payment_method_id'), [3, 4])) {
            $rules['discount'] = ['required', 'numeric', 'min:0', 'max:100'];
        }

        return $rules;
    }

    protected function prepareForValidation()
    {
        // ✅ تم إزالة dd() - استخدم فقط عند الحاجة للتشخيص
        // dd($this->all());

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
