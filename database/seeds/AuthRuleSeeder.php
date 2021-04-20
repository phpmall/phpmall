<?php

use think\migration\Seeder;

class AuthRuleSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $result = DB::table('auth_rules')->find(1);
        if (is_null($result)) {
            $menu = [
                ['title' => '全局设置', 'name' => 'setting', 'icon' => 'layui-icon-slider',],
                ['title' => '内容管理', 'name' => 'content', 'icon' => 'layui-icon-form',],
                ['title' => '扩展模块', 'name' => 'extension', 'icon' => 'layui-icon-app',],
                ['title' => '权限管理', 'name' => 'permission', 'icon' => 'layui-icon-user',],
                ['title' => '系统管理', 'name' => 'system', 'icon' => 'layui-icon-set',]
            ];

            foreach ($menu as $key => $item) {
                DB::table('auth_rules')->insert([
                    'id' => $key + 1,
                    'parent' => 0,
                    'name' => $item['name'],
                    'title' => $item['title'],
                    'icon' => $item['icon'],
                    'menu' => 1,
                ]);
            }
        }
    }
}
