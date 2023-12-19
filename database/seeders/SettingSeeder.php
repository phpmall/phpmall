<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    public function run()
    {
        DB::transaction(function () {
            $result = DB::table('settings')->count();
            if (empty($result)) {
                $this->setting();
                $this->company();
                $this->site();
                $this->mail();
                $this->sms();
                $this->baidu();
            }
        });
    }

    private function setting()
    {
        $id = DB::table('settings')->insertGetId(['parent_id' => 0, 'code' => 'setting', 'type' => 'hidden']);
        DB::table('settings')->insert([
            ['parent_id' => $id, 'name' => '配置项', 'code' => 'setting_item', 'type' => 'text'],
        ]);
    }

    private function company()
    {
        $id = DB::table('settings')->insertGetId(['parent_id' => 0, 'code' => 'company', 'type' => 'hidden']);
        DB::table('settings')->insert([
            ['parent_id' => $id, 'name' => '配置项', 'code' => 'company_name', 'type' => 'text'],
            ['parent_id' => $id, 'name' => '配置项', 'code' => 'company_address', 'type' => 'text'],
            ['parent_id' => $id, 'name' => '配置项', 'code' => 'company_zip_code', 'type' => 'text'],
            ['parent_id' => $id, 'name' => '配置项', 'code' => 'company_contact', 'type' => 'text'],
            ['parent_id' => $id, 'name' => '配置项', 'code' => 'company_phone', 'type' => 'text'],
            ['parent_id' => $id, 'name' => '配置项', 'code' => 'company_fax', 'type' => 'text'],
            ['parent_id' => $id, 'name' => '配置项', 'code' => 'company_qq', 'type' => 'text'],
            ['parent_id' => $id, 'name' => '配置项', 'code' => 'company_qrcode', 'type' => 'text'],
            ['parent_id' => $id, 'name' => '配置项', 'code' => 'company_license', 'type' => 'text'],
        ]);
    }

    private function site()
    {
        $id = DB::table('settings')->insertGetId(['parent_id' => 0, 'code' => 'site', 'type' => 'hidden']);
        DB::table('settings')->insert([
            ['parent_id' => $id, 'name' => '配置项', 'code' => 'site_title', 'type' => 'text'],
            ['parent_id' => $id, 'name' => '配置项', 'code' => 'site_subhead', 'type' => 'text'],
            ['parent_id' => $id, 'name' => '配置项', 'code' => 'site_keywords', 'type' => 'text'],
            ['parent_id' => $id, 'name' => '配置项', 'code' => 'site_description', 'type' => 'textarea'],
            ['parent_id' => $id, 'name' => '配置项', 'code' => 'site_logo', 'type' => 'image'],
            ['parent_id' => $id, 'name' => '配置项', 'code' => 'site_beian', 'type' => 'text'],
            ['parent_id' => $id, 'name' => '配置项', 'code' => 'site_stat_code', 'type' => 'textarea'],
            ['parent_id' => $id, 'name' => '配置项', 'code' => 'site_template', 'type' => 'select'],
            ['parent_id' => $id, 'name' => '配置项', 'code' => 'site_copyright', 'type' => 'textarea'],
        ]);
    }

    private function mail()
    {
        $id = DB::table('settings')->insertGetId(['parent_id' => 0, 'code' => 'mail', 'type' => 'hidden']);
        DB::table('settings')->insert([
            ['parent_id' => $id, 'name' => '配置项', 'code' => 'mail_host', 'type' => 'text'],
            ['parent_id' => $id, 'name' => '配置项', 'code' => 'mail_port', 'type' => 'text'],
            ['parent_id' => $id, 'name' => '配置项', 'code' => 'mail_ssl', 'type' => 'text'],
            ['parent_id' => $id, 'name' => '配置项', 'code' => 'mail_account', 'type' => 'text'],
            ['parent_id' => $id, 'name' => '配置项', 'code' => 'mail_password', 'type' => 'text'],
            ['parent_id' => $id, 'name' => '配置项', 'code' => 'mail_sender', 'type' => 'text'],
        ]);
    }

    private function sms()
    {
        $id = DB::table('settings')->insertGetId(['parent_id' => 0, 'code' => 'sms', 'type' => 'hidden']);
        DB::table('settings')->insert([
            ['parent_id' => $id, 'name' => '配置项', 'code' => 'sms_provider', 'type' => 'text'],
            ['parent_id' => $id, 'name' => '配置项', 'code' => 'sms_account', 'type' => 'text'],
            ['parent_id' => $id, 'name' => '配置项', 'code' => 'sms_password', 'type' => 'text'],
        ]);
    }

    private function baidu()
    {
        $id = DB::table('settings')->insertGetId(['parent_id' => 0, 'code' => 'baidu', 'type' => 'hidden']);
        DB::table('settings')->insert([
            ['parent_id' => $id, 'name' => '配置项', 'code' => 'baidu_token', 'type' => 'text'],
            ['parent_id' => $id, 'name' => '配置项', 'code' => 'baidu_push_type', 'type' => 'select'],
        ]);
    }
}
