<?php

declare(strict_types=1);

namespace App\Modules\Web\Controllers;

use App\Helpers\BaseHelper;
use App\Helpers\ClipsHelper;
use App\Helpers\CommonHelper;
use App\Helpers\GoodsHelper;
use App\Helpers\InsertHelper;
use App\Helpers\MainHelper;
use App\Helpers\OrderHelper;
use App\Helpers\PassportHelper;
use App\Helpers\TimeHelper;
use App\Helpers\TransactionHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class FlowController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        // 载入语言文件
        lang([
            ROOT_PATH.'languages/'.cfg('lang').'/user.php',
            ROOT_PATH.'languages/'.cfg('lang').'/shopping_flow.php',
        ]);

        if (! isset($_REQUEST['step'])) {
            $_REQUEST['step'] = 'cart';
        }

        $this->assign_template();
        $this->assign_dynamic('flow');
        $position = $this->assign_ur_here(0, lang('shopping_flow'));
        $this->assign('page_title', $position['title']);    // 页面标题
        $this->assign('ur_here', $position['ur_here']);  // 当前位置

        $this->assign('categories', GoodsHelper::get_categories_tree()); // 分类树
        $this->assign('helps', MainHelper::get_shop_help());       // 网店帮助

        $this->assign('show_marketprice', cfg('show_marketprice'));
        $this->assign('data_dir', DATA_DIR);       // 数据目录

        /**
         * 添加商品到购物车
         */
        if ($_REQUEST['step'] === 'add_to_cart') {
            $_POST['goods'] = strip_tags(urldecode($_POST['goods']));
            $_POST['goods'] = BaseHelper::json_str_iconv($_POST['goods']);

            if (! empty($_REQUEST['goods_id']) && empty($_POST['goods'])) {
                if (! is_numeric($_REQUEST['goods_id']) || intval($_REQUEST['goods_id']) <= 0) {
                    return response()->redirectTo('/');
                }
                $goods_id = intval($_REQUEST['goods_id']);
                exit;
            }

            $result = ['error' => 0, 'message' => '', 'content' => '', 'goods_id' => ''];

            if (empty($_POST['goods'])) {
                $result['error'] = 1;

                return response()->json($result);
            }

            $goods = json_decode($_POST['goods']);

            // 检查：如果商品有规格，而post的数据没有规格，把商品的规格属性通过JSON传到前台
            if (empty($goods->spec) and empty($goods->quick)) {
                $res = DB::table('goods_attr as g')
                    ->select('a.attr_id', 'a.attr_name', 'a.attr_type', 'g.goods_attr_id', 'g.attr_value', 'g.attr_price')
                    ->leftJoin('goods_type_attribute as a', 'a.attr_id', '=', 'g.attr_id')
                    ->where('a.attr_type', '<>', 0)
                    ->where('g.goods_id', $goods->goods_id)
                    ->orderBy('a.sort_order')
                    ->orderBy('g.attr_price')
                    ->orderBy('g.goods_attr_id')
                    ->get()
                    ->map(fn ($item) => (array) $item)
                    ->all();

                if (! empty($res)) {
                    $spe_arr = [];
                    foreach ($res as $row) {
                        $spe_arr[$row['attr_id']]['attr_type'] = $row['attr_type'];
                        $spe_arr[$row['attr_id']]['name'] = $row['attr_name'];
                        $spe_arr[$row['attr_id']]['attr_id'] = $row['attr_id'];
                        $spe_arr[$row['attr_id']]['values'][] = [
                            'label' => $row['attr_value'],
                            'price' => $row['attr_price'],
                            'format_price' => CommonHelper::price_format($row['attr_price'], false),
                            'id' => $row['goods_attr_id'],
                        ];
                    }
                    $i = 0;
                    $spe_array = [];
                    foreach ($spe_arr as $row) {
                        $spe_array[] = $row;
                    }
                    $result['error'] = ERR_NEED_SELECT_ATTR;
                    $result['goods_id'] = $goods->goods_id;
                    $result['parent'] = $goods->parent;
                    $result['message'] = $spe_array;

                    return response()->json($result);
                }
            }

            // 更新：如果是一步购物，先清空购物车
            if (cfg('one_step_buy') === '1') {
                OrderHelper::clear_cart();
            }

            // 检查：商品数量是否合法
            if (! is_numeric($goods->number) || intval($goods->number) <= 0) {
                $result['error'] = 1;
                $result['message'] = lang('invalid_number');
            } // 更新：购物车
            else {
                if (! empty($goods->spec)) {
                    foreach ($goods->spec as $key => $val) {
                        $goods->spec[$key] = intval($val);
                    }
                }
                // 更新：添加到购物车
                if (OrderHelper::addto_cart($goods->goods_id, $goods->number, $goods->spec, $goods->parent)) {
                    if (cfg('cart_confirm') > 2) {
                        $result['message'] = '';
                    } else {
                        $result['message'] = cfg('cart_confirm') === 1 ? lang('addto_cart_success_1') : lang('addto_cart_success_2');
                    }

                    $result['content'] = InsertHelper::insert_cart_info();
                    $result['one_step_buy'] = cfg('one_step_buy');
                } else {
                    $result['message'] = $err->last_message();
                    $result['error'] = $err->error_no;
                    $result['goods_id'] = stripslashes($goods->goods_id);
                    if (is_array($goods->spec)) {
                        $result['product_spec'] = implode(',', $goods->spec);
                    } else {
                        $result['product_spec'] = $goods->spec;
                    }
                }
            }

            $result['confirm_type'] = ! empty(cfg('cart_confirm')) ? cfg('cart_confirm') : 2;

            return response()->json($result);
        } elseif ($_REQUEST['step'] === 'link_buy') {
            $goods_id = intval($_GET['goods_id']);

            if (! OrderHelper::cart_goods_exists($goods_id, [])) {
                OrderHelper::addto_cart($goods_id);
            }

            return response()->redirectTo('flow.php');
        } elseif ($_REQUEST['step'] === 'login') { // @deprecated
            lang(ROOT_PATH.'languages/'.cfg('lang').'/user.php');

            $cart_query = DB::table('user_cart');
            if (Session::get('user_id')) {
                $cart_query->where('user_id', intval(Session::get('user_id')));
            } else {
                $cart_query->where('session_id', SESS_ID);
            }

            /*
             * 用户登录注册
             */
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                $this->assign('anonymous_buy', cfg('anonymous_buy'));

                // 检查是否有赠品，如果有提示登录后重新选择赠品
                if ((clone $cart_query)->where('is_gift', '>', 0)->count() > 0) {
                    $this->assign('need_rechoose_gift', 1);
                }

                // 检查是否需要注册码
                $captcha = intval(cfg('captcha'));
                if (($captcha & CAPTCHA_LOGIN) && (! ($captcha & CAPTCHA_LOGIN_FAIL) || (($captcha & CAPTCHA_LOGIN_FAIL) && Session::get('login_fail') > 2)) && BaseHelper::gd_version() > 0) {
                    $this->assign('enabled_login_captcha', 1);
                    $this->assign('rand', mt_rand());
                }
                if ($captcha & CAPTCHA_REGISTER) {
                    $this->assign('enabled_register_captcha', 1);
                    $this->assign('rand', mt_rand());
                }
            } else {
                if (! empty($_POST['act']) && $_POST['act'] === 'signin') {
                    $captcha = intval(cfg('captcha'));
                    if (($captcha & CAPTCHA_LOGIN) && (! ($captcha & CAPTCHA_LOGIN_FAIL) || (($captcha & CAPTCHA_LOGIN_FAIL) && Session::get('login_fail') > 2)) && BaseHelper::gd_version() > 0) {
                        if (empty($_POST['captcha'])) {
                            $this->show_message(lang('invalid_captcha'));
                        }

                        // 检查验证码

                        $validator = new captcha;
                        $validator->session_word = 'captcha_login';
                        if (! $validator->check_word($_POST['captcha'])) {
                            $this->show_message(lang('invalid_captcha'));
                        }
                    }

                    $_POST['password'] = isset($_POST['password']) ? trim($_POST['password']) : '';
                    if ($user->login($_POST['username'], $_POST['password'], isset($_POST['remember']))) {
                        MainHelper::update_cart_offline(); // 离线购物车绑定会员id
                        MainHelper::update_user_info();  // 更新用户信息
                        MainHelper::recalculate_price(); // 重新计算购物车中的商品价格

                        // 检查购物车中是否有商品 没有商品则跳转到首页
                        if ($cart_query->count() > 0) {
                            return response()->redirectTo('flow.php?step=checkout');
                        } else {
                            return response()->redirectTo('index.php');
                        }
                    } else {
                        Session::increment('login_fail');
                        $this->show_message(lang('signin_failed'), '', 'flow.php?step=login');
                    }
                } elseif (! empty($_POST['act']) && $_POST['act'] === 'signup') {
                    if ((intval(cfg('captcha')) & CAPTCHA_REGISTER) && BaseHelper::gd_version() > 0) {
                        if (empty($_POST['captcha'])) {
                            $this->show_message(lang('invalid_captcha'));
                        }

                        // 检查验证码

                        $validator = new captcha;
                        if (! $validator->check_word($_POST['captcha'])) {
                            $this->show_message(lang('invalid_captcha'));
                        }
                    }

                    if (PassportHelper::register(trim($_POST['username']), trim($_POST['password']), trim($_POST['email']))) {
                        // 用户注册成功
                        return response()->redirectTo('flow.php?step=consignee');
                    } else {
                        $err->show();
                    }
                } else {
                    // TODO: 非法访问的处理
                }
            }
        } elseif ($_REQUEST['step'] === 'consignee') {// @deprecated
            // ------------------------------------------------------
            // -- 收货人信息
            // ------------------------------------------------------

            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                // 取得购物类型
                $flow_type = Session::has('flow_type') ? intval(Session::get('flow_type')) : CART_GENERAL_GOODS;

                /*
                 * 收货人信息填写界面
                 */

                if (isset($_REQUEST['direct_shopping'])) {
                    Session::put('direct_shopping', 1);
                }

                // 取得国家列表、商店所在国家、商店所在国家的省列表
                $this->assign('country_list', CommonHelper::get_regions());
                $this->assign('shop_country', cfg('shop_country'));
                $this->assign('shop_province_list', CommonHelper::get_regions(1, cfg('shop_country')));

                // 获得用户所有的收货人信息
                if (Session::get('user_id') > 0) {
                    $consignee_list = TransactionHelper::get_consignee_list(Session::get('user_id'));

                    if (count($consignee_list) < 5) {
                        // 如果用户收货人信息的总数小于 5 则增加一个新的收货人信息
                        $consignee_list[] = ['country' => cfg('shop_country'), 'email' => Session::has('email') ? Session::get('email') : ''];
                    }
                } else {
                    if (Session::has('flow_consignee')) {
                        $consignee_list = [Session::get('flow_consignee')];
                    } else {
                        $consignee_list[] = ['country' => cfg('shop_country')];
                    }
                }
                $this->assign('name_of_region', [cfg('name_of_region_1'), cfg('name_of_region_2'), cfg('name_of_region_3'), cfg('name_of_region_4')]);
                $this->assign('consignee_list', $consignee_list);

                // 取得每个收货地址的省市区列表
                $province_list = [];
                $city_list = [];
                $district_list = [];
                foreach ($consignee_list as $region_id => $consignee) {
                    $consignee['country'] = isset($consignee['country']) ? intval($consignee['country']) : 0;
                    $consignee['province'] = isset($consignee['province']) ? intval($consignee['province']) : 0;
                    $consignee['city'] = isset($consignee['city']) ? intval($consignee['city']) : 0;

                    $province_list[$region_id] = CommonHelper::get_regions(1, $consignee['country']);
                    $city_list[$region_id] = CommonHelper::get_regions(2, $consignee['province']);
                    $district_list[$region_id] = CommonHelper::get_regions(3, $consignee['city']);
                }
                $this->assign('province_list', $province_list);
                $this->assign('city_list', $city_list);
                $this->assign('district_list', $district_list);

                // 返回收货人页面代码
                $this->assign('real_goods_count', OrderHelper::exist_real_goods(0, $flow_type) ? 1 : 0);
            } else {
                /*
                 * 保存收货人信息
                 */
                $consignee = [
                    'address_id' => empty($_POST['address_id']) ? 0 : intval($_POST['address_id']),
                    'consignee' => empty($_POST['consignee']) ? '' : BaseHelper::compile_str(trim($_POST['consignee'])),
                    'country' => empty($_POST['country']) ? '' : intval($_POST['country']),
                    'province' => empty($_POST['province']) ? '' : intval($_POST['province']),
                    'city' => empty($_POST['city']) ? '' : intval($_POST['city']),
                    'district' => empty($_POST['district']) ? '' : intval($_POST['district']),
                    'email' => empty($_POST['email']) ? '' : BaseHelper::compile_str($_POST['email']),
                    'address' => empty($_POST['address']) ? '' : BaseHelper::compile_str($_POST['address']),
                    'zipcode' => empty($_POST['zipcode']) ? '' : BaseHelper::compile_str(BaseHelper::make_semiangle(trim($_POST['zipcode']))),
                    'tel' => empty($_POST['tel']) ? '' : BaseHelper::compile_str(BaseHelper::make_semiangle(trim($_POST['tel']))),
                    'mobile' => empty($_POST['mobile']) ? '' : BaseHelper::compile_str(BaseHelper::make_semiangle(trim($_POST['mobile']))),
                    'sign_building' => empty($_POST['sign_building']) ? '' : BaseHelper::compile_str($_POST['sign_building']),
                    'best_time' => empty($_POST['best_time']) ? '' : BaseHelper::compile_str($_POST['best_time']),
                ];

                if (Session::get('user_id') > 0) {
                    // 如果用户已经登录，则保存收货人信息
                    $consignee['user_id'] = Session::get('user_id');

                    TransactionHelper::save_consignee($consignee, true);
                }

                // 保存到session
                Session::put('flow_consignee', BaseHelper::stripslashes_deep($consignee));

                return response()->redirectTo('flow.php?step=checkout');
            }
        } elseif ($_REQUEST['step'] === 'drop_consignee') {// @deprecated
            // ------------------------------------------------------
            // -- 删除收货人信息
            // ------------------------------------------------------

            $consignee_id = intval($_GET['id']);

            if (TransactionHelper::drop_consignee($consignee_id)) {
                return response()->redirectTo('flow.php?step=consignee');
            } else {
                $this->show_message(lang('not_fount_consignee'));
            }
        } elseif ($_REQUEST['step'] === 'checkout') {
            // ------------------------------------------------------
            // -- 订单确认
            // ------------------------------------------------------

            // 取得购物类型
            $flow_type = Session::has('flow_type') ? intval(Session::get('flow_type')) : CART_GENERAL_GOODS;

            // 团购标志
            if ($flow_type === CART_GROUP_BUY_GOODS) {
                $this->assign('is_group_buy', 1);
            } // 积分兑换商品
            elseif ($flow_type === CART_EXCHANGE_GOODS) {
                $this->assign('is_exchange_goods', 1);
            } else {
                // 正常购物流程  清空其他购物流程情况
                $flow_order = Session::get('flow_order', []);
                $flow_order['extension_code'] = '';
                Session::put('flow_order', $flow_order);
            }

            $cart_query = DB::table('user_cart');
            if (Session::get('user_id')) {
                $cart_query->where('user_id', intval(Session::get('user_id')));
            } else {
                $cart_query->where('session_id', SESS_ID);
            }

            // 检查购物车中是否有商品
            if ($cart_query->where('parent_id', 0)->where('is_gift', 0)->where('rec_type', $flow_type)->count() === 0) {
                $this->show_message(lang('no_goods_in_cart'), '', '', 'warning');
            }

            /*
             * 检查用户是否已经登录
             * 如果用户已经登录了则检查是否有默认的收货地址
             * 如果没有登录则跳转到登录和注册页面
             */
            if (empty(Session::get('direct_shopping')) && Session::get('user_id') === 0) {
                // 用户没有登录且没有选定匿名购物，转向到登录页面
                return response()->redirectTo('flow.php?step=login');
            }

            $consignee = OrderHelper::get_consignee(Session::get('user_id', 0));

            // 检查收货人信息是否完整
            if (! OrderHelper::check_consignee_info($consignee, $flow_type)) {
                // 如果不完整则转向到收货人信息填写界面
                return response()->redirectTo('flow.php?step=consignee');
            }

            Session::put('flow_consignee', $consignee);
            $this->assign('consignee', $consignee);

            // 对商品信息赋值
            $cart_goods = OrderHelper::cart_goods($flow_type); // 取得商品列表，计算合计
            $this->assign('goods_list', $cart_goods);

            // 对是否允许修改购物车赋值
            if ($flow_type != CART_GENERAL_GOODS || cfg('one_step_buy') === '1') {
                $this->assign('allow_edit_cart', 0);
            } else {
                $this->assign('allow_edit_cart', 1);
            }

            /*
             * 取得购物流程设置
             */
            $this->assign('config', cfg());
            /*
             * 取得订单信息
             */
            $order = OrderHelper::flow_order_info();
            $this->assign('order', $order);

            // 计算折扣
            if ($flow_type != CART_EXCHANGE_GOODS && $flow_type != CART_GROUP_BUY_GOODS) {
                $discount = OrderHelper::compute_discount();
                $this->assign('discount', $discount['discount']);
                $favour_name = empty($discount['name']) ? '' : implode(',', $discount['name']);
                $this->assign('your_discount', sprintf(lang('your_discount'), $favour_name, CommonHelper::price_format($discount['discount'])));
            }

            /*
             * 计算订单的费用
             */
            $total = OrderHelper::order_fee($order, $cart_goods, $consignee);

            $this->assign('total', $total);
            $this->assign('shopping_money', sprintf(lang('shopping_money'), $total['formated_goods_price']));
            $this->assign('market_price_desc', sprintf(lang('than_market_price'), $total['formated_market_price'], $total['formated_saving'], $total['save_rate']));

            // 取得配送列表
            $region = [$consignee['country'], $consignee['province'], $consignee['city'], $consignee['district']];
            $shipping_list = OrderHelper::available_shipping_list($region);
            $cart_weight_price = OrderHelper::cart_weight_price($flow_type);
            $insure_disabled = true;
            $cod_disabled = true;

            // 查看购物车中是否全为免运费商品，若是则把运费赋为零
            $shipping_count = DB::table('user_cart');
            if (Session::get('user_id')) {
                $shipping_count->where('user_id', intval(Session::get('user_id')));
            } else {
                $shipping_count->where('session_id', SESS_ID);
            }
            $shipping_count = $shipping_count->where('extension_code', '<>', 'package_buy')->where('is_shipping', 0)->count();

            foreach ($shipping_list as $key => $val) {
                $shipping_cfg = OrderHelper::unserialize_config($val['configure']);
                $shipping_fee = ($shipping_count === 0 and $cart_weight_price['free_shipping'] === 1) ? 0 : OrderHelper::shipping_fee(
                    $val['shipping_code'],
                    unserialize($val['configure']),
                    $cart_weight_price['weight'],
                    $cart_weight_price['amount'],
                    $cart_weight_price['number']
                );

                $shipping_list[$key]['format_shipping_fee'] = CommonHelper::price_format($shipping_fee, false);
                $shipping_list[$key]['shipping_fee'] = $shipping_fee;
                $shipping_list[$key]['free_money'] = CommonHelper::price_format($shipping_cfg['free_money'], false);
                $shipping_list[$key]['insure_formated'] = strpos($val['insure'], '%') === false ?
                    CommonHelper::price_format($val['insure'], false) : $val['insure'];

                // 当前的配送方式是否支持保价
                if ($val['shipping_id'] === $order['shipping_id']) {
                    $insure_disabled = ($val['insure'] === 0);
                    $cod_disabled = ($val['support_cod'] === 0);
                }
            }

            $this->assign('shipping_list', $shipping_list);
            $this->assign('insure_disabled', $insure_disabled);
            $this->assign('cod_disabled', $cod_disabled);

            // 取得支付列表
            if ($order['shipping_id'] === 0) {
                $cod = true;
                $cod_fee = 0;
            } else {
                $shipping = OrderHelper::shipping_info($order['shipping_id']);
                $cod = $shipping['support_cod'];

                if ($cod) {
                    // 如果是团购，且保证金大于0，不能使用货到付款
                    if ($flow_type === CART_GROUP_BUY_GOODS) {
                        $group_buy_id = Session::get('extension_id', 0);
                        if ($group_buy_id <= 0) {
                            $this->show_message('error group_buy_id');
                        }
                        $group_buy = GoodsHelper::group_buy_info($group_buy_id);
                        if (empty($group_buy)) {
                            $this->show_message('group buy not exists: '.$group_buy_id);
                        }

                        if ($group_buy['deposit'] > 0) {
                            $cod = false;
                            $cod_fee = 0;

                            // 赋值保证金
                            $this->assign('gb_deposit', $group_buy['deposit']);
                        }
                    }

                    if ($cod) {
                        $shipping_area_info = OrderHelper::shipping_area_info($order['shipping_id'], $region);
                        $cod_fee = $shipping_area_info['pay_fee'];
                    }
                } else {
                    $cod_fee = 0;
                }
            }

            // 给货到付款的手续费加<span id>，以便改变配送的时候动态显示
            $payment_list = OrderHelper::available_payment_list(1, $cod_fee);
            if (isset($payment_list)) {
                foreach ($payment_list as $key => $payment) {
                    if ($payment['is_cod'] === '1') {
                        $payment_list[$key]['format_pay_fee'] = '<span id="ECS_CODFEE">'.$payment['format_pay_fee'].'</span>';
                    }
                    // 如果有易宝神州行支付 如果订单金额大于300 则不显示
                    if ($payment['pay_code'] === 'yeepayszx' && $total['amount'] > 300) {
                        unset($payment_list[$key]);
                    }
                    // 如果有余额支付
                    if ($payment['pay_code'] === 'balance') {
                        // 如果未登录，不显示
                        if (Session::get('user_id') === 0) {
                            unset($payment_list[$key]);
                        } else {
                            if (Session::get('flow_order.pay_id') === $payment['pay_id']) {
                                $this->assign('disable_surplus', 1);
                            }
                        }
                    }
                }
            }
            $this->assign('payment_list', $payment_list);

            // 取得包装与贺卡
            if ($total['real_goods_count'] > 0) {
                // 只有有实体商品,才要判断包装和贺卡
                if (! cfg('use_package') || cfg('use_package') === '1') {
                    // 如果使用包装，取得包装列表及用户选择的包装
                    $this->assign('pack_list', OrderHelper::pack_list());
                }

                // 如果使用贺卡，取得贺卡列表及用户选择的贺卡
                if (! cfg('use_card') || cfg('use_card') === '1') {
                    $this->assign('card_list', OrderHelper::card_list());
                }
            }

            $user_info = OrderHelper::user_info(Session::get('user_id', 0));

            // 如果使用余额，取得用户余额
            if (
                (! cfg('use_surplus') || cfg('use_surplus') === '1')
                && Session::get('user_id') > 0
                && $user_info['user_money'] > 0
            ) {
                // 能使用余额
                $this->assign('allow_use_surplus', 1);
                $this->assign('your_surplus', $user_info['user_money']);
            }

            // 如果使用积分，取得用户可用积分及本订单最多可以使用的积分
            if (
                (! cfg('use_integral') || cfg('use_integral') === '1')
                && Session::get('user_id') > 0
                && $user_info['pay_points'] > 0
                && ($flow_type != CART_GROUP_BUY_GOODS && $flow_type != CART_EXCHANGE_GOODS)
            ) {
                // 能使用积分
                $this->assign('allow_use_integral', 1);
                $this->assign('order_max_integral', $this->flow_available_points());  // 可用积分
                $this->assign('your_integral', $user_info['pay_points']); // 用户积分
            }

            // 如果使用红包，取得用户可以使用的红包及用户选择的红包
            if (
                (! cfg('use_bonus') || cfg('use_bonus') === '1')
                && ($flow_type != CART_GROUP_BUY_GOODS && $flow_type != CART_EXCHANGE_GOODS)
            ) {
                // 取得用户可用红包
                $user_bonus = OrderHelper::user_bonus(Session::get('user_id', 0), $total['goods_price']);
                if (! empty($user_bonus)) {
                    foreach ($user_bonus as $key => $val) {
                        $user_bonus[$key]['bonus_money_formated'] = CommonHelper::price_format($val['type_money'], false);
                    }
                    $this->assign('bonus_list', $user_bonus);
                }

                // 能使用红包
                $this->assign('allow_use_bonus', 1);
            }

            // 如果使用缺货处理，取得缺货处理列表
            if (! cfg('use_how_oos') || cfg('use_how_oos') === '1') {
                if (is_array(lang('oos')) && ! empty(lang('oos'))) {
                    $this->assign('how_oos_list', lang('oos'));
                }
            }

            // 如果能开发票，取得发票内容列表
            if (
                (! cfg('can_invoice') || cfg('can_invoice') === '1')
                && cfg('invoice_content')
                && trim(cfg('invoice_content')) != '' && $flow_type != CART_EXCHANGE_GOODS
            ) {
                $inv_content_list = explode("\n", str_replace("\r", '', cfg('invoice_content')));
                $this->assign('inv_content_list', $inv_content_list);

                $inv_type_list = [];
                foreach (cfg('invoice_type.type') as $key => $type) {
                    if (! empty($type)) {
                        $inv_type_list[$type] = $type.' ['.floatval(cfg('invoice_type.rate')[$key]).'%]';
                    }
                }
                $this->assign('inv_type_list', $inv_type_list);
            }

            // 保存 session
            Session::put('flow_order', $order);
        } elseif ($_REQUEST['step'] === 'select_shipping') {
            // ------------------------------------------------------
            // -- 改变配送方式
            // ------------------------------------------------------
            $result = ['error' => '', 'content' => '', 'need_insure' => 0];

            // 取得购物类型
            $flow_type = Session::get('flow_type', CART_GENERAL_GOODS);

            // 获得收货人信息
            $consignee = OrderHelper::get_consignee(Session::get('user_id'));

            // 对商品信息赋值
            $cart_goods = OrderHelper::cart_goods($flow_type); // 取得商品列表，计算合计

            if (empty($cart_goods) || ! OrderHelper::check_consignee_info($consignee, $flow_type)) {
                $result['error'] = lang('no_goods_in_cart');
            } else {
                // 取得购物流程设置
                $this->assign('config', cfg());

                // 取得订单信息
                $order = OrderHelper::flow_order_info();

                $order['shipping_id'] = intval($_REQUEST['shipping']);
                $regions = [$consignee['country'], $consignee['province'], $consignee['city'], $consignee['district']];
                $shipping_info = OrderHelper::shipping_area_info($order['shipping_id'], $regions);

                // 计算订单的费用
                $total = OrderHelper::order_fee($order, $cart_goods, $consignee);
                $this->assign('total', $total);

                // 取得可以得到的积分和红包
                $this->assign('total_integral', OrderHelper::cart_amount(false, $flow_type) - $total['bonus'] - $total['integral_money']);
                $this->assign('total_bonus', CommonHelper::price_format(OrderHelper::get_total_bonus(), false));

                // 团购标志
                if ($flow_type === CART_GROUP_BUY_GOODS) {
                    $this->assign('is_group_buy', 1);
                }

                $result['cod_fee'] = $shipping_info['pay_fee'];
                if (strpos($result['cod_fee'], '%') === false) {
                    $result['cod_fee'] = CommonHelper::price_format($result['cod_fee'], false);
                }
                $result['need_insure'] = ($shipping_info['insure'] > 0 && ! empty($order['need_insure'])) ? 1 : 0;
                $result['content'] = $this->fetch('web::library/order_total');
            }

            return json_encode($result);
        } elseif ($_REQUEST['step'] === 'select_insure') {
            // ------------------------------------------------------
            // -- 选定/取消配送的保价
            // ------------------------------------------------------

            $result = ['error' => '', 'content' => '', 'need_insure' => 0];

            // 取得购物类型
            $flow_type = Session::get('flow_type', CART_GENERAL_GOODS);

            // 获得收货人信息
            $consignee = OrderHelper::get_consignee(Session::get('user_id'));

            // 对商品信息赋值
            $cart_goods = OrderHelper::cart_goods($flow_type); // 取得商品列表，计算合计

            if (empty($cart_goods) || ! OrderHelper::check_consignee_info($consignee, $flow_type)) {
                $result['error'] = lang('no_goods_in_cart');
            } else {
                // 取得购物流程设置
                $this->assign('config', cfg());

                // 取得订单信息
                $order = OrderHelper::flow_order_info();

                $order['need_insure'] = intval($_REQUEST['insure']);

                // 保存 session
                Session::put('flow_order', $order);

                // 计算订单的费用
                $total = OrderHelper::order_fee($order, $cart_goods, $consignee);
                $this->assign('total', $total);

                // 取得可以得到的积分和红包
                $this->assign('total_integral', OrderHelper::cart_amount(false, $flow_type) - $total['bonus'] - $total['integral_money']);
                $this->assign('total_bonus', CommonHelper::price_format(OrderHelper::get_total_bonus(), false));

                // 团购标志
                if ($flow_type === CART_GROUP_BUY_GOODS) {
                    $this->assign('is_group_buy', 1);
                }

                $result['content'] = $this->fetch('web::library/order_total');
            }

            return json_encode($result);
        } elseif ($_REQUEST['step'] === 'select_payment') {
            // ------------------------------------------------------
            // -- 改变支付方式
            // ------------------------------------------------------

            $result = ['error' => '', 'content' => '', 'need_insure' => 0, 'payment' => 1];

            // 取得购物类型
            $flow_type = Session::get('flow_type', CART_GENERAL_GOODS);

            // 获得收货人信息
            $consignee = OrderHelper::get_consignee(Session::get('user_id'));

            // 对商品信息赋值
            $cart_goods = OrderHelper::cart_goods($flow_type); // 取得商品列表，计算合计

            if (empty($cart_goods) || ! OrderHelper::check_consignee_info($consignee, $flow_type)) {
                $result['error'] = lang('no_goods_in_cart');
            } else {
                // 取得购物流程设置
                $this->assign('config', cfg());

                // 取得订单信息
                $order = OrderHelper::flow_order_info();

                $order['pay_id'] = intval($_REQUEST['payment']);
                $payment_info = OrderHelper::payment_info($order['pay_id']);
                $result['pay_code'] = $payment_info['pay_code'];

                // 保存 session
                Session::put('flow_order', $order);

                // 计算订单的费用
                $total = OrderHelper::order_fee($order, $cart_goods, $consignee);
                $this->assign('total', $total);

                // 取得可以得到的积分和红包
                $this->assign('total_integral', OrderHelper::cart_amount(false, $flow_type) - $total['bonus'] - $total['integral_money']);
                $this->assign('total_bonus', CommonHelper::price_format(OrderHelper::get_total_bonus(), false));

                // 团购标志
                if ($flow_type === CART_GROUP_BUY_GOODS) {
                    $this->assign('is_group_buy', 1);
                }

                $result['content'] = $this->fetch('web::library/order_total');
            }

            return json_encode($result);
        } elseif ($_REQUEST['step'] === 'select_pack') {
            // ------------------------------------------------------
            // -- 改变商品包装
            // ------------------------------------------------------

            $result = ['error' => '', 'content' => '', 'need_insure' => 0];

            // 取得购物类型
            $flow_type = Session::get('flow_type', CART_GENERAL_GOODS);

            // 获得收货人信息
            $consignee = OrderHelper::get_consignee(Session::get('user_id'));

            // 对商品信息赋值
            $cart_goods = OrderHelper::cart_goods($flow_type); // 取得商品列表，计算合计

            if (empty($cart_goods) || ! OrderHelper::check_consignee_info($consignee, $flow_type)) {
                $result['error'] = lang('no_goods_in_cart');
            } else {
                // 取得购物流程设置
                $this->assign('config', cfg());

                // 取得订单信息
                $order = OrderHelper::flow_order_info();

                $order['pack_id'] = intval($_REQUEST['pack']);

                // 保存 session
                Session::put('flow_order', $order);

                // 计算订单的费用
                $total = OrderHelper::order_fee($order, $cart_goods, $consignee);
                $this->assign('total', $total);

                // 取得可以得到的积分和红包
                $this->assign('total_integral', OrderHelper::cart_amount(false, $flow_type) - $total['bonus'] - $total['integral_money']);
                $this->assign('total_bonus', CommonHelper::price_format(OrderHelper::get_total_bonus(), false));

                // 团购标志
                if ($flow_type === CART_GROUP_BUY_GOODS) {
                    $this->assign('is_group_buy', 1);
                }

                $result['content'] = $this->fetch('web::library/order_total');
            }

            return json_encode($result);
        } elseif ($_REQUEST['step'] === 'select_card') {
            // ------------------------------------------------------
            // -- 改变贺卡
            // ------------------------------------------------------

            $result = ['error' => '', 'content' => '', 'need_insure' => 0];

            // 取得购物类型
            $flow_type = Session::has('flow_type') ? intval(Session::get('flow_type')) : CART_GENERAL_GOODS;

            // 获得收货人信息
            $consignee = OrderHelper::get_consignee(Session::get('user_id'));

            // 对商品信息赋值
            $cart_goods = OrderHelper::cart_goods($flow_type); // 取得商品列表，计算合计

            if (empty($cart_goods) || ! OrderHelper::check_consignee_info($consignee, $flow_type)) {
                $result['error'] = lang('no_goods_in_cart');
            } else {
                // 取得购物流程设置
                $this->assign('config', cfg());

                // 取得订单信息
                $order = OrderHelper::flow_order_info();

                $order['card_id'] = intval($_REQUEST['card']);

                // 保存 session
                Session::put('flow_order', $order);

                // 计算订单的费用
                $total = OrderHelper::order_fee($order, $cart_goods, $consignee);
                $this->assign('total', $total);

                // 取得可以得到的积分和红包
                $this->assign('total_integral', OrderHelper::cart_amount(false, $flow_type) - $order['bonus'] - $total['integral_money']);
                $this->assign('total_bonus', CommonHelper::price_format(OrderHelper::get_total_bonus(), false));

                // 团购标志
                if ($flow_type === CART_GROUP_BUY_GOODS) {
                    $this->assign('is_group_buy', 1);
                }

                $result['content'] = $this->fetch('web::library/order_total');
            }

            return json_encode($result);
        } elseif ($_REQUEST['step'] === 'change_surplus') {
            // ------------------------------------------------------
            // -- 改变余额
            // ------------------------------------------------------

            $surplus = floatval($_GET['surplus']);
            $user_info = OrderHelper::user_info(Session::get('user_id'));

            if ($surplus > $user_info['user_money'] + $user_info['credit_line']) {
                $result['error'] = lang('surplus_not_enough');
            } else {
                // 取得购物类型
                $flow_type = Session::has('flow_type') ? intval(Session::get('flow_type')) : CART_GENERAL_GOODS;

                // 取得购物流程设置
                $this->assign('config', cfg());

                // 获得收货人信息
                $consignee = OrderHelper::get_consignee(Session::get('user_id', 0));

                // 对商品信息赋值
                $cart_goods = OrderHelper::cart_goods($flow_type); // 取得商品列表，计算合计

                if (empty($cart_goods) || ! OrderHelper::check_consignee_info($consignee, $flow_type)) {
                    $result['error'] = lang('no_goods_in_cart');
                } else {
                    // 取得订单信息
                    $order = OrderHelper::flow_order_info();
                    $order['surplus'] = $surplus;

                    // 计算订单的费用
                    $total = OrderHelper::order_fee($order, $cart_goods, $consignee);
                    $this->assign('total', $total);

                    // 团购标志
                    if ($flow_type === CART_GROUP_BUY_GOODS) {
                        $this->assign('is_group_buy', 1);
                    }

                    $result['content'] = $this->fetch('web::library/order_total');
                }
            }

            return response()->json($result);
        } elseif ($_REQUEST['step'] === 'change_integral') {
            // ------------------------------------------------------
            // -- 改变积分
            // ------------------------------------------------------

            $points = floatval($_GET['points']);
            $user_info = OrderHelper::user_info(Session::get('user_id'));

            // 取得订单信息
            $order = OrderHelper::flow_order_info();

            $flow_points = $this->flow_available_points();  // 该订单允许使用的积分
            $user_points = $user_info['pay_points']; // 用户的积分总数

            if ($points > $user_points) {
                $result['error'] = lang('integral_not_enough');
            } elseif ($points > $flow_points) {
                $result['error'] = sprintf(lang('integral_too_much'), $flow_points);
            } else {
                // 取得购物类型
                $flow_type = Session::has('flow_type') ? intval(Session::get('flow_type')) : CART_GENERAL_GOODS;

                $order['integral'] = $points;

                // 获得收货人信息
                $consignee = OrderHelper::get_consignee(Session::get('user_id', 0));

                // 对商品信息赋值
                $cart_goods = OrderHelper::cart_goods($flow_type); // 取得商品列表，计算合计

                if (empty($cart_goods) || ! OrderHelper::check_consignee_info($consignee, $flow_type)) {
                    $result['error'] = lang('no_goods_in_cart');
                } else {
                    // 计算订单的费用
                    $total = OrderHelper::order_fee($order, $cart_goods, $consignee);
                    $this->assign('total', $total);
                    $this->assign('config', cfg());

                    // 团购标志
                    if ($flow_type === CART_GROUP_BUY_GOODS) {
                        $this->assign('is_group_buy', 1);
                    }

                    $result['content'] = $this->fetch('web::library/order_total');
                    $result['error'] = '';
                }
            }

            return response()->json($result);
        } elseif ($_REQUEST['step'] === 'change_bonus') {
            // ------------------------------------------------------
            // -- 改变红包
            // ------------------------------------------------------
            $result = ['error' => '', 'content' => ''];

            // 取得购物类型
            $flow_type = Session::get('flow_type', CART_GENERAL_GOODS);

            // 获得收货人信息
            $consignee = OrderHelper::get_consignee(Session::get('user_id'));

            // 对商品信息赋值
            $cart_goods = OrderHelper::cart_goods($flow_type); // 取得商品列表，计算合计

            if (empty($cart_goods) || ! OrderHelper::check_consignee_info($consignee, $flow_type)) {
                $result['error'] = lang('no_goods_in_cart');
            } else {
                // 取得购物流程设置
                $this->assign('config', cfg());

                $bonus = OrderHelper::bonus_info(intval($_GET['bonus']));

                if ((! empty($bonus) && $bonus['user_id'] === Session::get('user_id')) || $_GET['bonus'] === 0) {
                    $order['bonus_id'] = intval($_GET['bonus']);
                } else {
                    $order['bonus_id'] = 0;
                    $result['error'] = lang('invalid_bonus');
                }

                // 计算订单的费用
                $total = OrderHelper::order_fee($order, $cart_goods, $consignee);
                $this->assign('total', $total);

                // 团购标志
                if ($flow_type === CART_GROUP_BUY_GOODS) {
                    $this->assign('is_group_buy', 1);
                }

                $result['content'] = $this->fetch('web::library/order_total');
            }

            return response()->json($result);
        } elseif ($_REQUEST['step'] === 'change_needinv') {
            // ------------------------------------------------------
            // -- 改变发票的设置
            // ------------------------------------------------------
            $result = ['error' => '', 'content' => ''];
            $_GET['inv_type'] = ! empty($_GET['inv_type']) ? BaseHelper::json_str_iconv(urldecode($_GET['inv_type'])) : '';
            $_GET['invPayee'] = ! empty($_GET['invPayee']) ? BaseHelper::json_str_iconv(urldecode($_GET['invPayee'])) : '';
            $_GET['inv_content'] = ! empty($_GET['inv_content']) ? BaseHelper::json_str_iconv(urldecode($_GET['inv_content'])) : '';

            // 取得购物类型
            $flow_type = Session::has('flow_type') ? intval(Session::get('flow_type')) : CART_GENERAL_GOODS;

            // 获得收货人信息
            $consignee = OrderHelper::get_consignee(Session::get('user_id'));

            // 对商品信息赋值
            $cart_goods = OrderHelper::cart_goods($flow_type); // 取得商品列表，计算合计

            if (empty($cart_goods) || ! OrderHelper::check_consignee_info($consignee, $flow_type)) {
                $result['error'] = lang('no_goods_in_cart');

                return response()->json($result);
            } else {
                // 取得购物流程设置
                $this->assign('config', cfg());

                // 取得订单信息
                $order = OrderHelper::flow_order_info();

                if (isset($_GET['need_inv']) && intval($_GET['need_inv']) === 1) {
                    $order['need_inv'] = 1;
                    $order['inv_type'] = trim(stripslashes($_GET['inv_type']));
                    $order['inv_payee'] = trim(stripslashes($_GET['inv_payee']));
                    $order['inv_content'] = trim(stripslashes($_GET['inv_content']));
                } else {
                    $order['need_inv'] = 0;
                    $order['inv_type'] = '';
                    $order['inv_payee'] = '';
                    $order['inv_content'] = '';
                }

                // 计算订单的费用
                $total = OrderHelper::order_fee($order, $cart_goods, $consignee);
                $this->assign('total', $total);

                // 团购标志
                if ($flow_type === CART_GROUP_BUY_GOODS) {
                    $this->assign('is_group_buy', 1);
                }

                exit($this->fetch('web::library/order_total'));
            }
        } elseif ($_REQUEST['step'] === 'change_oos') {
            // ------------------------------------------------------
            // -- 改变缺货处理时的方式
            // ------------------------------------------------------

            // 取得订单信息
            $order = OrderHelper::flow_order_info();

            $order['how_oos'] = intval($_GET['oos']);

            // 保存 session
            Session::put('flow_order', $order);
        } elseif ($_REQUEST['step'] === 'check_surplus') {
            // ------------------------------------------------------
            // -- 检查用户输入的余额
            // ------------------------------------------------------
            $surplus = floatval($_GET['surplus']);
            $user_info = OrderHelper::user_info(Session::get('user_id'));

            if (($surplus > $user_info['user_money'] + $user_info['credit_line'])) {
                exit(lang('surplus_not_enough'));
            }
        } elseif ($_REQUEST['step'] === 'check_integral') {
            // ------------------------------------------------------
            // -- 检查用户输入的余额
            // ------------------------------------------------------
            $points = floatval($_GET['integral']);
            $user_info = OrderHelper::user_info(Session::get('user_id'));
            $flow_points = $this->flow_available_points();  // 该订单允许使用的积分
            $user_points = $user_info['pay_points']; // 用户的积分总数

            if ($points > $user_points) {
                exit(lang('integral_not_enough'));
            }

            if ($points > $flow_points) {
                exit(sprintf(lang('integral_too_much'), $flow_points));
            }
        }
        /**
         * 完成所有订单操作，提交到数据库
         */ elseif ($_REQUEST['step'] === 'done') {
            $cart_query = DB::table('user_cart');
            if (Session::get('user_id')) {
                $cart_query->where('user_id', intval(Session::get('user_id')));
            } else {
                $cart_query->where('session_id', SESS_ID);
            }

            // 取得购物类型
            $flow_type = Session::has('flow_type') ? intval(Session::get('flow_type')) : CART_GENERAL_GOODS;

            // 检查购物车中是否有商品
            if ((clone $cart_query)->where('parent_id', 0)->where('is_gift', 0)->where('rec_type', $flow_type)->count() === 0) {
                $this->show_message(lang('no_goods_in_cart'), '', '', 'warning');
            }

            // 检查商品库存
            // 如果使用库存，且下订单时减库存，则减少库存
            if (cfg('use_storage') === '1' && cfg('stock_dec_time') === SDT_PLACE) {
                $cart_goods_stock = OrderHelper::get_cart_goods();
                $_cart_goods_stock = [];
                foreach ($cart_goods_stock['goods_list'] as $value) {
                    $_cart_goods_stock[$value['rec_id']] = $value['goods_number'];
                }
                $this->flow_cart_stock($_cart_goods_stock);
                unset($cart_goods_stock, $_cart_goods_stock);
            }

            /*
             * 检查用户是否已经登录
             * 如果用户已经登录了则检查是否有默认的收货地址
             * 如果没有登录则跳转到登录和注册页面
             */
            if (empty(Session::get('direct_shopping')) && Session::get('user_id') === 0) {
                // 用户没有登录且没有选定匿名购物，转向到登录页面
                return response()->redirectTo('flow.php?step=login');
            }

            $consignee = OrderHelper::get_consignee(Session::get('user_id', 0));

            // 检查收货人信息是否完整
            if (! OrderHelper::check_consignee_info($consignee, $flow_type)) {
                // 如果不完整则转向到收货人信息填写界面
                return response()->redirectTo('flow.php?step=consignee');
            }

            $_POST['how_oos'] = isset($_POST['how_oos']) ? intval($_POST['how_oos']) : 0;
            $_POST['card_message'] = isset($_POST['card_message']) ? BaseHelper::compile_str($_POST['card_message']) : '';
            $_POST['inv_type'] = ! empty($_POST['inv_type']) ? BaseHelper::compile_str($_POST['inv_type']) : '';
            $_POST['inv_payee'] = isset($_POST['inv_payee']) ? BaseHelper::compile_str($_POST['inv_payee']) : '';
            $_POST['inv_content'] = isset($_POST['inv_content']) ? BaseHelper::compile_str($_POST['inv_content']) : '';
            $_POST['postscript'] = isset($_POST['postscript']) ? BaseHelper::compile_str($_POST['postscript']) : '';

            $order = [
                'shipping_id' => intval($_POST['shipping']),
                'pay_id' => intval($_POST['payment']),
                'pack_id' => isset($_POST['pack']) ? intval($_POST['pack']) : 0,
                'card_id' => isset($_POST['card']) ? intval($_POST['card']) : 0,
                'card_message' => trim($_POST['card_message']),
                'surplus' => isset($_POST['surplus']) ? floatval($_POST['surplus']) : 0.00,
                'integral' => isset($_POST['integral']) ? intval($_POST['integral']) : 0,
                'bonus_id' => isset($_POST['bonus']) ? intval($_POST['bonus']) : 0,
                'need_inv' => empty($_POST['need_inv']) ? 0 : 1,
                'inv_type' => $_POST['inv_type'],
                'inv_payee' => trim($_POST['inv_payee']),
                'inv_content' => $_POST['inv_content'],
                'postscript' => trim($_POST['postscript']),
                'how_oos' => isset(lang('oos')[$_POST['how_oos']]) ? addslashes(lang('oos')[$_POST['how_oos']]) : '',
                'need_insure' => isset($_POST['need_insure']) ? intval($_POST['need_insure']) : 0,
                'user_id' => Session::get('user_id'),
                'add_time' => TimeHelper::gmtime(),
                'order_status' => OS_UNCONFIRMED,
                'shipping_status' => SS_UNSHIPPED,
                'pay_status' => PS_UNPAYED,
                'agency_id' => OrderHelper::get_agency_by_regions([$consignee['country'], $consignee['province'], $consignee['city'], $consignee['district']]),
            ];

            // 扩展信息
            if (Session::has('flow_type') && intval(Session::get('flow_type')) != CART_GENERAL_GOODS) {
                $order['extension_code'] = Session::get('extension_code');
                $order['extension_id'] = Session::get('extension_id');
            } else {
                $order['extension_code'] = '';
                $order['extension_id'] = 0;
            }

            // 检查积分余额是否合法
            $user_id = Session::get('user_id');
            if ($user_id > 0) {
                $user_info = OrderHelper::user_info($user_id);

                $order['surplus'] = min($order['surplus'], $user_info['user_money'] + $user_info['credit_line']);
                if ($order['surplus'] < 0) {
                    $order['surplus'] = 0;
                }

                // 查询用户有多少积分
                $flow_points = $this->flow_available_points();  // 该订单允许使用的积分
                $user_points = $user_info['pay_points']; // 用户的积分总数

                $order['integral'] = min($order['integral'], $user_points, $flow_points);
                if ($order['integral'] < 0) {
                    $order['integral'] = 0;
                }
            } else {
                $order['surplus'] = 0;
                $order['integral'] = 0;
            }

            // 检查红包是否存在
            if ($order['bonus_id'] > 0) {
                $bonus = OrderHelper::bonus_info($order['bonus_id']);

                if (empty($bonus) || $bonus['user_id'] != $user_id || $bonus['order_id'] > 0 || $bonus['min_goods_amount'] > OrderHelper::cart_amount(true, $flow_type)) {
                    $order['bonus_id'] = 0;
                }
            } elseif (isset($_POST['bonus_sn'])) {
                $bonus_sn = trim($_POST['bonus_sn']);
                $bonus = OrderHelper::bonus_info(0, $bonus_sn);
                $now = TimeHelper::gmtime();
                if (empty($bonus) || $bonus['user_id'] > 0 || $bonus['order_id'] > 0 || $bonus['min_goods_amount'] > OrderHelper::cart_amount(true, $flow_type) || $now > $bonus['use_end_date']) {
                } else {
                    if ($user_id > 0) {
                        DB::table('user_bonus')
                            ->where('bonus_id', $bonus['bonus_id'])
                            ->update(['user_id' => $user_id]);
                    }
                    $order['bonus_id'] = $bonus['bonus_id'];
                    $order['bonus_sn'] = $bonus_sn;
                }
            }

            // 订单中的商品
            $cart_goods = OrderHelper::cart_goods($flow_type);

            if (empty($cart_goods)) {
                $this->show_message(lang('no_goods_in_cart'), lang('back_home'), './', 'warning');
            }

            // 检查商品总额是否达到最低限购金额
            if ($flow_type === CART_GENERAL_GOODS && OrderHelper::cart_amount(true, CART_GENERAL_GOODS) < cfg('min_goods_amount')) {
                $this->show_message(sprintf(lang('goods_amount_not_enough'), CommonHelper::price_format(cfg('min_goods_amount'), false)));
            }

            // 收货人信息
            foreach ($consignee as $key => $value) {
                $order[$key] = addslashes($value);
            }

            // 判断是不是实体商品
            foreach ($cart_goods as $val) {
                // 统计实体商品的个数
                if ($val['is_real']) {
                    $is_real_good = 1;
                }
            }
            if (isset($is_real_good)) {
                $shipping_exists = DB::table('shipping')
                    ->where('shipping_id', $order['shipping_id'])
                    ->where('enabled', 1)
                    ->exists();
                if (! $shipping_exists) {
                    $this->show_message(lang('flow_no_shipping'));
                }
            }
            // 订单中的总额
            $total = OrderHelper::order_fee($order, $cart_goods, $consignee);
            $order['bonus'] = $total['bonus'];
            $order['goods_amount'] = $total['goods_price'];
            $order['discount'] = $total['discount'];
            $order['surplus'] = $total['surplus'];
            $order['tax'] = $total['tax'];

            // 购物车中的商品能享受红包支付的总额
            $discount_amout = OrderHelper::compute_discount_amount();
            // 红包和积分最多能支付的金额为商品总额
            $temp_amout = $order['goods_amount'] - $discount_amout;
            if ($temp_amout <= 0) {
                $order['bonus_id'] = 0;
            }

            // 配送方式
            if ($order['shipping_id'] > 0) {
                $shipping = OrderHelper::shipping_info($order['shipping_id']);
                $order['shipping_name'] = addslashes($shipping['shipping_name']);
            }
            $order['shipping_fee'] = $total['shipping_fee'];
            $order['insure_fee'] = $total['shipping_insure'];

            // 支付方式
            if ($order['pay_id'] > 0) {
                $payment = OrderHelper::payment_info($order['pay_id']);
                $order['pay_name'] = addslashes($payment['pay_name']);
            }
            $order['pay_fee'] = $total['pay_fee'];
            $order['cod_fee'] = $total['cod_fee'];

            // 商品包装
            if ($order['pack_id'] > 0) {
                $pack = OrderHelper::pack_info($order['pack_id']);
                $order['pack_name'] = addslashes($pack['pack_name']);
            }
            $order['pack_fee'] = $total['pack_fee'];

            // 祝福贺卡
            if ($order['card_id'] > 0) {
                $card = OrderHelper::card_info($order['card_id']);
                $order['card_name'] = addslashes($card['card_name']);
            }
            $order['card_fee'] = $total['card_fee'];

            $order['order_amount'] = number_format($total['amount'], 2, '.', '');

            // 如果全部使用余额支付，检查余额是否足够
            if ($payment['pay_code'] === 'balance' && $order['order_amount'] > 0) {
                if ($order['surplus'] > 0) { // 余额支付里如果输入了一个金额
                    $order['order_amount'] = $order['order_amount'] + $order['surplus'];
                    $order['surplus'] = 0;
                }
                if ($order['order_amount'] > ($user_info['user_money'] + $user_info['credit_line'])) {
                    $this->show_message(lang('balance_not_enough'));
                } else {
                    $order['surplus'] = $order['order_amount'];
                    $order['order_amount'] = 0;
                }
            }

            // 如果订单金额为0（使用余额或积分或红包支付），修改订单状态为已确认、已付款
            if ($order['order_amount'] <= 0) {
                $order['order_status'] = OS_CONFIRMED;
                $order['confirm_time'] = TimeHelper::gmtime();
                $order['pay_status'] = PS_PAYED;
                $order['pay_time'] = TimeHelper::gmtime();
                $order['order_amount'] = 0;
            }

            $order['integral_money'] = $total['integral_money'];
            $order['integral'] = $total['integral'];

            $order['from_ad'] = Session::get('from_ad', '0');
            $order['referer'] = addslashes(Session::get('referer', ''));

            // 记录扩展信息
            if ($flow_type != CART_GENERAL_GOODS) {
                $order['extension_code'] = Session::get('extension_code');
                $order['extension_id'] = Session::get('extension_id');
            }

            $affiliate = unserialize(cfg('affiliate'));
            if (isset($affiliate['on']) && $affiliate['on'] === 1 && $affiliate['config']['separate_by'] === 1) {
                // 推荐订单分成
                $parent_id = MainHelper::get_affiliate();
                if ($user_id === $parent_id) {
                    $parent_id = 0;
                }
            } elseif (isset($affiliate['on']) && $affiliate['on'] === 1 && $affiliate['config']['separate_by'] === 0) {
                // 推荐注册分成
                $parent_id = 0;
            } else {
                // 分成功能关闭
                $parent_id = 0;
            }
            $order['parent_id'] = $parent_id;

            // 插入订单表
            $error_no = 0;
            do {
                $order['order_sn'] = OrderHelper::get_order_sn(); // 获取新订单号
                try {
                    $new_order_id = DB::table('order_info')->insertGetId($order);
                    $error_no = 0;
                } catch (\Exception $e) {
                    $error_no = $e->getCode();
                    if ($error_no != 1062) {
                        exit($e->getMessage());
                    }
                }
            } while ($error_no === 1062); // 如果是订单号重复则重新提交数据

            $order['order_id'] = $new_order_id;

            // 插入订单商品
            $where_cart = Session::get('user_id') ? "user_id = '".intval(Session::get('user_id'))."'" : "session_id = '".SESS_ID."'";
            $sql = 'INSERT INTO '.ecs()->table('order_goods').'( '.
                'order_id, goods_id, goods_name, goods_sn, product_id, goods_number, market_price, '.
                'goods_price, goods_attr, is_real, extension_code, parent_id, is_gift, goods_attr_id) '.
                " SELECT '$new_order_id', goods_id, goods_name, goods_sn, product_id, goods_number, market_price, ".
                'goods_price, goods_attr, is_real, extension_code, parent_id, is_gift, goods_attr_id'.
                ' FROM '.ecs()->table('user_cart').
                ' WHERE '.$where_cart." AND rec_type = '$flow_type'";
            DB::statement($sql);
            // 修改拍卖活动状态
            if ($order['extension_code'] === 'auction') {
                DB::table('goods_activity')
                    ->where('act_id', $order['extension_id'])
                    ->update(['is_finished' => 2]);
            }

            // 处理余额、积分、红包
            if ($order['user_id'] > 0 && $order['surplus'] > 0) {
                CommonHelper::log_account_change($order['user_id'], $order['surplus'] * (-1), 0, 0, 0, sprintf(lang('pay_order'), $order['order_sn']));
            }
            if ($order['user_id'] > 0 && $order['integral'] > 0) {
                CommonHelper::log_account_change($order['user_id'], 0, 0, 0, $order['integral'] * (-1), sprintf(lang('pay_order'), $order['order_sn']));
            }

            if ($order['bonus_id'] > 0 && $temp_amout > 0) {
                OrderHelper::use_bonus($order['bonus_id'], $new_order_id);
            }

            // 如果使用库存，且下订单时减库存，则减少库存
            if (cfg('use_storage') === '1' && cfg('stock_dec_time') === SDT_PLACE) {
                OrderHelper::change_order_goods_storage($order['order_id'], true, SDT_PLACE);
            }

            // 给商家发邮件
            // 增加是否给客服发送邮件选项
            if (cfg('send_service_email') && cfg('service_email') != '') {
                $tpl = CommonHelper::get_mail_template('remind_of_new_order');
                $this->assign('order', $order);
                $this->assign('goods_list', $cart_goods);
                $this->assign('shop_name', cfg('shop_name'));
                $this->assign('send_date', date(cfg('time_format')));
                $content = $this->fetch('str:'.$tpl['template_content']);
                BaseHelper::send_mail(cfg('shop_name'), cfg('service_email'), $tpl['template_subject'], $content, $tpl['is_html']);
            }

            // 如果需要，发短信
            if (cfg('sms_order_placed') === '1' && cfg('sms_shop_mobile') != '') {
                $sms = new sms;
                $msg = $order['pay_status'] === PS_UNPAYED ?
                    lang('order_placed_sms') : lang('order_placed_sms').'['.lang('sms_paid').']';
                $sms->send(cfg('sms_shop_mobile'), sprintf($msg, $order['consignee'], $order['tel']), '', 13, 1);
            }

            // 如果订单金额为0 处理虚拟卡
            if ($order['order_amount'] <= 0) {
                $res = (clone $cart_query)
                    ->select('goods_id', 'goods_name', 'goods_number as num')
                    ->where('is_real', 0)
                    ->where('extension_code', 'virtual_card')
                    ->where('rec_type', $flow_type)
                    ->get()
                    ->map(fn ($item) => (array) $item)
                    ->all();

                $virtual_goods = [];
                foreach ($res as $row) {
                    $virtual_goods['virtual_card'][] = ['goods_id' => $row['goods_id'], 'goods_name' => $row['goods_name'], 'num' => $row['num']];
                }

                if ($virtual_goods and $flow_type != CART_GROUP_BUY_GOODS) {
                    // 虚拟卡发货
                    if (CommonHelper::virtual_goods_ship($virtual_goods, $msg, $order['order_sn'], true)) {
                        // 如果没有实体商品，修改发货状态，送积分和红包
                        $real_goods_exists = DB::table('order_goods')
                            ->where('order_id', $order['order_id'])
                            ->where('is_real', 1)
                            ->exists();
                        if (! $real_goods_exists) {
                            // 修改订单状态
                            OrderHelper::update_order($order['order_id'], ['shipping_status' => SS_SHIPPED, 'shipping_time' => TimeHelper::gmtime()]);

                            // 如果订单用户不为空，计算积分，并发给用户；发红包
                            if ($order['user_id'] > 0) {
                                // 取得用户信息
                                $user = OrderHelper::user_info($order['user_id']);

                                // 计算并发放积分
                                $integral = OrderHelper::integral_to_give($order);
                                CommonHelper::log_account_change($order['user_id'], 0, 0, intval($integral['rank_points']), intval($integral['custom_points']), sprintf(lang('order_gift_integral'), $order['order_sn']));

                                // 发放红包
                                OrderHelper::send_order_bonus($order['order_id']);
                            }
                        }
                    }
                }
            }

            // 清空购物车
            OrderHelper::clear_cart($flow_type);
            // 清除缓存，否则买了商品，但是前台页面读取缓存，商品数量不减少
            CommonHelper::clear_all_files();

            // 插入支付日志
            $order['log_id'] = ClipsHelper::insert_pay_log($new_order_id, $order['order_amount'], PAY_ORDER);

            // 取得支付信息，生成支付代码
            if ($order['order_amount'] > 0) {
                $payment = OrderHelper::payment_info($order['pay_id']);

                include_once 'includes/modules/payment/'.$payment['pay_code'].'.php';

                $pay_obj = new $payment['pay_code'];

                $pay_online = $pay_obj->get_code($order, OrderHelper::unserialize_config($payment['pay_config']));

                $order['pay_desc'] = $payment['pay_desc'];

                $this->assign('pay_online', $pay_online);
            }
            if (! empty($order['shipping_name'])) {
                $order['shipping_name'] = trim(stripcslashes($order['shipping_name']));
            }

            // 订单信息
            $this->assign('order', $order);
            $this->assign('total', $total);
            $this->assign('goods_list', $cart_goods);
            $this->assign('order_submit_back', sprintf(lang('order_submit_back'), lang('back_home'), lang('goto_user_center'))); // 返回提示

            CommonHelper::user_uc_call('add_feed', [$order['order_id'], BUY_GOODS]); // 推送feed到uc
            Session::forget('flow_consignee'); // 清除session中保存的收货人信息
            Session::forget('flow_order');
            Session::forget('direct_shopping');
        }

        /**
         * 更新购物车
         */ elseif ($_REQUEST['step'] === 'update_cart') {
            if (isset($_POST['goods_number']) && is_array($_POST['goods_number'])) {
                $this->flow_update_cart($_POST['goods_number']);
            }

            return $this->show_message(lang('update_cart_notice'), lang('back_to_cart'), 'flow.php');
        }

        /**
         * 删除购物车中的商品
         */ elseif ($_REQUEST['step'] === 'drop_goods') {
            $rec_id = intval($_GET['id']);
            $this->flow_drop_cart_goods($rec_id);

            return response()->redirectTo('flow.php');
        } // 把优惠活动加入购物车
        elseif ($_REQUEST['step'] === 'add_favourable') {
            $cart_query = DB::table('user_cart');
            if (Session::get('user_id')) {
                $cart_query->where('user_id', intval(Session::get('user_id')));
            } else {
                $cart_query->where('session_id', SESS_ID);
            }
            // 取得优惠活动信息
            $act_id = intval($_POST['act_id']);
            $favourable = GoodsHelper::favourable_info($act_id);
            if (empty($favourable)) {
                $this->show_message(lang('favourable_not_exist'));
            }

            // 判断用户能否享受该优惠
            if (! $this->favourable_available($favourable)) {
                $this->show_message(lang('favourable_not_available'));
            }

            // 检查购物车中是否已有该优惠
            $cart_favourable = $this->cart_favourable();
            if (favourable_used($favourable, $cart_favourable)) {
                $this->show_message(lang('favourable_used'));
            }

            // 赠品（特惠品）优惠
            if ($favourable['act_type'] === FAT_GOODS) {
                // 检查是否选择了赠品
                if (empty($_POST['gift'])) {
                    $this->show_message(lang('pls_select_gift'));
                }

                // 检查是否已在购物车
                $gift_name = (clone $cart_query)
                    ->where('rec_type', CART_GENERAL_GOODS)
                    ->where('is_gift', $act_id)
                    ->whereIn('goods_id', $_POST['gift'])
                    ->pluck('goods_name')
                    ->all();
                if (! empty($gift_name)) {
                    $this->show_message(sprintf(lang('gift_in_cart'), implode(',', $gift_name)));
                }

                // 检查数量是否超过上限
                $count = isset($cart_favourable[$act_id]) ? $cart_favourable[$act_id] : 0;
                if ($favourable['act_type_ext'] > 0 && $count + count($_POST['gift']) > $favourable['act_type_ext']) {
                    $this->show_message(lang('gift_count_exceed'));
                }

                // 添加赠品到购物车
                foreach ($favourable['gift'] as $gift) {
                    if (in_array($gift['id'], $_POST['gift'])) {
                        $this->add_gift_to_cart($act_id, $gift['id'], $gift['price']);
                    }
                }
            } elseif ($favourable['act_type'] === FAT_DISCOUNT) {
                $this->add_favourable_to_cart($act_id, $favourable['act_name'], $this->cart_favourable_amount($favourable) * (100 - $favourable['act_type_ext']) / 100);
            } elseif ($favourable['act_type'] === FAT_PRICE) {
                $this->add_favourable_to_cart($act_id, $favourable['act_name'], $favourable['act_type_ext']);
            }

            // 刷新购物车
            return response()->redirectTo('flow.php');
        } elseif ($_REQUEST['step'] === 'clear') {
            $user_id = Session::get('user_id', 0);
            $query = DB::table('user_cart')
                ->where('session_id', SESS_ID);
            if ($user_id > 0) {
                $query->orWhere('user_id', $user_id);
            }
            $query->delete();

            return response()->redirectTo('/');
        } elseif ($_REQUEST['step'] === 'drop_to_collect') {
            $user_id = Session::get('user_id', 0);
            if ($user_id > 0) {
                $rec_id = intval($_GET['id']);
                $goods_id = DB::table('user_cart')
                    ->where('rec_id', $rec_id)
                    ->where('user_id', $user_id)
                    ->value('goods_id');

                $exists = DB::table('user_collect')
                    ->where('user_id', $user_id)
                    ->where('goods_id', $goods_id)
                    ->exists();

                if (! $exists) {
                    DB::table('user_collect')->insert([
                        'user_id' => $user_id,
                        'goods_id' => $goods_id,
                        'add_time' => TimeHelper::gmtime(),
                    ]);
                }
                $this->flow_drop_cart_goods($rec_id);
            }

            return response()->redirectTo('flow.php');
        } // 验证红包序列号
        elseif ($_REQUEST['step'] === 'validate_bonus') {
            $bonus_sn = trim($_REQUEST['bonus_sn']);
            if (is_numeric($bonus_sn)) {
                $bonus = OrderHelper::bonus_info(0, $bonus_sn);
            } else {
                $bonus = [];
            }

            //    if (empty($bonus) || $bonus['user_id'] > 0 || $bonus['order_id'] > 0)
            //    {
            //        die(lang('bonus_sn_error'));
            //    }
            //    if ($bonus['min_goods_amount'] > OrderHelper::cart_amount())
            //    {
            //        die(sprintf(lang('bonus_min_amount_error'), CommonHelper::price_format($bonus['min_goods_amount'], false)));
            //    }
            //    die(sprintf(lang('bonus_is_ok'), CommonHelper::price_format($bonus['type_money'], false)));
            $bonus_kill = CommonHelper::price_format($bonus['type_money'], false);

            $result = ['error' => '', 'content' => ''];

            // 取得购物类型
            $flow_type = Session::get('flow_type', CART_GENERAL_GOODS);

            // 获得收货人信息
            $consignee = OrderHelper::get_consignee(Session::get('user_id'));

            // 对商品信息赋值
            $cart_goods = OrderHelper::cart_goods($flow_type); // 取得商品列表，计算合计

            if (empty($cart_goods) || ! OrderHelper::check_consignee_info($consignee, $flow_type)) {
                $result['error'] = lang('no_goods_in_cart');
            } else {
                // 取得购物流程设置
                $this->assign('config', cfg());

                // 取得订单信息
                $order = OrderHelper::flow_order_info();

                $user_id = Session::get('user_id', 0);
                if (((! empty($bonus) && $bonus['user_id'] === $user_id) || ($bonus['type_money'] > 0 && empty($bonus['user_id']))) && $bonus['order_id'] <= 0) {
                    // $order['bonus_kill'] = $bonus['type_money'];
                    $now = TimeHelper::gmtime();
                    if ($now > $bonus['use_end_date']) {
                        $order['bonus_id'] = '';
                        $result['error'] = lang('bonus_use_expire');
                    } else {
                        $order['bonus_id'] = $bonus['bonus_id'];
                        $order['bonus_sn'] = $bonus_sn;
                    }
                } else {
                    // $order['bonus_kill'] = 0;
                    $order['bonus_id'] = '';
                    $result['error'] = lang('invalid_bonus');
                }

                // 计算订单的费用
                $total = OrderHelper::order_fee($order, $cart_goods, $consignee);

                if ($total['goods_price'] < $bonus['min_goods_amount']) {
                    $order['bonus_id'] = '';
                    // 重新计算订单
                    $total = OrderHelper::order_fee($order, $cart_goods, $consignee);
                    $result['error'] = sprintf(lang('bonus_min_amount_error'), CommonHelper::price_format($bonus['min_goods_amount'], false));
                }

                $this->assign('total', $total);

                // 团购标志
                if ($flow_type === CART_GROUP_BUY_GOODS) {
                    $this->assign('is_group_buy', 1);
                }

                $result['content'] = $this->fetch('web::library/order_total');
            }

            return response()->json($result);
        }
        /**
         * 添加礼包到购物车
         */ elseif ($_REQUEST['step'] === 'add_package_to_cart') {
            $_POST['package_info'] = BaseHelper::json_str_iconv($_POST['package_info']);

            $result = ['error' => 0, 'message' => '', 'content' => '', 'package_id' => ''];

            if (empty($_POST['package_info'])) {
                $result['error'] = 1;

                return response()->json($result);
            }

            $package = json_decode($_POST['package_info']);

            // 如果是一步购物，先清空购物车
            if (cfg('one_step_buy') === '1') {
                OrderHelper::clear_cart();
            }

            // 商品数量是否合法
            if (! is_numeric($package->number) || intval($package->number) <= 0) {
                $result['error'] = 1;
                $result['message'] = lang('invalid_number');
            } else {
                // 添加到购物车
                if (OrderHelper::add_package_to_cart($package->package_id, $package->number)) {
                    if (cfg('cart_confirm') > 2) {
                        $result['message'] = '';
                    } else {
                        $result['message'] = cfg('cart_confirm') === 1 ? lang('addto_cart_success_1') : lang('addto_cart_success_2');
                    }

                    $result['content'] = InsertHelper::insert_cart_info();
                    $result['one_step_buy'] = cfg('one_step_buy');
                } else {
                    $result['message'] = $err->last_message();
                    $result['error'] = $err->error_no;
                    $result['package_id'] = stripslashes($package->package_id);
                }
            }
            $result['confirm_type'] = ! empty(cfg('cart_confirm')) ? cfg('cart_confirm') : 2;

            return response()->json($result);
        } else {
            $flow_type = isset($_REQUEST['type']) ? $_REQUEST['type'] : CART_GENERAL_GOODS;
            $flow_type = strip_tags($flow_type);
            $flow_type = BaseHelper::json_str_iconv($flow_type);
            // 标记购物流程为普通商品
            Session::put('flow_type', $flow_type);

            // 如果是一步购物，跳到结算中心
            if (cfg('one_step_buy') === '1') {
                return response()->redirectTo('flow.php?step=checkout');
            }

            // 取得商品列表，计算合计
            $cart_goods = OrderHelper::get_cart_goods($flow_type);
            $this->assign('goods_list', $cart_goods['goods_list']);
            $this->assign('total', $cart_goods['total']);

            // 购物车的描述的格式化
            $this->assign('shopping_money', sprintf(lang('shopping_money'), $cart_goods['total']['goods_price']));
            $this->assign('market_price_desc', sprintf(
                lang('than_market_price'),
                $cart_goods['total']['market_price'],
                $cart_goods['total']['saving'],
                $cart_goods['total']['save_rate']
            ));

            // 显示收藏夹内的商品
            $user_id = Session::get('user_id', 0);
            if ($user_id > 0) {
                $collection_goods = ClipsHelper::get_collection_goods($user_id);
                $this->assign('collection_goods', $collection_goods);
                $where_cart = "user_id = '".intval($user_id)."' ";
            } else {
                $where_cart = "session_id = '".SESS_ID."' ";
            }

            // 取得优惠活动
            $favourable_list = $this->favourable_list(Session::get('user_rank', 0));
            usort($favourable_list, 'cmp_favourable');

            $this->assign('favourable_list', $favourable_list);

            // 计算折扣
            $discount = OrderHelper::compute_discount();
            $this->assign('discount', $discount['discount']);
            $favour_name = empty($discount['name']) ? '' : implode(',', $discount['name']);
            $this->assign('your_discount', sprintf(lang('your_discount'), $favour_name, CommonHelper::price_format($discount['discount'])));

            // 增加是否在购物车里显示商品图
            $this->assign('show_goods_thumb', cfg('show_goods_in_cart'));

            // 增加是否在购物车里显示商品属性
            $this->assign('show_goods_attribute', cfg('show_attr_in_cart'));

            // 购物车中商品配件列表
            // 取得购物车中基本件ID
            $parent_list = DB::table('user_cart')
                ->whereRaw($where_cart)
                ->where('rec_type', $flow_type)
                ->where('is_gift', 0)
                ->where('extension_code', '<>', 'package_buy')
                ->where('parent_id', 0)
                ->pluck('goods_id')
                ->all();

            $fittings_list = GoodsHelper::get_goods_fittings($parent_list);

            $this->assign('fittings_list', $fittings_list);
        }

        $this->assign('currency_format', cfg('currency_format'));
        $this->assign('integral_scale', cfg('integral_scale'));
        $this->assign('step', $_REQUEST['step']);
        $this->assign_dynamic('shopping_flow');

        return $this->display('flow');
    }

    /**
     * 获得用户的可用积分
     *
     * @return integral
     */
    private function flow_available_points()
    {
        $user_id = Session::get('user_id', 0);
        $res = DB::table('user_cart as c')
            ->join('goods as g', 'c.goods_id', '=', 'g.goods_id')
            ->where(function ($query) use ($user_id) {
                if ($user_id > 0) {
                    $query->where('c.user_id', $user_id);
                } else {
                    $query->where('c.session_id', SESS_ID);
                }
            })
            ->where('c.is_gift', 0)
            ->where('g.integral', '>', 0)
            ->where('c.rec_type', CART_GENERAL_GOODS)
            ->selectRaw('SUM(g.integral * c.goods_number) as total_integral')
            ->first();

        $val = $res ? intval($res->total_integral) : 0;

        return OrderHelper::integral_of_value((float) $val);
    }

    /**
     * 更新购物车中的商品数量
     *
     * @param  array  $arr
     * @return void
     */
    private function flow_update_cart($arr)
    {
        $user_id = Session::get('user_id', 0);
        $where_cart = $user_id > 0 ? "user_id = '$user_id'" : "session_id = '".SESS_ID."'";

        // 处理
        foreach ($arr as $key => $val) {
            $val = intval(BaseHelper::make_semiangle($val));
            if ($val <= 0 || ! is_numeric($key)) {
                continue;
            }

            // 查询：
            $goods = DB::table('user_cart')
                ->select('goods_id', 'goods_attr_id', 'product_id', 'extension_code')
                ->where('rec_id', $key)
                ->whereRaw($where_cart)
                ->first();
            $goods = (array) $goods;

            $row = DB::table('goods as g')
                ->join('user_cart as c', 'g.goods_id', '=', 'c.goods_id')
                ->select('g.goods_name', 'g.goods_number')
                ->where('c.rec_id', $key)
                ->first();
            $row = (array) $row;

            // 查询：系统启用了库存，检查输入的商品数量是否有效
            if (intval(cfg('use_storage')) > 0 && $goods['extension_code'] != 'package_buy') {
                if ($row['goods_number'] < $val) {
                    return $this->show_message(sprintf(
                        lang('stock_insufficiency'),
                        $row['goods_name'],
                        $row['goods_number'],
                        $row['goods_number']
                    ));
                }
                // 是货品
                $goods['product_id'] = trim((string) $goods['product_id']);
                if (! empty($goods['product_id'])) {
                    $product_number = DB::table('goods_product')
                        ->where('goods_id', $goods['goods_id'])
                        ->where('product_id', $goods['product_id'])
                        ->value('product_number');
                    if ($product_number < $val) {
                        return $this->show_message(sprintf(
                            lang('stock_insufficiency'),
                            $row['goods_name'],
                            $product_number,
                            $product_number
                        ));
                    }
                }
            } elseif (intval(cfg('use_storage')) > 0 && $goods['extension_code'] === 'package_buy') {
                if (OrderHelper::judge_package_stock($goods['goods_id'], $val)) {
                    return $this->show_message(lang('package_stock_insufficiency'));
                }
            }

            // 查询：检查该项是否为基本件 以及是否存在配件
            // 此处配件是指添加商品时附加的并且是设置了优惠价格的配件 此类配件都有parent_id goods_number为1
            $offers_accessories_res = DB::table('user_cart as a')
                ->join('user_cart as b', 'b.parent_id', '=', 'a.goods_id')
                ->select('b.goods_number', 'b.rec_id')
                ->where('a.rec_id', $key)
                ->whereRaw('a.'.$where_cart)
                ->where('a.extension_code', '<>', 'package_buy')
                ->whereRaw('b.'.$where_cart)
                ->get()
                ->map(fn ($item) => (array) $item)
                ->all();

            // 订货数量大于0
            if ($val > 0) {
                // 判断是否为超出数量的优惠价格的配件 删除
                $row_num = 1;
                foreach ($offers_accessories_res as $offers_accessories_row) {
                    if ($row_num > $val) {
                        DB::table('user_cart')
                            ->whereRaw($where_cart)
                            ->where('rec_id', $offers_accessories_row['rec_id'])
                            ->limit(1)
                            ->delete();
                    }

                    $row_num++;
                }

                // 处理超值礼包
                if ($goods['extension_code'] === 'package_buy') {
                    // 更新购物车中的商品数量
                    DB::table('user_cart')
                        ->where('rec_id', $key)
                        ->whereRaw($where_cart)
                        ->update(['goods_number' => $val]);
                } // 处理普通商品或非优惠的配件
                else {
                    $attr_id = empty($goods['goods_attr_id']) ? [] : explode(',', (string) $goods['goods_attr_id']);
                    $goods_price = CommonHelper::get_final_price((string) $goods['goods_id'], (int) $val, true, $attr_id);

                    // 更新购物车中的商品数量
                    DB::table('user_cart')
                        ->where('rec_id', $key)
                        ->whereRaw($where_cart)
                        ->update(['goods_number' => $val, 'goods_price' => $goods_price]);
                }
            } // 订货数量等于0
            else {
                // 如果是基本件并且有优惠价格的配件则删除优惠价格的配件
                foreach ($offers_accessories_res as $offers_accessories_row) {
                    DB::table('user_cart')
                        ->whereRaw($where_cart)
                        ->where('rec_id', $offers_accessories_row['rec_id'])
                        ->limit(1)
                        ->delete();
                }

                DB::table('user_cart')
                    ->where('rec_id', $key)
                    ->whereRaw($where_cart)
                    ->delete();
            }

        }

        // 删除所有赠品
        DB::table('user_cart')
            ->whereRaw($where_cart)
            ->where('is_gift', '<>', 0)
            ->delete();
    }

    /**
     * 检查订单中商品库存
     *
     * @param  array  $arr
     * @return void
     */
    private function flow_cart_stock($arr)
    {
        $user_id = Session::get('user_id', 0);
        $where_cart = $user_id > 0 ? "user_id = '$user_id'" : "session_id = '".SESS_ID."'";
        foreach ($arr as $key => $val) {
            $val = intval(BaseHelper::make_semiangle($val));
            if ($val <= 0 || ! is_numeric($key)) {
                continue;
            }

            $goods = DB::table('user_cart')
                ->select('goods_id', 'goods_attr_id', 'extension_code')
                ->where('rec_id', $key)
                ->whereRaw($where_cart)
                ->first();
            $goods = (array) $goods;

            $row = DB::table('goods as g')
                ->join('user_cart as c', 'g.goods_id', '=', 'c.goods_id')
                ->select('g.goods_name', 'g.goods_number', 'c.product_id')
                ->where('c.rec_id', $key)
                ->first();
            $row = (array) $row;

            // 系统启用了库存，检查输入的商品数量是否有效
            if (intval(cfg('use_storage')) > 0 && $goods['extension_code'] != 'package_buy') {
                if ($row['goods_number'] < $val) {
                    return $this->show_message(sprintf(
                        lang('stock_insufficiency'),
                        $row['goods_name'],
                        $row['goods_number'],
                        $row['goods_number']
                    ));
                }

                // 是货品
                $row['product_id'] = trim((string) $row['product_id']);
                if (! empty($row['product_id'])) {
                    $product_number = DB::table('goods_product')
                        ->where('goods_id', $goods['goods_id'])
                        ->where('product_id', $row['product_id'])
                        ->value('product_number');
                    if ($product_number < $val) {
                        return $this->show_message(sprintf(
                            lang('stock_insufficiency'),
                            $row['goods_name'],
                            $row['goods_number'],
                            $row['goods_number']
                        ));
                    }
                }
            } elseif (intval(cfg('use_storage')) > 0 && $goods['extension_code'] === 'package_buy') {
                if (OrderHelper::judge_package_stock($goods['goods_id'], $val)) {
                    return $this->show_message(lang('package_stock_insufficiency'));
                }
            }
        }
    }

    /**
     * 删除购物车中的商品
     *
     * @param  int  $id
     * @return void
     */
    private function flow_drop_cart_goods($id)
    {
        $user_id = Session::get('user_id', 0);
        $where_cart = $user_id > 0 ? "user_id = '$user_id'" : "session_id = '".SESS_ID."'";

        // 取得商品id
        $row = DB::table('user_cart')
            ->where('rec_id', $id)
            ->first();
        $row = (array) $row;
        if ($row) {
            // 如果是超值礼包
            if ($row['extension_code'] === 'package_buy') {
                DB::table('user_cart')
                    ->whereRaw($where_cart)
                    ->where('rec_id', $id)
                    ->limit(1)
                    ->delete();
            } // 如果是普通商品，同时删除所有赠品及其配件
            elseif ($row['parent_id'] === 0 && $row['is_gift'] === 0) {
                // 检查购物车中该普通商品的不可单独销售的配件并删除
                $res = DB::table('user_cart as c')
                    ->join('activity_group as gg', 'gg.parent_id', '=', DB::raw("'".$row['goods_id']."'"))
                    ->join('goods as g', 'gg.goods_id', '=', 'g.goods_id')
                    ->select('c.rec_id')
                    ->where('c.goods_id', DB::raw('gg.goods_id'))
                    ->where('c.parent_id', $row['goods_id'])
                    ->where('c.extension_code', '<>', 'package_buy')
                    ->where('g.is_alone_sale', 0)
                    ->get()
                    ->map(fn ($item) => (array) $item)
                    ->all();

                $_del_ids = [$id];
                foreach ($res as $id_alone_sale_goods) {
                    $_del_ids[] = $id_alone_sale_goods['rec_id'];
                }

                DB::table('user_cart')
                    ->whereRaw($where_cart)
                    ->where(function ($query) use ($_del_ids, $row) {
                        $query->whereIn('rec_id', $_del_ids)
                            ->orWhere('parent_id', $row['goods_id'])
                            ->orWhere('is_gift', '<>', 0);
                    })
                    ->delete();
            } // 如果不是普通商品，只删除该商品即可
            else {
                DB::table('user_cart')
                    ->whereRaw($where_cart)
                    ->where('rec_id', $id)
                    ->limit(1)
                    ->delete();
            }
        }

        $this->flow_clear_cart_alone();
    }

    /**
     * 删除购物车中不能单独销售的商品
     *
     * @return void
     */
    private function flow_clear_cart_alone()
    {
        $user_id = Session::get('user_id', 0);
        $where_cart = $user_id > 0 ? "user_id = '$user_id'" : "session_id = '".SESS_ID."'";
        // 查询：购物车中所有不可以单独销售的配件
        $res = DB::table('user_cart as c')
            ->leftJoin('activity_group as gg', 'c.goods_id', '=', 'gg.goods_id')
            ->leftJoin('goods as g', 'c.goods_id', '=', 'g.goods_id')
            ->select('c.rec_id', 'gg.parent_id')
            ->whereRaw('c.'.$where_cart)
            ->where('c.extension_code', '<>', 'package_buy')
            ->where('gg.parent_id', '>', 0)
            ->where('g.is_alone_sale', 0)
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();
        $rec_id = [];
        foreach ($res as $row) {
            $rec_id[$row['rec_id']][] = $row['parent_id'];
        }

        if (empty($rec_id)) {
            return;
        }

        // 查询：购物车中所有商品
        $res = DB::table('user_cart')
            ->select('goods_id')
            ->whereRaw($where_cart)
            ->where('extension_code', '<>', 'package_buy')
            ->distinct()
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();
        $cart_good = [];
        foreach ($res as $row) {
            $cart_good[] = $row['goods_id'];
        }

        if (empty($cart_good)) {
            return;
        }

        // 如果购物车中不可以单独销售配件的基本件不存在则删除该配件
        $del_rec_ids = [];
        foreach ($rec_id as $key => $value) {
            foreach ($value as $v) {
                if (in_array($v, $cart_good)) {
                    continue 2;
                }
            }

            $del_rec_ids[] = $key;
        }

        if (empty($del_rec_ids)) {
            return;
        }

        // 删除
        DB::table('user_cart')
            ->whereRaw($where_cart)
            ->whereIn('rec_id', $del_rec_ids)
            ->delete();
    }

    /**
     * 比较优惠活动的函数，用于排序（把可用的排在前面）
     *
     * @param  array  $a  优惠活动a
     * @param  array  $b  优惠活动b
     * @return int 相等返回0，小于返回-1，大于返回1
     */
    private function cmp_favourable($a, $b)
    {
        if ($a['available'] === $b['available']) {
            if ($a['sort_order'] === $b['sort_order']) {
                return 0;
            } else {
                return $a['sort_order'] < $b['sort_order'] ? -1 : 1;
            }
        } else {
            return $a['available'] ? -1 : 1;
        }
    }

    /**
     * 取得某用户等级当前时间可以享受的优惠活动
     *
     * @param  int  $user_rank  用户等级id，0表示非会员
     * @return array
     */
    private function favourable_list($user_rank)
    {
        // 购物车中已有的优惠活动及数量
        $used_list = $this->cart_favourable();

        // 当前用户可享受的优惠活动
        $favourable_list = [];
        $user_rank = ','.$user_rank.',';
        $now = TimeHelper::gmtime();
        $res = DB::table('activity')
            ->whereRaw("CONCAT(',', user_rank, ',') LIKE '%".$user_rank."%'")
            ->where('start_time', '<=', $now)
            ->where('end_time', '>=', $now)
            ->where('act_type', FAT_GOODS)
            ->orderBy('sort_order')
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();
        foreach ($res as $favourable) {
            $favourable['start_time'] = TimeHelper::local_date(cfg('time_format'), $favourable['start_time']);
            $favourable['end_time'] = TimeHelper::local_date(cfg('time_format'), $favourable['end_time']);
            $favourable['formated_min_amount'] = CommonHelper::price_format($favourable['min_amount'], false);
            $favourable['formated_max_amount'] = CommonHelper::price_format($favourable['max_amount'], false);
            $favourable['gift'] = unserialize($favourable['gift']);

            foreach ($favourable['gift'] as $key => $value) {
                $favourable['gift'][$key]['formated_price'] = CommonHelper::price_format($value['price'], false);
                $is_sale = DB::table('goods')
                    ->where('is_on_sale', 1)
                    ->where('goods_id', $value['id'])
                    ->exists();
                if (! $is_sale) {
                    unset($favourable['gift'][$key]);
                }
            }

            $favourable['act_range_desc'] = $this->act_range_desc($favourable);
            $favourable['act_type_desc'] = sprintf(lang('fat_ext')[$favourable['act_type']], $favourable['act_type_ext']);

            // 是否能享受
            $favourable['available'] = $this->favourable_available($favourable);
            if ($favourable['available']) {
                // 是否尚未享受
                $favourable['available'] = ! $this->favourable_used($favourable, $used_list);
            }

            $favourable_list[] = $favourable;
        }

        return $favourable_list;
    }

    /**
     * 根据购物车判断是否可以享受某优惠活动
     *
     * @param  array  $favourable  优惠活动信息
     * @return bool
     */
    private function favourable_available($favourable)
    {
        // 会员等级是否符合
        $user_rank = Session::get('user_rank', 0);
        if (strpos(','.$favourable['user_rank'].',', ','.$user_rank.',') === false) {
            return false;
        }

        // 优惠范围内的商品总额
        $amount = $this->cart_favourable_amount($favourable);

        // 金额上限为0表示没有上限
        return $amount >= $favourable['min_amount'] &&
            ($amount <= $favourable['max_amount'] || $favourable['max_amount'] === 0);
    }

    /**
     * 取得优惠范围描述
     *
     * @param  array  $favourable  优惠活动
     * @return string
     */
    private function act_range_desc($favourable)
    {
        if ($favourable['act_range'] === FAR_BRAND) {
            return DB::table('goods_brand')
                ->whereIn('brand_id', explode(',', (string) $favourable['act_range_ext']))
                ->pluck('brand_name')
                ->implode(',');
        } elseif ($favourable['act_range'] === FAR_CATEGORY) {
            return DB::table('goods_category')
                ->whereIn('cat_id', explode(',', (string) $favourable['act_range_ext']))
                ->pluck('cat_name')
                ->implode(',');
        } elseif ($favourable['act_range'] === FAR_GOODS) {
            return DB::table('goods')
                ->whereIn('goods_id', explode(',', (string) $favourable['act_range_ext']))
                ->pluck('goods_name')
                ->implode(',');
        } else {
            return '';
        }
    }

    /**
     * 取得购物车中已有的优惠活动及数量
     *
     * @return array
     */
    private function cart_favourable()
    {
        $user_id = Session::get('user_id', 0);
        $where_cart = $user_id > 0 ? "user_id = '$user_id'" : "session_id = '".SESS_ID."'";
        $list = [];
        $res = DB::table('user_cart')
            ->select('is_gift', DB::raw('COUNT(*) AS num'))
            ->whereRaw($where_cart)
            ->where('rec_type', CART_GENERAL_GOODS)
            ->where('is_gift', '>', 0)
            ->groupBy('is_gift')
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();
        foreach ($res as $row) {
            $list[$row['is_gift']] = $row['num'];
        }

        return $list;
    }

    /**
     * 购物车中是否已经有某优惠
     *
     * @param  array  $favourable  优惠活动
     * @param  array  $cart_favourable购物车中已有的优惠活动及数量
     */
    private function favourable_used($favourable, $cart_favourable)
    {
        if ($favourable['act_type'] === FAT_GOODS) {
            return isset($cart_favourable[$favourable['act_id']]) &&
                $cart_favourable[$favourable['act_id']] >= $favourable['act_type_ext'] &&
                $favourable['act_type_ext'] > 0;
        } else {
            return isset($cart_favourable[$favourable['act_id']]);
        }
    }

    /**
     * 添加优惠活动（赠品）到购物车
     *
     * @param  int  $act_id  优惠活动id
     * @param  int  $id  赠品id
     * @param  float  $price  赠品价格
     */
    private function add_gift_to_cart($act_id, $id, $price)
    {
        Session::get('user_id', 0);
        $goods = DB::table('goods')
            ->where('goods_id', $id)
            ->first();
        $goods = (array) $goods;

        if ($goods) {
            DB::table('user_cart')->insert([
                'user_id' => Session::get('user_id', 0),
                'session_id' => SESS_ID,
                'goods_id' => $goods['goods_id'],
                'goods_sn' => $goods['goods_sn'],
                'goods_name' => $goods['goods_name'],
                'market_price' => $goods['market_price'],
                'goods_price' => $price,
                'goods_number' => 1,
                'is_real' => $goods['is_real'],
                'extension_code' => $goods['extension_code'],
                'parent_id' => 0,
                'is_gift' => $act_id,
                'rec_type' => CART_GENERAL_GOODS,
            ]);
        }
    }

    /**
     * 添加优惠活动（非赠品）到购物车
     *
     * @param  int  $act_id  优惠活动id
     * @param  string  $act_name  优惠活动name
     * @param  float  $amount  优惠金额
     */
    private function add_favourable_to_cart($act_id, $act_name, $amount)
    {
        DB::table('user_cart')->insert([
            'user_id' => Session::get('user_id', 0),
            'session_id' => SESS_ID,
            'goods_id' => 0,
            'goods_sn' => '',
            'goods_name' => $act_name,
            'market_price' => 0,
            'goods_price' => (-1) * $amount,
            'goods_number' => 1,
            'is_real' => 0,
            'extension_code' => '',
            'parent_id' => 0,
            'is_gift' => $act_id,
            'rec_type' => CART_GENERAL_GOODS,
        ]);
    }

    /**
     * 取得购物车中某优惠活动范围内的总金额
     *
     * @param  array  $favourable  优惠活动
     */
    private function cart_favourable_amount($favourable): float
    {
        $user_id = Session::get('user_id', 0);
        $where_cart = $user_id > 0 ? "c.user_id = '$user_id'" : "c.session_id = '".SESS_ID."'";
        // 查询优惠范围内商品总额的sql
        $query = DB::table('user_cart as c')
            ->join('goods as g', 'c.goods_id', '=', 'g.goods_id')
            ->whereRaw($where_cart)
            ->where('c.rec_type', CART_GENERAL_GOODS)
            ->where('c.is_gift', 0)
            ->where('c.goods_id', '>', 0);

        // 根据优惠范围修正sql
        if ($favourable['act_range'] === FAR_ALL) {
            // sql do not change
        } elseif ($favourable['act_range'] === FAR_CATEGORY) {
            // 取得优惠范围分类的所有下级分类
            $id_list = [];
            $cat_list = explode(',', $favourable['act_range_ext']);
            foreach ($cat_list as $id) {
                $id_list = array_merge($id_list, array_keys(CommonHelper::cat_list(intval($id), 0, false)));
            }

            $query->whereIn('g.cat_id', $id_list);
        } elseif ($favourable['act_range'] === FAR_BRAND) {
            $id_list = explode(',', $favourable['act_range_ext']);

            $query->whereIn('g.brand_id', $id_list);
        } else {
            $id_list = explode(',', $favourable['act_range_ext']);

            $query->whereIn('c.goods_id', $id_list);
        }

        // 优惠范围内的商品总额
        return (float) $query->sum(DB::raw('c.goods_price * c.goods_number'));
    }
}
