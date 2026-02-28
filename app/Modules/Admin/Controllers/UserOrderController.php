<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Helpers\BaseHelper;
use App\Helpers\CommonHelper;
use App\Helpers\OrderHelper;
use App\Helpers\TimeHelper;
use App\Modules\Admin\Helpers\MainHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserOrderController extends BaseController
{
    public function index(Request $request)
    {
        lang([dirname(__DIR__).'/Languages/zh-CN/statistic.php']);

        $action = $request->get('act');

        if ($action === 'query' || $action === 'download') {
            $this->check_authz_json('client_flow_stats');
            if (strstr($_REQUEST['start_date'], '-') === false) {
                $_REQUEST['start_date'] = TimeHelper::local_date('Y-m-d', $_REQUEST['start_date']);
                $_REQUEST['end_date'] = TimeHelper::local_date('Y-m-d', $_REQUEST['end_date']);
            }

            if ($action === 'download') {
                $user_orderinfo = $this->get_user_orderinfo(false);
                $filename = $_REQUEST['start_date'].'_'.$_REQUEST['end_date'].'users_order';

                header('Content-type: application/vnd.ms-excel; charset=utf-8');
                header("Content-Disposition: attachment; filename=$filename.xls");

                $data = lang('visit_buy')."\t\n";
                $data .= lang('order_by')."\t".lang('member_name')."\t".lang('order_amount')."\t".lang('buy_sum')."\t\n";

                foreach ($user_orderinfo['user_orderinfo'] as $k => $row) {
                    $order_by = $k + 1;
                    $data .= "$order_by\t$row[user_name]\t$row[order_num]\t$row[turnover]\n";
                }
                echo BaseHelper::ecs_iconv(EC_CHARSET, 'GB2312', $data);
                exit;
            }
            $user_orderinfo = $this->get_user_orderinfo();
            $this->assign('filter', $user_orderinfo['filter']);
            $this->assign('record_count', $user_orderinfo['record_count']);
            $this->assign('page_count', $user_orderinfo['page_count']);
            $this->assign('user_orderinfo', $user_orderinfo['user_orderinfo']);

            $sort_flag = MainHelper::sort_flag($user_orderinfo['filter']);
            $this->assign($sort_flag['tag'], $sort_flag['img']);

            return $this->make_json_result($this->fetch('users_order'), '', ['filter' => $user_orderinfo['filter'], 'page_count' => $user_orderinfo['page_count']]);
        }

        if ($action === 'list') {
            // 权限判断
            $this->admin_priv('client_flow_stats');
            // 时间参数
            if (! isset($_REQUEST['start_date'])) {
                $start_date = TimeHelper::local_strtotime('-7 days');
            }
            if (! isset($_REQUEST['end_date'])) {
                $end_date = TimeHelper::local_strtotime('today');
            }

            // 取得会员排行数据
            $user_orderinfo = $this->get_user_orderinfo();

            // 赋值到模板
            $this->assign('ur_here', lang('report_users'));
            $this->assign('action_link', ['text' => lang('download_amount_sort'), 'href' => '#download']);
            $this->assign('filter', $user_orderinfo['filter']);
            $this->assign('record_count', $user_orderinfo['record_count']);
            $this->assign('page_count', $user_orderinfo['page_count']);
            $this->assign('user_orderinfo', $user_orderinfo['user_orderinfo']);
            $this->assign('full_page', 1);
            $this->assign('start_date', TimeHelper::local_date('Y-m-d', $start_date));
            $this->assign('end_date', TimeHelper::local_date('Y-m-d', $end_date));

            return $this->display('users_order');
        }
    }

    /*
     * 取得会员订单量/购物额排名统计数据
     * @param   bool  $is_pagination  是否分页
     * @return  array   取得会员订单量/购物额排名统计数据
     */
    private function get_user_orderinfo($is_pagination = true)
    {
        $start_date = TimeHelper::local_strtotime('-7 days');
        $end_date = TimeHelper::local_strtotime('today');

        $filter['start_date'] = empty($_REQUEST['start_date']) ? $start_date : TimeHelper::local_strtotime($_REQUEST['start_date']);
        $filter['end_date'] = empty($_REQUEST['end_date']) ? $end_date : TimeHelper::local_strtotime($_REQUEST['end_date']);
        $filter['sort_by'] = empty($_REQUEST['sort_by']) ? 'order_num' : trim($_REQUEST['sort_by']);
        $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);

        $query = DB::table('user as u')
            ->join('order_info as o', 'u.user_id', '=', 'o.user_id')
            ->where('u.user_id', '>', 0)
            ->whereRaw('1 '.order_query_sql('finished', 'o.'));

        if ($filter['start_date']) {
            $query->where('o.add_time', '>=', $filter['start_date']);
        }
        if ($filter['end_date']) {
            $query->where('o.add_time', '<=', $filter['end_date']);
        }

        $filter['record_count'] = $query->distinct()->count('u.user_id');
        // 分页大小
        $filter = MainHelper::page_and_size($filter);

        // 计算订单各种费用之和的语句
        $total_fee = ' SUM('.OrderHelper::order_amount_field().') AS turnover ';

        $query->select('u.user_id', 'u.user_name', DB::raw('COUNT(*) AS order_num'), DB::raw($total_fee))
            ->groupBy('u.user_id')
            ->orderBy($filter['sort_by'], $filter['sort_order']);

        if ($is_pagination) {
            $query->limit($filter['page_size'])->offset($filter['start']);
        }

        $res = $query->get()->map(function ($item) {
            return (array) $item;
        })->toArray();

        foreach ($res as $items) {
            $items['turnover'] = CommonHelper::price_format($items['turnover']);
            $user_orderinfo[] = $items;
        }
        $arr = ['user_orderinfo' => $user_orderinfo, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']];

        return $arr;
    }
}
