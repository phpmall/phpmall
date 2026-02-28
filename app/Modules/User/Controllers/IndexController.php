<?php

declare(strict_types=1);

namespace App\Modules\User\Controllers;

use App\Helpers\BaseHelper;
use App\Helpers\ClipsHelper;
use App\Helpers\CommonHelper;
use App\Helpers\MainHelper;
use App\Helpers\PassportHelper;
use App\Helpers\TimeHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class IndexController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        // 载入语言文件
        require_once ROOT_PATH.'languages/'.cfg('lang').'/user.php';

        $action = isset($_REQUEST['act']) ? trim($_REQUEST['act']) : 'default';

        $affiliate = unserialize(cfg('affiliate'));
        $this->assign('affiliate', $affiliate);
        $back_act = '';

        // 不需要登录的操作或自己验证是否登录（如ajax处理）的act
        $not_login_arr =
            ['login', 'act_login', 'register', 'act_register', 'act_edit_password', 'get_password', 'send_pwd_email', 'password', 'signin', 'add_tag', 'collect', 'return_to_cart', 'logout', 'email_list', 'validate_email', 'send_hash_mail', 'order_query', 'is_registered', 'check_email', 'clear_history', 'qpassword_name', 'get_passwd_question', 'check_answer'];

        $ui_arr = [
            'register',
            'login',
            'profile',
            'order_list',
            'order_detail',
            'address_list',
            'collection_list',
            'message_list',
            'tag_list',
            'get_password',
            'reset_password',
            'booking_list',
            'add_booking',
            'account_raply',
            'account_deposit',
            'account_log',
            'account_detail',
            'act_account',
            'pay',
            'default',
            'bonus',
            'group_buy',
            'group_buy_detail',
            'affiliate',
            'comment_list',
            'validate_email',
            'track_packages',
            'transform_points',
            'qpassword_name',
            'get_passwd_question',
            'check_answer',
        ];

        // 未登录处理
        if (empty(Session::get('user_id'))) {
            if (! in_array($action, $not_login_arr)) {
                if (in_array($action, $ui_arr)) {
                    /* 如果需要登录,并是显示页面的操作，记录当前操作，用于登录后跳转到相应操作
                    if ($action === 'login')
                    {
                        if (isset($_REQUEST['back_act']))
                        {
                            $back_act = trim($_REQUEST['back_act']);
                        }
                    }
                    else
                    {}*/
                    if (! empty($_SERVER['QUERY_STRING'])) {
                        $back_act = 'user.php?'.strip_tags($_SERVER['QUERY_STRING']);
                    }
                    $action = 'login';
                } else {
                    // 未登录提交数据。非正常途径提交数据！
                    exit(lang('require_login'));
                }
            }
        }

        // 如果是显示页面，对页面进行相应赋值
        if (in_array($action, $ui_arr)) {
            $this->assign_template();
            $position = $this->assign_ur_here(0, lang('user_center'));
            $this->assign('page_title', $position['title']); // 页面标题
            $this->assign('ur_here', $position['ur_here']);
            $car_off = DB::table('shop_config')->where('id', 419)->value('value');
            $this->assign('car_off', $car_off);
            // 是否显示积分兑换
            if (! empty(cfg('points_rule')) && unserialize(cfg('points_rule'))) {
                $this->assign('show_transform_points', 1);
            }
            $this->assign('helps', MainHelper::get_shop_help());        // 网店帮助
            $this->assign('data_dir', DATA_DIR);   // 数据目录
            $this->assign('action', $action);

        }

        // 用户中心欢迎页
        if ($action === 'default') {
            if ($rank = ClipsHelper::get_rank_info()) {
                $this->assign('rank_name', sprintf(lang('your_level'), $rank['rank_name']));
                if (! empty($rank['next_rank_name'])) {
                    $this->assign('next_rank_name', sprintf(lang('next_level'), $rank['next_rank'], $rank['next_rank_name']));
                }
            }
            $this->assign('info', ClipsHelper::get_user_default($this->getUserId()));
            $this->assign('user_notice', cfg('user_notice'));
            $this->assign('prompt', ClipsHelper::get_user_prompt($this->getUserId()));

            return $this->display('user_clips');
        }

        // 退出会员中心
        if ($action === 'logout') {
            if ((! isset($back_act) || empty($back_act)) && isset($GLOBALS['_SERVER']['HTTP_REFERER'])) {
                $back_act = strpos($GLOBALS['_SERVER']['HTTP_REFERER'], 'user.php') ? './index.php' : $GLOBALS['_SERVER']['HTTP_REFERER'];
            }

            $user->logout();
            $ucdata = empty($user->ucdata) ? '' : $user->ucdata;
            $this->show_message(lang('logout').$ucdata, [lang('back_up_page'), lang('back_home_lnk')], [$back_act, 'index.php'], 'info');
        }

        // 我的团购列表
        if ($action === 'group_buy') {
            // 待议
            return $this->display('user_transaction');
        }

        // 团购订单详情
        if ($action === 'group_buy_detail') {
            // 待议
            return $this->display('user_transaction');
        }

        // 首页邮件订阅ajax操做和验证操作
        if ($action === 'email_list') {
            $job = $_GET['job'];

            if ($job === 'add' || $job === 'del') {
                if (Session::has('last_email_query')) {
                    if (time() - Session::get('last_email_query') <= 30) {
                        exit(lang('order_query_toofast'));
                    }
                }
                Session::put('last_email_query', time());
            }

            $email = trim($_GET['email']);
            $email = htmlspecialchars($email);

            if (! CommonHelper::is_email($email)) {
                $info = sprintf(lang('email_invalid'), $email);
                exit($info);
            }
            $ck = DB::table('email_subscriber')->where('email', $email)->first();
            if ($ck) {
                $ck = (array) $ck;
            }
            if ($job === 'add') {
                if (empty($ck)) {
                    $hash = substr(md5((string) time()), 1, 10);
                    DB::table('email_subscriber')->insert([
                        'email' => $email,
                        'stat' => 0,
                        'hash' => $hash,
                    ]);
                    $info = lang('email_check');
                    $url = ecs()->url()."user.php?act=email_list&job=add_check&hash=$hash&email=$email";
                    BaseHelper::send_mail('', $email, lang('check_mail'), sprintf(lang('check_mail_content'), $email, cfg('shop_name'), $url, $url, cfg('shop_name'), TimeHelper::local_date('Y-m-d')), 1);
                } elseif ($ck['stat'] === 1) {
                    $info = sprintf(lang('email_alreadyin_list'), $email);
                } else {
                    $hash = substr(md5((string) time()), 1, 10);
                    DB::table('email_subscriber')->where('email', $email)->update(['hash' => $hash]);
                    $info = lang('email_re_check');
                    $url = ecs()->url()."user.php?act=email_list&job=add_check&hash=$hash&email=$email";
                    BaseHelper::send_mail('', $email, lang('check_mail'), sprintf(lang('check_mail_content'), $email, cfg('shop_name'), $url, $url, cfg('shop_name'), TimeHelper::local_date('Y-m-d')), 1);
                }
                exit($info);
            } elseif ($job === 'del') {
                if (empty($ck)) {
                    $info = sprintf(lang('email_notin_list'), $email);
                } elseif ($ck['stat'] === 1) {
                    $hash = substr(md5((string) time()), 1, 10);
                    DB::table('email_subscriber')->where('email', $email)->update(['hash' => $hash]);
                    $info = lang('email_check');
                    $url = ecs()->url()."user.php?act=email_list&job=del_check&hash=$hash&email=$email";
                    BaseHelper::send_mail('', $email, lang('check_mail'), sprintf(lang('check_mail_content'), $email, cfg('shop_name'), $url, $url, cfg('shop_name'), TimeHelper::local_date('Y-m-d')), 1);
                } else {
                    $info = lang('email_not_alive');
                }
                exit($info);
            } elseif ($job === 'add_check') {
                if (empty($ck)) {
                    $info = sprintf(lang('email_notin_list'), $email);
                } elseif ($ck['stat'] === 1) {
                    $info = lang('email_checked');
                } else {
                    if ($_GET['hash'] === $ck['hash']) {
                        DB::table('email_subscriber')->where('email', $email)->update(['stat' => 1]);
                        $info = lang('email_checked');
                    } else {
                        $info = lang('hash_wrong');
                    }
                }
                $this->show_message($info, lang('back_home_lnk'), 'index.php');
            } elseif ($job === 'del_check') {
                if (empty($ck)) {
                    $info = sprintf(lang('email_invalid'), $email);
                } elseif ($ck['stat'] === 1) {
                    if ($_GET['hash'] === $ck['hash']) {
                        DB::table('email_subscriber')->where('email', $email)->delete();
                        $info = lang('email_canceled');
                    } else {
                        $info = lang('hash_wrong');
                    }
                } else {
                    $info = lang('email_not_alive');
                }
                $this->show_message($info, lang('back_home_lnk'), 'index.php');
            }
        }

        // ajax 发送验证邮件
        if ($action === 'send_hash_mail') {
            $result = ['error' => 0, 'message' => '', 'content' => ''];

            if ($this->getUserId() === 0) {
                // 用户没有登录
                $result['error'] = 1;
                $result['message'] = lang('login_please');

                return response()->json($result);
            }

            if (PassportHelper::send_regiter_hash($this->getUserId())) {
                $result['message'] = lang('validate_mail_ok');

                return response()->json($result);
            } else {
                $result['error'] = 1;
                $result['message'] = err()->last_message();
            }

            return response()->json($result);
        }

        // 清除商品浏览历史
        if ($action === 'clear_history') {
            setcookie('ECS[history]', '', 1, '', '', false, true);
        }
    }
}
