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

class SnatchController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        $exc = new Exchange(ecs()->table('goods_activity'), db(), 'act_id', 'act_name');

        /**
         * 添加活动
         */
        if ($action === 'add') {
            // 权限判断
            $this->admin_priv('snatch_manage');

            // 初始化信息
            $start_time = TimeHelper::local_date('Y-m-d H:i');
            $end_time = TimeHelper::local_date('Y-m-d H:i', strtotime('+1 week'));
            $snatch = ['start_price' => '1.00', 'end_price' => '800.00', 'max_price' => '0', 'cost_points' => '1', 'start_time' => $start_time, 'end_time' => $end_time, 'option' => '<option value="0">'.lang('make_option').'</option>'];

            $this->assign('snatch', $snatch);
            $this->assign('ur_here', lang('snatch_add'));
            $this->assign('action_link', ['text' => lang('02_snatch_list'), 'href' => 'snatch.php?act=list']);
            $this->assign('cat_list', CommonHelper::cat_list());
            $this->assign('brand_list', CommonHelper::get_brand_list());
            $this->assign('form_action', 'insert');

            return $this->display('snatch_info');
        }

        if ($action === 'insert') {
            // 权限判断
            $this->admin_priv('snatch_manage');

            // 检查商品是否存在
            $_POST['goods_name'] = DB::table('goods')->where('goods_id', (int) $_POST['goods_id'])->value('goods_name');
            if (empty($_POST['goods_name'])) {
                return $this->sys_msg(lang('no_goods'), 1);
            }

            if (
                DB::table('goods_activity')
                    ->where('act_type', GAT_SNATCH)
                    ->where('act_name', $_POST['snatch_name'])
                    ->count()
            ) {
                return $this->sys_msg(sprintf(lang('snatch_name_exist'), $_POST['snatch_name']), 1);
            }

            // 将时间转换成整数
            $_POST['start_time'] = TimeHelper::local_strtotime($_POST['start_time']);
            $_POST['end_time'] = TimeHelper::local_strtotime($_POST['end_time']);

            // 处理提交数据
            if (empty($_POST['start_price'])) {
                $_POST['start_price'] = 0;
            }
            if (empty($_POST['end_price'])) {
                $_POST['end_price'] = 0;
            }
            if (empty($_POST['max_price'])) {
                $_POST['max_price'] = 0;
            }
            if (empty($_POST['cost_points'])) {
                $_POST['cost_points'] = 0;
            }
            if (isset($_POST['product_id']) && empty($_POST['product_id'])) {
                $_POST['product_id'] = 0;
            }

            $info = ['start_price' => $_POST['start_price'], 'end_price' => $_POST['end_price'], 'max_price' => $_POST['max_price'], 'cost_points' => $_POST['cost_points']];

            // 插入数据
            $record = [
                'act_name' => $_POST['snatch_name'],
                'act_desc' => $_POST['desc'],
                'act_type' => GAT_SNATCH,
                'goods_id' => $_POST['goods_id'],
                'goods_name' => $_POST['goods_name'],
                'start_time' => $_POST['start_time'],
                'end_time' => $_POST['end_time'],
                'product_id' => $_POST['product_id'],
                'is_finished' => 0,
                'ext_info' => serialize($info),
            ];

            DB::table('goods_activity')->insert($record);

            $this->admin_log($_POST['snatch_name'], 'add', 'snatch');
            $link[] = ['text' => lang('back_list'), 'href' => 'snatch.php?act=list'];
            $link[] = ['text' => lang('continue_add'), 'href' => 'snatch.php?act=add'];

            return $this->sys_msg(lang('add_succeed'), 0, $link);
        }

        /**
         * 活动列表
         */
        if ($action === 'list') {
            $this->assign('ur_here', lang('02_snatch_list'));
            $this->assign('action_link', ['text' => lang('snatch_add'), 'href' => 'snatch.php?act=add']);

            $snatchs = $this->get_snatchlist();

            $this->assign('snatch_list', $snatchs['snatchs']);
            $this->assign('filter', $snatchs['filter']);
            $this->assign('record_count', $snatchs['record_count']);
            $this->assign('page_count', $snatchs['page_count']);

            $sort_flag = MainHelper::sort_flag($snatchs['filter']);
            $this->assign($sort_flag['tag'], $sort_flag['img']);

            $this->assign('full_page', 1);

            return $this->display('snatch_list');
        }

        /**
         * 查询、翻页、排序
         */
        if ($action === 'query') {
            $snatchs = $this->get_snatchlist();

            $this->assign('snatch_list', $snatchs['snatchs']);
            $this->assign('filter', $snatchs['filter']);
            $this->assign('record_count', $snatchs['record_count']);
            $this->assign('page_count', $snatchs['page_count']);

            $sort_flag = MainHelper::sort_flag($snatchs['filter']);
            $this->assign($sort_flag['tag'], $sort_flag['img']);

            return $this->make_json_result(
                $this->fetch('snatch_list'),
                '',
                ['filter' => $snatchs['filter'], 'page_count' => $snatchs['page_count']]
            );
        }

        /**
         * 编辑活动名称
         */
        if ($action === 'edit_snatch_name') {
            $this->check_authz_json('snatch_manage');

            $id = intval($_POST['id']);
            $val = BaseHelper::json_str_iconv(trim($_POST['val']));

            // 检查活动重名
            if (
                DB::table('goods_activity')
                    ->where('act_type', GAT_SNATCH)
                    ->where('act_name', $val)
                    ->where('act_id', '<>', $id)
                    ->count()
            ) {
                return $this->make_json_error(sprintf(lang('snatch_name_exist'), $val));
            }

            $exc->edit("act_name='$val'", $id);

            return $this->make_json_result(stripslashes($val));
        }

        /**
         * 删除指定的活动
         */
        if ($action === 'remove') {
            $this->check_authz_json('attr_manage');

            $id = intval($_GET['id']);

            $exc->drop($id);

            $url = 'snatch.php?act=query&'.str_replace('act=remove', '', $_SERVER['QUERY_STRING']);

            return response()->redirectTo($url);
        }

        /**
         * 编辑活动
         */
        if ($action === 'edit') {
            // 权限判断
            $this->admin_priv('snatch_manage');

            $snatch = $this->get_snatch_info($_REQUEST['id']);

            $snatch['option'] = '<option value="'.$snatch['goods_id'].'">'.$snatch['goods_name'].'</option>';
            $this->assign('snatch', $snatch);
            $this->assign('ur_here', lang('snatch_edit'));
            $this->assign('action_link', ['text' => lang('02_snatch_list'), 'href' => 'snatch.php?act=list&'.MainHelper::list_link_postfix()]);
            $this->assign('form_action', 'update');

            // 商品货品表
            $this->assign('good_products_select', CommonHelper::get_good_products_select($snatch['goods_id']));

            return $this->display('snatch_info');
        }

        if ($action === 'update') {
            // 权限判断
            $this->admin_priv('snatch_manage');

            // 将时间转换成整数
            $_POST['start_time'] = TimeHelper::local_strtotime($_POST['start_time']);
            $_POST['end_time'] = TimeHelper::local_strtotime($_POST['end_time']);

            // 处理提交数据
            if (empty($_POST['snatch_name'])) {
                $_POST['snatch_name'] = '';
            }
            if (empty($_POST['goods_id'])) {
                $_POST['goods_id'] = 0;
            } else {
                $_POST['goods_name'] = DB::table('goods')->where('goods_id', (int) $_POST['goods_id'])->value('goods_name');
            }
            if (empty($_POST['start_price'])) {
                $_POST['start_price'] = 0;
            }
            if (empty($_POST['end_price'])) {
                $_POST['end_price'] = 0;
            }
            if (empty($_POST['max_price'])) {
                $_POST['max_price'] = 0;
            }
            if (empty($_POST['cost_points'])) {
                $_POST['cost_points'] = 0;
            }
            if (isset($_POST['product_id']) && empty($_POST['product_id'])) {
                $_POST['product_id'] = 0;
            }

            // 检查活动重名
            if (
                DB::table('goods_activity')
                    ->where('act_type', GAT_SNATCH)
                    ->where('act_name', $_POST['snatch_name'])
                    ->where('act_id', '<>', (int) $_POST['id'])
                    ->count()
            ) {
                return $this->sys_msg(sprintf(lang('snatch_name_exist'), $_POST['snatch_name']), 1);
            }

            $info = ['start_price' => $_POST['start_price'], 'end_price' => $_POST['end_price'], 'max_price' => $_POST['max_price'], 'cost_points' => $_POST['cost_points']];

            // 更新数据
            $record = [
                'act_name' => $_POST['snatch_name'],
                'goods_id' => $_POST['goods_id'],
                'goods_name' => $_POST['goods_name'],
                'start_time' => $_POST['start_time'],
                'end_time' => $_POST['end_time'],
                'act_desc' => $_POST['desc'],
                'product_id' => $_POST['product_id'],
                'ext_info' => serialize($info),
            ];
            DB::table('goods_activity')
                ->where('act_id', (int) $_POST['id'])
                ->where('act_type', GAT_SNATCH)
                ->update($record);

            $this->admin_log($_POST['snatch_name'], 'edit', 'snatch');
            $link[] = ['text' => lang('back_list'), 'href' => 'snatch.php?act=list&'.MainHelper::list_link_postfix()];

            return $this->sys_msg(lang('edit_succeed'), 0, $link);
        }

        /**
         * 查看活动详情
         */
        if ($action === 'view') {
            // 权限判断
            $this->admin_priv('snatch_manage');

            $id = empty($_REQUEST['snatch_id']) ? 0 : intval($_REQUEST['snatch_id']);

            $bid_list = $this->get_snatch_detail();

            $this->assign('bid_list', $bid_list['bid']);
            $this->assign('filter', $bid_list['filter']);
            $this->assign('record_count', $bid_list['record_count']);
            $this->assign('page_count', $bid_list['page_count']);

            $sort_flag = MainHelper::sort_flag($bid_list['filter']);
            $this->assign($sort_flag['tag'], $sort_flag['img']);
            // 赋值
            $this->assign('info', $this->get_snatch_info($id));
            $this->assign('full_page', 1);
            $this->assign('result', CommonHelper::get_snatch_result($id));
            $this->assign('ur_here', lang('view_detail'));
            $this->assign('action_link', ['text' => lang('02_snatch_list'), 'href' => 'snatch.php?act=list']);

            return $this->display('snatch_view');
        }

        /**
         * 排序、翻页活动详情
         */
        if ($action === 'query_bid') {
            $bid_list = $this->get_snatch_detail();

            $this->assign('bid_list', $bid_list['bid']);
            $this->assign('filter', $bid_list['filter']);
            $this->assign('record_count', $bid_list['record_count']);
            $this->assign('page_count', $bid_list['page_count']);

            $sort_flag = MainHelper::sort_flag($bid_list['filter']);
            $this->assign($sort_flag['tag'], $sort_flag['img']);

            return $this->make_json_result(
                $this->fetch('snatch_view'),
                '',
                ['filter' => $bid_list['filter'], 'page_count' => $bid_list['page_count']]
            );
        }

        /**
         * 搜索商品
         */
        if ($action === 'search_goods') {
            $filters = json_decode($_GET['JSON']);

            $arr['goods'] = MainHelper::get_goods_list($filters);

            if (! empty($arr['goods'][0]['goods_id'])) {
                $arr['products'] = CommonHelper::get_good_products($arr['goods'][0]['goods_id']);
            }

            return $this->make_json_result($arr);
        }

        /**
         * 搜索货品
         */
        if ($action === 'search_products') {
            $filters = json_decode($_GET['JSON']);

            if (! empty($filters->goods_id)) {
                $arr['products'] = CommonHelper::get_good_products($filters->goods_id);
            }

            return $this->make_json_result($arr);
        }
    }

    /**
     * 获取活动列表
     *
     *
     * @return void
     */
    private function get_snatchlist()
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
                ->where('act_type', GAT_SNATCH)
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
            ->select('act_id', 'act_name as snatch_name', 'goods_name', 'start_time', 'end_time', 'is_finished', 'ext_info', 'product_id')
            ->where('act_type', GAT_SNATCH)
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

        $arr = ['snatchs' => $row, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']];

        return $arr;
    }

    /**
     * 获取指定id snatch 的信息
     *
     * @param  int  $id  snatch_id
     * @return array array(snatch_id, snatch_name, goods_id,start_time, end_time, min_price, integral)
     */
    private function get_snatch_info($id)
    {
        $snatch = (array) DB::table('goods_activity')
            ->where('act_id', $id)
            ->where('act_type', GAT_SNATCH)
            ->select('act_id', 'act_name as snatch_name', 'goods_id', 'product_id', 'goods_name', 'start_time', 'end_time', 'act_desc', 'ext_info')
            ->first();

        // 将时间转成可阅读格式
        $snatch['start_time'] = TimeHelper::local_date('Y-m-d H:i', $snatch['start_time']);
        $snatch['end_time'] = TimeHelper::local_date('Y-m-d H:i', $snatch['end_time']);
        $row = unserialize($snatch['ext_info']);
        unset($snatch['ext_info']);
        if ($row) {
            foreach ($row as $key => $val) {
                $snatch[$key] = $val;
            }
        }

        return $snatch;
    }

    /**
     * 返回活动详细列表
     *
     *
     * @return array
     */
    private function get_snatch_detail()
    {
        $filter['snatch_id'] = empty($_REQUEST['snatch_id']) ? 0 : intval($_REQUEST['snatch_id']);
        $filter['sort_by'] = empty($_REQUEST['sort_by']) ? 'bid_time' : trim($_REQUEST['sort_by']);
        $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);

        $where = empty($filter['snatch_id']) ? '' : " WHERE snatch_id='$filter[snatch_id]'";

        // 获得记录总数以及总页数
        $filter['record_count'] = DB::table('activity_snatch')
            ->when(! empty($filter['snatch_id']), fn ($q) => $q->where('snatch_id', $filter['snatch_id']))
            ->count();

        $filter = MainHelper::page_and_size($filter);

        // 获得活动数据
        $row = DB::table('activity_snatch as s')
            ->leftJoin('user as u', 's.user_id', '=', 'u.user_id')
            ->when(! empty($filter['snatch_id']), fn ($q) => $q->where('s.snatch_id', $filter['snatch_id']))
            ->select('s.log_id', 'u.user_name', 's.bid_price', 's.bid_time')
            ->orderByRaw("{$filter['sort_by']} {$filter['sort_order']}")
            ->offset($filter['start'])
            ->limit($filter['page_size'])
            ->get()
            ->map(fn ($r) => (array) $r)
            ->all();

        foreach ($row as $key => $val) {
            $row[$key]['bid_time'] = date(cfg('time_format'), $val['bid_time']);
        }

        $arr = ['bid' => $row, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']];

        return $arr;
    }
}
