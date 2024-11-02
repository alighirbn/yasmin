<?php

namespace Database\Seeders;

use App\Models\Building\Classification;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClassificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $classifications = [

            [
                'name' => 'مساحة اضافية',
                'price_per_meter' => 1600000,
            ],
            [
                'name' => 'ركن',
                'price_per_meter' => 1500000,
            ],
            [
                'name' => 'اعتيادي',
                'price_per_meter' => 1400000,
            ],
            [
                'name' => 'شقة',
                'price_per_meter' => 1300000,
            ],


        ];
        foreach ($classifications as $classification) {
            Classification::create($classification);
        }
    }
}
