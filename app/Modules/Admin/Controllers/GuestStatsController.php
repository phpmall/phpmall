<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Helpers\BaseHelper;
use App\Helpers\CommonHelper;
use App\Helpers\OrderHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GuestStatsController extends BaseController
{
    public function index(Request $request)
    {
        lang([dirname(__DIR__).'/Languages/zh-CN/statistic.php']);

        $action = $request->get('act');

        /**
         * 客户统计列表
         */
        if ($action === 'list') {
            // 权限判断
            $this->admin_priv('client_flow_stats');

            // 取得会员总数
            $user_num = DB::table('user')->count();

            // 计算订单各种费用之和的语句
            $total_fee = ' SUM('.OrderHelper::order_amount_field().') AS turnover ';

            // 有过订单的会员数
            $have_order_usernum = DB::table('order_info')
                ->where('user_id', '>', 0)
                ->whereRaw('1 '.order_query_sql('finished'))
                ->distinct()
                ->count('user_id');

            // 会员订单总数和订单总购物额
            $user_all_order = DB::table('order_info')
                ->selectRaw('COUNT(*) AS order_num, SUM('.OrderHelper::order_amount_field().') AS turnover')
                ->where('user_id', '>', 0)
                ->whereRaw('1 '.order_query_sql('finished'))
                ->first();
            $user_all_order = $user_all_order ? (array) $user_all_order : ['order_num' => 0, 'turnover' => 0];
            $user_all_order['turnover'] = floatval($user_all_order['turnover']);

            // 匿名会员订单总数和总购物额
            $guest_all_order = DB::table('order_info')
                ->selectRaw('COUNT(*) AS order_num, SUM('.OrderHelper::order_amount_field().') AS turnover')
                ->where('user_id', 0)
                ->whereRaw('1 '.order_query_sql('finished'))
                ->first();
            $guest_all_order = $guest_all_order ? (array) $guest_all_order : ['order_num' => 0, 'turnover' => 0];

            // 匿名会员平均订单额: 购物总额/订单数
            $guest_order_amount = ($guest_all_order['order_num'] > 0) ? floatval($guest_all_order['turnover'] / $guest_all_order['order_num']) : '0.00';

            $_GET['flag'] = isset($_GET['flag']) ? 'download' : '';
            if ($_GET['flag'] === 'download') {
                $filename = BaseHelper::ecs_iconv(EC_CHARSET, 'GB2312', lang('guest_statistics'));

                header('Content-type: application/vnd.ms-excel; charset=utf-8');
                header("Content-Disposition: attachment; filename=$filename.xls");

                // 生成会员购买率
                $data = lang('percent_buy_member')."\t\n";
                $data .= lang('member_count')."\t".lang('order_member_count')."\t".
                    lang('member_order_count')."\t".lang('percent_buy_member')."\n";

                $data .= $user_num."\t".$have_order_usernum."\t".
                    $user_all_order['order_num']."\t".sprintf('%0.2f', ($user_num > 0 ? $have_order_usernum / $user_num : 0) * 100)."\n\n";

                // 每会员平均订单数及购物额
                $data .= lang('order_turnover_peruser')."\t\n";

                $data .= lang('member_sum')."\t".lang('average_member_order')."\t".
                    lang('member_order_sum')."\n";

                $ave_user_ordernum = $user_num > 0 ? sprintf('%0.2f', $user_all_order['order_num'] / $user_num) : 0;
                $ave_user_turnover = $user_num > 0 ? CommonHelper::price_format($user_all_order['turnover'] / $user_num) : 0;

                $data .= CommonHelper::price_format($user_all_order['turnover'])."\t".$ave_user_ordernum."\t".$ave_user_turnover."\n\n";

                // 每会员平均订单数及购物额
                $data .= lang('order_turnover_percus')."\t\n";
                $data .= lang('guest_member_orderamount')."\t".lang('guest_member_ordercount')."\t".
                    lang('guest_order_sum')."\n";

                $order_num = $guest_all_order['order_num'] > 0 ? CommonHelper::price_format($guest_all_order['turnover'] / $guest_all_order['order_num']) : 0;
                $data .= CommonHelper::price_format($guest_all_order['turnover'])."\t".$guest_all_order['order_num']."\t".
                    $order_num;

                return BaseHelper::ecs_iconv(EC_CHARSET, 'GB2312', $data)."\t";
            }

            // 赋值到模板
            $this->assign('user_num', $user_num);                    // 会员总数
            $this->assign('have_order_usernum', $have_order_usernum);          // 有过订单的会员数
            $this->assign('user_order_turnover', $user_all_order['order_num']); // 会员总订单数
            $this->assign('user_all_turnover', CommonHelper::price_format($user_all_order['turnover']));  // 会员购物总额
            $this->assign('guest_all_turnover', CommonHelper::price_format($guest_all_order['turnover'])); // 匿名会员购物总额
            $this->assign('guest_order_num', $guest_all_order['order_num']);              // 匿名会员订单总数

            // 每会员订单数
            $this->assign('ave_user_ordernum', $user_num > 0 ? sprintf('%0.2f', $user_all_order['order_num'] / $user_num) : 0);

            // 每会员购物额
            $this->assign('ave_user_turnover', $user_num > 0 ? CommonHelper::price_format($user_all_order['turnover'] / $user_num) : 0);

            // 注册会员购买率
            $this->assign('user_ratio', sprintf('%0.2f', ($user_num > 0 ? $have_order_usernum / $user_num : 0) * 100));

            // 匿名会员平均订单额
            $this->assign('guest_order_amount', $guest_all_order['order_num'] > 0 ? CommonHelper::price_format($guest_all_order['turnover'] / $guest_all_order['order_num']) : 0);

            $this->assign('all_order', $user_all_order);    // 所有订单总数以及所有购物总额
            $this->assign('ur_here', lang('report_guest'));

            $this->assign('action_link', [
                'text' => lang('down_guest_stats'),
                'href' => 'guest_stats.php?flag=download',
            ]);

            return $this->display('guest_stats');
        }
    }
}
