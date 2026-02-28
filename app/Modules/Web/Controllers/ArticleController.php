<?php

declare(strict_types=1);

namespace App\Modules\Web\Controllers;

use App\Helpers\BaseHelper;
use App\Helpers\CommonHelper;
use App\Helpers\GoodsHelper;
use App\Helpers\MainHelper;
use App\Helpers\TimeHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class ArticleController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        $_REQUEST['id'] = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
        $article_id = $_REQUEST['id'];
        if (isset($_REQUEST['cat_id']) && $_REQUEST['cat_id'] < 0) {
            $article_id = DB::table('article')
                ->where('cat_id', intval($_REQUEST['cat_id']))
                ->value('article_id');
        }

        $cache_id = sprintf('%X', crc32($_REQUEST['id'].'-'.cfg('lang')));

        if (! $this->is_cached('article', $cache_id)) {
            // 文章详情
            $article = $this->get_article_info($article_id);

            if (empty($article)) {
                return response()->redirectTo('/');
            }

            if (! empty($article['link']) && $article['link'] != 'http://' && $article['link'] != 'https://') {
                return response()->redirectTo($article['link']);
            }

            $this->assign('article_categories', MainHelper::article_categories_tree($article_id)); // 文章分类树
            $this->assign('categories', GoodsHelper::get_categories_tree());  // 分类树
            $this->assign('helps', MainHelper::get_shop_help()); // 网店帮助
            $this->assign('top_goods', GoodsHelper::get_top10());    // 销售排行
            $this->assign('best_goods', GoodsHelper::get_recommend_goods('best'));       // 推荐商品
            $this->assign('new_goods', GoodsHelper::get_recommend_goods('new'));        // 最新商品
            $this->assign('hot_goods', GoodsHelper::get_recommend_goods('hot'));        // 热点文章
            $this->assign('promotion_goods', GoodsHelper::get_promote_goods());    // 特价商品
            $this->assign('related_goods', $this->article_related_goods((int) $_REQUEST['id']));  // 特价商品
            $this->assign('id', $article_id);
            $this->assign('username', Session::get('user_name'));
            $this->assign('email', Session::get('email'));
            $this->assign('type', '1');
            $this->assign('promotion_info', CommonHelper::get_promotion_info());

            // 验证码相关设置
            if ((intval(cfg('captcha')) & CAPTCHA_COMMENT) && BaseHelper::gd_version() > 0) {
                $this->assign('enabled_captcha', 1);
                $this->assign('rand', mt_rand());
            }

            $this->assign('article', $article);
            $this->assign('keywords', htmlspecialchars($article['keywords']));
            $this->assign('description', htmlspecialchars($article['description']));

            $catlist = [];
            foreach (MainHelper::get_article_parent_cats($article['cat_id']) as $k => $v) {
                $catlist[] = $v['cat_id'];
            }

            $this->assign_template('a', $catlist);

            $position = $this->assign_ur_here($article['cat_id'], $article['title']);
            $this->assign('page_title', $position['title']);    // 页面标题
            $this->assign('ur_here', $position['ur_here']);  // 当前位置
            $this->assign('comment_type', 1);

            // 相关商品
            $goods_list = DB::table('goods_article as a')
                ->select('a.goods_id', 'g.goods_name')
                ->join('goods as g', 'a.goods_id', '=', 'g.goods_id')
                ->where('a.article_id', $_REQUEST['id'])
                ->get()
                ->map(fn ($item) => (array) $item)
                ->all();
            $this->assign('goods_list', $goods_list);

            // 上一篇下一篇文章
            $next_article = DB::table('article')
                ->select('article_id', 'title')
                ->where('article_id', '>', (int) $article_id)
                ->where('cat_id', $article['cat_id'])
                ->where('is_open', 1)
                ->orderBy('article_id')
                ->first();
            $next_article = (array) $next_article;
            if (! empty($next_article)) {
                $next_article['url'] = build_uri('article', ['aid' => $next_article['article_id']], $next_article['title']);
                $this->assign('next_article', $next_article);
            }

            $prev_aid = DB::table('article')
                ->where('article_id', '<', (int) $article_id)
                ->where('cat_id', $article['cat_id'])
                ->where('is_open', 1)
                ->max('article_id');
            if ($prev_aid) {
                $prev_article = DB::table('article')
                    ->select('article_id', 'title')
                    ->where('article_id', (int) $prev_aid)
                    ->first();
                $prev_article = (array) $prev_article;
                $prev_article['url'] = build_uri('article', ['aid' => $prev_article['article_id']], $prev_article['title']);
                $this->assign('prev_article', $prev_article);
            }

            $this->assign_dynamic('article');
        }
        if (isset($article) && $article['cat_id'] > 2) {
            return $this->display('article', $cache_id);
        } else {
            return $this->display('article_pro', $cache_id);
        }
    }

    /**
     * 获得指定的文章的详细信息
     *
     * @param  int  $article_id
     * @return array
     */
    private function get_article_info($article_id)
    {
        // 获得文章的信息
        $row = DB::table('article as a')
            ->select('a.*', DB::raw('IFNULL(AVG(r.comment_rank), 0) AS comment_rank'))
            ->leftJoin('comment as r', function ($join) {
                $join->on('r.id_value', '=', 'a.article_id')
                    ->where('comment_type', '=', 1);
            })
            ->where('a.is_open', 1)
            ->where('a.article_id', (int) $article_id)
            ->groupBy('a.article_id')
            ->first();
        $row = (array) $row;

        if ($row !== false) {
            $row['comment_rank'] = ceil($row['comment_rank']);                              // 用户评论级别取整
            $row['add_time'] = TimeHelper::local_date(cfg('date_format'), $row['add_time']); // 修正添加时间显示

            // 作者信息如果为空，则用网站名称替换
            if (empty($row['author']) || $row['author'] === '_SHOPHELP') {
                $row['author'] = cfg('shop_name');
            }
        }

        return $row;
    }

    /**
     * 获得文章关联的商品
     *
     * @param  int  $id
     * @return array
     */
    private function article_related_goods($id)
    {
        $res = DB::table('goods_article as ga')
            ->select('g.goods_id', 'g.goods_name', 'g.goods_thumb', 'g.goods_img', 'g.shop_price AS org_price', DB::raw("IFNULL(mp.user_price, g.shop_price * '".Session::get('discount', 1)."') AS shop_price"), 'g.market_price', 'g.promote_price', 'g.promote_start_date', 'g.promote_end_date')
            ->leftJoin('goods as g', 'g.goods_id', '=', 'ga.goods_id')
            ->leftJoin('goods_member_price as mp', function ($join) {
                $join->on('mp.goods_id', '=', 'g.goods_id')
                    ->where('mp.user_rank', '=', Session::get('user_rank', 0));
            })
            ->where('ga.article_id', (int) $id)
            ->where('g.is_on_sale', 1)
            ->where('g.is_alone_sale', 1)
            ->where('g.is_delete', 0)
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
}
