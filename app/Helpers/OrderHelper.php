<?php

declare(strict_types=1);

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class OrderHelper
{
    /**
     * 处理序列化的支付、配送的配置参数
     * 返回一个以name为索引的数组
     *
     * @param  string  $cfg
     */
    public static function unserialize_config($cfg): array|bool
    {
        if (is_string($cfg) && ($arr = unserialize($cfg)) !== false) {
            $config = [];

            foreach ($arr as $key => $val) {
                $config[$val['name']] = $val['value'];
            }

            return $config;
        } else {
            return false;
        }
    }

    public static function shipping_list(): array
    {
        return DB::table('shipping')
            ->select('shipping_id', 'shipping_name')
            ->where('enabled', 1)
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();
    }

    /**
     * 取得配送方式信息
     *
     * @param  int  $shipping_id  配送方式id
     * @return array 配送方式信息
     */
    public static function shipping_info($shipping_id)
    {
        return (array) DB::table('shipping')
            ->where('shipping_id', $shipping_id)
            ->where('enabled', 1)
            ->first();
    }

    public static function available_shipping_list($region_id_list)
    {
        return DB::table('shipping as s')
            ->select('s.shipping_id', 's.shipping_code', 's.shipping_name', 's.shipping_desc', 's.insure', 's.support_cod', 'a.configure')
            ->join('shipping_area as a', 'a.shipping_id', '=', 's.shipping_id')
            ->join('shipping_area_region as r', 'r.shipping_area_id', '=', 'a.shipping_area_id')
            ->whereIn('r.region_id', (array) $region_id_list)
            ->where('s.enabled', 1)
            ->orderBy('s.shipping_order')
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();
    }

    /**
     * 取得某配送方式对应于某收货地址的区域信息
     *
     * @param  int  $shipping_id  配送方式id
     * @param  array  $region_id_list  收货人地区id数组
     * @return array 配送区域信息（config 对应着反序列化的 configure）
     */
    public static function shipping_area_info($shipping_id, $region_id_list)
    {
        $row = (array) DB::table('shipping as s')
            ->select('s.shipping_code', 's.shipping_name', 's.shipping_desc', 's.insure', 's.support_cod', 'a.configure')
            ->join('shipping_area as a', 'a.shipping_id', '=', 's.shipping_id')
            ->join('shipping_area_region as r', 'r.shipping_area_id', '=', 'a.shipping_area_id')
            ->where('s.shipping_id', $shipping_id)
            ->whereIn('r.region_id', (array) $region_id_list)
            ->where('s.enabled', 1)
            ->first();

        if (! empty($row)) {
            $shipping_config = OrderHelper::unserialize_config($row['configure']);
            if (isset($shipping_config['pay_fee'])) {
                if (strpos($shipping_config['pay_fee'], '%') !== false) {
                    $row['pay_fee'] = floatval($shipping_config['pay_fee']).'%';
                } else {
                    $row['pay_fee'] = floatval($shipping_config['pay_fee']);
                }
            } else {
                $row['pay_fee'] = 0.00;
            }
        }

        return $row;
    }

    /**
     * 计算运费
     *
     * @param  string  $shipping_code  配送方式代码
     * @param  mixeded  $shipping_config  配送方式配置信息
     * @param  float  $goods_weight  商品重量
     * @param  float  $goods_amount  商品金额
     * @param  float  $goods_number  商品数量
     * @return float 运费
     */
    public static function shipping_fee($shipping_code, $shipping_config, $goods_weight, $goods_amount, $goods_number = '')
    {
        if (! is_array($shipping_config)) {
            $shipping_config = unserialize($shipping_config);
        }

        $filename = ROOT_PATH.'includes/modules/shipping/'.$shipping_code.'.php';
        if (file_exists($filename)) {
            include_once $filename;

            $obj = new $shipping_code($shipping_config);

            return $obj->calculate($goods_weight, $goods_amount, $goods_number);
        } else {
            return 0;
        }
    }

    /**
     * 获取指定配送的保价费用
     *
     * @param  string  $shipping_code  配送方式的code
     * @param  float  $goods_amount  保价金额
     * @param  mixeded  $insure  保价比例
     */
    public static function shipping_insure_fee($shipping_code, $goods_amount, $insure): float
    {
        if (strpos($insure, '%') === false) {
            // 如果保价费用不是百分比则直接返回该数值
            return floatval($insure);
        } else {
            $path = ROOT_PATH.'includes/modules/shipping/'.$shipping_code.'.php';

            if (file_exists($path)) {
                include_once $path;

                $shipping = new $shipping_code;
                $insure = floatval($insure) / 100;

                if (method_exists($shipping, 'calculate_insure')) {
                    return $shipping->calculate_insure($goods_amount, $insure);
                } else {
                    return ceil($goods_amount * $insure);
                }
            } else {
                return false;
            }
        }
    }

    /**
     * 取得已安装的支付方式列表
     *
     * @return array 已安装的配送方式列表
     */
    public static function payment_list(): array
    {
        return DB::table('payment')
            ->select('pay_id', 'pay_name')
            ->where('enabled', 1)
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();
    }

    /**
     * 取得支付方式信息
     *
     * @param  int  $pay_id  支付方式id
     * @return array 支付方式信息
     */
    public static function payment_info($pay_id): array|bool
    {
        return (array) DB::table('payment')
            ->where('pay_id', $pay_id)
            ->where('enabled', 1)
            ->first();
    }

    /**
     * 获得订单需要支付的支付费用
     *
     * @param  int  $payment_id
     * @param  float  $order_amount
     * @param  mixeded  $cod_fee
     * @return float
     */
    public static function pay_fee($payment_id, $order_amount, $cod_fee = null)
    {
        $pay_fee = 0;
        $payment = OrderHelper::payment_info($payment_id);
        $rate = ($payment['is_cod'] && ! is_null($cod_fee)) ? $cod_fee : $payment['pay_fee'];

        if (strpos($rate, '%') !== false) {
            // 支付费用是一个比例
            $val = floatval($rate) / 100;
            $pay_fee = $val > 0 ? $order_amount * $val / (1 - $val) : 0;
        } else {
            $pay_fee = floatval($rate);
        }

        return round($pay_fee, 2);
    }

    public static function available_payment_list($support_cod, $cod_fee = 0, $is_online = false)
    {
        $res = DB::table('payment')
            ->select('pay_id', 'pay_code', 'pay_name', 'pay_fee', 'pay_desc', 'pay_config', 'is_cod')
            ->where('enabled', 1)
            ->when(! $support_cod, fn ($q) => $q->where('is_cod', 0))
            ->when($is_online, fn ($q) => $q->where('is_online', '1'))
            ->orderBy('pay_order')
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();

        $modules = [];
        foreach ($res as $row) {
            if ($row['is_cod'] === '1') {
                $row['pay_fee'] = $cod_fee;
            }

            $row['format_pay_fee'] = strpos($row['pay_fee'], '%') !== false ? $row['pay_fee'] :
                CommonHelper::price_format($row['pay_fee'], false);
            $modules[] = $row;
        }

        return $modules;
    }

    /**
     * 取得包装列表
     *
     * @return array 包装列表
     */
    public static function pack_list()
    {
        $res = DB::table('shop_pack')
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();

        $list = [];
        foreach ($res as $row) {
            $row['format_pack_fee'] = CommonHelper::price_format($row['pack_fee'], false);
            $row['format_free_money'] = CommonHelper::price_format($row['free_money'], false);
            $list[] = $row;
        }

        return $list;
    }

    /**
     * 取得包装信息
     *
     * @param  int  $pack_id  包装id
     * @return array 包装信息
     */
    public static function pack_info($pack_id)
    {
        return (array) DB::table('shop_pack')
            ->where('pack_id', $pack_id)
            ->first();
    }

    /**
     * 根据订单中的商品总额来获得包装的费用
     *
     * @param  int  $pack_id
     * @param  float  $goods_amount
     * @return float
     */
    public static function pack_fee($pack_id, $goods_amount)
    {
        $pack = OrderHelper::pack_info($pack_id);

        $val = (floatval($pack['free_money'] ?? 0) <= $goods_amount && ($pack['free_money'] ?? 0) > 0) ? 0 : floatval($pack['pack_fee'] ?? 0);

        return $val;
    }

    /**
     * 取得贺卡列表
     *
     * @return array 贺卡列表
     */
    public static function card_list()
    {
        $res = DB::table('shop_card')
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();

        $list = [];
        foreach ($res as $row) {
            $row['format_card_fee'] = CommonHelper::price_format($row['card_fee'], false);
            $row['format_free_money'] = CommonHelper::price_format($row['free_money'], false);
            $list[] = $row;
        }

        return $list;
    }

    /**
     * 取得贺卡信息
     *
     * @param  int  $card_id  贺卡id
     * @return array 贺卡信息
     */
    public static function card_info($card_id)
    {
        return (array) DB::table('shop_card')
            ->where('card_id', $card_id)
            ->first();
    }

    /**
     * 根据订单中商品总额获得需要支付的贺卡费用
     *
     * @param  int  $card_id
     * @param  float  $goods_amount
     * @return float
     */
    public static function card_fee($card_id, $goods_amount)
    {
        $card = OrderHelper::card_info($card_id);

        return ($card['free_money'] <= $goods_amount && $card['free_money'] > 0) ? 0 : $card['card_fee'];
    }

    /**
     * 取得订单信息
     *
     * @param  int  $order_id  订单id（如果order_id > 0 就按id查，否则按sn查）
     * @param  string  $order_sn  订单号
     * @return array 订单信息（金额都有相应格式化的字段，前缀是formated_）
     */
    public static function order_info($order_id, $order_sn = '')
    {
        // 计算订单各种费用之和的语句
        $total_fee = ' (goods_amount - discount + tax + shipping_fee + insure_fee + pay_fee + pack_fee + card_fee) AS total_fee ';
        $order_id = intval($order_id);
        if ($order_id > 0) {
            $order = (array) DB::table('order_info')
                ->select('*', DB::raw($total_fee))
                ->where('order_id', $order_id)
                ->first();
        } else {
            $order = (array) DB::table('order_info')
                ->select('*', DB::raw($total_fee))
                ->where('order_sn', $order_sn)
                ->first();
        }

        // 格式化金额字段
        if ($order) {
            $order['formated_goods_amount'] = CommonHelper::price_format($order['goods_amount'], false);
            $order['formated_discount'] = CommonHelper::price_format($order['discount'], false);
            $order['formated_tax'] = CommonHelper::price_format($order['tax'], false);
            $order['formated_shipping_fee'] = CommonHelper::price_format($order['shipping_fee'], false);
            $order['formated_insure_fee'] = CommonHelper::price_format($order['insure_fee'], false);
            $order['formated_pay_fee'] = CommonHelper::price_format($order['pay_fee'], false);
            $order['formated_pack_fee'] = CommonHelper::price_format($order['pack_fee'], false);
            $order['formated_card_fee'] = CommonHelper::price_format($order['card_fee'], false);
            $order['formated_total_fee'] = CommonHelper::price_format($order['total_fee'], false);
            $order['formated_money_paid'] = CommonHelper::price_format($order['money_paid'], false);
            $order['formated_bonus'] = CommonHelper::price_format($order['bonus'], false);
            $order['formated_integral_money'] = CommonHelper::price_format($order['integral_money'], false);
            $order['formated_surplus'] = CommonHelper::price_format($order['surplus'], false);
            $order['formated_order_amount'] = CommonHelper::price_format(abs($order['order_amount']), false);
            $order['formated_add_time'] = TimeHelper::local_date(cfg('time_format'), $order['add_time']);
        }

        return $order;
    }

    /**
     * 判断订单是否已完成
     *
     * @param  array  $order  订单信息
     * @return bool
     */
    public static function order_finished($order)
    {
        return $order['order_status'] === OS_CONFIRMED &&
            ($order['shipping_status'] === SS_SHIPPED || $order['shipping_status'] === SS_RECEIVED) &&
            ($order['pay_status'] === PS_PAYED || $order['pay_status'] === PS_PAYING);
    }

    /**
     * 取得订单商品
     *
     * @param  int  $order_id  订单id
     * @return array 订单商品数组
     */
    public static function order_goods($order_id)
    {
        $res = DB::table('order_goods')
            ->select('rec_id', 'goods_id', 'goods_name', 'goods_sn', 'market_price', 'goods_number', 'goods_price', 'goods_attr', 'is_real', 'parent_id', 'is_gift', DB::raw('goods_price * goods_number AS subtotal'), 'extension_code')
            ->where('order_id', (int) $order_id)
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();

        foreach ($res as $row) {
            if ($row['extension_code'] === 'package_buy') {
                $row['package_goods_list'] = CommonHelper::get_package_goods($row['goods_id']);
            }
            $goods_list[] = $row;
        }

        return $goods_list;
    }

    /**
     * 取得订单总金额
     *
     * @param  int  $order_id  订单id
     * @param  bool  $include_gift  是否包括赠品
     * @return float 订单总金额
     */
    public static function order_amount($order_id, $include_gift = true)
    {
        return (float) DB::table('order_goods')
            ->where('order_id', (int) $order_id)
            ->when(! $include_gift, fn ($q) => $q->where('is_gift', 0))
            ->sum(DB::raw('goods_price * goods_number'));
    }

    /**
     * 取得某订单商品总重量和总金额（对应 cart_weight_price）
     *
     * @param  int  $order_id  订单id
     * @return array ('weight' => **, 'amount' => **, 'formated_weight' => **)
     */
    public static function order_weight_price($order_id)
    {
        $row = (array) DB::table('order_goods as o')
            ->select(DB::raw('SUM(g.goods_weight * o.goods_number) AS weight'), DB::raw('SUM(o.goods_price * o.goods_number) AS amount'), DB::raw('SUM(o.goods_number) AS number'))
            ->join('goods as g', 'o.goods_id', '=', 'g.goods_id')
            ->where('o.order_id', (int) $order_id)
            ->first();
        $row['weight'] = floatval($row['weight']);
        $row['amount'] = floatval($row['amount']);
        $row['number'] = intval($row['number']);

        // 格式化重量
        $row['formated_weight'] = CommonHelper::formated_weight($row['weight']);

        return $row;
    }

    /**
     * 获得订单中的费用信息
     *
     * @param  array  $order
     * @param  array  $goods
     * @param  array  $consignee
     * @param  bool  $is_gb_deposit  是否团购保证金（如果是，应付款金额只计算商品总额和支付费用，可以获得的积分取 $gift_integral）
     * @return array
     */
    public static function order_fee($order, $goods, $consignee)
    {
        // 初始化订单的扩展code
        if (! isset($order['extension_code'])) {
            $order['extension_code'] = '';
        }

        if ($order['extension_code'] === 'group_buy') {
            $group_buy = GoodsHelper::group_buy_info($order['extension_id']);
        }

        $total = [
            'real_goods_count' => 0,
            'gift_amount' => 0,
            'goods_price' => 0,
            'market_price' => 0,
            'discount' => 0,
            'pack_fee' => 0,
            'card_fee' => 0,
            'shipping_fee' => 0,
            'shipping_insure' => 0,
            'integral_money' => 0,
            'bonus' => 0,
            'surplus' => 0,
            'cod_fee' => 0,
            'pay_fee' => 0,
            'tax' => 0,
        ];
        $weight = 0;

        // 商品总价
        foreach ($goods as $val) {
            // 统计实体商品的个数
            if ($val['is_real']) {
                $total['real_goods_count']++;
            }

            $total['goods_price'] += $val['goods_price'] * $val['goods_number'];
            $total['market_price'] += $val['market_price'] * $val['goods_number'];
        }

        $total['saving'] = $total['market_price'] - $total['goods_price'];
        $total['save_rate'] = $total['market_price'] ? round($total['saving'] * 100 / $total['market_price']).'%' : 0;

        $total['goods_price_formated'] = CommonHelper::price_format($total['goods_price'], false);
        $total['market_price_formated'] = CommonHelper::price_format($total['market_price'], false);
        $total['saving_formated'] = CommonHelper::price_format($total['saving'], false);

        // 折扣
        if ($order['extension_code'] != 'group_buy') {
            $discount = OrderHelper::compute_discount();
            $total['discount'] = $discount['discount'];
            if ($total['discount'] > $total['goods_price']) {
                $total['discount'] = $total['goods_price'];
            }
        }
        $total['discount_formated'] = CommonHelper::price_format($total['discount'], false);

        // 税额
        if (! empty($order['need_inv']) && $order['inv_type'] != '') {
            // 查税率
            $rate = 0;
            foreach (cfg('invoice_type.type') as $key => $type) {
                if ($type === $order['inv_type']) {
                    $rate = floatval(cfg('invoice_type.rate')[$key]) / 100;
                    break;
                }
            }
            if ($rate > 0) {
                $total['tax'] = $rate * $total['goods_price'];
            }
        }
        $total['tax_formated'] = CommonHelper::price_format($total['tax'], false);

        // 包装费用
        if (! empty($order['pack_id'])) {
            $total['pack_fee'] = OrderHelper::pack_fee($order['pack_id'], $total['goods_price']);
        }
        $total['pack_fee_formated'] = CommonHelper::price_format($total['pack_fee'], false);

        // 贺卡费用
        if (! empty($order['card_id'])) {
            $total['card_fee'] = OrderHelper::card_fee($order['card_id'], $total['goods_price']);
        }
        $total['card_fee_formated'] = CommonHelper::price_format($total['card_fee'], false);

        // 红包

        if (! empty($order['bonus_id'])) {
            $bonus = OrderHelper::bonus_info($order['bonus_id']);
            $total['bonus'] = $bonus['type_money'];
        }
        $total['bonus_formated'] = CommonHelper::price_format($total['bonus'], false);

        // 线下红包
        if (! empty($order['bonus_kill'])) {
            $bonus = OrderHelper::bonus_info(0, $order['bonus_kill']);
            $total['bonus_kill'] = $order['bonus_kill'];
            $total['bonus_kill_formated'] = CommonHelper::price_format($total['bonus_kill'], false);
        }

        // 配送费用
        $shipping_cod_fee = null;

        if ($order['shipping_id'] > 0 && $total['real_goods_count'] > 0) {
            $region['country'] = $consignee['country'];
            $region['province'] = $consignee['province'];
            $region['city'] = $consignee['city'];
            $region['district'] = $consignee['district'];
            $shipping_info = OrderHelper::shipping_area_info($order['shipping_id'], $region);

            if (! empty($shipping_info)) {
                if ($order['extension_code'] === 'group_buy') {
                    $weight_price = OrderHelper::cart_weight_price(CART_GROUP_BUY_GOODS);
                } else {
                    $weight_price = OrderHelper::cart_weight_price();
                }

                // 查看购物车中是否全为免运费商品，若是则把运费赋为零
                $shipping_count = DB::table('user_cart')
                    ->where('session_id', SESS_ID)
                    ->where('extension_code', '!=', 'package_buy')
                    ->where('is_shipping', 0)
                    ->count();

                $total['shipping_fee'] = ($shipping_count === 0 and $weight_price['free_shipping'] === 1) ? 0 : OrderHelper::shipping_fee($shipping_info['shipping_code'], $shipping_info['configure'], $weight_price['weight'], $total['goods_price'], $weight_price['number']);

                if (! empty($order['need_insure']) && $shipping_info['insure'] > 0) {
                    $total['shipping_insure'] = OrderHelper::shipping_insure_fee(
                        $shipping_info['shipping_code'],
                        $total['goods_price'],
                        $shipping_info['insure']
                    );
                } else {
                    $total['shipping_insure'] = 0;
                }

                if ($shipping_info['support_cod']) {
                    $shipping_cod_fee = $shipping_info['pay_fee'];
                }
            }
        }

        $total['shipping_fee_formated'] = CommonHelper::price_format($total['shipping_fee'], false);
        $total['shipping_insure_formated'] = CommonHelper::price_format($total['shipping_insure'], false);

        // 购物车中的商品能享受红包支付的总额
        $bonus_amount = OrderHelper::compute_discount_amount();
        // 红包和积分最多能支付的金额为商品总额
        $max_amount = $total['goods_price'] === 0 ? $total['goods_price'] : $total['goods_price'] - $bonus_amount;

        // 计算订单总额
        if ($order['extension_code'] === 'group_buy' && $group_buy['deposit'] > 0) {
            $total['amount'] = $total['goods_price'];
        } else {
            $total['amount'] = $total['goods_price'] - $total['discount'] + $total['tax'] + $total['pack_fee'] + $total['card_fee'] +
                $total['shipping_fee'] + $total['shipping_insure'] + $total['cod_fee'];

            // 减去红包金额
            $use_bonus = min($total['bonus'], $max_amount); // 实际减去的红包金额
            if (isset($total['bonus_kill'])) {
                $use_bonus_kill = min($total['bonus_kill'], $max_amount);
                $total['amount'] -= $price = number_format($total['bonus_kill'], 2, '.', ''); // 还需要支付的订单金额
            }

            $total['bonus'] = $use_bonus;
            $total['bonus_formated'] = CommonHelper::price_format($total['bonus'], false);

            $total['amount'] -= $use_bonus; // 还需要支付的订单金额
            $max_amount -= $use_bonus; // 积分最多还能支付的金额
        }

        // 余额
        $order['surplus'] = $order['surplus'] > 0 ? $order['surplus'] : 0;
        if ($total['amount'] > 0) {
            if (isset($order['surplus']) && $order['surplus'] > $total['amount']) {
                $order['surplus'] = $total['amount'];
                $total['amount'] = 0;
            } else {
                $total['amount'] -= floatval($order['surplus']);
            }
        } else {
            $order['surplus'] = 0;
            $total['amount'] = 0;
        }
        $total['surplus'] = $order['surplus'];
        $total['surplus_formated'] = CommonHelper::price_format($order['surplus'], false);

        // 积分
        $order['integral'] = $order['integral'] > 0 ? $order['integral'] : 0;
        if ($total['amount'] > 0 && $max_amount > 0 && $order['integral'] > 0) {
            $integral_money = OrderHelper::value_of_integral($order['integral']);

            // 使用积分支付
            $use_integral = min($total['amount'], $max_amount, $integral_money); // 实际使用积分支付的金额
            $total['amount'] -= $use_integral;
            $total['integral_money'] = $use_integral;
            $order['integral'] = OrderHelper::integral_of_value($use_integral);
        } else {
            $total['integral_money'] = 0;
            $order['integral'] = 0;
        }
        $total['integral'] = $order['integral'];
        $total['integral_formated'] = CommonHelper::price_format($total['integral_money'], false);

        // 保存订单信息
        Session::put('flow_order', $order);

        $se_flow_type = Session::has('flow_type') ? Session::get('flow_type') : '';

        // 支付费用
        if (! empty($order['pay_id']) && ($total['real_goods_count'] > 0 || $se_flow_type != CART_EXCHANGE_GOODS)) {
            $total['pay_fee'] = OrderHelper::pay_fee($order['pay_id'], $total['amount'], $shipping_cod_fee);
        }

        $total['pay_fee_formated'] = CommonHelper::price_format($total['pay_fee'], false);

        $total['amount'] += $total['pay_fee']; // 订单总额累加上支付费用
        $total['amount_formated'] = CommonHelper::price_format($total['amount'], false);

        // 取得可以得到的积分和红包
        if ($order['extension_code'] === 'group_buy') {
            $total['will_get_integral'] = $group_buy['gift_integral'];
        } elseif ($order['extension_code'] === 'exchange_goods') {
            $total['will_get_integral'] = 0;
        } else {
            $total['will_get_integral'] = OrderHelper::get_give_integral($goods);
        }
        $total['will_get_bonus'] = $order['extension_code'] === 'exchange_goods' ? 0 : CommonHelper::price_format(OrderHelper::get_total_bonus(), false);
        $total['formated_goods_price'] = CommonHelper::price_format($total['goods_price'], false);
        $total['formated_market_price'] = CommonHelper::price_format($total['market_price'], false);
        $total['formated_saving'] = CommonHelper::price_format($total['saving'], false);

        if ($order['extension_code'] === 'exchange_goods') {
            $exchange_integral = DB::table('user_cart as c')
                ->join('activity_exchange as eg', 'c.goods_id', '=', 'eg.goods_id')
                ->where('c.session_id', SESS_ID)
                ->where('c.rec_type', CART_EXCHANGE_GOODS)
                ->where('c.is_gift', 0)
                ->where('c.goods_id', '>', 0)
                ->sum('eg.exchange_integral');
            $total['exchange_integral'] = $exchange_integral;
        }

        return $total;
    }

    /**
     * 修改订单
     *
     * @param  int  $order_id  订单id
     * @param  array  $order  key => value
     * @return bool
     */
    public static function update_order($order_id, $order)
    {
        return DB::table('order_info')->where('order_id', $order_id)->update($order);
    }

    /**
     * 得到新订单号
     *
     * @return string
     */
    public static function get_order_sn()
    {
        // 选择一个随机的方案
        mt_srand((float) microtime() * 1000000);

        return date('Ymd').str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
    }

    /**
     * 取得购物车商品
     *
     * @param  int  $type  类型：默认普通商品
     * @return array 购物车商品数组
     */
    public static function cart_goods($type = CART_GENERAL_GOODS): array
    {
        $arr = DB::table('user_cart as c')
            ->select('c.rec_id', 'c.user_id', 'c.goods_id', 'c.goods_name', 'c.goods_sn', 'c.goods_number', 'c.market_price', 'c.goods_price', 'c.goods_attr', 'c.is_real', 'c.extension_code', 'c.parent_id', 'c.is_gift', 'c.is_shipping', DB::raw('c.goods_price * c.goods_number AS subtotal'), 'g.goods_thumb')
            ->leftJoin('goods as g', 'g.goods_id', '=', 'c.goods_id')
            ->where('session_id', SESS_ID)
            ->where('rec_type', $type)
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();

        // 格式化价格及礼包商品
        foreach ($arr as $key => $value) {
            $arr[$key]['formated_market_price'] = CommonHelper::price_format($value['market_price'], false);
            $arr[$key]['formated_goods_price'] = CommonHelper::price_format($value['goods_price'], false);
            $arr[$key]['formated_subtotal'] = CommonHelper::price_format($value['subtotal'], false);

            if ($value['extension_code'] === 'package_buy') {
                $arr[$key]['package_goods_list'] = CommonHelper::get_package_goods($value['goods_id']);
            }
        }

        return $arr;
    }

    /**
     * 取得购物车总金额
     *
     * @params  boolean $include_gift   是否包括赠品
     *
     * @param  int  $type  类型：默认普通商品
     * @return float 购物车总金额
     */
    public static function cart_amount($include_gift = true, $type = CART_GENERAL_GOODS): float
    {
        return (float) DB::table('user_cart')
            ->where('session_id', SESS_ID)
            ->where('rec_type', $type)
            ->when(! $include_gift, fn ($q) => $q->where('is_gift', 0)->where('goods_id', '>', 0))
            ->sum(DB::raw('goods_price * goods_number'));
    }

    /**
     * 检查某商品是否已经存在于购物车
     *
     * @param  int  $id
     * @param  array  $spec
     * @param  int  $type  类型：默认普通商品
     * @return bool
     */
    public static function cart_goods_exists($id, $spec, $type = CART_GENERAL_GOODS)
    {
        // 检查该商品是否已经存在在购物车中
        return DB::table('user_cart')
            ->where('session_id', SESS_ID)
            ->where('goods_id', $id)
            ->where('parent_id', 0)
            ->where('goods_attr', OrderHelper::get_goods_attr_info($spec))
            ->where('rec_type', $type)
            ->count() > 0;
    }

    /**
     * 获得购物车中商品的总重量、总价格、总数量
     *
     * @param  int  $type  类型：默认普通商品
     * @return array
     */
    public static function cart_weight_price($type = CART_GENERAL_GOODS)
    {
        $package_row['weight'] = 0;
        $package_row['amount'] = 0;
        $package_row['number'] = 0;

        $packages_row['free_shipping'] = 1;

        // 计算超值礼包内商品的相关配送参数
        $row = DB::table('user_cart')
            ->select('goods_id', 'goods_number', 'goods_price')
            ->where('extension_code', 'package_buy')
            ->where('session_id', SESS_ID)
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();

        if ($row) {
            $packages_row['free_shipping'] = 0;
            $free_shipping_count = 0;

            foreach ($row as $val) {
                // 如果商品全为免运费商品，设置一个标识变量
                $shipping_count = DB::table('activity_package as pg')
                    ->join('goods as g', 'g.goods_id', '=', 'pg.goods_id')
                    ->where('g.is_shipping', 0)
                    ->where('pg.package_id', $val['goods_id'])
                    ->count();

                if ($shipping_count > 0) {
                    // 循环计算每个超值礼包商品的重量和数量，注意一个礼包中可能包换若干个同一商品
                    $goods_row = (array) DB::table('activity_package as pg')
                        ->select(DB::raw('SUM(g.goods_weight * pg.goods_number) AS weight'), DB::raw('SUM(pg.goods_number) AS number'))
                        ->join('goods as g', 'g.goods_id', '=', 'pg.goods_id')
                        ->where('g.is_shipping', 0)
                        ->where('pg.package_id', $val['goods_id'])
                        ->first();

                    $package_row['weight'] += floatval($goods_row['weight']) * $val['goods_number'];
                    $package_row['amount'] += floatval($val['goods_price']) * $val['goods_number'];
                    $package_row['number'] += intval($goods_row['number']) * $val['goods_number'];
                } else {
                    $free_shipping_count++;
                }
            }

            $packages_row['free_shipping'] = $free_shipping_count === count($row) ? 1 : 0;
        }

        // 获得购物车中非超值礼包商品的总重量
        $row = (array) DB::table('user_cart as c')
            ->select(DB::raw('SUM(g.goods_weight * c.goods_number) AS weight'), DB::raw('SUM(c.goods_price * c.goods_number) AS amount'), DB::raw('SUM(c.goods_number) AS number'))
            ->leftJoin('goods as g', 'g.goods_id', '=', 'c.goods_id')
            ->where('c.session_id', SESS_ID)
            ->where('rec_type', $type)
            ->where('g.is_shipping', 0)
            ->where('c.extension_code', '!=', 'package_buy')
            ->first();

        $packages_row['weight'] = floatval($row['weight']) + $package_row['weight'];
        $packages_row['amount'] = floatval($row['amount']) + $package_row['amount'];
        $packages_row['number'] = intval($row['number']) + $package_row['number'];
        // 格式化重量
        $packages_row['formated_weight'] = CommonHelper::formated_weight($packages_row['weight']);

        return $packages_row;
    }

    /**
     * 添加商品到购物车
     *
     * @param  int  $goods_id  商品编号
     * @param  int  $num  商品数量
     * @param  array  $spec  规格值对应的id数组
     * @param  int  $parent  基本件
     * @return bool
     */
    public static function addto_cart($goods_id, $num = 1, $spec = [], $parent = 0, $rec_type = CART_GENERAL_GOODS)
    {
        err()->clean();
        $_parent_id = $parent;

        // 取得商品信息
        $goods = (array) DB::table('goods as g')
            ->select('g.goods_name', 'g.goods_sn', 'g.is_on_sale', 'g.is_real', 'g.market_price', 'g.shop_price AS org_price', 'g.promote_price', 'g.promote_start_date', 'g.promote_end_date', 'g.goods_weight', 'g.integral', 'g.extension_code', 'g.goods_number', 'g.is_alone_sale', 'g.is_shipping', DB::raw("IFNULL(mp.user_price, g.shop_price * '".(Session::get('discount') ?? 1)."') AS shop_price"))
            ->leftJoin('goods_member_price as mp', function ($join) {
                $join->on('mp.goods_id', '=', 'g.goods_id')
                    ->where('mp.user_rank', '=', Session::get('user_rank') ?? 0);
            })
            ->where('g.goods_id', $goods_id)
            ->where('g.is_delete', 0)
            ->first();

        if (empty($goods)) {
            err()->add(lang('goods_not_exists'), ERR_NOT_EXISTS);

            return false;
        }

        // 如果是作为配件添加到购物车的，需要先检查购物车里面是否已经有基本件
        if ($parent > 0) {
            $shipping_count = DB::table('user_cart')
                ->where('goods_id', $parent)
                ->where('session_id', SESS_ID)
                ->where('extension_code', '!=', 'package_buy')
                ->count();

            if ($shipping_count === 0) {
                err()->add(lang('no_basic_goods'), ERR_NO_BASIC_GOODS);

                return false;
            }
        }

        // 是否正在销售
        if ($goods['is_on_sale'] === 0) {
            err()->add(lang('not_on_sale'), ERR_NOT_ON_SALE);

            return false;
        }

        // 不是配件时检查是否允许单独销售
        if (empty($parent) && $goods['is_alone_sale'] === 0) {
            err()->add(lang('cannt_alone_sale'), ERR_CANNOT_ALONE_SALE);

            return false;
        }

        // 如果商品有规格则取规格商品信息 配件除外
        $prod = (array) DB::table('goods_product')->where('goods_id', $goods_id)->first();

        if (CommonHelper::is_spec($spec) && ! empty($prod)) {
            $product_info = GoodsHelper::get_products_info($goods_id, $spec);
        }
        if (empty($product_info)) {
            $product_info = ['product_number' => '', 'product_id' => 0];
        }

        // 检查：库存
        if (cfg('use_storage') === 1) {
            // 检查：商品购买数量是否大于总库存
            if ($num > $goods['goods_number']) {
                err()->add(sprintf(lang('shortage'), $goods['goods_number']), ERR_OUT_OF_STOCK);

                return false;
            }

            // 商品存在规格 是货品 检查该货品库存
            if (CommonHelper::is_spec($spec) && ! empty($prod)) {
                if (! empty($spec)) {
                    // 取规格的货品库存
                    if ($num > $product_info['product_number']) {
                        err()->add(sprintf(lang('shortage'), $product_info['product_number']), ERR_OUT_OF_STOCK);

                        return false;
                    }
                }
            }
        }

        // 计算商品的促销价格
        $spec_price = GoodsHelper::spec_price($spec);
        $goods_price = CommonHelper::get_final_price($goods_id, $num, true, $spec);
        $goods['market_price'] += $spec_price;
        $goods_attr = OrderHelper::get_goods_attr_info($spec);
        $goods_attr_id = implode(',', $spec);

        // 初始化要插入购物车的基本件数据
        $parent = [
            'user_id' => Session::get('user_id'),
            'session_id' => SESS_ID,
            'goods_id' => $goods_id,
            'goods_sn' => addslashes($goods['goods_sn']),
            'product_id' => $product_info['product_id'],
            'goods_name' => addslashes($goods['goods_name']),
            'market_price' => $goods['market_price'],
            'goods_attr' => addslashes($goods_attr),
            'goods_attr_id' => $goods_attr_id,
            'is_real' => $goods['is_real'],
            'extension_code' => $goods['extension_code'],
            'is_gift' => 0,
            'is_shipping' => $goods['is_shipping'],
            'rec_type' => $rec_type,
        ];

        // 如果该配件在添加为基本件的配件时，所设置的“配件价格”比原价低，即此配件在价格上提供了优惠，
        // 则按照该配件的优惠价格卖，但是每一个基本件只能购买一个优惠价格的“该配件”，多买的“该配件”不享
        // 受此优惠
        $basic_list = [];
        $res = DB::table('activity_group')
            ->select('parent_id', 'goods_price')
            ->where('goods_id', $goods_id)
            ->where('goods_price', '<', $goods_price)
            ->where('parent_id', $_parent_id)
            ->orderBy('goods_price')
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();

        foreach ($res as $row) {
            $basic_list[$row['parent_id']] = $row['goods_price'];
        }

        // 取得购物车中该商品每个基本件的数量
        $basic_count_list = [];
        if ($basic_list) {
            $res = DB::table('user_cart')
                ->select('goods_id', DB::raw('SUM(goods_number) AS count'))
                ->where('session_id', SESS_ID)
                ->where('parent_id', 0)
                ->where('extension_code', '!=', 'package_buy')
                ->whereIn('goods_id', array_keys($basic_list))
                ->groupBy('goods_id')
                ->get()
                ->map(fn ($item) => (array) $item)
                ->all();

            foreach ($res as $row) {
                $basic_count_list[$row['goods_id']] = $row['count'];
            }
        }

        // 取得购物车中该商品每个基本件已有该商品配件数量，计算出每个基本件还能有几个该商品配件
        // 一个基本件对应一个该商品配件
        if ($basic_count_list) {
            $res = DB::table('user_cart')
                ->select('parent_id', DB::raw('SUM(goods_number) AS count'))
                ->where('session_id', SESS_ID)
                ->where('goods_id', $goods_id)
                ->where('extension_code', '!=', 'package_buy')
                ->whereIn('parent_id', array_keys($basic_count_list))
                ->groupBy('parent_id')
                ->get()
                ->map(fn ($item) => (array) $item)
                ->all();

            foreach ($res as $row) {
                $basic_count_list[$row['parent_id']] -= $row['count'];
            }
        }

        // 循环插入配件 如果是配件则用其添加数量依次为购物车中所有属于其的基本件添加足够数量的该配件
        foreach ($basic_list as $parent_id => $fitting_price) {
            // 如果已全部插入，退出
            if ($num <= 0) {
                break;
            }

            // 如果该基本件不再购物车中，执行下一个
            if (! isset($basic_count_list[$parent_id])) {
                continue;
            }

            // 如果该基本件的配件数量已满，执行下一个基本件
            if ($basic_count_list[$parent_id] <= 0) {
                continue;
            }

            // 作为该基本件的配件插入
            $parent['goods_price'] = max($fitting_price, 0) + $spec_price; // 允许该配件优惠价格为0
            $parent['goods_number'] = min($num, $basic_count_list[$parent_id]);
            $parent['parent_id'] = $parent_id;

            // 添加
            DB::table('user_cart')->insert($parent);

            // 改变数量
            $num -= $parent['goods_number'];
        }

        // 如果数量不为0，作为基本件插入
        if ($num > 0) {
            $row = (array) DB::table('user_cart')
                ->select('goods_number')
                ->where('session_id', SESS_ID)
                ->where('goods_id', $goods_id)
                ->where('parent_id', 0)
                ->where('goods_attr', OrderHelper::get_goods_attr_info($spec))
                ->where('extension_code', '!=', 'package_buy')
                ->where('rec_type', CART_GENERAL_GOODS)
                ->first();

            if ($row) { // 如果购物车已经有此物品，则更新
                $num += $row['goods_number'];
                if (CommonHelper::is_spec($spec) && ! empty($prod)) {
                    $goods_storage = $product_info['product_number'];
                } else {
                    $goods_storage = $goods['goods_number'];
                }
                if (cfg('use_storage') === 0 || $num <= $goods_storage) {
                    $goods_price = CommonHelper::get_final_price($goods_id, $num, true, $spec);
                    DB::table('user_cart')
                        ->where('session_id', SESS_ID)
                        ->where('goods_id', $goods_id)
                        ->where('parent_id', 0)
                        ->where('goods_attr', OrderHelper::get_goods_attr_info($spec))
                        ->where('extension_code', '!=', 'package_buy')
                        ->where('rec_type', CART_GENERAL_GOODS)
                        ->update(['goods_number' => $num, 'goods_price' => $goods_price]);
                } else {
                    err()->add(sprintf(lang('shortage'), $num), ERR_OUT_OF_STOCK);

                    return false;
                }
            } else { // 购物车没有此物品，则插入
                $goods_price = CommonHelper::get_final_price($goods_id, $num, true, $spec);
                $parent['goods_price'] = max($goods_price, 0);
                $parent['goods_number'] = $num;
                $parent['parent_id'] = 0;
                DB::table('user_cart')->insert($parent);
            }
        }

        // 把赠品删除
        DB::table('user_cart')
            ->where('session_id', SESS_ID)
            ->where('is_gift', '!=', 0)
            ->delete();

        return true;
    }

    /**
     * 清空购物车
     *
     * @param  int  $type  类型：默认普通商品
     */
    public static function clear_cart($type = CART_GENERAL_GOODS)
    {
        DB::table('user_cart')->where('session_id', SESS_ID)->where('rec_type', $type)->delete();
    }

    /**
     * 获得指定的商品属性
     *
     * @param  array  $arr  规格、属性ID数组
     * @param  type  $type  设置返回结果类型：pice，显示价格，默认；no，不显示价格
     * @return string
     */
    public static function get_goods_attr_info($arr, $type = 'pice')
    {
        $attr = '';

        if (! empty($arr)) {
            $fmt = "%s:%s[%s] \n";

            $res = DB::table('goods_attr as ga')
                ->select('a.attr_name', 'ga.attr_value', 'ga.attr_price')
                ->join('goods_type_attribute as a', 'a.attr_id', '=', 'ga.attr_id')
                ->whereIn('ga.goods_attr_id', (array) $arr)
                ->get()
                ->map(fn ($item) => (array) $item)
                ->all();

            foreach ($res as $row) {
                $attr_price = round(floatval($row['attr_price']), 2);
                $attr .= sprintf($fmt, $row['attr_name'], $row['attr_value'], $attr_price);
            }

            $attr = str_replace('[0]', '', $attr);
        }

        return $attr;
    }

    /**
     * 取得用户信息
     *
     * @param  int  $user_id  用户id
     * @return array 用户信息
     */
    public static function user_info($user_id)
    {
        $user = (array) DB::table('user')->where('user_id', $user_id)->first();

        unset($user['question']);
        unset($user['answer']);

        // 格式化帐户余额
        if ($user) {
            //        if ($user['user_money'] < 0)
            //        {
            //            $user['user_money'] = 0;
            //        }
            $user['formated_user_money'] = CommonHelper::price_format($user['user_money'], false);
            $user['formated_frozen_money'] = CommonHelper::price_format($user['frozen_money'], false);
        }

        return $user;
    }

    public static function update_user($user_id, $user)
    {
        return DB::table('user')->where('user_id', $user_id)->update($user);
    }

    /**
     * 取得用户地址列表
     *
     * @param  int  $user_id  用户id
     * @return array
     */
    public static function address_list($user_id)
    {
        return DB::table('user_address')
            ->where('user_id', $user_id)
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();
    }

    /**
     * 取得用户地址信息
     *
     * @param  int  $address_id  地址id
     * @return array
     */
    public static function address_info($address_id)
    {
        return (array) DB::table('user_address')
            ->where('address_id', $address_id)
            ->first();
    }

    /**
     * 取得用户当前可用红包
     *
     * @param  int  $user_id  用户id
     * @param  float  $goods_amount  订单商品金额
     * @return array 红包数组
     */
    public static function user_bonus($user_id, $goods_amount = 0)
    {
        $day = getdate();
        $today = TimeHelper::local_mktime(23, 59, 59, $day['mon'], $day['mday'], $day['year']);

        return DB::table('activity_bonus as t')
            ->select('t.type_id', 't.type_name', 't.type_money', 'b.bonus_id')
            ->join('user_bonus as b', 't.type_id', '=', 'b.bonus_type_id')
            ->where('t.use_start_date', '<=', $today)
            ->where('t.use_end_date', '>=', $today)
            ->where('t.min_goods_amount', '<=', $goods_amount)
            ->where('b.user_id', '!=', 0)
            ->where('b.user_id', $user_id)
            ->where('b.order_id', 0)
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();
    }

    /**
     * 取得红包信息
     *
     * @param  int  $bonus_id  红包id
     * @param  string  $bonus_sn  红包序列号
     * @param array   红包信息
     */
    public static function bonus_info($bonus_id, $bonus_sn = '')
    {
        return (array) DB::table('activity_bonus as t')
            ->select('t.*', 'b.*')
            ->join('user_bonus as b', 't.type_id', '=', 'b.bonus_type_id')
            ->when($bonus_id > 0, fn ($q) => $q->where('b.bonus_id', $bonus_id))
            ->when($bonus_id <= 0, fn ($q) => $q->where('b.bonus_sn', $bonus_sn))
            ->first();
    }

    /**
     * 检查红包是否已使用
     *
     * @param  int  $bonus_id  红包id
     * @return bool
     */
    public static function bonus_used($bonus_id)
    {
        return DB::table('user_bonus')
            ->where('bonus_id', $bonus_id)
            ->where('order_id', '>', 0)
            ->exists();
    }

    /**
     * 设置红包为已使用
     *
     * @param  int  $bonus_id  红包id
     * @param  int  $order_id  订单id
     * @return bool
     */
    public static function use_bonus($bonus_id, $order_id)
    {
        return DB::table('user_bonus')
            ->where('bonus_id', $bonus_id)
            ->limit(1)
            ->update(['order_id' => $order_id, 'used_time' => TimeHelper::gmtime()]);
    }

    /**
     * 设置红包为未使用
     *
     * @param  int  $bonus_id  红包id
     * @param  int  $order_id  订单id
     * @return bool
     */
    public static function unuse_bonus($bonus_id)
    {
        return DB::table('user_bonus')
            ->where('bonus_id', $bonus_id)
            ->limit(1)
            ->update(['order_id' => 0, 'used_time' => 0]);
    }

    /**
     * 计算积分的价值（能抵多少钱）
     *
     * @param  int  $integral  积分
     * @return float 积分价值
     */
    public static function value_of_integral($integral): float
    {
        $scale = floatval(cfg('integral_scale'));

        return $scale > 0 ? round(($integral / 100) * $scale, 2) : 0;
    }

    /**
     * 计算指定的金额需要多少积分
     *
     * @param  int  $value  金额
     */
    public static function integral_of_value($value): float
    {
        $scale = floatval(cfg('integral_scale'));

        return $scale > 0 ? round($value / $scale * 100) : 0;
    }

    /**
     * 订单退款
     *
     * @param  array  $order  订单
     * @param  int  $refund_type  退款方式 1 到帐户余额 2 到退款申请（先到余额，再申请提款） 3 不处理
     * @param  string  $refund_note  退款说明
     * @param  float  $refund_amount  退款金额（如果为0，取订单已付款金额）
     * @return bool
     */
    public static function order_refund($order, $refund_type, $refund_note, $refund_amount = 0)
    {
        $user_id = $order['user_id'];
        if ($user_id === 0 && $refund_type === 1) {
            exit('anonymous, cannot return to account balance');
        }

        $amount = $refund_amount > 0 ? $refund_amount : $order['money_paid'];
        if ($amount <= 0) {
            return true;
        }

        if (! in_array($refund_type, [1, 2, 3])) {
            exit('invalid params');
        }

        // 备注信息
        if ($refund_note) {
            $change_desc = $refund_note;
        } else {
            include_once ROOT_PATH.'languages/'.cfg('lang').'/admin/order.php';
            $change_desc = sprintf(lang('order_refund'), $order['order_sn']);
        }

        // 处理退款
        if ($refund_type === 1) {
            CommonHelper::log_account_change($user_id, $amount, 0, 0, 0, $change_desc);

            return true;
        } elseif ($refund_type === 2) {
            // 如果非匿名，退回余额
            if ($user_id > 0) {
                CommonHelper::log_account_change($user_id, $amount, 0, 0, 0, $change_desc);
            }

            // user_account 表增加提款申请记录
            $account = [
                'user_id' => $user_id,
                'amount' => (-1) * $amount,
                'add_time' => TimeHelper::gmtime(),
                'user_note' => $refund_note,
                'process_type' => SURPLUS_RETURN,
                'admin_user' => Session::get('admin_name'),
                'admin_note' => sprintf(lang('order_refund'), $order['order_sn']),
                'is_paid' => 0,
            ];
            DB::table('user_account')->insert($account);

            return true;
        } else {
            return true;
        }
    }

    /**
     * 获得购物车中的商品
     *
     * @return array
     */
    public static function get_cart_goods($rec_type = CART_GENERAL_GOODS)
    {
        // 初始化
        $goods_list = [];
        $total = [
            'goods_price' => 0, // 本店售价合计（有格式）
            'market_price' => 0, // 市场售价合计（有格式）
            'saving' => 0, // 节省金额（有格式）
            'save_rate' => 0, // 节省百分比
            'goods_amount' => 0, // 本店售价合计（无格式）
        ];

        // 循环、统计
        $res = DB::table('user_cart')
            ->select('*', DB::raw('IF(parent_id, parent_id, goods_id) AS pid'))
            ->when(Session::get('user_id') > 0, fn ($q) => $q->where('user_id', Session::get('user_id')))
            ->when(Session::get('user_id') <= 0, fn ($q) => $q->where('session_id', SESS_ID))
            ->where('rec_type', $rec_type)
            ->orderBy('pid')
            ->orderBy('parent_id')
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();

        // 用于统计购物车中实体商品和虚拟商品的个数
        $virtual_goods_count = 0;
        $real_goods_count = 0;

        foreach ($res as $row) {
            $total['goods_price'] += $row['goods_price'] * $row['goods_number'];
            $total['market_price'] += $row['market_price'] * $row['goods_number'];

            $row['subtotal'] = CommonHelper::price_format($row['goods_price'] * $row['goods_number'], false);
            $row['goods_price'] = CommonHelper::price_format($row['goods_price'], false);
            $row['market_price'] = CommonHelper::price_format($row['market_price'], false);

            // 统计实体商品和虚拟商品的个数
            if ($row['is_real']) {
                $real_goods_count++;
            } else {
                $virtual_goods_count++;
            }

            // 查询规格
            if (trim($row['goods_attr']) != '') {
                $row['goods_attr'] = addslashes($row['goods_attr']);
                $attr_list = DB::table('goods_attr')
                    ->whereIn('goods_attr_id', (array) explode(',', $row['goods_attr']))
                    ->pluck('attr_value')
                    ->all();

                foreach ($attr_list as $attr) {
                    $row['goods_name'] .= ' ['.$attr.'] ';
                }
            }
            // 增加是否在购物车里显示商品图
            if ((cfg('show_goods_in_cart') === '2' || cfg('show_goods_in_cart') === '3') && $row['extension_code'] != 'package_buy') {
                $goods_thumb = DB::table('goods')->where('goods_id', $row['goods_id'])->value('goods_thumb');
                $row['goods_thumb'] = CommonHelper::get_image_path($goods_thumb);
            }
            if ($row['extension_code'] === 'package_buy') {
                $row['package_goods_list'] = CommonHelper::get_package_goods($row['goods_id']);
            }
            $goods_list[] = $row;
        }
        $total['goods_amount'] = $total['goods_price'];
        $total['saving'] = CommonHelper::price_format($total['market_price'] - $total['goods_price'], false);
        if ($total['market_price'] > 0) {
            $total['save_rate'] = $total['market_price'] ? round(($total['market_price'] - $total['goods_price']) *
                100 / $total['market_price']).'%' : 0;
        }
        $total['goods_price'] = CommonHelper::price_format($total['goods_price'], false);
        $total['market_price'] = CommonHelper::price_format($total['market_price'], false);
        $total['real_goods_count'] = $real_goods_count;
        $total['virtual_goods_count'] = $virtual_goods_count;

        return ['goods_list' => $goods_list, 'total' => $total];
    }

    /**
     * 取得收货人信息
     *
     * @param  int  $user_id  用户编号
     * @return array
     */
    public static function get_consignee($user_id)
    {
        if (Session::has('flow_consignee')) {
            // 如果存在session，则直接返回session中的收货人信息
            return Session::get('flow_consignee');
        } else {
            // 如果不存在，则取得用户的默认收货人信息
            $arr = [];

            if ($user_id > 0) {
                // 取默认地址
                $arr = (array) DB::table('user_address as ua')
                    ->select('ua.*')
                    ->join('user as u', 'ua.address_id', '=', 'u.address_id')
                    ->where('u.user_id', $user_id)
                    ->first();
            }

            return $arr;
        }
    }

    /**
     * 查询购物车（订单id为0）或订单中是否有实体商品
     *
     * @param  int  $order_id  订单id
     * @param  int  $flow_type  购物流程类型
     * @return bool
     */
    public static function exist_real_goods($order_id = 0, $flow_type = CART_GENERAL_GOODS)
    {
        if ($order_id <= 0) {
            return DB::table('user_cart')
                ->where('session_id', SESS_ID)
                ->where('is_real', 1)
                ->where('rec_type', $flow_type)
                ->count() > 0;
        } else {
            return DB::table('order_goods')
                ->where('order_id', $order_id)
                ->where('is_real', 1)
                ->count() > 0;
        }
    }

    /**
     * 检查收货人信息是否完整
     *
     * @param  array  $consignee  收货人信息
     * @param  int  $flow_type  购物流程类型
     * @return bool true 完整 false 不完整
     */
    public static function check_consignee_info($consignee, $flow_type)
    {
        if (OrderHelper::exist_real_goods(0, $flow_type)) {
            // 如果存在实体商品
            $res = ! empty($consignee['consignee']) &&
                ! empty($consignee['country']) &&
                ! empty($consignee['email']) &&
                ! empty($consignee['tel']);

            if ($res) {
                if (empty($consignee['province'])) {
                    // 没有设置省份，检查当前国家下面有没有设置省份
                    $pro = CommonHelper::get_regions(1, $consignee['country']);
                    $res = empty($pro);
                } elseif (empty($consignee['city'])) {
                    // 没有设置城市，检查当前省下面有没有城市
                    $city = CommonHelper::get_regions(2, $consignee['province']);
                    $res = empty($city);
                } elseif (empty($consignee['district'])) {
                    $dist = CommonHelper::get_regions(3, $consignee['city']);
                    $res = empty($dist);
                }
            }

            return $res;
        } else {
            // 如果不存在实体商品
            return ! empty($consignee['consignee']) &&
                ! empty($consignee['email']) &&
                ! empty($consignee['tel']);
        }
    }

    /**
     * 获得上一次用户采用的支付和配送方式
     *
     * @return void
     */
    public static function last_shipping_and_payment(): array
    {
        $row = (array) DB::table('order_info')
            ->select('shipping_id', 'pay_id')
            ->where('user_id', Session::get('user_id'))
            ->orderByDesc('order_id')
            ->first();

        if (empty($row)) {
            // 如果获得是一个空数组，则返回默认值
            $row = ['shipping_id' => 0, 'pay_id' => 0];
        }

        return $row;
    }

    /**
     * 取得当前用户应该得到的红包总额
     */
    public static function get_total_bonus()
    {
        $day = getdate();
        $today = TimeHelper::local_mktime(23, 59, 59, $day['mon'], $day['mday'], $day['year']);

        // 按商品发的红包
        $goods_total = floatval(DB::table('user_cart as c')
            ->join('goods as g', 'c.goods_id', '=', 'g.goods_id')
            ->join('activity_bonus as t', 'g.bonus_type_id', '=', 't.type_id')
            ->where('c.session_id', SESS_ID)
            ->where('c.is_gift', 0)
            ->where('t.send_type', SEND_BY_GOODS)
            ->where('t.send_start_date', '<=', $today)
            ->where('t.send_end_date', '>=', $today)
            ->where('c.rec_type', CART_GENERAL_GOODS)
            ->sum(DB::raw('c.goods_number * t.type_money')));

        // 取得购物车中非赠品总金额
        $amount = floatval(DB::table('user_cart')
            ->where('session_id', SESS_ID)
            ->where('is_gift', 0)
            ->where('rec_type', CART_GENERAL_GOODS)
            ->sum(DB::raw('goods_price * goods_number')));

        // 按订单发的红包
        $order_total = floatval(DB::table('activity_bonus')
            ->where('send_type', SEND_BY_ORDER)
            ->where('send_start_date', '<=', $today)
            ->where('send_end_date', '>=', $today)
            ->where('min_amount', '>', 0)
            ->where('min_amount', '<=', $amount)
            ->value(DB::raw("FLOOR('$amount' / min_amount) * type_money")));

        return $goods_total + $order_total;
    }

    /**
     * 处理红包（下订单时设为使用，取消（无效，退货）订单时设为未使用
     *
     * @param  int  $bonus_id  红包编号
     * @param  int  $order_id  订单号
     * @param  int  $is_used  是否使用了
     */
    public static function change_user_bonus($bonus_id, $order_id, $is_used = true)
    {
        if ($is_used) {
            DB::table('user_bonus')
                ->where('bonus_id', $bonus_id)
                ->update(['used_time' => TimeHelper::gmtime(), 'order_id' => $order_id]);
        } else {
            DB::table('user_bonus')
                ->where('bonus_id', $bonus_id)
                ->update(['used_time' => 0, 'order_id' => 0]);
        }
    }

    /**
     * 获得订单信息
     *
     * @return array
     */
    public static function flow_order_info()
    {
        $order = Session::has('flow_order') ? Session::get('flow_order') : [];

        // 初始化配送和支付方式
        if (! isset($order['shipping_id']) || ! isset($order['pay_id'])) {
            // 如果还没有设置配送和支付
            if (Session::get('user_id') > 0) {
                // 用户已经登录了，则获得上次使用的配送和支付
                $arr = OrderHelper::last_shipping_and_payment();

                if (! isset($order['shipping_id'])) {
                    $order['shipping_id'] = $arr['shipping_id'];
                }
                if (! isset($order['pay_id'])) {
                    $order['pay_id'] = $arr['pay_id'];
                }
            } else {
                if (! isset($order['shipping_id'])) {
                    $order['shipping_id'] = 0;
                }
                if (! isset($order['pay_id'])) {
                    $order['pay_id'] = 0;
                }
            }
        }

        if (! isset($order['pack_id'])) {
            $order['pack_id'] = 0;  // 初始化包装
        }
        if (! isset($order['card_id'])) {
            $order['card_id'] = 0;  // 初始化贺卡
        }
        if (! isset($order['bonus'])) {
            $order['bonus'] = 0;    // 初始化红包
        }
        if (! isset($order['integral'])) {
            $order['integral'] = 0; // 初始化积分
        }
        if (! isset($order['surplus'])) {
            $order['surplus'] = 0;  // 初始化余额
        }

        // 扩展信息
        if (Session::has('flow_type') && intval(Session::get('flow_type')) != CART_GENERAL_GOODS) {
            $order['extension_code'] = Session::get('extension_code');
            $order['extension_id'] = Session::get('extension_id');
        }

        return $order;
    }

    /**
     * 合并订单
     *
     * @param  string  $from_order_sn  从订单号
     * @param  string  $to_order_sn  主订单号
     * @return 成功返回true，失败返回错误信息
     */
    public static function merge_order($from_order_sn, $to_order_sn)
    {
        // 订单号不能为空
        if (trim($from_order_sn) === '' || trim($to_order_sn) === '') {
            return lang('order_sn_not_null');
        }

        // 订单号不能相同
        if ($from_order_sn === $to_order_sn) {
            return lang('two_order_sn_same');
        }

        // 取得订单信息
        $from_order = OrderHelper::order_info(0, $from_order_sn);
        $to_order = OrderHelper::order_info(0, $to_order_sn);

        // 检查订单是否存在
        if (! $from_order) {
            return sprintf(lang('order_not_exist'), $from_order_sn);
        } elseif (! $to_order) {
            return sprintf(lang('order_not_exist'), $to_order_sn);
        }

        // 检查合并的订单是否为普通订单，非普通订单不允许合并
        if ($from_order['extension_code'] != '' || $to_order['extension_code'] != 0) {
            return lang('merge_invalid_order');
        }

        // 检查订单状态是否是已确认或未确认、未付款、未发货
        if ($from_order['order_status'] != OS_UNCONFIRMED && $from_order['order_status'] != OS_CONFIRMED) {
            return sprintf(lang('os_not_unconfirmed_or_confirmed'), $from_order_sn);
        } elseif ($from_order['pay_status'] != PS_UNPAYED) {
            return sprintf(lang('ps_not_unpayed'), $from_order_sn);
        } elseif ($from_order['shipping_status'] != SS_UNSHIPPED) {
            return sprintf(lang('ss_not_unshipped'), $from_order_sn);
        }

        if ($to_order['order_status'] != OS_UNCONFIRMED && $to_order['order_status'] != OS_CONFIRMED) {
            return sprintf(lang('os_not_unconfirmed_or_confirmed'), $to_order_sn);
        } elseif ($to_order['pay_status'] != PS_UNPAYED) {
            return sprintf(lang('ps_not_unpayed'), $to_order_sn);
        } elseif ($to_order['shipping_status'] != SS_UNSHIPPED) {
            return sprintf(lang('ss_not_unshipped'), $to_order_sn);
        }

        // 检查订单用户是否相同
        if ($from_order['user_id'] != $to_order['user_id']) {
            return lang('order_user_not_same');
        }

        // 合并订单
        $order = $to_order;
        $order['order_id'] = '';
        $order['add_time'] = TimeHelper::gmtime();

        // 合并商品总额
        $order['goods_amount'] += $from_order['goods_amount'];

        // 合并折扣
        $order['discount'] += $from_order['discount'];

        if ($order['shipping_id'] > 0) {
            // 重新计算配送费用
            $weight_price = OrderHelper::order_weight_price($to_order['order_id']);
            $from_weight_price = OrderHelper::order_weight_price($from_order['order_id']);
            $weight_price['weight'] += $from_weight_price['weight'];
            $weight_price['amount'] += $from_weight_price['amount'];
            $weight_price['number'] += $from_weight_price['number'];

            $region_id_list = [$order['country'], $order['province'], $order['city'], $order['district']];
            $shipping_area = OrderHelper::shipping_area_info($order['shipping_id'], $region_id_list);

            $order['shipping_fee'] = OrderHelper::shipping_fee(
                $shipping_area['shipping_code'],
                unserialize($shipping_area['configure']),
                $weight_price['weight'],
                $weight_price['amount'],
                $weight_price['number']
            );

            // 如果保价了，重新计算保价费
            if ($order['insure_fee'] > 0) {
                $order['insure_fee'] = OrderHelper::shipping_insure_fee($shipping_area['shipping_code'], $order['goods_amount'], $shipping_area['insure']);
            }
        }

        // 重新计算包装费、贺卡费
        if ($order['pack_id'] > 0) {
            $pack = OrderHelper::pack_info($order['pack_id']);
            $order['pack_fee'] = $pack['free_money'] > $order['goods_amount'] ? $pack['pack_fee'] : 0;
        }
        if ($order['card_id'] > 0) {
            $card = OrderHelper::card_info($order['card_id']);
            $order['card_fee'] = $card['free_money'] > $order['goods_amount'] ? $card['card_fee'] : 0;
        }

        // 红包不变，合并积分、余额、已付款金额
        $order['integral'] += $from_order['integral'];
        $order['integral_money'] = OrderHelper::value_of_integral($order['integral']);
        $order['surplus'] += $from_order['surplus'];
        $order['money_paid'] += $from_order['money_paid'];

        // 计算应付款金额（不包括支付费用）
        $order['order_amount'] = $order['goods_amount'] - $order['discount']
            + $order['shipping_fee']
            + $order['insure_fee']
            + $order['pack_fee']
            + $order['card_fee']
            - $order['bonus']
            - $order['integral_money']
            - $order['surplus']
            - $order['money_paid'];

        // 重新计算支付费
        if ($order['pay_id'] > 0) {
            // 货到付款手续费
            $cod_fee = $shipping_area ? $shipping_area['pay_fee'] : 0;
            $order['pay_fee'] = OrderHelper::pay_fee($order['pay_id'], $order['order_amount'], $cod_fee);

            // 应付款金额加上支付费
            $order['order_amount'] += $order['pay_fee'];
        }

        // 插入订单表
        $order_id = DB::transaction(function () use ($order, $from_order, $to_order) {
            do {
                $order['order_sn'] = OrderHelper::get_order_sn();
                try {
                    $new_id = DB::table('order_info')->insertGetId(BaseHelper::addslashes_deep($order));
                    break;
                } catch (\Illuminate\Database\QueryException $e) {
                    if ($e->getCode() != 23000) { // Not integrity constraint violation (e.g. duplicate key)
                        throw $e;
                    }
                }
            } while (true);

            // 更新订单商品
            DB::table('order_goods')
                ->whereIn('order_id', [$from_order['order_id'], $to_order['order_id']])
                ->update(['order_id' => $new_id]);

            // 插入支付日志
            ClipsHelper::insert_pay_log($new_id, $order['order_amount'], PAY_ORDER);

            // 删除原订单
            DB::table('order_info')->whereIn('order_id', [$from_order['order_id'], $to_order['order_id']])->delete();

            // 删除原订单支付日志
            DB::table('order_pay')->whereIn('order_id', [$from_order['order_id'], $to_order['order_id']])->delete();

            return $new_id;
        });

        // 返还 from_order 的红包，因为只使用 to_order 的红包
        if ($from_order['bonus_id'] > 0) {
            OrderHelper::unuse_bonus($from_order['bonus_id']);
        }

        // 返回成功
        return true;
    }

    /**
     * 查询配送区域属于哪个办事处管辖
     *
     * @param  array  $regions  配送区域（1、2、3、4级按顺序）
     * @return int 办事处id，可能为0
     */
    public static function get_agency_by_regions($regions)
    {
        if (! is_array($regions) || empty($regions)) {
            return 0;
        }

        $arr = DB::table('shop_region')
            ->whereIn('region_id', (array) $regions)
            ->where('region_id', '>', 0)
            ->where('agency_id', '>', 0)
            ->pluck('agency_id', 'region_id')
            ->all();

        if (empty($arr)) {
            return 0;
        }

        for ($i = count($regions) - 1; $i >= 0; $i--) {
            if (isset($arr[$regions[$i]])) {
                return $arr[$regions[$i]];
            }
        }
    }

    /**
     * 获取配送插件的实例
     *
     * @param  int  $shipping_id  配送插件ID
     * @return object 配送插件对象实例
     */
    public static function &get_shipping_object($shipping_id)
    {
        $shipping = OrderHelper::shipping_info($shipping_id);
        if (! $shipping) {
            $object = new stdClass;

            return $object;
        }

        $file_path = ROOT_PATH.'includes/modules/shipping/'.$shipping['shipping_code'].'.php';

        include_once $file_path;

        $object = new $shipping['shipping_code'];

        return $object;
    }

    /**
     * 改变订单中商品库存
     *
     * @param  int  $order_id  订单号
     * @param  bool  $is_dec  是否减少库存
     * @param  bool  $storage  减库存的时机，1，下订单时；0，发货时；
     */
    public static function change_order_goods_storage($order_id, $is_dec = true, $storage = 0)
    {
        // 查询订单商品信息
        $res = DB::table('order_goods')
            ->select('goods_id', DB::raw($storage === 0 ? 'SUM(send_number) AS num' : 'SUM(goods_number) AS num'), DB::raw('MAX(extension_code) AS extension_code'), 'product_id')
            ->where('order_id', $order_id)
            ->where('is_real', 1)
            ->groupBy('goods_id', 'product_id')
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();

        foreach ($res as $row) {
            if ($row['extension_code'] != 'package_buy') {
                if ($is_dec) {
                    OrderHelper::change_goods_storage($row['goods_id'], $row['product_id'], -$row['num']);
                } else {
                    OrderHelper::change_goods_storage($row['goods_id'], $row['product_id'], $row['num']);
                }
            } else {
                $res_goods = DB::table('activity_package')
                    ->select('goods_id', 'goods_number')
                    ->where('package_id', $row['goods_id'])
                    ->get()
                    ->map(fn ($item) => (array) $item)
                    ->all();

                foreach ($res_goods as $row_goods) {
                    $is_goods = (array) DB::table('goods')
                        ->select('is_real')
                        ->where('goods_id', $row_goods['goods_id'])
                        ->first();

                    if ($is_dec) {
                        OrderHelper::change_goods_storage($row_goods['goods_id'], $row['product_id'], -($row['num'] * $row_goods['goods_number']));
                    } elseif ($is_goods['is_real']) {
                        OrderHelper::change_goods_storage($row_goods['goods_id'], $row['product_id'], ($row['num'] * $row_goods['goods_number']));
                    }
                }
            }
        }
    }

    /**
     * 商品库存增与减 货品库存增与减
     *
     * @param  int  $good_id  商品ID
     * @param  int  $product_id  货品ID
     * @param  int  $number  增减数量，默认0；
     * @return bool true，成功；false，失败；
     */
    public static function change_goods_storage($good_id, $product_id, $number = 0)
    {
        if ($number === 0) {
            return true; // 值为0即不做、增减操作，返回true
        }

        if (empty($good_id) || empty($number)) {
            return false;
        }

        // 处理货品库存
        $products_query = true;
        if (! empty($product_id)) {
            $products_query = DB::table('goods_product')
                ->where('goods_id', $good_id)
                ->where('product_id', $product_id)
                ->limit(1)
                ->update(['product_number' => DB::raw("product_number + $number")]);
        }

        // 处理商品库存
        $query = DB::table('goods')
            ->where('goods_id', $good_id)
            ->limit(1)
            ->update(['goods_number' => DB::raw("goods_number + $number")]);

        return $query || $products_query;
    }

    /**
     * 生成查询订单总金额的字段
     *
     * @param  string  $alias  order表的别名（包括.例如 o.）
     * @return string
     */
    public static function order_amount_field($alias = '')
    {
        return "   {$alias}goods_amount + {$alias}tax + {$alias}shipping_fee".
            " + {$alias}insure_fee + {$alias}pay_fee + {$alias}pack_fee".
            " + {$alias}card_fee ";
    }

    /**
     * 生成计算应付款金额的字段
     *
     * @param  string  $alias  order表的别名（包括.例如 o.）
     * @return string
     */
    public static function order_due_field($alias = '')
    {
        return OrderHelper::order_amount_field($alias).
            " - {$alias}money_paid - {$alias}surplus - {$alias}integral_money".
            " - {$alias}bonus - {$alias}discount ";
    }

    /**
     * 计算折扣：根据购物车和优惠活动
     *
     * @return float 折扣
     */
    public static function compute_discount(): array
    {
        // 查询优惠活动
        $now = TimeHelper::gmtime();
        $user_rank = ','.Session::get('user_rank', 0).',';
        $favourable_list = DB::table('activity')
            ->where('start_time', '<=', $now)
            ->where('end_time', '>=', $now)
            ->whereRaw("CONCAT(',', user_rank, ',') LIKE ?", ["%{$user_rank}%"])
            ->whereIn('act_type', [FAT_DISCOUNT, FAT_PRICE])
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();

        if (! $favourable_list) {
            return ['discount' => 0, 'name' => ''];
        }

        // 查询购物车商品
        $goods_list = DB::table('user_cart as c')
            ->select('c.goods_id', DB::raw('c.goods_price * c.goods_number AS subtotal'), 'g.cat_id', 'g.brand_id')
            ->join('goods as g', 'c.goods_id', '=', 'g.goods_id')
            ->where('c.session_id', SESS_ID)
            ->where('c.parent_id', 0)
            ->where('c.is_gift', 0)
            ->where('rec_type', CART_GENERAL_GOODS)
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();

        if (! $goods_list) {
            return ['discount' => 0, 'name' => ''];
        }

        // 初始化折扣
        $discount = 0;
        $favourable_name = [];

        // 循环计算每个优惠活动的折扣
        foreach ($favourable_list as $favourable) {
            $total_amount = 0;
            if ($favourable['act_range'] === FAR_ALL) {
                foreach ($goods_list as $goods) {
                    $total_amount += $goods['subtotal'];
                }
            } elseif ($favourable['act_range'] === FAR_CATEGORY) {
                // 找出分类id的子分类id
                $id_list = [];
                $raw_id_list = explode(',', $favourable['act_range_ext']);
                foreach ($raw_id_list as $id) {
                    $id_list = array_merge($id_list, array_keys(CommonHelper::cat_list($id, 0, false)));
                }
                $ids = implode(',', array_unique($id_list));

                foreach ($goods_list as $goods) {
                    if (strpos(','.$ids.',', ','.$goods['cat_id'].',') !== false) {
                        $total_amount += $goods['subtotal'];
                    }
                }
            } elseif ($favourable['act_range'] === FAR_BRAND) {
                foreach ($goods_list as $goods) {
                    if (strpos(','.$favourable['act_range_ext'].',', ','.$goods['brand_id'].',') !== false) {
                        $total_amount += $goods['subtotal'];
                    }
                }
            } elseif ($favourable['act_range'] === FAR_GOODS) {
                foreach ($goods_list as $goods) {
                    if (strpos(','.$favourable['act_range_ext'].',', ','.$goods['goods_id'].',') !== false) {
                        $total_amount += $goods['subtotal'];
                    }
                }
            } else {
                continue;
            }

            // 如果金额满足条件，累计折扣
            if ($total_amount > 0 && $total_amount >= $favourable['min_amount'] && ($total_amount <= $favourable['max_amount'] || $favourable['max_amount'] === 0)) {
                if ($favourable['act_type'] === FAT_DISCOUNT) {
                    $discount += $total_amount * (1 - $favourable['act_type_ext'] / 100);

                    $favourable_name[] = $favourable['act_name'];
                } elseif ($favourable['act_type'] === FAT_PRICE) {
                    $discount += $favourable['act_type_ext'];

                    $favourable_name[] = $favourable['act_name'];
                }
            }
        }

        return ['discount' => $discount, 'name' => $favourable_name];
    }

    /**
     * 取得购物车该赠送的积分数
     *
     * @return int 积分数
     */
    public static function get_give_integral(): int
    {
        return (int) DB::table('user_cart as c')
            ->join('goods as g', 'c.goods_id', '=', 'g.goods_id')
            ->where('c.session_id', SESS_ID)
            ->where('c.goods_id', '>', 0)
            ->where('c.parent_id', 0)
            ->where('c.rec_type', 0)
            ->where('c.is_gift', 0)
            ->sum(DB::raw('c.goods_number * IF(g.give_integral > -1, g.give_integral, c.goods_price)'));
    }

    /**
     * 取得某订单应该赠送的积分数
     *
     * @param  array  $order  订单
     * @return int 积分数
     */
    public static function integral_to_give($order): array
    {
        // 判断是否团购
        if ($order['extension_code'] === 'group_buy') {
            $group_buy = GoodsHelper::group_buy_info(intval($order['extension_id']));

            return ['custom_points' => $group_buy['gift_integral'] ?? 0, 'rank_points' => $order['goods_amount']];
        } else {
            return (array) DB::table('order_goods as og')
                ->select(DB::raw('SUM(og.goods_number * IF(g.give_integral > -1, g.give_integral, og.goods_price)) AS custom_points'), DB::raw('SUM(og.goods_number * IF(g.rank_integral > -1, g.rank_integral, og.goods_price)) AS rank_points'))
                ->join('goods as g', 'og.goods_id', '=', 'g.goods_id')
                ->where('og.order_id', $order['order_id'])
                ->where('og.goods_id', '>', 0)
                ->where('og.parent_id', 0)
                ->where('og.is_gift', 0)
                ->where('og.extension_code', '!=', 'package_buy')
                ->first();
        }
    }

    /**
     * 发红包：发货时发红包
     *
     * @param  int  $order_id  订单号
     * @return bool
     */
    public static function send_order_bonus($order_id)
    {
        // 取得订单应该发放的红包
        $bonus_list = OrderHelper::order_bonus($order_id);

        // 如果有红包，统计并发送
        if ($bonus_list) {
            // 用户信息
            $user = (array) DB::table('order_info as o')
                ->select('u.user_id', 'u.user_name', 'u.email')
                ->join('user as u', 'o.user_id', '=', 'u.user_id')
                ->where('o.order_id', $order_id)
                ->first();

            // 统计
            $count = 0;
            $money = '';
            foreach ($bonus_list as $bonus) {
                $count += $bonus['number'];
                $money .= CommonHelper::price_format($bonus['type_money']).' ['.$bonus['number'].'], ';

                // 修改用户红包
                for ($i = 0; $i < $bonus['number']; $i++) {
                    DB::table('user_bonus')->insert([
                        'bonus_type_id' => $bonus['type_id'],
                        'user_id' => $user['user_id'],
                    ]);
                }
            }

            // 如果有红包，发送邮件
            if ($count > 0) {
                $tpl = CommonHelper::get_mail_template('send_bonus');
                tpl()->assign('user_name', $user['user_name']);
                tpl()->assign('count', $count);
                tpl()->assign('money', $money);
                tpl()->assign('shop_name', cfg('shop_name'));
                tpl()->assign('send_date', TimeHelper::local_date(cfg('date_format')));
                tpl()->assign('sent_date', TimeHelper::local_date(cfg('date_format')));
                $content = tpl()->fetch('str:'.$tpl['template_content']);
                BaseHelper::send_mail($user['user_name'], $user['email'], $tpl['template_subject'], $content, $tpl['is_html']);
            }
        }

        return true;
    }

    /**
     * 返回订单发放的红包
     *
     * @param  int  $order_id  订单id
     */
    public static function return_order_bonus($order_id)
    {
        // 取得订单应该发放的红包
        $bonus_list = OrderHelper::order_bonus($order_id);

        // 删除
        if ($bonus_list) {
            // 取得订单信息
            $order = OrderHelper::order_info($order_id);
            $user_id = $order['user_id'];

            foreach ($bonus_list as $bonus) {
                DB::table('user_bonus')
                    ->where('bonus_type_id', $bonus['type_id'])
                    ->where('user_id', $user_id)
                    ->where('order_id', 0)
                    ->limit($bonus['number'])
                    ->delete();
            }
        }
    }

    /**
     * 取得订单应该发放的红包
     *
     * @param  int  $order_id  订单id
     * @return array
     */
    public static function order_bonus($order_id)
    {
        // 查询按商品发的红包
        $day = getdate();
        $today = TimeHelper::local_mktime(23, 59, 59, $day['mon'], $day['mday'], $day['year']);

        $list = DB::table('order_goods as o')
            ->select('b.type_id', 'b.type_money', DB::raw('SUM(o.goods_number) AS number'))
            ->join('goods as g', 'o.goods_id', '=', 'g.goods_id')
            ->join('activity_bonus as b', 'g.bonus_type_id', '=', 'b.type_id')
            ->where('o.order_id', $order_id)
            ->where('o.is_gift', 0)
            ->where('b.send_type', SEND_BY_GOODS)
            ->where('b.send_start_date', '<=', $today)
            ->where('b.send_end_date', '>=', $today)
            ->groupBy('b.type_id')
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();

        // 查询定单中非赠品总金额
        $amount = OrderHelper::order_amount($order_id, false);

        // 查询订单日期
        $order_time = DB::table('order_info')->where('order_id', $order_id)->value('add_time');

        // 查询按订单发的红包
        $order_bonus = DB::table('activity_bonus')
            ->select('type_id', 'type_money', DB::raw("IFNULL(FLOOR('$amount' / min_amount), 1) AS number"))
            ->where('send_type', SEND_BY_ORDER)
            ->where('send_start_date', '<=', $order_time)
            ->where('send_end_date', '>=', $order_time)
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();

        return array_merge($list, $order_bonus);
    }

    /**
     * 计算购物车中的商品能享受红包支付的总额
     *
     * @return float 享受红包支付的总额
     */
    public static function compute_discount_amount()
    {
        // 查询优惠活动
        $now = TimeHelper::gmtime();
        $user_rank = ','.Session::get('user_rank', 0).',';
        $favourable_list = DB::table('activity')
            ->where('start_time', '<=', $now)
            ->where('end_time', '>=', $now)
            ->whereRaw("CONCAT(',', user_rank, ',') LIKE ?", ["%{$user_rank}%"])
            ->whereIn('act_type', [FAT_DISCOUNT, FAT_PRICE])
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();

        if (! $favourable_list) {
            return 0;
        }

        // 查询购物车商品
        $goods_list = DB::table('user_cart as c')
            ->select('c.goods_id', DB::raw('c.goods_price * c.goods_number AS subtotal'), 'g.cat_id', 'g.brand_id')
            ->join('goods as g', 'c.goods_id', '=', 'g.goods_id')
            ->where('c.session_id', SESS_ID)
            ->where('c.parent_id', 0)
            ->where('c.is_gift', 0)
            ->where('rec_type', CART_GENERAL_GOODS)
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();

        if (! $goods_list) {
            return 0;
        }

        // 初始化折扣
        $discount = 0;
        $favourable_name = [];

        // 循环计算每个优惠活动的折扣
        foreach ($favourable_list as $favourable) {
            $total_amount = 0;
            if ($favourable['act_range'] === FAR_ALL) {
                foreach ($goods_list as $goods) {
                    $total_amount += $goods['subtotal'];
                }
            } elseif ($favourable['act_range'] === FAR_CATEGORY) {
                // 找出分类id的子分类id
                $id_list = [];
                $raw_id_list = explode(',', $favourable['act_range_ext']);
                foreach ($raw_id_list as $id) {
                    $id_list = array_merge($id_list, array_keys(CommonHelper::cat_list($id, 0, false)));
                }
                $ids = implode(',', array_unique($id_list));

                foreach ($goods_list as $goods) {
                    if (strpos(','.$ids.',', ','.$goods['cat_id'].',') !== false) {
                        $total_amount += $goods['subtotal'];
                    }
                }
            } elseif ($favourable['act_range'] === FAR_BRAND) {
                foreach ($goods_list as $goods) {
                    if (strpos(','.$favourable['act_range_ext'].',', ','.$goods['brand_id'].',') !== false) {
                        $total_amount += $goods['subtotal'];
                    }
                }
            } elseif ($favourable['act_range'] === FAR_GOODS) {
                foreach ($goods_list as $goods) {
                    if (strpos(','.$favourable['act_range_ext'].',', ','.$goods['goods_id'].',') !== false) {
                        $total_amount += $goods['subtotal'];
                    }
                }
            } else {
                continue;
            }
            if ($total_amount > 0 && $total_amount >= $favourable['min_amount'] && ($total_amount <= $favourable['max_amount'] || $favourable['max_amount'] === 0)) {
                if ($favourable['act_type'] === FAT_DISCOUNT) {
                    $discount += $total_amount * (1 - $favourable['act_type_ext'] / 100);
                } elseif ($favourable['act_type'] === FAT_PRICE) {
                    $discount += $favourable['act_type_ext'];
                }
            }
        }

        return $discount;
    }

    /**
     * 添加礼包到购物车
     *
     * @param  int  $package_id  礼包编号
     * @param  int  $num  礼包数量
     * @return bool
     */
    public static function add_package_to_cart($package_id, $num = 1)
    {
        err()->clean();

        // 取得礼包信息
        $package = CommonHelper::get_package_info($package_id);

        if (empty($package)) {
            err()->add(lang('goods_not_exists'), ERR_NOT_EXISTS);

            return false;
        }

        // 是否正在销售
        if ($package['is_on_sale'] === 0) {
            err()->add(lang('not_on_sale'), ERR_NOT_ON_SALE);

            return false;
        }

        // 现有库存是否还能凑齐一个礼包
        if (cfg('use_storage') === '1' && OrderHelper::judge_package_stock($package_id)) {
            err()->add(sprintf(lang('shortage'), 1), ERR_OUT_OF_STOCK);

            return false;
        }

        // 初始化要插入购物车的基本件数据
        $parent = [
            'user_id' => Session::get('user_id', 0),
            'session_id' => SESS_ID,
            'goods_id' => $package_id,
            'goods_sn' => '',
            'goods_name' => addslashes($package['package_name']),
            'market_price' => $package['market_package'],
            'goods_price' => $package['package_price'],
            'goods_number' => $num,
            'goods_attr' => '',
            'goods_attr_id' => '',
            'is_real' => $package['is_real'],
            'extension_code' => 'package_buy',
            'is_gift' => 0,
            'rec_type' => CART_GENERAL_GOODS,
        ];

        // 如果数量不为0，作为基本件插入
        if ($num > 0) {
            // 检查该商品是否已经存在在购物车中
            $row = (array) DB::table('user_cart')
                ->select('goods_number')
                ->where('session_id', SESS_ID)
                ->where('goods_id', $package_id)
                ->where('parent_id', 0)
                ->where('extension_code', 'package_buy')
                ->where('rec_type', CART_GENERAL_GOODS)
                ->first();

            if ($row) { // 如果购物车已经有此物品，则更新
                $num += $row['goods_number'];
                if (cfg('use_storage') === 0 || $num > 0) {
                    DB::table('user_cart')
                        ->where('session_id', SESS_ID)
                        ->where('goods_id', $package_id)
                        ->where('parent_id', 0)
                        ->where('extension_code', 'package_buy')
                        ->where('rec_type', CART_GENERAL_GOODS)
                        ->update(['goods_number' => $num]);
                } else {
                    err()->add(sprintf(lang('shortage'), $num), ERR_OUT_OF_STOCK);

                    return false;
                }
            } else { // 购物车没有此物品，则插入
                DB::table('user_cart')->insert($parent);
            }
        }

        // 把赠品删除
        DB::table('user_cart')
            ->where('session_id', SESS_ID)
            ->where('is_gift', '!=', 0)
            ->delete();

        return true;
    }

    /**
     * 得到新发货单号
     *
     * @return string
     */
    public static function get_delivery_sn()
    {
        // 选择一个随机的方案
        mt_srand((float) microtime() * 1000000);

        return date('YmdHi').str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
    }

    /**
     * 检查礼包内商品的库存
     */
    public static function judge_package_stock($package_id, $package_num = 1): bool
    {
        $row = DB::table('activity_package')
            ->select('goods_id', 'product_id', 'goods_number')
            ->where('package_id', $package_id)
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();

        if (empty($row)) {
            return true;
        }

        // 分离货品与商品
        $product_ids = [];
        $goods_ids = [];
        foreach ($row as $value) {
            if ($value['product_id'] > 0) {
                $product_ids[] = $value['product_id'];
            } else {
                $goods_ids[] = $value['goods_id'];
            }
        }

        // 检查货品库存
        if (! empty($product_ids)) {
            $exists = DB::table('goods_product as p')
                ->join('activity_package as pg', 'pg.product_id', '=', 'p.product_id')
                ->where('pg.package_id', $package_id)
                ->whereIn('p.product_id', $product_ids)
                ->whereRaw('pg.goods_number * ? > p.product_number', [$package_num])
                ->exists();

            if ($exists) {
                return true;
            }
        }

        // 检查商品库存
        if (! empty($goods_ids)) {
            $exists = DB::table('goods as g')
                ->join('activity_package as pg', 'pg.goods_id', '=', 'g.goods_id')
                ->where('pg.package_id', $package_id)
                ->whereIn('g.goods_id', $goods_ids)
                ->whereRaw('pg.goods_number * ? > g.goods_number', [$package_num])
                ->exists();

            if ($exists) {
                return true;
            }
        }

        return false;
    }
}
