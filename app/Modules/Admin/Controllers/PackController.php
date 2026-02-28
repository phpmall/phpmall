<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Helpers\BaseHelper;
use App\Libraries\Image;
use App\Modules\Admin\Helpers\MainHelper;
use App\Modules\Admin\Libraries\Exchange;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PackController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        $image = new Image(cfg('bgcolor'));

        $exc = new Exchange(ecs()->table('shop_pack'), db(), 'pack_id', 'pack_name');

        /**
         * 包装列表
         */
        if ($action === 'list') {
            $this->assign('ur_here', lang('06_pack_list'));
            $this->assign('action_link', ['text' => lang('pack_add'), 'href' => 'pack.php?act=add']);
            $this->assign('full_page', 1);

            $packs_list = $this->packs_list();

            $this->assign('packs_list', $packs_list['packs_list']);
            $this->assign('filter', $packs_list['filter']);
            $this->assign('record_count', $packs_list['record_count']);
            $this->assign('page_count', $packs_list['page_count']);

            return $this->display('pack_list');
        }
        /**
         * ajax 列表
         */
        if ($action === 'query') {
            $packs_list = $this->packs_list();
            $this->assign('packs_list', $packs_list['packs_list']);
            $this->assign('filter', $packs_list['filter']);
            $this->assign('record_count', $packs_list['record_count']);
            $this->assign('page_count', $packs_list['page_count']);

            $sort_flag = MainHelper::sort_flag($packs_list['filter']);
            $this->assign($sort_flag['tag'], $sort_flag['img']);

            return $this->make_json_result($this->fetch('pack_list'), '', ['filter' => $packs_list['filter'], 'page_count' => $packs_list['page_count']]);
        }
        /**
         * 添加新包装
         */
        if ($action === 'add') {
            // 权限判断
            $this->admin_priv('pack');

            $pack['pack_fee'] = 0;
            $pack['free_money'] = 0;

            $this->assign('pack', $pack);
            $this->assign('ur_here', lang('pack_add'));
            $this->assign('form_action', 'insert');
            $this->assign('action_link', ['text' => lang('06_pack_list'), 'href' => 'pack.php?act=list']);

            return $this->display('pack_info');
        }

        if ($action === 'insert') {
            // 权限判断
            $this->admin_priv('pack');

            // 检查包装名是否重复
            $is_only = $exc->is_only('pack_name', $_POST['pack_name']);

            if (! $is_only) {
                return $this->sys_msg(sprintf(lang('packname_exist'), stripslashes($_POST['pack_name'])), 1);
            }

            // 处理图片
            if (! empty($_FILES['pack_img'])) {
                $upload_img = $image->upload_image($_FILES['pack_img'], 'packimg', $_POST['old_packimg']);
                if ($upload_img === false) {
                    return $this->sys_msg($image->error_msg);
                }
                $img_name = basename($upload_img);
            } else {
                $img_name = '';
            }

            // 插入数据
            DB::table('shop_pack')->insert([
                'pack_name' => $_POST['pack_name'],
                'pack_fee' => $_POST['pack_fee'],
                'free_money' => $_POST['free_money'],
                'pack_desc' => $_POST['pack_desc'],
                'pack_img' => $img_name,
            ]);

            // 添加链接
            $link[0]['text'] = lang('back_list');
            $link[0]['href'] = 'pack.php?act=list';
            $link[1]['text'] = lang('continue_add');
            $link[1]['href'] = 'pack.php?act=add';

            return $this->sys_msg($_POST['pack_name'].lang('packadd_succed'), 0, $link);
            $this->admin_log($_POST['pack_name'], 'add', 'pack');
        }

        /**
         * 编辑包装
         */
        if ($action === 'edit') {
            // 权限判断
            $this->admin_priv('pack');

            $pack = DB::table('shop_pack')
                ->select('pack_id', 'pack_name', 'pack_fee', 'free_money', 'pack_desc', 'pack_img')
                ->where('pack_id', $_REQUEST['id'])
                ->first();
            $pack = $pack ? (array) $pack : [];
            $this->assign('ur_here', lang('pack_edit'));
            $this->assign('action_link', ['text' => lang('06_pack_list'), 'href' => 'pack.php?act=list&'.MainHelper::list_link_postfix()]);
            $this->assign('pack', $pack);
            $this->assign('form_action', 'update');

            return $this->display('pack_info');
        }

        if ($action === 'update') {
            // 权限判断
            $this->admin_priv('pack');
            if ($_POST['pack_name'] != $_POST['old_packname']) {
                // 检查品牌名是否相同
                $is_only = $exc->is_only('pack_name', $_POST['pack_name'], $_POST['id']);

                if (! $is_only) {
                    return $this->sys_msg(sprintf(lang('packname_exist'), stripslashes($_POST['pack_name'])), 1);
                }
            }

            $param = "pack_name = '$_POST[pack_name]', pack_fee = '$_POST[pack_fee]', free_money= '$_POST[free_money]', pack_desc = '$_POST[pack_desc]' ";
            // 处理图片
            if (! empty($_FILES['pack_img']['name'])) {
                $upload_img = $image->upload_image($_FILES['pack_img'], 'packimg', $_POST['old_packimg']);
                if ($upload_img === false) {
                    return $this->sys_msg($image->error_msg);
                }
                $img_name = basename($upload_img);
            } else {
                $img_name = '';
            }

            if (! empty($img_name)) {
                $param .= " ,pack_img = '$img_name' ";
            }

            if ($exc->edit($param, $_POST['id'])) {
                $link[0]['text'] = lang('back_list');
                $link[0]['href'] = 'pack.php?act=list&'.MainHelper::list_link_postfix();
                $note = sprintf(lang('packedit_succed'), $_POST['pack_name']);

                return $this->sys_msg($note, 0, $link);
                $this->admin_log($_POST['pack_name'], 'edit', 'pack');
            } else {
                exit(lang('error'));
            }
        }

        // 删除卡片图片
        if ($action === 'drop_pack_img') {
            // 权限判断
            $this->admin_priv('pack');
            $pack_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

            // 取得logo名称
            $img_name = DB::table('shop_pack')
                ->where('pack_id', $pack_id)
                ->value('pack_img');

            if (! empty($img_name)) {
                @unlink(ROOT_PATH.DATA_DIR.'/packimg/'.$img_name);
                DB::table('shop_pack')
                    ->where('pack_id', $pack_id)
                    ->update(['pack_img' => '']);
            }
            $link = [['text' => lang('pack_edit_lnk'), 'href' => 'pack.php?act=edit&id='.$pack_id], ['text' => lang('pack_list_lnk'), 'href' => 'pack.php?act=list']];

            return $this->sys_msg(lang('drop_pack_img_success'), 0, $link);
        }

        /**
         * 编辑包装名称
         */
        if ($action === 'edit_name') {
            $this->check_authz_json('pack');

            $id = intval($_POST['id']);
            $val = BaseHelper::json_str_iconv(trim($_POST['val']));

            // 取得该属性所属商品类型id
            $pack_name = $exc->get_name($id);

            if (! $exc->is_only('pack_name', $val, $id)) {
                return $this->make_json_error(sprintf(lang('packname_exist'), $pack_name));
            } else {
                $exc->edit("pack_name='$val'", $id);

                $this->admin_log($val, 'edit', 'pack');

                return $this->make_json_result(stripslashes($val));
            }
        }

        /**
         * 编辑包装费用
         */
        if ($action === 'edit_pack_fee') {
            $this->check_authz_json('pack');

            $id = intval($_POST['id']);
            $val = floatval($_POST['val']);

            // 取得该属性所属商品类型id
            $pack_name = $exc->get_name($id);

            $exc->edit("pack_fee='$val'", $id);
            $this->admin_log(addslashes($pack_name), 'edit', 'pack');

            return $this->make_json_result(number_format($val, 2));
        }

        /**
         * 编辑免费额度
         */
        if ($action === 'edit_free_money') {
            $this->check_authz_json('pack');

            $id = intval($_POST['id']);
            $val = floatval($_POST['val']);

            // 取得该属性所属商品类型id
            $pack_name = $exc->get_name($id);

            $exc->edit("free_money='$val'", $id);
            $this->admin_log(addslashes($pack_name), 'edit', 'pack');

            return $this->make_json_result(number_format($val, 2));
        }

        /**
         * 删除包装
         */
        if ($action === 'remove') {
            $this->check_authz_json('pack');

            $id = intval($_GET['id']);
            $name = $exc->get_name($id);
            $img = $exc->get_name($id, 'pack_img');

            if ($exc->drop($id)) {
                // 删除图片
                if (! empty($img)) {
                    @unlink('../'.DATA_DIR.'/packimg/'.$img);
                }
                $this->admin_log(addslashes($name), 'remove', 'pack');

                $url = 'pack.php?act=query&'.str_replace('act=remove', '', $_SERVER['QUERY_STRING']);

                return response()->redirectTo($url);
            } else {
                return $this->make_json_error(lang('packremove_falure'));

                return false;
            }
        }
    }

    private function packs_list()
    {
        $result = MainHelper::get_filter();
        if ($result === false) {
            $filter['sort_by'] = empty($_REQUEST['sort_by']) ? 'pack_id' : trim($_REQUEST['sort_by']);
            $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);

            $filter['record_count'] = DB::table('shop_pack')->count();

            // 分页大小
            $filter = MainHelper::page_and_size($filter);

            // 查询
            MainHelper::set_filter($filter, '');
        } else {
            $filter = $result['filter'];
        }

        $packs_list = DB::table('shop_pack')
            ->select('pack_id', 'pack_name', 'pack_img', 'pack_fee', 'free_money', 'pack_desc')
            ->orderBy($filter['sort_by'], $filter['sort_order'])
            ->offset($filter['start'])
            ->limit($filter['page_size'])
            ->get()
            ->map(function ($item) {
                return (array) $item;
            })
            ->toArray();

        $arr = ['packs_list' => $packs_list, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']];

        return $arr;
    }
}
