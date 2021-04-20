<?php

use think\migration\Seeder;

class AuthGroupSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $result = DB::table('auth_groups')->find(1);
        if (is_null($result)) {
            DB::table('auth_groups')->insert([
                'id' => 1,
                'title' => '超级管理员',
                'status' => 1,
                'rules' => '1',
                'created_at' => Carbon::now(),
            ]);
        }
    }
}
