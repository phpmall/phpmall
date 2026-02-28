<?php

declare(strict_types=1);

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class PassportHelper
{
    /**
     * 用户注册，登录函数
     *
     * @param  string  $username  注册用户名
     * @param  string  $password  用户密码
     * @param  string  $email  注册email
     * @param  array  $other  注册的其他信息
     * @return bool $bool
     */
    public static function register($username, $password, $email, $other = [])
    {
        // 检查注册是否关闭
        if (! empty(cfg('shop_reg_closed'))) {
            err()->add(lang('shop_register_closed'));
        }
        // 检查username
        if (empty($username)) {
            err()->add(lang('username_empty'));
        } else {
            if (preg_match('/\'\/^\\s*$|^c:\\\\con\\\\con$|[%,\\*\\"\\s\\t\\<\\>\\&\'\\\\]/', $username)) {
                err()->add(sprintf(lang('username_invalid'), htmlspecialchars($username)));
            }
        }

        // 检查email
        if (empty($email)) {
            err()->add(lang('email_empty'));
        } else {
            if (! CommonHelper::is_email($email)) {
                err()->add(sprintf(lang('email_invalid'), htmlspecialchars($email)));
            }
        }

        if (err()->error_no > 0) {
            return false;
        }

        // 检查是否和管理员重名
        if (PassportHelper::admin_registered($username)) {
            err()->add(sprintf(lang('username_exist'), $username));

            return false;
        }

        if (! user()->add_user($username, $password, $email)) {
            if (user()->error === ERR_INVALID_USERNAME) {
                err()->add(sprintf(lang('username_invalid'), $username));
            } elseif (user()->error === ERR_USERNAME_NOT_ALLOW) {
                err()->add(sprintf(lang('username_not_allow'), $username));
            } elseif (user()->error === ERR_USERNAME_EXISTS) {
                err()->add(sprintf(lang('username_exist'), $username));
            } elseif (user()->error === ERR_INVALID_EMAIL) {
                err()->add(sprintf(lang('email_invalid'), $email));
            } elseif (user()->error === ERR_EMAIL_NOT_ALLOW) {
                err()->add(sprintf(lang('email_not_allow'), $email));
            } elseif (user()->error === ERR_EMAIL_EXISTS) {
                err()->add(sprintf(lang('email_exist'), $email));
            } else {
                err()->add('UNKNOWN ERROR!');
            }

            // 注册失败
            return false;
        } else {
            // 注册成功

            // 设置成登录状态
            user()->set_session($username);
            user()->set_cookie($username);

            // 注册送积分
            if (! empty(cfg('register_points'))) {
                CommonHelper::log_account_change(Session::get('user_id'), 0, 0, cfg('register_points'), cfg('register_points'), lang('register_points'));
            }

            // 推荐处理
            $affiliate = unserialize(cfg('affiliate'));
            if (isset($affiliate['on']) && $affiliate['on'] === 1) {
                // 推荐开关开启
                $up_uid = MainHelper::get_affiliate();
                empty($affiliate) && $affiliate = [];
                $affiliate['config']['level_register_all'] = intval($affiliate['config']['level_register_all']);
                $affiliate['config']['level_register_up'] = intval($affiliate['config']['level_register_up']);
                if ($up_uid) {
                    if (! empty($affiliate['config']['level_register_all'])) {
                        if (! empty($affiliate['config']['level_register_up'])) {
                            $rank_points = DB::table('user')->where('user_id', $up_uid)->value('rank_points');
                            if ((int) $rank_points + $affiliate['config']['level_register_all'] <= $affiliate['config']['level_register_up']) {
                                CommonHelper::log_account_change($up_uid, 0, 0, $affiliate['config']['level_register_all'], 0, sprintf(lang('register_affiliate'), Session::get('user_id'), $username));
                            }
                        } else {
                            CommonHelper::log_account_change($up_uid, 0, 0, $affiliate['config']['level_register_all'], 0, lang('register_affiliate'));
                        }
                    }

                    // 设置推荐人
                    DB::table('user')->where('user_id', Session::get('user_id'))->update(['parent_id' => $up_uid]);
                }
            }

            // 定义other合法的变量数组
            $other_key_array = ['msn', 'qq', 'office_phone', 'home_phone', 'mobile_phone'];
            $update_data['reg_time'] = TimeHelper::local_strtotime(TimeHelper::local_date('Y-m-d H:i:s'));
            if ($other) {
                foreach ($other as $key => $val) {
                    // 删除非法key值
                    if (! in_array($key, $other_key_array)) {
                        unset($other[$key]);
                    } else {
                        $other[$key] = htmlspecialchars(trim($val)); // 防止用户输入javascript代码
                    }
                }
                $update_data = array_merge($update_data, $other);
            }
            DB::table('user')->where('user_id', Session::get('user_id'))->update($update_data);

            MainHelper::update_user_info();      // 更新用户信息
            MainHelper::recalculate_price();     // 重新计算购物车中的商品价格

            return true;
        }
    }

    /**
     * @return void
     */
    public static function logout()
    {
        // todo
    }

    /**
     *  将指定user_id的密码修改为new_password。可以通过旧密码和验证字串验证修改。
     *
     * @param  int  $user_id  用户ID
     * @param  string  $new_password  用户新密码
     * @param  string  $old_password  用户旧密码
     * @param  string  $code  验证码（md5($user_id . md5($password))）
     * @return bool $bool
     */
    public static function edit_password($user_id, $old_password, $new_password = '', $code = '')
    {
        if (empty($user_id)) {
            err()->add(lang('not_login'));
        }

        if (user()->edit_password($user_id, $old_password, $new_password, $code)) {
            return true;
        } else {
            err()->add(lang('edit_password_failure'));

            return false;
        }
    }

    /**
     *  会员找回密码时，对输入的用户名和邮件地址匹配
     *
     * @param  string  $user_name  用户帐号
     * @param  string  $email  用户Email
     * @return bool
     */
    public static function check_userinfo($user_name, $email)
    {
        if (empty($user_name) || empty($email)) {
            return response()->redirectTo('user.php?act=get_password');
        }

        // 检测用户名和邮件地址是否匹配
        $user_info = user()->check_pwd_info($user_name, $email);
        if (! empty($user_info)) {
            return $user_info;
        } else {
            return false;
        }
    }

    /**
     *  用户进行密码找回操作时，发送一封确认邮件
     *
     * @param  string  $uid  用户ID
     * @param  string  $user_name  用户帐号
     * @param  string  $email  用户Email
     * @param  string  $code  key
     * @return bool $result;
     */
    public static function send_pwd_email($uid, $user_name, $email, $code)
    {
        if (empty($uid) || empty($user_name) || empty($email) || empty($code)) {
            return response()->redirectTo('user.php?act=get_password');
        }

        // 设置重置邮件模板所需要的内容信息
        $template = CommonHelper::get_mail_template('send_password');
        $reset_email = ecs()->url().'user.php?act=get_password&uid='.$uid.'&code='.$code;

        tpl()->assign('user_name', $user_name);
        tpl()->assign('reset_email', $reset_email);
        tpl()->assign('shop_name', cfg('shop_name'));
        tpl()->assign('send_date', date('Y-m-d'));
        tpl()->assign('sent_date', date('Y-m-d'));

        $content = tpl()->fetch('str:'.$template['template_content']);

        // 发送确认重置密码的确认邮件
        if (BaseHelper::send_mail($user_name, $email, $template['template_subject'], $content, $template['is_html'])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     *  发送激活验证邮件
     *
     * @param  int  $user_id  用户ID
     * @return bool
     */
    public static function send_regiter_hash($user_id)
    {
        // 设置验证邮件模板所需要的内容信息
        $template = CommonHelper::get_mail_template('register_validate');
        $hash = PassportHelper::register_hash('encode', $user_id);
        $validate_email = ecs()->url().'user.php?act=validate_email&hash='.$hash;

        $row = (array) DB::table('user')->select('user_name', 'email')->where('user_id', $user_id)->first();

        tpl()->assign('user_name', $row['user_name']);
        tpl()->assign('validate_email', $validate_email);
        tpl()->assign('shop_name', cfg('shop_name'));
        tpl()->assign('send_date', date(cfg('date_format')));

        $content = tpl()->fetch('str:'.$template['template_content']);

        // 发送激活验证邮件
        if (BaseHelper::send_mail($row['user_name'], $row['email'], $template['template_subject'], $content, $template['is_html'])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     *  生成邮件验证hash
     *
     *
     * @return void
     */
    public static function register_hash($operation, $key)
    {
        if ($operation === 'encode') {
            $user_id = intval($key);
            $reg_time = DB::table('user')->where('user_id', $user_id)->value('reg_time');

            $hash = substr(md5($user_id.cfg('hash_code').$reg_time), 16, 4);

            return base64_encode($user_id.','.$hash);
        } else {
            $hash = base64_decode(trim($key));
            $row = explode(',', $hash);
            if (count($row) != 2) {
                return 0;
            }
            $user_id = intval($row[0]);
            $salt = trim($row[1]);

            if ($user_id <= 0 || strlen($salt) != 4) {
                return 0;
            }

            $reg_time = DB::table('user')->where('user_id', $user_id)->value('reg_time');

            $pre_salt = substr(md5($user_id.cfg('hash_code').$reg_time), 16, 4);

            if ($pre_salt === $salt) {
                return $user_id;
            } else {
                return 0;
            }
        }
    }

    /**
     * 判断超级管理员用户名是否存在
     *
     * @param  string  $adminname  超级管理员用户名
     * @return bool
     */
    public static function admin_registered($adminname)
    {
        return DB::table('admin_user')->where('user_name', $adminname)->count();
    }
}
