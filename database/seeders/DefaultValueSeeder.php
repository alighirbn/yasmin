<?php

namespace Database\Seeders;

use App\Models\Building\DefaultValue;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DefaultValueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DefaultValue::create([
            'price_per_meter' => 1300000, // Set your desired default price
        ]);
    }
}
