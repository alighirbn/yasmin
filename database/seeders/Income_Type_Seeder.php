<?php

namespace Database\Seeders;

use App\Models\Cash\Income_Type;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Income_Type_Seeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // add Departments
    $income_types = [
      'اجور فسخ حجز',
      'اجور فسخ عقد',
      'ايراد عام',
    ];
    foreach ($income_types as $income_type) {
      Income_Type::create(['income_type' => $income_type]);
    }
  }
}
