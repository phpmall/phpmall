<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Helpers\CommonHelper;
use App\Helpers\OrderHelper;
use App\Helpers\TimeHelper;
use App\Modules\Admin\Helpers\MainHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AffiliateCkController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        $this->admin_priv('affiliate_ck');
        $timestamp = time();

        $affiliate = unserialize(cfg('affiliate'));
        empty($affiliate) && $affiliate = [];
        $separate_on = $affiliate['on'];

        /**
         * 分成页
         */
        if ($action === 'list') {
            isset($_GET['auid']) && $_GET['auid'] = intval($_GET['auid']);
            $logdb = $this->get_affiliate_ck();
            $this->assign('full_page', 1);
            $this->assign('ur_here', lang('affiliate_ck'));
            $this->assign('on', $separate_on);
            $this->assign('logdb', $logdb['logdb']);
            $this->assign('filter', $logdb['filter']);
            $this->assign('record_count', $logdb['record_count']);
            $this->assign('page_count', $logdb['page_count']);
            if (! empty($_GET['auid'])) {
                settype($_GET['auid'], 'integer');
                $this->assign('action_link', ['text' => lang('back_note'), 'href' => 'users.php?act=edit&id='.intval($_GET['auid'])]);
            }

            return $this->display('affiliate_ck_list');
        }
        /**
         * 分页
         */
        if ($action === 'query') {
            isset($_GET['auid']) && $_GET['auid'] = intval($_GET['auid']);
            $logdb = $this->get_affiliate_ck();
            $this->assign('logdb', $logdb['logdb']);
            $this->assign('on', $separate_on);
            $this->assign('filter', $logdb['filter']);
            $this->assign('record_count', $logdb['record_count']);
            $this->assign('page_count', $logdb['page_count']);

            $sort_flag = MainHelper::sort_flag($logdb['filter']);
            $this->assign($sort_flag['tag'], $sort_flag['img']);

            return $this->make_json_result($this->fetch('affiliate_ck_list'), '', ['filter' => $logdb['filter'], 'page_count' => $logdb['page_count']]);
        }

        /**
    取消分成，不再能对该订单进行分成
         */
        if ($action === 'del') {
            $oid = (int) $_REQUEST['oid'];
            $stat = DB::table('order_info')->where('order_id', $oid)->value('is_separate');
            if (empty($stat)) {
                DB::table('order_info')
                    ->where('order_id', $oid)
                    ->update(['is_separate' => 2]);
            }
            $links[] = ['text' => lang('affiliate_ck'), 'href' => 'affiliate_ck.php?act=list'];

            return $this->sys_msg(lang('edit_ok'), 0, $links);
        }

        /**
    撤销某次分成，将已分成的收回来
         */
        if ($action === 'rollback') {
            $logid = (int) $_REQUEST['logid'];
            $stat = DB::table('user_affiliate')->where('log_id', $logid)->first();
            $stat = $stat ? (array) $stat : [];
            if (! empty($stat)) {
                if ($stat['separate_type'] === 1) {
                    // 推荐订单分成
                    $flag = -2;
                } else {
                    // 推荐注册分成
                    $flag = -1;
                }
                CommonHelper::log_account_change($stat['user_id'], -$stat['money'], 0, -$stat['point'], 0, lang('loginfo.cancel'));
                DB::table('user_affiliate')
                    ->where('log_id', $logid)
                    ->update(['separate_type' => $flag]);
            }
            $links[] = ['text' => lang('affiliate_ck'), 'href' => 'affiliate_ck.php?act=list'];

            return $this->sys_msg(lang('edit_ok'), 0, $links);
        }

        /**
    分成
         */
        if ($action === 'separate') {
            $affiliate = unserialize(cfg('affiliate'));
            empty($affiliate) && $affiliate = [];

            $separate_by = $affiliate['config']['separate_by'];

            $oid = (int) $_REQUEST['oid'];

            $row = DB::table('order_info as o')
                ->leftJoin('user as u', 'o.user_id', '=', 'u.user_id')
                ->select('o.order_sn', 'o.is_separate', DB::raw('(o.goods_amount - o.discount) AS goods_amount'), 'o.user_id')
                ->where('o.order_id', $oid)
                ->first();
            $row = $row ? (array) $row : [];

            $order_sn = $row['order_sn'];

            if (empty($row['is_separate'])) {
                $affiliate['config']['level_point_all'] = (float) $affiliate['config']['level_point_all'];
                $affiliate['config']['level_money_all'] = (float) $affiliate['config']['level_money_all'];
                if ($affiliate['config']['level_point_all']) {
                    $affiliate['config']['level_point_all'] /= 100;
                }
                if ($affiliate['config']['level_money_all']) {
                    $affiliate['config']['level_money_all'] /= 100;
                }
                $money = round($affiliate['config']['level_money_all'] * $row['goods_amount'], 2);
                $integral = OrderHelper::integral_to_give(['order_id' => $oid, 'extension_code' => '']);
                $point = round($affiliate['config']['level_point_all'] * intval($integral['rank_points']), 0);

                if (empty($separate_by)) {
                    // 推荐注册分成
                    $num = count($affiliate['item']);
                    for ($i = 0; $i < $num; $i++) {
                        $affiliate['item'][$i]['level_point'] = (float) $affiliate['item'][$i]['level_point'];
                        $affiliate['item'][$i]['level_money'] = (float) $affiliate['item'][$i]['level_money'];
                        if ($affiliate['item'][$i]['level_point']) {
                            $affiliate['item'][$i]['level_point'] /= 100;
                        }
                        if ($affiliate['item'][$i]['level_money']) {
                            $affiliate['item'][$i]['level_money'] /= 100;
                        }
                        $setmoney = round($money * $affiliate['item'][$i]['level_money'], 2);
                        $setpoint = round($point * $affiliate['item'][$i]['level_point'], 0);
                        $row = DB::table('user as o')
                            ->leftJoin('user as u', 'o.parent_id', '=', 'u.user_id')
                            ->select('o.parent_id as user_id', 'u.user_name')
                            ->where('o.user_id', $row['user_id'])
                            ->first();
                        $row = $row ? (array) $row : [];
                        $up_uid = $row['user_id'];
                        if (empty($up_uid) || empty($row['user_name'])) {
                            break;
                        } else {
                            $info = sprintf(lang('separate_info'), $order_sn, $setmoney, $setpoint);
                            CommonHelper::log_account_change($up_uid, $setmoney, 0, $setpoint, 0, $info);
                            $this->write_affiliate_log($oid, $up_uid, $row['user_name'], $setmoney, $setpoint, $separate_by);
                        }
                    }
                } else {
                    // 推荐订单分成
                    $row = DB::table('order_info as o')
                        ->leftJoin('user as u', 'o.parent_id', '=', 'u.user_id')
                        ->select('o.parent_id', 'u.user_name')
                        ->where('o.order_id', $oid)
                        ->first();
                    $row = $row ? (array) $row : [];
                    $up_uid = $row['parent_id'];
                    if (! empty($up_uid) && $up_uid > 0) {
                        $info = sprintf(lang('separate_info'), $order_sn, $money, $point);
                        CommonHelper::log_account_change($up_uid, $money, 0, $point, 0, $info);
                        $this->write_affiliate_log($oid, $up_uid, $row['user_name'], $money, $point, $separate_by);
                    } else {
                        $links[] = ['text' => lang('affiliate_ck'), 'href' => 'affiliate_ck.php?act=list'];

                        return $this->sys_msg(lang('edit_fail'), 1, $links);
                    }
                }
                DB::table('order_info')
                    ->where('order_id', $oid)
                    ->update(['is_separate' => 1]);
            }
            $links[] = ['text' => lang('affiliate_ck'), 'href' => 'affiliate_ck.php?act=list'];

            return $this->sys_msg(lang('edit_ok'), 0, $links);
        }
    }

    private function get_affiliate_ck()
    {
        $affiliate = unserialize(cfg('affiliate'));
        empty($affiliate) && $affiliate = [];
        $separate_by = $affiliate['config']['separate_by'];

        $query = DB::table('order_info as o')
            ->leftJoin('user as u', 'o.user_id', '=', 'u.user_id')
            ->leftJoin('user_affiliate as a', 'o.order_id', '=', 'a.order_id');

        if (isset($_REQUEST['status'])) {
            $query->where('o.is_separate', (int) $_REQUEST['status']);
            $filter['status'] = (int) $_REQUEST['status'];
        }
        if (isset($_REQUEST['order_sn'])) {
            $query->where('o.order_sn', 'LIKE', '%'.trim($_REQUEST['order_sn']).'%');
            $filter['order_sn'] = $_REQUEST['order_sn'];
        }
        if (isset($_GET['auid']) && $_GET['auid'] > 0) {
            $query->where('a.user_id', (int) $_GET['auid']);
        }

        if (! empty($affiliate['on'])) {
            if (empty($separate_by)) {
                // 推荐注册分成
                $query->where('o.user_id', '>', 0)
                    ->where(function ($q) {
                        $q->where(function ($q2) {
                            $q2->where('u.parent_id', '>', 0)
                                ->where('o.is_separate', 0);
                        })->orWhere('o.is_separate', '>', 0);
                    });
            } else {
                // 推荐订单分成
                $query->where('o.user_id', '>', 0)
                    ->where(function ($q) {
                        $q->where(function ($q2) {
                            $q2->where('o.parent_id', '>', 0)
                                ->where('o.is_separate', 0);
                        })->orWhere('o.is_separate', '>', 0);
                    });
            }
        } else {
            $query->where('o.user_id', '>', 0)
                ->where('o.is_separate', '>', 0);
        }

        $filter['record_count'] = $query->count();
        $logdb = [];
        // 分页大小
        $filter = MainHelper::page_and_size($filter);

        $res = $query->select('o.*', 'a.log_id', 'a.user_id as suid', 'a.user_name as auser', 'a.money', 'a.point', 'a.separate_type', 'u.parent_id as up')
            ->orderBy('o.order_id', 'DESC')
            ->offset($filter['start'])
            ->limit($filter['page_size'])
            ->get();

        foreach ($res as $rt) {
            $rt = (array) $rt;
            if (empty($separate_by) && $rt['up'] > 0) {
                // 按推荐注册分成
                $rt['separate_able'] = 1;
            } elseif (! empty($separate_by) && $rt['parent_id'] > 0) {
                // 按推荐订单分成
                $rt['separate_able'] = 1;
            }
            if (! empty($rt['suid'])) {
                // 在affiliate_log有记录
                $rt['info'] = sprintf(lang('separate_info2'), $rt['suid'], $rt['auser'], $rt['money'], $rt['point']);
                if ($rt['separate_type'] === -1 || $rt['separate_type'] === -2) {
                    // 已被撤销
                    $rt['is_separate'] = 3;
                    $rt['info'] = '<s>'.$rt['info'].'</s>';
                }
            }
            $logdb[] = $rt;
        }
        $arr = ['logdb' => $logdb, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']];

        return $arr;
    }

    private function write_affiliate_log($oid, $uid, $username, $money, $point, $separate_by)
    {
        if ($oid) {
            DB::table('user_affiliate')->insert([
                'order_id' => $oid,
                'user_id' => $uid,
                'user_name' => $username,
                'time' => TimeHelper::gmtime(),
                'money' => $money,
                'point' => $point,
                'separate_type' => $separate_by,
            ]);
        }
    }
}
