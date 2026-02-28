<?php

declare(strict_types=1);

namespace App\Modules\Web\Controllers;

use App\Helpers\TimeHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SitemapController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        define('INIT_NO_USERS', true);
        define('INIT_NO_SMARTY', true);
        if (file_exists(ROOT_PATH.DATA_DIR.'/sitemap.dat') && time() - filemtime(ROOT_PATH.DATA_DIR.'/sitemap.dat') < 86400) {
            $out = file_get_contents(ROOT_PATH.DATA_DIR.'/sitemap.dat');
        } else {
            $site_url = rtrim(ecs()->url(), '/');
            $sitemap = new sitemap;
            $config = unserialize(cfg('sitemap'));
            $item = [
                'loc' => "$site_url/",
                'lastmod' => TimeHelper::local_date('Y-m-d'),
                'changefreq' => $config['homepage_changefreq'],
                'priority' => $config['homepage_priority'],
            ];
            $sitemap->item($item);
            // 商品分类
            $res = DB::table('goods_category')->select('cat_id', 'cat_name')->orderBy('parent_id')->get()->map(fn ($item) => (array) $item);

            foreach ($res as $row) {
                $item = [
                    'loc' => "$site_url/".build_uri('category', ['cid' => $row['cat_id']], $row['cat_name']),
                    'lastmod' => TimeHelper::local_date('Y-m-d'),
                    'changefreq' => $config['category_changefreq'],
                    'priority' => $config['category_priority'],
                ];
                $sitemap->item($item);
            }
            // 文章分类
            $res = DB::table('article_cat')->select('cat_id', 'cat_name')->where('cat_type', 1)->get()->map(fn ($item) => (array) $item);

            foreach ($res as $row) {
                $item = [
                    'loc' => "$site_url/".build_uri('article_cat', ['acid' => $row['cat_id']], $row['cat_name']),
                    'lastmod' => TimeHelper::local_date('Y-m-d'),
                    'changefreq' => $config['category_changefreq'],
                    'priority' => $config['category_priority'],
                ];
                $sitemap->item($item);
            }
            // 商品
            $res = DB::table('goods')->select('goods_id', 'goods_name', 'last_update')->where('is_delete', 0)->limit(300)->get()->map(fn ($item) => (array) $item);

            foreach ($res as $row) {
                $item = [
                    'loc' => "$site_url/".build_uri('goods', ['gid' => $row['goods_id']], $row['goods_name']),
                    'lastmod' => TimeHelper::local_date('Y-m-d', $row['last_update']),
                    'changefreq' => $config['content_changefreq'],
                    'priority' => $config['content_priority'],
                ];
                $sitemap->item($item);
            }
            // 文章
            $res = DB::table('article')->select('article_id', 'title', 'file_url', 'open_type', 'add_time')->where('is_open', 1)->get()->map(fn ($item) => (array) $item);

            foreach ($res as $row) {
                $article_url = $row['open_type'] != 1 ? build_uri('article', ['aid' => $row['article_id']], $row['title']) : trim($row['file_url']);
                $item = [
                    'loc' => "$site_url/".$article_url,
                    'lastmod' => TimeHelper::local_date('Y-m-d', $row['add_time']),
                    'changefreq' => $config['content_changefreq'],
                    'priority' => $config['content_priority'],
                ];
                $sitemap->item($item);
            }
            $out = $sitemap->generate();
            file_put_contents(ROOT_PATH.DATA_DIR.'/sitemap.dat', $out);
        }
        if (function_exists('gzencode')) {
            header('Content-type: application/x-gzip');
            $out = gzencode($out, 9);
        } else {
            header('Content-type: application/xml; charset=utf-8');
        }
        exit($out);
    }
}
