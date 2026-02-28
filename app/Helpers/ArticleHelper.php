<?php

declare(strict_types=1);

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ArticleHelper
{
    /**
     * 获得文章分类下的文章列表
     *
     * @param  int  $cat_id
     * @param  int  $page
     * @param  int  $size
     * @return array
     */
    public static function get_cat_articles($cat_id, $page = 1, $size = 20, $requirement = '')
    {
        // 取出所有非0的文章
        if ($cat_id === '-1') {
            $cat_str = 'cat_id > 0';
        } else {
            $cat_str = CommonHelper::get_article_children($cat_id);
        }
        $query = DB::table('article')
            ->select('article_id', 'title', 'author', 'add_time', 'file_url', 'open_type')
            ->where('is_open', 1)
            ->orderByDesc('article_type')
            ->orderByDesc('article_id');

        if ($requirement !== '') {
            $query->where('title', 'like', '%'.$requirement.'%');
        } else {
            $query->whereRaw($cat_str);
        }

        $res = $query->offset(($page - 1) * $size)
            ->limit($size)
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();

        $arr = [];
        if ($res) {
            foreach ($res as $row) {
                $article_id = $row['article_id'];

                $arr[$article_id]['id'] = $article_id;
                $arr[$article_id]['title'] = $row['title'];
                $arr[$article_id]['short_title'] = cfg('article_title_length') > 0 ? Str::substr($row['title'], cfg('article_title_length')) : $row['title'];
                $arr[$article_id]['author'] = empty($row['author']) || $row['author'] === '_SHOPHELP' ? cfg('shop_name') : $row['author'];
                $arr[$article_id]['url'] = $row['open_type'] != 1 ? build_uri('article', ['aid' => $article_id], $row['title']) : trim($row['file_url']);
                $arr[$article_id]['add_time'] = date(cfg('date_format'), $row['add_time']);
            }
        }

        return $arr;
    }

    /**
     * 获得指定分类下的文章总数
     */
    public static function get_article_count(int $cat_id, string $requirement = ''): int
    {
        $query = DB::table('article')
            ->whereRaw(CommonHelper::get_article_children($cat_id))
            ->where('is_open', 1);

        if ($requirement !== '') {
            $query->where('title', 'like', '%'.$requirement.'%');
        }

        return $query->count();
    }
}
