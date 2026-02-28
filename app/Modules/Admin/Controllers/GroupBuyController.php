<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Helpers\BaseHelper;
use App\Helpers\CommonHelper;
use App\Helpers\GoodsHelper;
use App\Helpers\OrderHelper;
use App\Helpers\TimeHelper;
use App\Modules\Admin\Helpers\MainHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GroupBuyController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        $this->admin_priv('group_by');

        /**
         * 团购活动列表
         */
        if ($action === 'list') {
            $this->assign('full_page', 1);
            $this->assign('ur_here', lang('group_buy_list'));
            $this->assign('action_link', ['href' => 'group_buy.php?act=add', 'text' => lang('add_group_buy')]);

            $list = $this->group_buy_list();

            $this->assign('group_buy_list', $list['item']);
            $this->assign('filter', $list['filter']);
            $this->assign('record_count', $list['record_count']);
            $this->assign('page_count', $list['page_count']);

            $sort_flag = MainHelper::sort_flag($list['filter']);
            $this->assign($sort_flag['tag'], $sort_flag['img']);

            return $this->display('group_buy_list');
        }

        if ($action === 'query') {
            $list = $this->group_buy_list();

            $this->assign('group_buy_list', $list['item']);
            $this->assign('filter', $list['filter']);
            $this->assign('record_count', $list['record_count']);
            $this->assign('page_count', $list['page_count']);

            $sort_flag = MainHelper::sort_flag($list['filter']);
            $this->assign($sort_flag['tag'], $sort_flag['img']);

            return $this->make_json_result(
                $this->fetch('group_buy_list'),
                '',
                ['filter' => $list['filter'], 'page_count' => $list['page_count']]
            );
        }

        /**
         * 添加/编辑团购活动
         */
        if ($action === 'add' || $action === 'edit') {
            // 初始化/取得团购活动信息
            if ($action === 'add') {
                $group_buy = [
                    'act_id' => 0,
                    'start_time' => date('Y-m-d', time() + 86400),
                    'end_time' => date('Y-m-d', time() + 4 * 86400),
                    'price_ladder' => [['amount' => 0, 'price' => 0]],
                ];
            } else {
                $group_buy_id = intval($_REQUEST['id']);
                if ($group_buy_id <= 0) {
                    exit('invalid param');
                }
                $group_buy = GoodsHelper::group_buy_info($group_buy_id);
            }
            $this->assign('group_buy', $group_buy);

            $this->assign('ur_here', lang('add_group_buy'));
            $this->assign('action_link', $this->list_link($action === 'add'));
            $this->assign('cat_list', CommonHelper::cat_list());
            $this->assign('brand_list', CommonHelper::get_brand_list());

            return $this->display('group_buy_info');
        }

        /**
         * 添加/编辑团购活动的提交
         */
        if ($action === 'insert_update') {
            // 取得团购活动id
            $group_buy_id = intval($_POST['act_id']);
            if (isset($_POST['finish']) || isset($_POST['succeed']) || isset($_POST['fail']) || isset($_POST['mail'])) {
                if ($group_buy_id <= 0) {
                    return $this->sys_msg(lang('error_group_buy'), 1);
                }
                $group_buy = GoodsHelper::group_buy_info($group_buy_id);
                if (empty($group_buy)) {
                    return $this->sys_msg(lang('error_group_buy'), 1);
                }
            }

            if (isset($_POST['finish'])) {
                // 判断订单状态
                if ($group_buy['status'] != GBS_UNDER_WAY) {
                    return $this->sys_msg(lang('error_status'), 1);
                }

                // 结束团购活动，修改结束时间为当前时间
                DB::table('goods_activity')
                    ->where('act_id', $group_buy_id)
                    ->limit(1)
                    ->update(['end_time' => TimeHelper::gmtime()]);

                // 清除缓存
                $this->clear_cache_files();

                // 提示信息
                $links = [
                    ['href' => 'group_buy.php?act=list', 'text' => lang('back_list')],
                ];

                return $this->sys_msg(lang('edit_success'), 0, $links);
            } elseif (isset($_POST['succeed'])) {
                // 设置活动成功

                // 判断订单状态
                if ($group_buy['status'] != GBS_FINISHED) {
                    return $this->sys_msg(lang('error_status'), 1);
                }

                // 如果有订单，更新订单信息
                if ($group_buy['total_order'] > 0) {
                    // 查找该团购活动的已确认或未确认订单（已取消的就不管了）
                    $order_id_list = DB::table('order_info')
                        ->where('extension_code', 'group_buy')
                        ->where('extension_id', $group_buy_id)
                        ->whereIn('order_status', [OS_CONFIRMED, OS_UNCONFIRMED])
                        ->pluck('order_id')
                        ->toArray();

                    // 更新订单商品价
                    $final_price = $group_buy['trans_price'];
                    DB::table('order_goods')
                        ->whereIn('order_id', $order_id_list)
                        ->update(['goods_price' => $final_price]);

                    // 查询订单商品总额
                    $res = DB::table('order_goods')
                        ->select('order_id', DB::raw('SUM(goods_number * goods_price) AS goods_amount'))
                        ->whereIn('order_id', $order_id_list)
                        ->groupBy('order_id')
                        ->get()
                        ->map(fn ($item) => (array) $item)
                        ->all();
                    foreach ($res as $row) {
                        $order_id = $row['order_id'];
                        $goods_amount = floatval($row['goods_amount']);

                        // 取得订单信息
                        $order = OrderHelper::order_info($order_id);

                        // 判断订单是否有效：余额支付金额 + 已付款金额 >= 保证金
                        if ($group_buy['deposit'] <= $order['surplus'] + $order['money_paid']) {
                            // 有效，设为已确认，更新订单

                            // 更新商品总额
                            $order['goods_amount'] = $goods_amount;

                            // 如果保价，重新计算保价费用
                            if ($order['insure_fee'] > 0) {
                                $shipping = OrderHelper::shipping_info($order['shipping_id']);
                                $order['insure_fee'] = OrderHelper::shipping_insure_fee($shipping['shipping_code'], $goods_amount, $shipping['insure']);
                            }

                            // 重算支付费用
                            $order['order_amount'] = $order['goods_amount'] + $order['shipping_fee']
                                + $order['insure_fee'] + $order['pack_fee'] + $order['card_fee']
                                - $order['money_paid'] - $order['surplus'];
                            if ($order['order_amount'] > 0) {
                                $order['pay_fee'] = OrderHelper::pay_fee($order['pay_id'], $order['order_amount']);
                            } else {
                                $order['pay_fee'] = 0;
                            }

                            // 计算应付款金额
                            $order['order_amount'] += $order['pay_fee'];

                            // 计算付款状态
                            if ($order['order_amount'] > 0) {
                                $order['pay_status'] = PS_UNPAYED;
                                $order['pay_time'] = 0;
                            } else {
                                $order['pay_status'] = PS_PAYED;
                                $order['pay_time'] = TimeHelper::gmtime();
                            }

                            // 如果需要退款，退到帐户余额
                            if ($order['order_amount'] < 0) {
                                // todo （现在手工退款）
                            }

                            // 订单状态
                            $order['order_status'] = OS_CONFIRMED;
                            $order['confirm_time'] = TimeHelper::gmtime();

                            // 更新订单
                            $order = BaseHelper::addslashes_deep($order);
                            OrderHelper::update_order($order_id, $order);
                        } else {
                            // 无效，取消订单，退回已付款

                            // 修改订单状态为已取消，付款状态为未付款
                            $order['order_status'] = OS_CANCELED;
                            $order['to_buyer'] = lang('cancel_order_reason');
                            $order['pay_status'] = PS_UNPAYED;
                            $order['pay_time'] = 0;

                            // 如果使用余额或有已付款金额，退回帐户余额
                            $money = $order['surplus'] + $order['money_paid'];
                            if ($money > 0) {
                                $order['surplus'] = 0;
                                $order['money_paid'] = 0;
                                $order['order_amount'] = $money;

                                // 退款到帐户余额
                                OrderHelper::order_refund($order, 1, lang('cancel_order_reason').':'.$order['order_sn']);
                            }

                            // 更新订单
                            $order = BaseHelper::addslashes_deep($order);
                            OrderHelper::update_order($order['order_id'], $order);
                        }
                    }
                }

                // 修改团购活动状态为成功
                DB::table('goods_activity')
                    ->where('act_id', $group_buy_id)
                    ->limit(1)
                    ->update(['is_finished' => GBS_SUCCEED]);

                // 清除缓存
                $this->clear_cache_files();

                // 提示信息
                $links = [
                    ['href' => 'group_buy.php?act=list', 'text' => lang('back_list')],
                ];

                return $this->sys_msg(lang('edit_success'), 0, $links);
            } elseif (isset($_POST['fail'])) {
                // 设置活动失败

                // 判断订单状态
                if ($group_buy['status'] != GBS_FINISHED) {
                    return $this->sys_msg(lang('error_status'), 1);
                }

                // 如果有有效订单，取消订单
                if ($group_buy['valid_order'] > 0) {
                    // 查找未确认或已确认的订单
                    $res = DB::table('order_info')
                        ->where('extension_code', 'group_buy')
                        ->where('extension_id', $group_buy_id)
                        ->whereIn('order_status', [OS_CONFIRMED, OS_UNCONFIRMED])
                        ->get()
                        ->map(fn ($item) => (array) $item)
                        ->all();
                    foreach ($res as $order) {
                        // 修改订单状态为已取消，付款状态为未付款
                        $order['order_status'] = OS_CANCELED;
                        $order['to_buyer'] = lang('cancel_order_reason');
                        $order['pay_status'] = PS_UNPAYED;
                        $order['pay_time'] = 0;

                        // 如果使用余额或有已付款金额，退回帐户余额
                        $money = $order['surplus'] + $order['money_paid'];
                        if ($money > 0) {
                            $order['surplus'] = 0;
                            $order['money_paid'] = 0;
                            $order['order_amount'] = $money;

                            // 退款到帐户余额
                            OrderHelper::order_refund($order, 1, lang('cancel_order_reason').':'.$order['order_sn'], $money);
                        }

                        // 更新订单
                        $order = BaseHelper::addslashes_deep($order);
                        OrderHelper::update_order($order['order_id'], $order);
                    }
                }

                // 修改团购活动状态为失败，记录失败原因（活动说明）
                DB::table('goods_activity')
                    ->where('act_id', $group_buy_id)
                    ->limit(1)
                    ->update([
                        'is_finished' => GBS_FAIL,
                        'act_desc' => $_POST['act_desc'],
                    ]);

                // 清除缓存
                $this->clear_cache_files();

                // 提示信息
                $links = [
                    ['href' => 'group_buy.php?act=list', 'text' => lang('back_list')],
                ];

                return $this->sys_msg(lang('edit_success'), 0, $links);
            } elseif (isset($_POST['mail'])) {
                // 发送通知邮件

                // 判断订单状态
                if ($group_buy['status'] != GBS_SUCCEED) {
                    return $this->sys_msg(lang('error_status'), 1);
                }

                // 取得邮件模板
                $tpl = CommonHelper::get_mail_template('group_buy');

                // 初始化订单数和成功发送邮件数
                $count = 0;
                $send_count = 0;

                // 取得有效订单
                $res = DB::table('order_info as o')
                    ->join('order_goods as g', 'o.order_id', '=', 'g.order_id')
                    ->select('o.consignee', 'o.add_time', 'g.goods_number', 'o.order_sn', 'o.order_amount', 'o.order_id', 'o.email')
                    ->where('o.extension_code', 'group_buy')
                    ->where('o.extension_id', $group_buy_id)
                    ->where('o.order_status', OS_CONFIRMED)
                    ->get()
                    ->map(fn ($item) => (array) $item)
                    ->all();
                foreach ($res as $order) {
                    // 邮件模板赋值
                    $this->assign('consignee', $order['consignee']);
                    $this->assign('add_time', TimeHelper::local_date(cfg('time_format'), $order['add_time']));
                    $this->assign('goods_name', $group_buy['goods_name']);
                    $this->assign('goods_number', $order['goods_number']);
                    $this->assign('order_sn', $order['order_sn']);
                    $this->assign('order_amount', CommonHelper::price_format($order['order_amount']));
                    $this->assign('shop_url', ecs()->url().'user.php?act=order_detail&order_id='.$order['order_id']);
                    $this->assign('shop_name', cfg('shop_name'));
                    $this->assign('send_date', TimeHelper::local_date(cfg('date_format')));

                    // 取得模板内容，发邮件
                    $content = $this->fetch('str:'.$tpl['template_content']);
                    if (BaseHelper::send_mail($order['consignee'], $order['email'], $tpl['template_subject'], $content, $tpl['is_html'])) {
                        $send_count++;
                    }
                    $count++;
                }

                // 提示信息
                return $this->sys_msg(sprintf(lang('mail_result'), $count, $send_count));
            } else {
                // 保存团购信息
                $goods_id = intval($_POST['goods_id']);
                if ($goods_id <= 0) {
                    return $this->sys_msg(lang('error_goods_null'));
                }
                $info = $this->goods_group_buy($goods_id);
                if ($info && $info['act_id'] != $group_buy_id) {
                    return $this->sys_msg(lang('error_goods_exist'));
                }

                $goods_name = DB::table('goods')->where('goods_id', $goods_id)->value('goods_name');

                $act_name = empty($_POST['act_name']) ? $goods_name : Str::substr($_POST['act_name'], 0, 255, false);

                $deposit = floatval($_POST['deposit']);
                if ($deposit < 0) {
                    $deposit = 0;
                }

                $restrict_amount = intval($_POST['restrict_amount']);
                if ($restrict_amount < 0) {
                    $restrict_amount = 0;
                }

                $gift_integral = intval($_POST['gift_integral']);
                if ($gift_integral < 0) {
                    $gift_integral = 0;
                }

                $price_ladder = [];
                $count = count($_POST['ladder_amount']);
                for ($i = $count - 1; $i >= 0; $i--) {
                    // 如果数量小于等于0，不要
                    $amount = intval($_POST['ladder_amount'][$i]);
                    if ($amount <= 0) {
                        continue;
                    }

                    // 如果价格小于等于0，不要
                    $price = round(floatval($_POST['ladder_price'][$i]), 2);
                    if ($price <= 0) {
                        continue;
                    }

                    // 加入价格阶梯
                    $price_ladder[$amount] = ['amount' => $amount, 'price' => $price];
                }
                if (count($price_ladder) < 1) {
                    return $this->sys_msg(lang('error_price_ladder'));
                }

                // 限购数量不能小于价格阶梯中的最大数量
                $amount_list = array_keys($price_ladder);
                if ($restrict_amount > 0 && max($amount_list) > $restrict_amount) {
                    return $this->sys_msg(lang('error_restrict_amount'));
                }

                ksort($price_ladder);
                $price_ladder = array_values($price_ladder);

                // 检查开始时间和结束时间是否合理
                $start_time = TimeHelper::local_strtotime($_POST['start_time']);
                $end_time = TimeHelper::local_strtotime($_POST['end_time']);
                if ($start_time >= $end_time) {
                    return $this->sys_msg(lang('invalid_time'));
                }

                $group_buy = [
                    'act_name' => $act_name,
                    'act_desc' => $_POST['act_desc'],
                    'act_type' => GAT_GROUP_BUY,
                    'goods_id' => $goods_id,
                    'goods_name' => $goods_name,
                    'start_time' => $start_time,
                    'end_time' => $end_time,
                    'ext_info' => serialize([
                        'price_ladder' => $price_ladder,
                        'restrict_amount' => $restrict_amount,
                        'gift_integral' => $gift_integral,
                        'deposit' => $deposit,
                    ]),
                ];

                // 清除缓存
                $this->clear_cache_files();

                // 保存数据
                if ($group_buy_id > 0) {
                    // update
                    DB::table('goods_activity')->where('act_id', $group_buy_id)->update($group_buy);

                    // log
                    $this->admin_log(addslashes($goods_name).'['.$group_buy_id.']', 'edit', 'group_buy');

                    // todo 更新活动表

                    // 提示信息
                    $links = [
                        ['href' => 'group_buy.php?act=list&'.MainHelper::list_link_postfix(), 'text' => lang('back_list')],
                    ];

                    return $this->sys_msg(lang('edit_success'), 0, $links);
                } else {
                    // insert
                    DB::table('goods_activity')->insert($group_buy);

                    // log
                    $this->admin_log(addslashes($goods_name), 'add', 'group_buy');

                    // 提示信息
                    $links = [
                        ['href' => 'group_buy.php?act=add', 'text' => lang('continue_add')],
                        ['href' => 'group_buy.php?act=list', 'text' => lang('back_list')],
                    ];

                    return $this->sys_msg(lang('add_success'), 0, $links);
                }
            }
        }

        /**
         * 批量删除团购活动
         */
        if ($action === 'batch_drop') {
            if (isset($_POST['checkboxes'])) {
                $del_count = 0; // 初始化删除数量
                foreach ($_POST['checkboxes'] as $key => $id) {
                    // 取得团购活动信息
                    $group_buy = GoodsHelper::group_buy_info($id);

                    // 如果团购活动已经有订单，不能删除
                    if ($group_buy['valid_order'] <= 0) {
                        // 删除团购活动
                        DB::table('goods_activity')
                            ->where('act_id', $id)
                            ->limit(1)
                            ->delete();

                        $this->admin_log(addslashes($group_buy['goods_name']).'['.$id.']', 'remove', 'group_buy');
                        $del_count++;
                    }
                }

                // 如果删除了团购活动，清除缓存
                if ($del_count > 0) {
                    $this->clear_cache_files();
                }

                $links[] = ['text' => lang('back_list'), 'href' => 'group_buy.php?act=list'];

                return $this->sys_msg(sprintf(lang('batch_drop_success'), $del_count), 0, $links);
            } else {
                $links[] = ['text' => lang('back_list'), 'href' => 'group_buy.php?act=list'];

                return $this->sys_msg(lang('no_select_group_buy'), 0, $links);
            }
        }

        /**
         * 搜索商品
         */
        if ($action === 'search_goods') {
            $this->check_authz_json('group_by');

            $filter = json_decode($_GET['JSON']);
            $arr = MainHelper::get_goods_list($filter);

            return $this->make_json_result($arr);
        }

        /**
         * 编辑保证金
         */
        if ($action === 'edit_deposit') {
            $this->check_authz_json('group_by');

            $id = intval($_POST['id']);
            $val = floatval($_POST['val']);

            $ext_info = DB::table('goods_activity')
                ->where('act_id', $id)
                ->where('act_type', GAT_GROUP_BUY)
                ->value('ext_info');
            $ext_info = unserialize($ext_info);
            $ext_info['deposit'] = $val;

            DB::table('goods_activity')
                ->where('act_id', $id)
                ->update(['ext_info' => serialize($ext_info)]);

            $this->clear_cache_files();

            return $this->make_json_result(number_format($val, 2));
        }

        /**
         * 编辑保证金
         */
        if ($action === 'edit_restrict_amount') {
            $this->check_authz_json('group_by');

            $id = intval($_POST['id']);
            $val = intval($_POST['val']);

            $ext_info = DB::table('goods_activity')
                ->where('act_id', $id)
                ->where('act_type', GAT_GROUP_BUY)
                ->value('ext_info');
            $ext_info = unserialize($ext_info);
            $ext_info['restrict_amount'] = $val;

            DB::table('goods_activity')
                ->where('act_id', $id)
                ->update(['ext_info' => serialize($ext_info)]);

            $this->clear_cache_files();

            return $this->make_json_result($val);
        }

        /**
         * 删除团购活动
         */
        if ($action === 'remove') {
            $this->check_authz_json('group_by');

            $id = intval($_GET['id']);

            // 取得团购活动信息
            $group_buy = GoodsHelper::group_buy_info($id);

            // 如果团购活动已经有订单，不能删除
            if ($group_buy['valid_order'] > 0) {
                return $this->make_json_error(lang('error_exist_order'));
            }

            // 删除团购活动
            DB::table('goods_activity')->where('act_id', $id)->limit(1)->delete();

            $this->admin_log(addslashes($group_buy['goods_name']).'['.$id.']', 'remove', 'group_buy');

            $this->clear_cache_files();

            $url = 'group_buy.php?act=query&'.str_replace('act=remove', '', $_SERVER['QUERY_STRING']);

            return response()->redirectTo($url);
        }
    }

    /*
     * 取得团购活动列表
     * @return   array
     */
    private function group_buy_list()
    {
        $result = MainHelper::get_filter();
        if ($result === false) {
            // 过滤条件
            $filter['keyword'] = empty($_REQUEST['keyword']) ? '' : trim($_REQUEST['keyword']);
            if (isset($_REQUEST['is_ajax']) && $_REQUEST['is_ajax'] === 1) {
                $filter['keyword'] = BaseHelper::json_str_iconv($filter['keyword']);
            }
            $filter['sort_by'] = empty($_REQUEST['sort_by']) ? 'act_id' : trim($_REQUEST['sort_by']);
            $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);

            $query = DB::table('goods_activity')
                ->where('act_type', GAT_GROUP_BUY);

            if (! empty($filter['keyword'])) {
                $query->where('goods_name', 'LIKE', '%'.BaseHelper::mysql_like_quote($filter['keyword']).'%');
            }

            $filter['record_count'] = $query->count();

            // 分页大小
            $filter = MainHelper::page_and_size($filter);

            // 查询
            $query->orderBy($filter['sort_by'], $filter['sort_order'])
                ->offset($filter['start'])
                ->limit($filter['page_size']);

            $sql = $query->toRawSql();

            MainHelper::set_filter($filter, $sql);
        } else {
            $sql = $result['sql'];
            $filter = $result['filter'];
        }

        if (isset($query)) {
            $res = $query->get()->map(fn ($item) => (array) $item)->all();
        } else {
            $res = DB::select($sql);
            $res = array_map(fn ($item) => (array) $item, $res);
        }

        $list = [];
        foreach ($res as $row) {
            $ext_info = unserialize($row['ext_info']);
            $stat = GoodsHelper::group_buy_stat($row['act_id'], $ext_info['deposit']);
            $arr = array_merge($row, $stat, $ext_info);

            // 处理价格阶梯
            $price_ladder = $arr['price_ladder'];
            if (! is_array($price_ladder) || empty($price_ladder)) {
                $price_ladder = [['amount' => 0, 'price' => 0]];
            } else {
                foreach ($price_ladder as $key => $amount_price) {
                    $price_ladder[$key]['formated_price'] = CommonHelper::price_format($amount_price['price']);
                }
            }

            // 计算当前价
            $cur_price = $price_ladder[0]['price'];    // 初始化
            $cur_amount = $stat['valid_goods'];         // 当前数量
            foreach ($price_ladder as $amount_price) {
                if ($cur_amount >= $amount_price['amount']) {
                    $cur_price = $amount_price['price'];
                } else {
                    break;
                }
            }

            $arr['cur_price'] = $cur_price;

            $status = GoodsHelper::group_buy_status($arr);

            $arr['start_time'] = TimeHelper::local_date(cfg('date_format'), $arr['start_time']);
            $arr['end_time'] = TimeHelper::local_date(cfg('date_format'), $arr['end_time']);
            $arr['cur_status'] = lang('gbs')[$status];

            $list[] = $arr;
        }
        $arr = ['item' => $list, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']];

        return $arr;
    }

    /**
     * 取得某商品的团购活动
     *
     * @param  int  $goods_id  商品id
     * @return array
     */
    private function goods_group_buy($goods_id)
    {
        $res = DB::table('goods_activity')
            ->where('goods_id', $goods_id)
            ->where('act_type', GAT_GROUP_BUY)
            ->where('start_time', '<=', TimeHelper::gmtime())
            ->where('end_time', '>=', TimeHelper::gmtime())
            ->first();

        return $res ? (array) $res : [];
    }

    /**
     * 列表链接
     *
     * @param  bool  $is_add  是否添加（插入）
     * @return array('href' => $href, 'text' => $text)
     */
    private function list_link($is_add = true)
    {
        $href = 'group_buy.php?act=list';
        if (! $is_add) {
            $href .= '&'.MainHelper::list_link_postfix();
        }

        return ['href' => $href, 'text' => lang('group_buy_list')];
    }
}
