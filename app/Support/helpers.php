<?php

declare(strict_types=1);

use Illuminate\Support\Facades\DB;

require __DIR__ . '/constant.php';

function cfg(string $name)
{
    return config('shop.'.$name);
}

function lang($name = '')
{
    static $lang = [];
    if (empty($name)) {
        return $lang;
    }

    if (is_array($name)) {
        $files = $name;
        foreach ($files as $file) {
            if (file_exists($file)) {
                $lang = array_merge($lang, require $file);
            }
        }

        return $lang;
    }

    return $lang[$name] ?? '';
}

/**
 * 取得支付方式id列表
 *
 * @param  bool  $is_cod  是否货到付款
 * @return array
 */
function payment_id_list($is_cod)
{
    return DB::table('payment')
        ->where('is_cod', $is_cod ? 1 : 0)
        ->pluck('pay_id')
        ->all();
}

/**
 * 生成查询订单的sql
 *
 * @param  string  $type  类型
 * @param  string  $alias  order表的别名（包括.例如 o.）
 * @return string
 */
function order_query_sql($type = 'finished', $alias = '')
{
    // 已完成订单
    if ($type === 'finished') {
        return " AND {$alias}order_status ".db_create_in([OS_CONFIRMED, OS_SPLITED]).
            " AND {$alias}shipping_status ".db_create_in([SS_SHIPPED, SS_RECEIVED]).
            " AND {$alias}pay_status ".db_create_in([PS_PAYED, PS_PAYING]).' ';
    } // 待发货订单
    elseif ($type === 'await_ship') {
        return " AND   {$alias}order_status ".
            db_create_in([OS_CONFIRMED, OS_SPLITED, OS_SPLITING_PART]).
            " AND   {$alias}shipping_status ".
            db_create_in([SS_UNSHIPPED, SS_PREPARING, SS_SHIPPED_ING]).
            " AND ( {$alias}pay_status ".db_create_in([PS_PAYED, PS_PAYING])." OR {$alias}pay_id ".db_create_in(payment_id_list(true)).') ';
    } // 待付款订单
    elseif ($type === 'await_pay') {
        return " AND   {$alias}order_status ".db_create_in([OS_CONFIRMED, OS_SPLITED]).
            " AND   {$alias}pay_status = '".PS_UNPAYED."'".
            " AND ( {$alias}shipping_status ".db_create_in([SS_SHIPPED, SS_RECEIVED])." OR {$alias}pay_id ".db_create_in(payment_id_list(false)).') ';
    } // 未确认订单
    elseif ($type === 'unconfirmed') {
        return " AND {$alias}order_status = '".OS_UNCONFIRMED."' ";
    } // 未处理订单：用户可操作
    elseif ($type === 'unprocessed') {
        return " AND {$alias}order_status ".db_create_in([OS_UNCONFIRMED, OS_CONFIRMED]).
            " AND {$alias}shipping_status = '".SS_UNSHIPPED."'".
            " AND {$alias}pay_status = '".PS_UNPAYED."' ";
    } // 未付款未发货订单：管理员可操作
    elseif ($type === 'unpay_unship') {
        return " AND {$alias}order_status ".db_create_in([OS_UNCONFIRMED, OS_CONFIRMED]).
            " AND {$alias}shipping_status ".db_create_in([SS_UNSHIPPED, SS_PREPARING]).
            " AND {$alias}pay_status = '".PS_UNPAYED."' ";
    } // 已发货订单：不论是否付款
    elseif ($type === 'shipped') {
        return " AND {$alias}order_status = '".OS_CONFIRMED."'".
            " AND {$alias}shipping_status ".db_create_in([SS_SHIPPED, SS_RECEIVED]).' ';
    } else {
        exit('函数 order_query_sql 参数错误');
    }
}
