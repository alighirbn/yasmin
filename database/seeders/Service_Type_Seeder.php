<?php

namespace Database\Seeders;

use App\Models\Payment\Service_Type;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Service_Type_Seeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // add Departments
    $service_types = [
      'اجور الماء والمجاري',
      'اجور الكهرباء الوطنية',
      'اجور خدمة الانترنيت',
      'اجور خدمة الحراسة الليلية',
      'اجور خدمة النظافة',
      'اجور اعمال الصيانة',
      'اجور خدمة المولدة السحب',
      'اجور عامة',

    ];
    foreach ($service_types as $service_type) {
      Service_Type::create(['type_name' => $service_type]);
    }
  }
}
