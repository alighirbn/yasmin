<?php

namespace Database\Seeders;

use App\Models\Cash\Cash_Account;

use Illuminate\Database\Seeder;

class Cash_Account_Seeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // add Departments
    $cash_accounts = [
      [
        'url_address' => 'kguydsgsdjdsvwuwufuvvudvvwuvuugsgssf',
        'account_name' => 'القاصة',
        'balance' => 0,
      ],

    ];
    foreach ($cash_accounts as $cash_account) {
      Cash_Account::create($cash_account);
    }
  }
}
