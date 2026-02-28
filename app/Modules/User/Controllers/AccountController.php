<?php

declare(strict_types=1);

namespace App\Modules\User\Controllers;

use App\Helpers\ClipsHelper;
use App\Helpers\CommonHelper;
use App\Helpers\MainHelper;
use App\Helpers\OrderHelper;
use App\Helpers\TimeHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class AccountController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        // 会员退款申请界面
        if ($action === 'account_raply') {
            return $this->display('user_transaction');
        }

        // 会员预付款界面
        if ($action === 'account_deposit') {
            $surplus_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
            $account = ClipsHelper::get_surplus_info($surplus_id);

            $this->assign('payment', ClipsHelper::get_online_payment_list(false));
            $this->assign('order', $account);

            return $this->display('user_transaction');
        }

        // 会员账目明细界面
        if ($action === 'account_detail') {
            $page = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;

            $account_type = 'user_money';

            // 获取记录条数
            $record_count = DB::table('user_account_log')
                ->where('user_id', $this->getUserId())
                ->where($account_type, '<>', 0)
                ->count();

            // 分页函数
            $pager = MainHelper::get_pager('user.php', ['act' => $action], $record_count, $page);

            // 获取剩余余额
            $surplus_amount = ClipsHelper::get_user_surplus($this->getUserId());
            if (empty($surplus_amount)) {
                $surplus_amount = 0;
            }

            // 获取余额记录
            $account_log = [];
            $res = DB::table('user_account_log')
                ->where('user_id', $this->getUserId())
                ->where($account_type, '<>', 0)
                ->orderBy('log_id', 'DESC')
                ->offset($pager['start'])
                ->limit($pager['size'])
                ->get()
                ->map(fn ($item) => (array) $item)
                ->all();
            foreach ($res as $row) {
                $row['change_time'] = TimeHelper::local_date(cfg('date_format'), $row['change_time']);
                $row['type'] = $row[$account_type] > 0 ? lang('account_inc') : lang('account_dec');
                $row['user_money'] = CommonHelper::price_format(abs($row['user_money']), false);
                $row['frozen_money'] = CommonHelper::price_format(abs($row['frozen_money']), false);
                $row['rank_points'] = abs($row['rank_points']);
                $row['pay_points'] = abs($row['pay_points']);
                $row['short_change_desc'] = Str::substr($row['change_desc'], 60);
                $row['amount'] = $row[$account_type];
                $account_log[] = $row;
            }

            // 模板赋值
            $this->assign('surplus_amount', CommonHelper::price_format($surplus_amount, false));
            $this->assign('account_log', $account_log);
            $this->assign('pager', $pager);

            return $this->display('user_transaction');
        }

        // 会员充值和提现申请记录
        if ($action === 'account_log') {
            $page = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;

            // 获取记录条数
            $record_count = DB::table('user_account')
                ->where('user_id', $this->getUserId())
                ->whereIn('process_type', [SURPLUS_SAVE, SURPLUS_RETURN])
                ->count();

            // 分页函数
            $pager = MainHelper::get_pager('user.php', ['act' => $action], $record_count, $page);

            // 获取剩余余额
            $surplus_amount = ClipsHelper::get_user_surplus($this->getUserId());
            if (empty($surplus_amount)) {
                $surplus_amount = 0;
            }

            // 获取余额记录
            $account_log = ClipsHelper::get_account_log($this->getUserId(), $pager['size'], $pager['start']);

            // 模板赋值
            $this->assign('surplus_amount', CommonHelper::price_format($surplus_amount, false));
            $this->assign('account_log', $account_log);
            $this->assign('pager', $pager);

            return $this->display('user_transaction');
        }

        // 对会员余额申请的处理
        if ($action === 'act_account') {
            $amount = isset($_POST['amount']) ? floatval($_POST['amount']) : 0;
            if ($amount <= 0) {
                $this->show_message(lang('amount_gt_zero'));
            }

            // 变量初始化
            $surplus = [
                'user_id' => $this->getUserId(),
                'rec_id' => ! empty($_POST['rec_id']) ? intval($_POST['rec_id']) : 0,
                'process_type' => isset($_POST['surplus_type']) ? intval($_POST['surplus_type']) : 0,
                'payment_id' => isset($_POST['payment_id']) ? intval($_POST['payment_id']) : 0,
                'user_note' => isset($_POST['user_note']) ? trim($_POST['user_note']) : '',
                'amount' => $amount,
            ];

            // 退款申请的处理
            if ($surplus['process_type'] === 1) {
                // 判断是否有足够的余额的进行退款的操作
                $sur_amount = ClipsHelper::get_user_surplus($this->getUserId());
                if ($amount > $sur_amount) {
                    $content = lang('surplus_amount_error');
                    $this->show_message($content, lang('back_page_up'), '', 'info');
                }

                // 插入会员账目明细
                $amount = '-'.$amount;
                $surplus['payment'] = '';
                $surplus['rec_id'] = ClipsHelper::insert_user_account($surplus, $amount);

                // 如果成功提交
                if ($surplus['rec_id'] > 0) {
                    $content = lang('surplus_appl_submit');
                    $this->show_message($content, lang('back_account_log'), 'user.php?act=account_log', 'info');
                } else {
                    $content = lang('process_false');
                    $this->show_message($content, lang('back_page_up'), '', 'info');
                }
            } // 如果是会员预付款，跳转到下一步，进行线上支付的操作
            else {
                if ($surplus['payment_id'] <= 0) {
                    $this->show_message(lang('select_payment_pls'));
                }

                // 获取支付方式名称
                $payment_info = [];
                $payment_info = OrderHelper::payment_info($surplus['payment_id']);
                $surplus['payment'] = $payment_info['pay_name'];

                if ($surplus['rec_id'] > 0) {
                    // 更新会员账目明细
                    $surplus['rec_id'] = ClipsHelper::update_user_account($surplus);
                } else {
                    // 插入会员账目明细
                    $surplus['rec_id'] = ClipsHelper::insert_user_account($surplus, $amount);
                }

                // 取得支付信息，生成支付代码
                $payment = OrderHelper::unserialize_config($payment_info['pay_config']);

                // 生成伪订单号, 不足的时候补0
                $order = [];
                $order['order_sn'] = $surplus['rec_id'];
                $order['user_name'] = Session::get('user_name', '');
                $order['surplus_amount'] = $amount;

                // 计算支付手续费用
                $payment_info['pay_fee'] = OrderHelper::pay_fee($surplus['payment_id'], $order['surplus_amount'], 0);

                // 计算此次预付款需要支付的总金额
                $order['order_amount'] = $amount + $payment_info['pay_fee'];

                // 记录支付log
                $order['log_id'] = ClipsHelper::insert_pay_log($surplus['rec_id'], $order['order_amount'], $type = PAY_SURPLUS, 0);

                // 调用相应的支付方式文件
                include_once ROOT_PATH.'includes/modules/payment/'.$payment_info['pay_code'].'.php';

                // 取得在线支付方式的支付按钮
                $pay_obj = new $payment_info['pay_code'];
                $payment_info['pay_button'] = $pay_obj->get_code($order, $payment);

                $this->assign('payment', $payment_info);
                $this->assign('pay_fee', CommonHelper::price_format($payment_info['pay_fee'], false));
                $this->assign('amount', CommonHelper::price_format($amount, false));
                $this->assign('order', $order);

                return $this->display('user_transaction');
            }
        }

        // 删除会员余额
        if ($action === 'cancel') {
            $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
            if ($id === 0 || $this->getUserId() === 0) {
                return response()->redirectTo('user.php?act=account_log');
            }

            $result = ClipsHelper::del_user_account($id, $this->getUserId());
            if ($result) {
                return response()->redirectTo('user.php?act=account_log');
            }
        }

        // 会员通过帐目明细列表进行再付款的操作
        if ($action === 'pay') {
            // 变量初始化
            $surplus_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
            $payment_id = isset($_GET['pid']) ? intval($_GET['pid']) : 0;

            if ($surplus_id === 0) {
                return response()->redirectTo('user.php?act=account_log');
            }

            // 如果原来的支付方式已禁用或者已删除, 重新选择支付方式
            if ($payment_id === 0) {
                return response()->redirectTo('user.php?act=account_deposit&id='.$surplus_id);
            }

            // 获取单条会员帐目信息
            $order = [];
            $order = ClipsHelper::get_surplus_info($surplus_id);

            // 支付方式的信息
            $payment_info = [];
            $payment_info = OrderHelper::payment_info($payment_id);

            // 如果当前支付方式没有被禁用，进行支付的操作
            if (! empty($payment_info)) {
                // 取得支付信息，生成支付代码
                $payment = OrderHelper::unserialize_config($payment_info['pay_config']);

                // 生成伪订单号
                $order['order_sn'] = $surplus_id;

                // 获取需要支付的log_id
                $order['log_id'] = ClipsHelper::get_paylog_id($surplus_id, $pay_type = PAY_SURPLUS);

                $order['user_name'] = Session::get('user_name', '');
                $order['surplus_amount'] = $order['amount'];

                // 计算支付手续费用
                $payment_info['pay_fee'] = OrderHelper::pay_fee($payment_id, $order['surplus_amount'], 0);

                // 计算此次预付款需要支付的总金额
                $order['order_amount'] = $order['surplus_amount'] + $payment_info['pay_fee'];

                // 如果支付费用改变了，也要相应的更改pay_log表的order_amount
                $order_amount = DB::table('order_pay')->where('log_id', $order['log_id'])->value('order_amount');
                if ($order_amount != $order['order_amount']) {
                    DB::table('order_pay')->where('log_id', $order['log_id'])->update(['order_amount' => $order['order_amount']]);
                }

                // 调用相应的支付方式文件
                include_once ROOT_PATH.'includes/modules/payment/'.$payment_info['pay_code'].'.php';

                // 取得在线支付方式的支付按钮
                $pay_obj = new $payment_info['pay_code'];
                $payment_info['pay_button'] = $pay_obj->get_code($order, $payment);

                $this->assign('payment', $payment_info);
                $this->assign('order', $order);
                $this->assign('pay_fee', CommonHelper::price_format($payment_info['pay_fee'], false));
                $this->assign('amount', CommonHelper::price_format($order['surplus_amount'], false));
                $this->assign('action', 'act_account');

                return $this->display('user_transaction');
            } // 重新选择支付方式
            else {
                $this->assign('payment', ClipsHelper::get_online_payment_list());
                $this->assign('order', $order);
                $this->assign('action', 'account_deposit');

                return $this->display('user_transaction');
            }
        }
    }
}
