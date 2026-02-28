<?php

declare(strict_types=1);

namespace App\Modules\User\Controllers;

use App\Helpers\ClipsHelper;
use App\Helpers\MainHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class MessageController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        if ($action === 'message_list') {
            $page = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;

            $order_id = empty($_GET['order_id']) ? 0 : intval($_GET['order_id']);
            $order_info = [];

            // 获取用户留言的数量
            $query = DB::table('feedback')->where('parent_id', 0);
            if ($order_id) {
                $query->where('order_id', $order_id)->where('user_id', $this->getUserId());
                $order_info = DB::table('order_info')->where('order_id', $order_id)->where('user_id', $this->getUserId())->first();
                if ($order_info) {
                    $order_info = (array) $order_info;
                    $order_info['url'] = 'user.php?act=order_detail&order_id='.$order_id;
                }
            } else {
                $query->where('user_id', $this->getUserId())->where('user_name', Session::get('user_name'))->where('order_id', 0);
            }

            $record_count = $query->count();
            $act = ['act' => $action];

            if ($order_id != '') {
                $act['order_id'] = $order_id;
            }

            $pager = MainHelper::get_pager('user.php', $act, $record_count, $page, 5);

            $this->assign('message_list', ClipsHelper::get_message_list($this->getUserId(), Session::get('user_name'), $pager['size'], $pager['start'], $order_id));
            $this->assign('pager', $pager);
            $this->assign('order_info', $order_info);

            return $this->display('user_clips');
        }

        // 添加我的留言
        if ($action === 'act_add_message') {
            $message = [
                'user_id' => $this->getUserId(),
                'user_name' => Session::get('user_name'),
                'user_email' => Session::get('email'),
                'msg_type' => isset($_POST['msg_type']) ? intval($_POST['msg_type']) : 0,
                'msg_title' => isset($_POST['msg_title']) ? trim($_POST['msg_title']) : '',
                'msg_content' => isset($_POST['msg_content']) ? trim($_POST['msg_content']) : '',
                'order_id' => empty($_POST['order_id']) ? 0 : intval($_POST['order_id']),
                'upload' => (isset($_FILES['message_img']['error']) && $_FILES['message_img']['error'] === 0) || (! isset($_FILES['message_img']['error']) && isset($_FILES['message_img']['tmp_name']) && $_FILES['message_img']['tmp_name'] != 'none')
                    ? $_FILES['message_img'] : [],
            ];

            if (ClipsHelper::add_message($message)) {
                $this->show_message(lang('add_message_success'), lang('message_list_lnk'), 'user.php?act=message_list&order_id='.$message['order_id'], 'info');
            } else {
                $this->show_message(lang('back_up_page'), lang('message_list_lnk'), 'user.php?act=message_list', 'error');
            }
        }

        // 删除留言
        if ($action === 'del_msg') {
            $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
            $order_id = empty($_GET['order_id']) ? 0 : intval($_GET['order_id']);

            if ($id > 0) {
                $row = DB::table('feedback')->where('msg_id', $id)->select('user_id', 'message_img')->first();
                if ($row) {
                    $row = (array) $row;
                    if ($row['user_id'] === $this->getUserId()) {
                        // 验证通过，删除留言，回复，及相应文件
                        if ($row['message_img']) {
                            @unlink(ROOT_PATH.DATA_DIR.'/feedbackimg/'.$row['message_img']);
                        }
                        DB::table('feedback')->where('msg_id', $id)->orWhere('parent_id', $id)->delete();
                    }
                }
            }

            return response()->redirectTo("user.php?act=message_list&order_id=$order_id");
        }
    }
}
