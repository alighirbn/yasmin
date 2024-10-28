<?php

namespace Database\Seeders;

use App\Models\Contract\Installment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Installment_Seeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $installments = [
      [
        'url_address' => $this->get_random_string(60),
        'installment_number' => '1',
        'installment_name' => 'نقدي',
        'installment_percent' => 1,
        'installment_period' => '0',
        'payment_method_id' => 1,
      ],
      [
        'url_address' => $this->get_random_string(60),
        'installment_number' => '1',
        'installment_name' => 'الاولى',
        'installment_percent' => 0.1,
        'installment_period' => '0',
        'payment_method_id' => 2,
      ],
      [
        'url_address' => $this->get_random_string(60),
        'installment_number' => '2',
        'installment_name' => 'الثانية',
        'installment_percent' => 0.05,
        'installment_period' => '3',
        'payment_method_id' => 2,
      ],
      [
        'url_address' => $this->get_random_string(60),
        'installment_number' => '3',
        'installment_name' => 'الثالثة',
        'installment_percent' => 0.10,
        'installment_period' => '6',
        'payment_method_id' => 2,
      ],
      [
        'url_address' => $this->get_random_string(60),
        'installment_number' => '4',
        'installment_name' => 'الرابعة',
        'installment_percent' => 0.05,
        'installment_period' => '9',
        'payment_method_id' => 2,
      ],
      [
        'url_address' => $this->get_random_string(60),
        'installment_number' => '5',
        'installment_name' => 'الخامسة',
        'installment_percent' => 0.10,
        'installment_period' => '12',
        'payment_method_id' => 2,
      ],
      [
        'url_address' => $this->get_random_string(60),
        'installment_number' => '6',
        'installment_name' => 'السادسة',
        'installment_percent' => 0.10,
        'installment_period' => '15',
        'payment_method_id' => 2,
      ],
      [
        'url_address' => $this->get_random_string(60),
        'installment_number' => '7',
        'installment_name' => 'السابعة',
        'installment_percent' => 0.10,
        'installment_period' => '18',
        'payment_method_id' => 2,
      ],
      [
        'url_address' => $this->get_random_string(60),
        'installment_number' => '8',
        'installment_name' => 'الثامنة',
        'installment_percent' => 0.10,
        'installment_period' => '21',
        'payment_method_id' => 2,
      ],
      [
        'url_address' => $this->get_random_string(60),
        'installment_number' => '9',
        'installment_name' => 'التاسعة',
        'installment_percent' => 0.1,
        'installment_period' => '24',
        'payment_method_id' => 2,
      ],
      [
        'url_address' => $this->get_random_string(60),
        'installment_number' => '10',
        'installment_name' => 'العاشرة ',
        'installment_percent' => 0.1,
        'installment_period' => '27',
        'payment_method_id' => 2,
      ],
      [
        'url_address' => $this->get_random_string(60),
        'installment_number' => '11',
        'installment_name' => 'الحادي عشر ',
        'installment_percent' => 0.05,
        'installment_period' => '30',
        'payment_method_id' => 2,
      ],
      [
        'url_address' => $this->get_random_string(60),
        'installment_number' => '12',
        'installment_name' => 'الثاني عشر والاخيرة ',
        'installment_percent' => 0.05,
        'installment_period' => '33',
        'payment_method_id' => 2,
      ],



    ];
    foreach ($installments as $installment) {
      Installment::create($installment);
    }
  }
  public function get_random_string($length)
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
