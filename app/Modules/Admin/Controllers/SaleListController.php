<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Helpers\BaseHelper;
use App\Helpers\CommonHelper;
use App\Helpers\TimeHelper;
use App\Modules\Admin\Helpers\MainHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleListController extends BaseController
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
            // ------------------------------------------------------
            // --Excel文件下载
            // ------------------------------------------------------
            if ($action === 'download') {
                $file_name = $_REQUEST['start_date'].'_'.$_REQUEST['end_date'].'_sale';
                $goods_sales_list = $this->get_sale_list(false);
                header('Content-type: application/vnd.ms-excel; charset=utf-8');
                header("Content-Disposition: attachment; filename=$file_name.xls");

                // 文件标题
                echo BaseHelper::ecs_iconv(EC_CHARSET, 'GB2312', $_REQUEST['start_date'].lang('to').$_REQUEST['end_date'].lang('sales_list'))."\t\n";

                // 商品名称,订单号,商品数量,销售价格,销售日期
                echo BaseHelper::ecs_iconv(EC_CHARSET, 'GB2312', lang('goods_name'))."\t";
                echo BaseHelper::ecs_iconv(EC_CHARSET, 'GB2312', lang('order_sn'))."\t";
                echo BaseHelper::ecs_iconv(EC_CHARSET, 'GB2312', lang('amount'))."\t";
                echo BaseHelper::ecs_iconv(EC_CHARSET, 'GB2312', lang('sell_price'))."\t";
                echo BaseHelper::ecs_iconv(EC_CHARSET, 'GB2312', lang('sell_date'))."\t\n";

                foreach ($goods_sales_list['sale_list_data'] as $key => $value) {
                    echo BaseHelper::ecs_iconv(EC_CHARSET, 'GB2312', $value['goods_name'])."\t";
                    echo BaseHelper::ecs_iconv(EC_CHARSET, 'GB2312', '[ '.$value['order_sn'].' ]')."\t";
                    echo BaseHelper::ecs_iconv(EC_CHARSET, 'GB2312', $value['goods_num'])."\t";
                    echo BaseHelper::ecs_iconv(EC_CHARSET, 'GB2312', $value['sales_price'])."\t";
                    echo BaseHelper::ecs_iconv(EC_CHARSET, 'GB2312', $value['sales_time'])."\t";
                    echo "\n";
                }
                exit;
            }
            $sale_list_data = $this->get_sale_list();
            $this->assign('goods_sales_list', $sale_list_data['sale_list_data']);
            $this->assign('filter', $sale_list_data['filter']);
            $this->assign('record_count', $sale_list_data['record_count']);
            $this->assign('page_count', $sale_list_data['page_count']);

            return $this->make_json_result($this->fetch('sale_list'), '', ['filter' => $sale_list_data['filter'], 'page_count' => $sale_list_data['page_count']]);
        }
        /**
         *商品明细列表
         */
        if ($action === 'list') {
            // 权限判断
            $this->admin_priv('sale_order_stats');
            // 时间参数
            if (! isset($_REQUEST['start_date'])) {
                $start_date = TimeHelper::local_strtotime('-7 days');
            }
            if (! isset($_REQUEST['end_date'])) {
                $end_date = TimeHelper::local_strtotime('today');
            }

            $sale_list_data = $this->get_sale_list();
            // 赋值到模板
            $this->assign('filter', $sale_list_data['filter']);
            $this->assign('record_count', $sale_list_data['record_count']);
            $this->assign('page_count', $sale_list_data['page_count']);
            $this->assign('goods_sales_list', $sale_list_data['sale_list_data']);
            $this->assign('ur_here', lang('sell_stats'));
            $this->assign('full_page', 1);
            $this->assign('start_date', TimeHelper::local_date('Y-m-d', $start_date));
            $this->assign('end_date', TimeHelper::local_date('Y-m-d', $end_date));
            $this->assign('ur_here', lang('sale_list'));
            $this->assign('cfg_lang', cfg('lang'));
            $this->assign('action_link', ['text' => lang('down_sales'), 'href' => '#download']);

            return $this->display('sale_list');
        }
    }

    // ------------------------------------------------------
    // --获取销售明细需要的函数
    // ------------------------------------------------------
    /**
     * 取得销售明细数据信息
     *
     * @param  bool  $is_pagination  是否分页
     * @return array 销售明细数据
     */
    private function get_sale_list($is_pagination = true)
    {
        // 时间参数
        $filter['start_date'] = empty($_REQUEST['start_date']) ? TimeHelper::local_strtotime('-7 days') : TimeHelper::local_strtotime($_REQUEST['start_date']);
        $filter['end_date'] = empty($_REQUEST['end_date']) ? TimeHelper::local_strtotime('today') : TimeHelper::local_strtotime($_REQUEST['end_date']);

        // 查询数据的条件
        $query = DB::table('order_info as oi')
            ->join('order_goods as og', 'og.order_id', '=', 'oi.order_id')
            ->whereRaw('1 '.order_query_sql('finished', 'oi.'))
            ->where('oi.add_time', '>=', $filter['start_date'])
            ->where('oi.add_time', '<', $filter['end_date'] + 86400);

        $filter['record_count'] = $query->count('og.goods_id');

        // 分页大小
        $filter = MainHelper::page_and_size($filter);

        $query->select('og.goods_id', 'og.goods_sn', 'og.goods_name', 'og.goods_number as goods_num', 'og.goods_price as sales_price', 'oi.add_time as sales_time', 'oi.order_id', 'oi.order_sn')
            ->orderByDesc('sales_time')
            ->orderByDesc('goods_num');

        if ($is_pagination) {
            $query->limit($filter['page_size'])->offset($filter['start']);
        }

        $sale_list_data = $query->get()->map(function ($item) {
            return (array) $item;
        })->toArray();

        foreach ($sale_list_data as $key => $item) {
            $sale_list_data[$key]['sales_price'] = CommonHelper::price_format($sale_list_data[$key]['sales_price']);
            $sale_list_data[$key]['sales_time'] = TimeHelper::local_date(cfg('time_format'), $sale_list_data[$key]['sales_time']);
        }
        $arr = ['sale_list_data' => $sale_list_data, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']];

        return $arr;
    }
}
