<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Helpers\BaseHelper;
use App\Helpers\CommonHelper;
use App\Helpers\TimeHelper;
use App\Modules\Admin\Helpers\MainHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommentManageController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        /**
         * 获取没有回复的评论列表
         */
        if ($action === 'list') {
            $this->admin_priv('comment_priv');

            $this->assign('ur_here', lang('05_comment_manage'));
            $this->assign('full_page', 1);

            $list = $this->get_comment_list();

            $this->assign('comment_list', $list['item']);
            $this->assign('filter', $list['filter']);
            $this->assign('record_count', $list['record_count']);
            $this->assign('page_count', $list['page_count']);

            $sort_flag = MainHelper::sort_flag($list['filter']);
            $this->assign($sort_flag['tag'], $sort_flag['img']);

            return $this->display('comment_list');
        }

        /**
         * 翻页、搜索、排序
         */
        if ($action === 'query') {
            $list = $this->get_comment_list();

            $this->assign('comment_list', $list['item']);
            $this->assign('filter', $list['filter']);
            $this->assign('record_count', $list['record_count']);
            $this->assign('page_count', $list['page_count']);

            $sort_flag = MainHelper::sort_flag($list['filter']);
            $this->assign($sort_flag['tag'], $sort_flag['img']);

            return $this->make_json_result(
                $this->fetch('comment_list'),
                '',
                ['filter' => $list['filter'], 'page_count' => $list['page_count']]
            );
        }

        /**
         * 回复用户评论(同时查看评论详情)
         */
        if ($action === 'reply') {
            $this->admin_priv('comment_priv');

            $comment_info = [];
            $reply_info = [];
            $id_value = [];

            // 获取评论详细信息并进行字符处理
            $comment_info = DB::table('comment')->where('comment_id', $_REQUEST['id'])->first();
            $comment_info = $comment_info ? (array) $comment_info : [];
            $comment_info['content'] = str_replace('\r\n', '<br />', htmlspecialchars($comment_info['content']));
            $comment_info['content'] = nl2br(str_replace('\n', '<br />', $comment_info['content']));
            $comment_info['add_time'] = TimeHelper::local_date(cfg('time_format'), $comment_info['add_time']);

            // 获得评论回复内容
            $reply_info = DB::table('comment')->where('parent_id', $_REQUEST['id'])->first();
            $reply_info = $reply_info ? (array) $reply_info : [];

            if (empty($reply_info)) {
                $reply_info['content'] = '';
                $reply_info['add_time'] = '';
            } else {
                $reply_info['content'] = nl2br(htmlspecialchars($reply_info['content']));
                $reply_info['add_time'] = TimeHelper::local_date(cfg('time_format'), $reply_info['add_time']);
            }
            // 获取管理员的用户名和Email地址
            $admin_info = DB::table('admin_user')->where('user_id', session('admin_id'))->select('user_name', 'email')->first();
            $admin_info = $admin_info ? (array) $admin_info : [];
            // 取得评论的对象(文章或者商品)
            if ($comment_info['comment_type'] === 0) {
                $id_value = DB::table('goods')->where('goods_id', $comment_info['id_value'])->value('goods_name');
            } else {
                $id_value = DB::table('article')->where('article_id', $comment_info['id_value'])->value('title');
            }

            $this->assign('msg', $comment_info); // 评论信息
            $this->assign('admin_info', $admin_info);   // 管理员信息
            $this->assign('reply_info', $reply_info);   // 回复的内容
            $this->assign('id_value', $id_value);  // 评论的对象
            $this->assign('send_fail', ! empty($_REQUEST['send_ok']));

            $this->assign('ur_here', lang('comment_info'));
            $this->assign('action_link', [
                'text' => lang('05_comment_manage'),
                'href' => 'comment_manage.php?act=list',
            ]);

            // 页面显示

            return $this->display('comment_info');
        }
        /**
         * 处理 回复用户评论
         */
        if ($action === 'action') {
            $this->admin_priv('comment_priv');

            // 获取IP地址
            $ip = BaseHelper::real_ip();

            // 获得评论是否有回复
            $reply_info = DB::table('comment')->where('parent_id', $_REQUEST['comment_id'])->select('comment_id', 'content', 'parent_id')->first();
            $reply_info = $reply_info ? (array) $reply_info : [];

            if (! empty($reply_info['content'])) {
                // 更新回复的内容
                DB::table('comment')->where('comment_id', $reply_info['comment_id'])->update([
                    'email' => $_POST['email'],
                    'user_name' => $_POST['user_name'],
                    'content' => $_POST['content'],
                    'add_time' => TimeHelper::gmtime(),
                    'ip_address' => $ip,
                    'status' => 0,
                ]);
            } else {
                // 插入回复的评论内容
                DB::table('comment')->insert([
                    'comment_type' => $_POST['comment_type'],
                    'id_value' => $_POST['id_value'],
                    'email' => $_POST['email'],
                    'user_name' => session('admin_name'),
                    'content' => $_POST['content'],
                    'add_time' => TimeHelper::gmtime(),
                    'ip_address' => $ip,
                    'status' => 0,
                    'parent_id' => $_POST['comment_id'],
                ]);
            }

            // 更新当前的评论状态为已回复并且可以显示此条评论
            DB::table('comment')->where('comment_id', $_POST['comment_id'])->update(['status' => 1]);
            // 邮件通知处理流程
            if (! empty($_POST['send_email_notice']) or isset($_POST['remail'])) {
                // 获取邮件中的必要内容
                $comment_info = DB::table('comment')->where('comment_id', $_REQUEST['comment_id'])->select('user_name', 'email', 'content')->first();
                $comment_info = $comment_info ? (array) $comment_info : [];
                // 设置留言回复模板所需要的内容信息
                $template = CommonHelper::get_mail_template('recomment');

                $this->assign('user_name', $comment_info['user_name']);
                $this->assign('recomment', $_POST['content']);
                $this->assign('comment', $comment_info['content']);
                $this->assign('shop_name', "<a href='".ecs()->url()."'>".cfg('shop_name').'</a>');
                $this->assign('send_date', date('Y-m-d'));

                $content = $this->fetch('str:'.$template['template_content']);

                // 发送邮件
                if (BaseHelper::send_mail($comment_info['user_name'], $comment_info['email'], $template['template_subject'], $content, $template['is_html'])) {
                    $send_ok = 0;
                } else {
                    $send_ok = 1;
                }
            }

            // 清除缓存
            $this->clear_cache_files();

            // 记录管理员操作
            $this->admin_log(addslashes(lang('reply')), 'edit', 'users_comment');

            return response()->redirectTo("comment_manage.php?act=reply&id=$_REQUEST[comment_id]&send_ok=$send_ok");
        }
        /**
         * 更新评论的状态为显示或者禁止
         */
        if ($action === 'check') {
            if ($_REQUEST['check'] === 'allow') {
                // 允许评论显示
                DB::table('comment')->where('comment_id', $_REQUEST['id'])->update(['status' => 1]);
                // add_feed($_REQUEST['id'], COMMENT_GOODS); @deprecated

                // 清除缓存
                $this->clear_cache_files();

                return response()->redirectTo("comment_manage.php?act=reply&id=$_REQUEST[id]");
            } else {
                // 禁止评论显示
                DB::table('comment')->where('comment_id', $_REQUEST['id'])->update(['status' => 0]);

                // 清除缓存
                $this->clear_cache_files();

                return response()->redirectTo("comment_manage.php?act=reply&id=$_REQUEST[id]");
            }
        }

        /**
         * 删除某一条评论
         */
        if ($action === 'remove') {
            $this->check_authz_json('comment_priv');

            $id = intval($_GET['id']);

            if (DB::table('comment')->where('comment_id', $id)->delete()) {
                DB::table('comment')->where('parent_id', $id)->delete();
            }
            $this->admin_log('', 'remove', 'ads');

            $url = 'comment_manage.php?act=query&'.str_replace('act=remove', '', $_SERVER['QUERY_STRING']);

            return response()->redirectTo($url);
        }

        /**
         * 批量删除用户评论
         */
        if ($action === 'batch') {
            $this->admin_priv('comment_priv');
            $action = isset($_POST['sel_action']) ? trim($_POST['sel_action']) : 'deny';

            if (isset($_POST['checkboxes'])) {
                switch ($action) {
                    case 'remove':
                        DB::table('comment')->whereIn('comment_id', $_POST['checkboxes'])->delete();
                        DB::table('comment')->whereIn('parent_id', $_POST['checkboxes'])->delete();
                        break;

                    case 'allow':
                        DB::table('comment')->whereIn('comment_id', $_POST['checkboxes'])->update(['status' => 1]);
                        break;

                    case 'deny':
                        DB::table('comment')->whereIn('comment_id', $_POST['checkboxes'])->update(['status' => 0]);
                        break;

                    default:
                        break;
                }

                $this->clear_cache_files();
                $action = ($action === 'remove') ? 'remove' : 'edit';
                $this->admin_log('', $action, 'adminlog');

                $link[] = ['text' => lang('back_list'), 'href' => 'comment_manage.php?act=list'];

                return $this->sys_msg(sprintf(lang('batch_drop_success'), count($_POST['checkboxes'])), 0, $link);
            } else {
                // 提示信息
                $link[] = ['text' => lang('back_list'), 'href' => 'comment_manage.php?act=list'];

                return $this->sys_msg(lang('no_select_comment'), 0, $link);
            }
        }
    }

    /**
     * 获取评论列表
     *
     * @return array
     */
    private function get_comment_list()
    {
        // 查询条件
        $filter['keywords'] = empty($_REQUEST['keywords']) ? 0 : trim($_REQUEST['keywords']);
        if (isset($_REQUEST['is_ajax']) && $_REQUEST['is_ajax'] === 1) {
            $filter['keywords'] = BaseHelper::json_str_iconv($filter['keywords']);
        }

        $sort = ['comment_id', 'user_name', 'comment_type', 'id_value', 'ip_address', 'add_time'];
        $filter['sort_by'] = isset($_REQUEST['sort_by']) && in_array($_REQUEST['sort_by'], $sort) ? $_REQUEST['sort_by'] : 'add_time';
        $filter['sort_order'] = isset($_REQUEST['sort_order']) && in_array($_REQUEST['sort_order'], ['ASC', 'DESC']) ? $_REQUEST['sort_order'] : 'DESC';

        $query = DB::table('comment')->where('parent_id', 0);

        if (! empty($filter['keywords'])) {
            $query->where('content', 'like', '%'.BaseHelper::mysql_like_quote($filter['keywords']).'%');
        }

        $filter['record_count'] = $query->count();

        // 分页大小
        $filter = MainHelper::page_and_size($filter);

        // 获取评论数据
        $res = $query->orderBy($filter['sort_by'], $filter['sort_order'])
            ->offset($filter['start'])
            ->limit($filter['page_size'])
            ->get();

        $arr = [];
        foreach ($res as $row) {
            $row = (array) $row;
            if ($row['comment_type'] === 0) {
                $row['title'] = DB::table('goods')->where('goods_id', $row['id_value'])->value('goods_name');
            } else {
                $row['title'] = DB::table('article')->where('article_id', $row['id_value'])->value('title');
            }

            $row['add_time'] = TimeHelper::local_date(cfg('time_format'), $row['add_time']);

            $arr[] = $row;
        }

        $filter['keywords'] = stripslashes($filter['keywords']);
        $arr = ['item' => $arr, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']];

        return $arr;
    }
}
