<?php

declare(strict_types=1);

namespace App\Modules\User\Controllers;

use App\Helpers\BaseHelper;
use App\Helpers\CommonHelper;
use App\Helpers\MainHelper;
use App\Helpers\OrderHelper;
use App\Helpers\TimeHelper;
use App\Helpers\TransactionHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class OrderController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        // 查看订单列表
        if ($action === 'order_list') {
            $page = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;

            $record_count = DB::table('order_info')->where('user_id', $this->getUserId())->count();

            $pager = MainHelper::get_pager('user.php', ['act' => $action], $record_count, $page);

            $orders = TransactionHelper::get_user_orders($this->getUserId(), $pager['size'], $pager['start']);
            $merge = TransactionHelper::get_user_merge($this->getUserId());

            $this->assign('merge', $merge);
            $this->assign('pager', $pager);
            $this->assign('orders', $orders);

            return $this->display('user_transaction');
        }

        // 查看订单详情
        if ($action === 'order_detail') {
            $order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

            // 订单详情
            $order = TransactionHelper::get_order_detail($order_id, $this->getUserId());

            if ($order === false) {
                $this->show_message(lang('back_home_lnk'), './', '', 'error');
            }

            // 是否显示添加到购物车
            if ($order['extension_code'] != 'group_buy' && $order['extension_code'] != 'exchange_goods') {
                $this->assign('allow_to_cart', 1);
            }

            // 订单商品
            $goods_list = OrderHelper::order_goods($order_id);
            foreach ($goods_list as $key => $value) {
                $goods_list[$key]['market_price'] = CommonHelper::price_format($value['market_price'], false);
                $goods_list[$key]['goods_price'] = CommonHelper::price_format($value['goods_price'], false);
                $goods_list[$key]['subtotal'] = CommonHelper::price_format($value['subtotal'], false);
            }

            // 设置能否修改使用余额数
            if ($order['order_amount'] > 0) {
                if ($order['order_status'] === OS_UNCONFIRMED || $order['order_status'] === OS_CONFIRMED) {
                    $user = OrderHelper::user_info($order['user_id']);
                    if ($user['user_money'] + $user['credit_line'] > 0) {
                        $this->assign('allow_edit_surplus', 1);
                        $this->assign('max_surplus', sprintf(lang('max_surplus'), $user['user_money']));
                    }
                }
            }

            // 未发货，未付款时允许更换支付方式
            if ($order['order_amount'] > 0 && $order['pay_status'] === PS_UNPAYED && $order['shipping_status'] === SS_UNSHIPPED) {
                $payment_list = OrderHelper::available_payment_list(false, 0, true);

                // 过滤掉当前支付方式和余额支付方式
                if (is_array($payment_list)) {
                    foreach ($payment_list as $key => $payment) {
                        if ($payment['pay_id'] === $order['pay_id'] || $payment['pay_code'] === 'balance') {
                            unset($payment_list[$key]);
                        }
                    }
                }
                $this->assign('payment_list', $payment_list);
            }

            // 订单 支付 配送 状态语言项
            $order['order_status'] = lang('os')[$order['order_status']];
            $order['pay_status'] = lang('ps')[$order['pay_status']];
            $order['shipping_status'] = lang('ss')[$order['shipping_status']];

            $this->assign('order', $order);
            $this->assign('goods_list', $goods_list);

            return $this->display('user_transaction');
        }

        // 编辑使用余额支付的处理
        if ($action === 'act_edit_surplus') {
            // 检查是否登录
            if (Session::get('user_id', 0) <= 0) {
                return response()->redirectTo('/');
            }

            // 检查订单号
            $order_id = intval($_POST['order_id']);
            if ($order_id <= 0) {
                return response()->redirectTo('/');
            }

            // 检查余额
            $surplus = floatval($_POST['surplus']);
            if ($surplus <= 0) {
                $this->show_message(lang('error_surplus_invalid'), lang('order_detail'), 'user.php?act=order_detail&order_id='.$order_id, 'error');
            }

            // 取得订单
            $order = OrderHelper::order_info($order_id);
            if (empty($order)) {
                return response()->redirectTo('/');
            }

            // 检查订单用户跟当前用户是否一致
            if (Session::get('user_id', 0) != $order['user_id']) {
                return response()->redirectTo('/');
            }

            // 检查订单是否未付款，检查应付款金额是否大于0
            if ($order['pay_status'] != PS_UNPAYED || $order['order_amount'] <= 0) {
                $this->show_message(lang('error_order_is_paid'), lang('order_detail'), 'user.php?act=order_detail&order_id='.$order_id, 'error');
            }

            // 计算应付款金额（减去支付费用）
            $order['order_amount'] -= $order['pay_fee'];

            // 余额是否超过了应付款金额，改为应付款金额
            if ($surplus > $order['order_amount']) {
                $surplus = $order['order_amount'];
            }

            // 取得用户信息
            $user = OrderHelper::user_info(Session::get('user_id'));

            // 用户帐户余额是否足够
            if ($surplus > $user['user_money'] + $user['credit_line']) {
                $this->show_message(lang('error_surplus_not_enough'), lang('order_detail'), 'user.php?act=order_detail&order_id='.$order_id, 'error');
            }

            // 修改订单，重新计算支付费用
            $order['surplus'] += $surplus;
            $order['order_amount'] -= $surplus;
            if ($order['order_amount'] > 0) {
                $cod_fee = 0;
                if ($order['shipping_id'] > 0) {
                    $regions = [$order['country'], $order['province'], $order['city'], $order['district']];
                    $shipping = OrderHelper::shipping_area_info($order['shipping_id'], $regions);
                    if ($shipping['support_cod'] === '1') {
                        $cod_fee = $shipping['pay_fee'];
                    }
                }

                $pay_fee = 0;
                if ($order['pay_id'] > 0) {
                    $pay_fee = OrderHelper::pay_fee($order['pay_id'], $order['order_amount'], $cod_fee);
                }

                $order['pay_fee'] = $pay_fee;
                $order['order_amount'] += $pay_fee;
            }

            // 如果全部支付，设为已确认、已付款
            if ($order['order_amount'] === 0) {
                if ($order['order_status'] === OS_UNCONFIRMED) {
                    $order['order_status'] = OS_CONFIRMED;
                    $order['confirm_time'] = TimeHelper::gmtime();
                }
                $order['pay_status'] = PS_PAYED;
                $order['pay_time'] = TimeHelper::gmtime();
            }
            $order = BaseHelper::addslashes_deep($order);
            OrderHelper::update_order($order_id, $order);

            // 更新用户余额
            $change_desc = sprintf(lang('pay_order_by_surplus'), $order['order_sn']);
            CommonHelper::log_account_change($user['user_id'], (-1) * $surplus, 0, 0, 0, $change_desc);

            // 跳转
            return response()->redirectTo('user.php?act=order_detail&order_id='.$order_id);
        }

        // 编辑使用余额支付的处理
        if ($action === 'act_edit_payment') {
            // 检查是否登录
            if (Session::get('user_id', 0) <= 0) {
                return response()->redirectTo('/');
            }

            // 检查支付方式
            $pay_id = intval($_POST['pay_id']);
            if ($pay_id <= 0) {
                return response()->redirectTo('/');
            }

            $payment_info = OrderHelper::payment_info($pay_id);
            if (empty($payment_info)) {
                return response()->redirectTo('/');
            }

            // 检查订单号
            $order_id = intval($_POST['order_id']);
            if ($order_id <= 0) {
                return response()->redirectTo('/');
            }

            // 取得订单
            $order = OrderHelper::order_info($order_id);
            if (empty($order)) {
                return response()->redirectTo('/');
            }

            // 检查订单用户跟当前用户是否一致
            if (Session::get('user_id', 0) != $order['user_id']) {
                return response()->redirectTo('/');
            }

            // 检查订单是否未付款和未发货 以及订单金额是否为0 和支付id是否为改变
            if ($order['pay_status'] != PS_UNPAYED || $order['shipping_status'] != SS_UNSHIPPED || $order['goods_amount'] <= 0 || $order['pay_id'] === $pay_id) {
                return response()->redirectTo("user.php?act=order_detail&order_id=$order_id");
            }

            $order_amount = $order['order_amount'] - $order['pay_fee'];
            $pay_fee = OrderHelper::pay_fee($pay_id, $order_amount);
            $order_amount += $pay_fee;

            DB::table('order_info')
                ->where('order_id', $order_id)
                ->update([
                    'pay_id' => $pay_id,
                    'pay_name' => $payment_info['pay_name'],
                    'pay_fee' => $pay_fee,
                    'order_amount' => $order_amount,
                ]);

            // 跳转
            return response()->redirectTo("user.php?act=order_detail&order_id=$order_id");
        }

        // 取消订单
        if ($action === 'cancel_order') {
            $order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

            if (TransactionHelper::cancel_order($order_id, $this->getUserId())) {
                return response()->redirectTo('user.php?act=order_list');
            } else {
                $this->show_message(lang('order_list_lnk'), 'user.php?act=order_list', '', 'error');
            }
        }

        // 确认收货
        if ($action === 'affirm_received') {
            $order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

            if (TransactionHelper::affirm_received($order_id, $this->getUserId())) {
                return response()->redirectTo('user.php?act=order_list');
            } else {
                $this->show_message(lang('order_list_lnk'), 'user.php?act=order_list', '', 'error');
            }
        }

        // 合并订单
        if ($action === 'merge_order') {
            $from_order = isset($_POST['from_order']) ? trim($_POST['from_order']) : '';
            $to_order = isset($_POST['to_order']) ? trim($_POST['to_order']) : '';
            if (TransactionHelper::merge_user_order($from_order, $to_order, $this->getUserId())) {
                $this->show_message(lang('merge_order_success'), lang('order_list_lnk'), 'user.php?act=order_list', 'info');
            } else {
                $this->show_message(lang('order_list_lnk'), '', '', 'error');
            }
        }

        // 将指定订单中商品添加到购物车
        if ($action === 'return_to_cart') {
            $result = ['error' => 0, 'message' => '', 'content' => ''];
            $order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
            if ($order_id === 0) {
                $result['error'] = 1;
                $result['message'] = lang('order_id_empty');

                return response()->json($result);
            }

            if ($this->getUserId() === 0) {
                // 用户没有登录
                $result['error'] = 1;
                $result['message'] = lang('login_please');

                return response()->json($result);
            }

            // 检查订单是否属于该用户
            $order_user = DB::table('order_info')->where('order_id', $order_id)->value('user_id');
            if (empty($order_user)) {
                $result['error'] = 1;
                $result['message'] = lang('order_exist');

                return response()->json($result);
            } else {
                if ($order_user != $this->getUserId()) {
                    $result['error'] = 1;
                    $result['message'] = lang('no_priv');

                    return response()->json($result);
                }
            }

            $message = TransactionHelper::return_to_cart($order_id);

            if ($message === true) {
                $result['error'] = 0;
                $result['message'] = lang('return_to_cart_success');

                return response()->json($result);
            } else {
                $result['error'] = 1;
                $result['message'] = lang('order_exist');

                return response()->json($result);
            }
        }

        // 保存订单详情收货地址
        if ($action === 'save_order_address') {
            $address = [
                'consignee' => isset($_POST['consignee']) ? BaseHelper::compile_str(trim($_POST['consignee'])) : '',
                'email' => isset($_POST['email']) ? BaseHelper::compile_str(trim($_POST['email'])) : '',
                'address' => isset($_POST['address']) ? BaseHelper::compile_str(trim($_POST['address'])) : '',
                'zipcode' => isset($_POST['zipcode']) ? BaseHelper::compile_str(BaseHelper::make_semiangle(trim($_POST['zipcode']))) : '',
                'tel' => isset($_POST['tel']) ? BaseHelper::compile_str(trim($_POST['tel'])) : '',
                'mobile' => isset($_POST['mobile']) ? BaseHelper::compile_str(trim($_POST['mobile'])) : '',
                'sign_building' => isset($_POST['sign_building']) ? BaseHelper::compile_str(trim($_POST['sign_building'])) : '',
                'best_time' => isset($_POST['best_time']) ? BaseHelper::compile_str(trim($_POST['best_time'])) : '',
                'order_id' => isset($_POST['order_id']) ? intval($_POST['order_id']) : 0,
            ];
            if (TransactionHelper::save_order_address($address, $this->getUserId())) {
                return response()->redirectTo('user.php?act=order_detail&order_id='.$address['order_id']);
            } else {
                $this->show_message(lang('order_list_lnk'), 'user.php?act=order_list', '', 'error');
            }
        }

        if ($action === 'track_packages') {
            $page = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;

            $orders = [];

            $res = DB::table('order_info')
                ->select('order_id', 'order_sn', 'invoice_no', 'shipping_id')
                ->where('user_id', $this->getUserId())
                ->where('shipping_status', SS_SHIPPED)
                ->get()
                ->toArray();
            $record_count = 0;
            foreach ($res as $item) {
                $shipping = OrderHelper::get_shipping_object($item['shipping_id']);

                if (method_exists($shipping, 'query')) {
                    $query_link = $shipping->query($item['invoice_no']);
                } else {
                    $query_link = $item['invoice_no'];
                }

                if ($query_link != $item['invoice_no']) {
                    $item['query_link'] = $query_link;
                    $orders[] = $item;
                    $record_count += 1;
                }
            }
            $pager = MainHelper::get_pager('user.php', ['act' => $action], $record_count, $page);
            $this->assign('pager', $pager);
            $this->assign('orders', $orders);

            return $this->display('user_transaction');
        }

        if ($action === 'order_query') {
            $_GET['order_sn'] = trim(substr($_GET['order_sn'], 1));
            $order_sn = empty($_GET['order_sn']) ? '' : addslashes($_GET['order_sn']);

            $result = ['error' => 0, 'message' => '', 'content' => ''];

            if (Session::has('last_order_query')) {
                if (time() - Session::get('last_order_query') <= 10) {
                    $result['error'] = 1;
                    $result['message'] = lang('order_query_toofast');

                    return response()->json($result);
                }
            }
            Session::put('last_order_query', time());

            if (empty($order_sn)) {
                $result['error'] = 1;
                $result['message'] = lang('invalid_order_sn');

                return response()->json($result);
            }

            $row = DB::table('order_info')
                ->select('order_id', 'order_status', 'shipping_status', 'pay_status', 'shipping_time', 'shipping_id', 'invoice_no', 'user_id')
                ->where('order_sn', $order_sn)
                ->first();
            $row = (array) $row;
            if (empty($row)) {
                $result['error'] = 1;
                $result['message'] = lang('invalid_order_sn');

                return response()->json($result);
            }

            $order_query = [];
            $order_query['order_sn'] = $order_sn;
            $order_query['order_id'] = $row['order_id'];
            $order_query['order_status'] = lang('os')[$row['order_status']].','.lang('ps')[$row['pay_status']].','.lang('ss')[$row['shipping_status']];

            if ($row['invoice_no'] && $row['shipping_id'] > 0) {
                $shipping_code = DB::table('shipping')->where('shipping_id', $row['shipping_id'])->value('shipping_code');
                $plugin = ROOT_PATH.'includes/modules/shipping/'.$shipping_code.'.php';
                if (file_exists($plugin)) {
                    include_once $plugin;
                    $shipping = new $shipping_code;
                    $order_query['invoice_no'] = $shipping->query((string) $row['invoice_no']);
                } else {
                    $order_query['invoice_no'] = (string) $row['invoice_no'];
                }
            }

            $order_query['user_id'] = $row['user_id'];
            // 如果是匿名用户显示发货时间
            if ($row['user_id'] === 0 && $row['shipping_time'] > 0) {
                $order_query['shipping_date'] = TimeHelper::local_date(cfg('date_format'), $row['shipping_time']);
            }
            $this->assign('order_query', $order_query);
            $result['content'] = $this->fetch('web::library/order_query');

            return response()->json($result);
        }
    }
}
