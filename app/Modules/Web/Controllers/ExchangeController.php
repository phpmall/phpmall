<?php

declare(strict_types=1);

namespace App\Modules\Web\Controllers;

use App\Helpers\CommonHelper;
use App\Helpers\GoodsHelper;
use App\Helpers\MainHelper;
use App\Helpers\OrderHelper;
use App\Helpers\TimeHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class ExchangeController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        /**
         * 积分兑换商品列表
         */
        if ($action === 'list') {
            // 初始化分页信息
            $page = isset($_REQUEST['page']) && intval($_REQUEST['page']) > 0 ? intval($_REQUEST['page']) : 1;
            $size = cfg('page_size') && intval(cfg('page_size')) > 0 ? intval(cfg('page_size')) : 10;
            $cat_id = isset($_REQUEST['cat_id']) && intval($_REQUEST['cat_id']) > 0 ? intval($_REQUEST['cat_id']) : 0;
            $integral_max = isset($_REQUEST['integral_max']) && intval($_REQUEST['integral_max']) > 0 ? intval($_REQUEST['integral_max']) : 0;
            $integral_min = isset($_REQUEST['integral_min']) && intval($_REQUEST['integral_min']) > 0 ? intval($_REQUEST['integral_min']) : 0;

            // 排序、显示方式以及类型
            $default_display_type = cfg('show_order_type') === '0' ? 'list' : (cfg('show_order_type') === '1' ? 'grid' : 'text');
            $default_sort_order_method = cfg('sort_order_method') === '0' ? 'DESC' : 'ASC';
            $default_sort_order_type = cfg('sort_order_type') === '0' ? 'goods_id' : (cfg('sort_order_type') === '1' ? 'exchange_integral' : 'last_update');

            $sort = (isset($_REQUEST['sort']) && in_array(trim(strtolower($_REQUEST['sort'])), ['goods_id', 'exchange_integral', 'last_update'])) ? trim($_REQUEST['sort']) : $default_sort_order_type;
            $order = (isset($_REQUEST['order']) && in_array(trim(strtoupper($_REQUEST['order'])), ['ASC', 'DESC'])) ? trim($_REQUEST['order']) : $default_sort_order_method;
            $ecsCookie = Cookie::get('ECS');
            $displayValue = is_array($ecsCookie) ? ($ecsCookie['display'] ?? '') : '';
            $display = (isset($_REQUEST['display']) && in_array(trim(strtolower($_REQUEST['display'])), ['list', 'grid', 'text'])) ? trim($_REQUEST['display']) : ($displayValue ?: $default_display_type);
            $display = in_array($display, ['list', 'grid', 'text']) ? $display : 'text';
            Cookie::queue('ECS[display]', $display, TimeHelper::gmtime() + 86400 * 7);

            // 页面的缓存ID
            $cache_id = sprintf('%X', crc32($cat_id.'-'.$display.'-'.$sort.'-'.$order.'-'.$page.'-'.$size.'-'.Session::get('user_rank', 0).'-'.
                cfg('lang').'-'.$integral_max.'-'.$integral_min));

            if (! $this->is_cached('exchange', $cache_id)) {
                // 如果页面没有被缓存则重新获取页面的内容

                $children = CommonHelper::get_children($cat_id);

                $cat = $this->get_cat_info($cat_id);   // 获得分类的相关信息

                if (! empty($cat)) {
                    $this->assign('keywords', htmlspecialchars($cat['keywords']));
                    $this->assign('description', htmlspecialchars($cat['cat_desc']));
                }

                $this->assign_template();

                $position = $this->assign_ur_here('exchange');
                $this->assign('page_title', $position['title']);    // 页面标题
                $this->assign('ur_here', $position['ur_here']);  // 当前位置

                $this->assign('categories', GoodsHelper::get_categories_tree());        // 分类树
                $this->assign('helps', MainHelper::get_shop_help());              // 网店帮助
                $this->assign('top_goods', GoodsHelper::get_top10());                  // 销售排行
                $this->assign('promotion_info', CommonHelper::get_promotion_info());         // 促销活动信息

                // 调查
                $vote = MainHelper::get_vote();
                if (! empty($vote)) {
                    $this->assign('vote_id', $vote['id']);
                    $this->assign('vote', $vote['content']);
                }

                $ext = ''; // 商品查询条件扩展

                // $this->assign('best_goods',      $this->get_exchange_recommend_goods('best', $children, $integral_min, $integral_max));
                // $this->assign('new_goods',       $this->get_exchange_recommend_goods('new',  $children, $integral_min, $integral_max));
                $this->assign('hot_goods', $this->get_exchange_recommend_goods('hot', $children, $integral_min, $integral_max));

                $count = $this->get_exchange_goods_count($children, $integral_min, $integral_max);
                $max_page = ($count > 0) ? ceil($count / $size) : 1;
                if ($page > $max_page) {
                    $page = $max_page;
                }
                $goodslist = $this->exchange_get_goods($children, $integral_min, $integral_max, $ext, $size, $page, $sort, $order);
                if ($display === 'grid') {
                    if (count($goodslist) % 2 != 0) {
                        $goodslist[] = [];
                    }
                }
                $this->assign('goods_list', $goodslist);
                $this->assign('category', $cat_id);
                $this->assign('integral_max', $integral_max);
                $this->assign('integral_min', $integral_min);

                MainHelper::assign_pager('exchange', $cat_id, $count, $size, $sort, $order, $page, '', '', $integral_min, $integral_max, $display); // 分页
                $this->assign_dynamic('exchange_list'); // 动态内容
            }

            $this->assign('feed_url', (cfg('rewrite') === 1) ? 'feed-typeexchange.xml' : 'feed.php?type=exchange'); // RSS URL

            return $this->display('exchange_list', $cache_id);
        }

        /**
         * 积分兑换商品详情
         */
        if ($action === 'view') {
            $goods_id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;

            $cache_id = $goods_id.'-'.Session::get('user_rank', 0).'-'.cfg('lang').'-exchange';
            $cache_id = sprintf('%X', crc32($cache_id));

            if (! $this->is_cached('exchange_goods', $cache_id)) {
                $this->assign('image_width', cfg('image_width'));
                $this->assign('image_height', cfg('image_height'));
                $this->assign('helps', MainHelper::get_shop_help()); // 网店帮助
                $this->assign('id', $goods_id);
                $this->assign('type', 0);
                $this->assign('cfg', cfg());

                // 获得商品的信息
                $goods = $this->get_exchange_goods_info($goods_id);

                if ($goods === false) {
                    // 如果没有找到任何记录则跳回到首页
                    return response()->redirectTo('/');
                } else {
                    if ($goods['brand_id'] > 0) {
                        $goods['goods_brand_url'] = build_uri('brand', ['bid' => $goods['brand_id']], $goods['goods_brand']);
                    }

                    $goods['goods_style_name'] = GoodsHelper::add_style($goods['goods_name'], $goods['goods_name_style']);

                    $this->assign('goods', $goods);
                    $this->assign('goods_id', $goods['goods_id']);
                    $this->assign('categories', GoodsHelper::get_categories_tree());  // 分类树

                    // meta
                    $this->assign('keywords', htmlspecialchars($goods['keywords']));
                    $this->assign('description', htmlspecialchars($goods['goods_brief']));

                    $this->assign_template();

                    // 上一个商品下一个商品
                    $prev_gid = DB::table('activity_exchange as eg')
                        ->join('goods as g', 'eg.goods_id', '=', 'g.goods_id')
                        ->where('eg.goods_id', '>', $goods['goods_id'])
                        ->where('eg.is_exchange', 1)
                        ->where('g.is_delete', 0)
                        ->value('eg.goods_id');

                    if (! empty($prev_gid)) {
                        $prev_good['url'] = build_uri('exchange_goods', ['gid' => $prev_gid], $goods['goods_name']);
                        $this->assign('prev_good', $prev_good); // 上一个商品
                    }

                    $next_gid = DB::table('activity_exchange as eg')
                        ->join('goods as g', 'eg.goods_id', '=', 'g.goods_id')
                        ->where('eg.goods_id', '<', $goods['goods_id'])
                        ->where('eg.is_exchange', 1)
                        ->where('g.is_delete', 0)
                        ->max('eg.goods_id');

                    if (! empty($next_gid)) {
                        $next_good['url'] = build_uri('exchange_goods', ['gid' => $next_gid], $goods['goods_name']);
                        $this->assign('next_good', $next_good); // 下一个商品
                    }

                    // current position
                    $position = $this->assign_ur_here('exchange', $goods['goods_name']);
                    $this->assign('page_title', $position['title']);                    // 页面标题
                    $this->assign('ur_here', $position['ur_here']);                  // 当前位置

                    $properties = GoodsHelper::get_goods_properties($goods_id);  // 获得商品的规格和属性
                    $this->assign('properties', $properties['pro']);                              // 商品属性
                    $this->assign('specification', $properties['spe']);                              // 商品规格

                    $this->assign('pictures', GoodsHelper::get_goods_gallery($goods_id));                    // 商品相册

                    $this->assign_dynamic('exchange_goods');
                }
            }

            return $this->display('exchange_goods', $cache_id);
        }

        /**
         *  兑换
         */
        if ($action === 'buy') {
            // 查询：判断是否登录
            if (! isset($back_act) && isset($GLOBALS['_SERVER']['HTTP_REFERER'])) {
                $back_act = strpos($GLOBALS['_SERVER']['HTTP_REFERER'], 'exchange') ? $GLOBALS['_SERVER']['HTTP_REFERER'] : './index.php';
            }

            // 查询：判断是否登录
            if (Session::get('user_id', 0) <= 0) {
                $this->show_message(lang('eg_error_login'), [lang('back_up_page')], [$back_act], 'error');
            }

            // 查询：取得参数：商品id
            $goods_id = isset($_POST['goods_id']) ? intval($_POST['goods_id']) : 0;
            if ($goods_id <= 0) {
                return response()->redirectTo('/');
            }

            // 查询：取得兑换商品信息
            $goods = $this->get_exchange_goods_info($goods_id);
            if (empty($goods)) {
                return response()->redirectTo('/');
            }
            // 查询：检查兑换商品是否有库存
            if ($goods['goods_number'] === 0 && cfg('use_storage') === 1) {
                $this->show_message(lang('eg_error_number'), [lang('back_up_page')], [$back_act], 'error');
            }
            // 查询：检查兑换商品是否是取消
            if ($goods['is_exchange'] === 0) {
                $this->show_message(lang('eg_error_status'), [lang('back_up_page')], [$back_act], 'error');
            }

            $user_info = MainHelper::get_user_info(Session::get('user_id', 0));
            $user_points = $user_info['pay_points']; // 用户的积分总数
            if ($goods['exchange_integral'] > $user_points) {
                $this->show_message(lang('eg_error_integral'), [lang('back_up_page')], [$back_act], 'error');
            }

            // 查询：取得规格
            $specs = '';
            foreach ($_POST as $key => $value) {
                if (strpos($key, 'spec_') !== false) {
                    $specs .= ','.intval($value);
                }
            }
            $specs = trim($specs, ',');

            // 查询：如果商品有规格则取规格商品信息 配件除外
            if (! empty($specs)) {
                $_specs = explode(',', $specs);

                $product_info = GoodsHelper::get_products_info($goods_id, $_specs);
            }
            if (empty($product_info)) {
                $product_info = ['product_number' => '', 'product_id' => 0];
            }

            // 查询：商品存在规格 是货品 检查该货品库存
            if ((! empty($specs)) && ($product_info['product_number'] === 0) && (cfg('use_storage') === 1)) {
                $this->show_message(lang('eg_error_number'), [lang('back_up_page')], [$back_act], 'error');
            }

            // 查询：查询规格名称和值，不考虑价格
            $attr_list = [];
            $res = DB::table('goods_attr as g')
                ->select('a.attr_name', 'g.attr_value')
                ->join('goods_type_attribute as a', 'g.attr_id', '=', 'a.attr_id')
                ->whereRaw('g.goods_attr_id '.db_create_in($specs))
                ->get()
                ->map(fn ($item) => (array) $item)
                ->all();
            foreach ($res as $row) {
                $attr_list[] = $row['attr_name'].': '.$row['attr_value'];
            }
            $goods_attr = implode(chr(13).chr(10), $attr_list);

            // 更新：清空购物车中所有团购商品
            OrderHelper::clear_cart(CART_EXCHANGE_GOODS);

            // 更新：加入购物车
            $number = 1;
            $cart = [
                'user_id' => Session::get('user_id', 0),
                'session_id' => SESS_ID,
                'goods_id' => $goods['goods_id'],
                'product_id' => $product_info['product_id'],
                'goods_sn' => addslashes($goods['goods_sn']),
                'goods_name' => addslashes($goods['goods_name']),
                'market_price' => $goods['market_price'],
                'goods_price' => 0, // $goods['exchange_integral']
                'goods_number' => $number,
                'goods_attr' => addslashes($goods_attr),
                'goods_attr_id' => $specs,
                'is_real' => $goods['is_real'],
                'extension_code' => addslashes($goods['extension_code']),
                'parent_id' => 0,
                'rec_type' => CART_EXCHANGE_GOODS,
                'is_gift' => 0,
            ];
            DB::table('user_cart')->insert($cart);

            // 记录购物流程类型：团购
            Session::put('flow_type', CART_EXCHANGE_GOODS);
            Session::put('extension_code', 'exchange_goods');
            Session::put('extension_id', $goods_id);

            // 进入收货人页面
            return response()->redirectTo('flow.php?step=consignee');
        }
    }

    /**
     * 获得分类的信息
     *
     * @param  int  $cat_id
     * @return void
     */
    private function get_cat_info($cat_id)
    {
        $row = DB::table('goods_category')
            ->select('keywords', 'cat_desc', 'style', 'grade', 'filter_attr', 'parent_id')
            ->where('cat_id', $cat_id)
            ->first();

        return $row ? (array) $row : [];
    }

    /**
     * 获得分类下的商品
     *
     * @param  string  $children
     * @return array
     */
    private function exchange_get_goods($children, $min, $max, $ext, $size, $page, $sort, $order)
    {
        $display = $GLOBALS['display'];
        $where = 'eg.is_exchange = 1 AND g.is_delete = 0 AND '.
            "($children OR ".GoodsHelper::get_extension_goods($children).')';

        if ($min > 0) {
            $where .= " AND eg.exchange_integral >= $min ";
        }

        if ($max > 0) {
            $where .= " AND eg.exchange_integral <= $max ";
        }

        // 获得商品列表
        $res = DB::table('activity_exchange as eg')
            ->select('g.goods_id', 'g.goods_name', 'g.goods_name_style', 'eg.exchange_integral', 'g.goods_type', 'g.goods_brief', 'g.goods_thumb', 'g.goods_img', 'eg.is_hot')
            ->join('goods as g', 'eg.goods_id', '=', 'g.goods_id')
            ->whereRaw($where.' '.$ext)
            ->orderBy($sort, $order)
            ->offset(($page - 1) * $size)
            ->limit($size)
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();

        $arr = [];
        foreach ($res as $row) {
            // 处理商品水印图片
            $watermark_img = '';

            //        if ($row['is_new'] != 0)
            //        {
            //            $watermark_img = "watermark_new_small";
            //        }
            //        elseif ($row['is_best'] != 0)
            //        {
            //            $watermark_img = "watermark_best_small";
            //        }
            //        else
            if ($row['is_hot'] != 0) {
                $watermark_img = 'watermark_hot_small';
            }

            if ($watermark_img != '') {
                $arr[$row['goods_id']]['watermark_img'] = $watermark_img;
            }

            $arr[$row['goods_id']]['goods_id'] = $row['goods_id'];
            if ($display === 'grid') {
                $arr[$row['goods_id']]['goods_name'] = cfg('goods_name_length') > 0 ? Str::substr($row['goods_name'], cfg('goods_name_length')) : $row['goods_name'];
            } else {
                $arr[$row['goods_id']]['goods_name'] = $row['goods_name'];
            }
            $arr[$row['goods_id']]['name'] = $row['goods_name'];
            $arr[$row['goods_id']]['goods_brief'] = $row['goods_brief'];
            $arr[$row['goods_id']]['goods_style_name'] = GoodsHelper::add_style($row['goods_name'], $row['goods_name_style']);
            $arr[$row['goods_id']]['exchange_integral'] = $row['exchange_integral'];
            $arr[$row['goods_id']]['type'] = $row['goods_type'];
            $arr[$row['goods_id']]['goods_thumb'] = CommonHelper::get_image_path($row['goods_thumb']);
            $arr[$row['goods_id']]['goods_img'] = CommonHelper::get_image_path($row['goods_img']);
            $arr[$row['goods_id']]['url'] = build_uri('exchange_goods', ['gid' => $row['goods_id']], $row['goods_name']);
        }

        return $arr;
    }

    /**
     * 获得分类下的商品总数
     *
     * @param  string  $cat_id
     * @return int
     */
    private function get_exchange_goods_count($children, $min = 0, $max = 0, $ext = '')
    {
        $where = "eg.is_exchange = 1 AND g.is_delete = 0 AND ($children OR ".GoodsHelper::get_extension_goods($children).')';

        if ($min > 0) {
            $where .= " AND eg.exchange_integral >= $min ";
        }

        if ($max > 0) {
            $where .= " AND eg.exchange_integral <= $max ";
        }

        return DB::table('activity_exchange as eg')
            ->join('goods as g', 'eg.goods_id', '=', 'g.goods_id')
            ->whereRaw($where.' '.$ext)
            ->count();
    }

    /**
     * 获得指定分类下的推荐商品
     *
     * @param  string  $type  推荐类型，可以是 best, new, hot, promote
     * @param  string  $cats  分类的ID
     * @param  int  $min  商品积分下限
     * @param  int  $max  商品积分上限
     * @param  string  $ext  商品扩展查询
     * @return array
     */
    private function get_exchange_recommend_goods($type = '', $cats = '', $min = 0, $max = 0, $ext = '')
    {
        $order_type = cfg('recommend_order');
        $query = DB::table('activity_exchange as eg')
            ->select('g.goods_id', 'g.goods_name', 'g.goods_name_style', 'eg.exchange_integral', 'g.goods_brief', 'g.goods_thumb', 'g.goods_img', 'b.brand_name')
            ->leftJoin('goods as g', 'g.goods_id', '=', 'eg.goods_id')
            ->leftJoin('goods_brand as b', 'b.brand_id', '=', 'g.brand_id')
            ->where('eg.is_exchange', 1)
            ->where('g.is_delete', 0)
            ->whereRaw(trim($price_where.$ext, ' AND'));

        if (! empty($cats)) {
            $query->whereRaw('('.$cats.' OR '.GoodsHelper::get_extension_goods($cats).')');
        }

        switch ($type) {
            case 'best':
                $query->where('eg.is_best', 1);
                break;
            case 'new':
                $query->where('eg.is_new', 1);
                break;
            case 'hot':
                $query->where('eg.is_hot', 1);
                break;
        }

        if ($order_type === 0) {
            $query->orderBy('g.sort_order')->orderByDesc('g.last_update');
        } else {
            $query->inRandomOrder();
        }

        $res = $query->limit($num)->get()->map(fn ($item) => (array) $item)->all();

        $idx = 0;
        $goods = [];
        foreach ($res as $row) {
            $goods[$idx]['id'] = $row['goods_id'];
            $goods[$idx]['name'] = $row['goods_name'];
            $goods[$idx]['brief'] = $row['goods_brief'];
            $goods[$idx]['brand_name'] = $row['brand_name'];
            $goods[$idx]['short_name'] = cfg('goods_name_length') > 0 ?
                Str::substr($row['goods_name'], cfg('goods_name_length')) : $row['goods_name'];
            $goods[$idx]['exchange_integral'] = $row['exchange_integral'];
            $goods[$idx]['thumb'] = CommonHelper::get_image_path($row['goods_thumb']);
            $goods[$idx]['goods_img'] = CommonHelper::get_image_path($row['goods_img']);
            $goods[$idx]['url'] = build_uri('exchange_goods', ['gid' => $row['goods_id']], $row['goods_name']);

            $goods[$idx]['short_style_name'] = GoodsHelper::add_style($goods[$idx]['short_name'], $row['goods_name_style']);
            $idx++;
        }

        return $goods;
    }

    /**
     * 获得积分兑换商品的详细信息
     *
     * @param  int  $goods_id
     * @return void
     */
    private function get_exchange_goods_info($goods_id)
    {
        $row = DB::table('goods as g')
            ->select('g.*', 'c.measure_unit', 'b.brand_id', 'b.brand_name AS goods_brand', 'eg.exchange_integral', 'eg.is_exchange')
            ->leftJoin('activity_exchange as eg', 'g.goods_id', '=', 'eg.goods_id')
            ->leftJoin('goods_category as c', 'g.cat_id', '=', 'c.cat_id')
            ->leftJoin('goods_brand as b', 'g.brand_id', '=', 'b.brand_id')
            ->where('g.goods_id', $goods_id)
            ->where('g.is_delete', 0)
            ->groupBy('g.goods_id')
            ->first();

        $row = $row ? (array) $row : false;

        if ($row !== false) {
            // 处理商品水印图片
            $watermark_img = '';

            if ($row['is_new'] != 0) {
                $watermark_img = 'watermark_new';
            } elseif ($row['is_best'] != 0) {
                $watermark_img = 'watermark_best';
            } elseif ($row['is_hot'] != 0) {
                $watermark_img = 'watermark_hot';
            }

            if ($watermark_img != '') {
                $row['watermark_img'] = $watermark_img;
            }

            // 修正重量显示
            $row['goods_weight'] = (intval($row['goods_weight']) > 0) ?
                $row['goods_weight'].lang('kilogram') :
                ($row['goods_weight'] * 1000).lang('gram');

            // 修正上架时间显示
            $row['add_time'] = TimeHelper::local_date(cfg('date_format'), $row['add_time']);

            // 修正商品图片
            $row['goods_img'] = CommonHelper::get_image_path($row['goods_img']);
            $row['goods_thumb'] = CommonHelper::get_image_path($row['goods_thumb']);

            return $row;
        } else {
            return false;
        }
    }
}
