<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Helpers\TimeHelper;
use Illuminate\Http\Request;

class SitemapController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        $this->admin_priv('sitemap');

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            // ------------------------------------------------------
            // -- 设置更新频率
            // ------------------------------------------------------

            $config = unserialize(cfg('sitemap'));
            $this->assign('config', $config);
            $this->assign('ur_here', lang('sitemap'));
            $this->assign('arr_changefreq', [1, 0.9, 0.8, 0.7, 0.6, 0.5, 0.4, 0.3, 0.2, 0.1]);

            return $this->display('sitemap');
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // ------------------------------------------------------
            // -- 生成站点地图
            // ------------------------------------------------------

            $domain = ecs()->url();
            $today = TimeHelper::local_date('Y-m-d');

            $sm = new google_sitemap;
            $smi = new google_sitemap_item($domain, $today, $_POST['homepage_changefreq'], $_POST['homepage_priority']);
            $sm->add_item($smi);

            $config = [
                'homepage_changefreq' => $_POST['homepage_changefreq'],
                'homepage_priority' => $_POST['homepage_priority'],
                'category_changefreq' => $_POST['category_changefreq'],
                'category_priority' => $_POST['category_priority'],
                'content_changefreq' => $_POST['content_changefreq'],
                'content_priority' => $_POST['content_priority'],
            ];
            $config = serialize($config);

            DB::table('shop_config')->where('code', 'sitemap')->update(['value' => $config]);

            // 商品分类
            $res = DB::table('goods_category')
                ->select('cat_id', 'cat_name')
                ->orderBy('parent_id')
                ->get();

            foreach ($res as $row) {
                $smi = new google_sitemap_item(
                    $domain.build_uri('category', ['cid' => $row['cat_id']], $row['cat_name']),
                    $today,
                    $_POST['category_changefreq'],
                    $_POST['category_priority']
                );
                $sm->add_item($smi);
            }

            // 文章分类
            $res = DB::table('article_cat')
                ->select('cat_id', 'cat_name')
                ->where('cat_type', 1)
                ->get();

            foreach ($res as $row) {
                $smi = new google_sitemap_item(
                    $domain.build_uri('article_cat', ['acid' => $row['cat_id']], $row['cat_name']),
                    $today,
                    $_POST['category_changefreq'],
                    $_POST['category_priority']
                );
                $sm->add_item($smi);
            }

            // 商品
            $res = DB::table('goods')
                ->select('goods_id', 'goods_name')
                ->where('is_delete', 0)
                ->get();

            foreach ($res as $row) {
                $smi = new google_sitemap_item(
                    $domain.build_uri('goods', ['gid' => $row['goods_id']], $row['goods_name']),
                    $today,
                    $_POST['content_changefreq'],
                    $_POST['content_priority']
                );
                $sm->add_item($smi);
            }

            // 文章
            $res = DB::table('article')
                ->select('article_id', 'title', 'file_url', 'open_type')
                ->where('is_open', 1)
                ->get();

            foreach ($res as $row) {
                $article_url = $row['open_type'] != 1 ? build_uri('article', ['aid' => $row['article_id']], $row['title']) : trim($row['file_url']);
                $smi = new google_sitemap_item(
                    $domain.$article_url,
                    $today,
                    $_POST['content_changefreq'],
                    $_POST['content_priority']
                );
                $sm->add_item($smi);
            }

            $this->clear_cache_files();    // 清除缓存

            $sm_file = '../sitemaps.xml';
            if ($sm->build($sm_file)) {
                return $this->sys_msg(sprintf(lang('generate_success'), ecs()->url().'sitemaps.xml'));
            } else {
                $sm_file = '../'.DATA_DIR.'/sitemaps.xml';
                if ($sm->build($sm_file)) {
                    return $this->sys_msg(sprintf(lang('generate_success'), ecs()->url().DATA_DIR.'/sitemaps.xml'));
                } else {
                    return $this->sys_msg(sprintf(lang('generate_failed')));
                }
            }
        }
    }
}
