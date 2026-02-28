<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Helpers\BaseHelper;
use Illuminate\Http\Request;

class CaptchaManageController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        $this->admin_priv('shop_config');

        /**
         * 验证码设置
         */
        if ($action === 'main') {
            if (BaseHelper::gd_version() === 0) {
                return $this->sys_msg(lang('captcha_note'), 1);
            }

            $captcha = intval(cfg('captcha'));

            $captcha_check = [];
            if ($captcha & CAPTCHA_REGISTER) {
                $captcha_check['register'] = 'checked="checked"';
            }
            if ($captcha & CAPTCHA_LOGIN) {
                $captcha_check['login'] = 'checked="checked"';
            }
            if ($captcha & CAPTCHA_COMMENT) {
                $captcha_check['comment'] = 'checked="checked"';
            }
            if ($captcha & CAPTCHA_ADMIN) {
                $captcha_check['admin'] = 'checked="checked"';
            }
            if ($captcha & CAPTCHA_MESSAGE) {
                $captcha_check['message'] = 'checked="checked"';
            }
            if ($captcha & CAPTCHA_LOGIN_FAIL) {
                $captcha_check['login_fail_yes'] = 'checked="checked"';
            } else {
                $captcha_check['login_fail_no'] = 'checked="checked"';
            }

            $this->assign('captcha', $captcha_check);
            $this->assign('captcha_width', cfg('captcha_width'));
            $this->assign('captcha_height', cfg('captcha_height'));
            $this->assign('ur_here', lang('captcha_manage'));

            return $this->display('captcha_manage');
        }

        /**
         * 保存设置
         */
        if ($action === 'save_config') {
            $captcha = 0;
            $captcha = empty($_POST['captcha_register']) ? $captcha : $captcha | CAPTCHA_REGISTER;
            $captcha = empty($_POST['captcha_login']) ? $captcha : $captcha | CAPTCHA_LOGIN;
            $captcha = empty($_POST['captcha_comment']) ? $captcha : $captcha | CAPTCHA_COMMENT;
            $captcha = empty($_POST['captcha_tag']) ? $captcha : $captcha | CAPTCHA_TAG;
            $captcha = empty($_POST['captcha_admin']) ? $captcha : $captcha | CAPTCHA_ADMIN;
            $captcha = empty($_POST['captcha_login_fail']) ? $captcha : $captcha | CAPTCHA_LOGIN_FAIL;
            $captcha = empty($_POST['captcha_message']) ? $captcha : $captcha | CAPTCHA_MESSAGE;

            $captcha_width = empty($_POST['captcha_width']) ? 145 : intval($_POST['captcha_width']);
            $captcha_height = empty($_POST['captcha_height']) ? 20 : intval($_POST['captcha_height']);

            DB::table('shop_config')->where('code', 'captcha')->update(['value' => $captcha]);
            DB::table('shop_config')->where('code', 'captcha_width')->update(['value' => $captcha_width]);
            DB::table('shop_config')->where('code', 'captcha_height')->update(['value' => $captcha_height]);

            $this->clear_cache_files();

            return $this->sys_msg(lang('save_ok'), 0, [['href' => 'captcha_manage.php?act=main', 'text' => lang('captcha_manage')]]);
        }
    }
}
