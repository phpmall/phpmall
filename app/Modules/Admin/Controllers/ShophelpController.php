<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Helpers\BaseHelper;
use App\Helpers\TimeHelper;
use App\Modules\Admin\Helpers\MainHelper;
use App\Modules\Admin\Libraries\Exchange;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShophelpController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        require_once ROOT_PATH.'includes/fckeditor/fckeditor.php';

        // 初始化数据交换对象
        $exc_article = new Exchange(ecs()->table('article'), db(), 'article_id', 'title');
        $exc_cat = new Exchange(ecs()->table('article_cat'), db(), 'cat_id', 'cat_name');

        /**
         * 列出所有文章分类
         */
        if ($action === 'list_cat') {
            $this->assign('action_link', ['text' => lang('article_add'), 'href' => 'shophelp.php?act=add']);
            $this->assign('ur_here', lang('cat_list'));
            $this->assign('full_page', 1);
            $this->assign('list', $this->get_shophelp_list());

            return $this->display('shophelp_cat_list');
        }

        /**
         * 分类下的文章
         */
        if ($action === 'list_article') {
            $this->assign('ur_here', lang('article_list'));
            $this->assign('action_link', ['text' => lang('article_add'), 'href' => 'shophelp.php?act=add&cat_id='.$_REQUEST['cat_id']]);
            $this->assign('full_page', 1);
            $this->assign('cat', article_cat_list($_REQUEST['cat_id'], true, 'cat_id', 0, "onchange=\"location.href='?act=list_article&cat_id='+this.value\""));
            $this->assign('list', $this->shophelp_article_list($_REQUEST['cat_id']));

            return $this->display('shophelp_article_list');
        }

        /**
         * 查询分类下的文章
         */
        if ($action === 'query_art') {
            $cat_id = intval($_GET['cat']);

            $this->assign('list', $this->shophelp_article_list($cat_id));

            return $this->make_json_result($this->fetch('shophelp_article_list'));
        }

        /**
         * 查询
         */
        if ($action === 'query') {
            $this->assign('list', $this->get_shophelp_list());

            return $this->make_json_result($this->fetch('shophelp_cat_list'));
        }

        /**
         * 添加文章
         */
        if ($action === 'add') {
            // 权限判断
            $this->admin_priv('shophelp_manage');
            $_POST['id'] = intval($_POST['id']);

            // 创建 html editor
            MainHelper::create_html_editor('FCKeditor1');

            if (empty($_REQUEST['cat_id'])) {
                $selected = 0;
            } else {
                $selected = $_REQUEST['cat_id'];
            }
            $cat_list = article_cat_list($selected, true, 'cat_id', 0);
            $cat_list = str_replace('select please', lang('select_plz'), $cat_list);
            $this->assign('cat_list', $cat_list);
            $this->assign('ur_here', lang('article_add'));
            $this->assign('action_link', ['text' => lang('cat_list'), 'href' => 'shophelp.php?act=list_cat']);
            $this->assign('form_action', 'insert');

            return $this->display('shophelp_info');
        }

        if ($action === 'insert') {
            // 权限判断
            $this->admin_priv('shophelp_manage');
            $_POST['id'] = intval($_POST['id']);

            // 判断是否重名
            $exc_article->is_only('title', $_POST['title'], lang('title_exist'));

            // 插入数据
            DB::table('article')->insert([
                'title' => $_POST['title'],
                'cat_id' => (int) $_POST['cat_id'],
                'article_type' => (int) $_POST['article_type'],
                'content' => $_POST['FCKeditor1'],
                'add_time' => TimeHelper::gmtime(),
                'author' => '_SHOPHELP',
            ]);

            $link[0]['text'] = lang('back_list');
            $link[0]['href'] = 'shophelp.php?act=list_article&cat_id='.$_POST['cat_id'];
            $link[1]['text'] = lang('continue_add');
            $link[1]['href'] = 'shophelp.php?act=add&cat_id='.$_POST['cat_id'];

            // 清除缓存
            $this->clear_cache_files();

            $this->admin_log($_POST['title'], 'add', 'shophelp');

            return $this->sys_msg(lang('articleadd_succeed'), 0, $link);
        }

        /**
         * 编辑文章
         */
        if ($action === 'edit') {
            // 权限判断
            $this->admin_priv('shophelp_manage');
            $_POST['id'] = intval($_POST['id']);

            // 取文章数据
            $article = (array) DB::table('article')
                ->where('article_id', (int) $_REQUEST['id'])
                ->select('article_id', 'title', 'cat_id', 'article_type', 'is_open', 'author', 'author_email', 'keywords', 'content')
                ->first();

            // 创建 html editor
            MainHelper::create_html_editor('FCKeditor1', $article['content']);

            $this->assign('cat_list', article_cat_list($article['cat_id'], true, 'cat_id', 0));
            $this->assign('ur_here', lang('article_add'));
            $this->assign('action_link', ['text' => lang('article_list'), 'href' => 'shophelp.php?act=list_article&cat_id='.$article['cat_id']]);
            $this->assign('article', $article);
            $this->assign('form_action', 'update');

            return $this->display('shophelp_info');
        }

        if ($action === 'update') {
            // 权限判断
            $this->admin_priv('shophelp_manage');
            $_POST['id'] = intval($_POST['id']);

            // 检查重名
            if ($_POST['title'] != $_POST['old_title']) {
                $exc_article->is_only('title', $_POST['title'], lang('articlename_exist'), $_POST['id']);
            }
            // 更新
            if ($exc_article->edit("title = '$_POST[title]', cat_id = '$_POST[cat_id]', article_type = '$_POST[article_type]', content = '$_POST[FCKeditor1]'", $_POST['id'])) {
                // 清除缓存
                $this->clear_cache_files();

                $link[0]['text'] = lang('back_list');
                $link[0]['href'] = 'shophelp.php?act=list_article&cat_id='.$_POST['cat_id'];

                return $this->sys_msg(sprintf(lang('articleedit_succeed'), $_POST['title']), 0, $link);
                $this->admin_log($_POST['title'], 'edit', 'shophelp');
            }
        }

        /**
         * 编辑分类的名称
         */
        if ($action === 'edit_catname') {
            $this->check_authz_json('shophelp_manage');

            $id = intval($_POST['id']);
            $cat_name = BaseHelper::json_str_iconv(trim($_POST['val']));

            // 检查分类名称是否重复
            if ($exc_cat->num('cat_name', $cat_name, $id) != 0) {
                return $this->make_json_error(sprintf(lang('catname_exist'), $cat_name));
            } else {
                if ($exc_cat->edit("cat_name = '$cat_name'", $id)) {
                    $this->clear_cache_files();
                    $this->admin_log($cat_name, 'edit', 'shophelpcat');

                    return $this->make_json_result(stripslashes($cat_name));
                } else {
                    return $this->make_json_error('DB error');
                }
            }
        }

        /**
         * 编辑分类的排序
         */
        if ($action === 'edit_cat_order') {
            $this->check_authz_json('shophelp_manage');

            $id = intval($_POST['id']);
            $order = BaseHelper::json_str_iconv(trim($_POST['val']));

            // 检查输入的值是否合法
            if (! preg_match('/^[0-9]+$/', $order)) {
                return $this->make_json_result('', sprintf(lang('enter_int'), $order));
            } else {
                if ($exc_cat->edit("sort_order = '$order'", $id)) {
                    $this->clear_cache_files();

                    return $this->make_json_result(stripslashes($order));
                }
            }
        }

        /**
         * 删除分类
         */
        if ($action === 'remove') {
            $this->check_authz_json('shophelp_manage');

            $id = intval($_GET['id']);

            // 非空的分类不允许删除
            if ($exc_article->num('cat_id', $id) != 0) {
                return $this->make_json_error(sprintf(lang('not_emptycat')));
            } else {
                $exc_cat->drop($id);
                $this->clear_cache_files();
                $this->admin_log('', 'remove', 'shophelpcat');
            }

            $url = 'shophelp.php?act=query&'.str_replace('act=remove', '', $_SERVER['QUERY_STRING']);

            return response()->redirectTo($url);
        }

        /**
         * 删除分类下的某文章
         */
        if ($action === 'remove_art') {
            $this->check_authz_json('shophelp_manage');

            $id = intval($_GET['id']);
            $cat_id = DB::table('article')
                ->where('article_id', $id)
                ->value('cat_id');

            if ($exc_article->drop($id)) {
                // 清除缓存
                $this->clear_cache_files();
                $this->admin_log('', 'remove', 'shophelp');
            } else {
                return $this->make_json_error(sprintf(lang('remove_fail')));
            }

            $url = 'shophelp.php?act=query_art&cat='.$cat_id.'&'.str_replace('act=remove_art', '', $_SERVER['QUERY_STRING']);

            return response()->redirectTo($url);
        }

        /**
         * 添加一个新分类
         */
        if ($action === 'add_catname') {
            $this->check_authz_json('shophelp_manage');

            $cat_name = trim($_POST['cat_name']);

            if (! empty($cat_name)) {
                if ($exc_cat->num('cat_name', $cat_name) != 0) {
                    return $this->make_json_error(lang('catname_exist'));
                } else {
                    DB::table('article_cat')->insert([
                        'cat_name' => $cat_name,
                        'cat_type' => 0,
                    ]);

                    $this->admin_log($cat_name, 'add', 'shophelpcat');

                    return response()->redirectTo('shophelp.php?act=query');
                }
            } else {
                return $this->make_json_error(lang('js_languages.no_catname'));
            }

            return response()->redirectTo('shophelp.php?act=list_cat');
        }

        /**
         * 编辑文章标题
         */
        if ($action === 'edit_title') {
            $this->check_authz_json('shophelp_manage');

            $id = intval($_POST['id']);
            $title = BaseHelper::json_str_iconv(trim($_POST['val']));

            // 检查文章标题是否有重名
            if ($exc_article->num('title', $title, $id) === 0) {
                if ($exc_article->edit("title = '$title'", $id)) {
                    $this->clear_cache_files();
                    $this->admin_log($title, 'edit', 'shophelp');

                    return $this->make_json_result(stripslashes($title));
                }
            } else {
                return $this->make_json_error(sprintf(lang('articlename_exist'), $title));
            }
        }
    }

    // 获得网店帮助文章分类
    private function get_shophelp_list()
    {
        $list = [];
        $res = DB::table('article_cat')
            ->where('cat_type', 0)
            ->orderBy('sort_order')
            ->get();
        foreach ($res as $rows) {
            $rows = (array) $rows;
            $rows['num'] = DB::table('article')->where('cat_id', $rows['cat_id'])->count();

            $list[] = $rows;
        }

        return $list;
    }

    // 获得网店帮助某分类下的文章
    private function shophelp_article_list($cat_id)
    {
        $list = [];
        $res = DB::table('article')
            ->where('cat_id', $cat_id)
            ->orderByDesc('article_type')
            ->select('article_id', 'title', 'article_type', 'add_time')
            ->get();
        foreach ($res as $rows) {
            $rows = (array) $rows;
            $rows['add_time'] = TimeHelper::local_date(cfg('time_format'), $rows['add_time']);

            $list[] = $rows;
        }

        return $list;
    }
}
