<?php

declare(strict_types=1);

namespace App\Plugins\Cron;

use Illuminate\Support\Facades\DB;

$cron_lang = ROOT_PATH.'languages/'.cfg('lang').'/cron/auto_manage.php';
if (file_exists($cron_lang)) {
    include_once $cron_lang;
}

// 模块的基本信息
if (isset($set_modules) && $set_modules === true) {
    $i = isset($modules) ? count($modules) : 0;

    // 代码
    $modules[$i]['code'] = basename(__FILE__, '.php');

    // 描述对应的语言项
    $modules[$i]['desc'] = 'auto_manage_desc';

    // 作者
    $modules[$i]['author'] = 'PHPMall TEAM';

    // 网址
    $modules[$i]['website'] = 'http://www.phpmall.net';

    // 版本号
    $modules[$i]['version'] = '1.0.0';

    // 配置信息
    $modules[$i]['config'] = [
        ['name' => 'auto_manage_count', 'type' => 'select', 'value' => '5'],
    ];

    return;
}

class AutoManage
{
    public function handle()
    {
        $time = gmtime();
        $limit = ! empty($cron['auto_manage_count']) ? $cron['auto_manage_count'] : 5;
        $autodb = DB::table('shop_auto_manage')
            ->where(function ($q) use ($time) {
                $q->where('starttime', '>', 0)->where('starttime', '<=', $time);
            })
            ->orWhere(function ($q) use ($time) {
                $q->where('endtime', '>', 0)->where('endtime', '<=', $time);
            })
            ->limit($limit)
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();
        foreach ($autodb as $key => $val) {
            $del = $up = false;
            if ($val['type'] === 'goods') {
                $goods = true;
                $where = " WHERE goods_id = '$val[item_id]'";
            } else {
                $goods = false;
                $where = " WHERE article_id = '$val[item_id]'";
            }

            // 上下架判断
            if (! empty($val['starttime']) && ! empty($val['endtime'])) {
                // 上下架时间均设置
                if ($val['starttime'] <= $time && $time < $val['endtime']) {
                    // 上架时间 <= 当前时间 < 下架时间
                    $up = true;
                    $del = false;
                } elseif ($val['starttime'] >= $time && $time > $val['endtime']) {
                    // 下架时间 <= 当前时间 < 上架时间
                    $up = false;
                    $del = false;
                } elseif ($val['starttime'] === $time && $time === $val['endtime']) {
                    // 下架时间 === 当前时间 === 上架时间
                    DB::table('shop_auto_manage')
                        ->where('item_id', $val['item_id'])
                        ->where('type', $val['type'])
                        ->delete();

                    continue;
                } elseif ($val['starttime'] > $val['endtime']) {
                    // 下架时间 < 上架时间 < 当前时间
                    $up = true;
                    $del = true;
                } elseif ($val['starttime'] < $val['endtime']) {
                    // 上架时间 < 下架时间 < 当前时间
                    $up = false;
                    $del = true;
                } else {
                    // 上架时间 = 下架时间 < 当前时间
                    DB::table('shop_auto_manage')
                        ->where('item_id', $val['item_id'])
                        ->where('type', $val['type'])
                        ->delete();

                    continue;
                }
            } elseif (! empty($val['starttime'])) {
                // 只设置了上架时间
                $up = true;
                $del = true;
            } else {
                // 只设置了下架时间
                $up = false;
                $del = true;
            }

            if ($goods) {
                DB::table('goods')
                    ->where($val['type'] === 'goods' ? 'goods_id' : 'article_id', $val['item_id'])
                    ->update(['is_on_sale' => $up ? 1 : 0]);
            } else {
                DB::table('article')
                    ->where('article_id', $val['item_id'])
                    ->update(['is_open' => $up ? 1 : 0]);
            }
            if ($del) {
                DB::table('shop_auto_manage')
                    ->where('item_id', $val['item_id'])
                    ->where('type', $val['type'])
                    ->delete();
            } else {
                if ($up) {
                    DB::table('shop_auto_manage')
                        ->where('item_id', $val['item_id'])
                        ->where('type', $val['type'])
                        ->update(['starttime' => 0]);
                } else {
                    DB::table('shop_auto_manage')
                        ->where('item_id', $val['item_id'])
                        ->where('type', $val['type'])
                        ->update(['endtime' => 0]);
                }
            }
        }
    }
}
