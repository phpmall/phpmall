<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Helpers\BaseHelper;
use App\Modules\Admin\Libraries\Exchange;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RegFieldsController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        $exc = new Exchange(ecs()->table('user_extend_fields'), db(), 'id', 'reg_field_name');

        /**
         * 会员注册项列表
         */
        if ($action === 'list') {
            $fields = [];
            $fields = DB::table('user_extend_fields')
                ->orderBy('dis_order')
                ->orderBy('id')
                ->get()
                ->map(fn ($item) => (array) $item)
                ->all();

            $this->assign('ur_here', lang('021_reg_fields'));
            $this->assign('action_link', ['text' => lang('add_reg_field'), 'href' => 'reg_fields.php?act=add']);
            $this->assign('full_page', 1);

            $this->assign('reg_fields', $fields);

            return $this->display('reg_fields');
        }

        /**
         * 翻页，排序
         */
        if ($action === 'query') {
            $fields = [];
            $fields = DB::table('user_extend_fields')
                ->orderBy('id')
                ->get()
                ->map(fn ($item) => (array) $item)
                ->all();

            $this->assign('reg_fields', $fields);

            return $this->make_json_result($this->fetch('reg_fields'));
        }

        /**
         * 添加会员注册项
         */
        if ($action === 'add') {
            $this->admin_priv('reg_fields');

            $form_action = 'insert';

            $reg_field['reg_field_order'] = 100;
            $reg_field['reg_field_display'] = 1;
            $reg_field['reg_field_need'] = 1;

            $this->assign('reg_field', $reg_field);
            $this->assign('ur_here', lang('add_reg_field'));
            $this->assign('action_link', ['text' => lang('021_reg_fields'), 'href' => 'reg_fields.php?act=list']);
            $this->assign('form_action', $form_action);

            return $this->display('reg_field_info');
        }

        /**
         * 增加会员注册项到数据库
         */
        if ($action === 'insert') {
            $this->admin_priv('reg_fields');

            // 检查是否存在重名的会员注册项
            if (! $exc->is_only('reg_field_name', trim($_POST['reg_field_name']))) {
                return $this->sys_msg(sprintf(lang('field_name_exist'), trim($_POST['reg_field_name'])), 1);
            }

            DB::table('user_extend_fields')->insert([
                'reg_field_name' => $_POST['reg_field_name'],
                'dis_order' => (int) $_POST['reg_field_order'],
                'display' => (int) $_POST['reg_field_display'],
                'is_need' => (int) $_POST['reg_field_need'],
            ]);

            // 管理员日志
            $this->admin_log(trim($_POST['reg_field_name']), 'add', 'reg_fields');
            $this->clear_cache_files();

            $lnk[] = ['text' => lang('back_list'), 'href' => 'reg_fields.php?act=list'];
            $lnk[] = ['text' => lang('add_continue'), 'href' => 'reg_fields.php?act=add'];

            return $this->sys_msg(lang('add_field_success'), 0, $lnk);
        }

        /**
         * 编辑会员注册项
         */
        if ($action === 'edit') {
            $this->admin_priv('reg_fields');

            $form_action = 'update';

            $reg_field = (array) DB::table('user_extend_fields')
                ->where('id', (int) $_REQUEST['id'])
                ->select('id as reg_field_id', 'reg_field_name', 'dis_order as reg_field_order', 'display as reg_field_display', 'is_need as reg_field_need')
                ->first();

            $this->assign('reg_field', $reg_field);
            $this->assign('ur_here', lang('add_reg_field'));
            $this->assign('action_link', ['text' => lang('021_reg_fields'), 'href' => 'reg_fields.php?act=list']);
            $this->assign('form_action', $form_action);

            return $this->display('reg_field_info');
        }

        /**
         * 更新会员注册项
         */
        if ($action === 'update') {
            $this->admin_priv('reg_fields');

            // 检查是否存在重名的会员注册项
            if ($_POST['reg_field_name'] != $_POST['old_field_name'] && ! $exc->is_only('reg_field_name', trim($_POST['reg_field_name']))) {
                return $this->sys_msg(sprintf(lang('field_name_exist'), trim($_POST['reg_field_name'])), 1);
            }

            DB::table('user_extend_fields')
                ->where('id', (int) $_POST['id'])
                ->update([
                    'reg_field_name' => $_POST['reg_field_name'],
                    'dis_order' => (int) $_POST['reg_field_order'],
                    'display' => (int) $_POST['reg_field_display'],
                    'is_need' => (int) $_POST['reg_field_need'],
                ]);

            // 管理员日志
            $this->admin_log(trim($_POST['reg_field_name']), 'edit', 'reg_fields');
            $this->clear_cache_files();

            $lnk[] = ['text' => lang('back_list'), 'href' => 'reg_fields.php?act=list'];

            return $this->sys_msg(lang('update_field_success'), 0, $lnk);
        }

        /**
         * 删除会员注册项
         */
        if ($action === 'remove') {
            $this->check_authz_json('reg_fields');

            $field_id = intval($_GET['id']);
            $field_name = $exc->get_name($field_id);

            if ($exc->drop($field_id)) {
                // 删除会员扩展信息表的相应信息
                DB::table('user_extend_info')
                    ->where('reg_field_id', $field_id)
                    ->delete();

                $this->admin_log(addslashes($field_name), 'remove', 'reg_fields');
                $this->clear_cache_files();
            }

            $url = 'reg_fields.php?act=query&'.str_replace('act=remove', '', $_SERVER['QUERY_STRING']);

            return response()->redirectTo($url);
        }

        /**
         *  编辑会员注册项名称
         */
        if ($action === 'edit_name') {
            $id = intval($_REQUEST['id']);
            $val = empty($_REQUEST['val']) ? '' : BaseHelper::json_str_iconv(trim($_REQUEST['val']));
            $this->check_authz_json('reg_fields');
            if ($exc->is_only('reg_field_name', $val, $id)) {
                if ($exc->edit("reg_field_name = '$val'", $id)) {
                    // 管理员日志
                    $this->admin_log($val, 'edit', 'reg_fields');
                    $this->clear_cache_files();

                    return $this->make_json_result(stripcslashes($val));
                } else {
                    return $this->make_json_error('DB error');
                }
            } else {
                return $this->make_json_error(sprintf(lang('field_name_exist'), htmlspecialchars($val)));
            }
        }

        /**
         *  编辑会员注册项排序权值
         */
        if ($action === 'edit_order') {
            $id = intval($_REQUEST['id']);
            $val = isset($_REQUEST['val']) ? BaseHelper::json_str_iconv(trim($_REQUEST['val'])) : '';
            $this->check_authz_json('reg_fields');
            if (is_numeric($val)) {
                if ($exc->edit("dis_order = '$val'", $id)) {
                    // 管理员日志
                    $this->admin_log($val, 'edit', 'reg_fields');
                    $this->clear_cache_files();

                    return $this->make_json_result(stripcslashes($val));
                } else {
                    return $this->make_json_error('DB error');
                }
            } else {
                return $this->make_json_error(lang('order_not_num'));
            }
        }

        /**
         * 修改会员注册项显示状态
         */
        if ($action === 'toggle_dis') {
            $this->check_authz_json('reg_fields');

            $id = intval($_POST['id']);
            $is_dis = intval($_POST['val']);

            if ($exc->edit("display = '$is_dis'", $id)) {
                $this->clear_cache_files();

                return $this->make_json_result($is_dis);
            }
        }

        /**
         * 修改会员注册项必填状态
         */
        if ($action === 'toggle_need') {
            $this->check_authz_json('reg_fields');

            $id = intval($_POST['id']);
            $is_need = intval($_POST['val']);

            if ($exc->edit("is_need = '$is_need'", $id)) {
                $this->clear_cache_files();

                return $this->make_json_result($is_need);
            }
        }
    }
}
