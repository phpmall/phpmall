<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Helpers\CommonHelper;
use App\Helpers\OrderHelper;
use App\Helpers\TimeHelper;
use App\Modules\Admin\Helpers\MainHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AccountLogController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');

        /**
         * 办事处列表
         */
        if ($action === 'list') {
            $user_id = intval($request->get('user_id'));
            if ($user_id <= 0) {
                return $this->sys_msg('invalid param');
            }

            $user = OrderHelper::user_info($user_id);
            if (empty($user)) {
                return $this->sys_msg(lang('user_not_exist'));
            }

            $account_type = $request->get('account_type');
            if (! in_array($account_type, ['user_money', 'frozen_money', 'rank_points', 'pay_points'])) {
                $account_type = '';
            }

            $this->assign('user', $user);
            $this->assign('account_type', $account_type);
            $this->assign('ur_here', lang('account_list'));
            $this->assign('action_link', ['text' => lang('add_account'), 'href' => 'account_log.php?act=add&user_id='.$user_id]);
            $this->assign('full_page', 1);

            $account_list = $this->get_accountlist($user_id, $account_type);
            $this->assign('account_list', $account_list['account']);
            $this->assign('filter', $account_list['filter']);
            $this->assign('record_count', $account_list['record_count']);
            $this->assign('page_count', $account_list['page_count']);

            return $this->display('account_list');
        }

        /**
         * 排序、分页、查询
         */
        if ($action === 'query') {
            $user_id = intval($request->get('user_id'));
            if ($user_id <= 0) {
                return $this->sys_msg('invalid param');
            }

            $user = OrderHelper::user_info($user_id);
            if (empty($user)) {
                return $this->sys_msg(lang('user_not_exist'));
            }

            $account_type = $request->get('account_type');
            if (! in_array($account_type, ['user_money', 'frozen_money', 'rank_points', 'pay_points'])) {
                $account_type = '';
            }

            $this->assign('user', $user);
            $this->assign('account_type', $account_type);

            $account_list = $this->get_accountlist($user_id, $account_type);
            $this->assign('account_list', $account_list['account']);
            $this->assign('filter', $account_list['filter']);
            $this->assign('record_count', $account_list['record_count']);
            $this->assign('page_count', $account_list['page_count']);

            return $this->make_json_result(
                $this->fetch('account_list'),
                '',
                ['filter' => $account_list['filter'], 'page_count' => $account_list['page_count']]
            );
        }

        /**
         * 调节帐户
         */
        if ($action === 'add') {
            $this->admin_priv('account_manage');

            $user_id = intval($request->get('user_id'));
            if ($user_id <= 0) {
                return $this->sys_msg('invalid param');
            }

            $user = OrderHelper::user_info($user_id);
            if (empty($user)) {
                return $this->sys_msg(lang('user_not_exist'));
            }

            $this->assign('user', $user);
            $this->assign('ur_here', lang('add_account'));
            $this->assign('action_link', ['href' => 'account_log.php?act=list&user_id='.$user_id, 'text' => lang('account_list')]);

            return $this->display('account_info');
        }

        /**
         * 提交添加、编辑
         */
        if ($action === 'insert') {
            // update
        }

        if ($action === 'update') {
            $this->admin_priv('account_manage');

            $token = trim($_POST['token']);
            if ($token != cfg('token')) {
                return $this->sys_msg(lang('no_account_change'), 1);
            }

            $user_id = intval($request->get('user_id'));
            if ($user_id <= 0) {
                return $this->sys_msg('invalid param');
            }

            $user = OrderHelper::user_info($user_id);
            if (empty($user)) {
                return $this->sys_msg(lang('user_not_exist'));
            }

            // 提交值
            $change_desc = Str::substr($_POST['change_desc'], 255, false);
            $user_money = floatval($_POST['add_sub_user_money']) * abs(floatval($_POST['user_money']));
            $frozen_money = floatval($_POST['add_sub_frozen_money']) * abs(floatval($_POST['frozen_money']));
            $rank_points = floatval($_POST['add_sub_rank_points']) * abs(floatval($_POST['rank_points']));
            $pay_points = floatval($_POST['add_sub_pay_points']) * abs(floatval($_POST['pay_points']));

            if ($user_money === 0 && $frozen_money === 0 && $rank_points === 0 && $pay_points === 0) {
                return $this->sys_msg(lang('no_account_change'));
            }

            // 保存
            CommonHelper::log_account_change($user_id, $user_money, $frozen_money, $rank_points, $pay_points, $change_desc, ACT_ADJUSTING);

            // 提示信息
            $links = [
                ['href' => 'account_log.php?act=list&user_id='.$user_id, 'text' => lang('account_list')],
            ];

            return $this->sys_msg(lang('log_account_change_ok'), 0, $links);
        }
    }

    /**
     * 取得帐户明细
     *
     * @param  int  $user_id  用户id
     * @param  string  $account_type  帐户类型：空表示所有帐户，user_money表示可用资金，
     *                                frozen_money表示冻结资金，rank_points表示等级积分，pay_points表示消费积分
     * @return array
     */
    private function get_accountlist($user_id, $account_type = '')
    {
        $query = DB::table('user_account_log')->where('user_id', $user_id);

        if (in_array($account_type, ['user_money', 'frozen_money', 'rank_points', 'pay_points'])) {
            $query->where($account_type, '<>', 0);
        }

        // 初始化分页参数
        $filter = [
            'user_id' => $user_id,
            'account_type' => $account_type,
        ];

        // 查询记录总数，计算分页数
        $filter['record_count'] = $query->count();
        $filter = MainHelper::page_and_size($filter);

        // 查询记录
        $res = $query->orderBy('log_id', 'DESC')
            ->offset($filter['start'])
            ->limit($filter['page_size'])
            ->get();

        $arr = [];
        foreach ($res as $row) {
            $row = (array) $row;
            $row['change_time'] = TimeHelper::local_date(cfg('time_format'), $row['change_time']);
            $arr[] = $row;
        }

        return ['account' => $arr, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']];
    }
}
