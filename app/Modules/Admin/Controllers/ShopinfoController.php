<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Helpers\BaseHelper;
use App\Helpers\TimeHelper;
use App\Modules\Admin\Helpers\MainHelper;
use App\Modules\Admin\Libraries\Exchange;
use Illuminate\Http\Request;

class ShopinfoController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        require_once ROOT_PATH.'includes/fckeditor/fckeditor.php';

        $exc = new Exchange(ecs()->table('article'), db(), 'article_id', 'title');

        /**
         * 文章列表
         */
        if ($action === 'list') {
            $this->assign('ur_here', lang('shop_info'));
            $this->assign('action_link', ['text' => lang('shopinfo_add'), 'href' => 'shopinfo.php?act=add']);
            $this->assign('full_page', 1);
            $this->assign('list', $this->shopinfo_article_list());

            return $this->display('shopinfo_list');
        }

        /**
         * 查询
         */
        if ($action === 'query') {
            $this->assign('list', $this->shopinfo_article_list());

            return $this->make_json_result($this->fetch('shopinfo_list'));
        }

        /**
         * 添加新文章
         */
        if ($action === 'add') {
            // 权限判断
            $this->admin_priv('shopinfo_manage');
            $_REQUEST['id'] = intval($_REQUEST['id']);

            // 创建 html editor
            MainHelper::create_html_editor('FCKeditor1');

            // 初始化
            $article['article_type'] = 0;

            $this->assign('ur_here', lang('shopinfo_add'));
            $this->assign('action_link', ['text' => lang('shopinfo_list'), 'href' => 'shopinfo.php?act=list']);
            $this->assign('form_action', 'insert');

            return $this->display('shopinfo_info');
        }

        if ($action === 'insert') {
            // 权限判断
            $this->admin_priv('shopinfo_manage');
            $_REQUEST['id'] = intval($_REQUEST['id']);

            // 判断是否重名
            $is_only = $exc->is_only('title', $_POST['title']);

            if (! $is_only) {
                return $this->sys_msg(sprintf(lang('title_exist'), stripslashes($_POST['title'])), 1);
            }

            // 插入数据
            $add_time = TimeHelper::gmtime();
            DB::table('article')->insert([
                'title' => $_POST['title'],
                'cat_id' => 0,
                'content' => $_POST['FCKeditor1'],
                'add_time' => $add_time,
            ]);

            $link[0]['text'] = lang('continue_add');
            $link[0]['href'] = 'shopinfo.php?act=add';

            $link[1]['text'] = lang('back_list');
            $link[1]['href'] = 'shopinfo.php?act=list';

            // 清除缓存
            $this->clear_cache_files();

            $this->admin_log($_POST['title'], 'add', 'shopinfo');

            return $this->sys_msg(lang('articleadd_succeed'), 0, $link);
        }

        /**
         * 文章编辑
         */
        if ($action === 'edit') {
            // 权限判断
            $this->admin_priv('shopinfo_manage');
            $_REQUEST['id'] = intval($_REQUEST['id']);

            // 取得文章数据
            $article = DB::table('article')
                ->select('article_id', 'title', 'content')
                ->where('article_id', $_REQUEST['id'])
                ->first();
            $article = $article ? (array) $article : [];

            // 创建 html editor
            MainHelper::create_html_editor('FCKeditor1', $article['content']);

            $this->assign('ur_here', lang('article_add'));
            $this->assign('action_link', ['text' => lang('shopinfo_list'), 'href' => 'shopinfo.php?act=list']);
            $this->assign('article', $article);
            $this->assign('form_action', 'update');

            return $this->display('shopinfo_info');
        }

        if ($action === 'update') {
            // 权限判断
            $this->admin_priv('shopinfo_manage');
            $_REQUEST['id'] = intval($_REQUEST['id']);

            // 检查重名
            if ($_POST['title'] != $_POST['old_title']) {
                $is_only = $exc->is_only('title', $_POST['title'], $_POST['id']);

                if (! $is_only) {
                    return $this->sys_msg(sprintf(lang('title_exist'), stripslashes($_POST['title'])), 1);
                }
            }

            // 更新数据
            $cur_time = TimeHelper::gmtime();
            if ($exc->edit("title='$_POST[title]', content='$_POST[FCKeditor1]',add_time ='$cur_time'", $_POST['id'])) {
                // 清除缓存
                $this->clear_cache_files();

                $link[0]['text'] = lang('back_list');
                $link[0]['href'] = 'shopinfo.php?act=list';

                return $this->sys_msg(sprintf(lang('articleedit_succeed'), $_POST['title']), 0, $link);
                $this->admin_log($_POST['title'], 'edit', 'shopinfo');
            }
        }

        /**
         * 编辑文章主题
         */
        if ($action === 'edit_title') {
            $this->check_authz_json('shopinfo_manage');

            $id = intval($_POST['id']);
            $title = BaseHelper::json_str_iconv(trim($_POST['val']));

            // 检查文章标题是否有重名
            if ($exc->num('title', $title, $id) === 0) {
                if ($exc->edit("title = '$title'", $id)) {
                    $this->clear_cache_files();
                    $this->admin_log($title, 'edit', 'shopinfo');

                    return $this->make_json_result(stripslashes($title));
                }
            } else {
                return $this->make_json_error(sprintf(lang('title_exist'), $title));
            }
        }

        /**
         * 删除文章
         */
        if ($action === 'remove') {
            $this->check_authz_json('shopinfo_manage');

            $id = intval($_GET['id']);

            // 获得文章主题
            $title = $exc->get_name($id);
            if ($exc->drop($id)) {
                $this->clear_cache_files();
                $this->admin_log(addslashes($title), 'remove', 'shopinfo');
            }

            $url = 'shopinfo.php?act=query&'.str_replace('act=remove', '', $_SERVER['QUERY_STRING']);

            return response()->redirectTo($url);
        }
    }

    // 获取网店信息文章数据
    private function shopinfo_article_list()
    {
        $list = [];
        $res = DB::table('article')
            ->select('article_id', 'title', 'add_time')
            ->where('cat_id', 0)
            ->orderBy('article_id')
            ->get();
        foreach ($res as $rows) {
            $rows['add_time'] = TimeHelper::local_date(cfg('time_format'), $rows['add_time']);

            $list[] = $rows;
        }

        return $list;
    }
}
