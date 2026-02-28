<?php

declare(strict_types=1);

namespace App\Modules\Web\Controllers;

use App\Helpers\BaseHelper;
use App\Helpers\CommonHelper;
use App\Helpers\MainHelper;
use App\Helpers\TimeHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CommentController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        if (! isset($_REQUEST['cmt']) && ! isset($_REQUEST['act'])) {
            // 只有在没有提交评论内容以及没有act的情况下才跳转
            return response()->redirectTo('/');
        }
        $_REQUEST['cmt'] = isset($_REQUEST['cmt']) ? BaseHelper::json_str_iconv($_REQUEST['cmt']) : '';

        $result = ['error' => 0, 'message' => '', 'content' => ''];

        if (empty($_REQUEST['act'])) {
            /*
             * act 参数为空
             * 默认为添加评论内容
             */
            $cmt = json_decode($_REQUEST['cmt']);
            $cmt->page = 1;
            $cmt->id = ! empty($cmt->id) ? intval($cmt->id) : 0;
            $cmt->type = ! empty($cmt->type) ? intval($cmt->type) : 0;

            if (empty($cmt) || ! isset($cmt->type) || ! isset($cmt->id)) {
                $result['error'] = 1;
                $result['message'] = lang('invalid_comments');
            } elseif (! CommonHelper::is_email($cmt->email)) {
                $result['error'] = 1;
                $result['message'] = lang('error_email');
            } else {
                if ((intval(cfg('captcha')) & CAPTCHA_COMMENT) && BaseHelper::gd_version() > 0) {
                    // 检查验证码

                    $validator = new captcha;
                    if (! $validator->check_word($cmt->captcha)) {
                        $result['error'] = 1;
                        $result['message'] = lang('invalid_captcha');
                    } else {
                        $factor = intval(cfg('comment_factor'));
                        if ($cmt->type === 0 && $factor > 0) {
                            // 只有商品才检查评论条件
                            switch ($factor) {
                                case COMMENT_LOGIN:
                                    if (Session::get('user_id', 0) === 0) {
                                        $result['error'] = 1;
                                        $result['message'] = lang('comment_login');
                                    }
                                    break;

                                case COMMENT_CUSTOM:
                                    $user_id = Session::get('user_id', 0);
                                    if ($user_id > 0) {
                                        $exists = DB::table('order_info')
                                            ->where('user_id', $user_id)
                                            ->whereIn('order_status', [OS_CONFIRMED, OS_SPLITED])
                                            ->whereIn('pay_status', [PS_PAYED, PS_PAYING])
                                            ->whereIn('shipping_status', [SS_SHIPPED, SS_RECEIVED])
                                            ->exists();

                                        if (! $exists) {
                                            $result['error'] = 1;
                                            $result['message'] = lang('comment_custom');
                                        }
                                    } else {
                                        $result['error'] = 1;
                                        $result['message'] = lang('comment_custom');
                                    }
                                    break;
                                case COMMENT_BOUGHT:
                                    $user_id = Session::get('user_id', 0);
                                    if ($user_id > 0) {
                                        $exists = DB::table('order_info as o')
                                            ->join('order_goods as og', 'o.order_id', '=', 'og.order_id')
                                            ->where('o.user_id', $user_id)
                                            ->where('og.goods_id', $cmt->id)
                                            ->whereIn('o.order_status', [OS_CONFIRMED, OS_SPLITED])
                                            ->whereIn('o.pay_status', [PS_PAYED, PS_PAYING])
                                            ->whereIn('o.shipping_status', [SS_SHIPPED, SS_RECEIVED])
                                            ->exists();

                                        if (! $exists) {
                                            $result['error'] = 1;
                                            $result['message'] = lang('comment_brought');
                                        }
                                    } else {
                                        $result['error'] = 1;
                                        $result['message'] = lang('comment_brought');
                                    }
                                    break;
                            }
                        }

                        // 无错误就保存留言
                        if (empty($result['error'])) {
                            $this->add_comment($cmt);
                        }
                    }
                } else {
                    // 没有验证码时，用时间来限制机器人发帖或恶意发评论
                    if (! Session::has('send_time')) {
                        Session::put('send_time', 0);
                    }

                    $cur_time = TimeHelper::gmtime();
                    if (($cur_time - Session::get('send_time', 0)) < 30) { // 小于30秒禁止发评论
                        $result['error'] = 1;
                        $result['message'] = lang('cmt_spam_warning');
                    } else {
                        $factor = intval(cfg('comment_factor'));
                        if ($cmt->type === 0 && $factor > 0) {
                            // 只有商品才检查评论条件
                            switch ($factor) {
                                case COMMENT_LOGIN:
                                    if (Session::get('user_id', 0) === 0) {
                                        $result['error'] = 1;
                                        $result['message'] = lang('comment_login');
                                    }
                                    break;

                                case COMMENT_CUSTOM:
                                    $user_id = Session::get('user_id', 0);
                                    if ($user_id > 0) {
                                        $exists = DB::table('order_info')
                                            ->where('user_id', $user_id)
                                            ->whereIn('order_status', [OS_CONFIRMED, OS_SPLITED])
                                            ->whereIn('pay_status', [PS_PAYED, PS_PAYING])
                                            ->whereIn('shipping_status', [SS_SHIPPED, SS_RECEIVED])
                                            ->exists();

                                        if (! $exists) {
                                            $result['error'] = 1;
                                            $result['message'] = lang('comment_custom');
                                        }
                                    } else {
                                        $result['error'] = 1;
                                        $result['message'] = lang('comment_custom');
                                    }
                                    break;

                                case COMMENT_BOUGHT:
                                    $user_id = Session::get('user_id', 0);
                                    if ($user_id > 0) {
                                        $exists = DB::table('order_info as o')
                                            ->join('order_goods as og', 'o.order_id', '=', 'og.order_id')
                                            ->where('o.user_id', $user_id)
                                            ->where('og.goods_id', $cmt->id)
                                            ->whereIn('o.order_status', [OS_CONFIRMED, OS_SPLITED])
                                            ->whereIn('o.pay_status', [PS_PAYED, PS_PAYING])
                                            ->whereIn('o.shipping_status', [SS_SHIPPED, SS_RECEIVED])
                                            ->exists();

                                        if (! $exists) {
                                            $result['error'] = 1;
                                            $result['message'] = lang('comment_brought');
                                        }
                                    } else {
                                        $result['error'] = 1;
                                        $result['message'] = lang('comment_brought');
                                    }
                                    break;
                            }
                        }
                        // 无错误就保存留言
                        if (empty($result['error'])) {
                            $this->add_comment($cmt);
                            Session::put('send_time', $cur_time);
                        }
                    }
                }
            }
        } else {
            /*
             * act 参数不为空
             * 默认为评论内容列表
             * 根据 _GET 创建一个静态对象
             */
            $cmt = new \stdClass;
            $cmt->id = ! empty($_GET['id']) ? intval($_GET['id']) : 0;
            $cmt->type = ! empty($_GET['type']) ? intval($_GET['type']) : 0;
            $cmt->page = isset($_GET['page']) && intval($_GET['page']) > 0 ? intval($_GET['page']) : 1;
        }

        if ($result['error'] === 0) {
            $comments = MainHelper::assign_comment($cmt->id, $cmt->type, $cmt->page);

            $this->assign('comment_type', $cmt->type);
            $this->assign('id', $cmt->id);
            $this->assign('username', Session::get('user_name'));
            $this->assign('email', Session::get('email'));
            $this->assign('comments', $comments['comments']);
            $this->assign('pager', $comments['pager']);

            // 验证码相关设置
            if ((intval(cfg('captcha')) & CAPTCHA_COMMENT) && BaseHelper::gd_version() > 0) {
                $this->assign('enabled_captcha', 1);
                $this->assign('rand', mt_rand());
            }

            $result['message'] = cfg('comment_check') ? lang('cmt_submit_wait') : lang('cmt_submit_done');
            $result['content'] = $this->fetch('web::library/comments_list');
        }

        return response()->json($result);
    }

    /**
     * 添加评论内容
     *
     * @param  object  $cmt
     */
    private function add_comment($cmt): bool
    {
        // 评论是否需要审核
        $status = 1 - cfg('comment_check');

        $user_id = Session::get('user_id', 0);
        $email = empty($cmt->email) ? Session::get('email') : trim((string) $cmt->email);
        $user_name = empty($cmt->username) ? Session::get('user_name', '') : '';
        $email = htmlspecialchars((string) $email);
        $user_name = htmlspecialchars((string) $user_name);

        // 保存评论内容
        $result = DB::table('comment')->insert([
            'comment_type' => $cmt->type,
            'id_value' => $cmt->id,
            'email' => $email,
            'user_name' => $user_name,
            'content' => $cmt->content,
            'comment_rank' => $cmt->rank,
            'add_time' => TimeHelper::gmtime(),
            'ip_address' => BaseHelper::real_ip(),
            'status' => $status,
            'parent_id' => 0,
            'user_id' => $user_id,
        ]);
        $this->clear_cache_files('comments_list');

        /*if ($status > 0)
        {
            // add_feed(db()->insert_id(), COMMENT_GOODS); @deprecated
        }*/
        return $result;
    }
}
