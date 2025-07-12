<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ZainSmsService
{
    public function send($phone_number, $name, $amount, $due_date)
    {
        $baseUrl = 'https://bulk-bgd.gtm.iq.zain.com:888/send-sms';
        $token = '7oRQYsWqP6nZ4h8hZVTQXgyg05wDwe';
        $lang = 'ar';

        // Normalize phone number
        $phone_number = $this->normalizePhoneNumber($phone_number);

        $params = [
            'token' => $token,
            'lang' => $lang,
            'receiver' => $phone_number,
            'x1' => $name,
            'x2' => $amount,
            'x3' => $due_date,
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
        $number = preg_replace('/\D/', '', $number); // Remove non-digit characters

        // If number starts with 0 and is 11 digits, convert to 964
        if (preg_match('/^0(7\d{9})$/', $number, $matches)) {
            return '964' . $matches[1];
        }

        // If already in international format, return as-is
        if (preg_match('/^9647\d{9}$/', $number)) {
            return $number;
        }

        // If invalid format
        throw new \InvalidArgumentException("Invalid phone number format: $number");
    }
}
