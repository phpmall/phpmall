<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Helpers\BaseHelper;
use App\Helpers\CommonHelper;
use App\Helpers\TimeHelper;
use App\Modules\Admin\Helpers\MainHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserAccountManageController extends BaseController
{
    public function index(Request $request)
    {
        lang([dirname(__DIR__).'/Languages/zh-CN/statistic.php']);

        $action = $request->get('act');

        // 权限判断
        $this->admin_priv('user_account_manage');

        /**
         *数据查询
         */
        // 时间参数

        $start_date = $end_date = '';
        if ($request->isMethod('post') && ! empty($request->all())) {
            $start_date = TimeHelper::local_strtotime($request->input('start_date'));
            $end_date = TimeHelper::local_strtotime($request->input('end_date'));
        } elseif ($request->has('start_date') && $request->has('end_date')) {
            $start_date = TimeHelper::local_strtotime($request->input('start_date'));
            $end_date = TimeHelper::local_strtotime($request->input('end_date'));
        } else {
            $today = TimeHelper::local_strtotime(TimeHelper::local_date('Y-m-d'));
            $start_date = $today - 86400 * 7;
            $end_date = $today;
        }

        /**
         *商品明细列表
         */
        if ($action === 'list') {
            $account = $money_list = [];
            $account['voucher_amount'] = $this->get_total_amount($start_date, $end_date); // 充值总额
            $account['to_cash_amount'] = $this->get_total_amount($start_date, $end_date, 1); // 提现总额

            $money_list = DB::table('user_account_log')
                ->selectRaw('IFNULL(SUM(user_money), 0) AS user_money, IFNULL(SUM(frozen_money), 0) AS frozen_money')
                ->where('change_time', '>=', $start_date)
                ->where('change_time', '<', $end_date + 86400)
                ->first();
            $money_list = (array) $money_list;
            $account['user_money'] = CommonHelper::price_format($money_list['user_money']);   // 用户可用余额
            $account['frozen_money'] = CommonHelper::price_format($money_list['frozen_money']);   // 用户冻结金额

            $money_list = DB::table('order_info')
                ->selectRaw('IFNULL(SUM(surplus), 0) AS surplus, IFNULL(SUM(integral_money), 0) AS integral_money')
                ->where('add_time', '>=', $start_date)
                ->where('add_time', '<', $end_date + 86400)
                ->first();
            $money_list = (array) $money_list;

            $account['surplus'] = CommonHelper::price_format($money_list['surplus']);   // 交易使用余额
            $account['integral_money'] = CommonHelper::price_format($money_list['integral_money']);   // 积分使用余额

            // 赋值到模板
            $this->assign('account', $account);
            $this->assign('start_date', TimeHelper::local_date('Y-m-d', $start_date));
            $this->assign('end_date', TimeHelper::local_date('Y-m-d', $end_date));
            $this->assign('ur_here', lang('user_account_manage'));

            return $this->display('user_account_manage');
        }

        if ($action === 'surplus') {
            $order_list = $this->order_list($request, $start_date, $end_date);

            // 赋值到模板
            $this->assign('order_list', $order_list['order_list']);
            $this->assign('ur_here', lang('order_by_surplus'));
            $this->assign('filter', $order_list['filter']);
            $this->assign('record_count', $order_list['record_count']);
            $this->assign('page_count', $order_list['page_count']);
            $this->assign('full_page', 1);
            $this->assign('action_link', ['text' => lang('user_account_manage'), 'href' => 'user_account_manage.php?act=list&start_date='.TimeHelper::local_date('Y-m-d', $start_date).'&end_date='.TimeHelper::local_date('Y-m-d', $end_date)]);

            return $this->display('order_surplus_list');
        }

        /**
         * ajax返回用户列表
         */
        if ($action === 'query') {
            $order_list = $this->order_list($request, $start_date, $end_date);

            $this->assign('order_list', $order_list['order_list']);
            $this->assign('filter', $order_list['filter']);
            $this->assign('record_count', $order_list['record_count']);
            $this->assign('page_count', $order_list['page_count']);

            $sort_flag = MainHelper::sort_flag($order_list['filter']);
            $this->assign($sort_flag['tag'], $sort_flag['img']);

            return $this->make_json_result($this->fetch('order_surplus_list'), '', ['filter' => $order_list['filter'], 'page_count' => $order_list['page_count']]);
        }
    }

    /**
     * 获得账户变动金额
     *
     * @param  int  $type  0,充值 1,提现
     */
    private function get_total_amount($start_date, $end_date, $type = 0): string
    {
        $amount = DB::table('user_account as a')
            ->join('user as u', 'a.user_id', '=', 'u.user_id')
            ->where('process_type', $type)
            ->where('is_paid', 1)
            ->where('paid_time', '>=', $start_date)
            ->where('paid_time', '<', $end_date + 86400)
            ->sum('amount');
        $amount = $type ? CommonHelper::price_format(abs($amount)) : CommonHelper::price_format($amount);

        return $amount;
    }

    /**
     *  返回用户订单列表数据
     */
    private function order_list(Request $request, $start_date, $end_date): array
    {
        $result = MainHelper::get_filter();

        if ($result === false) {
            // 过滤条件
            $filter['keywords'] = $request->has('keywords') ? trim($request->input('keywords')) : '';
            if ($request->has('is_ajax') && $request->input('is_ajax') == 1) {
                $filter['keywords'] = BaseHelper::json_str_iconv($filter['keywords']);
            }

            $filter['sort_by'] = $request->has('sort_by') ? trim($request->input('sort_by')) : 'order_id';
            $filter['sort_order'] = $request->has('sort_order') ? trim($request->input('sort_order')) : 'DESC';
            $filter['start_date'] = TimeHelper::local_date('Y-m-d', $start_date);
            $filter['end_date'] = TimeHelper::local_date('Y-m-d', $end_date);

            $filter['record_count'] = DB::table('order_info as o')
                ->join('user as u', 'o.user_id', '=', 'u.user_id')
                ->where(function ($query) use ($filter) {
                    if ($filter['keywords']) {
                        $query->where('user_name', 'LIKE', '%'.BaseHelper::mysql_like_quote($filter['keywords']).'%');
                    }
                })
                ->where(function ($query) {
                    $query->where('o.surplus', '!=', 0)
                        ->orWhere('integral_money', '!=', 0);
                })
                ->where('add_time', '>=', $start_date)
                ->where('add_time', '<', $end_date + 86400)
                ->count();

            // 分页大小
            $filter = MainHelper::page_and_size($filter);

            $filter['keywords'] = stripslashes($filter['keywords']);
            MainHelper::set_filter($filter, '');
        } else {
            $sql = $result['sql'];
            $filter = $result['filter'];
        }

        $order_list = DB::table('order_info as o')
            ->join('user as u', 'o.user_id', '=', 'u.user_id')
            ->select('o.order_id', 'o.order_sn', 'u.user_name', 'o.surplus', 'o.integral_money', 'o.add_time')
            ->where(function ($query) use ($filter) {
                if ($filter['keywords']) {
                    $query->where('user_name', 'LIKE', '%'.BaseHelper::mysql_like_quote($filter['keywords']).'%');
                }
            })
            ->where(function ($query) {
                $query->where('o.surplus', '!=', 0)
                    ->orWhere('integral_money', '!=', 0);
            })
            ->where('add_time', '>=', $start_date)
            ->where('add_time', '<', $end_date + 86400)
            ->orderBy($filter['sort_by'], $filter['sort_order'])
            ->offset($filter['start'])
            ->limit($filter['page_size'])
            ->get()
            ->map(function ($item) {
                return (array) $item;
            })
            ->toArray();

        $count = count($order_list);
        for ($i = 0; $i < $count; $i++) {
            $order_list[$i]['add_time'] = TimeHelper::local_date(cfg('date_format'), $order_list[$i]['add_time']);
        }

        $arr = [
            'order_list' => $order_list,
            'filter' => $filter,
            'page_count' => $filter['page_count'],
            'record_count' => $filter['record_count'],
        ];

        return $arr;
    }
}
