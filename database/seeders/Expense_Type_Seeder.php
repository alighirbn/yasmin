<?php

namespace Database\Seeders;

use App\Models\Cash\Expense_Type;
use App\Models\Payment\Payment_Method;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Expense_Type_Seeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // add Departments
    $expense_types = [
      'رواتب الموظفيين',
      'نثرية عامة',
    ];
    foreach ($expense_types as $expense_type) {
      Expense_Type::create(['expense_type' => $expense_type]);
    }
  }
}
