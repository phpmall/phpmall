<?php

declare(strict_types=1);

namespace App\Modules\Web\Controllers;

use App\Helpers\CommonHelper;
use App\Helpers\GoodsHelper;
use App\Helpers\MainHelper;
use App\Helpers\TimeHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class GoodsController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        $affiliate = unserialize(cfg('affiliate'));
        $this->assign('affiliate', $affiliate);

        $goods_id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;

        /**
         * 改变属性、数量时重新计算商品价格
         */
        if ($action === 'price') {
            $res = ['err_msg' => '', 'result' => '', 'qty' => 1];

            $attr_id = isset($_REQUEST['attr']) ? explode(',', $_REQUEST['attr']) : [];
            $number = (isset($_REQUEST['number'])) ? intval($_REQUEST['number']) : 1;

            if ($goods_id === 0) {
                $res['err_msg'] = lang('err_change_attr');
                $res['err_no'] = 1;
            } else {
                if ($number === 0) {
                    $res['qty'] = $number = 1;
                } else {
                    $res['qty'] = $number;
                }

                $shop_price = CommonHelper::get_final_price($goods_id, $number, true, $attr_id);
                $res['result'] = CommonHelper::price_format($shop_price * $number);
            }

            return response()->json($res);
        }

        /**
         * 商品购买记录ajax处理
         */
        if ($action === 'gotopage') {
            $res = ['err_msg' => '', 'result' => ''];

            $goods_id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
            $page = (isset($_REQUEST['page'])) ? intval($_REQUEST['page']) : 1;

            if (! empty($goods_id)) {
                // 商品购买记录
                $bought_notes = DB::table('order_info as oi')
                    ->select('u.user_name', 'og.goods_number', 'oi.add_time', DB::raw('IF(oi.order_status IN (2, 3, 4), 0, 1) AS order_status'))
                    ->leftJoin('users as u', 'oi.user_id', '=', 'u.user_id')
                    ->join('order_goods as og', 'oi.order_id', '=', 'og.order_id')
                    ->whereRaw('? - oi.add_time < 2592000', [time()])
                    ->where('og.goods_id', $goods_id)
                    ->orderByDesc('oi.add_time')
                    ->offset((($page > 1) ? ($page - 1) : 0) * 5)
                    ->limit(5)
                    ->get()
                    ->map(fn ($item) => (array) $item)
                    ->all();

                foreach ($bought_notes as $key => $val) {
                    $bought_notes[$key]['add_time'] = TimeHelper::local_date('Y-m-d G:i:s', $val['add_time']);
                }

                $count = DB::table('order_info as oi')
                    ->leftJoin('users as u', 'oi.user_id', '=', 'u.user_id')
                    ->join('order_goods as og', 'oi.order_id', '=', 'og.order_id')
                    ->whereRaw('? - oi.add_time < 2592000', [time()])
                    ->where('og.goods_id', $goods_id)
                    ->count();

                // 商品购买记录分页样式
                $pager = [];
                $pager['page'] = $page;
                $pager['size'] = $size = 5;
                $pager['record_count'] = $count;
                $pager['page_count'] = $page_count = ($count > 0) ? intval(ceil($count / $size)) : 1;
                $pager['page_first'] = "javascript:gotoBuyPage(1,$goods_id)";
                $pager['page_prev'] = $page > 1 ? 'javascript:gotoBuyPage('.($page - 1).",$goods_id)" : 'javascript:;';
                $pager['page_next'] = $page < $page_count ? 'javascript:gotoBuyPage('.($page + 1).",$goods_id)" : 'javascript:;';
                $pager['page_last'] = $page < $page_count ? 'javascript:gotoBuyPage('.$page_count.",$goods_id)" : 'javascript:;';

                $this->assign('notes', $bought_notes);
                $this->assign('pager', $pager);

                $res['result'] = $this->fetch('web::library/bought_notes');
            }

            return response()->json($res);
        }

        $cache_id = $goods_id.'-'.Session::get('user_rank', 0).'-'.cfg('lang');
        $cache_id = sprintf('%X', crc32($cache_id));
        if (! $this->is_cached('goods', $cache_id)) {
            $this->assign('image_width', cfg('image_width'));
            $this->assign('image_height', cfg('image_height'));
            $this->assign('helps', MainHelper::get_shop_help()); // 网店帮助
            $this->assign('id', $goods_id);
            $this->assign('type', 0);
            $this->assign('cfg', cfg(''));
            $this->assign('promotion', CommonHelper::get_promotion_info($goods_id)); // 促销信息
            $this->assign('promotion_info', CommonHelper::get_promotion_info());

            // 获得商品的信息
            $goods = GoodsHelper::get_goods_info($goods_id);

            if ($goods === false) {
                // 如果没有找到任何记录则跳回到首页
                return response()->redirectTo('/');
            } else {
                if ($goods['brand_id'] > 0) {
                    $goods['goods_brand_url'] = build_uri('brand', ['bid' => $goods['brand_id']], $goods['goods_brand']);
                }

                $shop_price = $goods['shop_price'];
                $linked_goods = $this->get_linked_goods($goods_id);

                $goods['goods_style_name'] = GoodsHelper::add_style($goods['goods_name'], $goods['goods_name_style']);

                // 购买该商品可以得到多少钱的红包
                if ($goods['bonus_type_id'] > 0) {
                    $time = TimeHelper::gmtime();
                    $goods['bonus_money'] = DB::table('activity_bonus')
                        ->where('type_id', $goods['bonus_type_id'])
                        ->where('send_type', SEND_BY_GOODS)
                        ->where('send_start_date', '<=', $time)
                        ->where('send_end_date', '>=', $time)
                        ->value('type_money');
                    $goods['bonus_money'] = floatval($goods['bonus_money']);
                    if ($goods['bonus_money'] > 0) {
                        $goods['bonus_money'] = CommonHelper::price_format($goods['bonus_money']);
                    }
                }

                $this->assign('goods', $goods);
                $this->assign('goods_id', $goods['goods_id']);
                $this->assign('promote_end_time', $goods['gmt_end_time']);
                $this->assign('categories', GoodsHelper::get_categories_tree($goods['cat_id']));  // 分类树

                // meta
                $this->assign('keywords', htmlspecialchars($goods['keywords']));
                $this->assign('description', htmlspecialchars($goods['goods_brief']));

                $catlist = [];
                foreach (MainHelper::get_parent_cats($goods['cat_id']) as $k => $v) {
                    $catlist[] = $v['cat_id'];
                }

                $this->assign_template('c', $catlist);

                // 上一个商品下一个商品
                $prev_gid = DB::table('goods')
                    ->where('cat_id', $goods['cat_id'])
                    ->where('goods_id', '>', $goods['goods_id'])
                    ->where('is_on_sale', 1)
                    ->where('is_alone_sale', 1)
                    ->where('is_delete', 0)
                    ->orderBy('goods_id')
                    ->value('goods_id');
                if (! empty($prev_gid)) {
                    $prev_good['url'] = build_uri('goods', ['gid' => $prev_gid], $goods['goods_name']);
                    $this->assign('prev_good', $prev_good); // 上一个商品
                }

                $next_gid = DB::table('goods')
                    ->where('cat_id', $goods['cat_id'])
                    ->where('goods_id', '<', $goods['goods_id'])
                    ->where('is_on_sale', 1)
                    ->where('is_alone_sale', 1)
                    ->where('is_delete', 0)
                    ->max('goods_id');
                if (! empty($next_gid)) {
                    $next_good['url'] = build_uri('goods', ['gid' => $next_gid], $goods['goods_name']);
                    $this->assign('next_good', $next_good); // 下一个商品
                }

                $position = $this->assign_ur_here($goods['cat_id'], $goods['goods_name']);

                // current position
                $this->assign('page_title', $position['title']);                    // 页面标题
                $this->assign('ur_here', $position['ur_here']);                  // 当前位置

                $properties = GoodsHelper::get_goods_properties($goods_id);  // 获得商品的规格和属性

                $this->assign('properties', $properties['pro']);                              // 商品属性
                $this->assign('specification', $properties['spe']);                              // 商品规格
                $this->assign('attribute_linked', GoodsHelper::get_same_attribute_goods($properties));           // 相同属性的关联商品
                $this->assign('related_goods', $linked_goods);                                   // 关联商品
                $this->assign('goods_article_list', $this->get_linked_articles($goods_id));                  // 关联文章
                $this->assign('fittings', GoodsHelper::get_goods_fittings([$goods_id]));                   // 配件
                $this->assign('rank_prices', $this->get_user_rank_prices($goods_id, $shop_price));    // 会员等级价格
                $this->assign('pictures', GoodsHelper::get_goods_gallery($goods_id));                    // 商品相册
                $this->assign('bought_goods', $this->get_also_bought($goods_id));                      // 购买了该商品的用户还购买了哪些商品
                $this->assign('goods_rank', $this->get_goods_rank($goods_id));                       // 商品的销售排名

                // 获取tag
                $tag_array = MainHelper::get_tags($goods_id);
                $this->assign('tags', $tag_array);                                       // 商品的标记

                // 获取关联礼包
                $package_goods_list = $this->get_package_goods_list($goods['goods_id']);
                $this->assign('package_goods_list', $package_goods_list);    // 获取关联礼包

                $this->assign_dynamic('goods');
                $volume_price_list = CommonHelper::get_volume_price_list($goods['goods_id'], '1');
                $this->assign('volume_price_list', $volume_price_list);    // 商品优惠价格区间
            }
        }

        // 记录浏览历史
        $ecsHistory = Cookie::get('ECS');
        $historyStr = is_array($ecsHistory) ? ($ecsHistory['history'] ?? '') : '';
        if (! empty($historyStr)) {
            $history = explode(',', $historyStr);

            array_unshift($history, $goods_id);
            $history = array_unique($history);

            while (count($history) > cfg('history_number')) {
                array_pop($history);
            }

            Cookie::queue('ECS[history]', implode(',', $history), TimeHelper::gmtime() + 3600 * 24 * 30);
        } else {
            Cookie::queue('ECS[history]', $goods_id, TimeHelper::gmtime() + 3600 * 24 * 30);
        }

        // 更新点击次数
        DB::table('goods')
            ->where('goods_id', $goods_id)
            ->increment('click_count');

        $this->assign('now_time', TimeHelper::gmtime());           // 当前系统时间

        return $this->display('goods', $cache_id);
    }

    /**
     * 获得指定商品的关联商品
     *
     * @param  int  $goods_id
     * @return array
     */
    private function get_linked_goods($goods_id)
    {
        $res = DB::table('goods_link_goods as lg')
            ->select(
                'g.goods_id',
                'g.goods_name',
                'g.goods_thumb',
                'g.goods_img',
                'g.shop_price as org_price',
                DB::raw("IFNULL(mp.user_price, g.shop_price * '".Session::get('discount', 1)."') as shop_price"),
                'g.market_price',
                'g.promote_price',
                'g.promote_start_date',
                'g.promote_end_date'
            )
            ->leftJoin('goods as g', 'g.goods_id', '=', 'lg.link_goods_id')
            ->leftJoin('goods_member_price as mp', function ($join) {
                $join->on('mp.goods_id', '=', 'g.goods_id')
                    ->where('mp.user_rank', '=', Session::get('user_rank', 0));
            })
            ->where('lg.goods_id', $goods_id)
            ->where('g.is_on_sale', 1)
            ->where('g.is_alone_sale', 1)
            ->where('g.is_delete', 0)
            ->limit((int) cfg('related_goods_number'))
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();

        $arr = [];
        foreach ($res as $row) {
            $arr[$row['goods_id']]['goods_id'] = $row['goods_id'];
            $arr[$row['goods_id']]['goods_name'] = $row['goods_name'];
            $arr[$row['goods_id']]['short_name'] = cfg('goods_name_length') > 0 ?
                Str::substr($row['goods_name'], cfg('goods_name_length')) : $row['goods_name'];
            $arr[$row['goods_id']]['goods_thumb'] = CommonHelper::get_image_path($row['goods_thumb']);
            $arr[$row['goods_id']]['goods_img'] = CommonHelper::get_image_path($row['goods_img']);
            $arr[$row['goods_id']]['market_price'] = CommonHelper::price_format($row['market_price']);
            $arr[$row['goods_id']]['shop_price'] = CommonHelper::price_format($row['shop_price']);
            $arr[$row['goods_id']]['url'] = build_uri('goods', ['gid' => $row['goods_id']], $row['goods_name']);

            if ($row['promote_price'] > 0) {
                $arr[$row['goods_id']]['promote_price'] = GoodsHelper::bargain_price($row['promote_price'], $row['promote_start_date'], $row['promote_end_date']);
                $arr[$row['goods_id']]['formated_promote_price'] = CommonHelper::price_format($arr[$row['goods_id']]['promote_price']);
            } else {
                $arr[$row['goods_id']]['promote_price'] = 0;
            }
        }

        return $arr;
    }

    /**
     * 获得指定商品的关联文章
     *
     * @param  int  $goods_id
     * @return void
     */
    private function get_linked_articles($goods_id)
    {
        $res = DB::table('goods_article as g')
            ->select('a.article_id', 'a.title', 'a.file_url', 'a.open_type', 'a.add_time')
            ->join('article as a', 'g.article_id', '=', 'a.article_id')
            ->where('g.goods_id', $goods_id)
            ->where('a.is_open', 1)
            ->orderByDesc('a.add_time')
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();

        $arr = [];
        foreach ($res as $row) {
            $row['url'] = $row['open_type'] != 1 ?
                build_uri('article', ['aid' => $row['article_id']], $row['title']) : trim($row['file_url']);
            $row['add_time'] = TimeHelper::local_date(cfg('date_format'), $row['add_time']);
            $row['short_title'] = cfg('article_title_length') > 0 ?
                Str::substr($row['title'], cfg('article_title_length')) : $row['title'];

            $arr[] = $row;
        }

        return $arr;
    }

    /**
     * 获得指定商品的各会员等级对应的价格
     *
     * @param  int  $goods_id
     * @return array
     */
    private function get_user_rank_prices($goods_id, $shop_price)
    {
        $res = DB::table('user_rank as r')
            ->select('rank_id', 'r.rank_name', 'r.discount', DB::raw("IFNULL(mp.user_price, r.discount * $shop_price / 100) AS price"))
            ->leftJoin('goods_member_price as mp', function ($join) use ($goods_id) {
                $join->on('mp.goods_id', '=', DB::raw("'$goods_id'"))
                    ->on('mp.user_rank', '=', 'r.rank_id');
            })
            ->where(function ($query) {
                $query->where('r.show_price', 1)
                    ->orWhere('r.rank_id', Session::get('user_rank', 0));
            })
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();

        $arr = [];
        foreach ($res as $row) {
            $arr[$row['rank_id']] = [
                'rank_name' => htmlspecialchars($row['rank_name']),
                'price' => CommonHelper::price_format($row['price']),
            ];
        }

        return $arr;
    }

    /**
     * 获得购买过该商品的人还买过的商品
     *
     * @param  int  $goods_id
     * @return array
     */
    private function get_also_bought($goods_id)
    {
        $res = DB::table('order_goods as a')
            ->select(DB::raw('COUNT(b.goods_id) AS num'), 'g.goods_id', 'g.goods_name', 'g.goods_thumb', 'g.goods_img', 'g.shop_price', 'g.promote_price', 'g.promote_start_date', 'g.promote_end_date')
            ->leftJoin('order_goods as b', 'b.order_id', '=', 'a.order_id')
            ->leftJoin('goods as g', 'g.goods_id', '=', 'b.goods_id')
            ->where('a.goods_id', $goods_id)
            ->where('b.goods_id', '<>', $goods_id)
            ->where('g.is_on_sale', 1)
            ->where('g.is_alone_sale', 1)
            ->where('g.is_delete', 0)
            ->groupBy('b.goods_id', 'g.goods_id', 'g.goods_name', 'g.goods_thumb', 'g.goods_img', 'g.shop_price', 'g.promote_price', 'g.promote_start_date', 'g.promote_end_date')
            ->orderByDesc('num')
            ->limit((int) cfg('bought_goods'))
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();

        $key = 0;
        $arr = [];
        foreach ($res as $row) {
            $arr[$key]['goods_id'] = $row['goods_id'];
            $arr[$key]['goods_name'] = $row['goods_name'];
            $arr[$key]['short_name'] = cfg('goods_name_length') > 0 ?
                Str::substr($row['goods_name'], cfg('goods_name_length')) : $row['goods_name'];
            $arr[$key]['goods_thumb'] = CommonHelper::get_image_path($row['goods_thumb']);
            $arr[$key]['goods_img'] = CommonHelper::get_image_path($row['goods_img']);
            $arr[$key]['shop_price'] = CommonHelper::price_format($row['shop_price']);
            $arr[$key]['url'] = build_uri('goods', ['gid' => $row['goods_id']], $row['goods_name']);

            if ($row['promote_price'] > 0) {
                $arr[$key]['promote_price'] = GoodsHelper::bargain_price($row['promote_price'], $row['promote_start_date'], $row['promote_end_date']);
                $arr[$key]['formated_promote_price'] = CommonHelper::price_format($arr[$key]['promote_price']);
            } else {
                $arr[$key]['promote_price'] = 0;
            }

            $key++;
        }

        return $arr;
    }

    /**
     * 获得指定商品的销售排名
     *
     * @param  int  $goods_id
     * @return int
     */
    private function get_goods_rank($goods_id)
    {
        // 统计时间段
        $period = intval(cfg('top10_time'));
        if ($period === 1) { // 一年
            $ext = " AND o.add_time > '".TimeHelper::local_strtotime('-1 years')."'";
        } elseif ($period === 2) { // 半年
            $ext = " AND o.add_time > '".TimeHelper::local_strtotime('-6 months')."'";
        } elseif ($period === 3) { // 三个月
            $ext = " AND o.add_time > '".TimeHelper::local_strtotime('-3 months')."'";
        } elseif ($period === 4) { // 一个月
            $ext = " AND o.add_time > '".TimeHelper::local_strtotime('-1 months')."'";
        } else {
            $ext = '';
        }

        // 查询该商品销量
        $sales_count_query = DB::table('order_info as o')
            ->join('order_goods as g', 'o.order_id', '=', 'g.order_id')
            ->where('o.order_status', OS_CONFIRMED)
            ->whereIn('o.shipping_status', [SS_SHIPPED, SS_RECEIVED])
            ->whereIn('o.pay_status', [PS_PAYED, PS_PAYING])
            ->where('g.goods_id', $goods_id);

        if ($ext) {
            $sales_count_query->whereRaw(substr($ext, 5));
        }
        $sales_count = $sales_count_query->sum('g.goods_number');

        if ($sales_count > 0) {
            // 只有在商品销售量大于0时才去计算该商品的排行
            $query = DB::table('order_info as o')
                ->select(DB::raw('SUM(goods_number) AS num'))
                ->join('order_goods as g', 'o.order_id', '=', 'g.order_id')
                ->where('o.order_status', OS_CONFIRMED)
                ->whereIn('o.shipping_status', [SS_SHIPPED, SS_RECEIVED])
                ->whereIn('o.pay_status', [PS_PAYED, PS_PAYING]);

            if ($ext) {
                $query->whereRaw(substr($ext, 5));
            }

            $res = $query->groupBy('g.goods_id')
                ->having('num', '>', $sales_count)
                ->get();

            $rank = $res->count() + 1;

            if ($rank > 10) {
                $rank = 0;
            }
        } else {
            $rank = 0;
        }

        return $rank;
    }

    /**
     * 获得商品选定的属性的附加总价格
     *
     * @param  int  $goods_id
     * @param  array  $attr
     * @return void
     */
    private function get_attr_amount($goods_id, $attr)
    {
        return DB::table('goods_attr')
            ->where('goods_id', $goods_id)
            ->whereIn('goods_attr_id', $attr)
            ->sum('attr_price');
    }

    /**
     * 取得跟商品关联的礼包列表
     *
     * @param  string  $goods_id  商品编号
     * @return 礼包列表
     */
    private function get_package_goods_list($goods_id)
    {
        $now = TimeHelper::gmtime();
        $res = DB::table('goods_activity as ga')
            ->select('pg.goods_id', 'ga.act_id', 'ga.act_name', 'ga.act_desc', 'ga.goods_name', 'ga.start_time', 'ga.end_time', 'ga.is_finished', 'ga.ext_info')
            ->join('activity_package as pg', 'pg.package_id', '=', 'ga.act_id')
            ->where('ga.start_time', '<=', $now)
            ->where('ga.end_time', '>=', $now)
            ->where('pg.goods_id', $goods_id)
            ->groupBy('ga.act_id', 'pg.goods_id', 'ga.act_name', 'ga.act_desc', 'ga.goods_name', 'ga.start_time', 'ga.end_time', 'ga.is_finished', 'ga.ext_info')
            ->orderBy('ga.act_id')
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();

        foreach ($res as $tempkey => $value) {
            $subtotal = 0;
            $row = unserialize($value['ext_info']);
            unset($value['ext_info']);
            if ($row) {
                foreach ($row as $key => $val) {
                    $res[$tempkey][$key] = $val;
                }
            }

            $goods_res = DB::table('activity_package as pg')
                ->select(
                    'pg.package_id',
                    'pg.goods_id',
                    'pg.goods_number',
                    'pg.admin_id',
                    'p.goods_attr',
                    'g.goods_sn',
                    'g.goods_name',
                    'g.market_price',
                    'g.goods_thumb',
                    DB::raw("IFNULL(mp.user_price, g.shop_price * '".Session::get('discount', 1)."') AS rank_price")
                )
                ->leftJoin('goods as g', 'g.goods_id', '=', 'pg.goods_id')
                ->leftJoin('goods_product as p', 'p.product_id', '=', 'pg.product_id')
                ->leftJoin('goods_member_price as mp', function ($join) {
                    $join->on('mp.goods_id', '=', 'g.goods_id')
                        ->where('mp.user_rank', '=', Session::get('user_rank', 0));
                })
                ->where('pg.package_id', $value['act_id'])
                ->orderBy('pg.package_id')
                ->orderBy('pg.goods_id')
                ->get()
                ->map(fn ($item) => (array) $item)
                ->all();

            foreach ($goods_res as $key => $val) {
                $goods_id_array[] = $val['goods_id'];
                $goods_res[$key]['goods_thumb'] = CommonHelper::get_image_path($val['goods_thumb']);
                $goods_res[$key]['market_price'] = CommonHelper::price_format($val['market_price']);
                $goods_res[$key]['rank_price'] = CommonHelper::price_format($val['rank_price']);
                $subtotal += $val['rank_price'] * $val['goods_number'];
            }

            // 取商品属性
            $result_goods_attr = DB::table('goods_attr as ga')
                ->select('ga.goods_attr_id', 'ga.attr_value')
                ->join('goods_type_attribute as a', 'a.attr_id', '=', 'ga.attr_id')
                ->where('a.attr_type', 1)
                ->whereIn('ga.goods_id', $goods_id_array)
                ->get()
                ->map(fn ($item) => (array) $item)
                ->all();

            $_goods_attr = [];
            foreach ($result_goods_attr as $value) {
                $_goods_attr[$value['goods_attr_id']] = $value['attr_value'];
            }

            // 处理货品
            $format = '[%s]';
            foreach ($goods_res as $key => $val) {
                if ($val['goods_attr'] != '') {
                    $goods_attr_array = explode('|', $val['goods_attr']);

                    $goods_attr = [];
                    foreach ($goods_attr_array as $_attr) {
                        $goods_attr[] = $_goods_attr[$_attr];
                    }

                    $goods_res[$key]['goods_attr_str'] = sprintf($format, implode('，', $goods_attr));
                }
            }

            $res[$tempkey]['goods_list'] = $goods_res;
            $res[$tempkey]['subtotal'] = CommonHelper::price_format($subtotal);
            $res[$tempkey]['saving'] = CommonHelper::price_format(($subtotal - $res[$tempkey]['package_price']));
            $res[$tempkey]['package_price'] = CommonHelper::price_format($res[$tempkey]['package_price']);
        }

        return $res;
    }
}
