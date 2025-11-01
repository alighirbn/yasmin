<?php

namespace Database\Seeders;

use App\Models\Payment\Payment_Method;
use Illuminate\Database\Seeder;

class Payment_Method_Seeder extends Seeder
{
  public function run(): void
  {
    $payment_methods = [
      'نقدي',
      'دفعات',
      'دفعات متغيرة',
      'دفعات مرنة', // ✅ جديد
    ];

    foreach ($payment_methods as $payment_method) {
      Payment_Method::create(['method_name' => $payment_method]);
    }
  }
}
