<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Helpers\BaseHelper;
use App\Helpers\CommonHelper;
use App\Helpers\TimeHelper;
use App\Modules\Admin\Helpers\MainHelper;
use App\Modules\Admin\Libraries\Exchange;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class UserMsgController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        // 权限判断
        $this->admin_priv('feedback_priv');

        // 初始化数据交换对象
        $exc = new Exchange(ecs()->table('feedback'), db(), 'msg_id', 'msg_title');

        /**
         * 发送留言
         */
        if ($action === 'add') {
            $user_id = empty($_GET['user_id']) ? 0 : intval($_GET['user_id']);
            $order_id = empty($_GET['order_id']) ? 0 : intval($_GET['order_id']);
            $order_sn = DB::table('order_info')->where('order_id', $order_id)->value('order_sn');

            // 获取关于订单所有信息
            $msg_list = DB::table('feedback')
                ->select('msg_id', 'user_name', 'msg_title', 'msg_type', 'msg_time', 'msg_content')
                ->where('user_id', $user_id)
                ->where('order_id', $order_id)
                ->get()
                ->map(fn ($r) => (array) $r)
                ->toArray();
            foreach ($msg_list as $key => $val) {
                $msg_list[$key]['msg_time'] = TimeHelper::local_date(cfg('time_format'), $val['msg_time']);
            }

            $this->assign('ur_here', sprintf(lang('msg_for_order'), $order_sn));
            $this->assign('action_link', ['text' => lang('order_detail'), 'href' => 'order.php?act=info&order_id='.$order_id]);
            $this->assign('msg_list', $msg_list);
            $this->assign('order_id', $_GET['order_id']);
            $this->assign('user_id', $_GET['user_id']);

            return $this->display('msg_add');
        }

        if ($action === 'insert') {
            DB::table('feedback')->insert([
                'parent_id' => 0,
                'user_id' => $_POST['user_id'],
                'user_name' => Session::get('admin_name'),
                'user_email' => ' ',
                'msg_title' => $_POST['msg_title'],
                'msg_type' => 5,
                'msg_content' => $_POST['msg_content'],
                'msg_time' => TimeHelper::gmtime(),
                'message_img' => '',
                'order_id' => $_POST['order_id'],
            ]);

            return response()->redirectTo("user_msg.php?act=add&order_id=$_POST[order_id]&user_id=$_POST[user_id]");
        }

        if ($action === 'remove_msg') {
            $msg_id = empty($_GET['msg_id']) ? 0 : intval($_GET['msg_id']);
            $order_id = empty($_GET['order_id']) ? 0 : intval($_GET['order_id']);
            $user_id = empty($_GET['user_id']) ? 0 : intval($_GET['user_id']);
            $row = (array) DB::table('feedback')->where('msg_id', $msg_id)->select('user_id', 'order_id', 'message_img')->first();
            if ($row) {
                if ($row['user_id'] === $user_id && $row['order_id'] === $order_id) {
                    if ($row['message_img']) {
                        @unlink(ROOT_PATH.DATA_DIR.'/feedbackimg/'.$row['message_img']);
                    }
                    DB::table('feedback')->where('msg_id', $msg_id)->limit(1)->delete();
                }
            }

            return response()->redirectTo("user_msg.php?act=add&order_id=$_GET[order_id]&user_id=$_GET[user_id]");
        }
        /**
         * 更新留言的状态为显示或者禁止
         */
        if ($action === 'check') {
            if ($_REQUEST['check'] === 'allow') {
                // 允许留言显示
                DB::table('feedback')->where('msg_id', $_REQUEST['id'])->update(['msg_status' => 1]);

                // 清除缓存
                $this->clear_cache_files();

                return response()->redirectTo("user_msg.php?act=view&id=$_REQUEST[id]");
            } else {
                // 禁止留言显示
                DB::table('feedback')->where('msg_id', $_REQUEST['id'])->update(['msg_status' => 0]);

                // 清除缓存
                $this->clear_cache_files();

                return response()->redirectTo("user_msg.php?act=view&id=$_REQUEST[id]");
            }
        }
        /**
         * 列出所有留言
         */
        if ($action === 'list_all') {
            $msg_list = $this->msg_list();

            $this->assign('msg_list', $msg_list['msg_list']);
            $this->assign('filter', $msg_list['filter']);
            $this->assign('record_count', $msg_list['record_count']);
            $this->assign('page_count', $msg_list['page_count']);
            $this->assign('full_page', 1);
            $this->assign('ur_here', lang('08_unreply_msg'));
            $this->assign('full_page', 1);

            return $this->display('msg_list');
        }

        /**
         * ajax显示留言列表
         */
        if ($action === 'query') {
            $msg_list = $this->msg_list();

            $this->assign('msg_list', $msg_list['msg_list']);
            $this->assign('filter', $msg_list['filter']);
            $this->assign('record_count', $msg_list['record_count']);
            $this->assign('page_count', $msg_list['page_count']);

            $sort_flag = MainHelper::sort_flag($msg_list['filter']);
            $this->assign($sort_flag['tag'], $sort_flag['img']);

            return $this->make_json_result($this->fetch('msg_list'), '', ['filter' => $msg_list['filter'], 'page_count' => $msg_list['page_count']]);
        }
        /**
         * ajax 删除留言
         */
        if ($action === 'remove') {
            $msg_id = intval($_REQUEST['id']);

            $this->check_authz_json('feedback_priv');

            $msg_title = $exc->get_name($msg_id);
            $img = $exc->get_name($msg_id, 'message_img');
            if ($exc->drop($msg_id)) {
                // 删除图片
                if (! empty($img)) {
                    @unlink(ROOT_PATH.DATA_DIR.'/feedbackimg/'.$img);
                }
                DB::table('feedback')->where('parent_id', $msg_id)->limit(1)->delete();

                $this->admin_log(addslashes($msg_title), 'remove', 'message');
                $url = 'user_msg.php?act=query&'.str_replace('act=remove', '', $_SERVER['QUERY_STRING']);

                return response()->redirectTo($url);
            } else {
                return $this->make_json_error(lang('delete_failed'));
            }
        }

        /**
         * 批量操作删除、允许显示、禁止显示用户评论
         */
        if ($action === 'batch') {
            $this->admin_priv('feedback_priv');
            $action = isset($_POST['sel_action']) ? trim($_POST['sel_action']) : 'def';

            if (isset($_POST['checkboxes'])) {
                switch ($action) {
                    case 'remove':
                        DB::table('feedback')->whereIn('msg_id', $_POST['checkboxes'])->delete();
                        DB::table('feedback')->whereIn('parent_id', $_POST['checkboxes'])->delete();
                        break;

                    case 'allow':
                        DB::table('feedback')->whereIn('msg_id', $_POST['checkboxes'])->update(['msg_status' => 1]);
                        break;

                    case 'deny':
                        DB::table('feedback')->whereIn('msg_id', $_POST['checkboxes'])->update(['msg_status' => 0, 'msg_area' => 1]);
                        break;

                    default:
                        break;
                }

                $this->clear_cache_files();
                $action = ($action === 'remove') ? 'remove' : 'edit';
                $this->admin_log('', $action, 'adminlog');

                $link[] = ['text' => lang('back_list'), 'href' => 'user_msg.php?act=list_all'];

                return $this->sys_msg(sprintf(lang('batch_drop_success'), count($_POST['checkboxes'])), 0, $link);
            } else {
                // 提示信息
                $link[] = ['text' => lang('back_list'), 'href' => 'user_msg.php?act=list_all'];

                return $this->sys_msg(lang('no_select_comment'), 0, $link);
            }
        }

        /**
         * 回复留言
         */
        if ($action === 'view') {
            $this->assign('send_fail', ! empty($_REQUEST['send_ok']));
            $this->assign('msg', $this->get_feedback_detail(intval($_REQUEST['id'])));
            $this->assign('ur_here', lang('reply'));
            $this->assign('action_link', ['text' => lang('08_unreply_msg'), 'href' => 'user_msg.php?act=list_all']);

            return $this->display('msg_info');
        }

        if ($action === 'action') {
            if (empty($_REQUEST['parent_id'])) {
                DB::table('feedback')->insert([
                    'msg_title' => 'reply',
                    'msg_time' => TimeHelper::gmtime(),
                    'user_id' => Session::get('admin_id'),
                    'user_name' => Session::get('admin_name'),
                    'user_email' => $_POST['user_email'],
                    'parent_id' => $_REQUEST['msg_id'],
                    'msg_content' => $_POST['msg_content'],
                ]);
            } else {
                DB::table('feedback')->where('msg_id', $_REQUEST['parent_id'])->update([
                    'user_email' => $_POST['user_email'],
                    'msg_content' => $_POST['msg_content'],
                    'msg_time' => TimeHelper::gmtime(),
                ]);
            }

            // 邮件通知处理流程
            if (! empty($_POST['send_email_notice']) or isset($_POST['remail'])) {
                // 获取邮件中的必要内容
                $message_info = (array) DB::table('feedback')
                    ->where('msg_id', $_REQUEST['msg_id'])
                    ->select('user_name', 'user_email', 'msg_title', 'msg_content')
                    ->first();

                // 设置留言回复模板所需要的内容信息
                $template = CommonHelper::get_mail_template('user_message');
                $message_content = $message_info['msg_title']."\r\n".$message_info['msg_content'];

                $this->assign('user_name', $message_info['user_name']);
                $this->assign('message_note', $_POST['msg_content']);
                $this->assign('message_content', $message_content);
                $this->assign('shop_name', "<a href='".ecs()->url()."'>".cfg('shop_name').'</a>');
                $this->assign('send_date', date('Y-m-d'));

                $content = $this->fetch('str:'.$template['template_content']);

                // 发送邮件
                if (BaseHelper::send_mail($message_info['user_name'], $message_info['user_email'], $template['template_subject'], $content, $template['is_html'])) {
                    $send_ok = 0;
                } else {
                    $send_ok = 1;
                }
            }

            return response()->redirectTo('?act=view&id='.$_REQUEST['msg_id']."&send_ok=$send_ok");
        }

        /**
         * 删除会员上传的文件
         */
        if ($action === 'drop_file') {
            // 删除上传的文件
            $file = $_GET['file'];
            $file = str_replace('/', '', $file);
            @unlink('../'.DATA_DIR.'/feedbackimg/'.$file);

            // 更新数据库
            DB::table('feedback')->where('msg_id', $_GET['id'])->update(['message_img' => '']);

            return response()->redirectTo('user_msg.php?act=view&amp;id='.$_GET['id']);
        }
    }

    /**
     * @return void
     */
    private function msg_list(): array
    {
        // 过滤条件
        $filter['keywords'] = empty($_REQUEST['keywords']) ? '' : trim($_REQUEST['keywords']);
        if (isset($_REQUEST['is_ajax']) && $_REQUEST['is_ajax'] === 1) {
            $filter['keywords'] = BaseHelper::json_str_iconv($filter['keywords']);
        }
        $filter['msg_type'] = isset($_REQUEST['msg_type']) ? intval($_REQUEST['msg_type']) : -1;
        $filter['sort_by'] = empty($_REQUEST['sort_by']) ? 'f.msg_id' : trim($_REQUEST['sort_by']);
        $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);

        $where = '';
        if ($filter['keywords']) {
            $where .= " AND f.msg_title LIKE '%".BaseHelper::mysql_like_quote($filter['keywords'])."%' ";
        }
        if ($filter['msg_type'] != -1) {
            $where .= " AND f.msg_type = '$filter[msg_type]' ";
        }

        $filter['record_count'] = DB::table('feedback AS f')
            ->whereRaw("parent_id = '0'".$where)
            ->count();

        // 分页大小
        $filter = MainHelper::page_and_size($filter);

        $msg_list = DB::table('feedback as f')
            ->selectRaw('f.msg_id, f.user_name, f.msg_title, f.msg_type, f.order_id, f.msg_status, f.msg_time, f.msg_area, COUNT(r.msg_id) AS reply')
            ->leftJoin('feedback as r', 'r.parent_id', '=', 'f.msg_id')
            ->where('f.parent_id', 0)
            ->groupBy('f.msg_id')
            ->orderBy($filter['sort_by'], $filter['sort_order'])
            ->offset($filter['start'])
            ->limit($filter['page_size'])
            ->get()
            ->map(fn ($r) => (array) $r)
            ->toArray();
        foreach ($msg_list as $key => $value) {
            if ($value['order_id'] > 0) {
                $msg_list[$key]['order_sn'] = DB::table('order_info')->where('order_id', $value['order_id'])->value('order_sn');
            }
            $msg_list[$key]['msg_time'] = TimeHelper::local_date(cfg('time_format'), $value['msg_time']);
            $msg_list[$key]['msg_type'] = lang('type')[$value['msg_type']];
        }
        $filter['keywords'] = stripslashes($filter['keywords']);
        $arr = ['msg_list' => $msg_list, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']];

        return $arr;
    }

    /**
     * 获得留言的详细信息
     *
     * @param  int  $id
     * @return array
     */
    private function get_feedback_detail($id)
    {
        $sql = 'SELECT T1.*, T2.msg_id AS reply_id, T2.user_name  AS reply_name, u.email AS reply_email, '.
            'T2.msg_content AS reply_content , T2.msg_time AS reply_time, T2.user_name AS reply_name '.
            'FROM '.ecs()->table('feedback').' AS T1 '.
            'LEFT JOIN '.ecs()->table('admin_user')." AS u ON u.user_id='".Session::get('admin_id')."' ".
            'LEFT JOIN '.ecs()->table('feedback').' AS T2 ON T2.parent_id=T1.msg_id '.
            "WHERE T1.msg_id = '$id'";
        $msg = (array) DB::selectOne($sql);

        if ($msg) {
            $msg['msg_time'] = TimeHelper::local_date(cfg('time_format'), $msg['msg_time']);
            $msg['reply_time'] = TimeHelper::local_date(cfg('time_format'), $msg['reply_time']);
        }

        return $msg;
    }
}
