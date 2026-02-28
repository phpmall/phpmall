<?php

declare(strict_types=1);

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class TransactionHelper
{
    /**
     * 修改个人资料（Email, 性别，生日)
     *
     * @param  array  $profile  array_keys(user_id int, email string, sex int, birthday string);
     * @return bool $bool
     */
    public static function edit_profile($profile): bool
    {
        if (empty($profile['user_id'])) {
            err()->add(lang('not_login'));

            return false;
        }

        $cfg = [];
        $cfg['username'] = DB::table('user')->where('user_id', $profile['user_id'])->value('user_name');
        if (isset($profile['sex'])) {
            $cfg['gender'] = intval($profile['sex']);
        }
        if (! empty($profile['email'])) {
            if (! CommonHelper::is_email($profile['email'])) {
                err()->add(sprintf(lang('email_invalid'), $profile['email']));

                return false;
            }
            $cfg['email'] = $profile['email'];
        }
        if (! empty($profile['birthday'])) {
            $cfg['bday'] = $profile['birthday'];
        }

        if (! user()->edit_user($cfg)) {
            if (user()->error === ERR_EMAIL_EXISTS) {
                err()->add(sprintf(lang('email_exist'), $profile['email']));
            } else {
                err()->add('DB ERROR!');
            }

            return false;
        }

        // 过滤非法的键值
        $other_key_array = ['msn', 'qq', 'office_phone', 'home_phone', 'mobile_phone'];
        foreach ($profile['other'] as $key => $val) {
            // 删除非法key值
            if (! in_array($key, $other_key_array)) {
                unset($profile['other'][$key]);
            } else {
                $profile['other'][$key] = htmlspecialchars(trim($val)); // 防止用户输入javascript代码
            }
        }
        // 修改在其他资料
        if (! empty($profile['other'])) {
            DB::table('user')->where('user_id', $profile['user_id'])->update($profile['other']);
        }

        return true;
    }

    /**
     * 获取用户帐号信息
     *
     * @param  int  $user_id  用户user_id
     * @return void
     */
    public static function get_profile($user_id): ?array
    {
        // 会员帐号信息
        $infos = (array) DB::table('user')
            ->select('user_name', 'birthday', 'sex', 'question', 'answer', 'rank_points', 'pay_points', 'user_money', 'user_rank', 'msn', 'qq', 'office_phone', 'home_phone', 'mobile_phone', 'passwd_question', 'passwd_answer')
            ->where('user_id', $user_id)
            ->first();

        $infos['user_name'] = addslashes($infos['user_name'] ?? '');

        $row = user()->get_profile_by_name($infos['user_name']); // 获取用户帐号信息
        Session::put('email', $row['email']);    // 注册SESSION

        // 会员等级
        if (($infos['user_rank'] ?? 0) > 0) {
            $row = (array) DB::table('user_rank')
                ->select('rank_id', 'rank_name', 'discount')
                ->where('rank_id', $infos['user_rank'])
                ->first();
        } else {
            $row = (array) DB::table('user_rank')
                ->select('rank_id', 'rank_name', 'discount', 'min_points')
                ->where('min_points', '<=', (int) ($infos['rank_points'] ?? 0))
                ->orderByDesc('min_points')
                ->first();
        }

        $info = [];
        if (! empty($row)) {
            $info['rank_name'] = $row['rank_name'];
        } else {
            $info['rank_name'] = lang('undifine_rank');
        }

        $cur_date = date('Y-m-d H:i:s');

        // 会员红包
        $bonus = DB::table('activity_bonus as t1')
            ->select('t1.type_name', 't1.type_money')
            ->join('user_bonus as t2', 't1.type_id', '=', 't2.bonus_type_id')
            ->where('t2.user_id', $user_id)
            ->where('t1.use_start_date', '<=', $cur_date)
            ->where('t1.use_end_date', '>', $cur_date)
            ->where('t2.order_id', 0)
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();

        if ($bonus) {
            foreach ($bonus as $key => $val) {
                $bonus[$key]['type_money'] = CommonHelper::price_format($val['type_money'], false);
            }
        }

        $info['discount'] = (Session::get('discount') ?? 1) * 100 .'%';
        $info['email'] = Session::get('email');
        $info['user_name'] = Session::get('user_name');
        $info['rank_points'] = $infos['rank_points'] ?? '';
        $info['pay_points'] = $infos['pay_points'] ?? 0;
        $info['user_money'] = $infos['user_money'] ?? 0;
        $info['sex'] = $infos['sex'] ?? 0;
        $info['birthday'] = $infos['birthday'] ?? '';
        $info['question'] = isset($infos['question']) ? htmlspecialchars($infos['question']) : '';

        $info['user_money'] = CommonHelper::price_format($info['user_money'], false);
        $info['pay_points'] = $info['pay_points'].cfg('integral_name');
        $info['bonus'] = $bonus;
        $info['qq'] = $infos['qq'] ?? '';
        $info['msn'] = $infos['msn'] ?? '';
        $info['office_phone'] = $infos['office_phone'] ?? '';
        $info['home_phone'] = $infos['home_phone'] ?? '';
        $info['mobile_phone'] = $infos['mobile_phone'] ?? '';
        $info['passwd_question'] = $infos['passwd_question'] ?? '';
        $info['passwd_answer'] = $infos['passwd_answer'] ?? '';

        return $info;
    }

    /**
     * 取得收货人地址列表
     *
     * @param  int  $user_id  用户编号
     */
    public static function get_consignee_list($user_id): array
    {
        return DB::table('user_address')
            ->where('user_id', $user_id)
            ->limit(5)
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();
    }

    /**
     *  给指定用户添加一个指定红包
     *
     * @param  int  $user_id  用户ID
     * @param  string  $bouns_sn  红包序列号
     * @return bool $result
     */
    public static function add_bonus($user_id, $bouns_sn)
    {
        if (empty($user_id)) {
            err()->add(lang('not_login'));

            return false;
        }

        // 查询红包序列号是否已经存在
        $row = (array) DB::table('user_bonus')
            ->select('bonus_id', 'bonus_sn', 'user_id', 'bonus_type_id')
            ->where('bonus_sn', $bouns_sn)
            ->first();

        if ($row) {
            if ($row['user_id'] === 0) {
                // 红包没有被使用
                $bonus_time = (array) DB::table('activity_bonus')
                    ->select('send_end_date', 'use_end_date')
                    ->where('type_id', $row['bonus_type_id'])
                    ->first();

                $now = TimeHelper::gmtime();
                if ($now > $bonus_time['use_end_date']) {
                    err()->add(lang('bonus_use_expire'));

                    return false;
                }

                $result = DB::table('user_bonus')
                    ->where('bonus_id', $row['bonus_id'])
                    ->update(['user_id' => $user_id]);

                if ($result) {
                    return true;
                } else {
                    return false;
                }
            } else {
                if ($row['user_id'] === $user_id) {
                    // 红包已经添加过了。
                    err()->add(lang('bonus_is_used'));
                } else {
                    // 红包被其他人使用过了。
                    err()->add(lang('bonus_is_used_by_other'));
                }

                return false;
            }
        } else {
            // 红包不存在
            err()->add(lang('bonus_not_exist'));

            return false;
        }
    }

    /**
     *  获取用户指定范围的订单列表
     *
     * @param  int  $user_id  用户ID号
     * @param  int  $num  列表最大数量
     * @param  int  $start  列表起始位置
     * @return array $order_list     订单列表
     */
    public static function get_user_orders($user_id, $num = 10, $start = 0): array
    {
        // 取得订单列表
        $res = DB::table('order_info')
            ->select('order_id', 'order_sn', 'order_status', 'shipping_status', 'pay_status', 'add_time', DB::raw('(goods_amount + shipping_fee + insure_fee + pay_fee + pack_fee + card_fee + tax - discount) AS total_fee'))
            ->where('user_id', $user_id)
            ->orderByDesc('add_time')
            ->offset($start)
            ->limit($num)
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();

        $arr = [];
        foreach ($res as $row) {
            if ($row['order_status'] === OS_UNCONFIRMED) {
                $row['handler'] = '<a href="user.php?act=cancel_order&order_id='.$row['order_id']."\" onclick=\"if (!confirm('".lang('confirm_cancel')."')) return false;\">".lang('cancel').'</a>';
            } elseif ($row['order_status'] === OS_SPLITED) {
                // 对配送状态的处理
                if ($row['shipping_status'] === SS_SHIPPED) {
                    @$row['handler'] = '<a href="user.php?act=affirm_received&order_id='.$row['order_id']."\" onclick=\"if (!confirm('".lang('confirm_received')."')) return false;\">".lang('received').'</a>';
                } elseif ($row['shipping_status'] === SS_RECEIVED) {
                    @$row['handler'] = '<span style="color:red">'.lang('ss_received').'</span>';
                } else {
                    if ($row['pay_status'] === PS_UNPAYED) {
                        @$row['handler'] = '<a href="user.php?act=order_detail&order_id='.$row['order_id'].'">'.lang('pay_money').'</a>';
                    } else {
                        @$row['handler'] = '<a href="user.php?act=order_detail&order_id='.$row['order_id'].'">'.lang('view_order').'</a>';
                    }
                }
            } else {
                $row['handler'] = '<span style="color:red">'.(isset(lang('os')[$row['order_status']]) ? lang('os')[$row['order_status']] : '').'</span>';
            }

            $row['shipping_status'] = ($row['shipping_status'] === SS_SHIPPED_ING) ? SS_PREPARING : $row['shipping_status'];
            $row['order_status'] = (isset(lang('os')[$row['order_status']]) ? lang('os')[$row['order_status']] : '').','.(isset(lang('ps')[$row['pay_status']]) ? lang('ps')[$row['pay_status']] : '').','.(isset(lang('ss')[$row['shipping_status']]) ? lang('ss')[$row['shipping_status']] : '');

            $arr[] = ['order_id' => $row['order_id'],
                'order_sn' => $row['order_sn'],
                'order_time' => TimeHelper::local_date(cfg('time_format'), $row['add_time']),
                'order_status' => $row['order_status'],
                'total_fee' => CommonHelper::price_format($row['total_fee'], false),
                'handler' => $row['handler']];
        }

        return $arr;
    }

    /**
     * 取消一个用户订单
     *
     * @param  int  $order_id  订单ID
     * @param  int  $user_id  用户ID
     * @return void
     */
    public static function cancel_order($order_id, $user_id = 0): bool
    {
        // 查询订单信息，检查状态
        $order = (array) DB::table('order_info')
            ->select('user_id', 'order_id', 'order_sn', 'surplus', 'integral', 'bonus_id', 'order_status', 'shipping_status', 'pay_status')
            ->where('order_id', $order_id)
            ->first();

        if (empty($order)) {
            err()->add(lang('order_exist'));

            return false;
        }

        // 如果用户ID大于0，检查订单是否属于该用户
        if ($user_id > 0 && $order['user_id'] != $user_id) {
            err()->add(lang('no_priv'));

            return false;
        }

        // 订单状态只能是“未确认”或“已确认”
        if ($order['order_status'] != OS_UNCONFIRMED && $order['order_status'] != OS_CONFIRMED) {
            err()->add(lang('current_os_not_unconfirmed'));

            return false;
        }

        // 订单一旦确认，不允许用户取消
        if ($order['order_status'] === OS_CONFIRMED) {
            err()->add(lang('current_os_already_confirmed'));

            return false;
        }

        // 发货状态只能是“未发货”
        if ($order['shipping_status'] != SS_UNSHIPPED) {
            err()->add(lang('current_ss_not_cancel'));

            return false;
        }

        // 如果付款状态是“已付款”、“付款中”，不允许取消，要取消和商家联系
        if ($order['pay_status'] != PS_UNPAYED) {
            err()->add(lang('current_ps_not_cancel'));

            return false;
        }

        // 将用户订单设置为取消
        $result = DB::table('order_info')
            ->where('order_id', $order_id)
            ->update(['order_status' => OS_CANCELED]);

        if ($result) {
            // 记录log
            CommonHelper::order_action($order['order_sn'], OS_CANCELED, $order['shipping_status'], PS_UNPAYED, lang('buyer_cancel'), 'buyer');
            // 退货用户余额、积分、红包
            if ($order['user_id'] > 0 && $order['surplus'] > 0) {
                $change_desc = sprintf(lang('return_surplus_on_cancel'), $order['order_sn']);
                CommonHelper::log_account_change($order['user_id'], $order['surplus'], 0, 0, 0, $change_desc);
            }
            if ($order['user_id'] > 0 && $order['integral'] > 0) {
                $change_desc = sprintf(lang('return_integral_on_cancel'), $order['order_sn']);
                CommonHelper::log_account_change($order['user_id'], 0, 0, 0, $order['integral'], $change_desc);
            }
            if ($order['user_id'] > 0 && $order['bonus_id'] > 0) {
                OrderHelper::change_user_bonus($order['bonus_id'], $order['order_id'], false);
            }

            // 如果使用库存，且下订单时减库存，则增加库存
            if (cfg('use_storage') === '1' && cfg('stock_dec_time') === SDT_PLACE) {
                OrderHelper::change_order_goods_storage($order['order_id'], false, 1);
            }

            // 修改订单
            $arr = [
                'bonus_id' => 0,
                'bonus' => 0,
                'integral' => 0,
                'integral_money' => 0,
                'surplus' => 0,
            ];
            OrderHelper::update_order($order['order_id'], $arr);

            return true;

            return false;
        }
    }

    /**
     * 确认一个用户订单
     *
     * @param  int  $order_id  订单ID
     * @param  int  $user_id  用户ID
     * @return bool $bool
     */
    public static function affirm_received($order_id, $user_id = 0): bool
    {
        // 查询订单信息，检查状态
        $order = (array) DB::table('order_info')
            ->select('user_id', 'order_sn', 'order_status', 'shipping_status', 'pay_status')
            ->where('order_id', $order_id)
            ->first();

        // 如果用户ID大于 0 。检查订单是否属于该用户
        if ($user_id > 0 && ($order['user_id'] ?? 0) != $user_id) {
            err()->add(lang('no_priv'));

            return false;
        } // 检查订单
        elseif ($order['shipping_status'] === SS_RECEIVED) {
            err()->add(lang('order_already_received'));

            return false;
        } elseif ($order['shipping_status'] != SS_SHIPPED) {
            err()->add(lang('order_invalid'));

            return false;
        } // 修改订单发货状态为“确认收货”
        else {
            $result = DB::table('order_info')
                ->where('order_id', $order_id)
                ->update(['shipping_status' => SS_RECEIVED]);

            if ($result) {
                // 记录日志
                CommonHelper::order_action($order['order_sn'], $order['order_status'], SS_RECEIVED, $order['pay_status'], '', lang('buyer'));

                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * 保存用户的收货人信息
     * 如果收货人信息中的 id 为 0 则新增一个收货人信息
     *
     * @param  array  $consignee
     * @param  bool  $default  是否将该收货人信息设置为默认收货人信息
     */
    public static function save_consignee($consignee, $default = false): bool
    {
        if ($consignee['address_id'] > 0) {
            // 修改地址
            $res = DB::table('user_address')
                ->where('address_id', $consignee['address_id'])
                ->where('user_id', Session::get('user_id'))
                ->update($consignee);
        } else {
            // 添加地址
            $consignee['address_id'] = DB::table('user_address')->insertGetId($consignee);
            $res = $consignee['address_id'] > 0;
        }

        if ($default) {
            // 保存为用户的默认收货地址
            DB::table('user')
                ->where('user_id', Session::get('user_id'))
                ->update(['address_id' => $consignee['address_id']]);
        }

        return $res !== false;
    }

    /**
     * 删除一个收货地址
     *
     * @param  int  $id
     */
    public static function drop_consignee($id): bool
    {
        $uid = DB::table('user_address')->where('address_id', $id)->value('user_id');

        if ($uid != Session::get('user_id')) {
            return false;
        } else {
            return (bool) DB::table('user_address')->where('address_id', $id)->delete();
        }
    }

    /**
     *  添加或更新指定用户收货地址
     *
     * @param  array  $address
     */
    public static function update_address($address): bool
    {
        $address_id = (int) ($address['address_id'] ?? 0);
        unset($address['address_id']);

        if ($address_id > 0) {
            // 更新指定记录
            DB::table('user_address')
                ->where('address_id', $address_id)
                ->where('user_id', $address['user_id'])
                ->update($address);
        } else {
            // 插入一条新记录
            $address_id = DB::table('user_address')->insertGetId($address);
        }

        if (isset($address['defalut']) && $address['default'] > 0 && isset($address['user_id'])) {
            DB::table('user')
                ->where('user_id', $address['user_id'])
                ->update(['address_id' => $address_id]);
        }

        return true;
    }

    /**
     *  获取指订单的详情
     *
     * @param  int  $order_id  订单ID
     * @param  int  $user_id  用户ID
     * @return arr $order          订单所有信息的数组
     */
    public static function get_order_detail($order_id, $user_id = 0): array|bool
    {
        $order_id = intval($order_id);
        if ($order_id <= 0) {
            err()->add(lang('invalid_order_id'));

            return false;
        }
        $order = OrderHelper::order_info($order_id);

        // 检查订单是否属于该用户
        if ($user_id > 0 && $user_id != $order['user_id']) {
            err()->add(lang('no_priv'));

            return false;
        }

        // 对发货号处理
        if (! empty($order['invoice_no'])) {
            $shipping_code = DB::table('shipping')->where('shipping_id', $order['shipping_id'])->value('shipping_code');
            $plugin = ROOT_PATH.'includes/modules/shipping/'.$shipping_code.'.php';
            if (file_exists($plugin)) {
                include_once $plugin;
                $shipping = new $shipping_code;
                $order['invoice_no'] = $shipping->query($order['invoice_no']);
            }
        }

        // 只有未确认才允许用户修改订单地址
        if ($order['order_status'] === OS_UNCONFIRMED) {
            $order['allow_update_address'] = 1; // 允许修改收货地址
        } else {
            $order['allow_update_address'] = 0;
        }

        // 获取订单中实体商品数量
        $order['exist_real_goods'] = OrderHelper::exist_real_goods($order_id);

        // 如果是未付款状态，生成支付按钮
        if ($order['pay_status'] === PS_UNPAYED &&
            ($order['order_status'] === OS_UNCONFIRMED ||
                $order['order_status'] === OS_CONFIRMED)) {
            /*
             * 在线支付按钮
             */
            // 支付方式信息
            $payment_info = [];
            $payment_info = OrderHelper::payment_info($order['pay_id']);

            // 无效支付方式
            if ($payment_info === false) {
                $order['pay_online'] = '';
            } else {
                // 取得支付信息，生成支付代码
                $payment = OrderHelper::unserialize_config($payment_info['pay_config']);

                // 获取需要支付的log_id
                $order['log_id'] = ClipsHelper::get_paylog_id($order['order_id'], $pay_type = PAY_ORDER);
                $order['user_name'] = Session::get('user_name');
                $order['pay_desc'] = $payment_info['pay_desc'];

                // 调用相应的支付方式文件
                include_once ROOT_PATH.'includes/modules/payment/'.$payment_info['pay_code'].'.php';

                // 取得在线支付方式的支付按钮
                $pay_obj = new $payment_info['pay_code'];
                $order['pay_online'] = $pay_obj->get_code($order, $payment);
            }
        } else {
            $order['pay_online'] = '';
        }

        // 无配送时的处理
        $order['shipping_id'] === -1 and $order['shipping_name'] = lang('shipping_not_need');

        // 其他信息初始化
        $order['how_oos_name'] = $order['how_oos'];
        $order['how_surplus_name'] = $order['how_surplus'];

        // 虚拟商品付款后处理
        if ($order['pay_status'] != PS_UNPAYED) {
            // 取得已发货的虚拟商品信息
            $virtual_goods = CommonHelper::get_virtual_goods($order_id, true);
            $virtual_card = [];
            foreach ($virtual_goods as $code => $goods_list) {
                // 只处理虚拟卡
                if ($code === 'virtual_card') {
                    foreach ($goods_list as $goods) {
                        if ($info = CommonHelper::virtual_card_result($order['order_sn'], $goods)) {
                            $virtual_card[] = ['goods_id' => $goods['goods_id'], 'goods_name' => $goods['goods_name'], 'info' => $info];
                        }
                    }
                }
                // 处理超值礼包里面的虚拟卡
                if ($code === 'package_buy') {
                    foreach ($goods_list as $goods) {
                        $vcard_arr = DB::table('activity_package as pg')
                            ->select('g.goods_id')
                            ->join('goods as g', 'pg.goods_id', '=', 'g.goods_id')
                            ->where('pg.package_id', $goods['goods_id'])
                            ->where('g.extension_code', 'virtual_card')
                            ->get()
                            ->map(fn ($item) => (array) $item)
                            ->all();

                        foreach ($vcard_arr as $val) {
                            if ($info = CommonHelper::virtual_card_result($order['order_sn'], $val)) {
                                $virtual_card[] = ['goods_id' => $goods['goods_id'], 'goods_name' => $goods['goods_name'], 'info' => $info];
                            }
                        }
                    }
                }
            }
            $var_card = TransactionHelper::deleteRepeat($virtual_card);
            tpl()->assign('virtual_card', $var_card);
        }

        // 确认时间 支付时间 发货时间
        if ($order['confirm_time'] > 0 && ($order['order_status'] === OS_CONFIRMED || $order['order_status'] === OS_SPLITED || $order['order_status'] === OS_SPLITING_PART)) {
            $order['confirm_time'] = sprintf(lang('confirm_time'), TimeHelper::local_date(cfg('time_format'), $order['confirm_time']));
        } else {
            $order['confirm_time'] = '';
        }
        if ($order['pay_time'] > 0 && $order['pay_status'] != PS_UNPAYED) {
            $order['pay_time'] = sprintf(lang('pay_time'), TimeHelper::local_date(cfg('time_format'), $order['pay_time']));
        } else {
            $order['pay_time'] = '';
        }
        if ($order['shipping_time'] > 0 && in_array($order['shipping_status'], [SS_SHIPPED, SS_RECEIVED])) {
            $order['shipping_time'] = sprintf(lang('shipping_time'), TimeHelper::local_date(cfg('time_format'), $order['shipping_time']));
        } else {
            $order['shipping_time'] = '';
        }

        return $order;
    }

    /**
     *  获取用户可以和并的订单数组
     *
     * @param  int  $user_id  用户ID
     * @return array $merge          可合并订单数组
     */
    public static function get_user_merge($user_id): array
    {
        $list = DB::table('order_info')
            ->where('user_id', $user_id)
            ->whereRaw(order_query_sql('unprocessed'))
            ->where('extension_code', '')
            ->orderByDesc('add_time')
            ->pluck('order_sn')
            ->all();

        $merge = [];
        foreach ($list as $val) {
            $merge[$val] = $val;
        }

        return $merge;
    }

    /**
     *  合并指定用户订单
     *
     * @param  string  $from_order  合并的从订单号
     * @param  string  $to_order  合并的主订单号
     * @return bool $bool
     */
    public static function merge_user_order($from_order, $to_order, $user_id = 0): bool
    {
        if ($user_id > 0) {
            // 检查订单是否属于指定用户
            if (strlen($to_order) > 0) {
                $order_user = DB::table('order_info')->where('order_sn', $to_order)->value('user_id');
                if ($order_user != $user_id) {
                    err()->add(lang('no_priv'));
                }
            } else {
                err()->add(lang('order_sn_empty'));

                return false;
            }
        }

        $result = OrderHelper::merge_order($from_order, $to_order);
        if ($result === true) {
            return true;
        } else {
            err()->add($result);

            return false;
        }
    }

    /**
     *  将指定订单中的商品添加到购物车
     *
     * @param  int  $order_id
     * @return mixeded $message        成功返回true, 错误返回出错信息
     */
    public static function return_to_cart($order_id): mixed
    {
        // 初始化基本件数量 goods_id => goods_number
        $basic_number = [];

        $res = DB::table('order_goods')
            ->select('goods_id', 'product_id', 'goods_number', 'goods_attr', 'parent_id', 'goods_attr_id')
            ->where('order_id', $order_id)
            ->where('is_gift', 0)
            ->where('extension_code', '!=', 'package_buy')
            ->orderBy('parent_id')
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();

        $time = TimeHelper::gmtime();
        $now = date('Y-m-d H:i:s', $time);
        foreach ($res as $row) {
            // 查该商品信息：是否删除、是否上架
            $goods = (array) DB::table('goods')
                ->select('goods_sn', 'goods_name', 'goods_number', DB::raw("IF(is_promote = 1 AND '$now' BETWEEN promote_start_date AND promote_end_date, promote_price, shop_price) AS goods_price"), 'is_real', 'extension_code', 'is_alone_sale', 'goods_type')
                ->where('goods_id', $row['goods_id'])
                ->where('is_delete', 0)
                ->first();

            // 如果该商品不存在，处理下一个商品
            if (empty($goods)) {
                continue;
            }
            if ($row['product_id']) {
                $product_number = DB::table('goods_product')->where('product_id', $row['product_id'])->value('product_number');
            }
            // 如果使用库存，且库存不足，修改数量
            if (cfg('use_storage') === 1 && ($row['product_id'] ? ($product_number < $row['goods_number']) : ($goods['goods_number'] < $row['goods_number']))) {
                if ($goods['goods_number'] === 0 || $product_number === 0) {
                    // 如果库存为0，处理下一个商品
                    continue;
                } else {
                    if ($row['product_id']) {
                        $row['goods_number'] = $product_number;
                    } else {
                        // 库存不为0，修改数量
                        $row['goods_number'] = $goods['goods_number'];
                    }
                }
            }

            // 检查商品价格是否有会员价格
            $temp_number = DB::table('user_cart')
                ->where('session_id', SESS_ID)
                ->where('goods_id', $row['goods_id'])
                ->where('rec_type', CART_GENERAL_GOODS)
                ->value('goods_number');
            $row['goods_number'] += $temp_number;

            $attr_array = empty($row['goods_attr_id']) ? [] : explode(',', $row['goods_attr_id']);
            $goods['goods_price'] = CommonHelper::get_final_price($row['goods_id'], $row['goods_number'], true, $attr_array);

            // 要返回购物车的商品
            $return_goods = [
                'goods_id' => $row['goods_id'],
                'goods_sn' => addslashes($goods['goods_sn']),
                'goods_name' => addslashes($goods['goods_name']),
                'market_price' => $goods['market_price'],
                'goods_price' => $goods['goods_price'],
                'goods_number' => $row['goods_number'],
                'goods_attr' => empty($row['goods_attr']) ? '' : addslashes($row['goods_attr']),
                'goods_attr_id' => empty($row['goods_attr_id']) ? '' : addslashes($row['goods_attr_id']),
                'is_real' => $goods['is_real'],
                'extension_code' => addslashes($goods['extension_code']),
                'parent_id' => '0',
                'is_gift' => '0',
                'rec_type' => CART_GENERAL_GOODS,
            ];

            // 如果是配件
            if ($row['parent_id'] > 0) {
                // 查询基本件信息：是否删除、是否上架、能否作为普通商品销售
                $parent = (array) DB::table('goods')
                    ->select('goods_id')
                    ->where('goods_id', $row['parent_id'])
                    ->where('is_delete', 0)
                    ->where('is_on_sale', 1)
                    ->where('is_alone_sale', 1)
                    ->first();

                if ($parent) {
                    // 如果基本件存在，查询组合关系是否存在
                    $fitting_price = DB::table('activity_group')
                        ->where('parent_id', $row['parent_id'])
                        ->where('goods_id', $row['goods_id'])
                        ->value('goods_price');
                    if ($fitting_price) {
                        // 如果组合关系存在，取配件价格，取基本件数量，改parent_id
                        $return_goods['parent_id'] = $row['parent_id'];
                        $return_goods['goods_price'] = $fitting_price;
                        $return_goods['goods_number'] = $basic_number[$row['parent_id']];
                    }
                }
            } else {
                // 保存基本件数量
                $basic_number[$row['goods_id']] = $row['goods_number'];
            }

            // 返回购物车：看有没有相同商品
            $cart_goods = DB::table('user_cart')
                ->where('session_id', SESS_ID)
                ->where('goods_id', $return_goods['goods_id'])
                ->where('goods_attr', $return_goods['goods_attr'])
                ->where('parent_id', $return_goods['parent_id'])
                ->where('is_gift', 0)
                ->where('rec_type', CART_GENERAL_GOODS)
                ->value('goods_id');

            if (empty($cart_goods)) {
                // 没有相同商品，插入
                $return_goods['session_id'] = SESS_ID;
                $return_goods['user_id'] = Session::get('user_id') ?? 0;
                DB::table('user_cart')->insert($return_goods);
            } else {
                // 有相同商品，修改数量
                DB::table('user_cart')
                    ->where('session_id', SESS_ID)
                    ->where('goods_id', $return_goods['goods_id'])
                    ->where('rec_type', CART_GENERAL_GOODS)
                    ->limit(1)
                    ->update([
                        'goods_number' => $return_goods['goods_number'],
                        'goods_price' => $return_goods['goods_price'],
                    ]);
            }
        }

        // 清空购物车的赠品
        DB::table('user_cart')
            ->where('session_id', SESS_ID)
            ->where('is_gift', 1)
            ->delete();

        return true;
    }

    /**
     *  保存用户收货地址
     *
     * @param  array  $address  array_keys(consignee string, email string, address string, zipcode string, tel string, mobile stirng, sign_building string, best_time string, order_id int)
     * @param  int  $user_id  用户ID
     * @return bool $bool
     */
    public static function save_order_address($address, $user_id)
    {
        err()->clean();
        // 数据验证
        empty($address['consignee']) and err()->add(lang('consigness_empty'));
        empty($address['address']) and err()->add(lang('address_empty'));
        $address['order_id'] === 0 and err()->add(lang('order_id_empty'));
        if (empty($address['email'])) {
            err()->add($GLOBALS['email_empty']);
        } else {
            if (! CommonHelper::is_email($address['email'])) {
                err()->add(sprintf(lang('email_invalid'), $address['email']));
            }
        }
        if (err()->error_no > 0) {
            return false;
        }

        // 检查订单状态
        $row = (array) DB::table('order_info')
            ->select('user_id', 'order_status')
            ->where('order_id', $address['order_id'])
            ->first();

        if ($row) {
            if ($user_id > 0 && $user_id != ($row['user_id'] ?? 0)) {
                err()->add(lang('no_priv'));

                return false;
            }
            if ($row['order_status'] != OS_UNCONFIRMED) {
                err()->add(lang('require_unconfirmed'));

                return false;
            }

            DB::table('order_info')
                ->where('order_id', $address['order_id'])
                ->update($address);

            return true;
        } else {
            // 订单不存在
            err()->add(lang('order_exist'));

            return false;
        }
    }

    /**
     * @param  int  $user_id  用户ID
     * @param  int  $num  列表显示条数
     * @param  int  $start  显示起始位置
     * @return array $arr             红保列表
     */
    public static function get_user_bouns_list($user_id, $num = 10, $start = 0)
    {
        $res = DB::table('user_bonus as u')
            ->select('u.bonus_sn', 'u.order_id', 'b.type_name', 'b.type_money', 'b.min_goods_amount', 'b.use_start_date', 'b.use_end_date')
            ->join('activity_bonus as b', 'u.bonus_type_id', '=', 'b.type_id')
            ->where('u.user_id', $user_id)
            ->offset($start)
            ->limit($num)
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();
        $arr = [];

        $day = getdate();
        $cur_date = TimeHelper::local_mktime(23, 59, 59, $day['mon'], $day['mday'], $day['year']);

        foreach ($res as $row) {
            // 先判断是否被使用，然后判断是否开始或过期
            if (empty($row['order_id'])) {
                // 没有被使用
                if ($row['use_start_date'] > $cur_date) {
                    $row['status'] = lang('not_start');
                } elseif ($row['use_end_date'] < $cur_date) {
                    $row['status'] = lang('overdue');
                } else {
                    $row['status'] = lang('not_use');
                }
            } else {
                $row['status'] = '<a href="user.php?act=order_detail&order_id='.$row['order_id'].'" >'.lang('had_use').'</a>';
            }

            $row['use_startdate'] = TimeHelper::local_date(cfg('date_format'), $row['use_start_date']);
            $row['use_enddate'] = TimeHelper::local_date(cfg('date_format'), $row['use_end_date']);

            $arr[] = $row;
        }

        return $arr;
    }

    /**
     * 去除虚拟卡中重复数据
     */
    public static function deleteRepeat($array)
    {
        $_card_sn_record = [];
        foreach ($array as $_k => $_v) {
            foreach ($_v['info'] as $__k => $__v) {
                if (in_array($__v['card_sn'], $_card_sn_record)) {
                    unset($array[$_k]['info'][$__k]);
                } else {
                    $_card_sn_record[] = $__v['card_sn'];
                }
            }
        }

        return $array;
    }
}
