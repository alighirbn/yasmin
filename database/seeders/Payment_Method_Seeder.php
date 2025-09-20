<?php

namespace Database\Seeders;

use App\Models\Payment\Payment_Method;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Payment_Method_Seeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $payment_methods = [
      'نقدي',
      'دفعات',
      'دفعات متغيرة', // NEW: Variable Installments
    ];
    foreach ($payment_methods as $payment_method) {
      Payment_Method::create(['method_name' => $payment_method]);
    }
  }
}
