<?php

use think\migration\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $exist = $this->fetchRow('select id from ' . env('DB_PREFIX') . 'user where id = 1');
        if ($exist === false) {
            $this->insert('user', [
                'id' => 1,
                'username' => 'admin',
                'password' => password_hash('123456', PASSWORD_DEFAULT),
                'name' => '李四',
                'avatar' => '头像',
                'mobile' => '13013013030',
                'mobile_verified_at' => time(),
                'email' => 'admin@domain.com',
                'email_verified_at' => time(),
                'remember_token' => '',
                'reset_token' => '',
                'created_at' => time(),
                'updated_at' => time(),
            ]);
        }
    }
}
