<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Helpers\BaseHelper;
use App\Helpers\CommonHelper;
use App\Helpers\OrderHelper;
use App\Helpers\TimeHelper;
use App\Http\Controllers\Controller;
use App\Modules\Admin\AdminServiceProvider;
use App\Modules\Admin\Helpers\MainHelper;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use OpenApi\Attributes as OA;

class AuthController extends Controller
{
    public function __construct()
    {
        lang([dirname(__DIR__).'/Languages/zh-CN/privilege.php']);
    }

    #[OA\Get(path: '/login', summary: '显示登录界面', security: [['bearerAuth' => []]], tags: ['管理认证模块'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ActivityAuctionResponse::class))]
    public function showLogin(Request $request): Renderable
    {
        $enableCaptcha = 0;
        if ((intval(cfg('captcha')) & CAPTCHA_ADMIN) && BaseHelper::gd_version() > 0) {
            $enableCaptcha = 1;
        }
        $this->assign('enable_captcha', $enableCaptcha);
        $this->assign('random', mt_rand());
        $this->assign('lang', lang());

        return $this->display(AdminServiceProvider::NS.'::login');
    }

    #[OA\Post(path: '/login', summary: '验证登录信息', security: [['bearerAuth' => []]], tags: ['管理认证模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: LoginRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function login(Request $request): RedirectResponse
    {
        if (intval(cfg('captcha')) & CAPTCHA_ADMIN) {
            // 检查验证码是否正确
            $validator = new captcha;
            if (! empty($_POST['captcha']) && ! $validator->check_word($_POST['captcha'])) {
                return $this->sys_msg(lang('captcha_error'), 1);
            }
        }

        $username = isset($_POST['username']) ? trim($_POST['username']) : '';
        $password = isset($_POST['password']) ? trim($_POST['password']) : '';

        // 获取用户信息（不验证密码，先获取存储的密码哈希）
        $user = DB::table('admin_user')
            ->select('user_id', 'user_name', 'password', 'add_time', 'action_list', 'last_login', 'suppliers_id', 'ec_salt')
            ->where('user_name', $username)
            ->first();

        if (!$user) {
            return $this->sys_msg(lang('login_faild'), 1);
        }

        // 使用 Hash 验证密码（兼容旧 MD5 密码）
        $passwordValid = false;
        if (Hash::needsRehash($user->password)) {
            // 旧 MD5 密码兼容验证
            if (!empty($user->ec_salt)) {
                $passwordValid = $user->password === md5(md5($password).$user->ec_salt);
            } else {
                $passwordValid = $user->password === md5($password);
            }
            // 如果验证成功，升级密码哈希
            if ($passwordValid) {
                DB::table('admin_user')->where('user_id', $user->user_id)->update([
                    'password' => Hash::make($password)
                ]);
            }
        } else {
            $passwordValid = Hash::check($password, $user->password);
        }

        if (!$passwordValid) {
            return $this->sys_msg(lang('login_faild'), 1);
        }

        $row = (array) $user;

        if ($row) {
            // 检查是否为供货商的管理员 所属供货商是否有效
            if (! empty($row['suppliers_id'])) {
                $supplier_is_check = MainHelper::suppliers_list_info(' is_check = 1 AND suppliers_id = '.$row['suppliers_id']);
                if (empty($supplier_is_check)) {
                    return $this->sys_msg(lang('login_disable'), 1);
                }
            }

            // 登录成功
            MainHelper::set_admin_session($row['user_id'], $row['user_name'], $row['action_list'], $row['last_login']);
            Session::put('suppliers_id', $row['suppliers_id']);

            // 升级密码哈希（首次登录升级旧 MD5 密码到 bcrypt）
            if (!empty($row['ec_salt']) || strlen($row['password']) === 32) {
                DB::table('admin_user')->where('user_id', $row['user_id'])->update([
                    'password' => Hash::make($password),
                    'ec_salt' => null,
                ]);
            }

            // 更新最后登录时间和IP
            DB::table('admin_user')->where('user_id', $row['user_id'])->update([
                'last_login' => TimeHelper::gmtime(),
                'last_ip' => BaseHelper::real_ip(),
            ]);

            Auth::guard(AdminServiceProvider::NS)->loginUsingId($row['user_id'], boolval($_POST['remember']));

            // 清除购物车中过期的数据
            OrderHelper::clear_cart();

            return response()->redirectTo('index.php');
        } else {
            return $this->sys_msg(lang('login_faild'), 1);
        }
    }

    public function showForget(Request $request)
    {
        $action = $request->get('act');
        $this->assign('form_act', 'forget_pwd');
        $this->assign('ur_here', lang('get_newpassword'));

        return $this->display('get_pwd');
    }

    // POST
    // 发送找回密码确认邮件
    public function sendMail()
    {
        $admin_username = ! empty($_POST['user_name']) ? trim($_POST['user_name']) : '';
        $admin_email = ! empty($_POST['email']) ? trim($_POST['email']) : '';

        if (empty($admin_username) || empty($admin_email)) {
            return response()->redirectTo('privilege.php?act=login');
        }

        // 管理员用户名和邮件地址是否匹配，并取得原密码
        $admin_info = DB::table('admin_user')
            ->select('user_id', 'password', 'add_time')
            ->where('user_name', $admin_username)
            ->where('email', $admin_email)
            ->first();
        $admin_info = $admin_info ? (array) $admin_info : [];

        if (! empty($admin_info)) {
            // 生成验证的code
            $admin_id = $admin_info['user_id'];
            $code = md5($admin_id.$admin_info['password'].$admin_info['add_time']);

            // 设置重置邮件模板所需要的内容信息
            $template = CommonHelper::get_mail_template('send_password');
            $reset_email = ecs()->url().ADMIN_PATH.'/get_password.php?act=reset_pwd&uid='.$admin_id.'&code='.$code;

            $this->assign('user_name', $admin_username);
            $this->assign('reset_email', $reset_email);
            $this->assign('shop_name', cfg('shop_name'));
            $this->assign('send_date', TimeHelper::local_date(cfg('date_format')));
            $this->assign('sent_date', TimeHelper::local_date(cfg('date_format')));

            $content = $this->fetch('str:'.$template['template_content']);

            // 发送确认重置密码的确认邮件
            if (
                BaseHelper::send_mail(
                    $admin_username,
                    $admin_email,
                    $template['template_subject'],
                    $content,
                    $template['is_html']
                )
            ) {
                // 提示信息
                $link[0]['text'] = lang('back');
                $link[0]['href'] = 'privilege.php?act=login';

                return $this->sys_msg(lang('send_success').$admin_email, 0, $link);
            } else {
                return $this->sys_msg(lang('send_mail_error'), 1);
            }
        } else {
            // 提示信息
            return $this->sys_msg(lang('email_username_error'), 1);
        }
    }

    // GET 验证从邮件地址过来的链接
    public function showReset()
    {
        $code = ! empty($_GET['code']) ? trim($_GET['code']) : '';
        $adminid = ! empty($_GET['uid']) ? intval($_GET['uid']) : 0;

        if ($adminid === 0 || empty($code)) {
            return response()->redirectTo('privilege.php?act=login');
        }

        // 以用户的原密码，与code的值匹配
        $password = DB::table('admin_user')->where('user_id', $adminid)->value('password');

        // 验证链接（兼容旧 MD5 密码和新 bcrypt 密码）
        $isValidCode = false;
        if (strlen($password) === 32) {
            // 旧 MD5 密码
            $isValidCode = md5($adminid.$password) === $code;
        } else {
            // bcrypt 密码 - 无法直接验证，只能通过重新生成链接时的逻辑验证
            // 这里我们需要记录生成时的密码哈希
            // 暂时保留旧逻辑，同时升级到 bcrypt
            $admin_info = DB::table('admin_user')
                ->select('user_id', 'password', 'add_time')
                ->where('user_id', $adminid)
                ->first();
            $isValidCode = md5($adminid.$admin_info->password.$admin_info->add_time) === $code;
        }

        if (!$isValidCode) {
            // 此链接不合法
            $link[0]['text'] = lang('back');
            $link[0]['href'] = 'privilege.php?act=login';

            return $this->sys_msg(lang('code_param_error'), 0, $link);
        } else {
            $this->assign('adminid', $adminid);
            $this->assign('code', $code);
            $this->assign('form_act', 'reset_pwd');
        }

        $this->assign('ur_here', lang('get_newpassword'));

        return $this->display('get_pwd');
    }

    // POST
    // 验证新密码，更新管理员密码
    public function reset()
    {
        $new_password = isset($_POST['password']) ? trim($_POST['password']) : '';
        $adminid = isset($_POST['adminid']) ? intval($_POST['adminid']) : 0;
        $code = isset($_POST['code']) ? trim($_POST['code']) : '';

        if (empty($new_password) || empty($code) || $adminid === 0) {
            return response()->redirectTo('privilege.php?act=login');
        }

        // 更新管理员的密码（使用 bcrypt）
        $result = DB::table('admin_user')->where('user_id', $adminid)->update([
            'password' => Hash::make($new_password),
            'ec_salt' => null,
        ]);
        if ($result !== false) {
            $link[0]['text'] = lang('login_now');
            $link[0]['href'] = 'privilege.php?act=login';

            return $this->sys_msg(lang('update_pwd_success'), 0, $link);
        } else {
            return $this->sys_msg(lang('update_pwd_failed'), 1);
        }
    }
}
