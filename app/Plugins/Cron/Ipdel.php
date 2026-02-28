<?php

declare(strict_types=1);

namespace App\Plugins\Cron;

use Illuminate\Support\Facades\DB;

$cron_lang = ROOT_PATH.'languages/'.cfg('lang').'/cron/ipdel.php';
if (file_exists($cron_lang)) {
    include_once $cron_lang;
}

// 模块的基本信息
if (isset($set_modules) && $set_modules === true) {
    $i = isset($modules) ? count($modules) : 0;

    // 代码
    $modules[$i]['code'] = basename(__FILE__, '.php');

    // 描述对应的语言项
    $modules[$i]['desc'] = 'ipdel_desc';

    // 作者
    $modules[$i]['author'] = 'PHPMall TEAM';

    // 网址
    $modules[$i]['website'] = 'http://www.phpmall.net';

    // 版本号
    $modules[$i]['version'] = '1.0.0';

    // 配置信息
    $modules[$i]['config'] = [
        ['name' => 'ipdel_day', 'type' => 'select', 'value' => '30'],
    ];

    return;
}

class Ipdel
{
    public function handle()
    {
        empty($cron['ipdel_day']) && $cron['ipdel_day'] = 7;

        $deltime = gmtime() - $cron['ipdel_day'] * 3600 * 24;
        DB::table('shop_stats')
            ->where('access_time', '<', $deltime)
            ->delete();
    }
}
