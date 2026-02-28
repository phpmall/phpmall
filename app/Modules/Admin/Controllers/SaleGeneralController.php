<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Helpers\BaseHelper;
use App\Helpers\TimeHelper;
use App\Modules\Admin\Helpers\MainHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleGeneralController extends BaseController
{
    public function index(Request $request)
    {
        lang([dirname(__DIR__).'/Languages/zh-CN/statistic.php']);

        $action = $request->get('act');

        // 权限判断
        $this->admin_priv('sale_order_stats');

        // 取得查询类型和查询时间段
        if (empty($_POST['query_by_year']) && empty($_POST['query_by_month'])) {
            if (empty($_GET['query_type'])) {
                // 默认当年的月走势
                $query_type = 'month';
                $start_time = TimeHelper::local_mktime(0, 0, 0, 1, 1, intval(date('Y')));
                $end_time = TimeHelper::gmtime();
            } else {
                // 下载时的参数
                $query_type = $_GET['query_type'];
                $start_time = $_GET['start_time'];
                $end_time = $_GET['end_time'];
            }
        } else {
            if (isset($_POST['query_by_year'])) {
                // 年走势
                $query_type = 'year';
                $start_time = TimeHelper::local_mktime(0, 0, 0, 1, 1, intval($_POST['year_beginYear']));
                $end_time = TimeHelper::local_mktime(23, 59, 59, 12, 31, intval($_POST['year_endYear']));
            } else {
                // 月走势
                $query_type = 'month';
                $start_time = TimeHelper::local_mktime(0, 0, 0, intval($_POST['month_beginMonth']), 1, intval($_POST['month_beginYear']));
                $end_time = TimeHelper::local_mktime(23, 59, 59, intval($_POST['month_endMonth']), 1, intval($_POST['month_endYear']));
                $end_time = TimeHelper::local_mktime(23, 59, 59, intval($_POST['month_endMonth']), date('t', $end_time), intval($_POST['month_endYear']));
            }
        }

        // 分组统计订单数和销售额：已发货时间为准
        $format = ($query_type === 'year') ? '%Y' : '%Y-%m';

        $data_list = DB::table('order_info')
            ->selectRaw("DATE_FORMAT(FROM_UNIXTIME(shipping_time), '$format') AS period, COUNT(*) AS order_count, SUM(goods_amount + shipping_fee + insure_fee + pay_fee + pack_fee + card_fee - discount) AS order_amount")
            ->where(function ($query) {
                $query->where('order_status', OS_CONFIRMED)
                    ->orWhere('order_status', '>=', OS_SPLITED);
            })
            ->where(function ($query) {
                $query->where('pay_status', PS_PAYED)
                    ->orWhere('pay_status', PS_PAYING);
            })
            ->where(function ($query) {
                $query->where('shipping_status', SS_SHIPPED)
                    ->orWhere('shipping_status', SS_RECEIVED);
            })
            ->where('shipping_time', '>=', $start_time)
            ->where('shipping_time', '<=', $end_time)
            ->groupBy('period')
            ->get()
            ->map(function ($item) {
                return (array) $item;
            })
            ->toArray();

        /**
         * 显示统计信息
         */
        if ($action === 'list') {
            // 赋值查询时间段
            $this->assign('start_time', TimeHelper::local_date('Y-m-d', $start_time));
            $this->assign('end_time', TimeHelper::local_date('Y-m-d', $end_time));

            // 赋值统计数据
            $xml = "<chart caption='' xAxisName='%s' showValues='0' decimals='0' formatNumberScale='0'>%s</chart>";
            $set = "<set label='%s' value='%s' />";
            $i = 0;
            $data_count = '';
            $data_amount = '';
            foreach ($data_list as $data) {
                $data_count .= sprintf($set, $data['period'], $data['order_count'], MainHelper::chart_color($i));
                $data_amount .= sprintf($set, $data['period'], $data['order_amount'], MainHelper::chart_color($i));
                $i++;
            }

            $this->assign('data_count', sprintf($xml, '', $data_count)); // 订单数统计数据
            $this->assign('data_amount', sprintf($xml, '', $data_amount));    // 销售额统计数据

            $this->assign('data_count_name', lang('order_count_trend'));
            $this->assign('data_amount_name', lang('order_amount_trend'));

            // 根据查询类型生成文件名
            if ($query_type === 'year') {
                $filename = date('Y', $start_time).'_'.date('Y', $end_time).'_report';
            } else {
                $filename = date('Ym', $start_time).'_'.date('Ym', $end_time).'_report';
            }
            $this->assign(
                'action_link',
                [
                    'text' => lang('down_sales_stats'),
                    'href' => 'sale_general.php?act=download&filename='.$filename.
                        '&query_type='.$query_type.'&start_time='.$start_time.'&end_time='.$end_time,
                ]
            );

            $this->assign('ur_here', lang('report_sell'));

            return $this->display('sale_general');
        }

        /**
         * 下载EXCEL报表
         */
        if ($action === 'download') {
            // 文件名
            $filename = ! empty($_REQUEST['filename']) ? trim($_REQUEST['filename']) : '';

            header('Content-type: application/vnd.ms-excel; charset=utf-8');
            header("Content-Disposition: attachment; filename=$filename.xls");

            // 文件标题
            echo BaseHelper::ecs_iconv(EC_CHARSET, 'GB2312', $filename.lang('sales_statistics'))."\t\n";

            // 订单数量, 销售出商品数量, 销售金额
            echo BaseHelper::ecs_iconv(EC_CHARSET, 'GB2312', lang('period'))."\t";
            echo BaseHelper::ecs_iconv(EC_CHARSET, 'GB2312', lang('order_count_trend'))."\t";
            echo BaseHelper::ecs_iconv(EC_CHARSET, 'GB2312', lang('order_amount_trend'))."\t\n";

            foreach ($data_list as $data) {
                echo BaseHelper::ecs_iconv(EC_CHARSET, 'GB2312', $data['period'])."\t";
                echo BaseHelper::ecs_iconv(EC_CHARSET, 'GB2312', $data['order_count'])."\t";
                echo BaseHelper::ecs_iconv(EC_CHARSET, 'GB2312', $data['order_amount'])."\t";
                echo "\n";
            }
        }
    }
}
