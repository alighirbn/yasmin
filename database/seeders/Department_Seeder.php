<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Department_Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
                // add Departments
        $departments = [
          'مجمع واحة الياسمين',
          'اخرى',

        ];
         foreach ($departments as $department)
          {
             Department::create(['department' => $department]);
          }

    }
}
