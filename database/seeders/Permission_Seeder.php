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

            'dashboard-Planning',
            'dashboard-users',
            'dashboard-roles',
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

            //**************************contract******************************* */
            //contract permissions
            'contract-list',
            'contract-show',
            'contract-create',
            'contract-update',
            'contract-delete',
            'contract-statement',
            'contract-print',

            //**************************transfer******************************* */
            //transfer permissions
            'transfer-list',
            'transfer-show',
            'transfer-create',
            'transfer-update',
            'transfer-delete',
            'transfer-approve',
            //**************************payment******************************* */
            //payment permissions
            'payment-list',
            'payment-show',
            'payment-create',
            'payment-update',
            'payment-delete',
            'payment-approve',
            //**************************service******************************* */
            //service permissions
            'service-list',
            'service-show',
            'service-create',
            'service-update',
            'service-delete',


            // map
            'map-index',
            //**************************user******************************* */

            // user permissions
            'user-list',
            'user-show',
            'user-create',
            'user-update',
            'user-delete',

            //**************************role******************************* */

            // role permissions
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
