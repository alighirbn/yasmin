<?php

namespace Database\Seeders;

use App\Models\Building\Building_Type;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Building_Type_Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
                // add Departments
        $building_types = [
          'دار',
          'شقة',
        ];
         foreach ($building_types as $building_type)
          {
             Building_Type::create(['type_name' => $building_type]);
          }

    }
}
