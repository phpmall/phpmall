<?php

use think\migration\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $result = DB::table('settings')->find(1);
        if (is_null($result)) {
            $config = [
                ['parent_id' => 0, 'code' => 'setting', 'type' => 'hidden'],
                ['parent_id' => 0, 'code' => 'company', 'type' => 'hidden'],
                ['parent_id' => 0, 'code' => 'site', 'type' => 'hidden'],
                ['parent_id' => 0, 'code' => 'email', 'type' => 'hidden'],
            ];

            foreach ($config as $key => $item) {
                DB::table('auth_rules')->insert($item);
            }

            $this->setting();
            $this->company();
            $this->site();
            $this->email();
        }
    }

    private function setting()
    {

    }

    private function company()
    {

    }

    private function site()
    {

    }

    private function email()
    {

    }
}
