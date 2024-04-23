<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminRoleSeeder extends Seeder
{
    public function run()
    {
        DB::transaction(function () {
            $result = DB::table('admin_roles')->count();
            if (empty($result)) {
                DB::table('admin_roles')->insert(['admin_user_id' => 1, 'role_id' => 1]);
            }
        });
    }
}
