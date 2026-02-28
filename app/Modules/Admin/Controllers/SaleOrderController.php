<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Helpers\BaseHelper;
use App\Helpers\CommonHelper;
use App\Helpers\TimeHelper;
use App\Modules\Admin\Helpers\MainHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SaleOrderController extends BaseController
{
    public function index(Request $request)
    {
        lang([dirname(__DIR__).'/Languages/zh-CN/statistic.php']);

        $action = $request->get('act');

        if ($action === 'query' || $action === 'download') {
            $this->check_authz_json('sale_order_stats');
            if (strstr($_REQUEST['start_date'], '-') === false) {
                $_REQUEST['start_date'] = TimeHelper::local_date('Y-m-d', $_REQUEST['start_date']);
                $_REQUEST['end_date'] = TimeHelper::local_date('Y-m-d', $_REQUEST['end_date']);
            }

            // 下载报表
            if ($action === 'download') {
                $goods_order_data = $this->get_sales_order(false);
                $goods_order_data = $goods_order_data['sales_order_data'];

                $filename = $_REQUEST['start_date'].'_'.$_REQUEST['end_date'].'sale_order';

                header('Content-type: application/vnd.ms-excel; charset=utf-8');
                header("Content-Disposition: attachment; filename=$filename.xls");

                $data = lang('sell_stats')."\t\n";
                $data .= lang('order_by')."\t".lang('goods_name')."\t".lang('goods_sn')."\t".lang('sell_amount')."\t".lang('sell_sum')."\t".lang('percent_count')."\n";

                foreach ($goods_order_data as $k => $row) {
                    $order_by = $k + 1;
                    $data .= "$order_by\t$row[goods_name]\t$row[goods_sn]\t$row[goods_num]\t$row[turnover]\t$row[wvera_price]\n";
                }

                if (EC_CHARSET === 'utf-8') {
                    echo BaseHelper::ecs_iconv(EC_CHARSET, 'GB2312', $data);
                } else {
                    echo $data;
                }
                exit;
            }
            $goods_order_data = $this->get_sales_order();
            $this->assign('goods_order_data', $goods_order_data['sales_order_data']);
            $this->assign('filter', $goods_order_data['filter']);
            $this->assign('record_count', $goods_order_data['record_count']);
            $this->assign('page_count', $goods_order_data['page_count']);

            $sort_flag = MainHelper::sort_flag($goods_order_data['filter']);
            $this->assign($sort_flag['tag'], $sort_flag['img']);

            return $this->make_json_result($this->fetch('sale_order'), '', ['filter' => $goods_order_data['filter'], 'page_count' => $goods_order_data['page_count']]);
        }

        if ($action === 'list') {
            // 权限检查
            $this->admin_priv('sale_order_stats');

            // 时间参数
            if (! isset($_REQUEST['start_date'])) {
                $_REQUEST['start_date'] = TimeHelper::local_strtotime('-1 months');
            }
            if (! isset($_REQUEST['end_date'])) {
                $_REQUEST['end_date'] = TimeHelper::local_strtotime('+1 day');
            }
            $goods_order_data = $this->get_sales_order();

            // 赋值到模板
            $this->assign('ur_here', lang('sell_stats'));
            $this->assign('goods_order_data', $goods_order_data['sales_order_data']);
            $this->assign('filter', $goods_order_data['filter']);
            $this->assign('record_count', $goods_order_data['record_count']);
            $this->assign('page_count', $goods_order_data['page_count']);
            $this->assign('filter', $goods_order_data['filter']);
            $this->assign('full_page', 1);
            $this->assign('start_date', TimeHelper::local_date('Y-m-d', $_REQUEST['start_date']));
            $this->assign('end_date', TimeHelper::local_date('Y-m-d', $_REQUEST['end_date']));
            $this->assign('action_link', ['text' => lang('download_sale_sort'), 'href' => '#download']);

            return $this->display('sale_order');
        }
    }

    // ------------------------------------------------------
    // --排行统计需要的函数
    // ------------------------------------------------------
    /**
     * 取得销售排行数据信息
     *
     * @param  bool  $is_pagination  是否分页
     * @return array 销售排行数据
     */
    private function get_sales_order($is_pagination = true)
    {
        $filter['start_date'] = empty($_REQUEST['start_date']) ? '' : TimeHelper::local_strtotime($_REQUEST['start_date']);
        $filter['end_date'] = empty($_REQUEST['end_date']) ? '' : TimeHelper::local_strtotime($_REQUEST['end_date']);
        $filter['sort_by'] = empty($_REQUEST['sort_by']) ? 'goods_num' : trim($_REQUEST['sort_by']);
        $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);

        $query = DB::table('order_info as oi')
            ->join('order_goods as og', 'og.order_id', '=', 'oi.order_id')
            ->whereRaw('1 '.order_query_sql('finished', 'oi.'));

        if ($filter['start_date']) {
            $query->where('oi.add_time', '>=', $filter['start_date']);
        }
        if ($filter['end_date']) {
            $query->where('oi.add_time', '<=', $filter['end_date']);
        }

        $filter['record_count'] = $query->distinct()->count('og.goods_id');

        // 分页大小
        $filter = MainHelper::page_and_size($filter);

        $query->select('og.goods_id', 'og.goods_sn', 'og.goods_name', 'oi.order_status', DB::raw('SUM(og.goods_number) AS goods_num'), DB::raw('SUM(og.goods_number * og.goods_price) AS turnover'))
            ->groupBy('og.goods_id')
            ->orderBy($filter['sort_by'], $filter['sort_order']);

        if ($is_pagination) {
            $query->limit($filter['page_size'])->offset($filter['start']);
        }

        $sales_order_data = $query->get()->map(function ($item) {
            return (array) $item;
        })->toArray();

        foreach ($sales_order_data as $key => $item) {
            $sales_order_data[$key]['wvera_price'] = CommonHelper::price_format($item['goods_num'] ? $item['turnover'] / $item['goods_num'] : 0);
            $sales_order_data[$key]['short_name'] = Str::limit($item['goods_name'], 30);
            $sales_order_data[$key]['turnover'] = CommonHelper::price_format($item['turnover']);
            $sales_order_data[$key]['taxis'] = $key + 1;
        }

        $arr = ['sales_order_data' => $sales_order_data, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']];

        return $arr;
    }
}
