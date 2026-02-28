<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Helpers\BaseHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdsenseController extends BaseController
{
    public function index(Request $request)
    {
        lang([dirname(__DIR__).'/Languages/zh-CN/ads.php']);

        $action = $request->get('act', 'list'); // Default to 'list'

        /**
         * 站外投放广告的统计
         */
        if ($action === 'list' || $action === 'download') {
            $this->admin_priv('ad_manage');

            // Pre-fetch order counts for ads_stats to avoid N+1 queries
            $ad_order_stats = DB::table('order_info')
                ->select(
                    'from_ad',
                    'referer',
                    DB::raw('COUNT(*) as total_orders'),
                    DB::raw('COUNT(CASE WHEN order_status = 5 AND shipping_status = 2 AND pay_status = 2 THEN 1 END) as confirmed_orders')
                )
                ->whereNotNull('from_ad')
                ->where('from_ad', '!=', '-1') // Exclude goods stats
                ->groupBy('from_ad', 'referer')
                ->get()
                ->keyBy(function ($item) {
                    return $item->from_ad.'_'.$item->referer;
                });

            // 获取广告数据
            $ads_stats = [];
            $res = DB::table('ad as a')
                ->join('ad_adsense as b', 'b.from_ad', '=', 'a.ad_id')
                ->select('a.ad_id', 'a.ad_name', 'b.*')
                ->orderBy('a.ad_name', 'DESC')
                ->get();

            foreach ($res as $rows) {
                $rows = (array) $rows;
                $key = $rows['ad_id'].'_'.$rows['referer'];
                $stats = $ad_order_stats->get($key);

                $rows['order_num'] = $stats ? $stats->total_orders : 0;
                $rows['order_confirm'] = $stats ? $stats->confirmed_orders : 0;
                $ads_stats[] = $rows;
            }

            // Pre-fetch order counts for goods_stats to avoid N+1 queries
            $goods_order_stats = DB::table('order_info')
                ->select(
                    'referer',
                    DB::raw('COUNT(*) as total_orders'),
                    DB::raw('COUNT(CASE WHEN order_status = 5 AND shipping_status = 2 AND pay_status = 2 THEN 1 END) as confirmed_orders')
                )
                ->where('from_ad', '-1') // Only for goods stats
                ->whereNotNull('referer')
                ->groupBy('referer')
                ->get()
                ->keyBy('referer');

            // 站外JS投放商品的统计数据
            $goods_stats = [];
            $goods_res = DB::table('ad_adsense')
                ->select('from_ad', 'referer', 'clicks')
                ->where('from_ad', '-1')
                ->orderBy('referer', 'DESC')
                ->get();

            foreach ($goods_res as $rows2) {
                $rows2 = (array) $rows2;
                $stats = $goods_order_stats->get($rows2['referer']);

                $rows2['order_num'] = $stats ? $stats->total_orders : 0;
                $rows2['order_confirm'] = $stats ? $stats->confirmed_orders : 0;
                $rows2['ad_name'] = lang('adsense_js_goods');
                $goods_stats[] = $rows2;
            }

            if ($action === 'download') {
                $filename = 'ad_statistics.xls';
                header('Content-type: application/vnd.ms-excel; charset=utf-8');
                header('Content-Disposition: attachment; filename='.$filename);

                $data = lang('adsense_name')."\t".lang('cleck_referer')."\t".lang('click_count')."\t".lang('confirm_order')."\t".lang('gen_order_amount')."\n";
                $res_merge = array_merge($goods_stats, $ads_stats);
                foreach ($res_merge as $row) {
                    $data .= "$row[ad_name]\t$row[referer]\t$row[clicks]\t$row[order_confirm]\t$row[order_num]\n";
                }

                return BaseHelper::ecs_iconv(EC_CHARSET, 'GB2312', $data);
            }

            $this->assign('ads_stats', $ads_stats);
            $this->assign('goods_stats', $goods_stats);

            // 赋值给模板
            $this->assign('action_link', ['href' => 'ads.php?act=list', 'text' => lang('ad_list')]);
            $this->assign('action_link2', ['href' => 'adsense.php?act=download', 'text' => lang('download_ad_statistics')]);
            $this->assign('ur_here', lang('adsense_js_stats'));

            return $this->display('adsense');
        }
    }
}
