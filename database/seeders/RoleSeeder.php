<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run()
    {
        DB::transaction(function () {
            $result = DB::table('roles')->count();
            if (empty($result)) {
                DB::table('roles')->insert([
                    'id' => 1,
                    'name' => '超级管理员',
                    'description' => '',
                ]);
            }
        });
    }
}
