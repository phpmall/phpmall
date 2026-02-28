<?php

declare(strict_types=1);

namespace App\Modules\Web\Controllers;

use App\Helpers\BaseHelper;
use App\Helpers\ClipsHelper;
use App\Helpers\CommonHelper;
use App\Helpers\GoodsHelper;
use App\Helpers\MainHelper;
use App\Helpers\TimeHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class MessageController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->input('act');
        if (empty(cfg('message_board'))) {
            $this->show_message(lang('message_board_close'));
        }
        $action = $request->has('act') ? trim($request->input('act')) : 'default';
        if ($action === 'act_add_message') {
            // 验证码防止灌水刷屏
            if ((intval(cfg('captcha')) & CAPTCHA_MESSAGE) && BaseHelper::gd_version() > 0) {
                //                $validator = new captcha;
                //                if (!$validator->check_word($request->input('captcha'))) {
                //                    $this->show_message(lang('invalid_captcha'));
                //                }
            } else {
                // 没有验证码时，用时间来限制机器人发帖或恶意发评论
                if (! Session::has('send_time')) {
                    Session::put('send_time', 0);
                }

                $cur_time = TimeHelper::gmtime();
                if (($cur_time - Session::get('send_time')) < 30) { // 小于30秒禁止发评论
                    $this->show_message(lang('cmt_spam_warning'));
                }
            }
            $user_name = '';
            if (empty($request->input('anonymous')) && Session::has('user_name')) {
                $user_name = Session::get('user_name');
            } elseif (! empty($request->input('anonymous')) && ! $request->has('user_name')) {
                $user_name = lang('anonymous');
            } elseif (empty($request->input('user_name'))) {
                $user_name = lang('anonymous');
            } else {
                $user_name = htmlspecialchars(trim($request->input('user_name')));
            }

            $user_id = Session::get('user_id', 0);
            $message = [
                'user_id' => $user_id,
                'user_name' => $user_name,
                'user_email' => $request->has('user_email') ? htmlspecialchars(trim($request->input('user_email'))) : '',
                'msg_type' => $request->has('msg_type') ? intval($request->input('msg_type')) : 0,
                'msg_title' => $request->has('msg_title') ? trim($request->input('msg_title')) : '',
                'msg_content' => $request->has('msg_content') ? trim($request->input('msg_content')) : '',
                'order_id' => 0,
                'msg_area' => 1,
                'upload' => [],
            ];

            if (ClipsHelper::add_message($message)) {
                if (intval(cfg('captcha')) & CAPTCHA_MESSAGE) {
                    //                    Session::forget($validator->session_word);
                } else {
                    Session::put('send_time', $cur_time);
                }
                $msg_info = cfg('message_check') ? lang('message_submit_wait') : lang('message_submit_done');
                $this->show_message($msg_info, lang('message_list_lnk'), 'message.php');
            } else {
                $this->show_message(lang('message_list_lnk'), lang('message_list_lnk'), 'message.php');
            }
        }

        if ($action === 'default') {
            $this->assign_template();
            $position = $this->assign_ur_here(0, lang('message_board'));
            $this->assign('page_title', $position['title']);    // 页面标题
            $this->assign('ur_here', $position['ur_here']);  // 当前位置
            $this->assign('helps', MainHelper::get_shop_help());       // 网店帮助

            $this->assign('categories', GoodsHelper::get_categories_tree()); // 分类树
            $this->assign('top_goods', GoodsHelper::get_top10());           // 销售排行
            $this->assign('cat_list', CommonHelper::cat_list(0, 0, true, 2, false));
            $this->assign('brand_list', CommonHelper::get_brand_list());
            $this->assign('promotion_info', CommonHelper::get_promotion_info());

            $this->assign('enabled_mes_captcha', (intval(cfg('captcha')) & CAPTCHA_MESSAGE));

            $record_count = DB::table('comment')->where('status', 1)->where('comment_type', 0)->count();
            $record_count += DB::table('feedback')->where('msg_area', '1')->where('msg_status', '1')->count();

            // 获取留言的数量
            $page = $request->input('page', 1);
            $pagesize = MainHelper::get_library_number('message_list', 'message_board');
            $pager = MainHelper::get_pager('message.php', [], $record_count, $page, $pagesize);
            $msg_lists = $this->get_msg_list($pagesize, $pager['start']);
            $this->assign_dynamic('message_board');
            $this->assign('rand', mt_rand());
            $this->assign('msg_lists', $msg_lists);
            $this->assign('pager', $pager);

            return $this->display('message_board');
        }
    }

    /**
     * 获取留言的详细信息
     *
     * @param  int  $num
     * @param  int  $start
     * @return array
     */
    private function get_msg_list($num, $start)
    {
        // 获取留言数据
        $msg = [];

        // 使用 union 查询
        $commentQuery = DB::table('comment')
            ->selectRaw("'comment' AS tablename, comment_id AS ID, content AS msg_content, null AS msg_title, add_time AS msg_time, id_value AS id_value, comment_rank AS comment_rank, null AS message_img, user_name AS user_name, '6' AS msg_type")
            ->where('status', 1)
            ->where('comment_type', 0);

        $feedbackQuery = DB::table('feedback')
            ->selectRaw("'feedback' AS tablename, msg_id AS ID, msg_content AS msg_content, msg_title AS msg_title, msg_time AS msg_time, null AS id_value, null AS comment_rank, message_img AS message_img, user_name AS user_name, msg_type AS msg_type")
            ->where('msg_area', '1')
            ->where('msg_status', '1');

        // 使用 union 合并两个查询
        $res = $commentQuery->union($feedbackQuery)
            ->orderByDesc('msg_time')
            ->offset($start)
            ->limit($num)
            ->get();
        $res = array_map(fn ($item) => (array) $item, $res);

        foreach ($res as $rows) {
            for ($i = 0; $i < count($rows); $i++) {
                $msg[$rows['msg_time']]['user_name'] = htmlspecialchars($rows['user_name']);
                $msg[$rows['msg_time']]['msg_content'] = str_replace('\r\n', '<br />', htmlspecialchars($rows['msg_content']));
                $msg[$rows['msg_time']]['msg_content'] = str_replace('\n', '<br />', $msg[$rows['msg_time']]['msg_content']);
                $msg[$rows['msg_time']]['msg_time'] = TimeHelper::local_date(cfg('time_format'), $rows['msg_time']);
                $msg[$rows['msg_time']]['msg_type'] = lang('message_type')[$rows['msg_type']];
                $msg[$rows['msg_time']]['msg_title'] = nl2br(htmlspecialchars($rows['msg_title']));
                $msg[$rows['msg_time']]['message_img'] = $rows['message_img'];
                $msg[$rows['msg_time']]['tablename'] = $rows['tablename'];

                if (isset($rows['order_id'])) {
                    $msg[$rows['msg_time']]['order_id'] = $rows['order_id'];
                }
                $msg[$rows['msg_time']]['comment_rank'] = $rows['comment_rank'];
                $msg[$rows['msg_time']]['id_value'] = $rows['id_value'];

                // 如果id_value为true为商品评论,根据商品id取出商品名称
                if ($rows['id_value']) {
                    $goods_res = DB::table('goods')
                        ->where('goods_id', $rows['id_value'])
                        ->select('goods_name')
                        ->first();
                    $goods_res = (array) $goods_res;
                    $msg[$rows['msg_time']]['goods_name'] = $goods_res['goods_name'];
                    $msg[$rows['msg_time']]['goods_url'] = build_uri('goods', ['gid' => $rows['id_value']], $goods_res['goods_name']);
                }
            }

            $msg[$rows['msg_time']]['tablename'] = $rows['tablename'];
            $id = $rows['ID'];
            $reply = [];
            if (isset($msg[$rows['msg_time']]['tablename'])) {
                $table_name = $msg[$rows['msg_time']]['tablename'];

                if ($table_name === 'feedback') {
                    $reply = DB::table('feedback')
                        ->select('user_name as re_name', 'user_email as re_email', 'msg_time as re_time', 'msg_content as re_content', 'parent_id')
                        ->where('parent_id', $id)
                        ->first();
                } else {
                    $reply = DB::table('comment')
                        ->select('user_name as re_name', 'email as re_email', 'add_time as re_time', 'content as re_content', 'parent_id')
                        ->where('parent_id', $id)
                        ->first();
                }
                $reply = (array) $reply;
                if ($reply) {
                    $msg[$rows['msg_time']]['re_name'] = $reply['re_name'];
                    $msg[$rows['msg_time']]['re_email'] = $reply['re_email'];
                    $msg[$rows['msg_time']]['re_time'] = TimeHelper::local_date(cfg('time_format'), $reply['re_time']);
                    $msg[$rows['msg_time']]['re_content'] = nl2br(htmlspecialchars($reply['re_content']));
                }
            }
        }

        return $msg;
    }
}
