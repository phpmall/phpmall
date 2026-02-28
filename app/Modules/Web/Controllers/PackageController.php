<?php

declare(strict_types=1);

namespace App\Modules\Web\Controllers;

use App\Helpers\CommonHelper;
use App\Helpers\MainHelper;
use App\Helpers\TimeHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class PackageController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        // 载入语言文件
        require_once ROOT_PATH.'languages/'.cfg('lang').'/shopping_flow.php';
        require_once ROOT_PATH.'languages/'.cfg('lang').'/user.php';
        require_once ROOT_PATH.'languages/'.cfg('lang').'/admin/package.php';

        $this->assign_template();
        $this->assign_dynamic('package');
        $position = $this->assign_ur_here(0, lang('shopping_package'));
        $this->assign('page_title', $position['title']);    // 页面标题
        $this->assign('ur_here', $position['ur_here']);  // 当前位置

        // 读出所有礼包信息

        $now = TimeHelper::gmtime();

        $res = DB::table('goods_activity')
            ->where('start_time', '<=', $now)
            ->where('end_time', '>=', $now)
            ->where('act_type', 4)
            ->orderBy('end_time')
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();

        $list = [];
        foreach ($res as $row) {
            $row['start_time'] = TimeHelper::local_date('Y-m-d H:i', $row['start_time']);
            $row['end_time'] = TimeHelper::local_date('Y-m-d H:i', $row['end_time']);
            $ext_arr = unserialize($row['ext_info']);
            unset($row['ext_info']);
            if ($ext_arr) {
                foreach ($ext_arr as $key => $val) {
                    $row[$key] = $val;
                }
            }

            $goods_res = DB::table('activity_package as pg')
                ->select('pg.package_id', 'pg.goods_id', 'pg.goods_number', 'pg.admin_id', 'g.goods_sn', 'g.goods_name', 'g.market_price', 'g.goods_thumb', DB::raw("IFNULL(mp.user_price, g.shop_price * '".Session::get('discount', 1)."') AS rank_price"))
                ->leftJoin('goods as g', 'g.goods_id', '=', 'pg.goods_id')
                ->leftJoin('goods_member_price as mp', function ($join) {
                    $join->on('mp.goods_id', '=', 'g.goods_id')
                        ->where('mp.user_rank', '=', Session::get('user_rank', 0));
                })
                ->where('pg.package_id', $row['act_id'])
                ->orderBy('pg.goods_id')
                ->get()
                ->map(fn ($item) => (array) $item)
                ->all();

            $subtotal = 0;
            foreach ($goods_res as $key => $val) {
                $goods_res[$key]['goods_thumb'] = CommonHelper::get_image_path($val['goods_thumb']);
                $goods_res[$key]['market_price'] = CommonHelper::price_format($val['market_price']);
                $goods_res[$key]['rank_price'] = CommonHelper::price_format($val['rank_price']);
                $subtotal += $val['rank_price'] * $val['goods_number'];
            }

            $row['goods_list'] = $goods_res;
            $row['subtotal'] = CommonHelper::price_format($subtotal);
            $row['saving'] = CommonHelper::price_format(($subtotal - $row['package_price']));
            $row['package_price'] = CommonHelper::price_format($row['package_price']);

            $list[] = $row;
        }

        $this->assign('list', $list);

        $this->assign('helps', MainHelper::get_shop_help());       // 网店帮助

        $this->assign('feed_url', (cfg('rewrite') === 1) ? 'feed-typepackage.xml' : 'feed.php?type=package'); // RSS URL

        return $this->display('package');
    }
}
