<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Helpers\BaseHelper;
use App\Helpers\CommonHelper;
use App\Helpers\TimeHelper;
use App\Modules\Admin\Helpers\MainHelper;
use App\Modules\Admin\Libraries\Exchange;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class PackageController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        $exc = new Exchange(ecs()->table('goods_activity'), db(), 'act_id', 'act_name'); // Exchange uses legacy db()

        /**
         * 添加活动
         */
        if ($action === 'add') {
            // 权限判断
            $this->admin_priv('package_manage');

            // 组合商品
            $group_goods_list = [];
            DB::table('activity_package')
                ->where('package_id', 0)
                ->where('admin_id', Session::get('admin_id'))
                ->delete();

            // 初始化信息
            $start_time = TimeHelper::local_date('Y-m-d H:i');
            $end_time = TimeHelper::local_date('Y-m-d H:i', strtotime('+1 month'));
            $package = ['package_price' => '', 'start_time' => $start_time, 'end_time' => $end_time];

            $this->assign('package', $package);
            $this->assign('ur_here', lang('package_add'));
            $this->assign('action_link', ['text' => lang('14_package_list'), 'href' => 'package.php?act=list']);
            $this->assign('cat_list', CommonHelper::cat_list());
            $this->assign('brand_list', CommonHelper::get_brand_list());
            $this->assign('form_action', 'insert');

            return $this->display('package_info');
        }

        if ($action === 'insert') {
            // 权限判断
            $this->admin_priv('package_manage');

            if (
                DB::table('goods_activity')
                    ->where('act_type', GAT_PACKAGE)
                    ->where('act_name', $_POST['package_name'])
                    ->count()
            ) {
                return $this->sys_msg(sprintf(lang('package_exist'), $_POST['package_name']), 1);
            }

            // 将时间转换成整数
            $_POST['start_time'] = TimeHelper::local_strtotime($_POST['start_time']);
            $_POST['end_time'] = TimeHelper::local_strtotime($_POST['end_time']);

            // 处理提交数据
            if (empty($_POST['package_price'])) {
                $_POST['package_price'] = 0;
            }

            $info = ['package_price' => $_POST['package_price']];

            // 插入数据
            $record = [
                'act_name' => $_POST['package_name'],
                'act_desc' => $_POST['desc'],
                'act_type' => GAT_PACKAGE,
                'start_time' => $_POST['start_time'],
                'end_time' => $_POST['end_time'],
                'is_finished' => 0,
                'ext_info' => serialize($info),
            ];

            $package_id = DB::table('goods_activity')->insertGetId($record);

            $this->handle_packagep_goods($package_id);

            $this->admin_log($_POST['package_name'], 'add', 'package');
            $link[] = ['text' => lang('back_list'), 'href' => 'package.php?act=list'];
            $link[] = ['text' => lang('continue_add'), 'href' => 'package.php?act=add'];

            return $this->sys_msg(lang('add_succeed'), 0, $link);
        }

        /**
         * 编辑活动
         */
        if ($action === 'edit') {
            // 权限判断
            $this->admin_priv('package_manage');

            $package = CommonHelper::get_package_info($_REQUEST['id']);
            $package_goods_list = CommonHelper::get_package_goods($_REQUEST['id']); // 礼包商品

            $this->assign('package', $package);
            $this->assign('ur_here', lang('package_edit'));
            $this->assign('action_link', ['text' => lang('14_package_list'), 'href' => 'package.php?act=list&'.MainHelper::list_link_postfix()]);
            $this->assign('cat_list', CommonHelper::cat_list());
            $this->assign('brand_list', CommonHelper::get_brand_list());
            $this->assign('form_action', 'update');
            $this->assign('package_goods_list', $package_goods_list);

            return $this->display('package_info');
        }

        if ($action === 'update') {
            // 权限判断
            $this->admin_priv('package_manage');

            // 将时间转换成整数
            $_POST['start_time'] = TimeHelper::local_strtotime($_POST['start_time']);
            $_POST['end_time'] = TimeHelper::local_strtotime($_POST['end_time']);

            // 处理提交数据
            if (empty($_POST['package_price'])) {
                $_POST['package_price'] = 0;
            }

            // 检查活动重名
            if (
                DB::table('goods_activity')
                    ->where('act_type', GAT_PACKAGE)
                    ->where('act_name', $_POST['package_name'])
                    ->where('act_id', '<>', (int) $_POST['id'])
                    ->count()
            ) {
                return $this->sys_msg(sprintf(lang('package_exist'), $_POST['package_name']), 1);
            }

            $info = ['package_price' => $_POST['package_price']];

            // 更新数据
            $record = [
                'act_name' => $_POST['package_name'],
                'start_time' => $_POST['start_time'],
                'end_time' => $_POST['end_time'],
                'act_desc' => $_POST['desc'],
                'ext_info' => serialize($info),
            ];
            DB::table('goods_activity')
                ->where('act_id', (int) $_POST['id'])
                ->where('act_type', GAT_PACKAGE)
                ->update($record);

            $this->admin_log($_POST['package_name'], 'edit', 'package');
            $link[] = ['text' => lang('back_list'), 'href' => 'package.php?act=list&'.MainHelper::list_link_postfix()];

            return $this->sys_msg(lang('edit_succeed'), 0, $link);
        }

        /**
         * 删除指定的活动
         */
        if ($action === 'remove') {
            $this->check_authz_json('package_manage');

            $id = intval($_GET['id']);

            $exc->drop($id);

            DB::table('activity_package')->where('package_id', $id)->delete();

            $url = 'package.php?act=query&'.str_replace('act=remove', '', $_SERVER['QUERY_STRING']);

            return response()->redirectTo($url);
        }

        /**
         * 活动列表
         */
        if ($action === 'list') {
            $this->assign('ur_here', lang('14_package_list'));
            $this->assign('action_link', ['text' => lang('package_add'), 'href' => 'package.php?act=add']);

            $packages = $this->get_packagelist();

            $this->assign('package_list', $packages['packages']);
            $this->assign('filter', $packages['filter']);
            $this->assign('record_count', $packages['record_count']);
            $this->assign('page_count', $packages['page_count']);

            $sort_flag = MainHelper::sort_flag($packages['filter']);
            $this->assign($sort_flag['tag'], $sort_flag['img']);

            $this->assign('full_page', 1);

            return $this->display('package_list');
        }

        /**
         * 查询、翻页、排序
         */
        if ($action === 'query') {
            $packages = $this->get_packagelist();

            $this->assign('package_list', $packages['packages']);
            $this->assign('filter', $packages['filter']);
            $this->assign('record_count', $packages['record_count']);
            $this->assign('page_count', $packages['page_count']);

            $sort_flag = MainHelper::sort_flag($packages['filter']);
            $this->assign($sort_flag['tag'], $sort_flag['img']);

            return $this->make_json_result(
                $this->fetch('package_list'),
                '',
                ['filter' => $packages['filter'], 'page_count' => $packages['page_count']]
            );
        }

        /**
         * 编辑活动名称
         */
        if ($action === 'edit_package_name') {
            $this->check_authz_json('package_manage');

            $id = intval($_POST['id']);
            $val = BaseHelper::json_str_iconv(trim($_POST['val']));

            // 检查活动重名
            if (
                DB::table('goods_activity')
                    ->where('act_type', GAT_PACKAGE)
                    ->where('act_name', $val)
                    ->where('act_id', '<>', $id)
                    ->count()
            ) {
                return $this->make_json_error(sprintf(lang('package_exist'), $val));
            }

            $exc->edit("act_name='$val'", $id);

            return $this->make_json_result(stripslashes($val));
        }

        /**
         * 搜索商品
         */
        if ($action === 'search_goods') {
            $filters = json_decode($_GET['JSON']);

            $arr = MainHelper::get_goods_list($filters);

            $opt = [];
            foreach ($arr as $key => $val) {
                $opt[$key] = [
                    'value' => $val['goods_id'],
                    'text' => $val['goods_name'],
                    'data' => $val['shop_price'],
                ];

                $opt[$key]['products'] = CommonHelper::get_good_products($val['goods_id']);
            }

            return $this->make_json_result($opt);
        }

        /**
         * 增加一个商品
         */
        if ($action === 'add_package_goods') {
            $this->check_authz_json('package_manage');

            $fittings = json_decode($_GET['add_ids']);
            $arguments = json_decode($_GET['JSON']);
            $package_id = $arguments[0];
            $number = $arguments[1];

            foreach ($fittings as $val) {
                $val_array = explode('_', $val);
                if (! isset($val_array[1]) || $val_array[1] <= 0) {
                    $val_array[1] = 0;
                }

                DB::table('activity_package')->insertOrIgnore([
                    'package_id' => $package_id,
                    'goods_id' => (int) $val_array[0],
                    'product_id' => (int) $val_array[1],
                    'goods_number' => $number,
                    'admin_id' => Session::get('admin_id'),
                ]);
            }

            $arr = CommonHelper::get_package_goods($package_id);
            $opt = [];

            foreach ($arr as $val) {
                $opt[] = [
                    'value' => $val['g_p'],
                    'text' => $val['goods_name'],
                    'data' => '',
                ];
            }

            $this->clear_cache_files();

            return $this->make_json_result($opt);
        }

        /**
         * 删除一个商品
         */
        if ($action === 'drop_package_goods') {
            $this->check_authz_json('package_manage');

            $fittings = json_decode($_GET['drop_ids']);
            $arguments = json_decode($_GET['JSON']);
            $package_id = $arguments[0];

            $goods = [];
            $g_p = [];
            foreach ($fittings as $val) {
                $val_array = explode('_', $val);
                if (isset($val_array[1]) && $val_array[1] > 0) {
                    $g_p['product_id'][] = $val_array[1];
                    $g_p['goods_id'][] = $val_array[0];
                } else {
                    $goods[] = $val_array[0];
                }
            }

            if (! empty($goods)) {
                $q = DB::table('activity_package')
                    ->where('package_id', $package_id)
                    ->whereIn('goods_id', $goods);
                if ($package_id === 0) {
                    $q->where('admin_id', Session::get('admin_id'));
                }
                $q->delete();
            }

            if (! empty($g_p)) {
                $q = DB::table('activity_package')
                    ->where('package_id', $package_id)
                    ->whereIn('goods_id', $g_p['goods_id'])
                    ->whereIn('product_id', $g_p['product_id']);
                if ($package_id === 0) {
                    $q->where('admin_id', Session::get('admin_id'));
                }
                $q->delete();
            }

            $arr = CommonHelper::get_package_goods($package_id);
            $opt = [];

            foreach ($arr as $val) {
                $opt[] = [
                    'value' => $val['goods_id'],
                    'text' => $val['goods_name'],
                    'data' => '',
                ];
            }

            $this->clear_cache_files();

            return $this->make_json_result($opt);
        }
    }

    /**
     * 获取活动列表
     *
     *
     * @return void
     */
    private function get_packagelist()
    {
        $result = MainHelper::get_filter();
        if ($result === false) {
            // 查询条件
            $filter['keywords'] = empty($_REQUEST['keywords']) ? '' : trim($_REQUEST['keywords']);
            if (isset($_REQUEST['is_ajax']) && $_REQUEST['is_ajax'] === 1) {
                $filter['keywords'] = BaseHelper::json_str_iconv($filter['keywords']);
            }
            $filter['sort_by'] = empty($_REQUEST['sort_by']) ? 'act_id' : trim($_REQUEST['sort_by']);
            $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);

            $where = (! empty($filter['keywords'])) ? " AND act_name like '%".BaseHelper::mysql_like_quote($filter['keywords'])."%'" : '';

            $filter['record_count'] = DB::table('goods_activity')
                ->where('act_type', GAT_PACKAGE)
                ->when(! empty($filter['keywords']), fn ($q) => $q->where('act_name', 'like', '%'.BaseHelper::mysql_like_quote($filter['keywords']).'%'))
                ->count();

            $filter = MainHelper::page_and_size($filter);

            // 获活动数据
            $filter['keywords'] = stripslashes($filter['keywords']);
            MainHelper::set_filter($filter, '');
        } else {
            $sql = $result['sql'];
            $filter = $result['filter'];
        }

        $row = DB::table('goods_activity')
            ->select('act_id', 'act_name as package_name', 'start_time', 'end_time', 'is_finished', 'ext_info')
            ->where('act_type', GAT_PACKAGE)
            ->orderBy($filter['sort_by'], $filter['sort_order'])
            ->offset($filter['start'])
            ->limit($filter['page_size'])
            ->get();

        foreach ($row as $key => $val) {
            $val = (array) $val;
            $row[$key] = $val;
            $row[$key]['start_time'] = TimeHelper::local_date(cfg('time_format'), $val['start_time']);
            $row[$key]['end_time'] = TimeHelper::local_date(cfg('time_format'), $val['end_time']);
            $info = unserialize($row[$key]['ext_info']);
            unset($row[$key]['ext_info']);
            if ($info) {
                foreach ($info as $info_key => $info_val) {
                    $row[$key][$info_key] = $info_val;
                }
            }
        }

        $arr = ['packages' => $row, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']];

        return $arr;
    }

    /**
     * 保存某礼包的商品
     *
     * @param  int  $package_id
     * @return void
     */
    private function handle_packagep_goods($package_id)
    {
        DB::table('activity_package')
            ->where('package_id', 0)
            ->where('admin_id', Session::get('admin_id'))
            ->update(['package_id' => $package_id]);
    }
}
