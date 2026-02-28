<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Modules\Admin\Libraries\Exchange;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class RoleController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        // 初始化 $exc 对象
        $exc = new Exchange(ecs()->table('admin_role'), db(), 'role_id', 'role_name');

        /**
         * 角色列表页面
         */
        if ($action === 'list') {
            $this->assign('ur_here', lang('admin_role'));
            $this->assign('action_link', ['href' => 'role.php?act=add', 'text' => lang('admin_add_role')]);
            $this->assign('full_page', 1);
            $this->assign('admin_list', $this->get_role_list());

            return $this->display('role_list');
        }

        /**
         * 查询
         */
        if ($action === 'query') {
            $this->assign('admin_list', $this->get_role_list());

            return $this->make_json_result($this->fetch('role_list'));
        }

        /**
         * 添加角色页面
         */
        if ($action === 'add') {
            $this->admin_priv('admin_manage');
            // include_once ROOT_PATH.'languages/'.cfg('lang').'/admin/priv_action.php';

            $priv_str = '';

            // 获取权限的分组数据
            $res = DB::table('admin_action')
                ->where('parent_id', 0)
                ->select('action_id', 'parent_id', 'action_code', 'relevance')
                ->get();
            foreach ($res as $rows) {
                $rows = (array) $rows;
                $priv_arr[$rows['action_id']] = $rows;
            }

            // 按权限组查询底级的权限名称
            $result = DB::table('admin_action')
                ->whereIn('parent_id', array_keys($priv_arr))
                ->select('action_id', 'parent_id', 'action_code', 'relevance')
                ->get();
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

            $this->assign('ur_here', lang('admin_add_role'));
            $this->assign('action_link', ['href' => 'role.php?act=list', 'text' => lang('admin_list_role')]);
            $this->assign('form_act', 'insert');
            $this->assign('action', 'add');

            $this->assign('priv_arr', $priv_arr);

            return $this->display('role_info');
        }

        /**
         * 添加角色的处理
         */
        if ($action === 'insert') {
            $this->admin_priv('admin_manage');
            $act_list = @implode(',', $_POST['action_code']);
            DB::table('admin_role')->insert([
                'role_name' => trim($_POST['user_name']),
                'action_list' => $act_list,
                'role_describe' => trim($_POST['role_describe']),
            ]);
            $new_id = DB::getPdo()->lastInsertId();

            // 添加链接

            $link[0]['text'] = lang('admin_list_role');
            $link[0]['href'] = 'role.php?act=list';

            return $this->sys_msg(lang('add').'&nbsp;'.$_POST['user_name'].'&nbsp;'.lang('action_succeed'), 0, $link);

            // 记录管理员操作
            $this->admin_log($_POST['user_name'], 'add', 'role');
        }

        /**
         * 编辑角色信息
         */
        if ($action === 'edit') {
            // include_once ROOT_PATH.'languages/'.cfg('lang').'/admin/priv_action.php';
            $_REQUEST['id'] = ! empty($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
            // 获得该管理员的权限
            $priv_str = DB::table('admin_role')
                ->where('role_id', (int) $_GET['id'])
                ->value('action_list');

            // 查看是否有权限编辑其他管理员的信息
            if (Session::get('admin_id') != $_REQUEST['id']) {
                $this->admin_priv('admin_manage');
            }

            // 获取角色信息
            $user_info = (array) DB::table('admin_role')
                ->where('role_id', (int) $_REQUEST['id'])
                ->select('role_id', 'role_name', 'role_describe')
                ->first();

            // 获取权限的分组数据
            $res = DB::table('admin_action')
                ->where('parent_id', 0)
                ->select('action_id', 'parent_id', 'action_code', 'relevance')
                ->get();
            foreach ($res as $rows) {
                $rows = (array) $rows;
                $priv_arr[$rows['action_id']] = $rows;
            }

            // 按权限组查询底级的权限名称
            $result = DB::table('admin_action')
                ->whereIn('parent_id', array_keys($priv_arr))
                ->select('action_id', 'parent_id', 'action_code', 'relevance')
                ->get();
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

            $this->assign('user', $user_info);
            $this->assign('form_act', 'update');
            $this->assign('action', 'edit');
            $this->assign('ur_here', lang('admin_edit_role'));
            $this->assign('action_link', ['href' => 'role.php?act=list', 'text' => lang('admin_list_role')]);

            $this->assign('priv_arr', $priv_arr);
            $this->assign('user_id', $_GET['id']);

            return $this->display('role_info');
        }

        /**
         * 更新角色信息
         */
        if ($action === 'update') {
            // 更新管理员的权限
            $act_list = @implode(',', $_POST['action_code']);
            DB::table('admin_role')
                ->where('role_id', (int) $_POST['id'])
                ->update([
                    'action_list' => $act_list,
                    'role_name' => $_POST['user_name'],
                    'role_describe' => $_POST['role_describe'],
                ]);
            DB::table('admin_user')
                ->where('role_id', (int) $_POST['id'])
                ->update(['action_list' => $act_list]);
            // 提示信息
            $link[] = ['text' => lang('back_admin_list'), 'href' => 'role.php?act=list'];

            return $this->sys_msg(lang('edit').'&nbsp;'.$_POST['user_name'].'&nbsp;'.lang('action_succeed'), 0, $link);
        }

        /**
         * 删除一个角色
         */
        if ($action === 'remove') {
            $this->check_authz_json('admin_drop');

            $id = intval($_GET['id']);
            $remove_num = DB::table('admin_user')
                ->where('role_id', (int) $_GET['id'])
                ->count();
            if ($remove_num > 0) {
                return $this->make_json_error(lang('remove_cannot_user'));
            } else {
                $exc->drop($id);
                $url = 'role.php?act=query&'.str_replace('act=remove', '', $_SERVER['QUERY_STRING']);
            }

            return response()->redirectTo($url);
        }
    }

    // 获取角色列表
    private function get_role_list()
    {
        return DB::table('admin_role')
            ->orderByDesc('role_id')
            ->select('role_id', 'role_name', 'action_list', 'role_describe')
            ->get()
            ->map(fn ($r) => (array) $r)
            ->all();
    }
}
