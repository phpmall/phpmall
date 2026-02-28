<?php

declare(strict_types=1);

namespace App\Modules\Web\Controllers;

use App\Helpers\CommonHelper;
use App\Helpers\GoodsHelper;
use App\Helpers\MainHelper;
use App\Helpers\OrderHelper;
use App\Helpers\TimeHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class SnatchController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        if ($_REQUEST['act'] === 'list') {
            // 默认显示页面
            $_REQUEST['act'] = 'main';
        }

        /**
         * 如果用没有指定活动id，将页面重定向到即将结束的活动
         */
        if (empty($_REQUEST['id'])) {
            $id = $this->get_last_snatch();
            if ($id) {
                $page = build_uri('snatch', ['sid' => $id]);

                return response()->redirectTo($page);
            } else {
                // 当前没有任何可默认的活动
                $id = 0;
            }
        } else {
            $id = intval($_REQUEST['id']);
        }

        if ($action === 'main') {
            $goods = $this->get_snatch($id);
            if ($goods) {
                $position = $this->assign_ur_here(0, $goods['snatch_name']);
                $myprice = $this->get_myprice($id);
                if ($goods['is_end']) {
                    // 如果活动已经结束,获取活动结果
                    $this->assign('result', CommonHelper::get_snatch_result($id));
                }
                $this->assign('id', $id);
                $this->assign('snatch_goods', $goods); // 竞价商品
                $this->assign('myprice', $this->get_myprice($id));
                if ($goods['product_id'] > 0) {
                    $goods_specifications = CommonHelper::get_specifications_list($goods['goods_id']);

                    $good_products = CommonHelper::get_good_products($goods['goods_id'], 'AND product_id = '.$goods['product_id']);

                    $_good_products = explode('|', $good_products[0]['goods_attr']);
                    $products_info = '';
                    foreach ($_good_products as $value) {
                        $products_info .= ' '.$goods_specifications[$value]['attr_name'].'：'.$goods_specifications[$value]['attr_value'];
                    }
                    $this->assign('products_info', $products_info);
                    unset($goods_specifications, $good_products, $_good_products, $products_info);
                }
            } else {
                $this->show_message(lang('now_not_snatch'));
            }

            // 调查
            $vote = MainHelper::get_vote();
            if (! empty($vote)) {
                $this->assign('vote_id', $vote['id']);
                $this->assign('vote', $vote['content']);
            }

            $this->assign_template();
            $this->assign_dynamic('snatch');
            $this->assign('page_title', $position['title']);
            $this->assign('ur_here', $position['ur_here']);
            $this->assign('categories', GoodsHelper::get_categories_tree()); // 分类树
            $this->assign('helps', MainHelper::get_shop_help());       // 网店帮助
            $this->assign('snatch_list', $this->get_snatch_list());     // 所有有效的夺宝奇兵列表
            $this->assign('price_list', $this->get_price_list($id));
            $this->assign('promotion_info', CommonHelper::get_promotion_info());
            $this->assign('feed_url', (cfg('rewrite') === 1) ? 'feed-typesnatch.xml' : 'feed.php?type=snatch'); // RSS URL

            return $this->display('snatch');
        }

        // 最新出价列表
        if ($action === 'new_price_list') {
            $this->assign('price_list', $this->get_price_list($id));

            return $this->display('library/snatch_price');
        }

        // 用户出价处理
        if ($action === 'bid') {
            $result = ['error' => 0, 'content' => ''];

            $price = isset($_POST['price']) ? floatval($_POST['price']) : 0;
            $price = round($price, 2);

            // 测试是否登陆
            if (! Session::has('user_id')) {
                $result['error'] = 1;
                $result['content'] = lang('not_login');

                return response()->json($result);
            }

            // 获取活动基本信息用于校验
            $row = (array) DB::table('goods_activity')
                ->where('act_id', $id)
                ->select('act_name as snatch_name', 'end_time', 'ext_info')
                ->first();

            if ($row) {
                $info = unserialize($row['ext_info']);
                if ($info) {
                    foreach ($info as $key => $val) {
                        $row[$key] = $val;
                    }
                }
            }

            if (empty($row)) {
                $result['error'] = 1;
                $result['content'] = 'Activity not found';

                return response()->json($result);
            }

            if ($row['end_time'] < TimeHelper::gmtime()) {
                $result['error'] = 1;
                $result['content'] = lang('snatch_is_end');

                return response()->json($result);
            }

            // 检查出价是否合理
            if ($price < $row['start_price'] || $price > $row['end_price']) {
                $result['error'] = 1;
                $result['content'] = sprintf(lang('not_in_range'), $row['start_price'], $row['end_price']);

                return response()->json($result);
            }

            // 检查用户是否已经出同一价格
            $count = DB::table('activity_snatch')
                ->where('snatch_id', $id)
                ->where('user_id', Session::get('user_id'))
                ->where('bid_price', $price)
                ->count();

            if ($count > 0) {
                $result['error'] = 1;
                $result['content'] = sprintf(lang('also_bid'), CommonHelper::price_format($price, false));

                return response()->json($result);
            }

            // 检查用户积分是否足够
            $pay_points = DB::table('user')
                ->where('user_id', Session::get('user_id'))
                ->value('pay_points');

            if ($row['cost_points'] > $pay_points) {
                $result['error'] = 1;
                $result['content'] = lang('lack_pay_points');

                return response()->json($result);
            }

            CommonHelper::log_account_change(Session::get('user_id'), 0, 0, 0, 0 - $row['cost_points'], sprintf(lang('snatch_log'), $row['snatch_name'])); // 扣除用户积分
            DB::table('activity_snatch')->insert([
                'snatch_id' => $id,
                'user_id' => Session::get('user_id'),
                'bid_price' => $price,
                'bid_time' => TimeHelper::gmtime(),
            ]);

            $this->assign('myprice', $this->get_myprice($id));
            $this->assign('id', $id);
            $result['content'] = $this->fetch('web::library/snatch');

            return response()->json($result);
        }

        /**
         * 购买商品
         */
        if ($action === 'buy') {
            if (empty($id)) {
                return response()->redirectTo('/');
            }

            if (empty(Session::get('user_id'))) {
                $this->show_message(lang('not_login'));
            }

            $snatch = $this->get_snatch($id);

            if (empty($snatch)) {
                return response()->redirectTo('/');
            }

            // 未结束，不能购买
            if (empty($snatch['is_end'])) {
                $page = build_uri('snatch', ['sid' => $id]);

                return response()->redirectTo($page);
            }

            $result = CommonHelper::get_snatch_result($id);

            if (Session::get('user_id') != $result['user_id']) {
                $this->show_message(lang('not_for_you'));
            }

            // 检查是否已经购买过
            if ($result['order_count'] > 0) {
                $this->show_message(lang('order_placed'));
            }

            // 处理规格属性
            $goods_attr = '';
            $goods_attr_id = '';
            if ($snatch['product_id'] > 0) {
                $product_info = CommonHelper::get_good_products($snatch['goods_id'], 'AND product_id = '.$snatch['product_id']);

                $goods_attr_id = str_replace('|', ',', $product_info[0]['goods_attr']);

                $attr_list = DB::table('goods_attr as g')
                    ->join('goods_type_attribute as a', 'g.attr_id', '=', 'a.attr_id')
                    ->whereIn('g.goods_attr_id', explode(',', $goods_attr_id))
                    ->select('a.attr_name', 'g.attr_value')
                    ->get()
                    ->map(fn ($item) => $item->attr_name.': '.$item->attr_value)
                    ->all();
                $goods_attr = implode('', $attr_list);
            } else {
                $snatch['product_id'] = 0;
            }

            // 清空购物车中所有商品
            OrderHelper::clear_cart(CART_SNATCH_GOODS);

            // 加入购物车
            $cart = [
                'user_id' => Session::get('user_id'),
                'session_id' => SESS_ID,
                'goods_id' => $snatch['goods_id'],
                'product_id' => $snatch['product_id'],
                'goods_sn' => addslashes($snatch['goods_sn']),
                'goods_name' => addslashes($snatch['goods_name']),
                'market_price' => $snatch['market_price'],
                'goods_price' => $result['buy_price'],
                'goods_number' => 1,
                'goods_attr' => $goods_attr,
                'goods_attr_id' => $goods_attr_id,
                'is_real' => $snatch['is_real'],
                'extension_code' => addslashes($snatch['extension_code']),
                'parent_id' => 0,
                'rec_type' => CART_SNATCH_GOODS,
                'is_gift' => 0,
            ];

            DB::table('user_cart')->insert($cart);

            // 记录购物流程类型：夺宝奇兵
            Session::put('flow_type', CART_SNATCH_GOODS);
            Session::put('extension_code', 'snatch');
            Session::put('extension_id', $id);

            // 进入收货人页面
            return response()->redirectTo('flow.php?step=consignee');
        }
    }

    /**
     * 取得用户对当前活动的所出过的价格
     *
     * @return void
     */
    private function get_myprice($id)
    {
        $my_only_price = [];
        $my_price = [];
        $pay_points = 0;
        $bid_price = [];
        $user_id = Session::get('user_id');
        if (! empty($user_id)) {
            // 取得用户所有价格
            $my_price = DB::table('activity_snatch')
                ->where('snatch_id', $id)
                ->where('user_id', $user_id)
                ->orderByDesc('bid_time')
                ->pluck('bid_price')
                ->all();

            if ($my_price) {
                // 取得用户唯一价格
                $my_only_price = DB::table('activity_snatch')
                    ->where('snatch_id', $id)
                    ->whereIn('bid_price', $my_price)
                    ->select('bid_price')
                    ->groupBy('bid_price')
                    ->havingRaw('count(*) = 1')
                    ->pluck('bid_price')
                    ->all();
            }

            for ($i = 0, $count = count($my_price); $i < $count; $i++) {
                $bid_price[] = [
                    'price' => CommonHelper::price_format($my_price[$i], false),
                    'is_only' => in_array($my_price[$i], $my_only_price),
                ];
            }

            $pay_points = DB::table('user')
                ->where('user_id', $user_id)
                ->value('pay_points');
            $pay_points = $pay_points.cfg('integral_name');
        }

        // 活动结束时间
        $end_time = DB::table('goods_activity')
            ->where('act_id', $id)
            ->where('act_type', GAT_SNATCH)
            ->value('end_time');
        $my_price = [
            'pay_points' => $pay_points,
            'bid_price' => $bid_price,
            'is_end' => TimeHelper::gmtime() > $end_time,
        ];

        return $my_price;
    }

    /**
     * 取得当前活动的前n个出价
     *
     * @param  int  $num  列表个数(取前5个)
     * @return void
     */
    private function get_price_list($id, $num = 5)
    {
        $res = DB::table('activity_snatch as t1')
            ->join('user as t2', 't1.user_id', '=', 't2.user_id')
            ->where('snatch_id', $id)
            ->select('t1.log_id', 't1.bid_price', 't2.user_name')
            ->orderByDesc('t1.log_id')
            ->limit($num)
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();
        $price_list = [];
        foreach ($res as $row) {
            $price_list[] = ['bid_price' => CommonHelper::price_format($row['bid_price'], false), 'user_name' => $row['user_name']];
        }

        return $price_list;
    }

    /**
     * 取的最近的几次活动。
     *
     *
     * @return void
     */
    private function get_snatch_list($num = 10)
    {
        $now = TimeHelper::gmtime();
        $res = DB::table('goods_activity')
            ->where('start_time', '<=', $now)
            ->where('act_type', GAT_SNATCH)
            ->select('act_id as snatch_id', 'act_name as snatch_name', 'end_time')
            ->orderByDesc('end_time')
            ->limit($num)
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();
        foreach ($res as $row) {
            $overtime = $row['end_time'] > $now ? 0 : 1;
            $snatch_list[] = [
                'snatch_id' => $row['snatch_id'],
                'snatch_name' => $row['snatch_name'],
                'overtime' => $overtime,
                'url' => build_uri('snatch', ['sid' => $row['snatch_id']]),
            ];
        }

        return $snatch_list;
    }

    /**
     * 取得当前活动信息
     *
     *
     * @return 活动名称
     */
    private function get_snatch($id)
    {
        $user_rank = Session::get('user_rank', 0);
        $discount = Session::get('discount', 1);

        $goods = (array) DB::table('goods_activity as ga')
            ->leftJoin('goods as g', 'g.goods_id', '=', 'ga.goods_id')
            ->leftJoin('goods_member_price as mp', function ($join) use ($user_rank) {
                $join->on('mp.goods_id', '=', 'g.goods_id')
                    ->where('mp.user_rank', '=', $user_rank);
            })
            ->where('ga.act_id', $id)
            ->where('g.is_delete', 0)
            ->select(
                'g.goods_id',
                'g.goods_sn',
                'g.is_real',
                'g.goods_name',
                'g.extension_code',
                'g.market_price',
                'g.shop_price as org_price',
                'ga.product_id',
                DB::raw("IFNULL(mp.user_price, g.shop_price * '".$discount."') as shop_price"),
                'g.promote_price',
                'g.promote_start_date',
                'g.promote_end_date',
                'g.goods_brief',
                'g.goods_thumb',
                'ga.act_name as snatch_name',
                'ga.start_time',
                'ga.end_time',
                'ga.ext_info',
                'ga.act_desc as desc'
            )
            ->first();

        if ($goods) {
            $promote_price = GoodsHelper::bargain_price($goods['promote_price'], $goods['promote_start_date'], $goods['promote_end_date']);
            $goods['formated_market_price'] = CommonHelper::price_format($goods['market_price']);
            $goods['formated_shop_price'] = CommonHelper::price_format($goods['shop_price']);
            $goods['formated_promote_price'] = ($promote_price > 0) ? CommonHelper::price_format($promote_price) : '';
            $goods['goods_thumb'] = CommonHelper::get_image_path($goods['goods_thumb']);
            $goods['url'] = build_uri('goods', ['gid' => $goods['goods_id']], $goods['goods_name']);
            $goods['start_time'] = TimeHelper::local_date(cfg('time_format'), $goods['start_time']);

            $info = unserialize($goods['ext_info']);
            if ($info) {
                foreach ($info as $key => $val) {
                    $goods[$key] = $val;
                }
                $goods['is_end'] = TimeHelper::gmtime() > $goods['end_time'];
                $goods['formated_start_price'] = CommonHelper::price_format($goods['start_price']);
                $goods['formated_end_price'] = CommonHelper::price_format($goods['end_price']);
                $goods['formated_max_price'] = CommonHelper::price_format($goods['max_price']);
            }
            // 将结束日期格式化为格林威治标准时间时间戳
            $goods['gmt_end_time'] = $goods['end_time'];
            $goods['end_time'] = TimeHelper::local_date(cfg('time_format'), $goods['end_time']);
            $goods['snatch_time'] = sprintf(lang('snatch_start_time'), $goods['start_time'], $goods['end_time']);

            return $goods;
        } else {
            return false;
        }
    }

    /**
     * 获取最近要到期的活动id，没有则返回 0
     *
     *
     * @return void
     */
    private function get_last_snatch()
    {
        $now = TimeHelper::gmtime();

        return DB::table('goods_activity')
            ->where('start_time', '<', $now)
            ->where('end_time', '>', $now)
            ->where('act_type', GAT_SNATCH)
            ->orderBy('end_time')
            ->value('act_id');
    }
}
