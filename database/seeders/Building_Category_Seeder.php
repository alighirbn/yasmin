<?php

namespace Database\Seeders;

use App\Models\Building\Building_Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Building_Category_Seeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $building_categorys = [
      [
        'url_address' => $this->get_random_string(60),
        'category_name' => 'A',
        'category_area' => '250',
        'category_cost' => '210,000,000',
      ],
      [
        'url_address' => $this->get_random_string(60),
        'category_name' => 'H',
        'category_area' => '400',
        'category_cost' => '190,000,000',
      ],
      [
        'url_address' => $this->get_random_string(60),
        'category_name' => 'R',
        'category_area' => '200',
        'category_cost' => '170,000,000',
      ],
      [
        'url_address' => $this->get_random_string(60),
        'category_name' => 'Y',
        'category_area' => '300',
        'category_cost' => '140,000,000',
      ],
      [
        'url_address' => $this->get_random_string(60),
        'category_name' => 'T',
        'category_area' => '200',
        'category_cost' => '100,000,000',
      ],

    ];
    foreach ($building_categorys as $building_category) {
      Building_Category::create($building_category);
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
