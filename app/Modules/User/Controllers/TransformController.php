<?php

declare(strict_types=1);

namespace App\Modules\User\Controllers;

use App\Helpers\CommonHelper;
use App\Helpers\TimeHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// @deprecated
class TransformController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        if ($action === 'transform_points') {
            $rule = [];
            if (! empty(cfg('points_rule'))) {
                $rule = unserialize(cfg('points_rule'));
            }
            $cfg = [];
            if (! empty(cfg('integrate_config'))) {
                $cfg = unserialize(cfg('integrate_config'));
                lang('exchange_points')[0] = empty($cfg['uc_lang']['credits'][0][0]) ? lang('exchange_points')[0] : $cfg['uc_lang']['credits'][0][0];
                lang('exchange_points')[1] = empty($cfg['uc_lang']['credits'][1][0]) ? lang('exchange_points')[1] : $cfg['uc_lang']['credits'][1][0];
            }
            $row = (array) DB::table('user')
                ->where('user_id', $this->getUserId())
                ->select('user_id', 'user_name', 'pay_points', 'rank_points')
                ->first();
            if (cfg('integrate_code') === 'ucenter') {
                $exchange_type = 'ucenter';
                $to_credits_options = [];
                $out_exchange_allow = [];
                foreach ($rule as $credit) {
                    $out_exchange_allow[$credit['appiddesc'].'|'.$credit['creditdesc'].'|'.$credit['creditsrc']] = $credit['ratio'];
                    if (! array_key_exists($credit['appiddesc'].'|'.$credit['creditdesc'], $to_credits_options)) {
                        $to_credits_options[$credit['appiddesc'].'|'.$credit['creditdesc']] = $credit['title'];
                    }
                }
                $this->assign('selected_org', $rule[0]['creditsrc']);
                $this->assign('selected_dst', $rule[0]['appiddesc'].'|'.$rule[0]['creditdesc']);
                $this->assign('descreditunit', $rule[0]['unit']);
                $this->assign('orgcredittitle', lang('exchange_points')[$rule[0]['creditsrc']]);
                $this->assign('descredittitle', $rule[0]['title']);
                $this->assign('descreditamount', round((1 / $rule[0]['ratio']), 2));
                $this->assign('to_credits_options', $to_credits_options);
                $this->assign('out_exchange_allow', $out_exchange_allow);
            } else {
                $exchange_type = 'other';

                $bbs_points_name = $user->get_points_name();
                $total_bbs_points = $user->get_points($row['user_name']);

                // 论坛积分
                $bbs_points = [];
                foreach ($bbs_points_name as $key => $val) {
                    $bbs_points[$key] = ['title' => lang('bbs').$val['title'], 'value' => $total_bbs_points[$key]];
                }

                // 兑换规则
                $rule_list = [];
                foreach ($rule as $key => $val) {
                    $rule_key = substr($key, 0, 1);
                    $bbs_key = substr($key, 1);
                    $rule_list[$key]['rate'] = $val;
                    switch ($rule_key) {
                        case TO_P:
                            $rule_list[$key]['from'] = lang('bbs').$bbs_points_name[$bbs_key]['title'];
                            $rule_list[$key]['to'] = lang('pay_points');
                            break;
                        case TO_R:
                            $rule_list[$key]['from'] = lang('bbs').$bbs_points_name[$bbs_key]['title'];
                            $rule_list[$key]['to'] = lang('rank_points');
                            break;
                        case FROM_P:
                            $rule_list[$key]['from'] = lang('pay_points');
                            lang('bbs').$bbs_points_name[$bbs_key]['title'];
                            $rule_list[$key]['to'] = lang('bbs').$bbs_points_name[$bbs_key]['title'];
                            break;
                        case FROM_R:
                            $rule_list[$key]['from'] = lang('rank_points');
                            $rule_list[$key]['to'] = lang('bbs').$bbs_points_name[$bbs_key]['title'];
                            break;
                    }
                }
                $this->assign('bbs_points', $bbs_points);
                $this->assign('rule_list', $rule_list);
            }
            $this->assign('shop_points', $row);
            $this->assign('exchange_type', $exchange_type);
            $this->assign('action', $action);

            return $this->display('user_transaction');
        }

        if ($action === 'act_transform_points') {
            $rule_index = empty($_POST['rule_index']) ? '' : trim($_POST['rule_index']);
            $num = empty($_POST['num']) ? 0 : intval($_POST['num']);

            if ($num <= 0 || $num != floor($num)) {
                $this->show_message(lang('invalid_points'), lang('transform_points'), 'user.php?act=transform_points');
            }

            $num = floor($num); // 格式化为整数

            $bbs_key = substr($rule_index, 1);
            $rule_key = substr($rule_index, 0, 1);

            $max_num = 0;

            // 取出用户数据
            $row = (array) DB::table('user')
                ->where('user_id', $this->getUserId())
                ->select('user_name', 'user_id', 'pay_points', 'rank_points')
                ->first();
            $bbs_points = $user->get_points($row['user_name']);
            $points_name = $user->get_points_name();

            $rule = [];
            if (cfg('points_rule')) {
                $rule = unserialize(cfg('points_rule'));
            }
            [$from, $to] = explode(':', $rule[$rule_index]);

            $max_points = 0;
            switch ($rule_key) {
                case TO_P:
                    $max_points = $bbs_points[$bbs_key];
                    break;
                case TO_R:
                    $max_points = $bbs_points[$bbs_key];
                    break;
                case FROM_P:
                    $max_points = $row['pay_points'];
                    break;
                case FROM_R:
                    $max_points = $row['rank_points'];
            }

            // 检查积分是否超过最大值
            if ($max_points <= 0 || $num > $max_points) {
                $this->show_message(lang('overflow_points'), lang('transform_points'), 'user.php?act=transform_points');
            }

            switch ($rule_key) {
                case TO_P:
                    $result_points = floor($num * $to / $from);
                    $user->set_points($row['user_name'], [$bbs_key => 0 - $num]); // 调整论坛积分
                    CommonHelper::log_account_change($row['user_id'], 0, 0, 0, $result_points, lang('transform_points'), ACT_OTHER);
                    $this->show_message(sprintf(lang('to_pay_points'), $num, $points_name[$bbs_key]['title'], $result_points), lang('transform_points'), 'user.php?act=transform_points');

                    // no break
                case TO_R:
                    $result_points = floor($num * $to / $from);
                    $user->set_points($row['user_name'], [$bbs_key => 0 - $num]); // 调整论坛积分
                    CommonHelper::log_account_change($row['user_id'], 0, 0, $result_points, 0, lang('transform_points'), ACT_OTHER);
                    $this->show_message(sprintf(lang('to_rank_points'), $num, $points_name[$bbs_key]['title'], $result_points), lang('transform_points'), 'user.php?act=transform_points');

                    // no break
                case FROM_P:
                    $result_points = floor($num * $to / $from);
                    CommonHelper::log_account_change($row['user_id'], 0, 0, 0, 0 - $num, lang('transform_points'), ACT_OTHER); // 调整商城积分
                    $user->set_points($row['user_name'], [$bbs_key => $result_points]); // 调整论坛积分
                    $this->show_message(sprintf(lang('from_pay_points'), $num, $result_points, $points_name[$bbs_key]['title']), lang('transform_points'), 'user.php?act=transform_points');

                    // no break
                case FROM_R:
                    $result_points = floor($num * $to / $from);
                    CommonHelper::log_account_change($row['user_id'], 0, 0, 0 - $num, 0, lang('transform_points'), ACT_OTHER); // 调整商城积分
                    $user->set_points($row['user_name'], [$bbs_key => $result_points]); // 调整论坛积分
                    $this->show_message(sprintf(lang('from_rank_points'), $num, $result_points, $points_name[$bbs_key]['title']), lang('transform_points'), 'user.php?act=transform_points');
            }
        }

        if ($action === 'act_transform_ucenter_points') {
            $rule = [];
            if (cfg('points_rule')) {
                $rule = unserialize(cfg('points_rule'));
            }
            $shop_points = [0 => 'rank_points', 1 => 'pay_points'];
            $row = (array) DB::table('user')
                ->where('user_id', $this->getUserId())
                ->select('user_id', 'user_name', 'pay_points', 'rank_points')
                ->first();
            $exchange_amount = intval($_POST['amount']);
            $fromcredits = intval($_POST['fromcredits']);
            $tocredits = trim($_POST['tocredits']);
            $cfg = unserialize(cfg('integrate_config'));
            if (! empty($cfg)) {
                lang('exchange_points')[0] = empty($cfg['uc_lang']['credits'][0][0]) ? lang('exchange_points')[0] : $cfg['uc_lang']['credits'][0][0];
                lang('exchange_points')[1] = empty($cfg['uc_lang']['credits'][1][0]) ? lang('exchange_points')[1] : $cfg['uc_lang']['credits'][1][0];
            }
            [$appiddesc, $creditdesc] = explode('|', $tocredits);
            $ratio = 0;

            if ($exchange_amount <= 0) {
                $this->show_message(lang('invalid_points'), lang('transform_points'), 'user.php?act=transform_points');
            }
            if ($exchange_amount > $row[$shop_points[$fromcredits]]) {
                $this->show_message(lang('overflow_points'), lang('transform_points'), 'user.php?act=transform_points');
            }
            foreach ($rule as $credit) {
                if ($credit['appiddesc'] === $appiddesc && $credit['creditdesc'] === $creditdesc && $credit['creditsrc'] === $fromcredits) {
                    $ratio = $credit['ratio'];
                    break;
                }
            }
            if ($ratio === 0) {
                $this->show_message(lang('exchange_deny'), lang('transform_points'), 'user.php?act=transform_points');
            }
            $netamount = floor($exchange_amount / $ratio);
            $result = exchange_points($row['user_id'], $fromcredits, $creditdesc, $appiddesc, $netamount); // @deprecated
            if ($result === true) {
                DB::table('user')
                    ->where('user_id', $row['user_id'])
                    ->decrement($shop_points[$fromcredits], $exchange_amount);
                DB::table('user_account_log')->insert([
                    'user_id' => $row['user_id'],
                    $shop_points[$fromcredits] => -$exchange_amount,
                    'change_time' => TimeHelper::gmtime(),
                    'change_desc' => $cfg['uc_lang']['exchange'],
                    'change_type' => 98,
                ]);
                $this->show_message(sprintf(lang('exchange_success'), $exchange_amount, lang('exchange_points')[$fromcredits], $netamount, $credit['title']), lang('transform_points'), 'user.php?act=transform_points');
            } else {
                $this->show_message(lang('exchange_error_1'), lang('transform_points'), 'user.php?act=transform_points');
            }
        }
    }
}
