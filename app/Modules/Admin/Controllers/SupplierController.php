<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Helpers\BaseHelper;
use App\Modules\Admin\Helpers\MainHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;

class SupplierController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        define('SUPPLIERS_ACTION_LIST', 'delivery_view,back_view');
        /**
         * 供货商列表
         */
        if ($action === 'list') {
            $this->admin_priv('suppliers_manage');

            // 查询
            $result = $this->suppliers_list();

            $this->assign('ur_here', lang('suppliers_list')); // 当前导航
            $this->assign('action_link', ['href' => 'suppliers.php?act=add', 'text' => lang('add_suppliers')]);

            $this->assign('full_page', 1); // 翻页参数

            $this->assign('suppliers_list', $result['result']);
            $this->assign('filter', $result['filter']);
            $this->assign('record_count', $result['record_count']);
            $this->assign('page_count', $result['page_count']);

            return $this->display('suppliers_list');
        }

        /**
         * 排序、分页、查询
         */
        if ($action === 'query') {
            $this->check_authz_json('suppliers_manage');

            $result = $this->suppliers_list();

            $this->assign('suppliers_list', $result['result']);
            $this->assign('filter', $result['filter']);
            $this->assign('record_count', $result['record_count']);
            $this->assign('page_count', $result['page_count']);

            // 排序标记
            $sort_flag = MainHelper::sort_flag($result['filter']);
            $this->assign($sort_flag['tag'], $sort_flag['img']);

            return $this->make_json_result(
                $this->fetch('suppliers_list'),
                '',
                ['filter' => $result['filter'], 'page_count' => $result['page_count']]
            );
        }

        /**
         * 列表页编辑名称
         */
        if ($action === 'edit_suppliers_name') {
            $this->check_authz_json('suppliers_manage');

            $id = intval($_POST['id']);
            $name = BaseHelper::json_str_iconv(trim($_POST['val']));

            // 判断名称是否重复
            $exists = DB::table('supplier')->where('suppliers_name', $name)->where('suppliers_id', '<>', $id)->exists();
            if ($exists) {
                return $this->make_json_error(sprintf(lang('suppliers_name_exist'), $name));
            } else {
                // 保存供货商信息
                $result = DB::table('supplier')->where('suppliers_id', $id)->update(['suppliers_name' => $name]);
                if ($result) {
                    // 记日志
                    $this->admin_log($name, 'edit', 'suppliers');

                    $this->clear_cache_files();

                    return $this->make_json_result(stripslashes($name));
                } else {
                    return $this->make_json_result(sprintf(lang('agency_edit_fail'), $name));
                }
            }
        }

        /**
         * 删除供货商
         */
        if ($action === 'remove') {
            $this->check_authz_json('suppliers_manage');

            $id = intval($_REQUEST['id']);
            $suppliers = DB::table('supplier')->where('suppliers_id', $id)->first();
            $suppliers = $suppliers ? (array) $suppliers : [];

            if ($suppliers) {
                // 判断供货商是否存在订单
                $order_exists = DB::table('order_info as O')
                    ->join('order_goods as OG', 'O.order_id', '=', 'OG.order_id')
                    ->join('goods as G', 'OG.goods_id', '=', 'G.goods_id')
                    ->where('G.suppliers_id', $id)
                    ->count();
                if ($order_exists > 0) {
                    $url = 'suppliers.php?act=query&'.str_replace('act=remove', '', $_SERVER['QUERY_STRING']);

                    return response()->redirectTo($url);
                }

                // 判断供货商是否存在商品
                $goods_exists = DB::table('goods')->where('suppliers_id', $id)->count();
                if ($goods_exists > 0) {
                    $url = 'suppliers.php?act=query&'.str_replace('act=remove', '', $_SERVER['QUERY_STRING']);

                    return response()->redirectTo($url);
                }

                DB::table('supplier')->where('suppliers_id', $id)->delete();

                // 删除管理员、发货单关联、退货单关联和订单关联的供货商
                $table_array = ['admin_user', 'delivery_order', 'back_order'];
                foreach ($table_array as $value) {
                    DB::table($value)->where('suppliers_id', $id)->delete();
                }

                // 记日志
                $this->admin_log($suppliers['suppliers_name'], 'remove', 'suppliers');

                // 清除缓存
                $this->clear_cache_files();
            }

            $url = 'suppliers.php?act=query&'.str_replace('act=remove', '', $_SERVER['QUERY_STRING']);

            return response()->redirectTo($url);
        }

        /**
         * 修改供货商状态
         */
        if ($action === 'is_check') {
            $this->check_authz_json('suppliers_manage');

            $id = intval($_REQUEST['id']);
            $suppliers = DB::table('supplier')->select('suppliers_id', 'is_check')->where('suppliers_id', $id)->first();
            $suppliers = $suppliers ? (array) $suppliers : [];

            if ($suppliers) {
                $is_check = empty($suppliers['is_check']) ? 1 : 0;
                DB::table('supplier')->where('suppliers_id', $id)->update(['is_check' => $is_check]);
                $this->clear_cache_files();

                return $this->make_json_result($is_check);
            }
        }

        /**
         * 批量操作
         */
        if ($action === 'batch') {
            // 取得要操作的记录编号
            if (empty($_POST['checkboxes'])) {
                return $this->sys_msg(lang('no_record_selected'));
            } else {
                $this->admin_priv('suppliers_manage');

                $ids = $_POST['checkboxes'];

                if (isset($_POST['remove'])) {
                    $suppliers = DB::table('supplier')->whereIn('suppliers_id', $ids)->get()->toArray();
                    $suppliers = array_map(fn ($item) => (array) $item, $suppliers);

                    foreach ($suppliers as $key => $value) {
                        // 判断供货商是否存在订单
                        $order_exists = DB::table('order_info as O')
                            ->join('order_goods as OG', 'O.order_id', '=', 'OG.order_id')
                            ->join('goods as G', 'OG.goods_id', '=', 'G.goods_id')
                            ->where('G.suppliers_id', $value['suppliers_id'])
                            ->count();
                        if ($order_exists > 0) {
                            unset($suppliers[$key]);
                        }

                        // 判断供货商是否存在商品
                        $goods_exists = DB::table('goods')->where('suppliers_id', $value['suppliers_id'])->count();
                        if ($goods_exists > 0) {
                            unset($suppliers[$key]);
                        }
                    }
                    if (empty($suppliers)) {
                        return $this->sys_msg(lang('batch_drop_no'));
                    }

                    $ids = array_column($suppliers, 'suppliers_id');

                    DB::table('supplier')->whereIn('suppliers_id', $ids)->delete();

                    // 更新管理员、发货单关联、退货单关联和订单关联的供货商
                    $table_array = ['admin_user', 'delivery_order', 'back_order'];
                    foreach ($table_array as $value) {
                        DB::table($value)->whereIn('suppliers_id', $ids)->delete();
                    }

                    // 记日志
                    $suppliers_names = '';
                    foreach ($suppliers as $value) {
                        $suppliers_names .= $value['suppliers_name'].'|';
                    }
                    $this->admin_log($suppliers_names, 'remove', 'suppliers');

                    // 清除缓存
                    $this->clear_cache_files();

                    return $this->sys_msg(lang('batch_drop_ok'));
                }
            }
        }

        /**
         * 添加、编辑供货商
         */
        if (in_array($_REQUEST['act'], ['add', 'edit'])) {
            $this->admin_priv('suppliers_manage');

            if ($action === 'add') {
                $suppliers = [];

                // 取得所有管理员，
                // 标注哪些是该供货商的('this')，哪些是空闲的('free')，哪些是别的供货商的('other')
                // 排除是办事处的管理员
                $suppliers['admin_list'] = DB::table('admin_user')
                    ->select('user_id', 'user_name')
                    ->selectRaw("CASE WHEN suppliers_id = 0 THEN 'free' ELSE 'other' END AS type")
                    ->where('agency_id', 0)
                    ->where('action_list', '<>', 'all')
                    ->get()
                    ->map(fn ($item) => (array) $item)
                    ->all();

                $this->assign('ur_here', lang('add_suppliers'));
                $this->assign('action_link', ['href' => 'suppliers.php?act=list', 'text' => lang('suppliers_list')]);

                $this->assign('form_action', 'insert');
                $this->assign('suppliers', $suppliers);

                return $this->display('suppliers_info');
            }

            if ($action === 'edit') {
                $suppliers = [];

                // 取得供货商信息
                $id = $_REQUEST['id'];
                $suppliers = DB::table('supplier')->where('suppliers_id', $id)->first();
                $suppliers = $suppliers ? (array) $suppliers : [];
                if (empty($suppliers)) {
                    return $this->sys_msg('suppliers does not exist');
                }

                // 取得所有管理员，
                // 标注哪些是该供货商的('this')，哪些是空闲的('free')，哪些是别的供货商的('other')
                // 排除是办事处的管理员
                $suppliers['admin_list'] = DB::table('admin_user')
                    ->select('user_id', 'user_name')
                    ->selectRaw("CASE WHEN suppliers_id = ? THEN 'this' WHEN suppliers_id = 0 THEN 'free' ELSE 'other' END AS type", [$id])
                    ->where('agency_id', 0)
                    ->where('action_list', '<>', 'all')
                    ->get()
                    ->map(fn ($item) => (array) $item)
                    ->all();

                $this->assign('ur_here', lang('edit_suppliers'));
                $this->assign('action_link', ['href' => 'suppliers.php?act=list', 'text' => lang('suppliers_list')]);

                $this->assign('form_action', 'update');
                $this->assign('suppliers', $suppliers);

                return $this->display('suppliers_info');
            }
        }

        /**
         * 提交添加、编辑供货商
         */
        if (in_array($_REQUEST['act'], ['insert', 'update'])) {
            $this->admin_priv('suppliers_manage');

            if ($action === 'insert') {
                // 提交值
                $suppliers = [
                    'suppliers_name' => trim($_POST['suppliers_name']),
                    'suppliers_desc' => trim($_POST['suppliers_desc']),
                    'parent_id' => 0,
                ];

                // 判断名称是否重复
                if (DB::table('supplier')->where('suppliers_name', $suppliers['suppliers_name'])->exists()) {
                    return $this->sys_msg(lang('suppliers_name_exist'));
                }

                $suppliers['suppliers_id'] = DB::table('supplier')->insertGetId($suppliers);

                if (isset($_POST['admins'])) {
                    DB::table('admin_user')
                        ->whereIn('user_id', (array) $_POST['admins'])
                        ->update([
                            'suppliers_id' => $suppliers['suppliers_id'],
                            'action_list' => SUPPLIERS_ACTION_LIST,
                        ]);
                }

                // 记日志
                $this->admin_log($suppliers['suppliers_name'], 'add', 'suppliers');

                // 清除缓存
                $this->clear_cache_files();

                // 提示信息
                $links = [
                    ['href' => 'suppliers.php?act=add', 'text' => lang('continue_add_suppliers')],
                    ['href' => 'suppliers.php?act=list', 'text' => lang('back_suppliers_list')],
                ];

                return $this->sys_msg(lang('add_suppliers_ok'), 0, $links);
            }

            if ($action === 'update') {
                // 提交值
                $suppliers = ['id' => trim($_POST['id'])];

                $suppliers['new'] = [
                    'suppliers_name' => trim($_POST['suppliers_name']),
                    'suppliers_desc' => trim($_POST['suppliers_desc']),
                ];

                // 取得供货商信息
                $suppliers['old'] = DB::table('supplier')->where('suppliers_id', $suppliers['id'])->first();
                $suppliers['old'] = $suppliers['old'] ? (array) $suppliers['old'] : [];
                if (empty($suppliers['old']['suppliers_id'])) {
                    return $this->sys_msg('suppliers does not exist');
                }

                // 判断名称是否重复
                if (DB::table('supplier')->where('suppliers_name', $suppliers['new']['suppliers_name'])->where('suppliers_id', '<>', $suppliers['id'])->exists()) {
                    return $this->sys_msg(lang('suppliers_name_exist'));
                }

                // 保存供货商信息
                DB::table('supplier')->where('suppliers_id', $suppliers['id'])->update($suppliers['new']);

                // 清空供货商的管理员
                DB::table('admin_user')
                    ->where('suppliers_id', $suppliers['id'])
                    ->update([
                        'suppliers_id' => 0,
                        'action_list' => SUPPLIERS_ACTION_LIST,
                    ]);

                // 添加供货商的管理员
                if (isset($_POST['admins'])) {
                    DB::table('admin_user')
                        ->whereIn('user_id', (array) $_POST['admins'])
                        ->update(['suppliers_id' => $suppliers['old']['suppliers_id']]);
                }

                // 记日志
                $this->admin_log($suppliers['old']['suppliers_name'], 'edit', 'suppliers');

                // 清除缓存
                $this->clear_cache_files();

                // 提示信息
                $links[] = ['href' => 'suppliers.php?act=list', 'text' => lang('back_suppliers_list')];

                return $this->sys_msg(lang('edit_suppliers_ok'), 0, $links);
            }
        }
    }

    /**
     *  获取供应商列表信息
     *
     *
     * @return void
     */
    private function suppliers_list()
    {
        $result = MainHelper::get_filter();
        if ($result === false) {
            $aiax = isset($_GET['is_ajax']) ? $_GET['is_ajax'] : 0;

            // 过滤信息
            $filter['sort_by'] = empty($_REQUEST['sort_by']) ? 'suppliers_id' : trim($_REQUEST['sort_by']);
            $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'ASC' : trim($_REQUEST['sort_order']);

            $where = 'WHERE 1 ';

            // 分页大小
            $filter['page'] = empty($_REQUEST['page']) || (intval($_REQUEST['page']) <= 0) ? 1 : intval($_REQUEST['page']);

            if (isset($_REQUEST['page_size']) && intval($_REQUEST['page_size']) > 0) {
                $filter['page_size'] = intval($_REQUEST['page_size']);
            } else {
                $ecscpCookie = Cookie::get('ECSCP');
                $pageSize = is_array($ecscpCookie) ? ($ecscpCookie['page_size'] ?? '') : '';
                $filter['page_size'] = isset($pageSize) && intval($pageSize) > 0 ? intval($pageSize) : 15;
            }

            // 记录总数
            $filter['record_count'] = DB::table('supplier')->count();
            $filter['page_count'] = $filter['record_count'] > 0 ? ceil($filter['record_count'] / $filter['page_size']) : 1;

            // 查询
            $query = DB::table('supplier')
                ->select('suppliers_id', 'suppliers_name', 'suppliers_desc', 'is_check')
                ->orderBy($filter['sort_by'], $filter['sort_order'])
                ->offset(($filter['page'] - 1) * $filter['page_size'])
                ->limit($filter['page_size']);

            MainHelper::set_filter($filter, '');
        } else {
            $sql = $result['sql'];
            $filter = $result['filter'];
        }

        if (isset($query)) {
            $row = $query->get()->map(fn ($item) => (array) $item)->all();
        } else {
            $row = DB::select($sql);
            $row = array_map(fn ($item) => (array) $item, $row);
        }

        $arr = ['result' => $row, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']];

        return $arr;
    }
}
