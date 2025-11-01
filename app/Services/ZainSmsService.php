<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ZainSmsService
{
    public function send($phone_number, $name, $amount, $due_date, $property_code = null)
    {
        $baseUrl = 'https://bulk-bgd.gtm.iq.zain.com:888/send-sms';
        $token = 'whfh9WZIUKH64oDWXydvvAk6UtHQYT';
        $lang = 'ar';

        // تطبيع رقم الهاتف
        $phone_number = $this->normalizePhoneNumber($phone_number);

        // ✅ صياغة الرسالة ضمن x1 لأن القالب لا يمكن تعديله
        $message = "  {$name}، تذكير بدفع {$amount} دينار المستحق بتاريخ {$due_date}";
        if ($property_code) {
            $message .= " الدفعة الأولى عن العقار المرقم {$property_code}.";
        }
        $message .= " لتجنب الغرامات .";

        // المعلمات المرسلة
        $params = [
            'token' => $token,
            'lang' => $lang,
            'receiver' => $phone_number,
            'x1' => $message, // أرسل النص الكامل ضمن x1
        ];

        try {
            $response = Http::get($baseUrl, $params);

            return $response->successful()
                ? ['status' => true, 'response' => $response->body()]
                : ['status' => false, 'response' => $response->body()];
        } catch (\Exception $e) {
            return ['status' => false, 'error' => $e->getMessage()];
        }
    }

    private function normalizePhoneNumber($number)
    {
        $number = preg_replace('/\D/', '', $number); // إزالة أي رموز غير أرقام

        // إذا الرقم يبدأ بـ0 ومكون من 11 رقم (مثلاً 0780...)
        if (preg_match('/^0(7\d{9})$/', $number, $matches)) {
            return '964' . $matches[1];
        }

        // إذا الرقم أصلاً بصيغة دولية صحيحة
        if (preg_match('/^9647\d{9}$/', $number)) {
            return $number;
        }

        throw new \InvalidArgumentException("Invalid phone number format: $number");
    }
}
