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
use Illuminate\Support\Facades\Session;

class UserAccountController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        /**
         * 会员余额记录列表
         */
        if ($action === 'list') {
            // 权限判断
            $this->admin_priv('surplus_manage');

            // 指定会员的ID为查询条件
            $user_id = ! empty($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;

            // 获得支付方式列表
            $payment = [];
            $res = DB::table('payment')
                ->where('enabled', 1)
                ->where('pay_code', '!=', 'cod')
                ->orderBy('pay_id')
                ->select('pay_id', 'pay_name')
                ->get();

            foreach ($res as $row) {
                $row = (array) $row;
                $payment[$row['pay_name']] = $row['pay_name'];
            }

            if (isset($_REQUEST['process_type'])) {
                $this->assign('process_type_'.intval($_REQUEST['process_type']), 'selected="selected"');
            }
            if (isset($_REQUEST['is_paid'])) {
                $this->assign('is_paid_'.intval($_REQUEST['is_paid']), 'selected="selected"');
            }
            $this->assign('ur_here', lang('09_user_account'));
            $this->assign('id', $user_id);
            $this->assign('payment_list', $payment);
            $this->assign('action_link', ['text' => lang('surplus_add'), 'href' => 'user_account.php?act=add']);

            $list = $this->account_list();
            $this->assign('list', $list['list']);
            $this->assign('filter', $list['filter']);
            $this->assign('record_count', $list['record_count']);
            $this->assign('page_count', $list['page_count']);
            $this->assign('full_page', 1);

            return $this->display('user_account_list');
        }

        /**
         * 添加/编辑会员余额页面
         */
        if ($action === 'add' || $action === 'edit') {
            $this->admin_priv('surplus_manage'); // 权限判断

            $ur_here = ($action === 'add') ? lang('surplus_add') : lang('surplus_edit');
            $form_act = ($action === 'add') ? 'insert' : 'update';
            $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

            // 获得支付方式列表, 不包括“货到付款”
            $user_account = [];
            $payment = [];
            $res = DB::table('payment')
                ->where('enabled', 1)
                ->where('pay_code', '!=', 'cod')
                ->orderBy('pay_id')
                ->select('pay_id', 'pay_name')
                ->get();

            foreach ($res as $row) {
                $row = (array) $row;
                $payment[$row['pay_name']] = $row['pay_name'];
            }

            if ($action === 'edit') {
                // 取得余额信息
                $user_account = (array) DB::table('user_account')->where('id', $id)->first();

                // 如果是负数，去掉前面的符号
                $user_account['amount'] = str_replace('-', '', $user_account['amount']);

                // 取得会员名称
                $user_name = DB::table('user')->where('user_id', $user_account['user_id'])->value('user_name');
            } else {
                $surplus_type = '';
                $user_name = '';
            }

            $this->assign('ur_here', $ur_here);
            $this->assign('form_act', $form_act);
            $this->assign('payment_list', $payment);
            $this->assign('action', $_REQUEST['act']);
            $this->assign('user_surplus', $user_account);
            $this->assign('user_name', $user_name);
            if ($action === 'add') {
                $href = 'user_account.php?act=list';
            } else {
                $href = 'user_account.php?act=list&'.MainHelper::list_link_postfix();
            }
            $this->assign('action_link', ['href' => $href, 'text' => lang('09_user_account')]);

            return $this->display('user_account_info');
        }

        /**
         * 添加/编辑会员余额的处理部分
         */
        if ($action === 'insert' || $action === 'update') {
            // 权限判断
            $this->admin_priv('surplus_manage');

            // 初始化变量
            $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
            $is_paid = ! empty($_POST['is_paid']) ? intval($_POST['is_paid']) : 0;
            $amount = ! empty($_POST['amount']) ? floatval($_POST['amount']) : 0;
            $process_type = ! empty($_POST['process_type']) ? intval($_POST['process_type']) : 0;
            $user_name = ! empty($_POST['user_id']) ? trim($_POST['user_id']) : '';
            $admin_note = ! empty($_POST['admin_note']) ? trim($_POST['admin_note']) : '';
            $user_note = ! empty($_POST['user_note']) ? trim($_POST['user_note']) : '';
            $payment = ! empty($_POST['payment']) ? trim($_POST['payment']) : '';

            $user_id = DB::table('user')->where('user_name', $user_name)->value('user_id');

            // 此会员是否存在
            if ($user_id === 0) {
                $link[] = ['text' => lang('go_back'), 'href' => 'javascript:history.back(-1)'];

                return $this->sys_msg(lang('username_not_exist'), 0, $link);
            }

            // 退款，检查余额是否足够
            if ($process_type === 1) {
                $user_account = $this->get_user_surplus($user_id);

                // 如果扣除的余额多于此会员拥有的余额，提示
                if ($amount > $user_account) {
                    $link[] = ['text' => lang('go_back'), 'href' => 'javascript:history.back(-1)'];

                    return $this->sys_msg(lang('surplus_amount_error'), 0, $link);
                }
            }

            if ($action === 'insert') {
                // 入库的操作
                if ($process_type === 1) {
                    $amount = (-1) * $amount;
                }
                $id = DB::table('user_account')->insertGetId([
                    'user_id' => $user_id,
                    'admin_user' => Session::get('admin_name'),
                    'amount' => $amount,
                    'add_time' => TimeHelper::gmtime(),
                    'paid_time' => TimeHelper::gmtime(),
                    'admin_note' => $admin_note,
                    'user_note' => $user_note,
                    'process_type' => $process_type,
                    'payment' => $payment,
                    'is_paid' => $is_paid,
                ]);
            } else {
                // 更新数据表
                DB::table('user_account')->where('id', $id)->update([
                    'admin_note' => $admin_note,
                    'user_note' => $user_note,
                    'payment' => $payment,
                ]);
            }

            // 更新会员余额数量
            if ($is_paid === 1) {
                $change_desc = $amount > 0 ? lang('surplus_type_0') : lang('surplus_type_1');
                $change_type = $amount > 0 ? ACT_SAVING : ACT_DRAWING;
                CommonHelper::log_account_change($user_id, $amount, 0, 0, 0, $change_desc, $change_type);
            }

            // 如果是预付款并且未确认，向pay_log插入一条记录
            if ($process_type === 0 && $is_paid === 0) {
                // 取支付方式信息
                $payment_info = (array) DB::table('payment')
                    ->where('pay_name', $payment)
                    ->where('enabled', 1)
                    ->first();
                // 计算支付手续费用
                $pay_fee = OrderHelper::pay_fee($payment_info['pay_id'], $amount, 0);
                $total_fee = $pay_fee + $amount;

                // 插入 pay_log
                DB::table('order_pay')->insert([
                    'order_id' => $id,
                    'order_amount' => $total_fee,
                    'order_type' => PAY_SURPLUS,
                    'is_paid' => 0,
                ]);
            }

            // 记录管理员操作
            if ($action === 'update') {
                $this->admin_log($user_name, 'edit', 'user_surplus');
            } else {
                $this->admin_log($user_name, 'add', 'user_surplus');
            }

            // 提示信息
            if ($action === 'insert') {
                $href = 'user_account.php?act=list';
            } else {
                $href = 'user_account.php?act=list&'.MainHelper::list_link_postfix();
            }
            $link[0]['text'] = lang('back_list');
            $link[0]['href'] = $href;

            $link[1]['text'] = lang('continue_add');
            $link[1]['href'] = 'user_account.php?act=add';

            return $this->sys_msg(lang('attradd_succed'), 0, $link);
        }

        /**
         * 审核会员余额页面
         */
        if ($action === 'check') {
            $this->admin_priv('surplus_manage');

            // 初始化
            $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

            // 如果参数不合法，返回
            if ($id === 0) {
                return response()->redirectTo('user_account.php?act=list');
            }

            // 查询当前的预付款信息
            $account = (array) DB::table('user_account')->where('id', $id)->first();
            $account['add_time'] = TimeHelper::local_date(cfg('time_format'), $account['add_time']);

            // 余额类型:预付款，退款申请，购买商品，取消订单
            if ($account['process_type'] === 0) {
                $process_type = lang('surplus_type_0');
            } elseif ($account['process_type'] === 1) {
                $process_type = lang('surplus_type_1');
            } elseif ($account['process_type'] === 2) {
                $process_type = lang('surplus_type_2');
            } else {
                $process_type = lang('surplus_type_3');
            }

            $user_name = DB::table('user')->where('user_id', $account['user_id'])->value('user_name');

            $this->assign('ur_here', lang('check'));
            $account['user_note'] = htmlspecialchars($account['user_note']);
            $this->assign('surplus', $account);
            $this->assign('process_type', $process_type);
            $this->assign('user_name', $user_name);
            $this->assign('id', $id);
            $this->assign('action_link', [
                'text' => lang('09_user_account'),
                'href' => 'user_account.php?act=list&'.MainHelper::list_link_postfix(),
            ]);

            // 页面显示

            return $this->display('user_account_check');
        }

        /**
         * 更新会员余额的状态
         */
        if ($action === 'action') {
            $this->admin_priv('surplus_manage');

            // 初始化
            $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
            $is_paid = isset($_POST['is_paid']) ? intval($_POST['is_paid']) : 0;
            $admin_note = isset($_POST['admin_note']) ? trim($_POST['admin_note']) : '';

            // 如果参数不合法，返回
            if ($id === 0 || empty($admin_note)) {
                return response()->redirectTo('user_account.php?act=list');
            }

            // 查询当前的预付款信息
            $account = (array) DB::table('user_account')->where('id', $id)->first();
            $amount = $account['amount'];

            // 如果状态为未确认
            if ($account['is_paid'] === 0) {
                // 如果是退款申请, 并且已完成,更新此条记录,扣除相应的余额
                if ($is_paid === '1' && $account['process_type'] === '1') {
                    $user_account = $this->get_user_surplus($account['user_id']);
                    $fmt_amount = str_replace('-', '', $amount);

                    // 如果扣除的余额多于此会员拥有的余额，提示
                    if ($fmt_amount > $user_account) {
                        $link[] = ['text' => lang('go_back'), 'href' => 'javascript:history.back(-1)'];

                        return $this->sys_msg(lang('surplus_amount_error'), 0, $link);
                    }

                    $this->update_user_account($id, $amount, $admin_note, $is_paid);

                    // 更新会员余额数量
                    CommonHelper::log_account_change($account['user_id'], $amount, 0, 0, 0, lang('surplus_type_1'), ACT_DRAWING);
                } elseif ($is_paid === '1' && $account['process_type'] === '0') {
                    // 如果是预付款，并且已完成, 更新此条记录，增加相应的余额
                    $this->update_user_account($id, $amount, $admin_note, $is_paid);

                    // 更新会员余额数量
                    CommonHelper::log_account_change($account['user_id'], $amount, 0, 0, 0, lang('surplus_type_0'), ACT_SAVING);
                } elseif ($is_paid === '0') {
                    // 否则更新信息
                    DB::table('user_account')->where('id', $id)->update([
                        'admin_user' => Session::get('admin_name'),
                        'admin_note' => $admin_note,
                        'is_paid' => 0,
                    ]);
                }

                // 记录管理员日志
                $this->admin_log('('.addslashes(lang('check')).')'.$admin_note, 'edit', 'user_surplus');

                // 提示信息
                $link[0]['text'] = lang('back_list');
                $link[0]['href'] = 'user_account.php?act=list&'.MainHelper::list_link_postfix();

                return $this->sys_msg(lang('attradd_succed'), 0, $link);
            }
        }

        /**
         * ajax帐户信息列表
         */
        if ($action === 'query') {
            $list = $this->account_list();
            $this->assign('list', $list['list']);
            $this->assign('filter', $list['filter']);
            $this->assign('record_count', $list['record_count']);
            $this->assign('page_count', $list['page_count']);

            $sort_flag = MainHelper::sort_flag($list['filter']);
            $this->assign($sort_flag['tag'], $sort_flag['img']);

            return $this->make_json_result($this->fetch('user_account_list'), '', ['filter' => $list['filter'], 'page_count' => $list['page_count']]);
        }
        /**
         * ajax删除一条信息
         */
        if ($action === 'remove') {
            $this->check_authz_json('surplus_manage');
            $id = @intval($_REQUEST['id']);
            $user_name = DB::table('user', 'u')
                ->join(ecs()->table('user_account').' AS ua', 'u.user_id', '=', 'ua.user_id')
                ->where('ua.id', $id)
                ->value('u.user_name');
            if (DB::table('user_account')->where('id', $id)->delete()) {
                $this->admin_log(addslashes($user_name), 'remove', 'user_surplus');
                $url = 'user_account.php?act=query&'.str_replace('act=remove', '', $_SERVER['QUERY_STRING']);

                return response()->redirectTo($url);
            } else {
                return $this->make_json_error(lang('delete_failed'));
            }
        }
    }

    // ------------------------------------------------------
    // -- 会员余额函数部分
    // ------------------------------------------------------
    /**
     * 查询会员余额的数量
     *
     * @param  int  $user_id  会员ID
     */
    private function get_user_surplus($user_id): float
    {
        return (float) DB::table('user_account_log')->where('user_id', $user_id)->sum('user_money');
    }

    /**
     * 更新会员账目明细
     *
     * @param  array  $id  帐目ID
     * @param  array  $admin_note  管理员描述
     * @param  array  $amount  操作的金额
     * @param  array  $is_paid  是否已完成
     * @return int
     */
    private function update_user_account($id, $amount, $admin_note, $is_paid)
    {
        return DB::table('user_account')->where('id', $id)->update([
            'admin_user' => Session::get('admin_name'),
            'amount' => $amount,
            'paid_time' => TimeHelper::gmtime(),
            'admin_note' => $admin_note,
            'is_paid' => $is_paid,
        ]);
    }

    /**
     * @return void
     */
    private function account_list(): array
    {
        $result = MainHelper::get_filter();
        if ($result === false) {
            // 过滤列表
            $filter['user_id'] = ! empty($_REQUEST['user_id']) ? intval($_REQUEST['user_id']) : 0;
            $filter['keywords'] = empty($_REQUEST['keywords']) ? '' : trim($_REQUEST['keywords']);
            if (isset($_REQUEST['is_ajax']) && $_REQUEST['is_ajax'] === 1) {
                $filter['keywords'] = BaseHelper::json_str_iconv($filter['keywords']);
            }

            $filter['process_type'] = isset($_REQUEST['process_type']) ? intval($_REQUEST['process_type']) : -1;
            $filter['payment'] = empty($_REQUEST['payment']) ? '' : trim($_REQUEST['payment']);
            $filter['is_paid'] = isset($_REQUEST['is_paid']) ? intval($_REQUEST['is_paid']) : -1;
            $filter['sort_by'] = empty($_REQUEST['sort_by']) ? 'add_time' : trim($_REQUEST['sort_by']);
            $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);
            $filter['start_date'] = empty($_REQUEST['start_date']) ? '' : TimeHelper::local_strtotime($_REQUEST['start_date']);
            $filter['end_date'] = empty($_REQUEST['end_date']) ? '' : (TimeHelper::local_strtotime($_REQUEST['end_date']) + 86400);

            $where = ' WHERE 1 ';
            if ($filter['user_id'] > 0) {
                $where .= " AND ua.user_id = '$filter[user_id]' ";
            }
            if ($filter['process_type'] != -1) {
                $where .= " AND ua.process_type = '$filter[process_type]' ";
            } else {
                $where .= ' AND ua.process_type '.db_create_in([SURPLUS_SAVE, SURPLUS_RETURN]);
            }
            if ($filter['payment']) {
                $where .= " AND ua.payment = '$filter[payment]' ";
            }
            if ($filter['is_paid'] != -1) {
                $where .= " AND ua.is_paid = '$filter[is_paid]' ";
            }

            if ($filter['keywords']) {
                $where .= " AND u.user_name LIKE '%".BaseHelper::mysql_like_quote($filter['keywords'])."%'";
                $sql = 'SELECT COUNT(*) FROM '.ecs()->table('user_account').' AS ua, '.
                    ecs()->table('user').' AS u '.$where;
            }
            /*　时间过滤　 */
            if (! empty($filter['start_date']) && ! empty($filter['end_date'])) {
                $where .= 'AND paid_time >= '.$filter['start_date']." AND paid_time < '".$filter['end_date']."'";
            }

            $sql = 'SELECT COUNT(*) FROM '.ecs()->table('user_account').' AS ua, '.
                ecs()->table('user').' AS u '.$where;
            $filter['record_count'] = DB::select($sql)[0]->{'COUNT(*)'} ?? 0;

            // 分页大小
            $filter = MainHelper::page_and_size($filter);

            // 查询数据
            $sql = 'SELECT ua.*, u.user_name FROM '.
                ecs()->table('user_account').' AS ua LEFT JOIN '.
                ecs()->table('user').' AS u ON ua.user_id = u.user_id'.
                $where.'ORDER by '.$filter['sort_by'].' '.$filter['sort_order'].' LIMIT '.$filter['start'].', '.$filter['page_size'];

            $filter['keywords'] = stripslashes($filter['keywords']);
            MainHelper::set_filter($filter, $sql);
        } else {
            $sql = $result['sql'];
            $filter = $result['filter'];
        }

        $list = DB::select($sql);
        $list = array_map(fn ($r) => (array) $r, $list);
        foreach ($list as $key => $value) {
            $list[$key]['surplus_amount'] = CommonHelper::price_format(abs($value['amount']), false);
            $list[$key]['add_date'] = TimeHelper::local_date(cfg('time_format'), $value['add_time']);
            $list[$key]['process_type_name'] = $GLOBALS['_LANG']['surplus_type_'.$value['process_type']];
        }
        $arr = ['list' => $list, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']];

        return $arr;
    }
}
