<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;


use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    // Facility referance tables


    $this->call([
      // General seeders
      Permission_Seeder::class,
      Department_Seeder::class,
      Payment_Method_Seeder::class,
      Installment_Seeder::class,
      Building_Category_Seeder::class,
      Building_Type_Seeder::class,
      Service_Type_Seeder::class,
      Expense_Type_Seeder::class,
      Cash_Account_Seeder::class,
      Building_Seeder::class,
      DefaultValueSeeder::class,
    ]);
  }
}
