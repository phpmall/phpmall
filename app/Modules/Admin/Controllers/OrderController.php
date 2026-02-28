<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Helpers\BaseHelper;
use App\Helpers\CodeHelper;
use App\Helpers\CommonHelper;
use App\Helpers\GoodsHelper;
use App\Helpers\OrderHelper;
use App\Helpers\TimeHelper;
use App\Modules\Admin\Helpers\MainHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class OrderController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        /**
         * 订单查询
         */
        if ($action === 'order_query') {
            $this->admin_priv('order_view');

            // 载入配送方式
            $this->assign('shipping_list', OrderHelper::shipping_list());

            // 载入支付方式
            $this->assign('pay_list', OrderHelper::payment_list());

            // 载入国家
            $this->assign('country_list', CommonHelper::get_regions());

            // 载入订单状态、付款状态、发货状态
            $this->assign('os_list', $this->get_status_list('order'));
            $this->assign('ps_list', $this->get_status_list('payment'));
            $this->assign('ss_list', $this->get_status_list('shipping'));

            $this->assign('ur_here', lang('03_order_query'));
            $this->assign('action_link', ['href' => 'order.php?act=list', 'text' => lang('02_order_list')]);

            return $this->display('order_query');
        }

        /**
         * 订单列表
         */
        if ($action === 'list') {
            $this->admin_priv('order_view');

            $this->assign('ur_here', lang('02_order_list'));
            $this->assign('action_link', ['href' => 'order.php?act=order_query', 'text' => lang('03_order_query')]);

            $this->assign('status_list', lang('cs'));   // 订单状态

            $this->assign('os_unconfirmed', OS_UNCONFIRMED);
            $this->assign('cs_await_pay', CS_AWAIT_PAY);
            $this->assign('cs_await_ship', CS_AWAIT_SHIP);
            $this->assign('full_page', 1);

            $order_list = $this->order_list();
            $this->assign('order_list', $order_list['orders']);
            $this->assign('filter', $order_list['filter']);
            $this->assign('record_count', $order_list['record_count']);
            $this->assign('page_count', $order_list['page_count']);

            return $this->display('order_list');
        }

        /**
         * 排序、分页、查询
         */
        if ($action === 'query') {
            $this->admin_priv('order_view');

            $order_list = $this->order_list();

            $this->assign('order_list', $order_list['orders']);
            $this->assign('filter', $order_list['filter']);
            $this->assign('record_count', $order_list['record_count']);
            $this->assign('page_count', $order_list['page_count']);
            $sort_flag = MainHelper::sort_flag($order_list['filter']);
            $this->assign($sort_flag['tag'], $sort_flag['img']);

            return $this->make_json_result($this->fetch('order_list'), '', ['filter' => $order_list['filter'], 'page_count' => $order_list['page_count']]);
        }

        /**
         * 订单详情页面
         */
        if ($action === 'info') {
            // 根据订单id或订单号查询订单信息
            if (isset($_REQUEST['order_id'])) {
                $order_id = intval($_REQUEST['order_id']);
                $order = OrderHelper::order_info($order_id);
            } elseif (isset($_REQUEST['order_sn'])) {
                $order_sn = trim($_REQUEST['order_sn']);
                $order = OrderHelper::order_info(0, $order_sn);
            } else {
                // 如果参数不存在，退出
                exit('invalid parameter');
            }

            // 如果订单不存在，退出
            if (empty($order)) {
                exit('order does not exist');
            }

            // 根据订单是否完成检查权限
            if (OrderHelper::order_finished($order)) {
                $this->admin_priv('order_view_finished');
            } else {
                $this->admin_priv('order_view');
            }

            // 如果管理员属于某个办事处，检查该订单是否也属于这个办事处
            $agency_id = DB::table('admin_user')
                ->where('user_id', Session::get('admin_id'))
                ->value('agency_id');
            if ($agency_id > 0) {
                if ($order['agency_id'] != $agency_id) {
                    return $this->sys_msg(lang('priv_error'));
                }
            }

            // 取得上一个、下一个订单号
            $ecscpCookie = Cookie::get('ECSCP');
            $lastfilter = is_array($ecscpCookie) ? ($ecscpCookie['lastfilter'] ?? '') : '';
            if (! empty($lastfilter)) {
                $filter = unserialize(urldecode($lastfilter));
                if (! empty($filter['composite_status'])) {
                    $where = '';
                    // 综合状态
                    switch ($filter['composite_status']) {
                        case CS_AWAIT_PAY:
                            $where .= order_query_sql('await_pay');
                            break;

                        case CS_AWAIT_SHIP:
                            $where .= order_query_sql('await_ship');
                            break;

                        case CS_FINISHED:
                            $where .= order_query_sql('finished');
                            break;

                        default:
                            if ($filter['composite_status'] != -1) {
                                $where .= " AND o.order_status = '$filter[composite_status]' ";
                            }
                    }
                }
            }
            $prev_id_query = DB::table('order_info as o')
                ->where('order_id', '<', $order['order_id']);
            if ($agency_id > 0) {
                $prev_id_query->where('agency_id', $agency_id);
            }
            if (! empty($where)) {
                $prev_id_query->whereRaw(ltrim($where, ' AND '));
            }
            $this->assign('prev_id', $prev_id_query->max('order_id'));

            $next_id_query = DB::table('order_info as o')
                ->where('order_id', '>', $order['order_id']);
            if ($agency_id > 0) {
                $next_id_query->where('agency_id', $agency_id);
            }
            if (! empty($where)) {
                $next_id_query->whereRaw(ltrim($where, ' AND '));
            }
            $this->assign('next_id', $next_id_query->min('order_id'));

            // 取得用户名
            if ($order['user_id'] > 0) {
                $user = OrderHelper::user_info($order['user_id']);
                if (! empty($user)) {
                    $order['user_name'] = $user['user_name'];
                }
            }

            // 取得所有办事处
            $agency_list = DB::table('shop_agency')
                ->select('agency_id', 'agency_name')
                ->get()
                ->toArray();
            $this->assign('agency_list', $agency_list);

            // 取得区域名
            $order_region = DB::table('order_info as o')
                ->leftJoin('shop_region as c', 'o.country', '=', 'c.region_id')
                ->leftJoin('shop_region as p', 'o.province', '=', 'p.region_id')
                ->leftJoin('shop_region as t', 'o.city', '=', 't.region_id')
                ->leftJoin('shop_region as d', 'o.district', '=', 'd.region_id')
                ->where('o.order_id', $order['order_id'])
                ->select(DB::raw("concat(IFNULL(c.region_name, ''), '  ', IFNULL(p.region_name, ''), '  ', IFNULL(t.region_name, ''), '  ', IFNULL(d.region_name, '')) AS region_name"))
                ->first();
            $order['region'] = $order_region->region_name ?? '';

            // 格式化金额
            if ($order['order_amount'] < 0) {
                $order['money_refund'] = abs($order['order_amount']);
                $order['formated_money_refund'] = CommonHelper::price_format(abs($order['order_amount']));
            }

            // 其他处理
            $order['order_time'] = TimeHelper::local_date(cfg('time_format'), $order['add_time']);
            $order['pay_time'] = $order['pay_time'] > 0 ?
                TimeHelper::local_date(cfg('time_format'), $order['pay_time']) : lang('ps')[PS_UNPAYED];
            $order['shipping_time'] = $order['shipping_time'] > 0 ?
                TimeHelper::local_date(cfg('time_format'), $order['shipping_time']) : lang('ss')[SS_UNSHIPPED];
            $order['status'] = lang('os')[$order['order_status']].','.lang('ps')[$order['pay_status']].','.lang('ss')[$order['shipping_status']];
            $order['invoice_no'] = $order['shipping_status'] === SS_UNSHIPPED || $order['shipping_status'] === SS_PREPARING ? lang('ss')[SS_UNSHIPPED] : $order['invoice_no'];

            // 取得订单的来源
            if ($order['from_ad'] === 0) {
                $order['referer'] = empty($order['referer']) ? lang('from_self_site') : $order['referer'];
            } elseif ($order['from_ad'] === -1) {
                $order['referer'] = lang('from_goods_js').' ('.lang('from').$order['referer'].')';
            } else {
                // 查询广告的名称
                $ad_name = DB::table('ad')->where('ad_id', $order['from_ad'])->value('ad_name');
                $order['referer'] = lang('from_ad_js').$ad_name.' ('.lang('from').$order['referer'].')';
            }

            // 此订单的发货备注(此订单的最后一条操作记录)
            $order['invoice_note'] = DB::table('order_action')
                ->where('order_id', $order['order_id'])
                ->where('shipping_status', 1)
                ->orderBy('log_time', 'desc')
                ->value('action_note');

            // 取得订单商品总重量
            $weight_price = OrderHelper::order_weight_price($order['order_id']);
            $order['total_weight'] = $weight_price['formated_weight'];

            // 将名字转换回来
            $order['user_name'] = urldecode($order['user_name']);

            // 参数赋值：订单
            $this->assign('order', $order);

            // 取得用户信息
            if ($order['user_id'] > 0) {
                // 用户等级
                if ($user['user_rank'] > 0) {
                    $user['rank_name'] = DB::table('user_rank')
                        ->where('rank_id', $user['user_rank'])
                        ->value('rank_name');
                } else {
                    $user['rank_name'] = DB::table('user_rank')
                        ->where('min_points', '<=', intval($user['rank_points']))
                        ->orderBy('min_points', 'desc')
                        ->value('rank_name');
                }

                // 用户红包数量
                $day = getdate();
                $today = TimeHelper::local_mktime(23, 59, 59, $day['mon'], $day['mday'], $day['year']);
                $user['bonus_count'] = DB::table('activity_bonus as bt')
                    ->join('user_bonus as ub', 'bt.type_id', '=', 'ub.bonus_type_id')
                    ->where('ub.user_id', $order['user_id'])
                    ->where('ub.order_id', 0)
                    ->where('bt.use_start_date', '<=', $today)
                    ->where('bt.use_end_date', '>=', $today)
                    ->count();
                $this->assign('user', $user);

                // 地址信息
                $address_list = DB::table('user_address')
                    ->where('user_id', $order['user_id'])
                    ->get()
                    ->toArray();
                $this->assign('address_list', $address_list);
            }

            // 取得订单商品及货品
            $goods_list = [];
            $goods_attr = [];
            $res = DB::table('order_goods as o')
                ->leftJoin('goods_product as p', 'p.product_id', '=', 'o.product_id')
                ->leftJoin('goods as g', 'o.goods_id', '=', 'g.goods_id')
                ->leftJoin('goods_brand as b', 'g.brand_id', '=', 'b.brand_id')
                ->select('o.*', DB::raw('IF(o.product_id > 0, p.product_number, g.goods_number) AS storage'), 'o.goods_attr', 'g.suppliers_id', DB::raw("IFNULL(b.brand_name, '') AS brand_name"), 'p.product_sn')
                ->where('o.order_id', $order['order_id'])
                ->get()
                ->toArray();
            foreach ($res as $row) {
                $row = (array) $row;
                $row['formated_subtotal'] = CommonHelper::price_format($row['goods_price'] * $row['goods_number']);
                $row['formated_goods_price'] = CommonHelper::price_format($row['goods_price']);

                $goods_attr[] = explode(' ', trim($row['goods_attr'])); // 将商品属性拆分为一个数组

                if ($row['extension_code'] === 'package_buy') {
                    $row['storage'] = '';
                    $row['brand_name'] = '';
                    $row['package_goods_list'] = CommonHelper::get_package_goods($row['goods_id']);
                }

                $goods_list[] = $row;
            }

            $attr = [];
            $arr = [];
            foreach ($goods_attr as $index => $array_val) {
                foreach ($array_val as $value) {
                    $arr = explode(':', $value); // 以 : 号将属性拆开
                    $attr[$index][] = @['name' => $arr[0], 'value' => $arr[1]];
                }
            }

            $this->assign('goods_attr', $attr);
            $this->assign('goods_list', $goods_list);

            // 取得能执行的操作列表
            $operable_list = $this->operable_list($order);
            $this->assign('operable_list', $operable_list);

            // 取得订单操作记录
            $act_list = [];
            $res = DB::table('order_action')
                ->where('order_id', $order['order_id'])
                ->orderBy('log_time', 'desc')
                ->orderBy('action_id', 'desc')
                ->get()
                ->toArray();
            foreach ($res as $row) {
                $row = (array) $row;
                $row['order_status'] = lang('os')[$row['order_status']];
                $row['pay_status'] = lang('ps')[$row['pay_status']];
                $row['shipping_status'] = lang('ss')[$row['shipping_status']];
                $row['action_time'] = TimeHelper::local_date(cfg('time_format'), $row['log_time']);
                $act_list[] = $row;
            }
            $this->assign('action_list', $act_list);

            // 取得是否存在实体商品
            $this->assign('exist_real_goods', OrderHelper::exist_real_goods($order['order_id']));

            // 是否打印订单，分别赋值
            if (isset($_GET['print'])) {
                $this->assign('shop_name', cfg('shop_name'));
                $this->assign('shop_url', ecs()->url());
                $this->assign('shop_address', cfg('shop_address'));
                $this->assign('service_phone', cfg('service_phone'));
                $this->assign('print_time', TimeHelper::local_date(cfg('time_format')));
                $this->assign('action_user', Session::get('admin_name'));

                // $smarty->template_dir = '../' . DATA_DIR;

                return $this->display('order_print.html');
            } // 打印快递单
            elseif (isset($_GET['shipping_print'])) {
                // $this->assign('print_time',   TimeHelper::local_date(cfg('time_format')));
                $region_id_arr = [];
                if (! empty(cfg('shop_country'))) {
                    $region_id_arr[] = cfg('shop_country');
                }
                if (! empty(cfg('shop_province'))) {
                    $region_id_arr[] = cfg('shop_province');
                }
                if (! empty(cfg('shop_city'))) {
                    $region_id_arr[] = cfg('shop_city');
                }

                $region = DB::table('shop_region')
                    ->select('region_id', 'region_name')
                    ->whereIn('region_id', $region_id_arr)
                    ->get()
                    ->toArray();
                if (! empty($region)) {
                    foreach ($region as $region_data) {
                        $region_data = (array) $region_data;
                        $region_array[$region_data['region_id']] = $region_data['region_name'];
                    }
                }
                $this->assign('shop_name', cfg('shop_name'));
                $this->assign('order_id', $order_id);
                $this->assign('province', $region_array[cfg('shop_province')]);
                $this->assign('city', $region_array[cfg('shop_city')]);
                $this->assign('shop_address', cfg('shop_address'));
                $this->assign('service_phone', cfg('service_phone'));
                $shipping = DB::table('shipping')->where('shipping_id', $order['shipping_id'])->first();
                $shipping = $shipping ? (array) $shipping : [];

                // 打印单模式
                if ($shipping['print_model'] === 2) {
                    // 可视化
                    // 快递单
                    $shipping['print_bg'] = empty($shipping['print_bg']) ? '' : $this->get_site_root_url().$shipping['print_bg'];

                    // 取快递单背景宽高
                    if (! empty($shipping['print_bg'])) {
                        $_size = @getimagesize($shipping['print_bg']);

                        if ($_size != false) {
                            $shipping['print_bg_size'] = ['width' => $_size[0], 'height' => $_size[1]];
                        }
                    }

                    if (empty($shipping['print_bg_size'])) {
                        $shipping['print_bg_size'] = ['width' => '1024', 'height' => '600'];
                    }

                    // 标签信息
                    $lable_box = [];
                    $lable_box['t_shop_country'] = $region_array[cfg('shop_country')]; // 网店-国家
                    $lable_box['t_shop_city'] = $region_array[cfg('shop_city')]; // 网店-城市
                    $lable_box['t_shop_province'] = $region_array[cfg('shop_province')]; // 网店-省份
                    $lable_box['t_shop_name'] = cfg('shop_name'); // 网店-名称
                    $lable_box['t_shop_district'] = ''; // 网店-区/县
                    $lable_box['t_shop_tel'] = cfg('service_phone'); // 网店-联系电话
                    $lable_box['t_shop_address'] = cfg('shop_address'); // 网店-地址
                    $lable_box['t_customer_country'] = $region_array[$order['country']]; // 收件人-国家
                    $lable_box['t_customer_province'] = $region_array[$order['province']]; // 收件人-省份
                    $lable_box['t_customer_city'] = $region_array[$order['city']]; // 收件人-城市
                    $lable_box['t_customer_district'] = $region_array[$order['district']]; // 收件人-区/县
                    $lable_box['t_customer_tel'] = $order['tel']; // 收件人-电话
                    $lable_box['t_customer_mobel'] = $order['mobile']; // 收件人-手机
                    $lable_box['t_customer_post'] = $order['zipcode']; // 收件人-邮编
                    $lable_box['t_customer_address'] = $order['address']; // 收件人-详细地址
                    $lable_box['t_customer_name'] = $order['consignee']; // 收件人-姓名

                    $gmtime_utc_temp = TimeHelper::gmtime(); // 获取 UTC 时间戳
                    $lable_box['t_year'] = date('Y', $gmtime_utc_temp); // 年-当日日期
                    $lable_box['t_months'] = date('m', $gmtime_utc_temp); // 月-当日日期
                    $lable_box['t_day'] = date('d', $gmtime_utc_temp); // 日-当日日期

                    $lable_box['t_order_no'] = $order['order_sn']; // 订单号-订单
                    $lable_box['t_order_postscript'] = $order['postscript']; // 备注-订单
                    $lable_box['t_order_best_time'] = $order['best_time']; // 送货时间-订单
                    $lable_box['t_pigeon'] = '√'; // √-对号
                    $lable_box['t_custom_content'] = ''; // 自定义内容

                    // 标签替换
                    $temp_config_lable = explode('||,||', $shipping['config_lable']);
                    if (! is_array($temp_config_lable)) {
                        $temp_config_lable[] = $shipping['config_lable'];
                    }
                    foreach ($temp_config_lable as $temp_key => $temp_lable) {
                        $temp_info = explode(',', $temp_lable);
                        if (is_array($temp_info)) {
                            $temp_info[1] = $lable_box[$temp_info[0]];
                        }
                        $temp_config_lable[$temp_key] = implode(',', $temp_info);
                    }
                    $shipping['config_lable'] = implode('||,||', $temp_config_lable);

                    $this->assign('shipping', $shipping);

                    return $this->display('print');
                } elseif (! empty($shipping['shipping_print'])) {
                    // 代码
                    echo $this->fetch('str:'.$shipping['shipping_print']);
                } else {
                    $shipping_code = DB::table('shipping')->where('shipping_id', $order['shipping_id'])->value('shipping_code');
                    if ($shipping_code) {
                        // include_once ROOT_PATH.'includes/modules/shipping/'.$shipping_code.'.php';
                    }

                    if (! empty(lang('shipping_print'))) {
                        echo $this->fetch('str:'.lang('shipping_print'));
                    } else {
                        echo lang('no_print_shipping');
                    }
                }
            } else {
                $this->assign('ur_here', lang('order_info'));
                $this->assign('action_link', ['href' => 'order.php?act=list&'.MainHelper::list_link_postfix(), 'text' => lang('02_order_list')]);

                return $this->display('order_info');
            }
        }

        /**
         * 发货单列表
         */
        if ($action === 'delivery_list') {
            $this->admin_priv('delivery_view');

            // 查询
            $result = $this->delivery_list();

            $this->assign('ur_here', lang('09_delivery_order'));

            $this->assign('os_unconfirmed', OS_UNCONFIRMED);
            $this->assign('cs_await_pay', CS_AWAIT_PAY);
            $this->assign('cs_await_ship', CS_AWAIT_SHIP);
            $this->assign('full_page', 1);

            $this->assign('delivery_list', $result['delivery']);
            $this->assign('filter', $result['filter']);
            $this->assign('record_count', $result['record_count']);
            $this->assign('page_count', $result['page_count']);

            return $this->display('delivery_list');
        }

        /**
         * 搜索、排序、分页
         */
        if ($action === 'delivery_query') {
            $this->admin_priv('delivery_view');

            $result = $this->delivery_list();

            $this->assign('delivery_list', $result['delivery']);
            $this->assign('filter', $result['filter']);
            $this->assign('record_count', $result['record_count']);
            $this->assign('page_count', $result['page_count']);

            $sort_flag = MainHelper::sort_flag($result['filter']);
            $this->assign($sort_flag['tag'], $sort_flag['img']);

            return $this->make_json_result($this->fetch('delivery_list'), '', ['filter' => $result['filter'], 'page_count' => $result['page_count']]);
        }

        /**
         * 发货单详细
         */
        if ($action === 'delivery_info') {
            $this->admin_priv('delivery_view');

            $delivery_id = intval(trim($_REQUEST['delivery_id']));

            // 根据发货单id查询发货单信息
            if (! empty($delivery_id)) {
                $delivery_order = $this->delivery_order_info($delivery_id);
            } else {
                exit('order does not exist');
            }

            // 如果管理员属于某个办事处，检查该订单是否也属于这个办事处
            $agency_id = DB::table('admin_user')
                ->where('user_id', Session::get('admin_id'))
                ->value('agency_id');
            if ($agency_id > 0) {
                if ($delivery_order['agency_id'] != $agency_id) {
                    return $this->sys_msg(lang('priv_error'));
                }

                // 取当前办事处信息
                $agency_name = DB::table('shop_agency')
                    ->where('agency_id', $agency_id)
                    ->value('agency_name');
                $delivery_order['agency_name'] = $agency_name;
            }

            // 取得用户名
            if ($delivery_order['user_id'] > 0) {
                $user = OrderHelper::user_info($delivery_order['user_id']);
                if (! empty($user)) {
                    $delivery_order['user_name'] = $user['user_name'];
                }
            }

            // 取得区域名
            $order_region = DB::table('order_info as o')
                ->leftJoin('shop_region as c', 'o.country', '=', 'c.region_id')
                ->leftJoin('shop_region as p', 'o.province', '=', 'p.region_id')
                ->leftJoin('shop_region as t', 'o.city', '=', 't.region_id')
                ->leftJoin('shop_region as d', 'o.district', '=', 'd.region_id')
                ->where('o.order_id', $delivery_order['order_id'])
                ->select(DB::raw("concat(IFNULL(c.region_name, ''), '  ', IFNULL(p.region_name, ''), '  ', IFNULL(t.region_name, ''), '  ', IFNULL(d.region_name, '')) AS region_name"))
                ->first();
            $delivery_order['region'] = $order_region->region_name ?? '';

            // 是否保价
            $order['insure_yn'] = empty($order['insure_fee']) ? 0 : 1;

            // 取得发货单商品
            $goods_list = DB::table('order_delivery_goods')
                ->where('delivery_id', $delivery_order['delivery_id'])
                ->get()
                ->toArray();
            $goods_list = array_map(function ($item) {
                return (array) $item;
            }, $goods_list);

            // 是否存在实体商品
            $exist_real_goods = 0;
            if ($goods_list) {
                foreach ($goods_list as $value) {
                    if ($value['is_real']) {
                        $exist_real_goods++;
                    }
                }
            }

            // 取得订单操作记录
            $act_list = [];
            $res = DB::table('order_action')
                ->where('order_id', $delivery_order['order_id'])
                ->where('action_place', 1)
                ->orderBy('log_time', 'desc')
                ->orderBy('action_id', 'desc')
                ->get()
                ->toArray();
            foreach ($res as $row) {
                $row = (array) $row;
                $row['order_status'] = lang('os')[$row['order_status']];
                $row['pay_status'] = lang('ps')[$row['pay_status']];
                $row['shipping_status'] = ($row['shipping_status'] === SS_SHIPPED_ING) ? lang('ss_admin')[SS_SHIPPED_ING] : lang('ss')[$row['shipping_status']];
                $row['action_time'] = TimeHelper::local_date(cfg('time_format'), $row['log_time']);
                $act_list[] = $row;
            }
            $this->assign('action_list', $act_list);

            $this->assign('delivery_order', $delivery_order);
            $this->assign('exist_real_goods', $exist_real_goods);
            $this->assign('goods_list', $goods_list);
            $this->assign('delivery_id', $delivery_id); // 发货单id

            $this->assign('ur_here', lang('delivery_operate').lang('detail'));
            $this->assign('action_link', ['href' => 'order.php?act=delivery_list&'.MainHelper::list_link_postfix(), 'text' => lang('09_delivery_order')]);
            $this->assign('action_act', ($delivery_order['status'] === 2) ? 'delivery_ship' : 'delivery_cancel_ship');

            return $this->display('delivery_info');
        }

        /**
         * 发货单发货确认
         */
        if ($action === 'delivery_ship') {
            $this->admin_priv('delivery_view');

            // 定义当前时间
            define('GMTIME_UTC', TimeHelper::gmtime()); // 获取 UTC 时间戳

            // 取得参数
            $delivery = [];
            $order_id = intval(trim($_REQUEST['order_id']));        // 订单id
            $delivery_id = intval(trim($_REQUEST['delivery_id']));        // 发货单id
            $delivery['invoice_no'] = isset($_REQUEST['invoice_no']) ? trim($_REQUEST['invoice_no']) : '';
            $action_note = isset($_REQUEST['action_note']) ? trim($_REQUEST['action_note']) : '';

            // 根据发货单id查询发货单信息
            if (! empty($delivery_id)) {
                $delivery_order = $this->delivery_order_info($delivery_id);
            } else {
                exit('order does not exist');
            }

            // 查询订单信息
            $order = OrderHelper::order_info($order_id);

            // 检查此单发货商品库存缺货情况
            $virtual_goods = [];
            $delivery_stock_result = DB::table('order_delivery_goods as DG')
                ->join('goods as G', 'DG.goods_id', '=', 'G.goods_id')
                ->join('goods_product as P', 'DG.product_id', '=', 'P.product_id')
                ->select('DG.goods_id', 'DG.is_real', 'DG.product_id', DB::raw('SUM(DG.send_number) AS sums'), DB::raw('IF(DG.product_id > 0, P.product_number, G.goods_number) AS storage'), 'G.goods_name', 'DG.send_number')
                ->where('DG.delivery_id', $delivery_id)
                ->groupBy('DG.product_id')
                ->get()
                ->toArray();
            $delivery_stock_result = array_map(function ($item) {
                return (array) $item;
            }, $delivery_stock_result);

            // 如果商品存在规格就查询规格，如果不存在规格按商品库存查询
            if (! empty($delivery_stock_result)) {
                foreach ($delivery_stock_result as $value) {
                    if (($value['sums'] > $value['storage'] || $value['storage'] <= 0) && ((cfg('use_storage') === '1' && cfg('stock_dec_time') === SDT_SHIP) || (cfg('use_storage') === '0' && $value['is_real'] === 0))) {
                        // 操作失败
                        $links[] = ['text' => lang('order_info'), 'href' => 'order.php?act=delivery_info&delivery_id='.$delivery_id];

                        return $this->sys_msg(sprintf(lang('act_good_vacancy'), $value['goods_name']), 1, $links);
                        break;
                    }

                    // 虚拟商品列表 virtual_card
                    if ($value['is_real'] === 0) {
                        $virtual_goods[] = [
                            'goods_id' => $value['goods_id'],
                            'goods_name' => $value['goods_name'],
                            'num' => $value['send_number'],
                        ];
                    }
                }
            } else {
                $delivery_stock_result = DB::table('order_delivery_goods as DG')
                    ->join('goods as G', 'DG.goods_id', '=', 'G.goods_id')
                    ->select('DG.goods_id', 'DG.is_real', DB::raw('SUM(DG.send_number) AS sums'), 'G.goods_number', 'G.goods_name', 'DG.send_number')
                    ->where('DG.delivery_id', $delivery_id)
                    ->groupBy('DG.goods_id')
                    ->get()
                    ->toArray();
                $delivery_stock_result = array_map(function ($item) {
                    return (array) $item;
                }, $delivery_stock_result);
                foreach ($delivery_stock_result as $value) {
                    if (($value['sums'] > $value['goods_number'] || $value['goods_number'] <= 0) && ((cfg('use_storage') === '1' && cfg('stock_dec_time') === SDT_SHIP) || (cfg('use_storage') === '0' && $value['is_real'] === 0))) {
                        // 操作失败
                        $links[] = ['text' => lang('order_info'), 'href' => 'order.php?act=delivery_info&delivery_id='.$delivery_id];

                        return $this->sys_msg(sprintf(lang('act_good_vacancy'), $value['goods_name']), 1, $links);
                        break;
                    }

                    // 虚拟商品列表 virtual_card
                    if ($value['is_real'] === 0) {
                        $virtual_goods[] = [
                            'goods_id' => $value['goods_id'],
                            'goods_name' => $value['goods_name'],
                            'num' => $value['send_number'],
                        ];
                    }
                }
            }

            // 发货
            // 处理虚拟卡 商品（虚货）
            if (is_array($virtual_goods) && count($virtual_goods) > 0) {
                foreach ($virtual_goods as $virtual_value) {
                    CommonHelper::virtual_card_shipping($virtual_value, $order['order_sn'], $msg, 'split');
                }
            }

            // 如果使用库存，且发货时减库存，则修改库存
            if (cfg('use_storage') === '1' && cfg('stock_dec_time') === SDT_SHIP) {
                foreach ($delivery_stock_result as $value) {
                    // 商品（实货）、超级礼包（实货）
                    if ($value['is_real'] != 0) {
                        // （货品）
                        if (! empty($value['product_id'])) {
                            DB::table('goods_product')
                                ->where('product_id', $value['product_id'])
                                ->decrement('product_number', $value['sums']);
                        }

                        DB::table('goods')
                            ->where('goods_id', $value['goods_id'])
                            ->decrement('goods_number', $value['sums']);
                    }
                }
            }

            // 修改发货单信息
            $invoice_no = str_replace(',', '<br>', $delivery['invoice_no']);
            $invoice_no = trim($invoice_no, '<br>');
            $_delivery['invoice_no'] = $invoice_no;
            $_delivery['status'] = 0; // 0，为已发货
            $query = DB::table('order_delivery_order')
                ->where('delivery_id', $delivery_id)
                ->update($_delivery);
            if (! $query) {
                // 操作失败
                $links[] = ['text' => lang('delivery_sn').lang('detail'), 'href' => 'order.php?act=delivery_info&delivery_id='.$delivery_id];

                return $this->sys_msg(lang('act_false'), 1, $links);
            }

            // 标记订单为已确认 “已发货”
            // 更新发货时间
            $order_finish = $this->get_all_delivery_finish($order_id);
            $shipping_status = ($order_finish === 1) ? SS_SHIPPED : SS_SHIPPED_PART;
            $arr['shipping_status'] = $shipping_status;
            $arr['shipping_time'] = GMTIME_UTC; // 发货时间
            $arr['invoice_no'] = trim($order['invoice_no'].'<br>'.$invoice_no, '<br>');
            OrderHelper::update_order($order_id, $arr);

            // 发货单发货记录log
            CommonHelper::order_action($order['order_sn'], OS_CONFIRMED, $shipping_status, $order['pay_status'], $action_note, null, 1);

            // 如果当前订单已经全部发货
            if ($order_finish) {
                // 如果订单用户不为空，计算积分，并发给用户；发红包
                if ($order['user_id'] > 0) {
                    // 取得用户信息
                    $user = OrderHelper::user_info($order['user_id']);

                    // 计算并发放积分
                    $integral = OrderHelper::integral_to_give($order);

                    CommonHelper::log_account_change($order['user_id'], 0, 0, intval($integral['rank_points']), intval($integral['custom_points']), sprintf(lang('order_gift_integral'), $order['order_sn']));

                    // 发放红包
                    OrderHelper::send_order_bonus($order_id);
                }

                // 发送邮件
                $cfg = cfg('send_ship_email');
                if ($cfg === '1') {
                    $order['invoice_no'] = $invoice_no;
                    $tpl = CommonHelper::get_mail_template('deliver_notice');
                    $this->assign('order', $order);
                    $this->assign('send_time', TimeHelper::local_date(cfg('time_format')));
                    $this->assign('shop_name', cfg('shop_name'));
                    $this->assign('send_date', TimeHelper::local_date(cfg('date_format')));
                    $this->assign('sent_date', TimeHelper::local_date(cfg('date_format')));
                    $this->assign('confirm_url', ecs()->url().'receive.php?id='.$order['order_id'].'&con='.rawurlencode($order['consignee']));
                    $this->assign('send_msg_url', ecs()->url().'user.php?act=message_list&order_id='.$order['order_id']);
                    $content = $this->fetch('str:'.$tpl['template_content']);
                    if (! BaseHelper::send_mail($order['consignee'], $order['email'], $tpl['template_subject'], $content, $tpl['is_html'])) {
                        $msg = lang('send_mail_fail');
                    }
                }

                // 如果需要，发短信
                if (cfg('sms_order_shipped') === '1' && $order['mobile'] != '') {
                    $sms = new \sms;
                    $sms->send($order['mobile'], sprintf(
                        lang('order_shipped_sms'),
                        $order['order_sn'],
                        TimeHelper::local_date(lang('sms_time_format')),
                        cfg('shop_name')
                    ), 0);
                }
            }

            // 清除缓存
            $this->clear_cache_files();

            // 操作成功
            $links[] = ['text' => lang('09_delivery_order'), 'href' => 'order.php?act=delivery_list'];
            $links[] = ['text' => lang('delivery_sn').lang('detail'), 'href' => 'order.php?act=delivery_info&delivery_id='.$delivery_id];

            return $this->sys_msg(lang('act_ok'), 0, $links);
        }

        /**
         * 发货单取消发货
         */
        if ($action === 'delivery_cancel_ship') {
            $this->admin_priv('delivery_view');

            // 取得参数
            $delivery = '';
            $order_id = intval(trim($_REQUEST['order_id']));        // 订单id
            $delivery_id = intval(trim($_REQUEST['delivery_id']));        // 发货单id
            $delivery['invoice_no'] = isset($_REQUEST['invoice_no']) ? trim($_REQUEST['invoice_no']) : '';
            $action_note = isset($_REQUEST['action_note']) ? trim($_REQUEST['action_note']) : '';

            // 根据发货单id查询发货单信息
            if (! empty($delivery_id)) {
                $delivery_order = $this->delivery_order_info($delivery_id);
            } else {
                exit('order does not exist');
            }

            // 查询订单信息
            $order = OrderHelper::order_info($order_id);

            // 取消当前发货单物流单号
            $_delivery['invoice_no'] = '';
            $_delivery['status'] = 2;
            $query = DB::table('order_delivery_order')
                ->where('delivery_id', $delivery_id)
                ->update($_delivery);
            if (! $query) {
                // 操作失败
                $links[] = ['text' => lang('delivery_sn').lang('detail'), 'href' => 'order.php?act=delivery_info&delivery_id='.$delivery_id];

                return $this->sys_msg(lang('act_false'), 1, $links);
            }

            // 修改定单发货单号
            $invoice_no_order = explode('<br>', $order['invoice_no']);
            $invoice_no_delivery = explode('<br>', $delivery_order['invoice_no']);
            foreach ($invoice_no_order as $key => $value) {
                $delivery_key = array_search($value, $invoice_no_delivery);
                if ($delivery_key !== false) {
                    unset($invoice_no_order[$key], $invoice_no_delivery[$delivery_key]);
                    if (count($invoice_no_delivery) === 0) {
                        break;
                    }
                }
            }
            $_order['invoice_no'] = implode('<br>', $invoice_no_order);

            // 更新配送状态
            $order_finish = $this->get_all_delivery_finish($order_id);
            $shipping_status = ($order_finish === -1) ? SS_SHIPPED_PART : SS_SHIPPED_ING;
            $arr['shipping_status'] = $shipping_status;
            if ($shipping_status === SS_SHIPPED_ING) {
                $arr['shipping_time'] = ''; // 发货时间
            }
            $arr['invoice_no'] = $_order['invoice_no'];
            OrderHelper::update_order($order_id, $arr);

            // 发货单取消发货记录log
            CommonHelper::order_action($order['order_sn'], $order['order_status'], $shipping_status, $order['pay_status'], $action_note, null, 1);

            // 如果使用库存，则增加库存
            if (cfg('use_storage') === '1' && cfg('stock_dec_time') === SDT_SHIP) {
                // 检查此单发货商品数量
                $virtual_goods = [];
                $delivery_stock_result = DB::table('order_delivery_goods as DG')
                    ->select('DG.goods_id', 'DG.product_id', 'DG.is_real', DB::raw('SUM(DG.send_number) AS sums'))
                    ->where('DG.delivery_id', $delivery_id)
                    ->groupBy('DG.goods_id')
                    ->get()
                    ->toArray();
                $delivery_stock_result = array_map(function ($item) {
                    return (array) $item;
                }, $delivery_stock_result);
                foreach ($delivery_stock_result as $key => $value) {
                    // 虚拟商品
                    if ($value['is_real'] === 0) {
                        continue;
                    }

                    // （货品）
                    if (! empty($value['product_id'])) {
                        DB::table('goods_product')
                            ->where('product_id', $value['product_id'])
                            ->increment('product_number', $value['sums']);
                    }

                    DB::table('goods')
                        ->where('goods_id', $value['goods_id'])
                        ->increment('goods_number', $value['sums']);
                }
            }

            // 发货单全退回时，退回其它
            if ($order['order_status'] === SS_SHIPPED_ING) {
                // 如果订单用户不为空，计算积分，并退回
                if ($order['user_id'] > 0) {
                    // 取得用户信息
                    $user = OrderHelper::user_info($order['user_id']);

                    // 计算并退回积分
                    $integral = OrderHelper::integral_to_give($order);
                    CommonHelper::log_account_change($order['user_id'], 0, 0, (-1) * intval($integral['rank_points']), (-1) * intval($integral['custom_points']), sprintf(lang('return_order_gift_integral'), $order['order_sn']));

                    // todo 计算并退回红包
                    OrderHelper::return_order_bonus($order_id);
                }
            }

            // 清除缓存
            $this->clear_cache_files();

            // 操作成功
            $links[] = ['text' => lang('delivery_sn').lang('detail'), 'href' => 'order.php?act=delivery_info&delivery_id='.$delivery_id];

            return $this->sys_msg(lang('act_ok'), 0, $links);
        }

        /**
         * 退货单列表
         */
        if ($action === 'back_list') {
            $this->admin_priv('back_view');

            // 查询
            $result = $this->back_list();

            $this->assign('ur_here', lang('10_back_order'));
            $this->assign('os_unconfirmed', OS_UNCONFIRMED);
            $this->assign('cs_await_pay', CS_AWAIT_PAY);
            $this->assign('cs_await_ship', CS_AWAIT_SHIP);
            $this->assign('full_page', 1);

            $this->assign('back_list', $result['back']);
            $this->assign('filter', $result['filter']);
            $this->assign('record_count', $result['record_count']);
            $this->assign('page_count', $result['page_count']);

            return $this->display('back_list');
        }

        /**
         * 搜索、排序、分页
         */
        if ($action === 'back_query') {
            $this->admin_priv('back_view');

            $result = $this->back_list();

            $this->assign('back_list', $result['back']);
            $this->assign('filter', $result['filter']);
            $this->assign('record_count', $result['record_count']);
            $this->assign('page_count', $result['page_count']);

            $sort_flag = MainHelper::sort_flag($result['filter']);
            $this->assign($sort_flag['tag'], $sort_flag['img']);

            return $this->make_json_result($this->fetch('back_list'), '', ['filter' => $result['filter'], 'page_count' => $result['page_count']]);
        }

        /**
         * 退货单详细
         */
        if ($action === 'back_info') {
            $this->admin_priv('back_view');

            $back_id = intval(trim($_REQUEST['back_id']));

            // 根据发货单id查询发货单信息
            if (! empty($back_id)) {
                $back_order = $this->back_order_info($back_id);
            } else {
                exit('order does not exist');
            }

            // 如果管理员属于某个办事处，检查该订单是否也属于这个办事处
            $agency_id = DB::table('admin_user')
                ->where('user_id', Session::get('admin_id'))
                ->value('agency_id');
            if ($agency_id > 0) {
                if ($back_order['agency_id'] != $agency_id) {
                    return $this->sys_msg(lang('priv_error'));
                }

                // 取当前办事处信息
                $agency_name = DB::table('shop_agency')
                    ->where('agency_id', $agency_id)
                    ->value('agency_name');
                $back_order['agency_name'] = $agency_name;
            }

            // 取得用户名
            if ($back_order['user_id'] > 0) {
                $user = OrderHelper::user_info($back_order['user_id']);
                if (! empty($user)) {
                    $back_order['user_name'] = $user['user_name'];
                }
            }

            // 取得区域名
            $order_region = DB::table('order_info as o')
                ->leftJoin('shop_region as c', 'o.country', '=', 'c.region_id')
                ->leftJoin('shop_region as p', 'o.province', '=', 'p.region_id')
                ->leftJoin('shop_region as t', 'o.city', '=', 't.region_id')
                ->leftJoin('shop_region as d', 'o.district', '=', 'd.region_id')
                ->where('o.order_id', $back_order['order_id'])
                ->select(DB::raw("concat(IFNULL(c.region_name, ''), '  ', IFNULL(p.region_name, ''), '  ', IFNULL(t.region_name, ''), '  ', IFNULL(d.region_name, '')) AS region_name"))
                ->first();
            $back_order['region'] = $order_region->region_name ?? '';

            // 是否保价
            $order['insure_yn'] = empty($order['insure_fee']) ? 0 : 1;

            // 取得发货单商品
            $goods_list = DB::table('order_back_goods')
                ->where('back_id', $back_order['back_id'])
                ->get()
                ->toArray();
            $goods_list = array_map(function ($item) {
                return (array) $item;
            }, $goods_list);

            // 是否存在实体商品
            $exist_real_goods = 0;
            if ($goods_list) {
                foreach ($goods_list as $value) {
                    if ($value['is_real']) {
                        $exist_real_goods++;
                    }
                }
            }

            $this->assign('back_order', $back_order);
            $this->assign('exist_real_goods', $exist_real_goods);
            $this->assign('goods_list', $goods_list);
            $this->assign('back_id', $back_id); // 发货单id

            $this->assign('ur_here', lang('back_operate').lang('detail'));
            $this->assign('action_link', ['href' => 'order.php?act=back_list&'.MainHelper::list_link_postfix(), 'text' => lang('10_back_order')]);

            return $this->display('back_info');
        }

        /**
         * 修改订单（处理提交）
         */
        if ($action === 'step_post') {
            $this->admin_priv('order_edit');

            // 取得参数 step
            $step_list = ['user', 'edit_goods', 'add_goods', 'goods', 'consignee', 'shipping', 'payment', 'other', 'money', 'invoice'];
            $step = isset($_REQUEST['step']) && in_array($_REQUEST['step'], $step_list) ? $_REQUEST['step'] : 'user';

            // 取得参数 order_id
            $order_id = isset($_REQUEST['order_id']) ? intval($_REQUEST['order_id']) : 0;
            if ($order_id > 0) {
                $old_order = OrderHelper::order_info($order_id);
            }

            // 取得参数 step_act 添加还是编辑
            $step_act = isset($_REQUEST['step_act']) ? $_REQUEST['step_act'] : 'add';

            // 插入订单信息
            if ($step === 'user') {
                // 取得参数：user_id
                $user_id = ($_POST['anonymous'] === 1) ? 0 : intval($_POST['user']);

                // 插入新订单，状态为无效
                $order = [
                    'user_id' => $user_id,
                    'add_time' => TimeHelper::gmtime(),
                    'order_status' => OS_INVALID,
                    'shipping_status' => SS_UNSHIPPED,
                    'pay_status' => PS_UNPAYED,
                    'from_ad' => 0,
                    'referer' => lang('admin'),
                ];

                do {
                    $order['order_sn'] = OrderHelper::get_order_sn();
                    try {
                        $order_id = DB::table('order_info')->insertGetId($order);
                        break;
                    } catch (\Illuminate\Database\QueryException $e) {
                        if ($e->getCode() != 23000) { // 23000 is SQLSTATE for integrity constraint violation (e.g. duplicate key)
                            return $this->sys_msg($e->getMessage());
                        }
                    }
                } while (true); // 防止订单号重复

                // todo 记录日志
                $this->admin_log($order['order_sn'], 'add', 'order');

                // 插入 pay_log
                DB::table('order_pay')->insert([
                    'order_id' => $order_id,
                    'order_amount' => 0,
                    'order_type' => PAY_ORDER,
                    'is_paid' => 0,
                ]);

                // 下一步
                return response()->redirectTo('order.php?act='.$step_act.'&order_id='.$order_id.'&step=goods');
            } // 编辑商品信息
            elseif ($step === 'edit_goods') {
                foreach ($_POST['rec_id'] as $key => $rec_id) {
                    // 取得参数
                    $goods_price = floatval($_POST['goods_price'][$key]);
                    $goods_number = intval($_POST['goods_number'][$key]);
                    $goods_attr = $_POST['goods_attr'][$key];
                    $product_id = intval($_POST['product_id'][$key]);

                    if ($product_id) {
                        $goods_number_all = DB::table('goods_product')
                            ->where('product_id', $product_id)
                            ->value('product_number');
                    } else {
                        $goods_number_all = DB::table('goods')
                            ->where('goods_id', $_POST['goods_id'][$key])
                            ->value('goods_number');
                    }

                    if ($goods_number_all >= $goods_number) {
                        // 修改
                        DB::table('order_goods')
                            ->where('rec_id', $rec_id)
                            ->limit(1)
                            ->update([
                                'goods_price' => $goods_price,
                                'goods_number' => $goods_number,
                                'goods_attr' => $goods_attr,
                            ]);
                    } else {
                        return $this->sys_msg(lang('goods_num_err'));
                    }
                }

                // 更新商品总金额和订单总金额
                $goods_amount = OrderHelper::order_amount($order_id);
                OrderHelper::update_order($order_id, ['goods_amount' => $goods_amount]);
                $this->update_order_amount($order_id);

                // 更新 pay_log
                $this->update_pay_log($order_id);

                // todo 记录日志
                $sn = $old_order['order_sn'];
                $new_order = OrderHelper::order_info($order_id);
                if ($old_order['total_fee'] != $new_order['total_fee']) {
                    $sn .= ','.sprintf(lang('order_amount_change'), $old_order['total_fee'], $new_order['total_fee']);
                }
                $this->admin_log($sn, 'edit', 'order');

                // 跳回订单商品
                return response()->redirectTo('order.php?act='.$step_act.'&order_id='.$order_id.'&step=goods');
            } // 添加商品
            elseif ($step === 'add_goods') {
                // 取得参数
                $goods_id = intval($_POST['goodslist']);
                $goods_price = $_POST['add_price'] != 'user_input' ? floatval($_POST['add_price']) : floatval($_POST['input_price']);
                $goods_attr = '0';
                for ($i = 0; $i < $_POST['spec_count']; $i++) {
                    if (is_array($_POST['spec_'.$i])) {
                        $temp_array = $_POST['spec_'.$i];
                        $temp_array_count = count($_POST['spec_'.$i]);
                        for ($j = 0; $j < $temp_array_count; $j++) {
                            if ($temp_array[$j] !== null) {
                                $goods_attr .= ','.$temp_array[$j];
                            }
                        }
                    } else {
                        if ($_POST['spec_'.$i] !== null) {
                            $goods_attr .= ','.$_POST['spec_'.$i];
                        }
                    }
                }
                $goods_number = $_POST['add_number'];
                $attr_list = $goods_attr;

                $goods_attr = explode(',', $goods_attr);
                $k = array_search(0, $goods_attr);
                unset($goods_attr[$k]);

                $res = DB::table('goods_attr')
                    ->whereIn('goods_attr_id', explode(',', $attr_list))
                    ->get()
                    ->toArray();
                foreach ($res as $row) {
                    $row = (array) $row;
                    $attr_value[] = $row['attr_value'];
                }

                $attr_value = implode(',', $attr_value);

                $prod = DB::table('goods_product')->where('goods_id', $goods_id)->first();
                $prod = $prod ? (array) $prod : [];

                if (CommonHelper::is_spec($goods_attr) && ! empty($prod)) {
                    $product_info = GoodsHelper::get_products_info($_REQUEST['goodslist'], $goods_attr);
                }

                // 商品存在规格 是货品 检查该货品库存
                if (CommonHelper::is_spec($goods_attr) && ! empty($prod)) {
                    if (! empty($goods_attr)) {
                        // 取规格的货品库存
                        if ($goods_number > $product_info['product_number']) {
                            $url = 'order.php?act='.$step_act.'&order_id='.$order_id.'&step=goods';

                            echo '<a href="'.$url.'">'.lang('goods_num_err').'</a>';
                            exit;

                            return false;
                        }
                    }
                }

                if (CommonHelper::is_spec($goods_attr) && ! empty($prod)) {
                    // 插入订单商品
                    $goods_info = DB::table('goods')->where('goods_id', $goods_id)->first();
                    $goods_info = $goods_info ? (array) $goods_info : [];

                    DB::table('order_goods')->insert([
                        'order_id' => $order_id,
                        'goods_id' => $goods_id,
                        'goods_name' => $goods_info['goods_name'],
                        'goods_sn' => $goods_info['goods_sn'],
                        'product_id' => $product_info['product_id'],
                        'goods_number' => $goods_number,
                        'market_price' => $goods_info['market_price'],
                        'goods_price' => $goods_price,
                        'goods_attr' => $attr_value,
                        'is_real' => $goods_info['is_real'],
                        'extension_code' => $goods_info['extension_code'],
                        'parent_id' => 0,
                        'is_gift' => 0,
                        'goods_attr_id' => implode(',', $goods_attr),
                    ]);
                } else {
                    $goods_info = DB::table('goods')->where('goods_id', $goods_id)->first();
                    $goods_info = $goods_info ? (array) $goods_info : [];

                    DB::table('order_goods')->insert([
                        'order_id' => $order_id,
                        'goods_id' => $goods_id,
                        'goods_name' => $goods_info['goods_name'],
                        'goods_sn' => $goods_info['goods_sn'],
                        'goods_number' => $goods_number,
                        'market_price' => $goods_info['market_price'],
                        'goods_price' => $goods_price,
                        'goods_attr' => $attr_value,
                        'is_real' => $goods_info['is_real'],
                        'extension_code' => $goods_info['extension_code'],
                        'parent_id' => 0,
                        'is_gift' => 0,
                    ]);
                }

                // 如果使用库存，且下订单时减库存，则修改库存
                if (cfg('use_storage') === '1' && cfg('stock_dec_time') === SDT_PLACE) {
                    // （货品）
                    if (! empty($product_info['product_id'])) {
                        DB::table('goods_product')
                            ->where('product_id', $product_info['product_id'])
                            ->decrement('product_number', $goods_number);
                    }

                    DB::table('goods')
                        ->where('goods_id', $goods_id)
                        ->limit(1)
                        ->decrement('goods_number', $goods_number);
                }

                // 更新商品总金额和订单总金额
                OrderHelper::update_order($order_id, ['goods_amount' => OrderHelper::order_amount($order_id)]);
                $this->update_order_amount($order_id);

                // 更新 pay_log
                $this->update_pay_log($order_id);

                // todo 记录日志
                $sn = $old_order['order_sn'];
                $new_order = OrderHelper::order_info($order_id);
                if ($old_order['total_fee'] != $new_order['total_fee']) {
                    $sn .= ','.sprintf(lang('order_amount_change'), $old_order['total_fee'], $new_order['total_fee']);
                }
                $this->admin_log($sn, 'edit', 'order');

                // 跳回订单商品
                return response()->redirectTo('order.php?act='.$step_act.'&order_id='.$order_id.'&step=goods');
            } // 商品
            elseif ($step === 'goods') {
                // 下一步
                if (isset($_POST['next'])) {
                    return response()->redirectTo('order.php?act='.$step_act.'&order_id='.$order_id.'&step=consignee');
                } // 完成
                elseif (isset($_POST['finish'])) {
                    // 初始化提示信息和链接
                    $msgs = [];
                    $links = [];

                    // 如果已付款，检查金额是否变动，并执行相应操作
                    $order = OrderHelper::order_info($order_id);
                    $this->handle_order_money_change($order, $msgs, $links);

                    // 显示提示信息
                    if (! empty($msgs)) {
                        return $this->sys_msg(implode(chr(13), $msgs), 0, $links);
                    } else {
                        // 跳转到订单详情
                        return response()->redirectTo('order.php?act=info&order_id='.$order_id);
                    }
                }
            } // 保存收货人信息
            elseif ($step === 'consignee') {
                // 保存订单
                $order = $_POST;
                $order['agency_id'] = OrderHelper::get_agency_by_regions([$order['country'], $order['province'], $order['city'], $order['district']]);
                OrderHelper::update_order($order_id, $order);

                // 该订单所属办事处是否变化
                $agency_changed = $old_order['agency_id'] != $order['agency_id'];

                // todo 记录日志
                $sn = $old_order['order_sn'];
                $this->admin_log($sn, 'edit', 'order');

                if (isset($_POST['next'])) {
                    // 下一步
                    if (OrderHelper::exist_real_goods($order_id)) {
                        // 存在实体商品，去配送方式
                        return response()->redirectTo('order.php?act='.$step_act.'&order_id='.$order_id.'&step=shipping');
                    } else {
                        // 不存在实体商品，去支付方式
                        return response()->redirectTo('order.php?act='.$step_act.'&order_id='.$order_id.'&step=payment');
                    }
                } elseif (isset($_POST['finish'])) {
                    // 如果是编辑且存在实体商品，检查收货人地区的改变是否影响原来选的配送
                    if ($step_act === 'edit' && OrderHelper::exist_real_goods($order_id)) {
                        $order = OrderHelper::order_info($order_id);

                        // 取得可用配送方式
                        $region_id_list = [
                            $order['country'],
                            $order['province'],
                            $order['city'],
                            $order['district'],
                        ];
                        $shipping_list = OrderHelper::available_shipping_list($region_id_list);

                        // 判断订单的配送是否在可用配送之内
                        $exist = false;
                        foreach ($shipping_list as $shipping) {
                            if ($shipping['shipping_id'] === $order['shipping_id']) {
                                $exist = true;
                                break;
                            }
                        }

                        // 如果不在可用配送之内，提示用户去修改配送
                        if (! $exist) {
                            // 修改配送为空，配送费和保价费为0
                            OrderHelper::update_order($order_id, ['shipping_id' => 0, 'shipping_name' => '']);
                            $links[] = ['text' => lang('step.shipping'), 'href' => 'order.php?act=edit&order_id='.$order_id.'&step=shipping'];

                            return $this->sys_msg(lang('continue_shipping'), 1, $links);
                        }
                    }

                    // 完成
                    if ($agency_changed) {
                        return response()->redirectTo('order.php?act=list');
                    } else {
                        return response()->redirectTo('order.php?act=info&order_id='.$order_id);
                    }
                }
            } // 保存配送信息
            elseif ($step === 'shipping') {
                // 如果不存在实体商品，退出
                if (! OrderHelper::exist_real_goods($order_id)) {
                    exit('Hacking Attemp');
                }

                // 取得订单信息
                $order_info = OrderHelper::order_info($order_id);
                $region_id_list = [$order_info['country'], $order_info['province'], $order_info['city'], $order_info['district']];

                // 保存订单
                $shipping_id = $_POST['shipping'];
                $shipping = OrderHelper::shipping_area_info($shipping_id, $region_id_list);
                $weight_amount = OrderHelper::order_weight_price($order_id);
                $shipping_fee = OrderHelper::shipping_fee($shipping['shipping_code'], $shipping['configure'], $weight_amount['weight'], $weight_amount['amount'], $weight_amount['number']);
                $order = [
                    'shipping_id' => $shipping_id,
                    'shipping_name' => addslashes($shipping['shipping_name']),
                    'shipping_fee' => $shipping_fee,
                ];

                if (isset($_POST['insure'])) {
                    // 计算保价费
                    $order['insure_fee'] = OrderHelper::shipping_insure_fee($shipping['shipping_code'], OrderHelper::order_amount($order_id), $shipping['insure']);
                } else {
                    $order['insure_fee'] = 0;
                }
                OrderHelper::update_order($order_id, $order);
                $this->update_order_amount($order_id);

                // 更新 pay_log
                $this->update_pay_log($order_id);

                // 清除首页缓存：发货单查询
                $this->clear_cache_files('index');

                // todo 记录日志
                $sn = $old_order['order_sn'];
                $new_order = OrderHelper::order_info($order_id);
                if ($old_order['total_fee'] != $new_order['total_fee']) {
                    $sn .= ','.sprintf(lang('order_amount_change'), $old_order['total_fee'], $new_order['total_fee']);
                }
                $this->admin_log($sn, 'edit', 'order');

                if (isset($_POST['next'])) {
                    // 下一步
                    return response()->redirectTo('order.php?act='.$step_act.'&order_id='.$order_id.'&step=payment');
                } elseif (isset($_POST['finish'])) {
                    // 初始化提示信息和链接
                    $msgs = [];
                    $links = [];

                    // 如果已付款，检查金额是否变动，并执行相应操作
                    $order = OrderHelper::order_info($order_id);
                    $this->handle_order_money_change($order, $msgs, $links);

                    // 如果是编辑且配送不支持货到付款且原支付方式是货到付款
                    if ($step_act === 'edit' && $shipping['support_cod'] === 0) {
                        $payment = OrderHelper::payment_info($order['pay_id']);
                        if ($payment['is_cod'] === 1) {
                            // 修改支付为空
                            OrderHelper::update_order($order_id, ['pay_id' => 0, 'pay_name' => '']);
                            $msgs[] = lang('continue_payment');
                            $links[] = ['text' => lang('step.payment'), 'href' => 'order.php?act='.$step_act.'&order_id='.$order_id.'&step=payment'];
                        }
                    }

                    // 显示提示信息
                    if (! empty($msgs)) {
                        return $this->sys_msg(implode(chr(13), $msgs), 0, $links);
                    } else {
                        // 完成
                        return response()->redirectTo('order.php?act=info&order_id='.$order_id);
                    }
                }
            } // 保存支付信息
            elseif ($step === 'payment') {
                // 取得支付信息
                $pay_id = $_POST['payment'];
                $payment = OrderHelper::payment_info($pay_id);

                // 计算支付费用
                $order_amount = OrderHelper::order_amount($order_id);
                if ($payment['is_cod'] === 1) {
                    $order = OrderHelper::order_info($order_id);
                    $region_id_list = [
                        $order['country'],
                        $order['province'],
                        $order['city'],
                        $order['district'],
                    ];
                    $shipping = OrderHelper::shipping_area_info($order['shipping_id'], $region_id_list);
                    $pay_fee = OrderHelper::pay_fee($pay_id, $order_amount, $shipping['pay_fee']);
                } else {
                    $pay_fee = OrderHelper::pay_fee($pay_id, $order_amount);
                }

                // 保存订单
                $order = [
                    'pay_id' => $pay_id,
                    'pay_name' => addslashes($payment['pay_name']),
                    'pay_fee' => $pay_fee,
                ];
                OrderHelper::update_order($order_id, $order);
                $this->update_order_amount($order_id);

                // 更新 pay_log
                $this->update_pay_log($order_id);

                // todo 记录日志
                $sn = $old_order['order_sn'];
                $new_order = OrderHelper::order_info($order_id);
                if ($old_order['total_fee'] != $new_order['total_fee']) {
                    $sn .= ','.sprintf(lang('order_amount_change'), $old_order['total_fee'], $new_order['total_fee']);
                }
                $this->admin_log($sn, 'edit', 'order');

                if (isset($_POST['next'])) {
                    // 下一步
                    return response()->redirectTo('order.php?act='.$step_act.'&order_id='.$order_id.'&step=other');
                } elseif (isset($_POST['finish'])) {
                    // 初始化提示信息和链接
                    $msgs = [];
                    $links = [];

                    // 如果已付款，检查金额是否变动，并执行相应操作
                    $order = OrderHelper::order_info($order_id);
                    $this->handle_order_money_change($order, $msgs, $links);

                    // 显示提示信息
                    if (! empty($msgs)) {
                        return $this->sys_msg(implode(chr(13), $msgs), 0, $links);
                    } else {
                        // 完成
                        return response()->redirectTo('order.php?act=info&order_id='.$order_id);
                    }
                }
            } elseif ($step === 'other') {
                // 保存订单
                $order = [];
                if (isset($_POST['pack']) && $_POST['pack'] > 0) {
                    $pack = OrderHelper::pack_info($_POST['pack']);
                    $order['pack_id'] = $pack['pack_id'];
                    $order['pack_name'] = addslashes($pack['pack_name']);
                    $order['pack_fee'] = $pack['pack_fee'];
                } else {
                    $order['pack_id'] = 0;
                    $order['pack_name'] = '';
                    $order['pack_fee'] = 0;
                }
                if (isset($_POST['card']) && $_POST['card'] > 0) {
                    $card = OrderHelper::card_info($_POST['card']);
                    $order['card_id'] = $card['card_id'];
                    $order['card_name'] = addslashes($card['card_name']);
                    $order['card_fee'] = $card['card_fee'];
                    $order['card_message'] = $_POST['card_message'];
                } else {
                    $order['card_id'] = 0;
                    $order['card_name'] = '';
                    $order['card_fee'] = 0;
                    $order['card_message'] = '';
                }
                $order['inv_type'] = $_POST['inv_type'];
                $order['inv_payee'] = $_POST['inv_payee'];
                $order['inv_content'] = $_POST['inv_content'];
                $order['how_oos'] = $_POST['how_oos'];
                $order['postscript'] = $_POST['postscript'];
                $order['to_buyer'] = $_POST['to_buyer'];
                OrderHelper::update_order($order_id, $order);
                $this->update_order_amount($order_id);

                // 更新 pay_log
                $this->update_pay_log($order_id);

                // todo 记录日志
                $sn = $old_order['order_sn'];
                $this->admin_log($sn, 'edit', 'order');

                if (isset($_POST['next'])) {
                    // 下一步
                    return response()->redirectTo('order.php?act='.$step_act.'&order_id='.$order_id.'&step=money');
                } elseif (isset($_POST['finish'])) {
                    // 完成
                    return response()->redirectTo('order.php?act=info&order_id='.$order_id);
                }
            } elseif ($step === 'money') {
                // 取得订单信息
                $old_order = OrderHelper::order_info($order_id);
                if ($old_order['user_id'] > 0) {
                    // 取得用户信息
                    $user = OrderHelper::user_info($old_order['user_id']);
                }

                // 保存信息
                $order['goods_amount'] = $old_order['goods_amount'];
                $order['discount'] = isset($_POST['discount']) && floatval($_POST['discount']) >= 0 ? round(floatval($_POST['discount']), 2) : 0;
                $order['tax'] = round(floatval($_POST['tax']), 2);
                $order['shipping_fee'] = isset($_POST['shipping_fee']) && floatval($_POST['shipping_fee']) >= 0 ? round(floatval($_POST['shipping_fee']), 2) : 0;
                $order['insure_fee'] = isset($_POST['insure_fee']) && floatval($_POST['insure_fee']) >= 0 ? round(floatval($_POST['insure_fee']), 2) : 0;
                $order['pay_fee'] = floatval($_POST['pay_fee']) >= 0 ? round(floatval($_POST['pay_fee']), 2) : 0;
                $order['pack_fee'] = isset($_POST['pack_fee']) && floatval($_POST['pack_fee']) >= 0 ? round(floatval($_POST['pack_fee']), 2) : 0;
                $order['card_fee'] = isset($_POST['card_fee']) && floatval($_POST['card_fee']) >= 0 ? round(floatval($_POST['card_fee']), 2) : 0;

                $order['money_paid'] = $old_order['money_paid'];
                $order['surplus'] = 0;
                // $order['integral']      = 0;
                $order['integral'] = intval($_POST['integral']) >= 0 ? intval($_POST['integral']) : 0;
                $order['integral_money'] = 0;
                $order['bonus_id'] = 0;
                $order['bonus'] = 0;

                // 计算待付款金额
                $order['order_amount'] = $order['goods_amount'] - $order['discount']
                    + $order['tax']
                    + $order['shipping_fee']
                    + $order['insure_fee']
                    + $order['pay_fee']
                    + $order['pack_fee']
                    + $order['card_fee']
                    - $order['money_paid'];
                if ($order['order_amount'] > 0) {
                    if ($old_order['user_id'] > 0) {
                        // 如果选择了红包，先使用红包支付
                        if ($_POST['bonus_id'] > 0) {
                            // todo 检查红包是否可用
                            $order['bonus_id'] = $_POST['bonus_id'];
                            $bonus = OrderHelper::bonus_info($_POST['bonus_id']);
                            $order['bonus'] = $bonus['type_money'];

                            $order['order_amount'] -= $order['bonus'];
                        }

                        // 使用红包之后待付款金额仍大于0
                        if ($order['order_amount'] > 0) {
                            if ($old_order['extension_code'] != 'exchange_goods') {
                                // 如果设置了积分，再使用积分支付
                                if (isset($_POST['integral']) && intval($_POST['integral']) > 0) {
                                    // 检查积分是否足够
                                    $order['integral'] = intval($_POST['integral']);
                                    $order['integral_money'] = OrderHelper::value_of_integral(intval($_POST['integral']));
                                    if ($order['integral'] > $old_order['integral'] + $user['pay_points']) {
                                        return $this->sys_msg(lang('pay_points_not_enough'));
                                    }

                                    $order['order_amount'] -= $order['integral_money'];
                                }
                            } else {
                                if (intval($_POST['integral']) > $user['pay_points'] + $old_order['integral']) {
                                    return $this->sys_msg(lang('pay_points_not_enough'));
                                }
                            }
                            if ($order['order_amount'] > 0) {
                                // 如果设置了余额，再使用余额支付
                                if (isset($_POST['surplus']) && floatval($_POST['surplus']) >= 0) {
                                    // 检查余额是否足够
                                    $order['surplus'] = round(floatval($_POST['surplus']), 2);
                                    if ($order['surplus'] > $old_order['surplus'] + $user['user_money'] + $user['credit_line']) {
                                        return $this->sys_msg(lang('user_money_not_enough'));
                                    }

                                    // 如果红包和积分和余额足以支付，把待付款金额改为0，退回部分积分余额
                                    $order['order_amount'] -= $order['surplus'];
                                    if ($order['order_amount'] < 0) {
                                        $order['surplus'] += $order['order_amount'];
                                        $order['order_amount'] = 0;
                                    }
                                }
                            } else {
                                // 如果红包和积分足以支付，把待付款金额改为0，退回部分积分
                                $order['integral_money'] += $order['order_amount'];
                                $order['integral'] = OrderHelper::integral_of_value($order['integral_money']);
                                $order['order_amount'] = 0;
                            }
                        } else {
                            // 如果红包足以支付，把待付款金额设为0
                            $order['order_amount'] = 0;
                        }
                    }
                }

                OrderHelper::update_order($order_id, $order);

                // 更新 pay_log
                $this->update_pay_log($order_id);

                // todo 记录日志
                $sn = $old_order['order_sn'];
                $new_order = OrderHelper::order_info($order_id);
                if ($old_order['total_fee'] != $new_order['total_fee']) {
                    $sn .= ','.sprintf(lang('order_amount_change'), $old_order['total_fee'], $new_order['total_fee']);
                }
                $this->admin_log($sn, 'edit', 'order');

                // 如果余额、积分、红包有变化，做相应更新
                if ($old_order['user_id'] > 0) {
                    $user_money_change = $old_order['surplus'] - $order['surplus'];
                    if ($user_money_change != 0) {
                        CommonHelper::log_account_change($user['user_id'], $user_money_change, 0, 0, 0, sprintf(lang('change_use_surplus'), $old_order['order_sn']));
                    }

                    $pay_points_change = $old_order['integral'] - $order['integral'];
                    if ($pay_points_change != 0) {
                        CommonHelper::log_account_change($user['user_id'], 0, 0, 0, $pay_points_change, sprintf(lang('change_use_integral'), $old_order['order_sn']));
                    }

                    if ($old_order['bonus_id'] != $order['bonus_id']) {
                        if ($old_order['bonus_id'] > 0) {
                            DB::table('user_bonus')
                                ->where('bonus_id', $old_order['bonus_id'])
                                ->limit(1)
                                ->update(['used_time' => 0, 'order_id' => 0]);
                        }

                        if ($order['bonus_id'] > 0) {
                            DB::table('user_bonus')
                                ->where('bonus_id', $order['bonus_id'])
                                ->limit(1)
                                ->update(['used_time' => TimeHelper::gmtime(), 'order_id' => $order_id]);
                        }
                    }
                }

                if (isset($_POST['finish'])) {
                    // 完成
                    if ($step_act === 'add') {
                        // 订单改为已确认，（已付款）
                        $arr['order_status'] = OS_CONFIRMED;
                        $arr['confirm_time'] = TimeHelper::gmtime();
                        if ($order['order_amount'] <= 0) {
                            $arr['pay_status'] = PS_PAYED;
                            $arr['pay_time'] = TimeHelper::gmtime();
                        }
                        OrderHelper::update_order($order_id, $arr);
                    }

                    // 初始化提示信息和链接
                    $msgs = [];
                    $links = [];

                    // 如果已付款，检查金额是否变动，并执行相应操作
                    $order = OrderHelper::order_info($order_id);
                    $this->handle_order_money_change($order, $msgs, $links);

                    // 显示提示信息
                    if (! empty($msgs)) {
                        return $this->sys_msg(implode(chr(13), $msgs), 0, $links);
                    } else {
                        return response()->redirectTo('order.php?act=info&order_id='.$order_id);
                    }
                }
            } // 保存发货后的配送方式和发货单号
            elseif ($step === 'invoice') {
                // 如果不存在实体商品，退出
                if (! OrderHelper::exist_real_goods($order_id)) {
                    exit('Hacking Attemp');
                }

                // 保存订单
                $shipping_id = $_POST['shipping'];
                $shipping = OrderHelper::shipping_info($shipping_id);
                $invoice_no = trim($_POST['invoice_no']);
                $invoice_no = str_replace(',', '<br>', $invoice_no);
                $order = [
                    'shipping_id' => $shipping_id,
                    'shipping_name' => addslashes($shipping['shipping_name']),
                    'invoice_no' => $invoice_no,
                ];
                OrderHelper::update_order($order_id, $order);

                // todo 记录日志
                $sn = $old_order['order_sn'];
                $this->admin_log($sn, 'edit', 'order');

                if (isset($_POST['finish'])) {
                    return response()->redirectTo('order.php?act=info&order_id='.$order_id);
                }
            }
        }

        /**
         * 修改订单（载入页面）
         */
        if ($action === 'add' || $action === 'edit') {
            $this->admin_priv('order_edit');

            // 取得参数 order_id
            $order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
            $this->assign('order_id', $order_id);

            // 取得参数 step
            $step_list = ['user', 'goods', 'consignee', 'shipping', 'payment', 'other', 'money'];
            $step = isset($_GET['step']) && in_array($_GET['step'], $step_list) ? $_GET['step'] : 'user';
            $this->assign('step', $step);

            // 取得参数 act
            $act = $_GET['act'];
            $this->assign('ur_here', lang('add_order'));
            $this->assign('step_act', $act);

            // 取得订单信息
            if ($order_id > 0) {
                $order = OrderHelper::order_info($order_id);

                // 发货单格式化
                $order['invoice_no'] = str_replace('<br>', ',', $order['invoice_no']);

                // 如果已发货，就不能修改订单了（配送方式和发货单号除外）
                if ($order['shipping_status'] === SS_SHIPPED || $order['shipping_status'] === SS_RECEIVED) {
                    if ($step != 'shipping') {
                        return $this->sys_msg(lang('cannot_edit_order_shipped'));
                    } else {
                        $step = 'invoice';
                        $this->assign('step', $step);
                    }
                }

                $this->assign('order', $order);
            } else {
                if ($act != 'add' || $step != 'user') {
                    exit('invalid params');
                }
            }

            // 选择会员
            if ($step === 'user') {
                // 无操作
            } // 增删改商品
            elseif ($step === 'goods') {
                // 取得订单商品
                $goods_list = OrderHelper::order_goods($order_id);
                if (! empty($goods_list)) {
                    foreach ($goods_list as $key => $goods) {
                        // 计算属性数
                        $attr = $goods['goods_attr'];
                        if ($attr === '') {
                            $goods_list[$key]['rows'] = 1;
                        } else {
                            $goods_list[$key]['rows'] = count(explode(chr(13), $attr));
                        }
                    }
                }

                $this->assign('goods_list', $goods_list);

                // 取得商品总金额
                $this->assign('goods_amount', OrderHelper::order_amount($order_id));
            } // 设置收货人
            elseif ($step === 'consignee') {
                // 查询是否存在实体商品
                $exist_real_goods = OrderHelper::exist_real_goods($order_id);
                $this->assign('exist_real_goods', $exist_real_goods);

                // 取得收货地址列表
                if ($order['user_id'] > 0) {
                    $this->assign('address_list', OrderHelper::address_list($order['user_id']));

                    $address_id = isset($_REQUEST['address_id']) ? intval($_REQUEST['address_id']) : 0;
                    if ($address_id > 0) {
                        $address = OrderHelper::address_info($address_id);
                        if ($address) {
                            $order['consignee'] = $address['consignee'];
                            $order['country'] = $address['country'];
                            $order['province'] = $address['province'];
                            $order['city'] = $address['city'];
                            $order['district'] = $address['district'];
                            $order['email'] = $address['email'];
                            $order['address'] = $address['address'];
                            $order['zipcode'] = $address['zipcode'];
                            $order['tel'] = $address['tel'];
                            $order['mobile'] = $address['mobile'];
                            $order['sign_building'] = $address['sign_building'];
                            $order['best_time'] = $address['best_time'];
                            $this->assign('order', $order);
                        }
                    }
                }

                if ($exist_real_goods) {
                    // 取得国家
                    $this->assign('country_list', CommonHelper::get_regions());
                    if ($order['country'] > 0) {
                        // 取得省份
                        $this->assign('province_list', CommonHelper::get_regions(1, $order['country']));
                        if ($order['province'] > 0) {
                            // 取得城市
                            $this->assign('city_list', CommonHelper::get_regions(2, $order['province']));
                            if ($order['city'] > 0) {
                                // 取得区域
                                $this->assign('district_list', CommonHelper::get_regions(3, $order['city']));
                            }
                        }
                    }
                }
            } // 选择配送方式
            elseif ($step === 'shipping') {
                // 如果不存在实体商品
                if (! OrderHelper::exist_real_goods($order_id)) {
                    exit('Hacking Attemp');
                }

                // 取得可用的配送方式列表
                $region_id_list = [
                    $order['country'],
                    $order['province'],
                    $order['city'],
                    $order['district'],
                ];
                $shipping_list = OrderHelper::available_shipping_list($region_id_list);

                // 取得配送费用
                $total = OrderHelper::order_weight_price($order_id);
                foreach ($shipping_list as $key => $shipping) {
                    $shipping_fee = OrderHelper::shipping_fee(
                        $shipping['shipping_code'],
                        unserialize($shipping['configure']),
                        $total['weight'],
                        $total['amount'],
                        $total['number']
                    );
                    $shipping_list[$key]['shipping_fee'] = $shipping_fee;
                    $shipping_list[$key]['format_shipping_fee'] = CommonHelper::price_format($shipping_fee);
                    $shipping_list[$key]['free_money'] = CommonHelper::price_format($shipping['configure']['free_money']);
                }
                $this->assign('shipping_list', $shipping_list);
            } // 选择支付方式
            elseif ($step === 'payment') {
                // 取得可用的支付方式列表
                if (OrderHelper::exist_real_goods($order_id)) {
                    // 存在实体商品
                    $region_id_list = [
                        $order['country'],
                        $order['province'],
                        $order['city'],
                        $order['district'],
                    ];
                    $shipping_area = OrderHelper::shipping_area_info($order['shipping_id'], $region_id_list);
                    $pay_fee = ($shipping_area['support_cod'] === 1) ? $shipping_area['pay_fee'] : 0;

                    $payment_list = OrderHelper::available_payment_list($shipping_area['support_cod'], $pay_fee);
                } else {
                    // 不存在实体商品
                    $payment_list = OrderHelper::available_payment_list(false);
                }

                // 过滤掉使用余额支付
                foreach ($payment_list as $key => $payment) {
                    if ($payment['pay_code'] === 'balance') {
                        unset($payment_list[$key]);
                    }
                }
                $this->assign('payment_list', $payment_list);
            } // 选择包装、贺卡
            elseif ($step === 'other') {
                // 查询是否存在实体商品
                $exist_real_goods = OrderHelper::exist_real_goods($order_id);
                $this->assign('exist_real_goods', $exist_real_goods);

                if ($exist_real_goods) {
                    // 取得包装列表
                    $this->assign('pack_list', OrderHelper::pack_list());

                    // 取得贺卡列表
                    $this->assign('card_list', OrderHelper::card_list());
                }
            } // 费用
            elseif ($step === 'money') {
                // 查询是否存在实体商品
                $exist_real_goods = OrderHelper::exist_real_goods($order_id);
                $this->assign('exist_real_goods', $exist_real_goods);

                // 取得用户信息
                if ($order['user_id'] > 0) {
                    $user = OrderHelper::user_info($order['user_id']);

                    // 计算可用余额
                    $this->assign('available_user_money', $order['surplus'] + $user['user_money']);

                    // 计算可用积分
                    $this->assign('available_pay_points', $order['integral'] + $user['pay_points']);

                    // 取得用户可用红包
                    $user_bonus = OrderHelper::user_bonus($order['user_id'], $order['goods_amount']);
                    if ($order['bonus_id'] > 0) {
                        $bonus = OrderHelper::bonus_info($order['bonus_id']);
                        $user_bonus[] = $bonus;
                    }
                    $this->assign('available_bonus', $user_bonus);
                }
            } // 发货后修改配送方式和发货单号
            elseif ($step === 'invoice') {
                // 如果不存在实体商品
                if (! OrderHelper::exist_real_goods($order_id)) {
                    exit('Hacking Attemp');
                }

                // 取得可用的配送方式列表
                $region_id_list = [
                    $order['country'],
                    $order['province'],
                    $order['city'],
                    $order['district'],
                ];
                $shipping_list = OrderHelper::available_shipping_list($region_id_list);

                //        // 取得配送费用
                //        $total = OrderHelper::order_weight_price($order_id);
                //        foreach ($shipping_list AS $key => $shipping)
                //        {
                //            $shipping_fee = OrderHelper::shipping_fee($shipping['shipping_code'],
                //                unserialize($shipping['configure']), $total['weight'], $total['amount'], $total['number']);
                //            $shipping_list[$key]['shipping_fee'] = $shipping_fee;
                //            $shipping_list[$key]['format_shipping_fee'] = CommonHelper::price_format($shipping_fee);
                //            $shipping_list[$key]['free_money'] = CommonHelper::price_format($shipping['configure']['free_money']);
                //        }
                $this->assign('shipping_list', $shipping_list);
            }

            return $this->display('order_step');
        }

        /**
         * 处理
         */
        if ($action === 'process') {
            // 取得参数 func
            $func = isset($_GET['func']) ? $_GET['func'] : '';

            // 删除订单商品
            if ($func === 'drop_order_goods') {
                $this->admin_priv('order_edit');

                // 取得参数
                $rec_id = intval($_GET['rec_id']);
                $step_act = $_GET['step_act'];
                $order_id = intval($_GET['order_id']);

                // 如果使用库存，且下订单时减库存，则修改库存
                if (cfg('use_storage') === '1' && cfg('stock_dec_time') === SDT_PLACE) {
                    $goods = DB::table('order_goods')
                        ->select('goods_id', 'goods_number')
                        ->where('rec_id', $rec_id)
                        ->first();
                    $goods = $goods ? (array) $goods : [];

                    if (! empty($goods)) {
                        DB::table('goods')
                            ->where('goods_id', $goods['goods_id'])
                            ->limit(1)
                            ->increment('goods_number', $goods['goods_number']);
                    }
                }

                // 删除
                DB::table('order_goods')
                    ->where('rec_id', $rec_id)
                    ->limit(1)
                    ->delete();

                // 更新商品总金额和订单总金额
                OrderHelper::update_order($order_id, ['goods_amount' => OrderHelper::order_amount($order_id)]);
                $this->update_order_amount($order_id);

                // 跳回订单商品
                return response()->redirectTo('order.php?act='.$step_act.'&order_id='.$order_id.'&step=goods');
            } // 取消刚添加或编辑的订单
            elseif ($func === 'cancel_order') {
                $step_act = $_GET['step_act'];
                $order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
                if ($step_act === 'add') {
                    // 如果是添加，删除订单，返回订单列表
                    if ($order_id > 0) {
                        DB::table('order_info')
                            ->where('order_id', $order_id)
                            ->limit(1)
                            ->delete();
                    }

                    return response()->redirectTo('order.php?act=list');
                } else {
                    // 如果是编辑，返回订单信息
                    return response()->redirectTo('order.php?act=info&order_id='.$order_id);
                }
            } // 编辑订单时由于订单已付款且金额减少而退款
            elseif ($func === 'refund') {
                // 处理退款
                $order_id = $_REQUEST['order_id'];
                $refund_type = $_REQUEST['refund'];
                $refund_note = $_REQUEST['refund_note'];
                $refund_amount = $_REQUEST['refund_amount'];
                $order = OrderHelper::order_info($order_id);
                OrderHelper::order_refund($order, $refund_type, $refund_note, $refund_amount);

                // 修改应付款金额为0，已付款金额减少 $refund_amount
                OrderHelper::update_order($order_id, ['order_amount' => 0, 'money_paid' => $order['money_paid'] - $refund_amount]);

                // 返回订单详情
                return response()->redirectTo('order.php?act=info&order_id='.$order_id);
            } // 载入退款页面
            elseif ($func === 'load_refund') {
                $refund_amount = floatval($_REQUEST['refund_amount']);
                $this->assign('refund_amount', $refund_amount);
                $this->assign('formated_refund_amount', CommonHelper::price_format($refund_amount));

                $anonymous = $_REQUEST['anonymous'];
                $this->assign('anonymous', $anonymous); // 是否匿名

                $order_id = intval($_REQUEST['order_id']);
                $this->assign('order_id', $order_id); // 订单id

                $this->assign('ur_here', lang('refund'));

                return $this->display('order_refund');
            } else {
                exit('invalid params');
            }
        }

        /**
         * 合并订单
         */
        if ($action === 'merge') {
            $this->admin_priv('order_os_edit');

            // 取得满足条件的订单
            $order_list = DB::table('order_info as o')
                ->leftJoin('user as u', 'o.user_id', '=', 'u.user_id')
                ->select('o.order_sn', 'u.user_name')
                ->where('o.user_id', '>', 0)
                ->where('o.extension_code', '')
                ->whereRaw(ltrim(order_query_sql('unprocessed'), ' AND '))
                ->get()
                ->toArray();
            $order_list = array_map(function ($item) {
                return (array) $item;
            }, $order_list);
            $this->assign('order_list', $order_list);

            $this->assign('ur_here', lang('04_merge_order'));
            $this->assign('action_link', ['href' => 'order.php?act=list', 'text' => lang('02_order_list')]);

            return $this->display('merge_order');
        }

        /**
         * 订单打印模板（载入页面）
         */
        if ($action === 'templates') {
            $this->admin_priv('order_os_edit');

            // 读入订单打印模板文件
            $file_path = ROOT_PATH.DATA_DIR.'/order_print.html';
            $file_content = file_get_contents($file_path);

            // include_once ROOT_PATH.'includes/fckeditor/fckeditor.php';

            // 编辑器
            $editor = new \FCKeditor('FCKeditor1');
            $editor->BasePath = '../includes/fckeditor/';
            $editor->ToolbarSet = 'Normal';
            $editor->Width = '95%';
            $editor->Height = '500';
            $editor->Value = $file_content;

            $fckeditor = $editor->CreateHtml();
            $this->assign('fckeditor', $fckeditor);

            $this->assign('ur_here', lang('edit_order_templates'));
            $this->assign('action_link', ['href' => 'order.php?act=list', 'text' => lang('02_order_list')]);
            $this->assign('act', 'edit_templates');

            return $this->display('order_templates');
        }
        /**
         * 订单打印模板（提交修改）
         */
        if ($action === 'edit_templates') {
            // 更新模板文件的内容
            $file_name = @fopen('../'.DATA_DIR.'/order_print.html', 'w+');
            @fwrite($file_name, stripslashes($_POST['FCKeditor1']));
            @fclose($file_name);

            // 提示信息
            $link[] = ['text' => lang('back_list'), 'href' => 'order.php?act=list'];

            return $this->sys_msg(lang('edit_template_success'), 0, $link);
        }

        /**
         * 操作订单状态（载入页面）
         */
        if ($action === 'operate') {
            $order_id = '';

            $this->admin_priv('order_os_edit');

            // 取得订单id（可能是多个，多个sn）和操作备注（可能没有）
            if (isset($_REQUEST['order_id'])) {
                $order_id = $_REQUEST['order_id'];
            }
            $batch = isset($_REQUEST['batch']); // 是否批处理
            $action_note = isset($_REQUEST['action_note']) ? trim($_REQUEST['action_note']) : '';

            // 确认
            if (isset($_POST['confirm'])) {
                $require_note = false;
                $action = lang('op_confirm');
                $operation = 'confirm';
            } // 付款
            elseif (isset($_POST['pay'])) {
                $this->admin_priv('order_ps_edit');
                $require_note = cfg('order_pay_note') === 1;
                $action = lang('op_pay');
                $operation = 'pay';
            } // 未付款
            elseif (isset($_POST['unpay'])) {
                $this->admin_priv('order_ps_edit');

                $require_note = cfg('order_unpay_note') === 1;
                $order = OrderHelper::order_info($order_id);
                if ($order['money_paid'] > 0) {
                    $show_refund = true;
                }
                $anonymous = $order['user_id'] === 0;
                $action = lang('op_unpay');
                $operation = 'unpay';
            } // 配货
            elseif (isset($_POST['prepare'])) {
                $require_note = false;
                $action = lang('op_prepare');
                $operation = 'prepare';
            } // 分单
            elseif (isset($_POST['ship'])) {
                // 查询：检查权限
                $this->admin_priv('order_ss_edit');

                $order_id = intval(trim($order_id));
                $action_note = trim($action_note);

                // 查询：根据订单id查询订单信息
                if (! empty($order_id)) {
                    $order = OrderHelper::order_info($order_id);
                } else {
                    exit('order does not exist');
                }

                // 查询：根据订单是否完成 检查权限
                if (OrderHelper::order_finished($order)) {
                    $this->admin_priv('order_view_finished');
                } else {
                    $this->admin_priv('order_view');
                }

                // 查询：如果管理员属于某个办事处，检查该订单是否也属于这个办事处
                $agency_id = DB::table('admin_user')
                    ->where('user_id', Session::get('admin_id'))
                    ->value('agency_id');
                if ($agency_id > 0) {
                    if ($order['agency_id'] != $agency_id) {
                        return $this->sys_msg(lang('priv_error'), 0);
                    }
                }

                // 查询：取得用户名
                if ($order['user_id'] > 0) {
                    $user = OrderHelper::user_info($order['user_id']);
                    if (! empty($user)) {
                        $order['user_name'] = $user['user_name'];
                    }
                }

                // 查询：取得区域名
                $order_region = DB::table('order_info as o')
                    ->leftJoin('shop_region as c', 'o.country', '=', 'c.region_id')
                    ->leftJoin('shop_region as p', 'o.province', '=', 'p.region_id')
                    ->leftJoin('shop_region as t', 'o.city', '=', 't.region_id')
                    ->leftJoin('shop_region as d', 'o.district', '=', 'd.region_id')
                    ->where('o.order_id', $order['order_id'])
                    ->select(DB::raw("concat(IFNULL(c.region_name, ''), '  ', IFNULL(p.region_name, ''), '  ', IFNULL(t.region_name, ''), '  ', IFNULL(d.region_name, '')) AS region_name"))
                    ->first();
                $order['region'] = $order_region->region_name ?? '';

                // 查询：其他处理
                $order['order_time'] = TimeHelper::local_date(cfg('time_format'), $order['add_time']);
                $order['invoice_no'] = $order['shipping_status'] === SS_UNSHIPPED || $order['shipping_status'] === SS_PREPARING ? lang('ss')[SS_UNSHIPPED] : $order['invoice_no'];

                // 查询：是否保价
                $order['insure_yn'] = empty($order['insure_fee']) ? 0 : 1;

                // 查询：是否存在实体商品
                $exist_real_goods = OrderHelper::exist_real_goods($order_id);

                // 查询：取得订单商品
                $_goods = $this->get_order_goods(['order_id' => $order['order_id'], 'order_sn' => $order['order_sn']]);

                $attr = $_goods['attr'];
                $goods_list = $_goods['goods_list'];
                unset($_goods);

                // 查询：商品已发货数量 此单可发货数量
                if ($goods_list) {
                    foreach ($goods_list as $key => $goods_value) {
                        if (! $goods_value['goods_id']) {
                            continue;
                        }

                        // 超级礼包
                        if (($goods_value['extension_code'] === 'package_buy') && (count($goods_value['package_goods_list']) > 0)) {
                            $goods_list[$key]['package_goods_list'] = $this->package_goods($goods_value['package_goods_list'], $goods_value['goods_number'], $goods_value['order_id'], $goods_value['extension_code'], $goods_value['goods_id']);

                            foreach ($goods_list[$key]['package_goods_list'] as $pg_key => $pg_value) {
                                $goods_list[$key]['package_goods_list'][$pg_key]['readonly'] = '';
                                // 使用库存 是否缺货
                                if ($pg_value['storage'] <= 0 && cfg('use_storage') === '1' && cfg('stock_dec_time') === SDT_SHIP) {
                                    $goods_list[$key]['package_goods_list'][$pg_key]['send'] = lang('act_good_vacancy');
                                    $goods_list[$key]['package_goods_list'][$pg_key]['readonly'] = 'readonly="readonly"';
                                } // 将已经全部发货的商品设置为只读
                                elseif ($pg_value['send'] <= 0) {
                                    $goods_list[$key]['package_goods_list'][$pg_key]['send'] = lang('act_good_delivery');
                                    $goods_list[$key]['package_goods_list'][$pg_key]['readonly'] = 'readonly="readonly"';
                                }
                            }
                        } else {
                            $goods_list[$key]['sended'] = $goods_value['send_number'];
                            $goods_list[$key]['send'] = $goods_value['goods_number'] - $goods_value['send_number'];

                            $goods_list[$key]['readonly'] = '';
                            // 是否缺货
                            if ($goods_value['storage'] <= 0 && cfg('use_storage') === '1' && cfg('stock_dec_time') === SDT_SHIP) {
                                $goods_list[$key]['send'] = lang('act_good_vacancy');
                                $goods_list[$key]['readonly'] = 'readonly="readonly"';
                            } elseif ($goods_list[$key]['send'] <= 0) {
                                $goods_list[$key]['send'] = lang('act_good_delivery');
                                $goods_list[$key]['readonly'] = 'readonly="readonly"';
                            }
                        }
                    }
                }

                $this->assign('order', $order);
                $this->assign('exist_real_goods', $exist_real_goods);
                $this->assign('goods_attr', $attr);
                $this->assign('goods_list', $goods_list);
                $this->assign('order_id', $order_id); // 订单id
                $this->assign('operation', 'split'); // 订单id
                $this->assign('action_note', $action_note); // 发货操作信息

                $suppliers_list = $this->get_suppliers_list();
                $suppliers_list_count = count($suppliers_list);
                $this->assign('suppliers_name', MainHelper::suppliers_list_name()); // 取供货商名
                $this->assign('suppliers_list', ($suppliers_list_count === 0 ? 0 : $suppliers_list)); // 取供货商列表

                $this->assign('ur_here', lang('order_operate').lang('op_split'));

                return $this->display('order_delivery_info');
            } // 未发货
            elseif (isset($_POST['unship'])) {
                $this->admin_priv('order_ss_edit');

                $require_note = cfg('order_unship_note') === 1;
                $action = lang('op_unship');
                $operation = 'unship';
            } // 收货确认
            elseif (isset($_POST['receive'])) {
                $require_note = cfg('order_receive_note') === 1;
                $action = lang('op_receive');
                $operation = 'receive';
            } // 取消
            elseif (isset($_POST['cancel'])) {
                $require_note = cfg('order_cancel_note') === 1;
                $action = lang('op_cancel');
                $operation = 'cancel';
                $show_cancel_note = true;
                $order = OrderHelper::order_info($order_id);
                if ($order['money_paid'] > 0) {
                    $show_refund = true;
                }
                $anonymous = $order['user_id'] === 0;
            } // 无效
            elseif (isset($_POST['invalid'])) {
                $require_note = cfg('order_invalid_note') === 1;
                $action = lang('op_invalid');
                $operation = 'invalid';
            } // 售后
            elseif (isset($_POST['after_service'])) {
                $require_note = true;
                $action = lang('op_after_service');
                $operation = 'after_service';
            } // 退货
            elseif (isset($_POST['return'])) {
                $require_note = cfg('order_return_note') === 1;
                $order = OrderHelper::order_info($order_id);
                if ($order['money_paid'] > 0) {
                    $show_refund = true;
                }
                $anonymous = $order['user_id'] === 0;
                $action = lang('op_return');
                $operation = 'return';
            } // 指派
            elseif (isset($_POST['assign'])) {
                // 取得参数
                $new_agency_id = isset($_POST['agency_id']) ? intval($_POST['agency_id']) : 0;
                if ($new_agency_id === 0) {
                    return $this->sys_msg(lang('js_languages.pls_select_agency'));
                }

                // 查询订单信息
                $order = OrderHelper::order_info($order_id);

                // 如果管理员属于某个办事处，检查该订单是否也属于这个办事处
                $admin_agency_id = DB::table('admin_user')
                    ->where('user_id', Session::get('admin_id'))
                    ->value('agency_id');
                if ($admin_agency_id > 0) {
                    if ($order['agency_id'] != $admin_agency_id) {
                        return $this->sys_msg(lang('priv_error'));
                    }
                }

                // 修改订单相关所属的办事处
                if ($new_agency_id != $order['agency_id']) {
                    $query_array = [
                        'order_info', // 更改订单表的供货商ID
                        'delivery_order', // 更改订单的发货单供货商ID
                        'back_order', // 更改订单的退货单供货商ID
                    ];
                    foreach ($query_array as $value) {
                        DB::table($value)
                            ->where('order_id', $order_id)
                            ->update(['agency_id' => $new_agency_id]);
                    }
                }

                // 操作成功
                $links[] = ['href' => 'order.php?act=list&'.MainHelper::list_link_postfix(), 'text' => lang('02_order_list')];

                return $this->sys_msg(lang('act_ok'), 0, $links);
            } // 订单删除
            elseif (isset($_POST['remove'])) {
                $require_note = false;
                $operation = 'remove';
                if (! $batch) {
                    // 检查能否操作
                    $order = OrderHelper::order_info($order_id);
                    $operable_list = $this->operable_list($order);
                    if (! isset($operable_list['remove'])) {
                        exit('Hacking attempt');
                    }

                    // 删除订单
                    DB::table('order_info')->where('order_id', $order_id)->delete();
                    DB::table('order_goods')->where('order_id', $order_id)->delete();
                    DB::table('order_action')->where('order_id', $order_id)->delete();
                    $action_array = ['delivery', 'back'];
                    $this->del_delivery($order_id, $action_array);

                    // todo 记录日志
                    $this->admin_log($order['order_sn'], 'remove', 'order');

                    // 返回
                    return $this->sys_msg(lang('order_removed'), 0, [['href' => 'order.php?act=list&'.MainHelper::list_link_postfix(), 'text' => lang('return_list')]]);
                }
            } // 发货单删除
            elseif (isset($_REQUEST['remove_invoice'])) {
                // 删除发货单
                $delivery_id = $_REQUEST['delivery_id'];
                $delivery_id = is_array($delivery_id) ? $delivery_id : [$delivery_id];

                foreach ($delivery_id as $value_is) {
                    $value_is = intval(trim($value_is));

                    // 查询：发货单信息
                    $delivery_order = $this->delivery_order_info($value_is);

                    // 如果status不是退货
                    if ($delivery_order['status'] != 1) {
                        // 处理退货
                        $this->delivery_return_goods($value_is, $delivery_order);
                    }

                    // 如果status是已发货并且发货单号不为空
                    if ($delivery_order['status'] === 0 && $delivery_order['invoice_no'] != '') {
                        // 更新：删除订单中的发货单号
                        $this->del_order_invoice_no($delivery_order['order_id'], $delivery_order['invoice_no']);
                    }

                    // 更新：删除发货单
                    DB::table('order_delivery_order')->where('delivery_id', $value_is)->delete();
                }

                // 返回
                return $this->sys_msg(lang('tips_delivery_del'), 0, [['href' => 'order.php?act=delivery_list', 'text' => lang('return_list')]]);
            } // 退货单删除
            elseif (isset($_REQUEST['remove_back'])) {
                $back_id = $_REQUEST['back_id'];
                // 删除退货单
                if (is_array($back_id)) {
                    foreach ($back_id as $value_is) {
                        DB::table('order_back_order')->where('back_id', $value_is)->delete();
                    }
                } else {
                    DB::table('order_back_order')->where('back_id', $back_id)->delete();
                }

                // 返回
                return $this->sys_msg(lang('tips_back_del'), 0, [['href' => 'order.php?act=back_list', 'text' => lang('return_list')]]);
            } // 批量打印订单
            elseif (isset($_POST['print'])) {
                if (empty($_POST['order_id'])) {
                    return $this->sys_msg(lang('pls_select_order'));
                }

                // 赋值公用信息
                $this->assign('shop_name', cfg('shop_name'));
                $this->assign('shop_url', ecs()->url());
                $this->assign('shop_address', cfg('shop_address'));
                $this->assign('service_phone', cfg('service_phone'));
                $this->assign('print_time', TimeHelper::local_date(cfg('time_format')));
                $this->assign('action_user', Session::get('admin_name'));

                $html = '';
                $order_sn_list = explode(',', $_POST['order_id']);
                foreach ($order_sn_list as $order_sn) {
                    // 取得订单信息
                    $order = OrderHelper::order_info(0, $order_sn);
                    if (empty($order)) {
                        continue;
                    }

                    // 根据订单是否完成检查权限
                    if (OrderHelper::order_finished($order)) {
                        if (! $this->admin_priv('order_view_finished', '', false)) {
                            continue;
                        }
                    } else {
                        if (! $this->admin_priv('order_view', '', false)) {
                            continue;
                        }
                    }

                    // 如果管理员属于某个办事处，检查该订单是否也属于这个办事处
                    $agency_id = DB::table('admin_user')
                        ->where('user_id', Session::get('admin_id'))
                        ->value('agency_id');
                    if ($agency_id > 0) {
                        if ($order['agency_id'] != $agency_id) {
                            continue;
                        }
                    }

                    // 取得用户名
                    if ($order['user_id'] > 0) {
                        $user = OrderHelper::user_info($order['user_id']);
                        if (! empty($user)) {
                            $order['user_name'] = $user['user_name'];
                        }
                    }

                    // 取得区域名
                    $order_region = DB::table('order_info as o')
                        ->leftJoin('shop_region as c', 'o.country', '=', 'c.region_id')
                        ->leftJoin('shop_region as p', 'o.province', '=', 'p.region_id')
                        ->leftJoin('shop_region as t', 'o.city', '=', 't.region_id')
                        ->leftJoin('shop_region as d', 'o.district', '=', 'd.region_id')
                        ->where('o.order_id', $order['order_id'])
                        ->select(DB::raw("concat(IFNULL(c.region_name, ''), '  ', IFNULL(p.region_name, ''), '  ', IFNULL(t.region_name, ''), '  ', IFNULL(d.region_name, '')) AS region_name"))
                        ->first();
                    $order['region'] = $order_region->region_name ?? '';

                    // 其他处理
                    $order['order_time'] = TimeHelper::local_date(cfg('time_format'), $order['add_time']);
                    $order['pay_time'] = $order['pay_time'] > 0 ?
                        TimeHelper::local_date(cfg('time_format'), $order['pay_time']) : lang('ps')[PS_UNPAYED];
                    $order['shipping_time'] = $order['shipping_time'] > 0 ?
                        TimeHelper::local_date(cfg('time_format'), $order['shipping_time']) : lang('ss')[SS_UNSHIPPED];
                    $order['status'] = lang('os')[$order['order_status']].','.lang('ps')[$order['pay_status']].','.lang('ss')[$order['shipping_status']];
                    $order['invoice_no'] = $order['shipping_status'] === SS_UNSHIPPED || $order['shipping_status'] === SS_PREPARING ? lang('ss')[SS_UNSHIPPED] : $order['invoice_no'];

                    // 此订单的发货备注(此订单的最后一条操作记录)
                    $order['invoice_note'] = DB::table('order_action')
                        ->where('order_id', $order['order_id'])
                        ->where('shipping_status', 1)
                        ->orderBy('log_time', 'desc')
                        ->value('action_note');

                    // 参数赋值：订单
                    $this->assign('order', $order);

                    // 取得订单商品
                    $goods_list = [];
                    $goods_attr = [];
                    $res = DB::table('order_goods as o')
                        ->leftJoin('goods as g', 'o.goods_id', '=', 'g.goods_id')
                        ->leftJoin('goods_brand as b', 'g.brand_id', '=', 'b.brand_id')
                        ->select('o.*', 'g.goods_number as storage', 'o.goods_attr', DB::raw("IFNULL(b.brand_name, '') as brand_name"))
                        ->where('o.order_id', $order['order_id'])
                        ->get()
                        ->toArray();
                    foreach ($res as $row) {
                        $row = (array) $row;
                        $row['formated_subtotal'] = CommonHelper::price_format($row['goods_price'] * $row['goods_number']);
                        $row['formated_goods_price'] = CommonHelper::price_format($row['goods_price']);

                        $goods_attr[] = explode(' ', trim($row['goods_attr'])); // 将商品属性拆分为一个数组
                        $goods_list[] = $row;
                    }

                    $attr = [];
                    $arr = [];
                    foreach ($goods_attr as $index => $array_val) {
                        foreach ($array_val as $value) {
                            $arr = explode(':', $value); // 以 : 号将属性拆开
                            $attr[$index][] = @['name' => $arr[0], 'value' => $arr[1]];
                        }
                    }

                    $this->assign('goods_attr', $attr);
                    $this->assign('goods_list', $goods_list);

                    $smarty->template_dir = '../'.DATA_DIR;
                    $html .= $this->fetch('order_print.html').
                        '<div style="PAGE-BREAK-AFTER:always"></div>';
                }

                echo $html;
                exit;
            } // 去发货
            elseif (isset($_POST['to_delivery'])) {
                $url = 'order.php?act=delivery_list&order_sn='.$_REQUEST['order_sn'];

                return response()->redirectTo($url);
            }

            // 直接处理还是跳到详细页面
            if (($require_note && $action_note === '') || isset($show_invoice_no) || isset($show_refund)) {
                $this->assign('require_note', $require_note); // 是否要求填写备注
                $this->assign('action_note', $action_note);   // 备注
                $this->assign('show_cancel_note', isset($show_cancel_note)); // 是否显示取消原因
                $this->assign('show_invoice_no', isset($show_invoice_no)); // 是否显示发货单号
                $this->assign('show_refund', isset($show_refund)); // 是否显示退款
                $this->assign('anonymous', isset($anonymous) ? $anonymous : true); // 是否匿名
                $this->assign('order_id', $order_id); // 订单id
                $this->assign('batch', $batch);   // 是否批处理
                $this->assign('operation', $operation); // 操作

                $this->assign('ur_here', lang('order_operate').$action);

                return $this->display('order_operate');
            } else {
                // 直接处理
                if (! $batch) {
                    // 一个订单
                    return response()->redirectTo('order.php?act=operate_post&order_id='.$order_id.
                        '&operation='.$operation.'&action_note='.urlencode($action_note));
                } else {
                    // 多个订单
                    return response()->redirectTo('order.php?act=batch_operate_post&order_id='.$order_id.
                        '&operation='.$operation.'&action_note='.urlencode($action_note));
                }
            }
        }

        /**
         * 操作订单状态（处理批量提交）
         */
        if ($action === 'batch_operate_post') {
            $this->admin_priv('order_os_edit');

            // 取得参数
            $order_id = $_REQUEST['order_id'];        // 订单id（逗号格开的多个订单id）
            $operation = $_REQUEST['operation'];       // 订单操作
            $action_note = $_REQUEST['action_note'];     // 操作备注

            $order_id_list = explode(',', $order_id);

            // 初始化处理的订单sn
            $sn_list = [];
            $sn_not_list = [];

            // 确认
            if ($operation === 'confirm') {
                foreach ($order_id_list as $id_order) {
                    $order = DB::table('order_info')
                        ->where('order_sn', $id_order)
                        ->where('order_status', OS_UNCONFIRMED)
                        ->first();
                    $order = $order ? (array) $order : [];

                    if ($order) {
                        // 检查能否操作
                        $operable_list = $this->operable_list($order);
                        if (! isset($operable_list[$operation])) {
                            $sn_not_list[] = $id_order;

                            continue;
                        }

                        $order_id = $order['order_id'];

                        // 标记订单为已确认
                        OrderHelper::update_order($order_id, ['order_status' => OS_CONFIRMED, 'confirm_time' => TimeHelper::gmtime()]);
                        $this->update_order_amount($order_id);

                        // 记录log
                        CommonHelper::order_action($order['order_sn'], OS_CONFIRMED, SS_UNSHIPPED, PS_UNPAYED, $action_note);

                        // 发送邮件
                        if (cfg('send_confirm_email') === '1') {
                            $tpl = CommonHelper::get_mail_template('order_confirm');
                            $order['formated_add_time'] = TimeHelper::local_date(cfg('time_format'), $order['add_time']);
                            $this->assign('order', $order);
                            $this->assign('shop_name', cfg('shop_name'));
                            $this->assign('send_date', TimeHelper::local_date(cfg('date_format')));
                            $this->assign('sent_date', TimeHelper::local_date(cfg('date_format')));
                            $content = $this->fetch('str:'.$tpl['template_content']);
                            BaseHelper::send_mail($order['consignee'], $order['email'], $tpl['template_subject'], $content, $tpl['is_html']);
                        }

                        $sn_list[] = $order['order_sn'];
                    } else {
                        $sn_not_list[] = $id_order;
                    }
                }

                $sn_str = lang('confirm_order');
            } // 无效
            elseif ($operation === 'invalid') {
                foreach ($order_id_list as $id_order) {
                    $order = DB::table('order_info')
                        ->where('order_sn', $id_order)
                        ->whereRaw(ltrim(order_query_sql('unpay_unship'), ' AND '))
                        ->first();
                    $order = $order ? (array) $order : [];

                    if ($order) {
                        // 检查能否操作
                        $operable_list = $this->operable_list($order);
                        if (! isset($operable_list[$operation])) {
                            $sn_not_list[] = $id_order;

                            continue;
                        }

                        $order_id = $order['order_id'];

                        // 标记订单为“无效”
                        OrderHelper::update_order($order_id, ['order_status' => OS_INVALID]);

                        // 记录log
                        CommonHelper::order_action($order['order_sn'], OS_INVALID, SS_UNSHIPPED, PS_UNPAYED, $action_note);

                        // 如果使用库存，且下订单时减库存，则增加库存
                        if (cfg('use_storage') === '1' && cfg('stock_dec_time') === SDT_PLACE) {
                            OrderHelper::change_order_goods_storage($order_id, false, SDT_PLACE);
                        }

                        // 发送邮件
                        if (cfg('send_invalid_email') === '1') {
                            $tpl = CommonHelper::get_mail_template('order_invalid');
                            $this->assign('order', $order);
                            $this->assign('shop_name', cfg('shop_name'));
                            $this->assign('send_date', TimeHelper::local_date(cfg('date_format')));
                            $this->assign('sent_date', TimeHelper::local_date(cfg('date_format')));
                            $content = $this->fetch('str:'.$tpl['template_content']);
                            BaseHelper::send_mail($order['consignee'], $order['email'], $tpl['template_subject'], $content, $tpl['is_html']);
                        }

                        // 退还用户余额、积分、红包
                        $this->return_user_surplus_integral_bonus($order);

                        $sn_list[] = $order['order_sn'];
                    } else {
                        $sn_not_list[] = $id_order;
                    }
                }

                $sn_str = lang('invalid_order');
            } elseif ($operation === 'cancel') {
                foreach ($order_id_list as $id_order) {
                    $order = DB::table('order_info')
                        ->where('order_sn', $id_order)
                        ->whereRaw(ltrim(order_query_sql('unpay_unship'), ' AND '))
                        ->first();
                    $order = $order ? (array) $order : [];
                    if ($order) {
                        // 检查能否操作
                        $operable_list = $this->operable_list($order);
                        if (! isset($operable_list[$operation])) {
                            $sn_not_list[] = $id_order;

                            continue;
                        }

                        $order_id = $order['order_id'];

                        // 标记订单为“取消”，记录取消原因
                        $cancel_note = trim($_REQUEST['cancel_note']);
                        OrderHelper::update_order($order_id, ['order_status' => OS_CANCELED, 'to_buyer' => $cancel_note]);

                        // 记录log
                        CommonHelper::order_action($order['order_sn'], OS_CANCELED, $order['shipping_status'], PS_UNPAYED, $action_note);

                        // 如果使用库存，且下订单时减库存，则增加库存
                        if (cfg('use_storage') === '1' && cfg('stock_dec_time') === SDT_PLACE) {
                            OrderHelper::change_order_goods_storage($order_id, false, SDT_PLACE);
                        }

                        // 发送邮件
                        if (cfg('send_cancel_email') === '1') {
                            $tpl = CommonHelper::get_mail_template('order_cancel');
                            $this->assign('order', $order);
                            $this->assign('shop_name', cfg('shop_name'));
                            $this->assign('send_date', TimeHelper::local_date(cfg('date_format')));
                            $this->assign('sent_date', TimeHelper::local_date(cfg('date_format')));
                            $content = $this->fetch('str:'.$tpl['template_content']);
                            BaseHelper::send_mail($order['consignee'], $order['email'], $tpl['template_subject'], $content, $tpl['is_html']);
                        }

                        // 退还用户余额、积分、红包
                        $this->return_user_surplus_integral_bonus($order);

                        $sn_list[] = $order['order_sn'];
                    } else {
                        $sn_not_list[] = $id_order;
                    }
                }

                $sn_str = lang('cancel_order');
            } elseif ($operation === 'remove') {
                foreach ($order_id_list as $id_order) {
                    // 检查能否操作
                    $order = OrderHelper::order_info('', $id_order);
                    $operable_list = $this->operable_list($order);
                    if (! isset($operable_list['remove'])) {
                        $sn_not_list[] = $id_order;

                        continue;
                    }

                    // 删除订单
                    DB::table('order_info')->where('order_id', $order['order_id'])->delete();
                    DB::table('order_goods')->where('order_id', $order['order_id'])->delete();
                    DB::table('order_action')->where('order_id', $order['order_id'])->delete();
                    $action_array = ['delivery', 'back'];
                    $this->del_delivery($order['order_id'], $action_array);

                    // todo 记录日志
                    $this->admin_log($order['order_sn'], 'remove', 'order');

                    $sn_list[] = $order['order_sn'];
                }

                $sn_str = lang('remove_order');
            } else {
                exit('invalid params');
            }

            // 取得备注信息
            //    $action_note = $_REQUEST['action_note'];

            if (empty($sn_not_list)) {
                $sn_list = empty($sn_list) ? '' : lang('updated_order').implode($sn_list, ',');
                $msg = $sn_list;
                $links[] = ['text' => lang('return_list'), 'href' => 'order.php?act=list&'.MainHelper::list_link_postfix()];

                return $this->sys_msg($msg, 0, $links);
            } else {
                $order_list_no_fail = [];
                $res = DB::table('order_info')
                    ->whereIn('order_sn', $sn_not_list)
                    ->get()
                    ->toArray();
                foreach ($res as $row) {
                    $row = (array) $row;
                    $order_list_no_fail[$row['order_id']]['order_id'] = $row['order_id'];
                    $order_list_no_fail[$row['order_id']]['order_sn'] = $row['order_sn'];
                    $order_list_no_fail[$row['order_id']]['order_status'] = $row['order_status'];
                    $order_list_no_fail[$row['order_id']]['shipping_status'] = $row['shipping_status'];
                    $order_list_no_fail[$row['order_id']]['pay_status'] = $row['pay_status'];

                    $order_list_fail = '';
                    foreach ($this->operable_list($row) as $key => $value) {
                        if ($key != $operation) {
                            $order_list_fail .= lang('op_'.$key).',';
                        }
                    }
                    $order_list_no_fail[$row['order_id']]['operable'] = $order_list_fail;
                }

                $this->assign('order_info', $sn_str);
                $this->assign('action_link', ['href' => 'order.php?act=list', 'text' => lang('02_order_list')]);
                $this->assign('order_list', $order_list_no_fail);

                return $this->display('order_operate_info');
            }
        }

        /**
         * 操作订单状态（处理提交）
         */
        if ($action === 'operate_post') {
            $this->admin_priv('order_os_edit');

            // 取得参数
            $order_id = intval(trim($_REQUEST['order_id']));        // 订单id
            $operation = $_REQUEST['operation'];       // 订单操作

            // 查询订单信息
            $order = OrderHelper::order_info($order_id);

            // 检查能否操作
            $operable_list = $this->operable_list($order);
            if (! isset($operable_list[$operation])) {
                exit('Hacking attempt');
            }

            // 取得备注信息
            $action_note = $_REQUEST['action_note'];

            // 初始化提示信息
            $msg = '';

            // 确认
            if ($operation === 'confirm') {
                // 标记订单为已确认
                OrderHelper::update_order($order_id, ['order_status' => OS_CONFIRMED, 'confirm_time' => TimeHelper::gmtime()]);
                $this->update_order_amount($order_id);

                // 记录log
                CommonHelper::order_action($order['order_sn'], OS_CONFIRMED, SS_UNSHIPPED, PS_UNPAYED, $action_note);

                // 如果原来状态不是“未确认”，且使用库存，且下订单时减库存，则减少库存
                if ($order['order_status'] != OS_UNCONFIRMED && cfg('use_storage') === '1' && cfg('stock_dec_time') === SDT_PLACE) {
                    OrderHelper::change_order_goods_storage($order_id, true, SDT_PLACE);
                }

                // 发送邮件
                $cfg = cfg('send_confirm_email');
                if ($cfg === '1') {
                    $tpl = CommonHelper::get_mail_template('order_confirm');
                    $this->assign('order', $order);
                    $this->assign('shop_name', cfg('shop_name'));
                    $this->assign('send_date', TimeHelper::local_date(cfg('date_format')));
                    $this->assign('sent_date', TimeHelper::local_date(cfg('date_format')));
                    $content = $this->fetch('str:'.$tpl['template_content']);
                    if (! BaseHelper::send_mail($order['consignee'], $order['email'], $tpl['template_subject'], $content, $tpl['is_html'])) {
                        $msg = lang('send_mail_fail');
                    }
                }
            } // 付款
            elseif ($operation === 'pay') {
                $this->admin_priv('order_ps_edit');

                // 标记订单为已确认、已付款，更新付款时间和已支付金额，如果是货到付款，同时修改订单为“收货确认”
                if ($order['order_status'] != OS_CONFIRMED) {
                    $arr['order_status'] = OS_CONFIRMED;
                    $arr['confirm_time'] = TimeHelper::gmtime();
                }
                $arr['pay_status'] = PS_PAYED;
                $arr['pay_time'] = TimeHelper::gmtime();
                $arr['money_paid'] = $order['money_paid'] + $order['order_amount'];
                $arr['order_amount'] = 0;
                $payment = OrderHelper::payment_info($order['pay_id']);
                if ($payment['is_cod']) {
                    $arr['shipping_status'] = SS_RECEIVED;
                    $order['shipping_status'] = SS_RECEIVED;
                }
                OrderHelper::update_order($order_id, $arr);

                // 记录log
                CommonHelper::order_action($order['order_sn'], OS_CONFIRMED, $order['shipping_status'], PS_PAYED, $action_note);
            } // 设为未付款
            elseif ($operation === 'unpay') {
                $this->admin_priv('order_ps_edit');

                // 标记订单为未付款，更新付款时间和已付款金额
                $arr = [
                    'pay_status' => PS_UNPAYED,
                    'pay_time' => 0,
                    'money_paid' => 0,
                    'order_amount' => $order['money_paid'],
                ];
                OrderHelper::update_order($order_id, $arr);

                // todo 处理退款
                $refund_type = @$_REQUEST['refund'];
                $refund_note = @$_REQUEST['refund_note'];
                OrderHelper::order_refund($order, $refund_type, $refund_note);

                // 记录log
                CommonHelper::order_action($order['order_sn'], OS_CONFIRMED, SS_UNSHIPPED, PS_UNPAYED, $action_note);
            } // 配货
            elseif ($operation === 'prepare') {
                // 标记订单为已确认，配货中
                if ($order['order_status'] != OS_CONFIRMED) {
                    $arr['order_status'] = OS_CONFIRMED;
                    $arr['confirm_time'] = TimeHelper::gmtime();
                }
                $arr['shipping_status'] = SS_PREPARING;
                OrderHelper::update_order($order_id, $arr);

                // 记录log
                CommonHelper::order_action($order['order_sn'], OS_CONFIRMED, SS_PREPARING, $order['pay_status'], $action_note);

                // 清除缓存
                $this->clear_cache_files();
            } // 分单确认
            elseif ($operation === 'split') {
                $this->admin_priv('order_ss_edit');

                // 定义当前时间
                define('GMTIME_UTC', TimeHelper::gmtime()); // 获取 UTC 时间戳

                // 获取表单提交数据
                $suppliers_id = isset($_REQUEST['suppliers_id']) ? intval(trim($_REQUEST['suppliers_id'])) : '0';
                array_walk($_REQUEST['delivery'], 'trim_array_walk');
                $delivery = $_REQUEST['delivery'];
                array_walk($_REQUEST['send_number'], 'trim_array_walk');
                array_walk($_REQUEST['send_number'], 'intval_array_walk');
                $send_number = $_REQUEST['send_number'];
                $action_note = isset($_REQUEST['action_note']) ? trim($_REQUEST['action_note']) : '';
                $delivery['user_id'] = intval($delivery['user_id']);
                $delivery['country'] = intval($delivery['country']);
                $delivery['province'] = intval($delivery['province']);
                $delivery['city'] = intval($delivery['city']);
                $delivery['district'] = intval($delivery['district']);
                $delivery['agency_id'] = intval($delivery['agency_id']);
                $delivery['insure_fee'] = floatval($delivery['insure_fee']);
                $delivery['shipping_fee'] = floatval($delivery['shipping_fee']);

                // 订单是否已全部分单检查
                if ($order['order_status'] === OS_SPLITED) {
                    // 操作失败
                    $links[] = ['text' => lang('order_info'), 'href' => 'order.php?act=info&order_id='.$order_id];

                    return $this->sys_msg(sprintf(
                        lang('order_splited_sms'),
                        $order['order_sn'],
                        lang('os')[OS_SPLITED],
                        lang('ss')[SS_SHIPPED_ING],
                        cfg('shop_name')
                    ), 1, $links);
                }

                // 取得订单商品
                $_goods = $this->get_order_goods(['order_id' => $order_id, 'order_sn' => $delivery['order_sn']]);
                $goods_list = $_goods['goods_list'];

                // 检查此单发货数量填写是否正确 合并计算相同商品和货品
                if (! empty($send_number) && ! empty($goods_list)) {
                    $goods_no_package = [];
                    foreach ($goods_list as $key => $value) {
                        // 去除 此单发货数量 等于 0 的商品
                        if (! isset($value['package_goods_list']) || ! is_array($value['package_goods_list'])) {
                            // 如果是货品则键值为商品ID与货品ID的组合
                            $_key = empty($value['product_id']) ? $value['goods_id'] : ($value['goods_id'].'_'.$value['product_id']);

                            // 统计此单商品总发货数 合并计算相同ID商品或货品的发货数
                            if (empty($goods_no_package[$_key])) {
                                $goods_no_package[$_key] = $send_number[$value['rec_id']];
                            } else {
                                $goods_no_package[$_key] += $send_number[$value['rec_id']];
                            }

                            // 去除
                            if ($send_number[$value['rec_id']] <= 0) {
                                unset($send_number[$value['rec_id']], $goods_list[$key]);

                                continue;
                            }
                        } else {
                            // 组合超值礼包信息
                            $goods_list[$key]['package_goods_list'] = $this->package_goods($value['package_goods_list'], $value['goods_number'], $value['order_id'], $value['extension_code'], $value['goods_id']);

                            // 超值礼包
                            foreach ($value['package_goods_list'] as $pg_key => $pg_value) {
                                // 如果是货品则键值为商品ID与货品ID的组合
                                $_key = empty($pg_value['product_id']) ? $pg_value['goods_id'] : ($pg_value['goods_id'].'_'.$pg_value['product_id']);

                                // 统计此单商品总发货数 合并计算相同ID产品的发货数
                                if (empty($goods_no_package[$_key])) {
                                    $goods_no_package[$_key] = $send_number[$value['rec_id']][$pg_value['g_p']];
                                } // 否则已经存在此键值
                                else {
                                    $goods_no_package[$_key] += $send_number[$value['rec_id']][$pg_value['g_p']];
                                }

                                // 去除
                                if ($send_number[$value['rec_id']][$pg_value['g_p']] <= 0) {
                                    unset($send_number[$value['rec_id']][$pg_value['g_p']], $goods_list[$key]['package_goods_list'][$pg_key]);
                                }
                            }

                            if (count($goods_list[$key]['package_goods_list']) <= 0) {
                                unset($send_number[$value['rec_id']], $goods_list[$key]);

                                continue;
                            }
                        }

                        // 发货数量与总量不符
                        if (! isset($value['package_goods_list']) || ! is_array($value['package_goods_list'])) {
                            $sended = $this->order_delivery_num($order_id, $value['goods_id'], $value['product_id']);
                            if (($value['goods_number'] - $sended - $send_number[$value['rec_id']]) < 0) {
                                // 操作失败
                                $links[] = ['text' => lang('order_info'), 'href' => 'order.php?act=info&order_id='.$order_id];

                                return $this->sys_msg(lang('act_ship_num'), 1, $links);
                            }
                        } else {
                            // 超值礼包
                            foreach ($goods_list[$key]['package_goods_list'] as $pg_key => $pg_value) {
                                if (($pg_value['order_send_number'] - $pg_value['sended'] - $send_number[$value['rec_id']][$pg_value['g_p']]) < 0) {
                                    // 操作失败
                                    $links[] = ['text' => lang('order_info'), 'href' => 'order.php?act=info&order_id='.$order_id];

                                    return $this->sys_msg(lang('act_ship_num'), 1, $links);
                                }
                            }
                        }
                    }
                }
                // 对上一步处理结果进行判断 兼容 上一步判断为假情况的处理
                if (empty($send_number) || empty($goods_list)) {
                    // 操作失败
                    $links[] = ['text' => lang('order_info'), 'href' => 'order.php?act=info&order_id='.$order_id];

                    return $this->sys_msg(lang('act_false'), 1, $links);
                }

                // 检查此单发货商品库存缺货情况
                // $goods_list已经过处理 超值礼包中商品库存已取得
                $virtual_goods = [];
                $package_virtual_goods = [];
                foreach ($goods_list as $key => $value) {
                    // 商品（超值礼包）
                    if ($value['extension_code'] === 'package_buy') {
                        foreach ($value['package_goods_list'] as $pg_key => $pg_value) {
                            if ($pg_value['goods_number'] < $goods_no_package[$pg_value['g_p']] && ((cfg('use_storage') === '1' && cfg('stock_dec_time') === SDT_SHIP) || (cfg('use_storage') === '0' && $pg_value['is_real'] === 0))) {
                                // 操作失败
                                $links[] = ['text' => lang('order_info'), 'href' => 'order.php?act=info&order_id='.$order_id];

                                return $this->sys_msg(sprintf(lang('act_good_vacancy'), $pg_value['goods_name']), 1, $links);
                            }

                            // 商品（超值礼包） 虚拟商品列表 package_virtual_goods
                            if ($pg_value['is_real'] === 0) {
                                $package_virtual_goods[] = [
                                    'goods_id' => $pg_value['goods_id'],
                                    'goods_name' => $pg_value['goods_name'],
                                    'num' => $send_number[$value['rec_id']][$pg_value['g_p']],
                                ];
                            }
                        }
                    } // 商品（虚货）
                    elseif ($value['extension_code'] === 'virtual_card' || $value['is_real'] === 0) {
                        $num = DB::table('goods_virtual_card')
                            ->where('goods_id', $value['goods_id'])
                            ->where('is_saled', 0)
                            ->count();
                        if (($num < $goods_no_package[$value['goods_id']]) && ! (cfg('use_storage') === '1' && cfg('stock_dec_time') === SDT_PLACE)) {
                            // 操作失败
                            $links[] = ['text' => lang('order_info'), 'href' => 'order.php?act=info&order_id='.$order_id];

                            return $this->sys_msg(sprintf(lang('virtual_card_oos').'【'.$value['goods_name'].'】'), 1, $links);
                        }

                        // 虚拟商品列表 virtual_card
                        if ($value['extension_code'] === 'virtual_card') {
                            $virtual_goods[$value['extension_code']][] = ['goods_id' => $value['goods_id'], 'goods_name' => $value['goods_name'], 'num' => $send_number[$value['rec_id']]];
                        }
                    } // 商品（实货）、（货品）
                    else {
                        // 如果是货品则键值为商品ID与货品ID的组合
                        $_key = empty($value['product_id']) ? $value['goods_id'] : ($value['goods_id'].'_'.$value['product_id']);

                        // （实货）
                        if (empty($value['product_id'])) {
                            $num = DB::table('goods')
                                ->where('goods_id', $value['goods_id'])
                                ->value('goods_number');
                        } // （货品）
                        else {
                            $num = DB::table('goods_product')
                                ->where('goods_id', $value['goods_id'])
                                ->where('product_id', $value['product_id'])
                                ->value('product_number');
                        }

                        if (($num < $goods_no_package[$_key]) && cfg('use_storage') === '1' && cfg('stock_dec_time') === SDT_SHIP) {
                            // 操作失败
                            $links[] = ['text' => lang('order_info'), 'href' => 'order.php?act=info&order_id='.$order_id];

                            return $this->sys_msg(sprintf(lang('act_good_vacancy'), $value['goods_name']), 1, $links);
                        }
                    }
                }

                // 生成发货单
                // 获取发货单号和流水号
                $delivery['delivery_sn'] = OrderHelper::get_delivery_sn();
                $delivery_sn = $delivery['delivery_sn'];
                // 获取当前操作员
                $delivery['action_user'] = Session::get('admin_name');
                // 获取发货单生成时间
                $delivery['update_time'] = GMTIME_UTC;
                $delivery_time = $delivery['update_time'];
                $delivery['add_time'] = DB::table('order_info')
                    ->where('order_sn', $delivery['order_sn'])
                    ->value('add_time');
                // 获取发货单所属供应商
                $delivery['suppliers_id'] = $suppliers_id;
                // 设置默认值
                $delivery['status'] = 2; // 正常
                $delivery['order_id'] = $order_id;
                // 过滤字段项
                $filter_fileds = [
                    'order_sn',
                    'add_time',
                    'user_id',
                    'how_oos',
                    'shipping_id',
                    'shipping_fee',
                    'consignee',
                    'address',
                    'country',
                    'province',
                    'city',
                    'district',
                    'sign_building',
                    'email',
                    'zipcode',
                    'tel',
                    'mobile',
                    'best_time',
                    'postscript',
                    'insure_fee',
                    'agency_id',
                    'delivery_sn',
                    'action_user',
                    'update_time',
                    'suppliers_id',
                    'status',
                    'order_id',
                    'shipping_name',
                ];
                $_delivery = [];
                foreach ($filter_fileds as $value) {
                    $_delivery[$value] = $delivery[$value];
                }
                // 发货单入库
                $delivery_id = DB::table('order_delivery_order')->insertGetId($_delivery);
                if ($delivery_id) {
                    $delivery_goods = [];

                    // 发货单商品入库
                    if (! empty($goods_list)) {
                        foreach ($goods_list as $value) {
                            // 商品（实货）（虚货）
                            if (empty($value['extension_code']) || $value['extension_code'] === 'virtual_card') {
                                $delivery_goods = [
                                    'delivery_id' => $delivery_id,
                                    'goods_id' => $value['goods_id'],
                                    'product_id' => $value['product_id'],
                                    'product_sn' => $value['product_sn'],
                                    'goods_id' => $value['goods_id'],
                                    'goods_name' => addslashes($value['goods_name']),
                                    'brand_name' => addslashes($value['brand_name']),
                                    'goods_sn' => $value['goods_sn'],
                                    'send_number' => $send_number[$value['rec_id']],
                                    'parent_id' => 0,
                                    'is_real' => $value['is_real'],
                                    'goods_attr' => addslashes($value['goods_attr']),
                                ];

                                // 如果是货品
                                if (! empty($value['product_id'])) {
                                    $delivery_goods['product_id'] = $value['product_id'];
                                }

                                DB::table('order_delivery_goods')->insert($delivery_goods);
                            } // 商品（超值礼包）
                            elseif ($value['extension_code'] === 'package_buy') {
                                foreach ($value['package_goods_list'] as $pg_key => $pg_value) {
                                    $delivery_pg_goods = [
                                        'delivery_id' => $delivery_id,
                                        'goods_id' => $pg_value['goods_id'],
                                        'product_id' => $pg_value['product_id'],
                                        'product_sn' => $pg_value['product_sn'],
                                        'goods_name' => $pg_value['goods_name'],
                                        'brand_name' => '',
                                        'goods_sn' => $pg_value['goods_sn'],
                                        'send_number' => $send_number[$value['rec_id']][$pg_value['g_p']],
                                        'parent_id' => $value['goods_id'], // 礼包ID
                                        'extension_code' => $value['extension_code'], // 礼包
                                        'is_real' => $pg_value['is_real'],
                                    ];
                                    DB::table('order_delivery_goods')->insert($delivery_pg_goods);
                                }
                            }
                        }
                    }
                } else {
                    // 操作失败
                    $links[] = ['text' => lang('order_info'), 'href' => 'order.php?act=info&order_id='.$order_id];

                    return $this->sys_msg(lang('act_false'), 1, $links);
                }
                unset($filter_fileds, $delivery, $_delivery, $order_finish);

                // 定单信息更新处理
                if (true) {
                    // 定单信息
                    $_sended = &$send_number;
                    foreach ($_goods['goods_list'] as $key => $value) {
                        if ($value['extension_code'] != 'package_buy') {
                            unset($_goods['goods_list'][$key]);
                        }
                    }
                    foreach ($goods_list as $key => $value) {
                        if ($value['extension_code'] === 'package_buy') {
                            unset($goods_list[$key]);
                        }
                    }
                    $_goods['goods_list'] = $goods_list + $_goods['goods_list'];
                    unset($goods_list);

                    // 更新订单的虚拟卡 商品（虚货）
                    $_virtual_goods = isset($virtual_goods['virtual_card']) ? $virtual_goods['virtual_card'] : '';
                    $this->update_order_virtual_goods($order_id, $_sended, $_virtual_goods);

                    // 更新订单的非虚拟商品信息 即：商品（实货）（货品）、商品（超值礼包）
                    $this->update_order_goods($order_id, $_sended, $_goods['goods_list']);

                    // 标记订单为已确认 “发货中”
                    // 更新发货时间
                    $order_finish = $this->get_order_finish($order_id);
                    $shipping_status = SS_SHIPPED_ING;
                    if ($order['order_status'] != OS_CONFIRMED && $order['order_status'] != OS_SPLITED && $order['order_status'] != OS_SPLITING_PART) {
                        $arr['order_status'] = OS_CONFIRMED;
                        $arr['confirm_time'] = GMTIME_UTC;
                    }
                    $arr['order_status'] = $order_finish ? OS_SPLITED : OS_SPLITING_PART; // 全部分单、部分分单
                    $arr['shipping_status'] = $shipping_status;
                    OrderHelper::update_order($order_id, $arr);
                }

                // 记录log
                CommonHelper::order_action($order['order_sn'], $arr['order_status'], $shipping_status, $order['pay_status'], $action_note);

                // 清除缓存
                $this->clear_cache_files();
            } // 设为未发货
            elseif ($operation === 'unship') {
                $this->admin_priv('order_ss_edit');

                // 标记订单为“未发货”，更新发货时间, 订单状态为“确认”
                OrderHelper::update_order($order_id, ['shipping_status' => SS_UNSHIPPED, 'shipping_time' => 0, 'invoice_no' => '', 'order_status' => OS_CONFIRMED]);

                // 记录log
                CommonHelper::order_action($order['order_sn'], $order['order_status'], SS_UNSHIPPED, $order['pay_status'], $action_note);

                // 如果订单用户不为空，计算积分，并退回
                if ($order['user_id'] > 0) {
                    // 取得用户信息
                    $user = OrderHelper::user_info($order['user_id']);

                    // 计算并退回积分
                    $integral = OrderHelper::integral_to_give($order);
                    CommonHelper::log_account_change($order['user_id'], 0, 0, (-1) * intval($integral['rank_points']), (-1) * intval($integral['custom_points']), sprintf(lang('return_order_gift_integral'), $order['order_sn']));

                    // todo 计算并退回红包
                    OrderHelper::return_order_bonus($order_id);
                }

                // 如果使用库存，则增加库存
                if (cfg('use_storage') === '1' && cfg('stock_dec_time') === SDT_SHIP) {
                    OrderHelper::change_order_goods_storage($order['order_id'], false, SDT_SHIP);
                }

                // 删除发货单
                $this->del_order_delivery($order_id);

                // 将订单的商品发货数量更新为 0
                DB::table('order_goods')
                    ->where('order_id', $order_id)
                    ->update(['send_number' => 0]);

                // 清除缓存
                $this->clear_cache_files();
            } // 收货确认
            elseif ($operation === 'receive') {
                // 标记订单为“收货确认”，如果是货到付款，同时修改订单为已付款
                $arr = ['shipping_status' => SS_RECEIVED];
                $payment = OrderHelper::payment_info($order['pay_id']);
                if ($payment['is_cod']) {
                    $arr['pay_status'] = PS_PAYED;
                    $order['pay_status'] = PS_PAYED;
                }
                OrderHelper::update_order($order_id, $arr);

                // 记录log
                CommonHelper::order_action($order['order_sn'], $order['order_status'], SS_RECEIVED, $order['pay_status'], $action_note);
            } // 取消
            elseif ($operation === 'cancel') {
                // 标记订单为“取消”，记录取消原因
                $cancel_note = isset($_REQUEST['cancel_note']) ? trim($_REQUEST['cancel_note']) : '';
                $arr = [
                    'order_status' => OS_CANCELED,
                    'to_buyer' => $cancel_note,
                    'pay_status' => PS_UNPAYED,
                    'pay_time' => 0,
                    'money_paid' => 0,
                    'order_amount' => $order['money_paid'],
                ];
                OrderHelper::update_order($order_id, $arr);

                // todo 处理退款
                if ($order['money_paid'] > 0) {
                    $refund_type = $_REQUEST['refund'];
                    $refund_note = $_REQUEST['refund_note'];
                    OrderHelper::order_refund($order, $refund_type, $refund_note);
                }

                // 记录log
                CommonHelper::order_action($order['order_sn'], OS_CANCELED, $order['shipping_status'], PS_UNPAYED, $action_note);

                // 如果使用库存，且下订单时减库存，则增加库存
                if (cfg('use_storage') === '1' && cfg('stock_dec_time') === SDT_PLACE) {
                    OrderHelper::change_order_goods_storage($order_id, false, SDT_PLACE);
                }

                // 退还用户余额、积分、红包
                $this->return_user_surplus_integral_bonus($order);

                // 发送邮件
                $cfg = cfg('send_cancel_email');
                if ($cfg === '1') {
                    $tpl = CommonHelper::get_mail_template('order_cancel');
                    $this->assign('order', $order);
                    $this->assign('shop_name', cfg('shop_name'));
                    $this->assign('send_date', TimeHelper::local_date(cfg('date_format')));
                    $this->assign('sent_date', TimeHelper::local_date(cfg('date_format')));
                    $content = $this->fetch('str:'.$tpl['template_content']);
                    if (! BaseHelper::send_mail($order['consignee'], $order['email'], $tpl['template_subject'], $content, $tpl['is_html'])) {
                        $msg = lang('send_mail_fail');
                    }
                }
            } // 设为无效
            elseif ($operation === 'invalid') {
                // 标记订单为“无效”、“未付款”
                OrderHelper::update_order($order_id, ['order_status' => OS_INVALID]);

                // 记录log
                CommonHelper::order_action($order['order_sn'], OS_INVALID, $order['shipping_status'], PS_UNPAYED, $action_note);

                // 如果使用库存，且下订单时减库存，则增加库存
                if (cfg('use_storage') === '1' && cfg('stock_dec_time') === SDT_PLACE) {
                    OrderHelper::change_order_goods_storage($order_id, false, SDT_PLACE);
                }

                // 发送邮件
                $cfg = cfg('send_invalid_email');
                if ($cfg === '1') {
                    $tpl = CommonHelper::get_mail_template('order_invalid');
                    $this->assign('order', $order);
                    $this->assign('shop_name', cfg('shop_name'));
                    $this->assign('send_date', TimeHelper::local_date(cfg('date_format')));
                    $this->assign('sent_date', TimeHelper::local_date(cfg('date_format')));
                    $content = $this->fetch('str:'.$tpl['template_content']);
                    if (! BaseHelper::send_mail($order['consignee'], $order['email'], $tpl['template_subject'], $content, $tpl['is_html'])) {
                        $msg = lang('send_mail_fail');
                    }
                }

                // 退货用户余额、积分、红包
                $this->return_user_surplus_integral_bonus($order);
            } // 退货
            elseif ($operation === 'return') {
                // 定义当前时间
                define('GMTIME_UTC', TimeHelper::gmtime()); // 获取 UTC 时间戳

                // 过滤数据
                $_REQUEST['refund'] = isset($_REQUEST['refund']) ? $_REQUEST['refund'] : '';
                $_REQUEST['refund_note'] = isset($_REQUEST['refund_note']) ? $_REQUEST['refund'] : '';

                // 标记订单为“退货”、“未付款”、“未发货”
                $arr = [
                    'order_status' => OS_RETURNED,
                    'pay_status' => PS_UNPAYED,
                    'shipping_status' => SS_UNSHIPPED,
                    'money_paid' => 0,
                    'invoice_no' => '',
                    'order_amount' => $order['money_paid'],
                ];
                OrderHelper::update_order($order_id, $arr);

                // todo 处理退款
                if ($order['pay_status'] != PS_UNPAYED) {
                    $refund_type = $_REQUEST['refund'];
                    $refund_note = $_REQUEST['refund'];
                    OrderHelper::order_refund($order, $refund_type, $refund_note);
                }

                // 记录log
                CommonHelper::order_action($order['order_sn'], OS_RETURNED, SS_UNSHIPPED, PS_UNPAYED, $action_note);

                // 如果订单用户不为空，计算积分，并退回
                if ($order['user_id'] > 0) {
                    // 取得用户信息
                    $user = OrderHelper::user_info($order['user_id']);

                    $goods_num = DB::table('order_goods')
                        ->select('goods_number', 'send_number')
                        ->where('order_id', $order['order_id'])
                        ->first();
                    $goods_num = $goods_num ? (array) $goods_num : [];

                    if ($goods_num['goods_number'] === $goods_num['send_number']) {
                        // 计算并退回积分
                        $integral = OrderHelper::integral_to_give($order);
                        CommonHelper::log_account_change($order['user_id'], 0, 0, (-1) * intval($integral['rank_points']), (-1) * intval($integral['custom_points']), sprintf(lang('return_order_gift_integral'), $order['order_sn']));
                    }
                    // todo 计算并退回红包
                    OrderHelper::return_order_bonus($order_id);
                }

                // 如果使用库存，则增加库存（不论何时减库存都需要）
                if (cfg('use_storage') === '1') {
                    if (cfg('stock_dec_time') === SDT_SHIP) {
                        OrderHelper::change_order_goods_storage($order['order_id'], false, SDT_SHIP);
                    } elseif (cfg('stock_dec_time') === SDT_PLACE) {
                        OrderHelper::change_order_goods_storage($order['order_id'], false, SDT_PLACE);
                    }
                }

                // 退货用户余额、积分、红包
                $this->return_user_surplus_integral_bonus($order);

                // 获取当前操作员
                $delivery['action_user'] = Session::get('admin_name');
                // 添加退货记录
                $delivery_list = DB::table('order_delivery_order')
                    ->whereIn('status', [0, 2])
                    ->where('order_id', $order['order_id'])
                    ->get()
                    ->toArray();
                $delivery_list = array_map(function ($item) {
                    return (array) $item;
                }, $delivery_list);
                if ($delivery_list) {
                    foreach ($delivery_list as $list) {
                        $back_id = DB::table('order_back_order')->insertGetId([
                            'delivery_sn' => $list['delivery_sn'],
                            'order_sn' => $list['order_sn'],
                            'order_id' => $list['order_id'],
                            'add_time' => $list['add_time'],
                            'shipping_id' => $list['shipping_id'],
                            'user_id' => $list['user_id'],
                            'action_user' => $delivery['action_user'],
                            'consignee' => $list['consignee'],
                            'address' => $list['address'],
                            'country' => $list['country'],
                            'province' => $list['province'],
                            'city' => $list['city'],
                            'district' => $list['district'],
                            'sign_building' => $list['sign_building'],
                            'email' => $list['email'],
                            'zipcode' => $list['zipcode'],
                            'tel' => $list['tel'],
                            'mobile' => $list['mobile'],
                            'best_time' => $list['best_time'],
                            'postscript' => $list['postscript'],
                            'how_oos' => $list['how_oos'],
                            'insure_fee' => $list['insure_fee'],
                            'shipping_fee' => $list['shipping_fee'],
                            'update_time' => $list['update_time'],
                            'suppliers_id' => $list['suppliers_id'],
                            'return_time' => GMTIME_UTC,
                            'agency_id' => $list['agency_id'],
                            'invoice_no' => $list['invoice_no'],
                        ]);

                        DB::table('order_back_goods')->insertUsing(
                            ['back_id', 'goods_id', 'product_id', 'product_sn', 'goods_name', 'goods_sn', 'is_real', 'send_number', 'goods_attr'],
                            DB::table('order_delivery_goods')
                                ->select(DB::raw("'$back_id'"), 'goods_id', 'product_id', 'product_sn', 'goods_name', 'goods_sn', 'is_real', 'send_number', 'goods_attr')
                                ->where('delivery_id', $list['delivery_id'])
                        );
                    }
                }

                // 修改订单的发货单状态为退货
                DB::table('order_delivery_order')
                    ->whereIn('status', [0, 2])
                    ->where('order_id', $order['order_id'])
                    ->update(['status' => 1]);

                // 将订单的商品发货数量更新为 0
                DB::table('order_goods')
                    ->where('order_id', $order_id)
                    ->update(['send_number' => 0]);

                // 清除缓存
                $this->clear_cache_files();
            } elseif ($operation === 'after_service') {
                // 记录log
                CommonHelper::order_action($order['order_sn'], $order['order_status'], $order['shipping_status'], $order['pay_status'], '['.lang('op_after_service').'] '.$action_note);
            } else {
                exit('invalid params');
            }

            // 操作成功
            $links[] = ['text' => lang('order_info'), 'href' => 'order.php?act=info&order_id='.$order_id];

            return $this->sys_msg(lang('act_ok').$msg, 0, $links);
        }

        if ($action === 'json') {
            $func = $_REQUEST['func'];
            if ($func === 'get_goods_info') {
                // 取得商品信息
                $goods_id = $_REQUEST['goods_id'];
                $goods = DB::table('goods as g')
                    ->leftJoin('goods_brand as b', 'g.brand_id', '=', 'b.brand_id')
                    ->leftJoin('goods_category as c', 'g.cat_id', '=', 'c.cat_id')
                    ->select('goods_id', 'c.cat_name', 'goods_sn', 'goods_name', 'b.brand_name', 'goods_number', 'market_price', 'shop_price', 'promote_price', 'promote_start_date', 'promote_end_date', 'goods_brief', 'goods_type', 'is_promote')
                    ->where('goods_id', $goods_id)
                    ->first();
                $goods = $goods ? (array) $goods : [];
                $today = TimeHelper::gmtime();
                $goods['goods_price'] = ($goods['is_promote'] === 1 &&
                    $goods['promote_start_date'] <= $today && $goods['promote_end_date'] >= $today) ?
                    $goods['promote_price'] : $goods['shop_price'];

                // 取得会员价格
                $goods['user_price'] = DB::table('goods_member_price as p')
                    ->join('user_rank as r', 'p.user_rank', '=', 'r.rank_id')
                    ->select('p.user_price', 'r.rank_name')
                    ->where('p.goods_id', $goods_id)
                    ->get()
                    ->toArray();
                $goods['user_price'] = array_map(function ($item) {
                    return (array) $item;
                }, $goods['user_price']);

                $goods['attr_list'] = [];
                $res = DB::table('goods_attr as g')
                    ->join('goods_type_attribute as a', 'g.attr_id', '=', 'a.attr_id')
                    ->select('a.attr_id', 'a.attr_name', 'g.goods_attr_id', 'g.attr_value', 'g.attr_price', 'a.attr_input_type', 'a.attr_type')
                    ->where('g.goods_id', $goods_id)
                    ->get()
                    ->toArray();
                foreach ($res as $row) {
                    $row = (array) $row;
                    $goods['attr_list'][$row['attr_id']][] = $row;
                }
                $goods['attr_list'] = array_values($goods['attr_list']);

                echo json_encode($goods);
            }
        }

        /**
         * 合并订单
         */
        if ($action === 'ajax_merge_order') {
            $this->admin_priv('order_os_edit');

            $from_order_sn = empty($_POST['from_order_sn']) ? '' : BaseHelper::json_str_iconv(substr($_POST['from_order_sn'], 1));
            $to_order_sn = empty($_POST['to_order_sn']) ? '' : BaseHelper::json_str_iconv(substr($_POST['to_order_sn'], 1));

            $m_result = OrderHelper::merge_order($from_order_sn, $to_order_sn);
            $result = ['error' => 0, 'content' => ''];
            if ($m_result === true) {
                $result['message'] = lang('act_ok');
            } else {
                $result['error'] = 1;
                $result['message'] = $m_result;
            }

            return response()->json($result);
        }

        /**
         * 删除订单
         */
        if ($action === 'remove_order') {
            $this->admin_priv('order_edit');

            $order_id = intval($_REQUEST['id']);

            $this->check_authz_json('order_edit');

            // 检查订单是否允许删除操作
            $order = OrderHelper::order_info($order_id);
            $operable_list = $this->operable_list($order);
            if (! isset($operable_list['remove'])) {
                return $this->make_json_error('Hacking attempt');
            }

            DB::table('order_info')->where('order_id', $order_id)->delete();
            DB::table('order_goods')->where('order_id', $order_id)->delete();
            DB::table('order_action')->where('order_id', $order_id)->delete();
            $action_array = ['delivery', 'back'];
            $this->del_delivery($order_id, $action_array);

            $return = true;
            if ($return) {
                $url = 'order.php?act=query&'.str_replace('act=remove_order', '', $_SERVER['QUERY_STRING']);

                return response()->redirectTo($url);
            } else {
                return $this->make_json_error('Error removing order');
            }
        }

        /**
         * 根据关键字和id搜索用户
         */
        if ($action === 'search_users') {
            $id_name = empty($_GET['id_name']) ? '' : BaseHelper::json_str_iconv(trim($_GET['id_name']));

            $result = ['error' => 0, 'message' => '', 'content' => ''];
            if ($id_name != '') {
                $res = DB::table('user')
                    ->select('user_id', 'user_name')
                    ->where('user_id', 'like', '%'.BaseHelper::mysql_like_quote($id_name).'%')
                    ->orWhere('user_name', 'like', '%'.BaseHelper::mysql_like_quote($id_name).'%')
                    ->limit(20)
                    ->get()
                    ->toArray();

                $result['userlist'] = [];
                foreach ($res as $row) {
                    $row = (array) $row;
                    $result['userlist'][] = ['user_id' => $row['user_id'], 'user_name' => $row['user_name']];
                }
            } else {
                $result['error'] = 1;
                $result['message'] = 'NO KEYWORDS!';
            }

            return response()->json($result);
        }

        /**
         * 根据关键字搜索商品
         */
        if ($action === 'search_goods') {
            $keyword = empty($_GET['keyword']) ? '' : BaseHelper::json_str_iconv(trim($_GET['keyword']));

            $result = ['error' => 0, 'message' => '', 'content' => ''];

            if ($keyword != '') {
                $res = DB::table('goods')
                    ->select('goods_id', 'goods_name', 'goods_sn')
                    ->where('is_delete', 0)
                    ->where('is_on_sale', 1)
                    ->where('is_alone_sale', 1)
                    ->where(function ($query) use ($keyword) {
                        $query->where('goods_id', 'LIKE', '%'.BaseHelper::mysql_like_quote($keyword).'%')
                            ->orWhere('goods_name', 'LIKE', '%'.BaseHelper::mysql_like_quote($keyword).'%')
                            ->orWhere('goods_sn', 'LIKE', '%'.BaseHelper::mysql_like_quote($keyword).'%');
                    })
                    ->limit(20)
                    ->get()
                    ->toArray();

                $result['goodslist'] = [];
                foreach ($res as $row) {
                    $row = (array) $row;
                    $result['goodslist'][] = ['goods_id' => $row['goods_id'], 'name' => $row['goods_id'].'  '.$row['goods_name'].'  '.$row['goods_sn']];
                }
            } else {
                $result['error'] = 1;
                $result['message'] = 'NO KEYWORDS';
            }

            return response()->json($result);
        }

        /**
         * 编辑收货单号
         */
        if ($action === 'edit_invoice_no') {
            $this->check_authz_json('order_edit');

            $no = empty($_POST['val']) ? 'N/A' : BaseHelper::json_str_iconv(trim($_POST['val']));
            $no = $no === 'N/A' ? '' : $no;
            $order_id = empty($_POST['id']) ? 0 : intval($_POST['id']);

            if ($order_id === 0) {
                return $this->make_json_error('NO ORDER ID');
            }

            if (DB::table('order_info')->where('order_id', $order_id)->update(['invoice_no' => $no])) {
                if (empty($no)) {
                    return $this->make_json_result('N/A');
                } else {
                    return $this->make_json_result(stripcslashes($no));
                }
            } else {
                return $this->make_json_error(DB::getRawQuery('UPDATE error')); // DB facade error handling is different, usually throws exception
            }
        }

        /**
         * 编辑付款备注
         */
        if ($action === 'edit_pay_note') {
            $this->check_authz_json('order_edit');

            $no = empty($_POST['val']) ? 'N/A' : BaseHelper::json_str_iconv(trim($_POST['val']));
            $no = $no === 'N/A' ? '' : $no;
            $order_id = empty($_POST['id']) ? 0 : intval($_POST['id']);

            if ($order_id === 0) {
                return $this->make_json_error('NO ORDER ID');
            }

            if (DB::table('order_info')->where('order_id', $order_id)->update(['pay_note' => $no])) {
                if (empty($no)) {
                    return $this->make_json_result('N/A');
                } else {
                    return $this->make_json_result(stripcslashes($no));
                }
            } else {
                return $this->make_json_error(DB::getRawQuery('UPDATE error'));
            }
        }

        /**
         * 获取订单商品信息
         */
        if ($action === 'get_goods_info') {
            // 取得订单商品
            $order_id = isset($_REQUEST['order_id']) ? intval($_REQUEST['order_id']) : 0;
            if (empty($order_id)) {
                return $this->make_json_response('', 1, lang('error_get_goods_info'));
            }
            $goods_list = [];
            $goods_attr = [];
            $res = DB::table('order_goods as o')
                ->leftJoin('goods as g', 'o.goods_id', '=', 'g.goods_id')
                ->leftJoin('goods_brand as b', 'g.brand_id', '=', 'b.brand_id')
                ->select('o.*', 'g.goods_thumb', 'g.goods_number as storage', 'o.goods_attr', DB::raw("IFNULL(b.brand_name, '') as brand_name"))
                ->where('o.order_id', $order_id)
                ->get()
                ->toArray();
            foreach ($res as $row) {
                $row = (array) $row;
                $row['formated_subtotal'] = CommonHelper::price_format($row['goods_price'] * $row['goods_number']);
                $row['formated_goods_price'] = CommonHelper::price_format($row['goods_price']);
                $_goods_thumb = CommonHelper::get_image_path($row['goods_thumb']);
                $_goods_thumb = (strpos($_goods_thumb, 'http://') === 0) ? $_goods_thumb : ecs()->url().$_goods_thumb;
                $row['goods_thumb'] = $_goods_thumb;
                $goods_attr[] = explode(' ', trim($row['goods_attr'])); // 将商品属性拆分为一个数组
                $goods_list[] = $row;
            }
            $attr = [];
            $arr = [];
            foreach ($goods_attr as $index => $array_val) {
                foreach ($array_val as $value) {
                    $arr = explode(':', $value); // 以 : 号将属性拆开
                    $attr[$index][] = @['name' => $arr[0], 'value' => $arr[1]];
                }
            }

            $this->assign('goods_attr', $attr);
            $this->assign('goods_list', $goods_list);
            $str = $this->fetch('order_goods_info');
            $goods[] = ['order_id' => $order_id, 'str' => $str];

            return $this->make_json_result($goods);
        }
    }

    /**
     * 取得状态列表
     *
     * @param  string  $type  类型：all | order | shipping | payment
     */
    private function get_status_list($type = 'all')
    {
        $list = [];

        if ($type === 'all' || $type === 'order') {
            $pre = $type === 'all' ? 'os_' : '';
            foreach (lang('os') as $key => $value) {
                $list[$pre.$key] = $value;
            }
        }

        if ($type === 'all' || $type === 'shipping') {
            $pre = $type === 'all' ? 'ss_' : '';
            foreach (lang('ss') as $key => $value) {
                $list[$pre.$key] = $value;
            }
        }

        if ($type === 'all' || $type === 'payment') {
            $pre = $type === 'all' ? 'ps_' : '';
            foreach (lang('ps') as $key => $value) {
                $list[$pre.$key] = $value;
            }
        }

        return $list;
    }

    /**
     * 退回余额、积分、红包（取消、无效、退货时），把订单使用余额、积分、红包设为0
     *
     * @param  array  $order  订单信息
     */
    private function return_user_surplus_integral_bonus($order)
    {
        // 处理余额、积分、红包
        if ($order['user_id'] > 0 && $order['surplus'] > 0) {
            $surplus = $order['money_paid'] < 0 ? $order['surplus'] + $order['money_paid'] : $order['surplus'];
            CommonHelper::log_account_change($order['user_id'], $surplus, 0, 0, 0, sprintf(lang('return_order_surplus'), $order['order_sn']));
            DB::table('order_info')
                ->where('order_id', $order['order_id'])
                ->update(['order_amount' => 0]);
        }

        if ($order['user_id'] > 0 && $order['integral'] > 0) {
            CommonHelper::log_account_change($order['user_id'], 0, 0, 0, $order['integral'], sprintf(lang('return_order_integral'), $order['order_sn']));
        }

        if ($order['bonus_id'] > 0) {
            OrderHelper::unuse_bonus($order['bonus_id']);
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
    }

    /**
     * 更新订单总金额
     *
     * @param  int  $order_id  订单id
     * @return bool
     */
    private function update_order_amount($order_id): int
    {
        // 更新订单总金额
        return (int) DB::table('order_info')
            ->where('order_id', $order_id)
            ->limit(1)
            ->update(['order_amount' => DB::raw(OrderHelper::order_due_field())]);
    }

    /**
     * 返回某个订单可执行的操作列表，包括权限判断
     *
     * @param  array  $order  订单信息 order_status, shipping_status, pay_status
     * @param  bool  $is_cod  支付方式是否货到付款
     * @return array 可执行的操作  confirm, pay, unpay, prepare, ship, unship, receive, cancel, invalid, return, drop
     *               格式 array('confirm' => true, 'pay' => true)
     */
    private function operable_list($order)
    {
        // 取得订单状态、发货状态、付款状态
        $os = $order['order_status'];
        $ss = $order['shipping_status'];
        $ps = $order['pay_status'];
        // 取得订单操作权限
        $actions = Session::get('action_list');
        if ($actions === 'all') {
            $priv_list = ['os' => true, 'ss' => true, 'ps' => true, 'edit' => true];
        } else {
            $actions = ','.$actions.',';
            $priv_list = [
                'os' => strpos($actions, ',order_os_edit,') !== false,
                'ss' => strpos($actions, ',order_ss_edit,') !== false,
                'ps' => strpos($actions, ',order_ps_edit,') !== false,
                'edit' => strpos($actions, ',order_edit,') !== false,
            ];
        }

        // 取得订单支付方式是否货到付款
        $payment = OrderHelper::payment_info($order['pay_id']);
        $is_cod = $payment['is_cod'] === 1;

        // 根据状态返回可执行操作
        $list = [];
        if ($os === OS_UNCONFIRMED) {
            // 状态：未确认 => 未付款、未发货
            if ($priv_list['os']) {
                $list['confirm'] = true; // 确认
                $list['invalid'] = true; // 无效
                $list['cancel'] = true; // 取消
                if ($is_cod) {
                    // 货到付款
                    if ($priv_list['ss']) {
                        $list['prepare'] = true; // 配货
                        $list['split'] = true; // 分单
                    }
                } else {
                    // 不是货到付款
                    if ($priv_list['ps']) {
                        $list['pay'] = true;  // 付款
                    }
                }
            }
        } elseif ($os === OS_CONFIRMED || $os === OS_SPLITED || $os === OS_SPLITING_PART) {
            // 状态：已确认
            if ($ps === PS_UNPAYED) {
                // 状态：已确认、未付款
                if ($ss === SS_UNSHIPPED || $ss === SS_PREPARING) {
                    // 状态：已确认、未付款、未发货（或配货中）
                    if ($priv_list['os']) {
                        $list['cancel'] = true; // 取消
                        $list['invalid'] = true; // 无效
                    }
                    if ($is_cod) {
                        // 货到付款
                        if ($priv_list['ss']) {
                            if ($ss === SS_UNSHIPPED) {
                                $list['prepare'] = true; // 配货
                            }
                            $list['split'] = true; // 分单
                        }
                    } else {
                        // 不是货到付款
                        if ($priv_list['ps']) {
                            $list['pay'] = true; // 付款
                        }
                    }
                } // 状态：已确认、未付款、发货中
                elseif ($ss === SS_SHIPPED_ING || $ss === SS_SHIPPED_PART) {
                    // 部分分单
                    if ($os === OS_SPLITING_PART) {
                        $list['split'] = true; // 分单
                    }
                    $list['to_delivery'] = true; // 去发货
                } else {
                    // 状态：已确认、未付款、已发货或已收货 => 货到付款
                    if ($priv_list['ps']) {
                        $list['pay'] = true; // 付款
                    }
                    if ($priv_list['ss']) {
                        if ($ss === SS_SHIPPED) {
                            $list['receive'] = true; // 收货确认
                        }
                        $list['unship'] = true; // 设为未发货
                        if ($priv_list['os']) {
                            $list['return'] = true; // 退货
                        }
                    }
                }
            } else {
                // 状态：已确认、已付款和付款中
                if ($ss === SS_UNSHIPPED || $ss === SS_PREPARING) {
                    // 状态：已确认、已付款和付款中、未发货（配货中） => 不是货到付款
                    if ($priv_list['ss']) {
                        if ($ss === SS_UNSHIPPED) {
                            $list['prepare'] = true; // 配货
                        }
                        $list['split'] = true; // 分单
                    }
                    if ($priv_list['ps']) {
                        $list['unpay'] = true; // 设为未付款
                        if ($priv_list['os']) {
                            $list['cancel'] = true; // 取消
                        }
                    }
                } // 状态：已确认、未付款、发货中
                elseif ($ss === SS_SHIPPED_ING || $ss === SS_SHIPPED_PART) {
                    // 部分分单
                    if ($os === OS_SPLITING_PART) {
                        $list['split'] = true; // 分单
                    }
                    $list['to_delivery'] = true; // 去发货
                } else {
                    // 状态：已确认、已付款和付款中、已发货或已收货
                    if ($priv_list['ss']) {
                        if ($ss === SS_SHIPPED) {
                            $list['receive'] = true; // 收货确认
                        }
                        if (! $is_cod) {
                            $list['unship'] = true; // 设为未发货
                        }
                    }
                    if ($priv_list['ps'] && $is_cod) {
                        $list['unpay'] = true; // 设为未付款
                    }
                    if ($priv_list['os'] && $priv_list['ss'] && $priv_list['ps']) {
                        $list['return'] = true; // 退货（包括退款）
                    }
                }
            }
        } elseif ($os === OS_CANCELED) {
            // 状态：取消
            if ($priv_list['os']) {
                $list['confirm'] = true;
            }
            if ($priv_list['edit']) {
                $list['remove'] = true;
            }
        } elseif ($os === OS_INVALID) {
            // 状态：无效
            if ($priv_list['os']) {
                $list['confirm'] = true;
            }
            if ($priv_list['edit']) {
                $list['remove'] = true;
            }
        } elseif ($os === OS_RETURNED) {
            // 状态：退货
            if ($priv_list['os']) {
                $list['confirm'] = true;
            }
        }

        // 修正发货操作
        if (! empty($list['split'])) {
            // 如果是团购活动且未处理成功，不能发货
            if ($order['extension_code'] === 'group_buy') {
                $group_buy = GoodsHelper::group_buy_info(intval($order['extension_id']));
                if ($group_buy['status'] != GBS_SUCCEED) {
                    unset($list['split']);
                    unset($list['to_delivery']);
                }
            }

            // 如果部分发货 不允许 取消 订单
            if ($this->order_deliveryed($order['order_id'])) {
                $list['return'] = true; // 退货（包括退款）
                unset($list['cancel']); // 取消
            }
        }

        // 售后
        $list['after_service'] = true;

        return $list;
    }

    /**
     * 处理编辑订单时订单金额变动
     *
     * @param  array  $order  订单信息
     * @param  array  $msgs  提示信息
     * @param  array  $links  链接信息
     */
    private function handle_order_money_change($order, &$msgs, &$links)
    {
        $order_id = $order['order_id'];
        if ($order['pay_status'] === PS_PAYED || $order['pay_status'] === PS_PAYING) {
            // 应付款金额
            $money_dues = $order['order_amount'];
            if ($money_dues > 0) {
                // 修改订单为未付款
                OrderHelper::update_order($order_id, ['pay_status' => PS_UNPAYED, 'pay_time' => 0]);
                $msgs[] = lang('amount_increase');
                $links[] = ['text' => lang('order_info'), 'href' => 'order.php?act=info&order_id='.$order_id];
            } elseif ($money_dues < 0) {
                $anonymous = $order['user_id'] > 0 ? 0 : 1;
                $msgs[] = lang('amount_decrease');
                $links[] = [
                    'text' => lang('refund'),
                    'href' => 'order.php?act=process&func=load_refund&anonymous='.
                        $anonymous.'&order_id='.$order_id.'&refund_amount='.abs($money_dues),
                ];
            }
        }
    }

    /**
     *  获取订单列表信息
     */
    private function order_list(): array
    {
        $result = MainHelper::get_filter();
        if ($result === false) {
            // 过滤信息
            $filter['order_sn'] = empty($_REQUEST['order_sn']) ? '' : trim($_REQUEST['order_sn']);
            if (! empty($_GET['is_ajax']) && $_GET['is_ajax'] === 1) {
                $_REQUEST['consignee'] = BaseHelper::json_str_iconv($_REQUEST['consignee']);
                // $_REQUEST['address'] = BaseHelper::json_str_iconv($_REQUEST['address']);
            }
            $filter['consignee'] = empty($_REQUEST['consignee']) ? '' : trim($_REQUEST['consignee']);
            $filter['email'] = empty($_REQUEST['email']) ? '' : trim($_REQUEST['email']);
            $filter['address'] = empty($_REQUEST['address']) ? '' : trim($_REQUEST['address']);
            $filter['zipcode'] = empty($_REQUEST['zipcode']) ? '' : trim($_REQUEST['zipcode']);
            $filter['tel'] = empty($_REQUEST['tel']) ? '' : trim($_REQUEST['tel']);
            $filter['mobile'] = empty($_REQUEST['mobile']) ? 0 : intval($_REQUEST['mobile']);
            $filter['country'] = empty($_REQUEST['country']) ? 0 : intval($_REQUEST['country']);
            $filter['province'] = empty($_REQUEST['province']) ? 0 : intval($_REQUEST['province']);
            $filter['city'] = empty($_REQUEST['city']) ? 0 : intval($_REQUEST['city']);
            $filter['district'] = empty($_REQUEST['district']) ? 0 : intval($_REQUEST['district']);
            $filter['shipping_id'] = empty($_REQUEST['shipping_id']) ? 0 : intval($_REQUEST['shipping_id']);
            $filter['pay_id'] = empty($_REQUEST['pay_id']) ? 0 : intval($_REQUEST['pay_id']);
            $filter['order_status'] = isset($_REQUEST['order_status']) ? intval($_REQUEST['order_status']) : -1;
            $filter['shipping_status'] = isset($_REQUEST['shipping_status']) ? intval($_REQUEST['shipping_status']) : -1;
            $filter['pay_status'] = isset($_REQUEST['pay_status']) ? intval($_REQUEST['pay_status']) : -1;
            $filter['user_id'] = empty($_REQUEST['user_id']) ? 0 : intval($_REQUEST['user_id']);
            $filter['user_name'] = empty($_REQUEST['user_name']) ? '' : trim($_REQUEST['user_name']);
            $filter['composite_status'] = isset($_REQUEST['composite_status']) ? intval($_REQUEST['composite_status']) : -1;
            $filter['group_buy_id'] = isset($_REQUEST['group_buy_id']) ? intval($_REQUEST['group_buy_id']) : 0;

            $filter['sort_by'] = empty($_REQUEST['sort_by']) ? 'add_time' : trim($_REQUEST['sort_by']);
            $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);

            $filter['start_time'] = empty($_REQUEST['start_time']) ? '' : (strpos($_REQUEST['start_time'], '-') > 0 ? TimeHelper::local_strtotime($_REQUEST['start_time']) : $_REQUEST['start_time']);
            $filter['end_time'] = empty($_REQUEST['end_time']) ? '' : (strpos($_REQUEST['end_time'], '-') > 0 ? TimeHelper::local_strtotime($_REQUEST['end_time']) : $_REQUEST['end_time']);

            $where = 'WHERE 1 ';
            if ($filter['order_sn']) {
                $where .= " AND o.order_sn LIKE '%".BaseHelper::mysql_like_quote($filter['order_sn'])."%'";
            }
            if ($filter['consignee']) {
                $where .= " AND o.consignee LIKE '%".BaseHelper::mysql_like_quote($filter['consignee'])."%'";
            }
            if ($filter['email']) {
                $where .= " AND o.email LIKE '%".BaseHelper::mysql_like_quote($filter['email'])."%'";
            }
            if ($filter['address']) {
                $where .= " AND o.address LIKE '%".BaseHelper::mysql_like_quote($filter['address'])."%'";
            }
            if ($filter['zipcode']) {
                $where .= " AND o.zipcode LIKE '%".BaseHelper::mysql_like_quote($filter['zipcode'])."%'";
            }
            if ($filter['tel']) {
                $where .= " AND o.tel LIKE '%".BaseHelper::mysql_like_quote($filter['tel'])."%'";
            }
            if ($filter['mobile']) {
                $where .= " AND o.mobile LIKE '%".BaseHelper::mysql_like_quote($filter['mobile'])."%'";
            }
            if ($filter['country']) {
                $where .= " AND o.country = '$filter[country]'";
            }
            if ($filter['province']) {
                $where .= " AND o.province = '$filter[province]'";
            }
            if ($filter['city']) {
                $where .= " AND o.city = '$filter[city]'";
            }
            if ($filter['district']) {
                $where .= " AND o.district = '$filter[district]'";
            }
            if ($filter['shipping_id']) {
                $where .= " AND o.shipping_id  = '$filter[shipping_id]'";
            }
            if ($filter['pay_id']) {
                $where .= " AND o.pay_id  = '$filter[pay_id]'";
            }
            if ($filter['order_status'] != -1) {
                $where .= " AND o.order_status  = '$filter[order_status]'";
            }
            if ($filter['shipping_status'] != -1) {
                $where .= " AND o.shipping_status = '$filter[shipping_status]'";
            }
            if ($filter['pay_status'] != -1) {
                $where .= " AND o.pay_status = '$filter[pay_status]'";
            }
            if ($filter['user_id']) {
                $where .= " AND o.user_id = '$filter[user_id]'";
            }
            if ($filter['user_name']) {
                $where .= " AND u.user_name LIKE '%".BaseHelper::mysql_like_quote($filter['user_name'])."%'";
            }
            if ($filter['start_time']) {
                $where .= " AND o.add_time >= '$filter[start_time]'";
            }
            if ($filter['end_time']) {
                $where .= " AND o.add_time <= '$filter[end_time]'";
            }

            // 综合状态
            switch ($filter['composite_status']) {
                case CS_AWAIT_PAY:
                    $where .= order_query_sql('await_pay');
                    break;

                case CS_AWAIT_SHIP:
                    $where .= order_query_sql('await_ship');
                    break;

                case CS_FINISHED:
                    $where .= order_query_sql('finished');
                    break;

                case PS_PAYING:
                    if ($filter['composite_status'] != -1) {
                        $where .= " AND o.pay_status = '$filter[composite_status]' ";
                    }
                    break;
                case OS_SHIPPED_PART:
                    if ($filter['composite_status'] != -1) {
                        $where .= " AND o.shipping_status  = '$filter[composite_status]'-2 ";
                    }
                    break;
                default:
                    if ($filter['composite_status'] != -1) {
                        $where .= " AND o.order_status = '$filter[composite_status]' ";
                    }
            }

            // 团购订单
            if ($filter['group_buy_id']) {
                $where .= " AND o.extension_code = 'group_buy' AND o.extension_id = '$filter[group_buy_id]' ";
            }

            // 如果管理员属于某个办事处，只列出这个办事处管辖的订单
            $agency_id = DB::table('admin_user')
                ->where('user_id', Session::get('admin_id'))
                ->value('agency_id');
            if ($agency_id > 0) {
                $where .= " AND o.agency_id = '$agency_id' ";
            }

            // 分页大小
            $filter['page'] = empty($_REQUEST['page']) || (intval($_REQUEST['page']) <= 0) ? 1 : intval($_REQUEST['page']);

            if (isset($_REQUEST['page_size']) && intval($_REQUEST['page_size']) > 0) {
                $filter['page_size'] = intval($_REQUEST['page_size']);
            } else {
                $ecscpCookie = Cookie::get('ECSCP');
                $pageSize = is_array($ecscpCookie) ? ($ecscpCookie['page_size'] ?? '') : '';
                $filter['page_size'] = isset($pageSize) && intval($pageSize) > 0 ? intval($pageSize) : 15;
            }
                $filter['page_size'] = 15;
            }

            // 记录总数
            if ($filter['user_name']) {
                $filter['record_count'] = DB::table('order_info as o')
                    ->join('user as u', 'o.user_id', '=', 'u.user_id')
                    ->whereRaw(ltrim($where, 'WHERE '))
                    ->count();
            } else {
                $filter['record_count'] = DB::table('order_info as o')
                    ->whereRaw(ltrim($where, 'WHERE '))
                    ->count();
            }
            $filter['page_count'] = $filter['record_count'] > 0 ? ceil($filter['record_count'] / $filter['page_size']) : 1;

            // 查询
            $order_query = DB::table('order_info as o')
                ->leftJoin('user as u', 'u.user_id', '=', 'o.user_id')
                ->select(
                    'o.order_id',
                    'o.order_sn',
                    'o.add_time',
                    'o.order_status',
                    'o.shipping_status',
                    'o.order_amount',
                    'o.money_paid',
                    'o.pay_status',
                    'o.consignee',
                    'o.address',
                    'o.email',
                    'o.tel',
                    'o.extension_code',
                    'o.extension_id',
                    DB::raw('('.OrderHelper::order_amount_field('o.').') AS total_fee'),
                    DB::raw("IFNULL(u.user_name, '".lang('anonymous')."') AS buyer")
                )
                ->whereRaw(ltrim($where, 'WHERE '))
                ->orderBy($filter['sort_by'], $filter['sort_order'])
                ->offset(($filter['page'] - 1) * $filter['page_size'])
                ->limit($filter['page_size']);

            $sql = $order_query->toSql();

            foreach (['order_sn', 'consignee', 'email', 'address', 'zipcode', 'tel', 'user_name'] as $val) {
                $filter[$val] = stripslashes($filter[$val]);
            }
            $row = $order_query->get()->toArray();
        } else {
            $sql = $result['sql'];
            $filter = $result['filter'];
            $row = DB::select($sql);
        }

        $row = array_map(function ($item) {
            return (array) $item;
        }, $row);

        // 格式话数据
        foreach ($row as $key => $value) {
            $row[$key]['formated_order_amount'] = CommonHelper::price_format($value['order_amount']);
            $row[$key]['formated_money_paid'] = CommonHelper::price_format($value['money_paid']);
            $row[$key]['formated_total_fee'] = CommonHelper::price_format($value['total_fee']);
            $row[$key]['short_order_time'] = TimeHelper::local_date('m-d H:i', $value['add_time']);
            if ($value['order_status'] === OS_INVALID || $value['order_status'] === OS_CANCELED) {
                // 如果该订单为无效或取消则显示删除链接
                $row[$key]['can_remove'] = 1;
            } else {
                $row[$key]['can_remove'] = 0;
            }
        }
        $arr = ['orders' => $row, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']];

        return $arr;
    }

    /**
     * 更新订单对应的 pay_log
     * 如果未支付，修改支付金额；否则，生成新的支付log
     *
     * @param  int  $order_id  订单id
     */
    private function update_pay_log($order_id)
    {
        $order_id = intval($order_id);
        if ($order_id > 0) {
            $order_amount = DB::table('order_info')
                ->where('order_id', $order_id)
                ->value('order_amount');
            if (! is_null($order_amount)) {
                $log_id = DB::table('order_pay')
                    ->where('order_id', $order_id)
                    ->where('order_type', PAY_ORDER)
                    ->where('is_paid', 0)
                    ->value('log_id');
                $log_id = intval($log_id);
                if ($log_id > 0) {
                    // 未付款，更新支付金额
                    DB::table('order_pay')
                        ->where('log_id', $log_id)
                        ->limit(1)
                        ->update(['order_amount' => $order_amount]);
                } else {
                    // 已付款，生成新的pay_log
                    DB::table('order_pay')->insert([
                        'order_id' => $order_id,
                        'order_amount' => $order_amount,
                        'order_type' => PAY_ORDER,
                        'is_paid' => 0,
                    ]);
                }
            }
        }
    }

    /**
     * 取得供货商列表
     *
     * @return array 二维数组
     */
    private function get_suppliers_list()
    {
        $res = DB::table('supplier')
            ->where('is_check', 1)
            ->orderBy('suppliers_name', 'asc')
            ->get()
            ->toArray();
        $res = array_map(function ($item) {
            return (array) $item;
        }, $res);

        if (! is_array($res)) {
            $res = [];
        }

        return $res;
    }

    /**
     * 取得订单商品
     *
     * @param  array  $order  订单数组
     * @return array
     */
    private function get_order_goods($order)
    {
        $goods_list = [];
        $goods_attr = [];
        $res = DB::table('order_goods as o')
            ->leftJoin('goods_product as p', 'o.product_id', '=', 'p.product_id')
            ->leftJoin('goods as g', 'o.goods_id', '=', 'g.goods_id')
            ->leftJoin('goods_brand as b', 'g.brand_id', '=', 'b.brand_id')
            ->select('o.*', 'g.suppliers_id as suppliers_id', DB::raw('IF(o.product_id > 0, p.product_number, g.goods_number) as storage'), 'o.goods_attr', DB::raw("IFNULL(b.brand_name, '') as brand_name"), 'p.product_sn')
            ->where('o.order_id', $order['order_id'])
            ->get()
            ->toArray();
        foreach ($res as $row) {
            $row = (array) $row;
            $row['formated_subtotal'] = CommonHelper::price_format($row['goods_price'] * $row['goods_number']);
            $row['formated_goods_price'] = CommonHelper::price_format($row['goods_price']);

            $goods_attr[] = explode(' ', trim($row['goods_attr'])); // 将商品属性拆分为一个数组

            if ($row['extension_code'] === 'package_buy') {
                $row['storage'] = '';
                $row['brand_name'] = '';
                $row['package_goods_list'] = $this->get_package_goods_list($row['goods_id']);
            }

            // 处理货品id
            $row['product_id'] = empty($row['product_id']) ? 0 : $row['product_id'];

            $goods_list[] = $row;
        }

        $attr = [];
        $arr = [];
        foreach ($goods_attr as $index => $array_val) {
            foreach ($array_val as $value) {
                $arr = explode(':', $value); // 以 : 号将属性拆开
                $attr[$index][] = @['name' => $arr[0], 'value' => $arr[1]];
            }
        }

        return ['goods_list' => $goods_list, 'attr' => $attr];
    }

    /**
     * 取得礼包列表
     *
     * @param  int  $package_id  订单商品表礼包类商品id
     * @return array
     */
    private function get_package_goods_list($package_id)
    {
        $res = DB::table('activity_package as pg')
            ->leftJoin('goods as g', 'pg.goods_id', '=', 'g.goods_id')
            ->leftJoin('goods_product as p', 'pg.product_id', '=', 'p.product_id')
            ->select('pg.goods_id', 'g.goods_name', DB::raw('(CASE WHEN pg.product_id > 0 THEN p.product_number ELSE g.goods_number END) AS goods_number'), 'p.goods_attr', 'p.product_id', 'pg.goods_number AS order_goods_number', 'g.goods_sn', 'g.is_real', 'p.product_sn')
            ->where('pg.package_id', $package_id)
            ->get()
            ->toArray();
        if (empty($res)) {
            return [];
        }

        $row = [];

        // 生成结果数组 取存在货品的商品id 组合商品id与货品id
        $good_product_str = '';
        foreach ($res as $_row) {
            $_row = (array) $_row;
            if ($_row['product_id'] > 0) {
                // 取存商品id
                $good_product_str .= ','.$_row['goods_id'];

                // 组合商品id与货品id
                $_row['g_p'] = $_row['goods_id'].'_'.$_row['product_id'];
            } else {
                // 组合商品id与货品id
                $_row['g_p'] = $_row['goods_id'];
            }

            // 生成结果数组
            $row[] = $_row;
        }
        $good_product_str = trim($good_product_str, ',');

        // 释放空间
        unset($resource, $_row, $sql);

        // 取商品属性
        if ($good_product_str != '') {
            $result_goods_attr = DB::table('goods_attr as ga')
                ->join('goods_type_attribute as a', 'a.attr_id', '=', 'ga.attr_id')
                ->select('ga.goods_attr_id', 'ga.attr_value', 'ga.attr_price', 'a.attr_name')
                ->where('a.attr_type', 1)
                ->whereRaw("goods_id IN ($good_product_str)")
                ->get()
                ->toArray();
            $result_goods_attr = array_map(function ($item) {
                return (array) $item;
            }, $result_goods_attr);

            $_goods_attr = [];
            foreach ($result_goods_attr as $value) {
                $_goods_attr[$value['goods_attr_id']] = $value;
            }
        }

        // 过滤货品
        $format[0] = '%s:%s[%d] <br>';
        $format[1] = '%s--[%d]';
        foreach ($row as $key => $value) {
            if ($value['goods_attr'] != '') {
                $goods_attr_array = explode('|', $value['goods_attr']);

                $goods_attr = [];
                foreach ($goods_attr_array as $_attr) {
                    $goods_attr[] = sprintf($format[0], $_goods_attr[$_attr]['attr_name'], $_goods_attr[$_attr]['attr_value'], $_goods_attr[$_attr]['attr_price']);
                }

                $row[$key]['goods_attr_str'] = implode('', $goods_attr);
            }

            $row[$key]['goods_name'] = sprintf($format[1], $value['goods_name'], $value['order_goods_number']);
        }

        return $row;

        //    $sql = "SELECT pg.goods_id, CONCAT(g.goods_name, ' -- [', pg.goods_number, ']') AS goods_name,
        //            g.goods_number, pg.goods_number AS order_goods_number, g.goods_sn, g.is_real " .
        //            "FROM " . ecs()->table('activity_package') . " AS pg, " .
        //                ecs()->table('goods') . " AS g " .
        //            "WHERE pg.package_id = '$package_id' " .
        //            "AND pg.goods_id = g.goods_id ";
        //    $row = db()->getAll($sql);
        //
        //    return $row;
    }

    /**
     * 订单单个商品或货品的已发货数量
     *
     * @param  int  $order_id  订单 id
     * @param  int  $goods_id  商品 id
     * @param  int  $product_id  货品 id
     * @return int
     */
    private function order_delivery_num($order_id, $goods_id, $product_id = 0)
    {
        $query = DB::table('order_delivery_goods as G')
            ->join('order_delivery_order as O', 'O.delivery_id', '=', 'G.delivery_id')
            ->where('O.status', 0)
            ->where('O.order_id', $order_id)
            ->where('G.extension_code', '<>', 'package_buy')
            ->where('G.goods_id', $goods_id);

        if ($product_id > 0) {
            $query->where('G.product_id', $product_id);
        }

        $sum = $query->sum('G.send_number');

        return $sum ?: 0;
    }

    /**
     * 判断订单是否已发货（含部分发货）
     *
     * @param  int  $order_id  订单 id
     * @return int 1，已发货；0，未发货
     */
    private function order_deliveryed($order_id)
    {
        $return_res = 0;

        if (empty($order_id)) {
            return $return_res;
        }

        $sum = DB::table('order_delivery_order')
            ->where('order_id', $order_id)
            ->where('status', 0)
            ->count('delivery_id');

        if ($sum) {
            $return_res = 1;
        }

        return $return_res;
    }

    /**
     * 更新订单商品信息
     *
     * @param  int  $order_id  订单 id
     * @param  array  $_sended  Array(‘商品id’ => ‘此单发货数量’)
     * @param  array  $goods_list
     * @return bool
     */
    private function update_order_goods($order_id, $_sended, $goods_list = [])
    {
        if (! is_array($_sended) || empty($order_id)) {
            return false;
        }

        foreach ($_sended as $key => $value) {
            // 超值礼包
            if (is_array($value)) {
                if (! is_array($goods_list)) {
                    $goods_list = [];
                }

                foreach ($goods_list as $goods) {
                    if (($key != $goods['rec_id']) || (! isset($goods['package_goods_list']) || ! is_array($goods['package_goods_list']))) {
                        continue;
                    }

                    $goods['package_goods_list'] = $this->package_goods($goods['package_goods_list'], $goods['goods_number'], $goods['order_id'], $goods['extension_code'], $goods['goods_id']);
                    $pg_is_end = true;

                    foreach ($goods['package_goods_list'] as $pg_key => $pg_value) {
                        if ($pg_value['order_send_number'] != $pg_value['sended']) {
                            $pg_is_end = false; // 此超值礼包，此商品未全部发货

                            break;
                        }
                    }

                    // 超值礼包商品全部发货后更新订单商品库存
                    if ($pg_is_end) {
                        DB::table('order_goods')
                            ->where('order_id', $order_id)
                            ->where('goods_id', $goods['goods_id'])
                            ->update(['send_number' => DB::raw('goods_number')]);
                    }
                }
            } // 商品（实货）（货品）
            elseif (! is_array($value)) {
                // 检查是否为商品（实货）（货品）
                foreach ($goods_list as $goods) {
                    if ($goods['rec_id'] === $key && $goods['is_real'] === 1) {
                        DB::table('order_goods')
                            ->where('order_id', $order_id)
                            ->where('rec_id', $key)
                            ->increment('send_number', $value);
                        break;
                    }
                }
            }
        }

        return true;
    }

    /**
     * 更新订单虚拟商品信息
     *
     * @param  int  $order_id  订单 id
     * @param  array  $_sended  Array(‘商品id’ => ‘此单发货数量’)
     * @param  array  $virtual_goods  虚拟商品列表
     * @return bool
     */
    private function update_order_virtual_goods($order_id, $_sended, $virtual_goods)
    {
        if (! is_array($_sended) || empty($order_id)) {
            return false;
        }
        if (empty($virtual_goods)) {
            return true;
        } elseif (! is_array($virtual_goods)) {
            return false;
        }

        foreach ($virtual_goods as $goods) {
            DB::table('order_goods')
                ->where('order_id', $order_id)
                ->where('goods_id', $goods['goods_id'])
                ->increment('send_number', $goods['num']);
        }

        return true;
    }

    /**
     * 订单中的商品是否已经全部发货
     *
     * @param  int  $order_id  订单 id
     * @return int 1，全部发货；0，未全部发货
     */
    private function get_order_finish($order_id)
    {
        $return_res = 0;

        if (empty($order_id)) {
            return $return_res;
        }

        $sum = DB::table('order_goods')
            ->where('order_id', $order_id)
            ->whereRaw('goods_number > send_number')
            ->count('rec_id');
        if (empty($sum)) {
            $return_res = 1;
        }

        return $return_res;
    }

    /**
     * 判断订单的发货单是否全部发货
     *
     * @param  int  $order_id  订单 id
     * @return int 1，全部发货；0，未全部发货；-1，部分发货；-2，完全没发货；
     */
    private function get_all_delivery_finish($order_id)
    {
        $return_res = 0;

        if (empty($order_id)) {
            return $return_res;
        }

        // 未全部分单
        if (! $this->get_order_finish($order_id)) {
            return $return_res;
        } // 已全部分单
        else {
            // 是否全部发货
            $sum = DB::table('order_delivery_order')
                ->where('order_id', $order_id)
                ->where('status', 2)
                ->count('delivery_id');
            // 全部发货
            if (empty($sum)) {
                $return_res = 1;
            } // 未全部发货
            else {
                // 订单全部发货中时：当前发货单总数
                $sum_non_returned = DB::table('order_delivery_order')
                    ->where('order_id', $order_id)
                    ->where('status', '<>', 1)
                    ->count('delivery_id');
                if ($sum_non_returned === $sum) {
                    $return_res = -2; // 完全没发货
                } else {
                    $return_res = -1; // 部分发货
                }
            }
        }

        return $return_res;
    }

    private function trim_array_walk(&$array_value)
    {
        if (is_array($array_value)) {
            array_walk($array_value, 'trim_array_walk');
        } else {
            $array_value = trim($array_value);
        }
    }

    private function intval_array_walk(&$array_value)
    {
        if (is_array($array_value)) {
            array_walk($array_value, 'intval_array_walk');
        } else {
            $array_value = intval($array_value);
        }
    }

    /**
     * 删除发货单(不包括已退货的单子)
     *
     * @param  int  $order_id  订单 id
     * @return int 1，成功；0，失败
     */
    private function del_order_delivery($order_id)
    {
        $return_res = 0;

        if (empty($order_id)) {
            return $return_res;
        }

        $delivery_ids = DB::table('order_delivery_order')
            ->where('order_id', $order_id)
            ->where('status', 0)
            ->pluck('delivery_id')
            ->toArray();

        if (! empty($delivery_ids)) {
            DB::table('order_delivery_goods')->whereIn('delivery_id', $delivery_ids)->delete();
            DB::table('order_delivery_order')->whereIn('delivery_id', $delivery_ids)->delete();
            $return_res = 1;
        }

        return $return_res;
    }

    /**
     * 删除订单所有相关单子
     *
     * @param  int  $order_id  订单 id
     * @param  array  $action_array  操作列表 Array('delivery', 'back', ......)
     * @return int 1，成功；0，失败
     */
    private function del_delivery($order_id, array $action_array)
    {
        $return_res = 0;

        if (empty($order_id) || empty($action_array)) {
            return $return_res;
        }

        $query_delivery = 1;
        $query_back = 1;
        if (in_array('delivery', $action_array)) {
            $delivery_ids = DB::table('order_delivery_order')
                ->where('order_id', $order_id)
                ->pluck('delivery_id')
                ->toArray();
            if (! empty($delivery_ids)) {
                DB::table('order_delivery_goods')->whereIn('delivery_id', $delivery_ids)->delete();
                DB::table('order_delivery_order')->whereIn('delivery_id', $delivery_ids)->delete();
            }
            $query_delivery = 1;
        }
        if (in_array('back', $action_array)) {
            $back_ids = DB::table('order_back_order')
                ->where('order_id', $order_id)
                ->pluck('back_id')
                ->toArray();
            if (! empty($back_ids)) {
                DB::table('order_back_goods')->whereIn('back_id', $back_ids)->delete();
                DB::table('order_back_order')->whereIn('back_id', $back_ids)->delete();
            }
            $query_back = 1;
        }

        if ($query_delivery && $query_back) {
            $return_res = 1;
        }

        return $return_res;
    }

    /**
     *  获取发货单列表信息
     */
    private function delivery_list(): array
    {
        $result = MainHelper::get_filter();
        if ($result === false) {
            $aiax = isset($_GET['is_ajax']) ? $_GET['is_ajax'] : 0;

            // 过滤信息
            $filter['delivery_sn'] = empty($_REQUEST['delivery_sn']) ? '' : trim($_REQUEST['delivery_sn']);
            $filter['order_sn'] = empty($_REQUEST['order_sn']) ? '' : trim($_REQUEST['order_sn']);
            $filter['order_id'] = empty($_REQUEST['order_id']) ? 0 : intval($_REQUEST['order_id']);
            if ($aiax === 1 && ! empty($_REQUEST['consignee'])) {
                $_REQUEST['consignee'] = BaseHelper::json_str_iconv($_REQUEST['consignee']);
            }
            $filter['consignee'] = empty($_REQUEST['consignee']) ? '' : trim($_REQUEST['consignee']);
            $filter['status'] = isset($_REQUEST['status']) ? $_REQUEST['status'] : -1;

            $filter['sort_by'] = empty($_REQUEST['sort_by']) ? 'update_time' : trim($_REQUEST['sort_by']);
            $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);

            $where = 'WHERE 1 ';
            if ($filter['order_sn']) {
                $where .= " AND order_sn LIKE '%".BaseHelper::mysql_like_quote($filter['order_sn'])."%'";
            }
            if ($filter['consignee']) {
                $where .= " AND consignee LIKE '%".BaseHelper::mysql_like_quote($filter['consignee'])."%'";
            }
            if ($filter['status'] >= 0) {
                $where .= " AND status = '".BaseHelper::mysql_like_quote($filter['status'])."'";
            }
            if ($filter['delivery_sn']) {
                $where .= " AND delivery_sn LIKE '%".BaseHelper::mysql_like_quote($filter['delivery_sn'])."%'";
            }

            // 获取管理员信息
            $admin_info = MainHelper::admin_info();

            // 如果管理员属于某个办事处，只列出这个办事处管辖的发货单
            if ($admin_info['agency_id'] > 0) {
                $where .= " AND agency_id = '".$admin_info['agency_id']."' ";
            }

            // 如果管理员属于某个供货商，只列出这个供货商的发货单
            if ($admin_info['suppliers_id'] > 0) {
                $where .= " AND suppliers_id = '".$admin_info['suppliers_id']."' ";
            }

            // 分页大小
            $filter['page'] = empty($_REQUEST['page']) || (intval($_REQUEST['page']) <= 0) ? 1 : intval($_REQUEST['page']);

            if (isset($_REQUEST['page_size']) && intval($_REQUEST['page_size']) > 0) {
                $filter['page_size'] = intval($_REQUEST['page_size']);
            } else {
                $ecscpCookie = Cookie::get('ECSCP');
                $pageSize = is_array($ecscpCookie) ? ($ecscpCookie['page_size'] ?? '') : '';
                $filter['page_size'] = isset($pageSize) && intval($pageSize) > 0 ? intval($pageSize) : 15;
            }
                $filter['page_size'] = 15;
            }

            // 记录总数
            $filter['record_count'] = DB::table('order_delivery_order')
                ->whereRaw(ltrim($where, 'WHERE '))
                ->count();
            $filter['page_count'] = $filter['record_count'] > 0 ? ceil($filter['record_count'] / $filter['page_size']) : 1;

            // 查询
            $delivery_query = DB::table('order_delivery_order')
                ->select('delivery_id', 'delivery_sn', 'order_sn', 'order_id', 'add_time', 'action_user', 'consignee', 'country', 'province', 'city', 'district', 'tel', 'status', 'update_time', 'email', 'suppliers_id')
                ->whereRaw(ltrim($where, 'WHERE '))
                ->orderBy($filter['sort_by'], $filter['sort_order'])
                ->offset(($filter['page'] - 1) * $filter['page_size'])
                ->limit($filter['page_size']);

            $sql = $delivery_query->toSql();

            MainHelper::set_filter($filter, $sql);
        } else {
            $sql = $result['sql'];
            $filter = $result['filter'];
        }

        // 获取供货商列表
        $suppliers_list = $this->get_suppliers_list();
        $_suppliers_list = [];
        foreach ($suppliers_list as $value) {
            $_suppliers_list[$value['suppliers_id']] = $value['suppliers_name'];
        }

        if ($result === false) {
            $row = $delivery_query->get()->toArray();
        } else {
            $row = DB::select($sql);
        }
        $row = array_map(function ($item) {
            return (array) $item;
        }, $row);

        // 格式化数据
        foreach ($row as $key => $value) {
            $row[$key]['add_time'] = TimeHelper::local_date(cfg('time_format'), $value['add_time']);
            $row[$key]['update_time'] = TimeHelper::local_date(cfg('time_format'), $value['update_time']);
            if ($value['status'] === 1) {
                $row[$key]['status_name'] = lang('delivery_status')[1];
            } elseif ($value['status'] === 2) {
                $row[$key]['status_name'] = lang('delivery_status')[2];
            } else {
                $row[$key]['status_name'] = lang('delivery_status')[0];
            }
            $row[$key]['suppliers_name'] = isset($_suppliers_list[$value['suppliers_id']]) ? $_suppliers_list[$value['suppliers_id']] : '';
        }
        $arr = ['delivery' => $row, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']];

        return $arr;
    }

    /**
     *  获取退货单列表信息
     */
    private function back_list(): array
    {
        $result = MainHelper::get_filter();
        if ($result === false) {
            $aiax = isset($_GET['is_ajax']) ? $_GET['is_ajax'] : 0;

            // 过滤信息
            $filter['delivery_sn'] = empty($_REQUEST['delivery_sn']) ? '' : trim($_REQUEST['delivery_sn']);
            $filter['order_sn'] = empty($_REQUEST['order_sn']) ? '' : trim($_REQUEST['order_sn']);
            $filter['order_id'] = empty($_REQUEST['order_id']) ? 0 : intval($_REQUEST['order_id']);
            if ($aiax === 1 && ! empty($_REQUEST['consignee'])) {
                $_REQUEST['consignee'] = BaseHelper::json_str_iconv($_REQUEST['consignee']);
            }
            $filter['consignee'] = empty($_REQUEST['consignee']) ? '' : trim($_REQUEST['consignee']);

            $filter['sort_by'] = empty($_REQUEST['sort_by']) ? 'update_time' : trim($_REQUEST['sort_by']);
            $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);

            $where = 'WHERE 1 ';
            if ($filter['order_sn']) {
                $where .= " AND order_sn LIKE '%".BaseHelper::mysql_like_quote($filter['order_sn'])."%'";
            }
            if ($filter['consignee']) {
                $where .= " AND consignee LIKE '%".BaseHelper::mysql_like_quote($filter['consignee'])."%'";
            }
            if ($filter['delivery_sn']) {
                $where .= " AND delivery_sn LIKE '%".BaseHelper::mysql_like_quote($filter['delivery_sn'])."%'";
            }

            // 获取管理员信息
            $admin_info = MainHelper::admin_info();

            // 如果管理员属于某个办事处，只列出这个办事处管辖的发货单
            if ($admin_info['agency_id'] > 0) {
                $where .= " AND agency_id = '".$admin_info['agency_id']."' ";
            }

            // 如果管理员属于某个供货商，只列出这个供货商的发货单
            if ($admin_info['suppliers_id'] > 0) {
                $where .= " AND suppliers_id = '".$admin_info['suppliers_id']."' ";
            }

            // 分页大小
            $filter['page'] = empty($_REQUEST['page']) || (intval($_REQUEST['page']) <= 0) ? 1 : intval($_REQUEST['page']);

            if (isset($_REQUEST['page_size']) && intval($_REQUEST['page_size']) > 0) {
                $filter['page_size'] = intval($_REQUEST['page_size']);
            } else {
                $ecscpCookie = Cookie::get('ECSCP');
                $pageSize = is_array($ecscpCookie) ? ($ecscpCookie['page_size'] ?? '') : '';
                $filter['page_size'] = isset($pageSize) && intval($pageSize) > 0 ? intval($pageSize) : 15;
            }
                $filter['page_size'] = 15;
            }

            // 记录总数
            $filter['record_count'] = DB::table('order_back_order')
                ->whereRaw(ltrim($where, 'WHERE '))
                ->count();
            $filter['page_count'] = $filter['record_count'] > 0 ? ceil($filter['record_count'] / $filter['page_size']) : 1;

            // 查询
            $back_query = DB::table('order_back_order')
                ->select('back_id', 'delivery_sn', 'order_sn', 'order_id', 'add_time', 'action_user', 'consignee', 'country', 'province', 'city', 'district', 'tel', 'status', 'update_time', 'email', 'return_time')
                ->whereRaw(ltrim($where, 'WHERE '))
                ->orderBy($filter['sort_by'], $filter['sort_order'])
                ->offset(($filter['page'] - 1) * $filter['page_size'])
                ->limit($filter['page_size']);

            $sql = $back_query->toSql();

            MainHelper::set_filter($filter, $sql);
        } else {
            $sql = $result['sql'];
            $filter = $result['filter'];
        }

        if ($result === false) {
            $row = $back_query->get()->toArray();
        } else {
            $row = DB::select($sql);
        }
        $row = array_map(function ($item) {
            return (array) $item;
        }, $row);

        // 格式化数据
        foreach ($row as $key => $value) {
            $row[$key]['return_time'] = TimeHelper::local_date(cfg('time_format'), $value['return_time']);
            $row[$key]['add_time'] = TimeHelper::local_date(cfg('time_format'), $value['add_time']);
            $row[$key]['update_time'] = TimeHelper::local_date(cfg('time_format'), $value['update_time']);
            if ($value['status'] === 1) {
                $row[$key]['status_name'] = lang('delivery_status')[1];
            } else {
                $row[$key]['status_name'] = lang('delivery_status')[0];
            }
        }
        $arr = ['back' => $row, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']];

        return $arr;
    }

    /**
     * 取得发货单信息
     *
     * @param  int  $delivery_order  发货单id（如果delivery_order > 0 就按id查，否则按sn查）
     * @param  string  $delivery_sn  发货单号
     * @return array 发货单信息（金额都有相应格式化的字段，前缀是formated_）
     */
    private function delivery_order_info($delivery_id, $delivery_sn = '')
    {
        $return_order = [];
        if (empty($delivery_id) || ! is_numeric($delivery_id)) {
            return $return_order;
        }

        $where = '';
        // 获取管理员信息
        $admin_info = MainHelper::admin_info();

        // 如果管理员属于某个办事处，只列出这个办事处管辖的发货单
        if ($admin_info['agency_id'] > 0) {
            $where .= " AND agency_id = '".$admin_info['agency_id']."' ";
        }

        // 如果管理员属于某个供货商，只列出这个供货商的发货单
        if ($admin_info['suppliers_id'] > 0) {
            $where .= " AND suppliers_id = '".$admin_info['suppliers_id']."' ";
        }

        $query = DB::table('order_delivery_order');
        if ($delivery_id > 0) {
            $query->where('delivery_id', $delivery_id);
        } else {
            $query->where('delivery_sn', $delivery_sn);
        }

        if ($where != '') {
            $query->whereRaw(ltrim($where, ' AND '));
        }

        $delivery = $query->first();
        $delivery = $delivery ? (array) $delivery : [];
        if ($delivery) {
            // 格式化金额字段
            $delivery['formated_insure_fee'] = CommonHelper::price_format($delivery['insure_fee'], false);
            $delivery['formated_shipping_fee'] = CommonHelper::price_format($delivery['shipping_fee'], false);

            // 格式化时间字段
            $delivery['formated_add_time'] = TimeHelper::local_date(cfg('time_format'), $delivery['add_time']);
            $delivery['formated_update_time'] = TimeHelper::local_date(cfg('time_format'), $delivery['update_time']);

            $return_order = $delivery;
        }

        return $return_order;
    }

    /**
     * 取得退货单信息
     *
     * @param  int  $back_id  退货单 id（如果 back_id > 0 就按 id 查，否则按 sn 查）
     * @return array 退货单信息（金额都有相应格式化的字段，前缀是 formated_ ）
     */
    private function back_order_info($back_id)
    {
        $return_order = [];
        if (empty($back_id) || ! is_numeric($back_id)) {
            return $return_order;
        }

        $where = '';
        // 获取管理员信息
        $admin_info = MainHelper::admin_info();

        // 如果管理员属于某个办事处，只列出这个办事处管辖的发货单
        if ($admin_info['agency_id'] > 0) {
            $where .= " AND agency_id = '".$admin_info['agency_id']."' ";
        }

        // 如果管理员属于某个供货商，只列出这个供货商的发货单
        if ($admin_info['suppliers_id'] > 0) {
            $where .= " AND suppliers_id = '".$admin_info['suppliers_id']."' ";
        }

        $query = DB::table('order_back_order')
            ->where('back_id', $back_id);

        if ($where != '') {
            $query->whereRaw(ltrim($where, ' AND '));
        }

        $back = $query->first();
        $back = $back ? (array) $back : [];
        if ($back) {
            // 格式化金额字段
            $back['formated_insure_fee'] = CommonHelper::price_format($back['insure_fee'], false);
            $back['formated_shipping_fee'] = CommonHelper::price_format($back['shipping_fee'], false);

            // 格式化时间字段
            $back['formated_add_time'] = TimeHelper::local_date(cfg('time_format'), $back['add_time']);
            $back['formated_update_time'] = TimeHelper::local_date(cfg('time_format'), $back['update_time']);
            $back['formated_return_time'] = TimeHelper::local_date(cfg('time_format'), $back['return_time']);

            $return_order = $back;
        }

        return $return_order;
    }

    /**
     * 超级礼包发货数处理
     *
     * @param array   超级礼包商品列表
     * @param int     发货数量
     * @param int     订单ID
     * @param varchar 虚拟代码
     * @param int     礼包ID
     * @return array 格式化结果
     */
    private function package_goods(&$package_goods, $goods_number, $order_id, $extension_code, $package_id)
    {
        $return_array = [];

        if (count($package_goods) === 0 || ! is_numeric($goods_number)) {
            return $return_array;
        }

        foreach ($package_goods as $key => $value) {
            $return_array[$key] = $value;
            $return_array[$key]['order_send_number'] = $value['order_goods_number'] * $goods_number;
            $return_array[$key]['sended'] = $this->package_sended($package_id, $value['goods_id'], $order_id, $extension_code, $value['product_id']);
            $return_array[$key]['send'] = ($value['order_goods_number'] * $goods_number) - $return_array[$key]['sended'];
            $return_array[$key]['storage'] = $value['goods_number'];

            if ($return_array[$key]['send'] <= 0) {
                $return_array[$key]['send'] = lang('act_good_delivery');
                $return_array[$key]['readonly'] = 'readonly="readonly"';
            }

            // 是否缺货
            if ($return_array[$key]['storage'] <= 0 && cfg('use_storage') === '1') {
                $return_array[$key]['send'] = lang('act_good_vacancy');
                $return_array[$key]['readonly'] = 'readonly="readonly"';
            }
        }

        return $return_array;
    }

    /**
     * 获取超级礼包商品已发货数
     *
     * @param  int  $package_id  礼包ID
     * @param  int  $goods_id  礼包的产品ID
     * @param  string  $extension_code  虚拟代码
     * @param  int  $product_id  货品id
     * @return int 数值
     */
    private function package_sended($package_id, $goods_id, $order_id, $extension_code, $product_id = 0): int
    {
        if (empty($package_id) || empty($goods_id) || empty($order_id) || empty($extension_code)) {
            return 0;
        }

        $query = DB::table('order_delivery_goods as DG')
            ->join('order_delivery_order as o', 'o.delivery_id', '=', 'DG.delivery_id')
            ->whereIn('o.status', [0, 2])
            ->where('o.order_id', $order_id)
            ->where('DG.parent_id', $package_id)
            ->where('DG.goods_id', $goods_id)
            ->where('DG.extension_code', $extension_code);

        if ($product_id > 0) {
            $query->where('DG.product_id', $product_id);
        }

        $send = $query->sum('DG.send_number');

        return $send ?: 0;
    }

    /**
     * 改变订单中商品库存
     *
     * @param  int  $order_id  订单 id
     * @param  array  $_sended  Array(‘商品id’ => ‘此单发货数量’)
     * @param  array  $goods_list
     * @return bool
     */
    private function change_order_goods_storage_split($order_id, $_sended, $goods_list = [])
    {
        // 参数检查
        if (! is_array($_sended) || empty($order_id)) {
            return false;
        }

        foreach ($_sended as $key => $value) {
            // 商品（超值礼包）
            if (is_array($value)) {
                if (! is_array($goods_list)) {
                    $goods_list = [];
                }
                foreach ($goods_list as $goods) {
                    if (($key != $goods['rec_id']) || (! isset($goods['package_goods_list']) || ! is_array($goods['package_goods_list']))) {
                        continue;
                    }

                    // 超值礼包无库存，只减超值礼包商品库存
                    foreach ($goods['package_goods_list'] as $package_goods) {
                        if (! isset($value[$package_goods['goods_id']])) {
                            continue;
                        }

                        // 减库存：商品（超值礼包）（实货）、商品（超值礼包）（虚货）
                        DB::table('goods')
                            ->where('goods_id', $package_goods['goods_id'])
                            ->decrement('goods_number', $value[$package_goods['goods_id']]);
                    }
                }
            } // 商品（实货）
            elseif (! is_array($value)) {
                // 检查是否为商品（实货）
                foreach ($goods_list as $goods) {
                    if ($goods['rec_id'] === $key && $goods['is_real'] === 1) {
                        DB::table('goods')
                            ->where('goods_id', $goods['goods_id'])
                            ->decrement('goods_number', $value);
                        break;
                    }
                }
            }
        }

        return true;
    }

    /**
     *  超值礼包虚拟卡发货、跳过修改订单商品发货数的虚拟卡发货
     *
     * @param  array  $goods  超值礼包虚拟商品列表数组
     * @param  string  $order_sn  本次操作的订单
     */
    private function package_virtual_card_shipping($goods, $order_sn): bool
    {
        if (! is_array($goods)) {
            return false;
        }

        // 包含加密解密函数所在文件

        // 取出超值礼包中的虚拟商品信息
        foreach ($goods as $virtual_goods_key => $virtual_goods_value) {
            // 取出卡片信息
            $arr = DB::table('goods_virtual_card')
                ->select('card_id', 'card_sn', 'card_password', 'end_date', 'crc32')
                ->where('goods_id', $virtual_goods_value['goods_id'])
                ->where('is_saled', 0)
                ->limit($virtual_goods_value['num'])
                ->get()
                ->toArray();
            $arr = array_map(function ($item) {
                return (array) $item;
            }, $arr);
            // 判断是否有库存 没有则推出循环
            if (count($arr) === 0) {
                continue;
            }

            $card_ids = [];
            $cards = [];

            foreach ($arr as $virtual_card) {
                $card_info = [];

                // 卡号和密码解密
                if ($virtual_card['crc32'] === 0 || $virtual_card['crc32'] === crc32(AUTH_KEY)) {
                    $card_info['card_sn'] = CodeHelper::decrypt($virtual_card['card_sn']);
                    $card_info['card_password'] = CodeHelper::decrypt($virtual_card['card_password']);
                } elseif ($virtual_card['crc32'] === crc32(OLD_AUTH_KEY)) {
                    $card_info['card_sn'] = CodeHelper::decrypt($virtual_card['card_sn'], OLD_AUTH_KEY);
                    $card_info['card_password'] = CodeHelper::decrypt($virtual_card['card_password'], OLD_AUTH_KEY);
                } else {
                    return false;
                }
                $card_info['end_date'] = date(cfg('date_format'), $virtual_card['end_date']);
                $card_ids[] = $virtual_card['card_id'];
                $cards[] = $card_info;
            }

            // 标记已经取出的卡片
            DB::table('goods_virtual_card')
                ->whereIn('card_id', $card_ids)
                ->update([
                    'is_saled' => 1,
                    'order_sn' => $order_sn,
                ]);

            // 获取订单信息
            $order = DB::table('order_info')
                ->select('order_id', 'order_sn', 'consignee', 'email')
                ->where('order_sn', $order_sn)
                ->first();
            $order = $order ? (array) $order : [];

            $cfg = cfg('send_ship_email');
            if ($cfg === '1') {
                // 发送邮件
                $this->assign('virtual_card', $cards);
                $this->assign('order', $order);
                $this->assign('goods', $virtual_goods_value);

                $this->assign('send_time', date('Y-m-d H:i:s'));
                $this->assign('shop_name', cfg('shop_name'));
                $this->assign('send_date', date('Y-m-d'));
                $this->assign('sent_date', date('Y-m-d'));

                $tpl = CommonHelper::get_mail_template('virtual_card');
                $content = $this->fetch('str:'.$tpl['template_content']);
                BaseHelper::send_mail($order['consignee'], $order['email'], $tpl['template_subject'], $content, $tpl['is_html']);
            }
        }

        return true;
    }

    /**
     * 删除发货单时进行退货
     *
     * @param  int  $delivery_id  发货单id
     * @param  array  $delivery_order  发货单信息数组
     * @return void
     */
    private function delivery_return_goods($delivery_id, $delivery_order)
    {
        // 查询：取得发货单商品
        $goods_list = DB::table('order_delivery_goods')
            ->where('delivery_id', $delivery_order['delivery_id'])
            ->get()
            ->toArray();
        $goods_list = array_map(function ($item) {
            return (array) $item;
        }, $goods_list);
        // 更新：
        foreach ($goods_list as $val) {
            DB::table('order_goods')
                ->where('order_id', $delivery_order['order_id'])
                ->where('goods_id', $val['goods_id'])
                ->limit(1)
                ->decrement('send_number', $val['send_number']);
        }
        DB::table('order_info')
            ->where('order_id', $delivery_order['order_id'])
            ->limit(1)
            ->update([
                'shipping_status' => SS_UNSHIPPED,
                'order_status' => OS_CONFIRMED,
            ]);
    }

    /**
     * 删除发货单时删除其在订单中的发货单号
     *
     * @param  int  $order_id  定单id
     * @param  string  $delivery_invoice_no  发货单号
     * @return void
     */
    private function del_order_invoice_no($order_id, $delivery_invoice_no)
    {
        // 查询：取得订单中的发货单号
        $order_invoice_no = DB::table('order_info')
            ->where('order_id', $order_id)
            ->value('invoice_no');

        // 如果为空就结束处理
        if (empty($order_invoice_no)) {
            return;
        }

        // 去除当前发货单号
        $order_array = explode('<br>', $order_invoice_no);
        $delivery_array = explode('<br>', $delivery_invoice_no);

        foreach ($order_array as $key => $invoice_no) {
            if ($ii = array_search($invoice_no, $delivery_array)) {
                unset($order_array[$key], $delivery_array[$ii]);
            }
        }

        $arr['invoice_no'] = implode('<br>', $order_array);
        OrderHelper::update_order($order_id, $arr);
    }

    /**
     * 获取站点根目录网址
     */
    private function get_site_root_url(): string
    {
        return 'http://'.$_SERVER['HTTP_HOST'].str_replace('/'.ADMIN_PATH.'/order.php', '', PHP_SELF);
    }
}
