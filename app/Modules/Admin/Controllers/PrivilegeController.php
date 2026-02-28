<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Helpers\TimeHelper;
use App\Modules\Admin\AdminServiceProvider;
use App\Modules\Admin\Libraries\Exchange;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class PrivilegeController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        $exc = new Exchange(ecs()->table('admin_user'), db(), 'user_id', 'user_name');

        /**
         * 退出登录
         */
        if ($action === 'logout') {
            Auth::guard(AdminServiceProvider::NS)->logout();

            return response()->redirectTo('index.php');
        }

        /**
         * 管理员列表页面
         */
        if ($action === 'list') {
            $this->assign('ur_here', lang('admin_list'));
            $this->assign('action_link', ['href' => 'privilege.php?act=add', 'text' => lang('admin_add')]);
            $this->assign('full_page', 1);
            $this->assign('admin_list', $this->get_admin_userlist());

            return $this->display('privilege_list');
        }

        /**
         * 查询
         */
        if ($action === 'query') {
            $this->assign('admin_list', $this->get_admin_userlist());

            return $this->make_json_result($this->fetch('privilege_list'));
        }

        /**
         * 添加管理员页面
         */
        if ($action === 'add') {
            $this->admin_priv('admin_manage');

            $this->assign('ur_here', lang('admin_add'));
            $this->assign('action_link', ['href' => 'privilege.php?act=list', 'text' => lang('admin_list')]);
            $this->assign('form_act', 'insert');
            $this->assign('action', 'add');
            $this->assign('select_role', $this->get_role_list());

            return $this->display('privilege_info');
        }

        /**
         * 添加管理员的处理
         */
        if ($action === 'insert') {
            $this->admin_priv('admin_manage');
            if ($_POST['token'] != cfg('token')) {
                return $this->sys_msg('add_error', 1);
            }
            // 判断管理员是否已经存在
            if (! empty($_POST['user_name'])) {
                $is_only = $exc->is_only('user_name', stripslashes($_POST['user_name']));

                if (! $is_only) {
                    return $this->sys_msg(sprintf(lang('user_name_exist'), stripslashes($_POST['user_name'])), 1);
                }
            }

            // Email地址是否有重复
            if (! empty($_POST['email'])) {
                $is_only = $exc->is_only('email', stripslashes($_POST['email']));

                if (! $is_only) {
                    return $this->sys_msg(sprintf(lang('email_exist'), stripslashes($_POST['email'])), 1);
                }
            }

            // 获取添加日期及密码
            $add_time = TimeHelper::gmtime();

            $password = Hash::make($_POST['password']);
            $role_id = '';
            $action_list = '';
            if (! empty($_POST['select_role'])) {
                $row = (array) DB::table('admin_role')->where('role_id', $_POST['select_role'])->first();
                $action_list = $row['action_list'];
                $role_id = $_POST['select_role'];
            }

            $row = (array) DB::table('admin_user')->where('action_list', 'all')->select('nav_list')->first();

            $new_id = DB::table('admin_user')->insertGetId([
                'user_name' => trim($_POST['user_name']),
                'email' => trim($_POST['email']),
                'password' => $password,
                'add_time' => $add_time,
                'nav_list' => $row['nav_list'] ?? '',
                'action_list' => $action_list,
                'role_id' => $role_id,
            ]);

            // 添加链接
            $link[0]['text'] = lang('go_allot_priv');
            $link[0]['href'] = 'privilege.php?act=allot&id='.$new_id.'&user='.$_POST['user_name'].'';

            $link[1]['text'] = lang('continue_add');
            $link[1]['href'] = 'privilege.php?act=add';

            return $this->sys_msg(lang('add').'&nbsp;'.$_POST['user_name'].'&nbsp;'.lang('action_succeed'), 0, $link);

            // 记录管理员操作
            $this->admin_log($_POST['user_name'], 'add', 'privilege');
        }

        /**
         * 编辑管理员信息
         */
        if ($action === 'edit') {
            // 不能编辑demo这个管理员
            if (Session::get('admin_name') === 'demo') {
                $link[] = ['text' => lang('back_list'), 'href' => 'privilege.php?act=list'];

                return $this->sys_msg(lang('edit_admininfo_cannot'), 0, $link);
            }

            $_REQUEST['id'] = ! empty($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;

            // 查看是否有权限编辑其他管理员的信息
            if (Session::get('admin_id') != $_REQUEST['id']) {
                $this->admin_priv('admin_manage');
            }

            // 获取管理员信息
            $user_info = (array) DB::table('admin_user')
                ->where('user_id', $_REQUEST['id'])
                ->select('user_id', 'user_name', 'email', 'password', 'agency_id', 'role_id')
                ->first();

            // 取得该管理员负责的办事处名称
            if ($user_info['agency_id'] > 0) {
                $user_info['agency_name'] = DB::table('shop_agency')->where('agency_id', $user_info['agency_id'])->value('agency_name');
            }

            $this->assign('ur_here', lang('admin_edit'));
            $this->assign('action_link', ['text' => lang('admin_list'), 'href' => 'privilege.php?act=list']);
            $this->assign('user', $user_info);

            // 获得该管理员的权限
            $priv_str = DB::table('admin_user')->where('user_id', $_GET['id'])->value('action_list');

            // 如果被编辑的管理员拥有了all这个权限，将不能编辑
            if ($priv_str != 'all') {
                $this->assign('select_role', $this->get_role_list());
            }
            $this->assign('form_act', 'update');
            $this->assign('action', 'edit');

            return $this->display('privilege_info');
        }

        /**
         * 更新管理员信息
         */
        if ($action === 'update' || $action === 'update_self') {
            // 变量初始化
            $admin_id = ! empty($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
            $admin_name = ! empty($_REQUEST['user_name']) ? trim($_REQUEST['user_name']) : '';
            $admin_email = ! empty($_REQUEST['email']) ? trim($_REQUEST['email']) : '';
            if ($_POST['token'] != cfg('token')) {
                return $this->sys_msg('update_error', 1);
            }
            if ($action === 'update') {
                // 查看是否有权限编辑其他管理员的信息
                if (Session::get('admin_id') != $_REQUEST['id']) {
                    $this->admin_priv('admin_manage');
                }
                $g_link = 'privilege.php?act=list';
                $nav_list = '';
            } else {
                $nav_list = ! empty($_POST['nav_list']) ? ", nav_list = '".@implode(',', $_POST['nav_list'])."'" : '';
                $admin_id = Session::get('admin_id');
                $g_link = 'privilege.php?act=modif';
            }
            // 判断管理员是否已经存在
            if (! empty($admin_name)) {
                $is_only = $exc->num('user_name', $admin_name, $admin_id);
                if ($is_only === 1) {
                    return $this->sys_msg(sprintf(lang('user_name_exist'), stripslashes($admin_name)), 1);
                }
            }

            // Email地址是否有重复
            if (! empty($admin_email)) {
                $is_only = $exc->num('email', $admin_email, $admin_id);

                if ($is_only === 1) {
                    return $this->sys_msg(sprintf(lang('email_exist'), stripslashes($admin_email)), 1);
                }
            }

            // 如果要修改密码
            $pwd_modified = false;

            if (! empty($_POST['new_password'])) {
                // 查询旧密码并与输入的旧密码比较是否相同
                $old_password = DB::table('admin_user')->where('user_id', $admin_id)->value('password');

                // 使用 Hash 验证密码（兼容旧 MD5 密码）
                $passwordValid = false;
                if (strlen($old_password) === 32) {
                    // 旧 MD5 密码兼容
                    $old_ec_salt = DB::table('admin_user')->where('user_id', $admin_id)->value('ec_salt');
                    if (empty($old_ec_salt)) {
                        $passwordValid = $old_password === md5($_POST['old_password']);
                    } else {
                        $passwordValid = $old_password === md5(md5($_POST['old_password']).$old_ec_salt);
                    }
                } else {
                    $passwordValid = Hash::check($_POST['old_password'], $old_password);
                }

                if (!$passwordValid) {
                    $link[] = ['text' => lang('go_back'), 'href' => 'javascript:history.back(-1)'];

                    return $this->sys_msg(lang('pwd_error'), 0, $link);
                }

                // 比较新密码和确认密码是否相同
                if ($_POST['new_password'] != $_POST['pwd_confirm']) {
                    $link[] = ['text' => lang('go_back'), 'href' => 'javascript:history.back(-1)'];

                    return $this->sys_msg(lang('js_languages.password_error'), 0, $link);
                } else {
                    $pwd_modified = true;
                }
            }

            $role_id = '';
            $action_list = '';
            if (! empty($_POST['select_role'])) {
                $row = (array) DB::table('admin_role')->where('role_id', $_POST['select_role'])->select('action_list')->first();
                $action_list = ', action_list = \''.$row['action_list'].'\'';
                $role_id = ', role_id = '.$_POST['select_role'].' ';
            }
            // 更新管理员信息
            $updateData = ['user_name' => $admin_name, 'email' => $admin_email];
            if ($pwd_modified) {
                $updateData['password'] = Hash::make($_POST['new_password']);
                $updateData['ec_salt'] = null;
            }
            if (! empty($_POST['select_role'])) {
                $updateData['action_list'] = $row['action_list'];
                $updateData['role_id'] = $_POST['select_role'];
            }
            if ($action !== 'update' && ! empty($_POST['nav_list'])) {
                $updateData['nav_list'] = @implode(',', $_POST['nav_list']);
            }

            DB::table('admin_user')->where('user_id', $admin_id)->update($updateData);
            // 记录管理员操作
            $this->admin_log($_POST['user_name'], 'edit', 'privilege');

            // 如果修改了密码，则需要将session中该管理员的数据清空
            if ($pwd_modified && $action === 'update_self') {
                // TODO $sess->delete_spec_admin_session(Session::get('admin_id'));
                $msg = lang('edit_password_succeed');
            } else {
                $msg = lang('edit_profile_succeed');
            }

            // 提示信息
            $link[] = ['text' => strpos($g_link, 'list') ? lang('back_admin_list') : lang('modif_info'), 'href' => $g_link];

            return $this->sys_msg("$msg<script>parent.document.getElementById('header-frame').contentWindow.document.location.reload();</script>", 0, $link);
        }

        /**
         * 编辑个人资料
         */
        if ($action === 'modif') {
            // 不能编辑demo这个管理员
            if (Session::get('admin_name') === 'demo') {
                $link[] = ['text' => lang('back_admin_list'), 'href' => 'privilege.php?act=list'];

                return $this->sys_msg(lang('edit_admininfo_cannot'), 0, $link);
            }

            // include_once 'includes/inc_menu.php';
            // include_once 'includes/inc_priv.php';

            foreach ($modules as $key => $value) {
                ksort($modules[$key]);
            }
            ksort($modules);

            foreach ($modules as $key => $val) {
                if (is_array($val)) {
                    foreach ($val as $k => $v) {
                        if (is_array($purview[$k])) {
                            $boole = false;
                            foreach ($purview[$k] as $action) {
                                $boole = $boole || $this->admin_priv($action, '', false);
                            }
                            if (! $boole) {
                                unset($modules[$key][$k]);
                            }
                        } elseif (! $this->admin_priv($purview[$k], '', false)) {
                            unset($modules[$key][$k]);
                        }
                    }
                }
            }

            // 获得当前管理员数据信息
            $user_info = (array) DB::table('admin_user')
                ->where('user_id', Session::get('admin_id'))
                ->select('user_id', 'user_name', 'email', 'nav_list')
                ->first();

            // 获取导航条
            $nav_arr = (trim($user_info['nav_list']) === '') ? [] : explode(',', $user_info['nav_list']);
            $nav_lst = [];
            foreach ($nav_arr as $val) {
                $arr = explode('|', $val);
                $nav_lst[$arr[1]] = $arr[0];
            }

            $this->assign('ur_here', lang('modif_info'));
            $this->assign('action_link', ['text' => lang('admin_list'), 'href' => 'privilege.php?act=list']);
            $this->assign('user', $user_info);
            $this->assign('menus', $modules);
            $this->assign('nav_arr', $nav_lst);

            $this->assign('form_act', 'update_self');
            $this->assign('action', 'modif');

            return $this->display('privilege_info');
        }

        /**
         * 为管理员分配权限
         */
        if ($action === 'allot') {
            // include_once ROOT_PATH.'languages/'.cfg('lang').'/admin/priv_action.php';

            $this->admin_priv('allot_priv');
            if (Session::get('admin_id') === $_GET['id']) {
                $this->admin_priv('all');
            }

            // 获得该管理员的权限
            $priv_str = DB::table('admin_user')->where('user_id', $_GET['id'])->value('action_list');

            // 如果被编辑的管理员拥有了all这个权限，将不能编辑
            if ($priv_str === 'all') {
                $link[] = ['text' => lang('back_admin_list'), 'href' => 'privilege.php?act=list'];

                return $this->sys_msg(lang('edit_admininfo_cannot'), 0, $link);
            }

            // 获取权限的分组数据
            $res = DB::table('admin_action')->where('parent_id', 0)->get();
            foreach ($res as $rows) {
                $rows = (array) $rows;
                $priv_arr[$rows['action_id']] = $rows;
            }

            // 按权限组查询底级的权限名称
            $result = DB::table('admin_action')->whereIn('parent_id', array_keys($priv_arr))->get();
            foreach ($result as $priv) {
                $priv = (array) $priv;
                $priv_arr[$priv['parent_id']]['priv'][$priv['action_code']] = $priv;
            }

            // 将同一组的权限使用 "," 连接起来，供JS全选
            foreach ($priv_arr as $action_id => $action_group) {
                $priv_arr[$action_id]['priv_list'] = implode(',', @array_keys($action_group['priv']));

                foreach ($action_group['priv'] as $key => $val) {
                    $priv_arr[$action_id]['priv'][$key]['cando'] = (strpos($priv_str, $val['action_code']) !== false || $priv_str === 'all') ? 1 : 0;
                }
            }

            // 赋值

            $this->assign('ur_here', lang('allot_priv').' [ '.$_GET['user'].' ] ');
            $this->assign('action_link', ['href' => 'privilege.php?act=list', 'text' => lang('admin_list')]);
            $this->assign('priv_arr', $priv_arr);
            $this->assign('form_act', 'update_allot');
            $this->assign('user_id', $_GET['id']);

            return $this->display('privilege_allot');
        }

        /**
         * 更新管理员的权限
         */
        if ($action === 'update_allot') {
            $this->admin_priv('admin_manage');
            if ($_POST['token'] != cfg('token')) {
                return $this->sys_msg('update_allot_error', 1);
            }
            // 取得当前管理员用户名
            $admin_name = DB::table('admin_user')->where('user_id', $_POST['id'])->value('user_name');

            // 更新管理员的权限
            $act_list = @implode(',', $_POST['action_code']);
            DB::table('admin_user')->where('user_id', $_POST['id'])->update([
                'action_list' => $act_list,
                'role_id' => '',
            ]);
            // 动态更新管理员的SESSION
            if (Session::get('admin_id') === $_POST['id']) {
                Session::put('action_list', $act_list);
            }

            // 记录管理员操作
            $this->admin_log(addslashes($admin_name), 'edit', 'privilege');

            // 提示信息
            $link[] = ['text' => lang('back_admin_list'), 'href' => 'privilege.php?act=list'];

            return $this->sys_msg(lang('edit').'&nbsp;'.$admin_name.'&nbsp;'.lang('action_succeed'), 0, $link);
        }

        /**
         * 删除一个管理员
         */
        if ($action === 'remove') {
            $this->check_authz_json('admin_drop');

            $id = intval($_GET['id']);

            // 获得管理员用户名
            $admin_name = DB::table('admin_user')->where('user_id', $id)->value('user_name');

            // demo这个管理员不允许删除
            if ($admin_name === 'demo') {
                return $this->make_json_error(lang('edit_remove_cannot'));
            }

            // ID为1的不允许删除
            if ($id === 1) {
                return $this->make_json_error(lang('remove_cannot'));
            }

            // 管理员不能删除自己
            if ($id === Session::get('admin_id')) {
                return $this->make_json_error(lang('remove_self_cannot'));
            }

            if ($exc->drop($id)) {
                // TODO $sess->delete_spec_admin_session($id); // 删除session中该管理员的记录

                $this->admin_log(addslashes($admin_name), 'remove', 'privilege');
                $this->clear_cache_files();
            }

            $url = 'privilege.php?act=query&'.str_replace('act=remove', '', $_SERVER['QUERY_STRING']);

            return response()->redirectTo($url);
        }
    }

    // 获取管理员列表
    private function get_admin_userlist()
    {
        $list = [];
        $list = DB::table('admin_user')
            ->orderBy('user_id', 'desc')
            ->select('user_id', 'user_name', 'email', 'add_time', 'last_login')
            ->get();
        $list = $list->map(fn ($r) => (array) $r)->all();

        foreach ($list as $key => $val) {
            $list[$key]['add_time'] = TimeHelper::local_date(cfg('time_format'), $val['add_time']);
            $list[$key]['last_login'] = TimeHelper::local_date(cfg('time_format'), $val['last_login']);
        }

        return $list;
    }

    // 清除购物车中过期的数据
    private function clear_cart()
    {
        // 取得有效的session
        $valid_sess = DB::table('user_cart AS c')
            ->join('sessions AS s', 'c.session_id', '=', 's.sesskey')
            ->distinct()
            ->pluck('c.session_id')
            ->all();

        // 删除cart中无效的数据
        DB::table('user_cart')->whereNotIn('session_id', $valid_sess)->delete();
    }

    // 获取角色列表
    private function get_role_list()
    {
        $list = DB::table('admin_role')
            ->select('role_id', 'role_name', 'action_list')
            ->get();

        return $list->map(fn ($r) => (array) $r)->all();
    }
}
