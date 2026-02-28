<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Helpers\BaseHelper;
use App\Helpers\CommonHelper;
use App\Helpers\TimeHelper;
use App\Libraries\Image;
use App\Modules\Admin\Helpers\MainHelper;
use App\Services\Article\ArticleCatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArticleController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        // require_once ROOT_PATH.'includes/fckeditor/fckeditor.php'; // Removed as FCKeditor is handled by MainHelper::create_html_editor

        // 初始化数据交换对象
        // $exc = new Exchange(ecs()->table('article'), db(), 'article_id', 'title'); // Replaced with DB facade
        $image = new Image;
        $articleCatService = new ArticleCatService;

        // 允许上传的文件类型
        $allow_file_types = '|GIF|JPG|PNG|BMP|SWF|DOC|XLS|PPT|MID|WAV|ZIP|RAR|PDF|CHM|RM|TXT|';

        /**
         * 文章列表
         */
        if ($action === 'list') {
            // 取得过滤条件
            $filter = [];
            $this->assign('cat_select', $articleCatService->article_cat_list(0));
            $this->assign('ur_here', lang('03_article_list'));
            $this->assign('action_link', ['text' => lang('article_add'), 'href' => 'article.php?act=add']);
            $this->assign('full_page', 1);
            $this->assign('filter', $filter);

            $article_list = $this->get_articleslist();

            $this->assign('article_list', $article_list['arr']);
            $this->assign('filter', $article_list['filter']);
            $this->assign('record_count', $article_list['record_count']);
            $this->assign('page_count', $article_list['page_count']);

            $sort_flag = MainHelper::sort_flag($article_list['filter']);
            $this->assign($sort_flag['tag'], $sort_flag['img']);

            return $this->display('article_list');
        }

        /**
         * 翻页，排序
         */
        if ($action === 'query') {
            $this->check_authz_json('article_manage');

            $article_list = $this->get_articleslist();

            $this->assign('article_list', $article_list['arr']);
            $this->assign('filter', $article_list['filter']);
            $this->assign('record_count', $article_list['record_count']);
            $this->assign('page_count', $article_list['page_count']);

            $sort_flag = MainHelper::sort_flag($article_list['filter']);
            $this->assign($sort_flag['tag'], $sort_flag['img']);

            return $this->make_json_result(
                $this->fetch('article_list'),
                '',
                ['filter' => $article_list['filter'], 'page_count' => $article_list['page_count']]
            );
        }

        /**
         * 添加文章
         */
        if ($action === 'add') {
            // 权限判断
            $this->admin_priv('article_manage');

            // 创建 html editor
            MainHelper::create_html_editor('FCKeditor1');

            // 初始化
            $article = [];
            $article['is_open'] = 1;

            // 取得分类、品牌
            $this->assign('goods_cat_list', CommonHelper::cat_list());
            $this->assign('brand_list', CommonHelper::get_brand_list());

            // 清理关联商品
            DB::table('goods_article')->where('article_id', 0)->delete();

            if (isset($_GET['id'])) {
                $this->assign('cur_id', $_GET['id']);
            }
            $this->assign('article', $article);
            $this->assign('cat_select', $articleCatService->article_cat_list(0));
            $this->assign('ur_here', lang('article_add'));
            $this->assign('action_link', ['text' => lang('03_article_list'), 'href' => 'article.php?act=list']);
            $this->assign('form_action', 'insert');

            return $this->display('article_info');
        }

        /**
         * 添加文章
         */
        if ($action === 'insert') {
            // 权限判断
            $this->admin_priv('article_manage');

            // 检查是否重复
            $is_only = ! DB::table('article')
                ->where('title', $_POST['title'])
                ->where('cat_id', $_POST['article_cat'])
                ->exists();

            if (! $is_only) {
                return $this->sys_msg(sprintf(lang('title_exist'), stripslashes($_POST['title'])), 1);
            }

            // 取得文件地址
            $file_url = '';
            if ((isset($_FILES['file']['error']) && $_FILES['file']['error'] === 0) || (! isset($_FILES['file']['error']) && isset($_FILES['file']['tmp_name']) && $_FILES['file']['tmp_name'] != 'none')) {
                // 检查文件格式
                if (! BaseHelper::check_file_type($_FILES['file']['tmp_name'], $_FILES['file']['name'], $allow_file_types)) {
                    return $this->sys_msg(lang('invalid_file'));
                }

                // 复制文件
                $res = $this->upload_article_file($_FILES['file']);
                if ($res != false) {
                    $file_url = $res;
                }
            }

            if ($file_url === '') {
                $file_url = $_POST['file_url'];
            }

            // 计算文章打开方式
            if ($file_url === '') {
                $open_type = 0;
            } else {
                $open_type = $_POST['FCKeditor1'] === '' ? 1 : 2;
            }

            // 插入数据
            $add_time = TimeHelper::gmtime();
            if (empty($_POST['cat_id'])) {
                $_POST['cat_id'] = 0;
            }

            $article_id = DB::table('article')->insertGetId([
                'title' => $_POST['title'],
                'cat_id' => $_POST['article_cat'],
                'article_type' => $_POST['article_type'],
                'is_open' => $_POST['is_open'],
                'author' => $_POST['author'],
                'author_email' => $_POST['author_email'],
                'keywords' => $_POST['keywords'],
                'content' => $_POST['FCKeditor1'],
                'add_time' => $add_time,
                'file_url' => $file_url,
                'open_type' => $open_type,
                'link' => $_POST['link_url'],
                'description' => $_POST['description'],
            ]);

            // 处理关联商品
            DB::table('goods_article')->where('article_id', 0)->update(['article_id' => $article_id]);

            $link[0]['text'] = lang('continue_add');
            $link[0]['href'] = 'article.php?act=add';

            $link[1]['text'] = lang('back_list');
            $link[1]['href'] = 'article.php?act=list';

            $this->admin_log($_POST['title'], 'add', 'article');

            $this->clear_cache_files(); // 清除相关的缓存文件

            return $this->sys_msg(lang('articleadd_succeed'), 0, $link);
        }

        /**
         * 编辑
         */
        if ($action === 'edit') {
            // 权限判断
            $this->admin_priv('article_manage');

            // 取文章数据
            $article = DB::table('article')->where('article_id', $_REQUEST['id'])->first();
            $article = $article ? (array) $article : [];

            // 创建 html editor
            MainHelper::create_html_editor('FCKeditor1', $article['content']);

            // 取得分类、品牌
            $this->assign('goods_cat_list', CommonHelper::cat_list());
            $this->assign('brand_list', CommonHelper::get_brand_list());

            // 取得关联商品
            $goods_list = $this->get_article_goods($_REQUEST['id']);
            $this->assign('goods_list', $goods_list);

            $this->assign('article', $article);
            $this->assign('cat_select', $articleCatService->article_cat_list(0, $article['cat_id']));
            $this->assign('ur_here', lang('article_edit'));
            $this->assign('action_link', ['text' => lang('03_article_list'), 'href' => 'article.php?act=list&'.MainHelper::list_link_postfix()]);
            $this->assign('form_action', 'update');

            return $this->display('article_info');
        }

        if ($action === 'update') {
            // 权限判断
            $this->admin_priv('article_manage');

            // 检查文章名是否相同
            $is_only = ! DB::table('article')
                ->where('title', $_POST['title'])
                ->where('cat_id', $_POST['article_cat'])
                ->where('article_id', '<>', $_POST['id'])
                ->exists();

            if (! $is_only) {
                return $this->sys_msg(sprintf(lang('title_exist'), stripslashes($_POST['title'])), 1);
            }

            if (empty($_POST['cat_id'])) {
                $_POST['cat_id'] = 0;
            }

            // 取得文件地址
            $file_url = '';
            if (empty($_FILES['file']['error']) || (! isset($_FILES['file']['error']) && isset($_FILES['file']['tmp_name']) && $_FILES['file']['tmp_name'] != 'none')) {
                // 检查文件格式
                if (! BaseHelper::check_file_type($_FILES['file']['tmp_name'], $_FILES['file']['name'], $allow_file_types)) {
                    return $this->sys_msg(lang('invalid_file'));
                }

                // 复制文件
                $res = $this->upload_article_file($_FILES['file']);
                if ($res != false) {
                    $file_url = $res;
                }
            }

            if ($file_url === '') {
                $file_url = $_POST['file_url'];
            }

            // 计算文章打开方式
            if ($file_url === '') {
                $open_type = 0;
            } else {
                $open_type = $_POST['FCKeditor1'] === '' ? 1 : 2;
            }

            // 如果 file_url 跟以前不一样，且原来的文件是本地文件，删除原来的文件
            $old_url = DB::table('article')->where('article_id', $_POST['id'])->value('file_url');
            if ($old_url != '' && $old_url != $file_url && strpos($old_url, 'http://') === false && strpos($old_url, 'https://') === false) {
                @unlink(ROOT_PATH.$old_url);
            }

            $update_data = [
                'title' => $_POST['title'],
                'cat_id' => $_POST['article_cat'],
                'article_type' => $_POST['article_type'],
                'is_open' => $_POST['is_open'],
                'author' => $_POST['author'],
                'author_email' => $_POST['author_email'],
                'keywords' => $_POST['keywords'],
                'file_url' => $file_url,
                'open_type' => $open_type,
                'content' => $_POST['FCKeditor1'],
                'link' => $_POST['link_url'],
                'description' => $_POST['description'],
            ];

            if (DB::table('article')->where('article_id', $_POST['id'])->update($update_data)) {
                $link[0]['text'] = lang('back_list');
                $link[0]['href'] = 'article.php?act=list&'.MainHelper::list_link_postfix();

                $note = sprintf(lang('articleedit_succeed'), stripslashes($_POST['title']));
                $this->admin_log($_POST['title'], 'edit', 'article');

                $this->clear_cache_files();

                return $this->sys_msg($note, 0, $link);
            } else {
                return $this->sys_msg('DB error', 1);
            }
        }

        /**
         * 编辑文章主题
         */
        if ($action === 'edit_title') {
            $this->check_authz_json('article_manage');

            $id = intval($_POST['id']);
            $title = BaseHelper::json_str_iconv(trim($_POST['val']));

            // 检查文章标题是否重复
            if (DB::table('article')->where('title', $title)->where('article_id', '<>', $id)->exists()) {
                return $this->make_json_error(sprintf(lang('title_exist'), $title));
            } else {
                if (DB::table('article')->where('article_id', $id)->update(['title' => $title])) {
                    $this->clear_cache_files();
                    $this->admin_log($title, 'edit', 'article');

                    return $this->make_json_result(stripslashes($title));
                } else {
                    return $this->make_json_error('DB error');
                }
            }
        }

        /**
         * 切换是否显示
         */
        if ($action === 'toggle_show') {
            $this->check_authz_json('article_manage');

            $id = intval($_POST['id']);
            $val = intval($_POST['val']);

            DB::table('article')->where('article_id', $id)->update(['is_open' => $val]);
            $this->clear_cache_files();

            return $this->make_json_result((string) $val);
        }

        /**
         * 切换文章重要性
         */
        if ($action === 'toggle_type') {
            $this->check_authz_json('article_manage');

            $id = intval($_POST['id']);
            $val = intval($_POST['val']);

            DB::table('article')->where('article_id', $id)->update(['article_type' => $val]);
            $this->clear_cache_files();

            return $this->make_json_result((string) $val);
        }

        /**
         * 删除文章主题
         */
        if ($action === 'remove') {
            $this->check_authz_json('article_manage');

            $id = intval($_GET['id']);

            // 删除原来的文件
            $old_url = DB::table('article')->where('article_id', $id)->value('file_url');
            if ($old_url != '' && strpos($old_url, 'http://') === false && strpos($old_url, 'https://') === false) {
                @unlink(ROOT_PATH.$old_url);
            }

            $name = DB::table('article')->where('article_id', $id)->value('title');
            if (DB::table('article')->where('article_id', $id)->delete()) {
                DB::table('comment')
                    ->where('comment_type', 1)
                    ->where('id_value', $id)
                    ->delete();

                $this->admin_log(addslashes($name), 'remove', 'article');
                $this->clear_cache_files();
            }

            $url = 'article.php?act=query&'.str_replace('act=remove', '', $_SERVER['QUERY_STRING']);

            return response()->redirectTo($url);
        }

        /**
         * 将商品加入关联
         */
        if ($action === 'add_link_goods') {
            $this->check_authz_json('article_manage');

            $add_ids = json_decode($_GET['add_ids']);
            $args = json_decode($_GET['JSON']);
            $article_id = $args[0];

            if ($article_id === 0) {
                $article_id = DB::table('article')->max('article_id') + 1;
            }

            foreach ($add_ids as $key => $val) {
                DB::table('goods_article')->insertOrIgnore([
                    'goods_id' => $val,
                    'article_id' => $article_id,
                ]);
            }

            // 重新载入
            $arr = $this->get_article_goods($article_id);
            $opt = [];

            foreach ($arr as $key => $val) {
                $opt[] = [
                    'value' => $val['goods_id'],
                    'text' => $val['goods_name'],
                    'data' => '',
                ];
            }

            return $this->make_json_result(json_encode($opt));
        }

        /**
         * 将商品删除关联
         */
        if ($action === 'drop_link_goods') {
            $this->check_authz_json('article_manage');

            $drop_goods = json_decode($_GET['drop_ids']);
            $arguments = json_decode($_GET['JSON']);
            $article_id = $arguments[0];

            if ($article_id === 0) {
                $article_id = DB::table('article')->max('article_id') + 1;
            }

            DB::table('goods_article')
                ->where('article_id', $article_id)
                ->whereIn('goods_id', $drop_goods)
                ->delete();

            // 重新载入
            $arr = $this->get_article_goods($article_id);
            $opt = [];

            foreach ($arr as $key => $val) {
                $opt[] = [
                    'value' => $val['goods_id'],
                    'text' => $val['goods_name'],
                    'data' => '',
                ];
            }

            return $this->make_json_result(json_encode($opt));
        }

        /**
         * 搜索商品
         */
        if ($action === 'get_goods_list') {
            $filters = json_decode($_GET['JSON']);

            $arr = MainHelper::get_goods_list($filters);
            $opt = [];

            foreach ($arr as $key => $val) {
                $opt[] = [
                    'value' => $val['goods_id'],
                    'text' => $val['goods_name'],
                    'data' => $val['shop_price'],
                ];
            }

            return $this->make_json_result(json_encode($opt));
        }
        /**
         * 批量操作
         */
        if ($action === 'batch') {
            // 批量删除
            if (isset($_POST['type'])) {
                if ($_POST['type'] === 'button_remove') {
                    $this->admin_priv('article_manage');

                    if (! isset($_POST['checkboxes']) || ! is_array($_POST['checkboxes'])) {
                        return $this->sys_msg(lang('no_select_article'), 1);
                    }

                    // 删除原来的文件
                    $res = DB::table('article')
                        ->whereIn('article_id', $_POST['checkboxes'])
                        ->where('file_url', '<>', '')
                        ->get();

                    foreach ($res as $row) {
                        $row = (array) $row;
                        $old_url = $row['file_url'];
                        if (strpos($old_url, 'http://') === false && strpos($old_url, 'https://') === false) {
                            @unlink(ROOT_PATH.$old_url);
                        }
                    }

                    foreach ($_POST['checkboxes'] as $key => $id) {
                        $name = DB::table('article')->where('article_id', $id)->value('title');
                        if (DB::table('article')->where('article_id', $id)->delete()) {
                            $this->admin_log(addslashes($name), 'remove', 'article');
                        }
                    }
                }

                // 批量隐藏
                if ($_POST['type'] === 'button_hide') {
                    $this->check_authz_json('article_manage');
                    if (! isset($_POST['checkboxes']) || ! is_array($_POST['checkboxes'])) {
                        return $this->sys_msg(lang('no_select_article'), 1);
                    }

                    foreach ($_POST['checkboxes'] as $key => $id) {
                        DB::table('article')->where('article_id', $id)->update(['is_open' => 0]);
                    }
                }

                // 批量显示
                if ($_POST['type'] === 'button_show') {
                    $this->check_authz_json('article_manage');
                    if (! isset($_POST['checkboxes']) || ! is_array($_POST['checkboxes'])) {
                        return $this->sys_msg(lang('no_select_article'), 1);
                    }

                    foreach ($_POST['checkboxes'] as $key => $id) {
                        DB::table('article')->where('article_id', $id)->update(['is_open' => 1]);
                    }
                }

                // 批量移动分类
                if ($_POST['type'] === 'move_to') {
                    $this->check_authz_json('article_manage');
                    if (! isset($_POST['checkboxes']) || ! is_array($_POST['checkboxes'])) {
                        return $this->sys_msg(lang('no_select_article'), 1);
                    }

                    if (! $_POST['target_cat']) {
                        return $this->sys_msg(lang('no_select_act'), 1);
                    }

                    foreach ($_POST['checkboxes'] as $key => $id) {
                        DB::table('article')->where('article_id', $id)->update(['cat_id' => $_POST['target_cat']]);
                    }
                }
            }

            // 清除缓存
            $this->clear_cache_files();
            $lnk[] = ['text' => lang('back_list'), 'href' => 'article.php?act=list'];

            return $this->sys_msg(lang('batch_handle_ok'), 0, $lnk);
        }
    }

    // 把商品删除关联
    private function drop_link_goods($goods_id, $article_id)
    {
        DB::table('goods_article')
            ->where('goods_id', $goods_id)
            ->where('article_id', $article_id)
            ->limit(1)
            ->delete();
    }

    // 取得文章关联商品
    private function get_article_goods($article_id)
    {
        return DB::table('goods_article as ga')
            ->leftJoin('goods as g', 'g.goods_id', '=', 'ga.goods_id')
            ->select('g.goods_id', 'g.goods_name')
            ->where('ga.article_id', $article_id)
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();
    }

    // 获得文章列表
    private function get_articleslist()
    {
        $result = MainHelper::get_filter();
        if ($result === false) {
            $filter = [];
            $filter['keyword'] = empty($_REQUEST['keyword']) ? '' : trim($_REQUEST['keyword']);
            if (isset($_REQUEST['is_ajax']) && $_REQUEST['is_ajax'] === 1) {
                $filter['keyword'] = BaseHelper::json_str_iconv($filter['keyword']);
            }
            $filter['cat_id'] = empty($_REQUEST['cat_id']) ? 0 : intval($_REQUEST['cat_id']);
            $filter['sort_by'] = empty($_REQUEST['sort_by']) ? 'a.article_id' : trim($_REQUEST['sort_by']);
            $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);

            $query = DB::table('article as a')
                ->leftJoin('article_cat as ac', 'ac.cat_id', '=', 'a.cat_id');

            if (! empty($filter['keyword'])) {
                $query->where('a.title', 'like', '%'.BaseHelper::mysql_like_quote($filter['keyword']).'%');
            }
            if ($filter['cat_id']) {
                $query->whereRaw('a.'.CommonHelper::get_article_children($filter['cat_id']));
            }

            // 文章总数
            $filter['record_count'] = $query->count();

            $filter = MainHelper::page_and_size($filter);

            // 获取文章数据
            $res = $query->select('a.*', 'ac.cat_name')
                ->orderBy($filter['sort_by'], $filter['sort_order'])
                ->offset($filter['start'])
                ->limit($filter['page_size'])
                ->get();

            $filter['keyword'] = stripslashes($filter['keyword']);
        } else {
            $res = DB::select($result['sql']);
            $filter = $result['filter'];
        }

        $arr = [];
        foreach ($res as $rows) {
            $rows = (array) $rows;
            $rows['date'] = TimeHelper::local_date(cfg('time_format'), $rows['add_time']);

            $arr[] = $rows;
        }

        return ['arr' => $arr, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']];
    }

    // 上传文件
    private function upload_article_file($upload)
    {
        if (! BaseHelper::make_dir('../'.DATA_DIR.'/article')) {
            // 创建目录失败
            return false;
        }

        $image = new Image;
        $filename = $image->random_filename().substr($upload['name'], strpos($upload['name'], '.'));
        $path = ROOT_PATH.DATA_DIR.'/article/'.$filename;

        if (BaseHelper::move_upload_file($upload['tmp_name'], $path)) {
            return DATA_DIR.'/article/'.$filename;
        } else {
            return false;
        }
    }
}
