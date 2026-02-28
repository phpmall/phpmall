<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Helpers\BaseHelper;
use App\Modules\Admin\Helpers\MainHelper;
use App\Modules\Admin\Libraries\Exchange;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttributeController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        $exc = new Exchange(ecs()->table('goods_type_attribute'), db(), 'attr_id', 'attr_name');

        /**
         * 属性列表
         */
        if ($action === 'list') {
            $goods_type = isset($_GET['goods_type']) ? intval($_GET['goods_type']) : 0;

            $this->assign('ur_here', lang('09_attribute_list'));
            $this->assign('action_link', ['href' => 'attribute.php?act=add&goods_type='.$goods_type, 'text' => lang('10_attribute_add')]);
            $this->assign('goods_type_list', MainHelper::goods_type_list($goods_type)); // 取得商品类型
            $this->assign('full_page', 1);

            $list = $this->get_attrlist();

            $this->assign('attr_list', $list['item']);
            $this->assign('filter', $list['filter']);
            $this->assign('record_count', $list['record_count']);
            $this->assign('page_count', $list['page_count']);

            $sort_flag = MainHelper::sort_flag($list['filter']);
            $this->assign($sort_flag['tag'], $sort_flag['img']);

            return $this->display('attribute_list');
        }

        /**
         * 排序、翻页
         */
        if ($action === 'query') {
            $list = $this->get_attrlist();

            $this->assign('attr_list', $list['item']);
            $this->assign('filter', $list['filter']);
            $this->assign('record_count', $list['record_count']);
            $this->assign('page_count', $list['page_count']);

            $sort_flag = MainHelper::sort_flag($list['filter']);
            $this->assign($sort_flag['tag'], $sort_flag['img']);

            return $this->make_json_result(
                $this->fetch('attribute_list'),
                '',
                ['filter' => $list['filter'], 'page_count' => $list['page_count']]
            );
        }

        /**
         * 添加/编辑属性
         */
        if ($action === 'add' || $action === 'edit') {
            $this->admin_priv('attr_manage');

            // 添加还是编辑的标识
            $is_add = $action === 'add';
            $this->assign('form_act', $is_add ? 'insert' : 'update');

            // 取得属性信息
            if ($is_add) {
                $goods_type = isset($_GET['goods_type']) ? intval($_GET['goods_type']) : 0;
                $attr = [
                    'attr_id' => 0,
                    'cat_id' => $goods_type,
                    'attr_name' => '',
                    'attr_input_type' => 0,
                    'attr_index' => 0,
                    'attr_values' => '',
                    'attr_type' => 0,
                    'is_linked' => 0,
                ];
            } else {
                $attr = DB::table('goods_type_attribute')
                    ->where('attr_id', $_REQUEST['attr_id'])
                    ->first();
                $attr = $attr ? (array) $attr : [];
            }

            $this->assign('attr', $attr);
            $this->assign('attr_groups', MainHelper::get_attr_groups($attr['cat_id']));

            // 取得商品分类列表
            $this->assign('goods_type_list', MainHelper::goods_type_list($attr['cat_id']));

            $this->assign('ur_here', $is_add ? lang('10_attribute_add') : lang('52_attribute_add'));
            $this->assign('action_link', ['href' => 'attribute.php?act=list', 'text' => lang('09_attribute_list')]);

            return $this->display('attribute_info');
        }

        /**
         * 插入/更新属性
         */
        if ($action === 'insert' || $action === 'update') {
            $this->admin_priv('attr_manage');

            // 插入还是更新的标识
            $is_insert = $action === 'insert';

            // 检查名称是否重复
            $exclude = empty($_POST['attr_id']) ? 0 : intval($_POST['attr_id']);
            if (! $exc->is_only('attr_name', $_POST['attr_name'], $exclude, " cat_id = '$_POST[cat_id]'")) {
                return $this->sys_msg(lang('name_exist'), 1);
            }

            $cat_id = $_REQUEST['cat_id'];

            // 取得属性信息
            $attr = [
                'cat_id' => $_POST['cat_id'],
                'attr_name' => $_POST['attr_name'],
                'attr_index' => $_POST['attr_index'],
                'attr_input_type' => $_POST['attr_input_type'],
                'is_linked' => $_POST['is_linked'],
                'attr_values' => isset($_POST['attr_values']) ? $_POST['attr_values'] : '',
                'attr_type' => empty($_POST['attr_type']) ? '0' : intval($_POST['attr_type']),
                'attr_group' => isset($_POST['attr_group']) ? intval($_POST['attr_group']) : 0,
            ];

            // 入库、记录日志、提示信息
            if ($is_insert) {
                DB::table('goods_type_attribute')->insert($attr);
                $this->admin_log($_POST['attr_name'], 'add', 'attribute');
                $links = [
                    ['text' => lang('add_next'), 'href' => '?act=add&goods_type='.$_POST['cat_id']],
                    ['text' => lang('back_list'), 'href' => '?act=list'],
                ];

                return $this->sys_msg(sprintf(lang('add_ok'), $attr['attr_name']), 0, $links);
            } else {
                DB::table('goods_type_attribute')
                    ->where('attr_id', $_POST['attr_id'])
                    ->update($attr);
                $this->admin_log($_POST['attr_name'], 'edit', 'attribute');
                $links = [
                    ['text' => lang('back_list'), 'href' => '?act=list&amp;goods_type='.$_POST['cat_id'].''],
                ];

                return $this->sys_msg(sprintf(lang('edit_ok'), $attr['attr_name']), 0, $links);
            }
        }

        /**
         * 删除属性(一个或多个)
         */
        if ($action === 'batch') {
            $this->admin_priv('attr_manage');

            // 取得要操作的编号
            if (isset($_POST['checkboxes'])) {
                $count = count($_POST['checkboxes']);
                $ids = $_POST['checkboxes'];

                DB::table('goods_type_attribute')->whereIn('attr_id', $ids)->delete();
                DB::table('goods_attr')->whereIn('attr_id', $ids)->delete();

                // 记录日志
                $this->admin_log('', 'batch_remove', 'attribute');
                $this->clear_cache_files();

                $link[] = ['text' => lang('back_list'), 'href' => 'attribute.php?act=list'];

                return $this->sys_msg(sprintf(lang('drop_ok'), $count), 0, $link);
            } else {
                $link[] = ['text' => lang('back_list'), 'href' => 'attribute.php?act=list'];

                return $this->sys_msg(lang('no_select_arrt'), 0, $link);
            }
        }

        /**
         * 编辑属性名称
         */
        if ($action === 'edit_attr_name') {
            $this->check_authz_json('attr_manage');

            $id = intval($_POST['id']);
            $val = BaseHelper::json_str_iconv(trim($_POST['val']));

            // 取得该属性所属商品类型id
            $cat_id = $exc->get_name($id, 'cat_id');

            // 检查属性名称是否重复
            if (! $exc->is_only('attr_name', $val, $id, " cat_id = '$cat_id'")) {
                return $this->make_json_error(lang('name_exist'));
            }

            $exc->edit("attr_name='$val'", $id);

            $this->admin_log($val, 'edit', 'attribute');

            return $this->make_json_result(stripslashes($val));
        }

        /**
         * 编辑排序序号
         */
        if ($action === 'edit_sort_order') {
            $this->check_authz_json('attr_manage');

            $id = intval($_POST['id']);
            $val = intval($_POST['val']);

            $exc->edit("sort_order='$val'", $id);

            $this->admin_log(addslashes($exc->get_name($id)), 'edit', 'attribute');

            return $this->make_json_result(stripslashes($val));
        }

        /**
         * 删除商品属性
         */
        if ($action === 'remove') {
            $this->check_authz_json('attr_manage');

            $id = intval($_GET['id']);

            DB::table('goods_type_attribute')->where('attr_id', $id)->delete();
            DB::table('goods_attr')->where('attr_id', $id)->delete();

            $url = 'attribute.php?act=query&'.str_replace('act=remove', '', $_SERVER['QUERY_STRING']);

            return response()->redirectTo($url);
        }

        /**
         * 获取某属性商品数量
         */
        if ($action === 'get_attr_num') {
            $this->check_authz_json('attr_manage');

            $id = intval($_GET['attr_id']);

            $goods_num = DB::table('goods_attr as a')
                ->join('goods as g', 'g.goods_id', '=', 'a.goods_id')
                ->where('g.is_delete', 0)
                ->where('a.attr_id', $id)
                ->count();

            if ($goods_num > 0) {
                $drop_confirm = sprintf(lang('notice_drop_confirm'), $goods_num);
            } else {
                $drop_confirm = lang('drop_confirm');
            }

            return $this->make_json_result(['attr_id' => $id, 'drop_confirm' => $drop_confirm]);
        }

        /**
         * 获得指定商品类型下的所有属性分组
         */
        if ($action === 'get_attr_groups') {
            $this->check_authz_json('attr_manage');

            $cat_id = intval($_GET['cat_id']);
            $groups = MainHelper::get_attr_groups($cat_id);

            return $this->make_json_result($groups);
        }
    }

    /**
     * 获取属性列表
     *
     * @return array
     */
    private function get_attrlist()
    {
        // 查询条件
        $filter = [];
        $filter['goods_type'] = empty($_REQUEST['goods_type']) ? 0 : intval($_REQUEST['goods_type']);
        $filter['sort_by'] = empty($_REQUEST['sort_by']) ? 'a.sort_order' : trim($_REQUEST['sort_by']);
        $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);

        $query = DB::table('goods_type_attribute as a');

        if (! empty($filter['goods_type'])) {
            $query->where('a.cat_id', $filter['goods_type']);
        }

        $filter['record_count'] = $query->count();

        // 分页大小
        $filter = MainHelper::page_and_size($filter);

        // 查询
        $res = $query->leftJoin('goods_type as t', 'a.cat_id', '=', 't.cat_id')
            ->select('a.*', 't.cat_name')
            ->orderBy($filter['sort_by'], $filter['sort_order'])
            ->offset($filter['start'])
            ->limit($filter['page_size'])
            ->get();

        $row = [];
        foreach ($res as $val) {
            $val = (array) $val;
            $val['attr_input_type_desc'] = lang('value_attr_input_type')[$val['attr_input_type']];
            $val['attr_values'] = str_replace("\n", ', ', $val['attr_values']);
            $row[] = $val;
        }

        $arr = ['item' => $row, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']];

        return $arr;
    }
}
