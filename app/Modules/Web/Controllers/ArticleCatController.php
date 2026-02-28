<?php

declare(strict_types=1);

namespace App\Modules\Web\Controllers;

use App\Helpers\ArticleHelper;
use App\Helpers\CommonHelper;
use App\Helpers\GoodsHelper;
use App\Helpers\MainHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArticleCatController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        // 获得指定的分类ID
        if (! empty($_GET['id'])) {
            $cat_id = intval($_GET['id']);
        } elseif (! empty($_GET['category'])) {
            $cat_id = intval($_GET['category']);
        } else {
            return response()->redirectTo('/');
        }

        // 获得当前页码
        $page = ! empty($_REQUEST['page']) && intval($_REQUEST['page']) > 0 ? intval($_REQUEST['page']) : 1;

        // 获得页面的缓存ID
        $cache_id = sprintf('%X', crc32($cat_id.'-'.$page.'-'.cfg('lang')));

        if (! $this->is_cached('article_cat', $cache_id)) {
            // 如果页面没有被缓存则重新获得页面的内容

            $this->assign_template('a', [$cat_id]);
            $position = $this->assign_ur_here($cat_id);
            $this->assign('page_title', $position['title']);     // 页面标题
            $this->assign('ur_here', $position['ur_here']);   // 当前位置

            $this->assign('categories', GoodsHelper::get_categories_tree(0)); // 分类树
            $this->assign('article_categories', MainHelper::article_categories_tree($cat_id)); // 文章分类树
            $this->assign('helps', MainHelper::get_shop_help());        // 网店帮助
            $this->assign('top_goods', GoodsHelper::get_top10());            // 销售排行

            $this->assign('best_goods', GoodsHelper::get_recommend_goods('best'));
            $this->assign('new_goods', GoodsHelper::get_recommend_goods('new'));
            $this->assign('hot_goods', GoodsHelper::get_recommend_goods('hot'));
            $this->assign('promotion_goods', GoodsHelper::get_promote_goods());
            $this->assign('promotion_info', CommonHelper::get_promotion_info());

            // Meta
            $meta = DB::table('article_cat')
                ->select('keywords', 'cat_desc')
                ->where('cat_id', (string) $cat_id)
                ->first();
            $meta = (array) $meta;

            if ($meta === false || empty($meta)) {
                // 如果没有找到任何记录则返回首页
                return response()->redirectTo('/');
            }

            $this->assign('keywords', htmlspecialchars($meta['keywords']));
            $this->assign('description', htmlspecialchars($meta['cat_desc']));

            // 获得文章总数
            $size = cfg('article_page_size') && intval(cfg('article_page_size')) > 0 ? intval(cfg('article_page_size')) : 20;
            $count = ArticleHelper::get_article_count($cat_id);
            $pages = ($count > 0) ? ceil($count / $size) : 1;

            if ($page > $pages) {
                $page = $pages;
            }
            $pager['search']['id'] = $cat_id;
            $keywords = '';
            $goon_keywords = ''; // 继续传递的搜索关键词

            // 获得文章列表
            if (isset($_REQUEST['keywords'])) {
                $keywords = addslashes(htmlspecialchars(urldecode(trim($_REQUEST['keywords']))));
                $pager['search']['keywords'] = $keywords;
                $search_url = substr(strrchr($_POST['cur_url'], '/'), 1);

                $this->assign('search_value', stripslashes(stripslashes($keywords)));
                $this->assign('search_url', $search_url);
                $count = ArticleHelper::get_article_count($cat_id, $keywords);
                $pages = ($count > 0) ? ceil($count / $size) : 1;
                if ($page > $pages) {
                    $page = $pages;
                }

                $goon_keywords = urlencode($_REQUEST['keywords']);
            }
            $this->assign('artciles_list', ArticleHelper::get_cat_articles($cat_id, $page, $size, $keywords));
            $this->assign('cat_id', $cat_id);
            // 分页
            MainHelper::assign_pager('article_cat', $cat_id, $count, $size, '', '', $page, $goon_keywords);
            $this->assign_dynamic('article_cat');
        }

        $this->assign('feed_url', (cfg('rewrite') === 1) ? 'feed-typearticle_cat'.$cat_id.'.xml' : 'feed.php?type=article_cat'.$cat_id); // RSS URL

        return $this->display('article_cat', $cache_id);
    }
}
