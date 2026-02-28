<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Helpers\TimeHelper;
use App\Modules\Admin\Helpers\MainHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class MessageController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        /**
         * 留言列表页面
         */
        if ($action === 'list') {
            $this->assign('full_page', 1);
            $this->assign('ur_here', lang('msg_list'));
            $this->assign('action_link', ['text' => lang('send_msg'), 'href' => 'message.php?act=send']);

            $list = $this->get_message_list();

            $this->assign('message_list', $list['item']);
            $this->assign('filter', $list['filter']);
            $this->assign('record_count', $list['record_count']);
            $this->assign('page_count', $list['page_count']);

            $sort_flag = MainHelper::sort_flag($list['filter']);
            $this->assign($sort_flag['tag'], $sort_flag['img']);

            return $this->display('message_list');
        }

        /**
         * 翻页、排序
         */
        if ($action === 'query') {
            $list = $this->get_message_list();

            $this->assign('message_list', $list['item']);
            $this->assign('filter', $list['filter']);
            $this->assign('record_count', $list['record_count']);
            $this->assign('page_count', $list['page_count']);

            $sort_flag = MainHelper::sort_flag($list['filter']);
            $this->assign($sort_flag['tag'], $sort_flag['img']);

            return $this->make_json_result(
                $this->fetch('message_list'),
                '',
                ['filter' => $list['filter'], 'page_count' => $list['page_count']]
            );
        }

        /**
         * 留言发送页面
         */
        if ($action === 'send') {
            // 获取管理员列表
            $admin_list = DB::table('admin_user')->select('user_id', 'user_name')->get()->map(fn ($r) => (array) $r)->all();

            $this->assign('ur_here', lang('send_msg'));
            $this->assign('action_link', ['href' => 'message.php?act=list', 'text' => lang('msg_list')]);
            $this->assign('action', 'add');
            $this->assign('form_act', 'insert');
            $this->assign('admin_list', $admin_list);

            return $this->display('message_info');
        }

        /**
         * 处理留言的发送
         */
        if ($action === 'insert') {
            $rec_arr = $_POST['receiver_id'];

            // 向所有管理员发送留言
            if ($rec_arr[0] === 0) {
                // 获取管理员信息
                $result = DB::table('admin_user')->where('user_id', '<>', Session::get('admin_id'))->select('user_id')->get();
                foreach ($result as $rows) {
                    $rows = (array) $rows;
                    DB::table('admin_message')->insert([
                        'sender_id' => Session::get('admin_id'),
                        'receiver_id' => $rows['user_id'],
                        'sent_time' => TimeHelper::gmtime(),
                        'read_time' => 0,
                        'readed' => 0,
                        'deleted' => 0,
                        'title' => $_POST['title'],
                        'message' => $_POST['message'],
                    ]);
                }

                // 添加链接
                $link[0]['text'] = lang('back_list');
                $link[0]['href'] = 'message.php?act=list';

                $link[1]['text'] = lang('continue_send_msg');
                $link[1]['href'] = 'message.php?act=send';

                return $this->sys_msg(lang('send_msg').'&nbsp;'.lang('action_succeed'), 0, $link);

                // 记录管理员操作
                $this->admin_log(admin_log(lang('send_msg')), 'add', 'admin_message');
            } else {
                // 如果是发送给指定的管理员
                foreach ($rec_arr as $key => $id) {
                    DB::table('admin_message')->insert([
                        'sender_id' => Session::get('admin_id'),
                        'receiver_id' => $id,
                        'sent_time' => TimeHelper::gmtime(),
                        'read_time' => 0,
                        'readed' => 0,
                        'deleted' => 0,
                        'title' => $_POST['title'],
                        'message' => $_POST['message'],
                    ]);
                }
                $this->admin_log(addslashes(lang('send_msg')), 'add', 'admin_message');

                $link[0]['text'] = lang('back_list');
                $link[0]['href'] = 'message.php?act=list';
                $link[1]['text'] = lang('continue_send_msg');
                $link[1]['href'] = 'message.php?act=send';

                return $this->sys_msg(lang('send_msg').'&nbsp;'.lang('action_succeed'), 0, $link);
            }
        }
        /**
         * 留言编辑页面
         */
        if ($action === 'edit') {
            $id = intval($_REQUEST['id']);

            // 获取管理员列表
            $admin_list = DB::table('admin_user')->select('user_id', 'user_name')->get()->map(fn ($r) => (array) $r)->all();

            // 获得留言数据
            $msg_arr = (array) DB::table('admin_message')
                ->select('message_id', 'receiver_id', 'title', 'message')
                ->where('message_id', $id)
                ->first();

            $this->assign('ur_here', lang('edit_msg'));
            $this->assign('action_link', ['href' => 'message.php?act=list', 'text' => lang('msg_list')]);
            $this->assign('form_act', 'update');
            $this->assign('admin_list', $admin_list);
            $this->assign('msg_arr', $msg_arr);

            return $this->display('message_info');
        }

        if ($action === 'update') {
            // 获得留言数据
            $msg_arr = (array) DB::table('admin_message')->where('message_id', (int) $_POST['id'])->first();

            DB::table('admin_message')
                ->where('sender_id', $msg_arr['sender_id'])
                ->where('sent_time', $msg_arr['send_time'])
                ->update(['title' => $_POST['title'], 'message' => $_POST['message']]);

            $link[0]['text'] = lang('back_list');
            $link[0]['href'] = 'message.php?act=list';

            return $this->sys_msg(lang('edit_msg').' '.lang('action_succeed'), 0, $link);

            // 记录管理员操作
            $this->admin_log(addslashes(lang('edit_msg')), 'edit', 'admin_message');
        }

        /**
         * 留言查看页面
         */
        if ($action === 'view') {
            $msg_id = intval($_REQUEST['id']);

            // 获得管理员留言数据
            $msg_arr = (array) DB::table('admin_message AS a')
                ->leftJoin('admin_user AS b', 'b.user_id', '=', 'a.sender_id')
                ->where('a.message_id', $msg_id)
                ->select('a.*', 'b.user_name')
                ->first();
            $msg_arr['title'] = nl2br(htmlspecialchars($msg_arr['title']));
            $msg_arr['message'] = nl2br(htmlspecialchars($msg_arr['message']));

            // 如果还未阅读
            if ($msg_arr['readed'] === 0) {
                $msg_arr['read_time'] = TimeHelper::gmtime(); // 阅读日期为当前日期

                // 更新阅读日期和阅读状态
                DB::table('admin_message')
                    ->where('message_id', $msg_id)
                    ->update(['read_time' => $msg_arr['read_time'], 'readed' => 1]);
            }

            // 模板赋值，显示
            $this->assign('ur_here', lang('view_msg'));
            $this->assign('action_link', ['href' => 'message.php?act=list', 'text' => lang('msg_list')]);
            $this->assign('admin_user', Session::get('admin_name'));
            $this->assign('msg_arr', $msg_arr);

            return $this->display('message_view');
        }

        /**
         *留言回复页面
         */
        if ($action === 'reply') {
            $msg_id = intval($_REQUEST['id']);

            // 获得留言数据
            $msg_val = (array) DB::table('admin_message AS a')
                ->leftJoin('admin_user AS b', 'b.user_id', '=', 'a.sender_id')
                ->where('a.message_id', $msg_id)
                ->select('a.*', 'b.user_name')
                ->first();

            $this->assign('ur_here', lang('reply_msg'));
            $this->assign('action_link', ['href' => 'message.php?act=list', 'text' => lang('msg_list')]);

            $this->assign('action', 'reply');
            $this->assign('form_act', 're_msg');
            $this->assign('msg_val', $msg_val);

            return $this->display('message_info');
        }

        /**
         *留言回复的处理
         */
        if ($action === 're_msg') {
            DB::table('admin_message')->insert([
                'sender_id' => Session::get('admin_id'),
                'receiver_id' => $_POST['receiver_id'],
                'sent_time' => TimeHelper::gmtime(),
                'read_time' => 0,
                'readed' => 0,
                'deleted' => 0,
                'title' => $_POST['title'],
                'message' => $_POST['message'],
            ]);

            $link[0]['text'] = lang('back_list');
            $link[0]['href'] = 'message.php?act=list';

            return $this->sys_msg(lang('send_msg').' '.lang('action_succeed'), 0, $link);

            // 记录管理员操作
            $this->admin_log(addslashes(lang('send_msg')), 'add', 'admin_message');
        }

        /**
         * 批量删除留言记录
         */
        if ($action === 'drop_msg') {
            if (isset($_POST['checkboxes'])) {
                $count = 0;
                foreach ($_POST['checkboxes'] as $key => $id) {
                    DB::table('admin_message')
                        ->where('message_id', (int) $id)
                        ->where(function ($q) {
                            $q->where('sender_id', Session::get('admin_id'))
                                ->orWhere('receiver_id', Session::get('admin_id'));
                        })
                        ->update(['deleted' => 1]);
                    $count++;
                }

                $this->admin_log('', 'remove', 'admin_message');
                $link[] = ['text' => lang('back_list'), 'href' => 'message.php?act=list'];

                return $this->sys_msg(sprintf(lang('batch_drop_success'), $count), 0, $link);
            } else {
                return $this->sys_msg(lang('no_select_msg'), 1);
            }
        }

        /**
         * 删除留言
         */
        if ($action === 'remove') {
            $id = intval($_GET['id']);

            DB::table('admin_message')
                ->where('message_id', $id)
                ->where(function ($q) {
                    $q->where('sender_id', Session::get('admin_id'))
                        ->orWhere('receiver_id', Session::get('admin_id'));
                })
                ->update(['deleted' => 1]);

            $url = 'message.php?act=query&'.str_replace('act=remove', '', $_SERVER['QUERY_STRING']);

            return response()->redirectTo($url);
        }
    }

    /**
     *  获取管理员留言列表
     *
     * @return void
     */
    private function get_message_list()
    {
        // 查询条件
        $filter['sort_by'] = empty($_REQUEST['sort_by']) ? 'sent_time' : trim($_REQUEST['sort_by']);
        $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);
        $filter['msg_type'] = empty($_REQUEST['msg_type']) ? 0 : intval($_REQUEST['msg_type']);

        // 查询条件
        switch ($filter['msg_type']) {
            case 1:
                $where = " a.receiver_id='".Session::get('admin_id')."'";
                break;
            case 2:
                $where = " a.sender_id='".Session::get('admin_id')."' AND a.deleted='0'";
                break;
            case 3:
                $where = " a.readed='0' AND a.receiver_id='".Session::get('admin_id')."' AND a.deleted='0'";
                break;
            case 4:
                $where = " a.readed='1' AND a.receiver_id='".Session::get('admin_id')."' AND a.deleted='0'";
                break;
            default:
                $where = " (a.receiver_id='".Session::get('admin_id')."' OR a.sender_id='".Session::get('admin_id')."') AND a.deleted='0'";
        }

        $filter['record_count'] = DB::selectOne('SELECT COUNT(*) AS cnt FROM '.ecs()->table('admin_message').' AS a WHERE 1 AND '.$where)->cnt;

        // 分页大小
        $filter = MainHelper::page_and_size($filter);

        $row = DB::table('admin_message as a')
            ->select('a.message_id', 'a.sender_id', 'a.receiver_id', 'a.sent_time', 'a.read_time', 'a.deleted', 'a.title', 'a.message', 'b.user_name')
            ->join('admin_user as b', 'a.sender_id', '=', 'b.user_id')
            ->orderBy($filter['sort_by'], $filter['sort_order'])
            ->offset($filter['start'])
            ->limit($filter['page_size'])
            ->get();

        foreach ($row as $key => $val) {
            $val = (array) $val;
            $row[$key] = $val;
            $row[$key]['sent_time'] = TimeHelper::local_date(cfg('time_format'), $val['sent_time']);
            $row[$key]['read_time'] = TimeHelper::local_date(cfg('time_format'), $val['read_time']);
        }

        $arr = ['item' => $row, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']];

        return $arr;
    }
}
