<?php

declare(strict_types=1);

namespace App\Modules\Web\Controllers;

use App\Helpers\CommonHelper;
use App\Helpers\GoodsHelper;
use App\Helpers\MainHelper;
use App\Helpers\TimeHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class IndexController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        /**
         * 判断是否存在缓存，如果存在则调用缓存，反之读取相应内容
         */
        // 缓存编号
        $cache_id = sprintf('%X', crc32(Session::get('user_rank', 0).'-'.cfg('lang')));

        if (! $this->is_cached('index', $cache_id)) {
            $this->assign_template();

            $position = $this->assign_ur_here();
            $this->assign('page_title', $position['title']);    // 页面标题
            $this->assign('ur_here', $position['ur_here']);  // 当前位置

            // meta information
            $this->assign('keywords', htmlspecialchars(cfg('shop_keywords')));
            $this->assign('description', htmlspecialchars(cfg('shop_desc')));
            $this->assign('flash_theme', cfg('flash_theme'));  // Flash轮播图片模板

            $this->assign('feed_url', (cfg('rewrite') === 1) ? 'feed.xml' : 'feed.php'); // RSS URL

            $this->assign('categories', GoodsHelper::get_categories_tree()); // 分类树
            $this->assign('helps', MainHelper::get_shop_help());       // 网店帮助
            $this->assign('top_goods', GoodsHelper::get_top10());           // 销售排行

            $this->assign('best_goods', GoodsHelper::get_recommend_goods('best'));    // 推荐商品
            $this->assign('new_goods', GoodsHelper::get_recommend_goods('new'));     // 最新商品
            $this->assign('hot_goods', GoodsHelper::get_recommend_goods('hot'));     // 热点文章
            $this->assign('promotion_goods', GoodsHelper::get_promote_goods()); // 特价商品
            $this->assign('brand_list', CommonHelper::get_brands());
            $this->assign('promotion_info', CommonHelper::get_promotion_info()); // 增加一个动态显示所有促销信息的标签栏

            $this->assign('invoice_list', $this->index_get_invoice_query());  // 发货查询
            $this->assign('new_articles', $this->index_get_new_articles());   // 最新文章
            $this->assign('group_buy_goods', $this->index_get_group_buy());      // 团购商品
            $this->assign('auction_list', $this->index_get_auction());        // 拍卖活动
            $this->assign('shop_notice', cfg('shop_notice'));       // 商店公告

            // 首页主广告设置
            $this->assign('index_ad', cfg('index_ad'));
            if (cfg('index_ad') === 'cus') {
                $ad = DB::table('ad_custom')
                    ->select('ad_type', 'content', 'url')
                    ->where('ad_status', 1)
                    ->first();
                $ad = (array) $ad;
                $this->assign('ad', $ad);
            }

            // links
            $links = $this->index_get_links();
            $this->assign('img_links', $links['img']);
            $this->assign('txt_links', $links['txt']);
            $this->assign('data_dir', DATA_DIR);       // 数据目录

            // 首页推荐分类
            $cat_recommend_res = DB::table('goods_cat_recommend as cr')
                ->select('c.cat_id', 'c.cat_name', 'cr.recommend_type')
                ->join('goods_category as c', 'cr.cat_id', '=', 'c.cat_id')
                ->get()
                ->map(fn ($item) => (array) $item)
                ->all();
            if (! empty($cat_recommend_res)) {
                $cat_rec_array = [];
                foreach ($cat_recommend_res as $cat_recommend_data) {
                    $cat_rec[$cat_recommend_data['recommend_type']][] = ['cat_id' => $cat_recommend_data['cat_id'], 'cat_name' => $cat_recommend_data['cat_name']];
                }
                $this->assign('cat_rec', $cat_rec);
            }

            // 页面中的动态内容
            $this->assign_dynamic('index');
        }

        return $this->display('index', $cache_id);
    }

    // cat_rec
    private function catRecommend()
    {
        $rec_array = [1 => 'best', 2 => 'new', 3 => 'hot'];
        $rec_type = ! empty($_REQUEST['rec_type']) ? intval($_REQUEST['rec_type']) : '1';
        $cat_id = ! empty($_REQUEST['cid']) ? intval($_REQUEST['cid']) : '0';
        $result = ['error' => 0, 'content' => '', 'type' => $rec_type, 'cat_id' => $cat_id];

        $children = CommonHelper::get_children($cat_id);
        $this->assign($rec_array[$rec_type].'_goods', GoodsHelper::get_category_recommend_goods($rec_array[$rec_type], $children));    // 推荐商品
        $this->assign('cat_rec_sign', 1);
        $result['content'] = $this->fetch('web::library/recommend_'.$rec_array[$rec_type].'');

        return response()->json($result);
    }

    /**
     * 调用发货单查询
     *
     * @return array
     */
    private function index_get_invoice_query()
    {
        $all = DB::table('order_info as o')
            ->select('o.order_sn', 'o.invoice_no', 's.shipping_code')
            ->leftJoin('shipping as s', 's.shipping_id', '=', 'o.shipping_id')
            ->where('invoice_no', '>', '')
            ->where('shipping_status', SS_SHIPPED)
            ->orderByDesc('shipping_time')
            ->limit(10)
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();

        foreach ($all as $key => $row) {
            $plugin = ROOT_PATH.'includes/modules/shipping/'.$row['shipping_code'].'.php';

            if (file_exists($plugin)) {
                include_once $plugin;

                $shipping = new $row['shipping_code'];
                $all[$key]['invoice_no'] = $shipping->query((string) $row['invoice_no']);
            }
        }

        clearstatcache();

        return $all;
    }

    /**
     * 获得最新的文章列表。
     *
     * @return array
     */
    private function index_get_new_articles()
    {
        $res = DB::table('article as a')
            ->select('a.article_id', 'a.title', 'ac.cat_name', 'a.add_time', 'a.file_url', 'a.open_type', 'ac.cat_id')
            ->join('article_cat as ac', 'a.cat_id', '=', 'ac.cat_id')
            ->where('a.is_open', 1)
            ->where('ac.cat_type', 1)
            ->orderByDesc('a.article_type')
            ->orderByDesc('a.add_time')
            ->limit((int) cfg('article_number'))
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();

        $arr = [];
        foreach ($res as $idx => $row) {
            $arr[$idx]['id'] = $row['article_id'];
            $arr[$idx]['title'] = $row['title'];
            $arr[$idx]['short_title'] = cfg('article_title_length') > 0 ?
                Str::substr($row['title'], cfg('article_title_length')) : $row['title'];
            $arr[$idx]['cat_name'] = $row['cat_name'];
            $arr[$idx]['add_time'] = TimeHelper::local_date(cfg('date_format'), $row['add_time']);
            $arr[$idx]['url'] = $row['open_type'] != 1 ?
                build_uri('article', ['aid' => $row['article_id']], $row['title']) : trim($row['file_url']);
            $arr[$idx]['cat_url'] = build_uri('article_cat', ['acid' => $row['cat_id']], $row['cat_name']);
        }

        return $arr;
    }

    /**
     * 获得最新的团购活动
     *
     * @return array
     */
    private function index_get_group_buy()
    {
        $time = TimeHelper::gmtime();
        $limit = MainHelper::get_library_number('group_buy', 'index');

        $group_buy_list = [];
        if ($limit > 0) {
            $res = DB::table('goods_activity as gb')
                ->select('gb.act_id as group_buy_id', 'gb.goods_id', 'gb.ext_info', 'gb.goods_name', 'g.goods_thumb', 'g.goods_img')
                ->join('goods as g', 'g.goods_id', '=', 'gb.goods_id')
                ->where('gb.act_type', GAT_GROUP_BUY)
                ->where('gb.start_time', '<=', $time)
                ->where('gb.end_time', '>=', $time)
                ->where('g.is_delete', 0)
                ->orderByDesc('gb.act_id')
                ->limit($limit)
                ->get()
                ->map(fn ($item) => (array) $item)
                ->all();

            foreach ($res as $row) {
                // 如果缩略图为空，使用默认图片
                $row['goods_img'] = CommonHelper::get_image_path($row['goods_img']);
                $row['thumb'] = CommonHelper::get_image_path($row['goods_thumb']);

                // 根据价格阶梯，计算最低价
                $ext_info = unserialize($row['ext_info']);
                $price_ladder = $ext_info['price_ladder'];
                if (! is_array($price_ladder) || empty($price_ladder)) {
                    $row['last_price'] = CommonHelper::price_format(0);
                } else {
                    foreach ($price_ladder as $amount_price) {
                        $price_ladder[$amount_price['amount']] = $amount_price['price'];
                    }
                }
                ksort($price_ladder);
                $row['last_price'] = CommonHelper::price_format(end($price_ladder));
                $row['url'] = build_uri('group_buy', ['gbid' => $row['group_buy_id']]);
                $row['short_name'] = cfg('goods_name_length') > 0 ?
                    Str::substr($row['goods_name'], cfg('goods_name_length')) : $row['goods_name'];
                $row['short_style_name'] = GoodsHelper::add_style($row['short_name'], '');
                $group_buy_list[] = $row;
            }
        }

        return $group_buy_list;
    }

    /**
     * 取得拍卖活动列表
     *
     * @return array
     */
    private function index_get_auction()
    {
        $now = TimeHelper::gmtime();
        $limit = MainHelper::get_library_number('auction', 'index');
        $res = DB::table('goods_activity as a')
            ->select('a.act_id', 'a.goods_id', 'a.goods_name', 'a.ext_info', 'g.goods_thumb')
            ->join('goods as g', 'a.goods_id', '=', 'g.goods_id')
            ->where('a.act_type', GAT_AUCTION)
            ->where('a.is_finished', 0)
            ->where('a.start_time', '<=', $now)
            ->where('a.end_time', '>=', $now)
            ->where('g.is_delete', 0)
            ->orderByDesc('a.start_time')
            ->limit($limit)
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();

        $list = [];
        foreach ($res as $row) {
            $ext_info = unserialize($row['ext_info']);
            $arr = array_merge($row, $ext_info);
            $arr['formated_start_price'] = CommonHelper::price_format($arr['start_price']);
            $arr['formated_end_price'] = CommonHelper::price_format($arr['end_price']);
            $arr['thumb'] = CommonHelper::get_image_path($row['goods_thumb']);
            $arr['url'] = build_uri('auction', ['auid' => $arr['act_id']]);
            $arr['short_name'] = cfg('goods_name_length') > 0 ?
                Str::substr($arr['goods_name'], cfg('goods_name_length')) : $arr['goods_name'];
            $arr['short_style_name'] = GoodsHelper::add_style($arr['short_name'], '');
            $list[] = $arr;
        }

        return $list;
    }

    /**
     * 获得所有的友情链接
     *
     * @return array
     */
    private function index_get_links()
    {
        $res = DB::table('shop_friend_link')
            ->select('link_logo', 'link_name', 'link_url')
            ->orderBy('show_order')
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();

        $links['img'] = $links['txt'] = [];

        foreach ($res as $row) {
            if (! empty($row['link_logo'])) {
                $links['img'][] = [
                    'name' => $row['link_name'],
                    'url' => $row['link_url'],
                    'logo' => $row['link_logo'],
                ];
            } else {
                $links['txt'][] = [
                    'name' => $row['link_name'],
                    'url' => $row['link_url'],
                ];
            }
        }

        return $links;
    }
}
