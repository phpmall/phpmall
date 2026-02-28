<?php

declare(strict_types=1);

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class PaymentHelper
{
    /**
     * 取得返回信息地址
     *
     * @param  string  $code  支付方式代码
     */
    public static function return_url($code)
    {
        return ecs()->url().'respond.php?code='.$code;
    }

    /**
     *  取得某支付方式信息
     *
     * @param  string  $code  支付方式代码
     */
    public static function get_payment($code): array
    {
        $payment = (array) DB::table('payment')
            ->where('pay_code', $code)
            ->where('enabled', '1')
            ->first();

        if ($payment) {
            $config_list = unserialize($payment['pay_config']);

            foreach ($config_list as $config) {
                $payment[$config['name']] = $config['value'];
            }
        }

        return $payment;
    }

    /**
     *  通过订单sn取得订单ID
     *
     * @param  string  $voucher  是否为会员充值
     */
    public static function get_order_id_by_sn($order_sn, $voucher = 'false'): string
    {
        if ($voucher === 'true') {
            if (is_numeric($order_sn)) {
                return DB::table('order_pay')
                    ->where('order_id', $order_sn)
                    ->where('order_type', 1)
                    ->value('log_id');
            } else {
                return '';
            }
        } else {
            $order_id = 0;
            if (is_numeric($order_sn)) {
                $order_id = DB::table('order_info')->where('order_sn', $order_sn)->value('order_id');
            }
            if (! empty($order_id)) {
                return DB::table('order_pay')->where('order_id', $order_id)->value('log_id');
            } else {
                return '';
            }
        }
    }

    /**
     *  通过订单ID取得订单商品名称
     *
     * @param  string  $order_id  订单ID
     */
    public static function get_goods_name_by_id($order_id): string
    {
        $goods_name = DB::table('order_goods')
            ->where('order_id', $order_id)
            ->pluck('goods_name')
            ->all();

        return implode(',', $goods_name);
    }

    /**
     * 检查支付的金额是否与订单相符
     *
     * @param  string  $log_id  支付编号
     * @param  float  $money  支付接口返回的金额
     * @return true
     */
    public static function check_money($log_id, $money): bool
    {
        if (is_numeric($log_id)) {
            $amount = DB::table('order_pay')->where('log_id', $log_id)->value('order_amount');
        } else {
            return false;
        }

        return (float) $money === (float) $amount;
    }

    /**
     * 修改订单的支付状态
     *
     * @param  string  $log_id  支付编号
     * @param  int  $pay_status  状态
     * @param  string  $note  备注
     * @return void
     */
    public static function order_paid($log_id, $pay_status = PS_PAYED, $note = ''): bool
    {
        // 取得支付编号
        $log_id = intval($log_id);
        if ($log_id > 0) {
            // 取得要修改的支付记录信息
            $pay_log = (array) DB::table('order_pay')->where('log_id', $log_id)->first();

            if ($pay_log && $pay_log['is_paid'] === 0) {
                // 修改此次支付操作的状态为已付款
                DB::table('order_pay')->where('log_id', $log_id)->update(['is_paid' => 1]);

                // 根据记录类型做相应处理
                if ($pay_log['order_type'] === PAY_ORDER) {
                    // 取得订单信息
                    $order = (array) DB::table('order_info')
                        ->select('order_id', 'user_id', 'order_sn', 'consignee', 'address', 'tel', 'shipping_id', 'extension_code', 'extension_id', 'goods_amount')
                        ->where('order_id', $pay_log['order_id'])
                        ->first();

                    $order_id = $order['order_id'];
                    $order_sn = $order['order_sn'];

                    // 修改订单状态为已付款
                    DB::table('order_info')
                        ->where('order_id', $order_id)
                        ->update([
                            'order_status' => OS_CONFIRMED,
                            'confirm_time' => TimeHelper::gmtime(),
                            'pay_status' => $pay_status,
                            'pay_time' => TimeHelper::gmtime(),
                            'money_paid' => DB::raw('order_amount'),
                            'order_amount' => 0,
                        ]);

                    // 记录订单操作记录
                    CommonHelper::order_action($order_sn, OS_CONFIRMED, SS_UNSHIPPED, $pay_status, $note, lang('buyer'));

                    // 如果需要，发短信
                    if (cfg('sms_order_payed') === '1' && cfg('sms_shop_mobile') != '') {
                        $sms = new sms;
                        $sms->send(
                            cfg('sms_shop_mobile'),
                            sprintf(lang('order_payed_sms'), $order_sn, $order['consignee'], $order['tel']),
                            '',
                            13,
                            1
                        );
                    }

                    // 对虚拟商品的支持
                    $virtual_goods = CommonHelper::get_virtual_goods($order_id);
                    if (! empty($virtual_goods)) {
                        $msg = '';
                        if (! CommonHelper::virtual_goods_ship($virtual_goods, $msg, $order_sn, true)) {
                            lang('pay_success') .= '<div style="color:red;">'.$msg.'</div>'.lang('virtual_goods_ship_fail');
                        }

                        // 如果订单没有配送方式，自动完成发货操作
                        if ($order['shipping_id'] === -1) {
                            // 将订单标识为已发货状态，并记录发货记录
                            DB::table('order_info')
                                ->where('order_id', $order_id)
                                ->update(['shipping_status' => SS_SHIPPED, 'shipping_time' => TimeHelper::gmtime()]);

                            // 记录订单操作记录
                            CommonHelper::order_action($order_sn, OS_CONFIRMED, SS_SHIPPED, $pay_status, $note, lang('buyer'));
                            $integral = OrderHelper::integral_to_give($order);
                            CommonHelper::log_account_change($order['user_id'], 0, 0, intval($integral['rank_points']), intval($integral['custom_points']), sprintf(lang('order_gift_integral'), $order['order_sn']));
                        }
                    }
                } elseif ($pay_log['order_type'] === PAY_SURPLUS) {
                    $res_id = DB::table('user_account')
                        ->where('id', $pay_log['order_id'])
                        ->where('is_paid', 1)
                        ->value('id');

                    if (empty($res_id)) {
                        // 更新会员预付款的到款状态
                        DB::table('user_account')
                            ->where('id', $pay_log['order_id'])
                            ->limit(1)
                            ->update(['paid_time' => TimeHelper::gmtime(), 'is_paid' => 1]);

                        // 取得添加预付款的用户以及金额
                        $arr = (array) DB::table('user_account')
                            ->select('user_id', 'amount')
                            ->where('id', $pay_log['order_id'])
                            ->first();

                        // 修改会员帐户金额
                        $_LANG = [];
                        include_once ROOT_PATH.'languages/'.cfg('lang').'/user.php';
                        CommonHelper::log_account_change($arr['user_id'], $arr['amount'], 0, 0, 0, lang('surplus_type_0'), ACT_SAVING);
                    }
                }
            } else {
                // 取得已发货的虚拟商品信息
                $post_virtual_goods = CommonHelper::get_virtual_goods($pay_log['order_id'], true);

                // 有已发货的虚拟商品
                if (! empty($post_virtual_goods)) {
                    $msg = '';
                    // 检查两次刷新时间有无超过12小时
                    $row = (array) DB::table('order_info')
                        ->select('pay_time', 'order_sn')
                        ->where('order_id', $pay_log['order_id'])
                        ->first();
                    $intval_time = TimeHelper::gmtime() - $row['pay_time'];
                    if ($intval_time >= 0 && $intval_time < 3600 * 12) {
                        $virtual_card = [];
                        foreach ($post_virtual_goods as $code => $goods_list) {
                            // 只处理虚拟卡
                            if ($code === 'virtual_card') {
                                foreach ($goods_list as $goods) {
                                    if ($info = CommonHelper::virtual_card_result($row['order_sn'], $goods)) {
                                        $virtual_card[] = ['goods_id' => $goods['goods_id'], 'goods_name' => $goods['goods_name'], 'info' => $info];
                                    }
                                }

                                tpl()->assign('virtual_card', $virtual_card);
                            }
                        }
                    } else {
                        $msg = '<div>'.lang('please_view_order_detail').'</div>';
                    }

                    lang('pay_success') .= $msg;
                }

                // 取得未发货虚拟商品
                $virtual_goods = CommonHelper::get_virtual_goods($pay_log['order_id'], false);
                if (! empty($virtual_goods)) {
                    lang('pay_success') .= '<br />'.lang('virtual_goods_ship_fail');
                }
            }
        }
    }
}
