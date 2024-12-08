<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Permission_Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // add Permissions
        $permissions = [
            //dashboard



            //**************************building******************************* */
            //building permissions
            'building-list',
            'building-show',
            'building-create',
            'building-update',
            'building-delete',


            //**************************customer******************************* */
            //customer permissions
            'customer-list',
            'customer-show',
            'customer-create',
            'customer-update',
            'customer-delete',
            'customer-statement',

            //**************************contract******************************* */
            //contract permissions
            'contract-list',
            'contract-show',
            'contract-create',
            'contract-update',
            'contract-delete',
            'contract-statement',
            'contract-due',
            'contract-print',
            'contract-accept',
            'contract-authenticat',
            'contract-archive',
            'contract-archiveshow',


            //**************************transfer******************************* */
            //transfer permissions
            'transfer-list',
            'transfer-show',
            'transfer-create',
            'transfer-update',
            'transfer-delete',
            'transfer-approve',
            'transfer-print',

            //**************************payment******************************* */
            //payment permissions
            'payment-list',
            'payment-show',
            'payment-create',
            'payment-update',
            'payment-delete',
            'payment-approve',

            //**************************expense******************************* */
            //expense permissions
            'expense-list',
            'expense-show',
            'expense-create',
            'expense-update',
            'expense-delete',
            'expense-approve',

            //**************************income******************************* */
            //income permissions
            'income-list',
            'income-show',
            'income-create',
            'income-update',
            'income-delete',
            'income-approve',

            //**************************cash_account******************************* */
            //cash_account permissions
            'cash_account-list',
            'cash_account-show',
            'cash_account-create',
            'cash_account-update',
            'cash_account-delete',

            //**************************cash_transfer******************************* */
            //cash_transfer permissions
            'cash_transfer-list',
            'cash_transfer-show',
            'cash_transfer-create',
            'cash_transfer-update',
            'cash_transfer-delete',
            'cash_transfer-approve',

            //**************************service******************************* */
            //service permissions
            'service-list',
            'service-show',
            'service-create',
            'service-update',
            'service-delete',

            //**************************report******************************* */
            //report permissions
            'report-list',
            'report-category',
            'report-due_installments',
            'report-first_installment',
            'report-general_report',

            //**************************user******************************* */
            // map
            'map-map',
            'map-draw',
            'map-empty',
            'map-contract',
            'map-due',
            'map-edit',
            'map-hidden',

            //**************************history******************************* */
            // history
            'history-list',
            'history-all',

            //**************************user******************************* */

            // user permissions
            'dashboard-users',

            'user-list',
            'user-show',
            'user-create',
            'user-update',
            'user-delete',

            //**************************role******************************* */

            // role permissions
            'dashboard-roles',
            'role-list',
            'role-show',
            'role-create',
            'role-update',
            'role-delete',

        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        $user = User::create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('12345678'),
            'department_id' => 1,
            'url_address' => $this->get_random_string(60),
            'Status' => 'active',
        ]);

        $role = Role::create(['name' => 'admin']);

        $per = Permission::pluck('id', 'id')->all();

        $role->syncPermissions($per);

        $user->assignRole([$role]);


        // create user -- accountant 
        $user = User::create([
            'name' => 'accountant',
            'email' => 'accountant@admin.com',
            'password' => bcrypt('12345678'),
            'department_id' => 1,
            'url_address' => $this->get_random_string(60),
            'Status' => 'active',
        ]);

        $accountantRole = Role::create(['name' => 'accountant']);

        // Define a subset of permissions for the accountant role
        $accountantPermissions = [

            //**************************customer******************************* */
            //customer permissions
            'customer-list',
            'customer-show',
            'customer-create',
            'customer-update',

            'customer-statement',

            //**************************contract******************************* */
            //contract permissions
            'contract-list',
            'contract-show',
            'contract-create',
            'contract-update',

            'contract-statement',
            'contract-due',
            'contract-print',
            'contract-accept',


            //**************************transfer******************************* */
            //transfer permissions
            'transfer-list',
            'transfer-show',
            'transfer-create',
            'transfer-update',

            'transfer-approve',
            'transfer-print',

            //**************************payment******************************* */
            //payment permissions
            'payment-list',
            'payment-show',
            'payment-create',
            'payment-update',

            'payment-approve',

            //**************************expense******************************* */
            //expense permissions
            'expense-list',
            'expense-show',
            'expense-create',
            'expense-update',

            'expense-approve',

            //**************************cash_account******************************* */
            //cash_account permissions
            'cash_account-list',
            'cash_account-show',
            'cash_account-create',
            'cash_account-update',


            //**************************cash_transfer******************************* */
            //cash_transfer permissions
            'cash_transfer-list',
            'cash_transfer-show',
            'cash_transfer-create',
            'cash_transfer-update',

            'cash_transfer-approve',

            //**************************service******************************* */
            //service permissions
            'service-list',
            'service-show',
            'service-create',
            'service-update',


            //**************************report******************************* */
            //report permissions
            'report-list',
            'report-category',
            'report-due_installments',
            'report-first_installment',


            // map
            'map-map',
            'map-draw',
            'map-empty',
            'map-contract',
            'map-due',
        ];

        // Sync only the accountant permissions
        $accountantRole->syncPermissions($accountantPermissions);
        $user->assignRole($accountantRole);


        // create user -- lawyer 
        $user = User::create([
            'name' => 'lawyer',
            'email' => 'lawyer@admin.com',
            'password' => bcrypt('12345678'),
            'department_id' => 1,
            'url_address' => $this->get_random_string(60),
            'Status' => 'active',
        ]);

        $lawyerRole = Role::create(['name' => 'lawyer']);

        // Define a subset of permissions for the lawyer role
        $lawyerPermissions = [

            //**************************customer******************************* */
            //customer permissions
            'customer-list',
            'customer-show',
            'customer-create',
            'customer-update',

            'customer-statement',

            //**************************contract******************************* */
            //contract permissions
            'contract-list',
            'contract-show',
            'contract-create',
            'contract-update',

            'contract-statement',
            'contract-due',
            'contract-print',
            'contract-accept',


            //**************************transfer******************************* */
            //transfer permissions
            'transfer-list',
            'transfer-show',
            'transfer-create',
            'transfer-update',

            'transfer-approve',
            'transfer-print',

            //**************************payment******************************* */
            //payment permissions
            'payment-list',
            'payment-show',
            'payment-create',
            'payment-update',

            'payment-approve',

            //**************************expense******************************* */
            //expense permissions
            'expense-list',
            'expense-show',
            'expense-create',
            'expense-update',

            'expense-approve',

            //**************************cash_account******************************* */
            //cash_account permissions
            'cash_account-list',
            'cash_account-show',
            'cash_account-create',
            'cash_account-update',


            //**************************cash_transfer******************************* */
            //cash_transfer permissions
            'cash_transfer-list',
            'cash_transfer-show',
            'cash_transfer-create',
            'cash_transfer-update',

            'cash_transfer-approve',

            //**************************service******************************* */
            //service permissions
            'service-list',
            'service-show',
            'service-create',
            'service-update',


            //**************************report******************************* */
            //report permissions
            'report-list',
            'report-category',
            'report-due_installments',
            'report-first_installment',


            // map
            'map-map',
            'map-draw',
            'map-empty',
            'map-contract',
            'map-due',
        ];

        // Sync only the lawyer permissions
        $lawyerRole->syncPermissions($lawyerPermissions);
        $user->assignRole($lawyerRole);

        // create user -- lawyer 
        $user = User::create([
            'name' => 'sale',
            'email' => 'sale@admin.com',
            'password' => bcrypt('12345678'),
            'department_id' => 1,
            'url_address' => $this->get_random_string(60),
            'Status' => 'active',
        ]);
        $salesRole = Role::create(['name' => 'sales']);

        // Define a subset of permissions for the sales role
        $salesPermissions = [


            'customer-create',

            'contract-create',

            'map-map',
            'map-draw',
            'map-empty',

        ];

        // Sync only the sales permissions
        $salesRole->syncPermissions($salesPermissions);
        $user->assignRole($salesRole);
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
