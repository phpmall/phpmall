<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Helpers\BaseHelper;
use App\Helpers\CommonHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VisitSoldController extends BaseController
{
    public function index(Request $request)
    {
        lang([dirname(__DIR__).'/Languages/zh-CN/statistic.php']);

        $action = $request->get('act');

        $this->admin_priv('client_flow_stats');

        /**
         *访问购买比例
         */
        if ($action === 'list' || $action === 'download') {
            // 变量的初始化
            $cat_id = (! empty($_REQUEST['cat_id'])) ? intval($_REQUEST['cat_id']) : 0;
            $brand_id = (! empty($_REQUEST['brand_id'])) ? intval($_REQUEST['brand_id']) : 0;
            $show_num = (! empty($_REQUEST['show_num'])) ? intval($_REQUEST['show_num']) : 15;

            // 获取访问购买的比例数据
            $click_sold_info = $this->click_sold_info($cat_id, $brand_id, $show_num);

            // 下载报表
            if ($action === 'download') {
                $filename = 'visit_sold';
                header('Content-type: application/vnd.ms-excel; charset=utf-8');
                header("Content-Disposition: attachment; filename=$filename.xls");
                $data = lang('visit_buy')."\t\n";
                $data .= lang('order_by')."\t".lang('goods_name')."\t".lang('fav_exponential')."\t".lang('buy_times')."\t".lang('visit_buy')."\n";
                foreach ($click_sold_info as $k => $row) {
                    $order_by = $k + 1;
                    $data .= "$order_by\t$row[goods_name]\t$row[click_count]\t$row[sold_times]\t$row[scale]\n";
                }

                return BaseHelper::ecs_iconv(EC_CHARSET, 'GB2312', $data);
            }

            // 赋值到模板
            $this->assign('ur_here', lang('visit_buy_per'));

            $this->assign('show_num', $show_num);
            $this->assign('brand_id', $brand_id);
            $this->assign('click_sold_info', $click_sold_info);

            $this->assign('cat_list', CommonHelper::cat_list(0, $cat_id));
            $this->assign('brand_list', CommonHelper::get_brand_list());

            $filename = 'visit_sold';
            $this->assign('action_link', ['text' => lang('download_visit_buy'), 'href' => 'visit_sold.php?act=download&show_num='.$show_num.'&cat_id='.$cat_id.'&brand_id='.$brand_id.'&show_num='.$show_num]);

            return $this->display('visit_sold');
        }
    }

    // ------------------------------------------------------
    // --订单统计需要的函数
    // ------------------------------------------------------
    /**
     * 取得访问和购买次数统计数据
     *
     * @param  int  $cat_id  分类编号
     * @param  int  $brand_id  品牌编号
     * @param  int  $show_num  显示个数
     * @return array $click_sold_info  访问购买比例数据
     */
    private function click_sold_info($cat_id, $brand_id, $show_num)
    {
        $query = DB::table('goods as g')
            ->join('order_goods as og', 'g.goods_id', '=', 'og.goods_id')
            ->join('order_info as o', 'o.order_id', '=', 'og.order_id')
            ->select('og.goods_id', 'g.goods_sn', 'g.goods_name', 'g.click_count', DB::raw('COUNT(og.goods_id) AS sold_times'))
            ->whereRaw('1 '.order_query_sql('finished', 'o.'));

        if ($cat_id > 0) {
            $query->whereRaw(CommonHelper::get_children($cat_id));
        }

        if ($brand_id > 0) {
            $query->where('g.brand_id', $brand_id);
        }

        $res = $query->groupBy('og.goods_id')
            ->orderByDesc('g.click_count')
            ->limit($show_num)
            ->get()
            ->map(function ($item) {
                return (array) $item;
            })
            ->toArray();

        foreach ($res as $item) {
            if ($item['click_count'] <= 0) {
                $item['scale'] = 0;
            } else {
                // 每一百个点击的订单比率
                $item['scale'] = sprintf('%0.2f', ($item['sold_times'] / $item['click_count']) * 100).'%';
            }

            $click_sold_info[] = $item;
        }

        return $click_sold_info;
    }
}
