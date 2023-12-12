<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        DB::transaction(function () {
            $roleId = 1;

            $permission = DB::table('permissions')->where('status', 1)->select();

            $privilege = [];
            foreach ($permission as $item) {
                $privilege[] = ['role_id' => $roleId, 'permission_id' => $item['id']];
            }

            DB::table('permissions')->where('role_id', $roleId)->delete();
            DB::table('permissions')->insert($privilege);
        });
    }
}
