<?php

use think\migration\Seeder;

class AuthGroupAccessSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $result = DB::table('auth_group_access')->find(1);
        if (is_null($result)) {
            DB::table('auth_group_access')->insert([
                'id' => 1,
                'user_id' => 1,
                'auth_group_id' => 1,
            ]);
        }
    }
}
