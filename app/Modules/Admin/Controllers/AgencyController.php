<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Helpers\BaseHelper;
use App\Helpers\CommonHelper;
use App\Modules\Admin\Helpers\MainHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AgencyController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');

        /**
         * 办事处列表
         */
        if ($action === 'list') {
            $this->assign('ur_here', lang('agency_list'));
            $this->assign('action_link', ['text' => lang('add_agency'), 'href' => 'agency.php?act=add']);
            $this->assign('full_page', 1);

            $agency_list = $this->get_agencylist();
            $this->assign('agency_list', $agency_list['agency']);
            $this->assign('filter', $agency_list['filter']);
            $this->assign('record_count', $agency_list['record_count']);
            $this->assign('page_count', $agency_list['page_count']);

            // 排序标记
            $sort_flag = MainHelper::sort_flag($agency_list['filter']);
            $this->assign($sort_flag['tag'], $sort_flag['img']);

            return $this->display('agency_list');
        }

        /**
         * 排序、分页、查询
         */
        if ($action === 'query') {
            $agency_list = $this->get_agencylist();
            $this->assign('agency_list', $agency_list['agency']);
            $this->assign('filter', $agency_list['filter']);
            $this->assign('record_count', $agency_list['record_count']);
            $this->assign('page_count', $agency_list['page_count']);

            // 排序标记
            $sort_flag = MainHelper::sort_flag($agency_list['filter']);
            $this->assign($sort_flag['tag'], $sort_flag['img']);

            return $this->make_json_result(
                $this->fetch('agency_list'),
                '',
                ['filter' => $agency_list['filter'], 'page_count' => $agency_list['page_count']]
            );
        }

        /**
         * 列表页编辑名称
         */
        if ($action === 'edit_agency_name') {
            $this->check_authz_json('agency_manage');

            $id = intval($_POST['id']);
            $name = BaseHelper::json_str_iconv(trim($_POST['val']));

            // 检查名称是否重复
            if (DB::table('shop_agency')->where('agency_name', $name)->where('agency_id', '<>', $id)->exists()) {
                return $this->make_json_error(sprintf(lang('agency_name_exist'), $name));
            } else {
                if (DB::table('shop_agency')->where('agency_id', $id)->update(['agency_name' => $name])) {
                    $this->admin_log($name, 'edit', 'agency');
                    $this->clear_cache_files();

                    return $this->make_json_result(stripslashes($name));
                } else {
                    return $this->make_json_result(sprintf(lang('agency_edit_fail'), $name));
                }
            }
        }

        /**
         * 删除办事处
         */
        if ($action === 'remove') {
            $this->check_authz_json('agency_manage');

            $id = intval($_GET['id']);
            $name = DB::table('shop_agency')->where('agency_id', $id)->value('agency_name');
            DB::table('shop_agency')->where('agency_id', $id)->delete();

            // 更新管理员、配送地区、发货单、退货单和订单关联的办事处
            $table_array = ['admin_user', 'region', 'order_info', 'delivery_order', 'back_order'];
            foreach ($table_array as $value) {
                DB::table($value)->where('agency_id', $id)->update(['agency_id' => 0]);
            }

            // 记日志
            $this->admin_log($name, 'remove', 'agency');

            // 清除缓存
            $this->clear_cache_files();

            $url = 'agency.php?act=query&'.str_replace('act=remove', '', $_SERVER['QUERY_STRING']);

            return response()->redirectTo($url);
        }

        /**
         * 批量操作
         */
        if ($action === 'batch') {
            // 取得要操作的记录编号
            if (empty($_POST['checkboxes'])) {
                return $this->sys_msg(lang('no_record_selected'));
            } else {
                $this->admin_priv('agency_manage');

                $ids = $_POST['checkboxes'];

                if (isset($_POST['remove'])) {
                    // 删除记录
                    DB::table('shop_agency')->whereIn('agency_id', $ids)->delete();

                    // 更新管理员、配送地区、发货单、退货单和订单关联的办事处
                    $table_array = ['admin_user', 'region', 'order_info', 'delivery_order', 'back_order'];
                    foreach ($table_array as $value) {
                        DB::table($value)->whereIn('agency_id', $ids)->update(['agency_id' => 0]);
                    }

                    // 记日志
                    $this->admin_log('', 'batch_remove', 'agency');

                    // 清除缓存
                    $this->clear_cache_files();

                    return $this->sys_msg(lang('batch_drop_ok'));
                }
            }
        }

        /**
         * 添加、编辑办事处
         */
        if ($action === 'add' || $action === 'edit') {
            $this->admin_priv('agency_manage');

            // 是否添加
            $is_add = $action === 'add';
            $this->assign('form_action', $is_add ? 'insert' : 'update');

            // 初始化、取得办事处信息
            if ($is_add) {
                $agency = [
                    'agency_id' => 0,
                    'agency_name' => '',
                    'agency_desc' => '',
                    'region_list' => [],
                ];
            } else {
                if (empty($_GET['id'])) {
                    return $this->sys_msg('invalid param');
                }

                $id = $_GET['id'];
                $agency = DB::table('shop_agency')->where('agency_id', $id)->first();
                $agency = $agency ? (array) $agency : [];
                if (empty($agency)) {
                    return $this->sys_msg('agency does not exist');
                }

                // 关联的地区
                $agency['region_list'] = DB::table('shop_region')
                    ->select('region_id', 'region_name')
                    ->where('agency_id', $id)
                    ->get()
                    ->map(fn ($item) => (array) $item)
                    ->all();
            }

            // 取得所有管理员，标注哪些是该办事处的('this')，哪些是空闲的('free')，哪些是别的办事处的('other')
            $agency['admin_list'] = DB::table('admin_user')
                ->select('user_id', 'user_name')
                ->selectRaw("CASE WHEN agency_id = 0 THEN 'free' WHEN agency_id = ? THEN 'this' ELSE 'other' END AS type", [$agency['agency_id']])
                ->get()
                ->map(fn ($item) => (array) $item)
                ->all();

            $this->assign('agency', $agency);

            // 取得地区
            $country_list = CommonHelper::get_regions();
            $this->assign('countries', $country_list);

            if ($is_add) {
                $this->assign('ur_here', lang('add_agency'));
            } else {
                $this->assign('ur_here', lang('edit_agency'));
            }
            if ($is_add) {
                $href = 'agency.php?act=list';
            } else {
                $href = 'agency.php?act=list&'.MainHelper::list_link_postfix();
            }
            $this->assign('action_link', ['href' => $href, 'text' => lang('agency_list')]);

            return $this->display('agency_info');
        }

        /**
         * 提交添加、编辑办事处
         */
        if ($action === 'insert' || $action === 'update') {
            $this->admin_priv('agency_manage');

            // 是否添加
            $is_add = $action === 'insert';

            // 提交值
            $agency = [
                'agency_id' => intval($_POST['id']),
                'agency_name' => Str::limit($_POST['agency_name'], 255, ''),
                'agency_desc' => $_POST['agency_desc'],
            ];

            // 判断名称是否重复
            if (DB::table('shop_agency')->where('agency_name', $agency['agency_name'])->where('agency_id', '<>', $agency['agency_id'])->exists()) {
                return $this->sys_msg(lang('agency_name_exist'));
            }

            // 检查是否选择了地区
            if (empty($_POST['regions'])) {
                return $this->sys_msg(lang('no_regions'));
            }

            // 保存办事处信息
            if ($is_add) {
                unset($agency['agency_id']);
                $agency['agency_id'] = DB::table('shop_agency')->insertGetId($agency);
            } else {
                DB::table('shop_agency')
                    ->where('agency_id', $agency['agency_id'])
                    ->update($agency);
            }

            // 更新管理员表和地区表
            if (! $is_add) {
                DB::table('admin_user')->where('agency_id', $agency['agency_id'])->update(['agency_id' => 0]);
                DB::table('shop_region')->where('agency_id', $agency['agency_id'])->update(['agency_id' => 0]);
            }

            if (isset($_POST['admins'])) {
                DB::table('admin_user')->whereIn('user_id', $_POST['admins'])->update(['agency_id' => $agency['agency_id']]);
            }

            if (isset($_POST['regions'])) {
                DB::table('shop_region')->whereIn('region_id', $_POST['regions'])->update(['agency_id' => $agency['agency_id']]);
            }

            // 记日志
            if ($is_add) {
                $this->admin_log($agency['agency_name'], 'add', 'agency');
            } else {
                $this->admin_log($agency['agency_name'], 'edit', 'agency');
            }

            // 清除缓存
            $this->clear_cache_files();

            // 提示信息
            if ($is_add) {
                $links = [
                    ['href' => 'agency.php?act=add', 'text' => lang('continue_add_agency')],
                    ['href' => 'agency.php?act=list', 'text' => lang('back_agency_list')],
                ];

                return $this->sys_msg(lang('add_agency_ok'), 0, $links);
            } else {
                $links = [
                    ['href' => 'agency.php?act=list&'.MainHelper::list_link_postfix(), 'text' => lang('back_agency_list')],
                ];

                return $this->sys_msg(lang('edit_agency_ok'), 0, $links);
            }
        }
    }

    /**
     * 取得办事处列表
     *
     * @return array
     */
    private function get_agencylist()
    {
        $result = MainHelper::get_filter();
        if ($result === false) {
            // 初始化分页参数
            $filter = [];
            $filter['sort_by'] = empty($_REQUEST['sort_by']) ? 'agency_id' : trim($_REQUEST['sort_by']);
            $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);

            // 查询记录总数，计算分页数
            $filter['record_count'] = DB::table('shop_agency')->count();
            $filter = MainHelper::page_and_size($filter);

            // 查询记录
            $res = DB::table('shop_agency')
                ->orderBy($filter['sort_by'], $filter['sort_order'])
                ->offset($filter['start'])
                ->limit($filter['page_size'])
                ->get()
                ->map(fn ($item) => (array) $item)
                ->all();
        } else {
            $filter = $result['filter'];
            $res = DB::select($result['sql']);
            $res = array_map(fn ($item) => (array) $item, $res);
        }

        $arr = [];
        foreach ($res as $rows) {
            $arr[] = $rows;
        }

        return ['agency' => $arr, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']];
    }
}
