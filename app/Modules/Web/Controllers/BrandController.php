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

class BrandController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        // 获得请求的分类 ID
        if (! empty($_REQUEST['id'])) {
            $brand_id = intval($_REQUEST['id']);
        }
        if (! empty($_REQUEST['brand'])) {
            $brand_id = intval($_REQUEST['brand']);
        }
        if (empty($brand_id)) {
            // 缓存编号
            $cache_id = sprintf('%X', crc32(cfg('lang')));
            if (! $this->is_cached('brand_list', $cache_id)) {
                $this->assign_template();
                $position = $this->assign_ur_here('', lang('all_brand'));
                $this->assign('page_title', $position['title']);    // 页面标题
                $this->assign('ur_here', $position['ur_here']);  // 当前位置

                $this->assign('categories', GoodsHelper::get_categories_tree()); // 分类树
                $this->assign('helps', MainHelper::get_shop_help());       // 网店帮助
                $this->assign('top_goods', GoodsHelper::get_top10());           // 销售排行

                $this->assign('brand_list', CommonHelper::get_brands());
            }

            return $this->display('brand_list', $cache_id);
        }

        // 初始化分页信息
        $page = ! empty($_REQUEST['page']) && intval($_REQUEST['page']) > 0 ? intval($_REQUEST['page']) : 1;
        $size = ! empty(cfg('page_size')) && intval(cfg('page_size')) > 0 ? intval(cfg('page_size')) : 10;
        $cate = ! empty($_REQUEST['cat']) && intval($_REQUEST['cat']) > 0 ? intval($_REQUEST['cat']) : 0;

        // 排序、显示方式以及类型
        $default_display_type = cfg('show_order_type') === '0' ? 'list' : (cfg('show_order_type') === '1' ? 'grid' : 'text');
        $default_sort_order_method = cfg('sort_order_method') === '0' ? 'DESC' : 'ASC';
        $default_sort_order_type = cfg('sort_order_type') === '0' ? 'goods_id' : (cfg('sort_order_type') === '1' ? 'shop_price' : 'last_update');

        $sort = (isset($_REQUEST['sort']) && in_array(trim(strtolower($_REQUEST['sort'])), ['goods_id', 'shop_price', 'last_update'])) ? trim($_REQUEST['sort']) : $default_sort_order_type;
        $order = (isset($_REQUEST['order']) && in_array(trim(strtoupper($_REQUEST['order'])), ['ASC', 'DESC'])) ? trim($_REQUEST['order']) : $default_sort_order_method;
        $ecsCookie = Cookie::get('ECS');
        $displayValue = is_array($ecsCookie) ? ($ecsCookie['display'] ?? '') : '';
        $display = (isset($_REQUEST['display']) && in_array(trim(strtolower($_REQUEST['display'])), ['list', 'grid', 'text'])) ? trim($_REQUEST['display']) : ($displayValue ?: $default_display_type);
        $display = in_array($display, ['list', 'grid', 'text']) ? $display : 'text';
        Cookie::queue('ECS[display]', $display, TimeHelper::gmtime() + 86400 * 7);

        // 页面的缓存ID
        $cache_id = sprintf('%X', crc32($brand_id.'-'.$display.'-'.$sort.'-'.$order.'-'.$page.'-'.$size.'-'.Session::get('user_rank').'-'.cfg('lang').'-'.$cate));

        if (! $this->is_cached('brand', $cache_id)) {
            $brand_info = $this->get_brand_info($brand_id);

            if (empty($brand_info)) {
                return response()->redirectTo('/');
            }

            $this->assign('data_dir', DATA_DIR);
            $this->assign('keywords', htmlspecialchars($brand_info['brand_desc']));
            $this->assign('description', htmlspecialchars($brand_info['brand_desc']));

            // 赋值固定内容
            $this->assign_template();
            $position = $this->assign_ur_here($cate, $brand_info['brand_name']);
            $this->assign('page_title', $position['title']);   // 页面标题
            $this->assign('ur_here', $position['ur_here']); // 当前位置
            $this->assign('brand_id', $brand_id);
            $this->assign('category', $cate);

            $this->assign('categories', GoodsHelper::get_categories_tree());        // 分类树
            $this->assign('helps', MainHelper::get_shop_help());              // 网店帮助
            $this->assign('top_goods', GoodsHelper::get_top10());                  // 销售排行
            $this->assign('show_marketprice', cfg('show_marketprice'));
            $this->assign('brand_cat_list', $this->brand_related_cat($brand_id)); // 相关分类
            $this->assign('feed_url', (cfg('rewrite') === 1) ? "feed-b$brand_id.xml" : 'feed.php?brand='.$brand_id);

            // 调查
            $vote = MainHelper::get_vote();
            if (! empty($vote)) {
                $this->assign('vote_id', $vote['id']);
                $this->assign('vote', $vote['content']);
            }

            $this->assign('best_goods', $this->brand_recommend_goods('best', $brand_id, $cate));
            $this->assign('promotion_goods', $this->brand_recommend_goods('promote', $brand_id, $cate));
            $this->assign('brand', $brand_info);
            $this->assign('promotion_info', CommonHelper::get_promotion_info());

            $count = $this->goods_count_by_brand($brand_id, $cate);

            $goodslist = $this->brand_get_goods($brand_id, $cate, $size, $page, $sort, $order);

            if ($display === 'grid') {
                if (count($goodslist) % 2 != 0) {
                    $goodslist[] = [];
                }
            }
            $this->assign('goods_list', $goodslist);
            $this->assign('script_name', 'brand');

            MainHelper::assign_pager('brand', $cate, $count, $size, $sort, $order, $page, '', $brand_id, 0, 0, $display); // 分页
            $this->assign_dynamic('brand'); // 动态内容
        }

        return $this->display('brand', $cache_id);
    }

    /**
     * 获得指定品牌的详细信息
     *
     * @param  int  $id
     * @return void
     */
    private function get_brand_info($id)
    {
        $row = DB::table('goods_brand')
            ->where('brand_id', $id)
            ->first();

        return (array) $row;
    }

    /**
     * 获得指定品牌下的推荐和促销商品
     *
     * @param  string  $type
     * @param  int  $brand
     * @return array
     */
    private function brand_recommend_goods($type, $brand, $cat = 0)
    {
        static $result = null;

        $time = TimeHelper::gmtime();

        if ($result === null) {
            if ($cat > 0) {
                $cat_where = 'AND '.CommonHelper::get_children($cat);
            } else {
                $cat_where = '';
            }

            $result = DB::table('goods as g')
                ->select('g.goods_id', 'g.goods_name', 'g.market_price', 'g.shop_price AS org_price', 'g.promote_price', DB::raw("IFNULL(mp.user_price, g.shop_price * '".Session::get('discount', 1)."') AS shop_price"), 'promote_start_date', 'promote_end_date', 'g.goods_brief', 'g.goods_thumb', 'goods_img', 'b.brand_name', 'g.is_best', 'g.is_new', 'g.is_hot', 'g.is_promote')
                ->leftJoin('goods_brand as b', 'b.brand_id', '=', 'g.brand_id')
                ->leftJoin('goods_member_price as mp', function ($join) {
                    $join->on('mp.goods_id', '=', 'g.goods_id')
                        ->where('mp.user_rank', '=', Session::get('user_rank'));
                })
                ->where('g.is_on_sale', 1)
                ->where('g.is_alone_sale', 1)
                ->where('g.is_delete', 0)
                ->where('g.brand_id', $brand)
                ->where(function ($query) use ($time) {
                    $query->where('g.is_best', 1)
                        ->orWhere(function ($query) use ($time) {
                            $query->where('g.is_promote', 1)
                                ->where('promote_start_date', '<=', $time)
                                ->where('promote_end_date', '>=', $time);
                        });
                })
                ->whereRaw($cat_where ? substr($cat_where, 5) : '1=1')
                ->orderBy('g.sort_order')
                ->orderByDesc('g.last_update')
                ->get()
                ->map(fn ($item) => (array) $item)
                ->all();
        }

        // 取得每一项的数量限制
        $num = 0;
        $type2lib = ['best' => 'recommend_best', 'new' => 'recommend_new', 'hot' => 'recommend_hot', 'promote' => 'recommend_promotion'];
        $num = MainHelper::get_library_number($type2lib[$type]);

        $idx = 0;
        $goods = [];
        foreach ($result as $row) {
            if ($idx >= $num) {
                break;
            }

            if (
                ($type === 'best' && $row['is_best'] === 1) ||
                ($type === 'promote' && $row['is_promote'] === 1 &&
                    $row['promote_start_date'] <= $time && $row['promote_end_date'] >= $time)
            ) {
                if ($row['promote_price'] > 0) {
                    $promote_price = GoodsHelper::bargain_price($row['promote_price'], $row['promote_start_date'], $row['promote_end_date']);
                    $goods[$idx]['promote_price'] = $promote_price > 0 ? CommonHelper::price_format($promote_price) : '';
                } else {
                    $goods[$idx]['promote_price'] = '';
                }

                $goods[$idx]['id'] = $row['goods_id'];
                $goods[$idx]['name'] = $row['goods_name'];
                $goods[$idx]['brief'] = $row['goods_brief'];
                $goods[$idx]['brand_name'] = $row['brand_name'];
                $goods[$idx]['short_style_name'] = cfg('goods_name_length') > 0 ?
                    Str::substr($row['goods_name'], cfg('goods_name_length')) : $row['goods_name'];
                $goods[$idx]['market_price'] = CommonHelper::price_format($row['market_price']);
                $goods[$idx]['shop_price'] = CommonHelper::price_format($row['shop_price']);
                $goods[$idx]['thumb'] = CommonHelper::get_image_path($row['goods_thumb']);
                $goods[$idx]['goods_img'] = CommonHelper::get_image_path($row['goods_img']);
                $goods[$idx]['url'] = build_uri('goods', ['gid' => $row['goods_id']], $row['goods_name']);

                $idx++;
            }
        }

        return $goods;
    }

    /**
     * 获得指定的品牌下的商品总数
     *
     * @param  int  $brand_id
     * @param  int  $cate
     * @return int
     */
    private function goods_count_by_brand($brand_id, $cate = 0)
    {
        $query = DB::table('goods as g')
            ->where('brand_id', $brand_id)
            ->where('g.is_on_sale', 1)
            ->where('g.is_alone_sale', 1)
            ->where('g.is_delete', 0);

        if ($cate > 0) {
            $query->whereRaw(CommonHelper::get_children($cate));
        }

        return $query->count();
    }

    /**
     * 获得品牌下的商品
     *
     * @param  int  $brand_id
     * @return array
     */
    private function brand_get_goods($brand_id, $cate, $size, $page, $sort, $order)
    {
        $cate_where = ($cate > 0) ? 'AND '.CommonHelper::get_children($cate) : '';

        // 获得商品列表
        $res = DB::table('goods as g')
            ->select('g.goods_id', 'g.goods_name', 'g.market_price', 'g.shop_price AS org_price', DB::raw("IFNULL(mp.user_price, g.shop_price * '".Session::get('discount', 1)."') AS shop_price"), 'g.promote_price', 'g.promote_start_date', 'g.promote_end_date', 'g.goods_brief', 'g.goods_thumb', 'g.goods_img')
            ->leftJoin('goods_member_price as mp', function ($join) {
                $join->on('mp.goods_id', '=', 'g.goods_id')
                    ->where('mp.user_rank', '=', Session::get('user_rank'));
            })
            ->where('g.is_on_sale', 1)
            ->where('g.is_alone_sale', 1)
            ->where('g.is_delete', 0)
            ->where('g.brand_id', $brand_id)
            ->whereRaw($cate_where ? substr($cate_where, 5) : '1=1')
            ->orderBy($sort, $order)
            ->offset(($page - 1) * $size)
            ->limit($size)
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();

        $arr = [];
        foreach ($res as $row) {
            if ($row['promote_price'] > 0) {
                $promote_price = GoodsHelper::bargain_price($row['promote_price'], $row['promote_start_date'], $row['promote_end_date']);
            } else {
                $promote_price = 0;
            }

            $arr[$row['goods_id']]['goods_id'] = $row['goods_id'];
            if ($GLOBALS['display'] === 'grid') {
                $arr[$row['goods_id']]['goods_name'] = cfg('goods_name_length') > 0 ? Str::substr($row['goods_name'], cfg('goods_name_length')) : $row['goods_name'];
            } else {
                $arr[$row['goods_id']]['goods_name'] = $row['goods_name'];
            }
            $arr[$row['goods_id']]['market_price'] = CommonHelper::price_format($row['market_price']);
            $arr[$row['goods_id']]['shop_price'] = CommonHelper::price_format($row['shop_price']);
            $arr[$row['goods_id']]['promote_price'] = ($promote_price > 0) ? CommonHelper::price_format($promote_price) : '';
            $arr[$row['goods_id']]['goods_brief'] = $row['goods_brief'];
            $arr[$row['goods_id']]['goods_thumb'] = CommonHelper::get_image_path($row['goods_thumb']);
            $arr[$row['goods_id']]['goods_img'] = CommonHelper::get_image_path($row['goods_img']);
            $arr[$row['goods_id']]['url'] = build_uri('goods', ['gid' => $row['goods_id']], $row['goods_name']);
        }

        return $arr;
    }

    /**
     * 获得与指定品牌相关的分类
     *
     * @param  int  $brand
     * @return array
     */
    private function brand_related_cat($brand)
    {
        $arr[] = [
            'cat_id' => 0,
            'cat_name' => lang('all_category'),
            'url' => build_uri('brand', ['bid' => $brand], lang('all_category')),
        ];

        $res = DB::table('goods_category as c')
            ->join('goods as g', 'c.cat_id', '=', 'g.cat_id')
            ->where('g.brand_id', $brand)
            ->groupBy('g.cat_id')
            ->select('c.cat_id', 'c.cat_name', DB::raw('COUNT(g.goods_id) AS goods_count'))
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();

        foreach ($res as $row) {
            $row['url'] = build_uri('brand', ['cid' => $row['cat_id'], 'bid' => $brand], $row['cat_name']);
            $arr[] = $row;
        }

        return $arr;
    }
}
