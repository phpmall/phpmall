<?php

declare(strict_types=1);

namespace App\Modules\User\Controllers;

use App\Helpers\BaseHelper;
use App\Helpers\MainHelper;
use App\Helpers\PassportHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        if ($action === 'register') {
            if ((! isset($back_act) || empty($back_act)) && isset($GLOBALS['_SERVER']['HTTP_REFERER'])) {
                $back_act = strpos($GLOBALS['_SERVER']['HTTP_REFERER'], 'user.php') ? './index.php' : $GLOBALS['_SERVER']['HTTP_REFERER'];
            }

            // 取出注册扩展字段
            $extend_info_list = DB::table('user_extend_fields')
                ->where('type', '<', 2)
                ->where('display', 1)
                ->orderBy('dis_order')
                ->orderBy('id')
                ->get()
                ->map(fn ($item) => (array) $item)
                ->all();
            $this->assign('extend_info_list', $extend_info_list);

            // 验证码相关设置
            if ((intval(cfg('captcha')) & CAPTCHA_REGISTER) && BaseHelper::gd_version() > 0) {
                $this->assign('enabled_captcha', 1);
                $this->assign('rand', mt_rand());
            }

            // 密码提示问题
            $this->assign('passwd_questions', lang('passwd_questions'));

            // 增加是否关闭注册
            $this->assign('shop_reg_closed', cfg('shop_reg_closed'));

            //    $this->assign('back_act', $back_act);
            return $this->display('user_passport');
        }

        // 注册会员的处理
        if ($action === 'act_register') {
            // 增加是否关闭注册
            if (cfg('shop_reg_closed')) {
                $this->assign('action', 'register');
                $this->assign('shop_reg_closed', cfg('shop_reg_closed'));

                return $this->display('user_passport');
            } else {
                $username = isset($_POST['username']) ? trim($_POST['username']) : '';
                $password = isset($_POST['password']) ? trim($_POST['password']) : '';
                $email = isset($_POST['email']) ? trim($_POST['email']) : '';
                $other['msn'] = isset($_POST['extend_field1']) ? $_POST['extend_field1'] : '';
                $other['qq'] = isset($_POST['extend_field2']) ? $_POST['extend_field2'] : '';
                $other['office_phone'] = isset($_POST['extend_field3']) ? $_POST['extend_field3'] : '';
                $other['home_phone'] = isset($_POST['extend_field4']) ? $_POST['extend_field4'] : '';
                $other['mobile_phone'] = isset($_POST['extend_field5']) ? $_POST['extend_field5'] : '';
                $sel_question = empty($_POST['sel_question']) ? '' : BaseHelper::compile_str($_POST['sel_question']);
                $passwd_answer = isset($_POST['passwd_answer']) ? BaseHelper::compile_str(trim($_POST['passwd_answer'])) : '';

                $back_act = isset($_POST['back_act']) ? trim($_POST['back_act']) : '';

                if (empty($_POST['agreement'])) {
                    $this->show_message(lang('passport_js.agreement'));
                }
                if (strlen($username) < 3) {
                    $this->show_message(lang('passport_js.username_shorter'));
                }

                if (strlen($password) < 6) {
                    $this->show_message(lang('passport_js.password_shorter'));
                }

                if (strpos($password, ' ') > 0) {
                    $this->show_message(lang('passwd_balnk'));
                }

                // 验证码检查
                if ((intval(cfg('captcha')) & CAPTCHA_REGISTER) && BaseHelper::gd_version() > 0) {
                    if (empty($_POST['captcha'])) {
                        $this->show_message(lang('invalid_captcha'), lang('sign_up'), 'user.php?act=register', 'error');
                    }

                    // 检查验证码

                    $validator = new captcha;
                    if (! $validator->check_word($_POST['captcha'])) {
                        $this->show_message(lang('invalid_captcha'), lang('sign_up'), 'user.php?act=register', 'error');
                    }
                }

                if (PassportHelper::register($username, $password, $email, $other) !== false) {
                    // 把新注册用户的扩展信息插入数据库
                    $fields_arr = DB::table('user_extend_fields')
                        ->where('type', 0)
                        ->where('display', 1)
                        ->orderBy('dis_order')
                        ->orderBy('id')
                        ->pluck('id')
                        ->toArray();

                    foreach ($fields_arr as $val_id) {
                        $extend_field_index = 'extend_field'.$val_id;
                        if (! empty($_POST[$extend_field_index])) {
                            $temp_field_content = strlen($_POST[$extend_field_index]) > 100 ? mb_substr($_POST[$extend_field_index], 0, 99) : $_POST[$extend_field_index];
                            DB::table('user_extend_info')->insert([
                                'user_id' => Session::get('user_id'),
                                'reg_field_id' => $val_id,
                                'content' => BaseHelper::compile_str($temp_field_content),
                            ]);
                        }
                    }

                    // 写入密码提示问题和答案
                    if (! empty($passwd_answer) && ! empty($sel_question)) {
                        DB::table('users')
                            ->where('user_id', Session::get('user_id'))
                            ->update([
                                'passwd_question' => $sel_question,
                                'passwd_answer' => $passwd_answer,
                            ]);
                    }
                    // 判断是否需要自动发送注册邮件
                    if (cfg('member_email_validate') && cfg('send_verify_email')) {
                        PassportHelper::send_regiter_hash(Session::get('user_id'));
                    }
                    $ucdata = empty($user->ucdata) ? '' : $user->ucdata;
                    $this->show_message(sprintf(lang('register_success'), $username.$ucdata), [lang('back_up_page'), lang('profile_lnk')], [$back_act, 'user.php'], 'info');
                } else {
                    $err->show(lang('sign_up'), 'user.php?act=register');
                }
            }
        }

        // 验证用户注册邮件
        if ($action === 'validate_email') {
            $hash = empty($_GET['hash']) ? '' : trim($_GET['hash']);
            if ($hash) {
                $id = PassportHelper::register_hash('decode', $hash);
                if ($id > 0) {
                    DB::table('users')->where('user_id', $id)->update(['is_validated' => 1]);
                    $row = DB::table('users')
                        ->select('user_name', 'email')
                        ->where('user_id', $id)
                        ->first();
                    $row = (array) $row;
                    $this->show_message(sprintf(lang('validate_ok'), $row['user_name'], $row['email']), lang('profile_lnk'), 'user.php');
                }
            }
            $this->show_message(lang('validate_fail'));
        }

        // 验证用户注册用户名是否可以注册
        if ($action === 'is_registered') {
            $username = trim($_GET['username']);
            $username = BaseHelper::json_str_iconv($username);

            if ($user->check_user($username) || PassportHelper::admin_registered($username)) {
                echo 'false';
            } else {
                echo 'true';
            }
        }
        // 验证用户邮箱地址是否被注册
        if ($action === 'check_email') {
            $email = trim($_GET['email']);
            if ($user->check_email($email)) {
                echo 'false';
            } else {
                echo 'ok';
            }
        }

        // 用户登录界面
        if ($action === 'login') {
            if (empty($back_act)) {
                if (empty($back_act) && isset($GLOBALS['_SERVER']['HTTP_REFERER'])) {
                    $back_act = strpos($GLOBALS['_SERVER']['HTTP_REFERER'], 'user.php') ? './index.php' : $GLOBALS['_SERVER']['HTTP_REFERER'];
                } else {
                    $back_act = 'user.php';
                }
            }

            $captcha = intval(cfg('captcha'));
            if (($captcha & CAPTCHA_LOGIN) && (! ($captcha & CAPTCHA_LOGIN_FAIL) || (($captcha & CAPTCHA_LOGIN_FAIL) && Session::get('login_fail', 0) > 2)) && BaseHelper::gd_version() > 0) {
                $this->assign('enabled_captcha', 1);
                $this->assign('rand', mt_rand());
            }

            $this->assign('back_act', $back_act);

            return $this->display('user_passport');
        }

        // 处理会员的登录
        if ($action === 'act_login') {
            $username = isset($_POST['username']) ? trim($_POST['username']) : '';
            $password = isset($_POST['password']) ? trim($_POST['password']) : '';
            $back_act = isset($_POST['back_act']) ? trim($_POST['back_act']) : '';

            $captcha = intval(cfg('captcha'));
            if (($captcha & CAPTCHA_LOGIN) && (! ($captcha & CAPTCHA_LOGIN_FAIL) || (($captcha & CAPTCHA_LOGIN_FAIL) && Session::get('login_fail', 0) > 2)) && BaseHelper::gd_version() > 0) {
                if (empty($_POST['captcha'])) {
                    $this->show_message(lang('invalid_captcha'), lang('relogin_lnk'), 'user.php', 'error');
                }

                // 检查验证码

                $validator = new captcha;
                $validator->session_word = 'captcha_login';
                if (! $validator->check_word($_POST['captcha'])) {
                    $this->show_message(lang('invalid_captcha'), lang('relogin_lnk'), 'user.php', 'error');
                }
            }

            if ($user->login($username, $password, isset($_POST['remember']))) {
                MainHelper::update_user_info();
                MainHelper::recalculate_price();

                $ucdata = isset($user->ucdata) ? $user->ucdata : '';
                $this->show_message(lang('login_success').$ucdata, [lang('back_up_page'), lang('profile_lnk')], [$back_act, 'user.php'], 'info');
            } else {
                Session::increment('login_fail');
                $this->show_message(lang('login_failure'), lang('relogin_lnk'), 'user.php', 'error');
            }
        }

        // 处理 ajax 的登录请求
        if ($action === 'signin') {
            $username = ! empty($_POST['username']) ? BaseHelper::json_str_iconv(trim($_POST['username'])) : '';
            $password = ! empty($_POST['password']) ? trim($_POST['password']) : '';
            $captcha = ! empty($_POST['captcha']) ? BaseHelper::json_str_iconv(trim($_POST['captcha'])) : '';
            $result = ['error' => 0, 'content' => ''];

            $captcha = intval(cfg('captcha'));
            if (($captcha & CAPTCHA_LOGIN) && (! ($captcha & CAPTCHA_LOGIN_FAIL) || (($captcha & CAPTCHA_LOGIN_FAIL) && Session::get('login_fail', 0) > 2)) && BaseHelper::gd_version() > 0) {
                if (empty($captcha)) {
                    $result['error'] = 1;
                    $result['content'] = lang('invalid_captcha');

                    return response()->json($result);
                }

                // 检查验证码

                $validator = new captcha;
                $validator->session_word = 'captcha_login';
                if (! $validator->check_word($_POST['captcha'])) {
                    $result['error'] = 1;
                    $result['content'] = lang('invalid_captcha');

                    return response()->json($result);
                }
            }

            if ($user->login($username, $password)) {
                MainHelper::update_user_info();  // 更新用户信息
                MainHelper::recalculate_price(); // 重新计算购物车中的商品价格
                $this->assign('user_info', MainHelper::get_user_info());
                $ucdata = empty($user->ucdata) ? '' : $user->ucdata;
                $result['ucdata'] = $ucdata;
                $result['content'] = $this->fetch('web::library/member_info');
            } else {
                Session::increment('login_fail');
                if (Session::get('login_fail') > 2) {
                    $this->assign('enabled_captcha', 1);
                    $result['html'] = $this->fetch('web::library/member_info');
                }
                $result['error'] = 1;
                $result['content'] = lang('login_failure');
            }

            return response()->json($result);
        }

        // 密码找回-->修改密码界面
        if ($action === 'get_password') {
            if (isset($_GET['code']) && isset($_GET['uid'])) { // 从邮件处获得的act
                $code = trim($_GET['code']);
                $uid = intval($_GET['uid']);

                // 判断链接的合法性
                $user_info = $user->get_profile_by_id($uid);
                if (empty($user_info) || ($user_info && md5($user_info['user_id'].cfg('hash_code').$user_info['reg_time']) != $code)) {
                    $this->show_message(lang('parm_error'), lang('back_home_lnk'), './', 'info');
                }

                $this->assign('uid', $uid);
                $this->assign('code', $code);
                $this->assign('action', 'reset_password');

                return $this->display('user_passport');
            } else {
                // 显示用户名和email表单
                return $this->display('user_passport');
            }
        }

        // 密码找回-->输入用户名界面
        if ($action === 'qpassword_name') {
            // 显示输入要找回密码的账号表单
            return $this->display('user_passport');
        }
        // 密码找回-->根据注册用户名取得密码提示问题界面
        if ($action === 'get_passwd_question') {
            if (empty($_POST['user_name'])) {
                $this->show_message(lang('no_passwd_question'), lang('back_home_lnk'), './', 'info');
            } else {
                $user_name = trim($_POST['user_name']);
            }

            // 取出会员密码问题和答案
            $user_question_arr = DB::table('users')
                ->select('user_id', 'user_name', 'passwd_question', 'passwd_answer')
                ->where('user_name', $user_name)
                ->first();
            $user_question_arr = (array) $user_question_arr;

            // 如果没有设置密码问题，给出错误提示
            if (empty($user_question_arr['passwd_answer'])) {
                $this->show_message(lang('no_passwd_question'), lang('back_home_lnk'), './', 'info');
            }

            Session::put('temp_user', $user_question_arr['user_id']);  // 设置临时用户，不具有有效身份
            Session::put('temp_user_name', $user_question_arr['user_name']);  // 设置临时用户，不具有有效身份
            Session::put('passwd_answer', $user_question_arr['passwd_answer']);   // 存储密码问题答案，减少一次数据库访问

            $captcha = intval(cfg('captcha'));
            if (($captcha & CAPTCHA_LOGIN) && (! ($captcha & CAPTCHA_LOGIN_FAIL) || (($captcha & CAPTCHA_LOGIN_FAIL) && Session::get('login_fail') > 2)) && BaseHelper::gd_version() > 0) {
                $this->assign('enabled_captcha', 1);
                $this->assign('rand', mt_rand());
            }

            $this->assign('passwd_question', lang('passwd_questions')[$user_question_arr['passwd_question']]);

            return $this->display('user_passport');
        }
        // 密码找回-->根据提交的密码答案进行相应处理
        if ($action === 'check_answer') {
            $captcha = intval(cfg('captcha'));
            if (($captcha & CAPTCHA_LOGIN) && (! ($captcha & CAPTCHA_LOGIN_FAIL) || (($captcha & CAPTCHA_LOGIN_FAIL) && Session::get('login_fail') > 2)) && BaseHelper::gd_version() > 0) {
                if (empty($_POST['captcha'])) {
                    $this->show_message(lang('invalid_captcha'), lang('back_retry_answer'), 'user.php?act=qpassword_name', 'error');
                }

                // 检查验证码

                $validator = new captcha;
                $validator->session_word = 'captcha_login';
                if (! $validator->check_word($_POST['captcha'])) {
                    $this->show_message(lang('invalid_captcha'), lang('back_retry_answer'), 'user.php?act=qpassword_name', 'error');
                }
            }

            if (empty($_POST['passwd_answer']) || $_POST['passwd_answer'] != Session::get('passwd_answer')) {
                $this->show_message(lang('wrong_passwd_answer'), lang('back_retry_answer'), 'user.php?act=qpassword_name', 'info');
            } else {
                Session::put('user_id', Session::get('temp_user'));
                Session::put('user_name', Session::get('temp_user_name'));
                Session::forget(['temp_user', 'temp_user_name']);
                $this->assign('uid', Session::get('user_id'));
                $this->assign('action', 'reset_password');

                return $this->display('user_passport');
            }
        }

        // 发送密码修改确认邮件
        if ($action === 'send_pwd_email') {
            // 初始化会员用户名和邮件地址
            $user_name = ! empty($_POST['user_name']) ? trim($_POST['user_name']) : '';
            $email = ! empty($_POST['email']) ? trim($_POST['email']) : '';

            // 用户名和邮件地址是否匹配
            $user_info = $user->get_user_info($user_name);

            if ($user_info && $user_info['email'] === $email) {
                // 生成code
                // $code = md5($user_info[0] . $user_info[1]);

                $code = md5($user_info['user_id'].cfg('hash_code').$user_info['reg_time']);
                // 发送邮件的函数
                if (PassportHelper::send_pwd_email($user_info['user_id'], $user_name, $email, $code)) {
                    $this->show_message(lang('send_success').$email, lang('back_home_lnk'), './', 'info');
                } else {
                    // 发送邮件出错
                    $this->show_message(lang('fail_send_password'), lang('back_page_up'), './', 'info');
                }
            } else {
                // 用户名与邮件地址不匹配
                $this->show_message(lang('username_no_email'), lang('back_page_up'), '', 'info');
            }
        }
        // 重置新密码
        if ($action === 'reset_password') {
            // 显示重置密码的表单
            return $this->display('user_passport');
        }
        // 修改会员密码
        if ($action === 'act_edit_password') {
            $old_password = isset($_POST['old_password']) ? trim($_POST['old_password']) : null;
            $new_password = isset($_POST['new_password']) ? trim($_POST['new_password']) : '';
            $this->getUserId() = isset($_POST['uid']) ? intval($_POST['uid']) : $this->getUserId();
            $code = isset($_POST['code']) ? trim($_POST['code']) : '';

            if (strlen($new_password) < 6) {
                $this->show_message(lang('passport_js.password_shorter'));
            }

            $user_info = $user->get_profile_by_id($this->getUserId()); // 论坛记录

            if (($user_info && (! empty($code) && md5($user_info['user_id'].cfg('hash_code').$user_info['reg_time']) === $code)) || (Session::get('user_id') > 0 && Session::get('user_id') === $this->getUserId() && $user->check_user(Session::get('user_name'), $old_password))) {
                if ($user->edit_user(['username' => (empty($code) ? Session::get('user_name') : $user_info['user_name']), 'old_password' => $old_password, 'password' => $new_password], empty($code) ? 0 : 1)) {
                    DB::table('users')->where('user_id', $this->getUserId())->update(['ec_salt' => 0]);
                    $user->logout();
                    $this->show_message(lang('edit_password_success'), lang('relogin_lnk'), 'user.php?act=login', 'info');
                } else {
                    $this->show_message(lang('edit_password_failure'), lang('back_page_up'), '', 'info');
                }
            } else {
                $this->show_message(lang('edit_password_failure'), lang('back_page_up'), '', 'info');
            }
        }
    }
}
