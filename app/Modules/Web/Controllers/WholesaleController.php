<?php

declare(strict_types=1);

namespace App\Modules\Web\Controllers;

use App\Helpers\BaseHelper;
use App\Helpers\CommonHelper;
use App\Helpers\GoodsHelper;
use App\Helpers\MainHelper;
use App\Helpers\OrderHelper;
use App\Helpers\TimeHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class WholesaleController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        // 如果没登录，提示登录
        if (Session::get('user_rank', 0) <= 0) {
            $this->show_message(lang('ws_user_rank'), lang('ws_return_home'), 'index.php');
        }

        /**
         * 批发活动列表
         */
        if ($action === 'list') {
            $search_category = empty($_REQUEST['search_category']) ? 0 : intval($_REQUEST['search_category']);
            $search_keywords = isset($_REQUEST['search_keywords']) ? trim($_REQUEST['search_keywords']) : '';
            $param = []; // 翻页链接所带参数列表

            // 查询条件：当前用户的会员等级（搜索关键字）
            $user_rank = Session::get('user_rank');
            $query = DB::table('activity_wholesale as w')
                ->join('goods as g', 'g.goods_id', '=', 'w.goods_id')
                ->where('w.enabled', 1)
                ->whereRaw("CONCAT(',', w.rank_ids, ',') LIKE '%,".$user_rank.",%'");

            // 搜索
            // 搜索类别
            if ($search_category) {
                $query->where('g.cat_id', $search_category);
                $param['search_category'] = $search_category;
                $this->assign('search_category', $search_category);
            }
            // 搜索商品名称和关键字
            if ($search_keywords) {
                $query->where(function ($q) use ($search_keywords) {
                    $q->where('g.keywords', 'like', '%'.$search_keywords.'%')
                        ->orWhere('g.goods_name', 'like', '%'.$search_keywords.'%');
                });
                $param['search_keywords'] = $search_keywords;
                $this->assign('search_keywords', $search_keywords);
            }

            // 取得批发商品总数
            $count = $query->count();

            if ($count > 0) {
                $default_display_type = cfg('show_order_type') === '0' ? 'list' : 'text';
                $display = (isset($_REQUEST['display']) && in_array(trim(strtolower($_REQUEST['display'])), ['list', 'text'])) ? trim($_REQUEST['display']) : ($request->cookie('ECS')['display'] ?? $default_display_type);
                $display = in_array($display, ['list', 'text']) ? $display : 'text';
                setcookie('ECS[display]', $display, TimeHelper::gmtime() + 86400 * 7, '', '', false, true);

                // 取得每页记录数
                $size = cfg('page_size') && intval(cfg('page_size')) > 0 ? intval(cfg('page_size')) : 10;

                // 计算总页数
                $page_count = ceil($count / $size);

                // 取得当前页
                $page = isset($_REQUEST['page']) && intval($_REQUEST['page']) > 0 ? intval($_REQUEST['page']) : 1;
                $page = $page > $page_count ? $page_count : $page;

                // 取得当前页的批发商品
                $wholesale_list = $this->wholesale_list($size, $page, $query);
                $this->assign('wholesale_list', $wholesale_list);

                $param['act'] = 'list';
                $pager = MainHelper::get_pager('wholesale.php', array_reverse($param, true), $count, $page, $size);
                $pager['display'] = $display;
                $this->assign('pager', $pager);

                // 批发商品购物车
                $this->assign('cart_goods', Session::get('wholesale_goods', []));
            }

            $this->assign_template();
            $position = $this->assign_ur_here();
            $this->assign('page_title', $position['title']);    // 页面标题
            $this->assign('ur_here', $position['ur_here']);  // 当前位置
            $this->assign('categories', GoodsHelper::get_categories_tree()); // 分类树
            $this->assign('helps', MainHelper::get_shop_help());       // 网店帮助
            $this->assign('top_goods', GoodsHelper::get_top10());           // 销售排行

            $this->assign_dynamic('wholesale');

            return $this->display('wholesale_list');
        }

        /**
         * 下载价格单
         */
        if ($action === 'price_list') {
            $data = lang('goods_name')."\t".lang('goods_attr')."\t".lang('number')."\t".lang('ws_price')."\t\n";
            $user_rank = Session::get('user_rank');
            $res = DB::table('activity_wholesale')
                ->where('enabled', 1)
                ->whereRaw("CONCAT(',', rank_ids, ',') LIKE '%,".$user_rank.",%'")
                ->get()
                ->map(fn ($item) => (array) $item)
                ->all();

            foreach ($res as $row) {
                $price_list = unserialize($row['prices']);
                foreach ($price_list as $attr_price) {
                    if ($attr_price['attr']) {
                        $goods_attr = DB::table('goods_attr')
                            ->whereIn('goods_attr_id', $attr_price['attr'])
                            ->pluck('attr_value')
                            ->implode(',');
                    } else {
                        $goods_attr = '';
                    }
                    foreach ($attr_price['qp_list'] as $qp) {
                        $data .= $row['goods_name']."\t".$goods_attr."\t".$qp['quantity']."\t".$qp['price']."\t\n";
                    }
                }
            }

            header('Content-type: application/vnd.ms-excel; charset=utf-8');
            header('Content-Disposition: attachment; filename=price_list.xls');
            if (EC_CHARSET === 'utf-8') {
                echo BaseHelper::ecs_iconv('UTF8', 'GB2312', $data);
            } else {
                echo $data;
            }
        }

        /**
         * 加入购物车
         */
        if ($action === 'add_to_cart') {
            // 取得参数
            $act_id = intval($_POST['act_id']);
            $goods_number = $_POST['goods_number'][$act_id];
            $attr_id = isset($_POST['attr_id']) ? $_POST['attr_id'] : [];
            if (isset($attr_id[$act_id])) {
                $goods_attr = $attr_id[$act_id];
            }

            // 用户提交必须全部通过检查，才能视为完成操作

            // 检查数量
            if (empty($goods_number) || (is_array($goods_number) && array_sum($goods_number) <= 0)) {
                $this->show_message(lang('ws_invalid_goods_number'));
            }

            // 确定购买商品列表
            $goods_list = [];
            if (is_array($goods_number)) {
                foreach ($goods_number as $key => $value) {
                    if (! $value) {
                        unset($goods_number[$key], $goods_attr[$key]);

                        continue;
                    }

                    $goods_list[] = ['number' => $goods_number[$key], 'goods_attr' => $goods_attr[$key]];
                }
            } else {
                $goods_list[0] = ['number' => $goods_number, 'goods_attr' => ''];
            }

            // 取批发相关数据
            $wholesale = GoodsHelper::wholesale_info($act_id);

            // 检查session中该商品，该属性是否存在
            $wholesale_goods = Session::get('wholesale_goods', []);
            if (! empty($wholesale_goods)) {
                foreach ($wholesale_goods as $goods) {
                    if ($goods['goods_id'] === $wholesale['goods_id']) {
                        if (empty($goods_attr)) {
                            $this->show_message(lang('ws_goods_attr_exists'));
                        } elseif (in_array($goods['goods_attr_id'], $goods_attr)) {
                            $this->show_message(lang('ws_goods_attr_exists'));
                        }
                    }
                }
            }

            // 获取购买商品的批发方案的价格阶梯 （一个方案多个属性组合、一个属性组合、一个属性、无属性）
            $attr_matching = false;
            foreach ($wholesale['price_list'] as $attr_price) {
                // 没有属性
                if (empty($attr_price['attr'])) {
                    $attr_matching = true;
                    $goods_list[0]['qp_list'] = $attr_price['qp_list'];
                    break;
                } // 有属性
                elseif (($key = $this->is_attr_matching($goods_list, $attr_price['attr'])) !== false) {
                    $attr_matching = true;
                    $goods_list[$key]['qp_list'] = $attr_price['qp_list'];
                }
            }
            if (! $attr_matching) {
                $this->show_message(lang('ws_attr_not_matching'));
            }

            // 检查数量是否达到最低要求
            foreach ($goods_list as $goods_key => $goods) {
                if ($goods['number'] < $goods['qp_list'][0]['quantity']) {
                    $this->show_message(lang('ws_goods_number_not_enough'));
                } else {
                    $goods_price = 0;
                    foreach ($goods['qp_list'] as $qp) {
                        if ($goods['number'] >= $qp['quantity']) {
                            $goods_list[$goods_key]['goods_price'] = $qp['price'];
                        } else {
                            break;
                        }
                    }
                }
            }

            // 写入session
            foreach ($goods_list as $goods_key => $goods) {
                // 属性名称
                $goods_attr_name = '';
                if (! empty($goods['goods_attr'])) {
                    foreach ($goods['goods_attr'] as $key => $attr) {
                        $attr['attr_name'] = htmlspecialchars($attr['attr_name']);
                        $goods['goods_attr'][$key]['attr_name'] = $attr['attr_name'];
                        $attr['attr_val'] = htmlspecialchars($attr['attr_val']);
                        $goods['goods_attr'][$key]['attr_name'] = $attr['attr_name'];
                        $goods_attr_name .= $attr['attr_name'].'：'.$attr['attr_val'].'&nbsp;';
                    }
                }

                // 总价
                $total = $goods['number'] * $goods['goods_price'];

                $wholesale_goods_item = [
                    'goods_id' => $wholesale['goods_id'],
                    'goods_name' => $wholesale['goods_name'],
                    'goods_attr_id' => $goods['goods_attr'],
                    'goods_attr' => $goods_attr_name,
                    'goods_number' => $goods['number'],
                    'goods_price' => $goods['goods_price'],
                    'subtotal' => $total,
                    'formated_goods_price' => CommonHelper::price_format($goods['goods_price'], false),
                    'formated_subtotal' => CommonHelper::price_format($total, false),
                    'goods_url' => build_uri('goods', ['gid' => $wholesale['goods_id']], $wholesale['goods_name']),
                ];

                Session::push('wholesale_goods', $wholesale_goods_item);
            }

            unset($goods_attr, $attr_id, $goods_list, $wholesale, $goods_attr_name);

            // 刷新页面
            return response()->redirectTo('wholesale.php');
        }

        /**
         * 从购物车删除
         */
        if ($action === 'drop_goods') {
            $key = intval($_REQUEST['key']);
            $wholesale_goods = Session::get('wholesale_goods', []);
            if (isset($wholesale_goods[$key])) {
                unset($wholesale_goods[$key]);
                Session::put('wholesale_goods', $wholesale_goods);
            }

            // 刷新页面
            return response()->redirectTo('wholesale.php');
        }

        /**
         * 提交订单
         */
        if ($action === 'submit_order') {
            $wholesale_goods = Session::get('wholesale_goods', []);
            // 检查购物车中是否有商品
            if (count($wholesale_goods) === 0) {
                $this->show_message(lang('no_goods_in_cart'));
            }

            // 检查备注信息
            if (empty($_POST['remark'])) {
                $this->show_message(lang('ws_remark'));
            }

            // 计算商品总额
            $goods_amount = 0;
            foreach ($wholesale_goods as $goods) {
                $goods_amount += $goods['subtotal'];
            }

            $order = [
                'postscript' => htmlspecialchars($_POST['remark']),
                'user_id' => Session::get('user_id'),
                'add_time' => TimeHelper::gmtime(),
                'order_status' => OS_UNCONFIRMED,
                'shipping_status' => SS_UNSHIPPED,
                'pay_status' => PS_UNPAYED,
                'goods_amount' => $goods_amount,
                'order_amount' => $goods_amount,
            ];

            // 插入订单表
            $error_no = 0;
            do {
                $order['order_sn'] = OrderHelper::get_order_sn(); // 获取新订单号
                try {
                    $new_order_id = DB::table('order_info')->insertGetId($order);
                    $error_no = 0;
                } catch (\Illuminate\Database\QueryException $e) {
                    $error_no = $e->getCode();
                    if ($error_no != 1062) {
                        exit($e->getMessage());
                    }
                }
            } while ($error_no === 1062); // 如果是订单号重复则重新提交数据

            $order['order_id'] = $new_order_id;

            // 插入订单商品
            foreach ($wholesale_goods as $goods) {
                // 如果存在货品
                $product_id = 0;
                if (! empty($goods['goods_attr_id'])) {
                    $goods_attr_id = [];
                    foreach ($goods['goods_attr_id'] as $value) {
                        $goods_attr_id[$value['attr_id']] = $value['attr_val_id'];
                    }

                    ksort($goods_attr_id);
                    $goods_attr_str = implode('|', $goods_attr_id);

                    $product_id = DB::table('goods_product')
                        ->where('goods_attr', $goods_attr_str)
                        ->where('goods_id', $goods['goods_id'])
                        ->value('product_id');
                    $product_id = $product_id ?: 0;
                }

                $goods_info = (array) DB::table('goods')
                    ->where('goods_id', $goods['goods_id'])
                    ->first();

                DB::table('order_goods')->insert([
                    'order_id' => $new_order_id,
                    'goods_id' => $goods['goods_id'],
                    'goods_name' => $goods['goods_name'],
                    'goods_sn' => $goods_info['goods_sn'],
                    'product_id' => $product_id,
                    'goods_number' => $goods['goods_number'],
                    'market_price' => $goods_info['market_price'],
                    'goods_price' => $goods['goods_price'],
                    'goods_attr' => $goods['goods_attr'],
                    'is_real' => $goods_info['is_real'],
                    'extension_code' => $goods_info['extension_code'],
                    'parent_id' => 0,
                    'is_gift' => 0,
                ]);
            }

            // 给商家发邮件
            if (cfg('service_email') != '') {
                $tpl = CommonHelper::get_mail_template('remind_of_new_order');
                $this->assign('order', $order);
                $this->assign('shop_name', cfg('shop_name'));
                $this->assign('send_date', date(cfg('time_format')));
                $content = $this->fetch('str:'.$tpl['template_content']);
                BaseHelper::send_mail(cfg('shop_name'), cfg('service_email'), $tpl['template_subject'], $content, $tpl['is_html']);
            }

            // 如果需要，发短信
            if (cfg('sms_order_placed') === '1' && cfg('sms_shop_mobile') != '') {
                $sms = new sms;
                $msg = lang('order_placed_sms');
                $sms->send(cfg('sms_shop_mobile'), sprintf($msg, $order['consignee'], $order['tel']), '', 13, 1);
            }

            // 清空购物车
            Session::forget('wholesale_goods');

            // 提示
            $this->show_message(sprintf(lang('ws_order_submitted'), $order['order_sn']), lang('ws_return_home'), 'index.php');
        }
    }

    /**
     * 取得某页的批发商品
     *
     * @param  int  $size  每页记录数
     * @param  int  $page  当前页
     * @param  string  $where  查询条件
     * @return array
     */
    private function wholesale_list($size, $page, $query)
    {
        $list = [];
        $res = $query->select('w.*', 'g.goods_thumb', 'g.goods_name as goods_name')
            ->offset(($page - 1) * $size)
            ->limit($size)
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();

        foreach ($res as $row) {
            if (empty($row['goods_thumb'])) {
                $row['goods_thumb'] = cfg('no_picture');
            }
            $row['goods_url'] = build_uri('goods', ['gid' => $row['goods_id']], $row['goods_name']);

            $properties = GoodsHelper::get_goods_properties($row['goods_id']);
            $row['goods_attr'] = $properties['pro'];

            $price_ladder = $this->get_price_ladder($row['goods_id']);
            $row['price_ladder'] = $price_ladder;

            $list[] = $row;
        }

        return $list;
    }

    /**
     * 商品价格阶梯
     *
     * @param  int  $goods_id  商品ID
     * @return array
     */
    private function get_price_ladder($goods_id)
    {
        $goods_attr_list = array_values(GoodsHelper::get_goods_attr($goods_id));
        $prices = DB::table('activity_wholesale')
            ->where('goods_id', $goods_id)
            ->value('prices');

        $arr = [];
        $_arr = unserialize($prices);
        if (is_array($_arr)) {
            foreach ($_arr as $key => $val) {
                // 显示属性
                if (! empty($val['attr'])) {
                    foreach ($val['attr'] as $attr_key => $attr_val) {
                        // 获取当前属性 $attr_key 的信息
                        $goods_attr = [];
                        foreach ($goods_attr_list as $goods_attr_val) {
                            if ($goods_attr_val['attr_id'] === $attr_key) {
                                $goods_attr = $goods_attr_val;
                                break;
                            }
                        }

                        // 重写商品规格的价格阶梯信息
                        if (! empty($goods_attr)) {
                            $arr[$key]['attr'][] = [
                                'attr_id' => $goods_attr['attr_id'],
                                'attr_name' => $goods_attr['attr_name'],
                                'attr_val' => (isset($goods_attr['goods_attr_list'][$attr_val]) ? $goods_attr['goods_attr_list'][$attr_val] : ''),
                                'attr_val_id' => $attr_val,
                            ];
                        }
                    }
                }

                // 显示数量与价格
                foreach ($val['qp_list'] as $index => $qp) {
                    $arr[$key]['qp_list'][$qp['quantity']] = CommonHelper::price_format($qp['price']);
                }
            }
        }

        return $arr;
    }

    /**
     * 商品属性是否匹配
     *
     * @param  array  $goods_list  用户选择的商品
     * @param  array  $reference  参照的商品属性
     * @return bool
     */
    private function is_attr_matching(&$goods_list, $reference)
    {
        foreach ($goods_list as $key => $goods) {
            // 需要相同的元素个数
            if (count($goods['goods_attr']) != count($reference)) {
                break;
            }

            // 判断用户提交与批发属性是否相同
            $is_check = true;
            if (is_array($goods['goods_attr'])) {
                foreach ($goods['goods_attr'] as $attr) {
                    if (! (array_key_exists($attr['attr_id'], $reference) && $attr['attr_val_id'] === $reference[$attr['attr_id']])) {
                        $is_check = false;
                        break;
                    }
                }
            }
            if ($is_check) {
                return $key;
                break;
            }
        }

        //    foreach ($goods_attr as $attr_id => $goods_attr_id)
        //    {
        //        if (isset($reference[$attr_id]) && $reference[$attr_id] != 0 && $reference[$attr_id] != $goods_attr_id)
        //        {
        //            return false;
        //        }
        //    }

        return false;
    }
}

// /**
// * 购物车中的商品属性与当前购买的商品属性是否匹配
// * @param   array   $goods_attr     用户选择的商品属性
// * @param   array   $reference      参照的商品属性
// * @return  bool
// */
// function is_attr_same($goods_attr, $reference)
// {
//    // 比较元素个数是否相同
//    if (count($goods_attr) === count($reference)) {
//    }
//
//    return true;
// }
