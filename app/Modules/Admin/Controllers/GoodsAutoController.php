<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Helpers\BaseHelper;
use App\Helpers\TimeHelper;
use App\Modules\Admin\Helpers\MainHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GoodsAutoController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        $this->admin_priv('goods_auto');
        $this->assign('thisfile', 'goods_auto.php');
        if ($action === 'list') {
            $goodsdb = $this->get_auto_goods();
            $crons_enable = DB::table('shop_cron')->where('cron_code', 'auto_manage')->value('enable');
            $this->assign('crons_enable', $crons_enable);
            $this->assign('full_page', 1);
            $this->assign('ur_here', lang('goods_auto'));
            $this->assign('cfg_lang', cfg('lang'));
            $this->assign('goodsdb', $goodsdb['goodsdb']);
            $this->assign('filter', $goodsdb['filter']);
            $this->assign('record_count', $goodsdb['record_count']);
            $this->assign('page_count', $goodsdb['page_count']);

            return $this->display('goods_auto');
        }

        if ($action === 'query') {
            $goodsdb = $this->get_auto_goods();
            $this->assign('goodsdb', $goodsdb['goodsdb']);
            $this->assign('filter', $goodsdb['filter']);
            $this->assign('cfg_lang', cfg('lang'));
            $this->assign('record_count', $goodsdb['record_count']);
            $this->assign('page_count', $goodsdb['page_count']);

            $sort_flag = MainHelper::sort_flag($goodsdb['filter']);
            $this->assign($sort_flag['tag'], $sort_flag['img']);

            return $this->make_json_result($this->fetch('goods_auto'), '', ['filter' => $goodsdb['filter'], 'page_count' => $goodsdb['page_count']]);
        }

        if ($action === 'del') {
            $goods_id = (int) $_REQUEST['goods_id'];
            DB::table('shop_auto_manage')->where('item_id', $goods_id)->where('type', 'goods')->delete();
            $links[] = ['text' => lang('goods_auto'), 'href' => 'goods_auto.php?act=list'];

            return $this->sys_msg(lang('edit_ok'), 0, $links);
        }

        if ($action === 'edit_starttime') {
            $this->check_authz_json('goods_auto');

            if (! preg_match('/^\d{4}-\d{2}-\d{2}$/', trim($_POST['val']))) {
                return $this->make_json_error('');
            }

            $id = intval($_POST['id']);
            $time = TimeHelper::local_strtotime(trim($_POST['val']));
            if ($id <= 0 || $_POST['val'] === '0000-00-00' || $time <= 0) {
                return $this->make_json_error('');
            }

            DB::table('shop_auto_manage')->updateOrInsert(
                ['item_id' => $id, 'type' => 'goods'],
                ['starttime' => $time]
            );

            $this->clear_cache_files();

            return $this->make_json_result(stripslashes($_POST['val']), '', ['act' => 'goods_auto', 'id' => $id]);
        }

        if ($action === 'edit_endtime') {
            $this->check_authz_json('goods_auto');

            if (! preg_match('/^\d{4}-\d{2}-\d{2}$/', trim($_POST['val']))) {
                return $this->make_json_error('');
            }

            $id = intval($_POST['id']);
            $time = TimeHelper::local_strtotime(trim($_POST['val']));
            if ($id <= 0 || $_POST['val'] === '0000-00-00' || $time <= 0) {
                return $this->make_json_error('');
            }

            DB::table('shop_auto_manage')->updateOrInsert(
                ['item_id' => $id, 'type' => 'goods'],
                ['endtime' => $time]
            );

            $this->clear_cache_files();

            return $this->make_json_result(stripslashes($_POST['val']), '', ['act' => 'goods_auto', 'id' => $id]);
        }

        // 批量上架
        if ($action === 'batch_start') {
            $this->admin_priv('goods_auto');

            if (! isset($_POST['checkboxes']) || ! is_array($_POST['checkboxes'])) {
                return $this->sys_msg(lang('no_select_goods'), 1);
            }

            if ($_POST['date'] === '0000-00-00') {
                $_POST['date'] = 0;
            } else {
                $_POST['date'] = TimeHelper::local_strtotime(trim($_POST['date']));
            }

            foreach ($_POST['checkboxes'] as $id) {
                DB::table('shop_auto_manage')->updateOrInsert(
                    ['item_id' => $id, 'type' => 'goods'],
                    ['starttime' => $_POST['date']]
                );
            }

            $lnk[] = ['text' => lang('back_list'), 'href' => 'goods_auto.php?act=list'];

            return $this->sys_msg(lang('batch_start_succeed'), 0, $lnk);
        }

        // 批量下架
        if ($action === 'batch_end') {
            $this->admin_priv('goods_auto');

            if (! isset($_POST['checkboxes']) || ! is_array($_POST['checkboxes'])) {
                return $this->sys_msg(lang('no_select_goods'), 1);
            }

            if ($_POST['date'] === '0000-00-00') {
                $_POST['date'] = 0;
            } else {
                $_POST['date'] = TimeHelper::local_strtotime(trim($_POST['date']));
            }

            foreach ($_POST['checkboxes'] as $id) {
                DB::table('shop_auto_manage')->updateOrInsert(
                    ['item_id' => $id, 'type' => 'goods'],
                    ['endtime' => $_POST['date']]
                );
            }

            $lnk[] = ['text' => lang('back_list'), 'href' => 'goods_auto.php?act=list'];

            return $this->sys_msg(lang('batch_end_succeed'), 0, $lnk);
        }
    }

    private function get_auto_goods()
    {
        $result = MainHelper::get_filter();

        if ($result === false) {
            $filter['sort_by'] = empty($_REQUEST['sort_by']) ? 'last_update' : trim($_REQUEST['sort_by']);
            $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);
            $filter['goods_name'] = empty($_REQUEST['goods_name']) ? '' : trim($_REQUEST['goods_name']);

            $query = DB::table('goods as g');

            if (! empty($filter['goods_name'])) {
                $query->where('g.goods_name', 'like', '%'.BaseHelper::mysql_like_quote($filter['goods_name']).'%');
            }
            $query->where('g.is_delete', '<>', 1);

            $filter['record_count'] = $query->count();

            // 分页大小
            $filter = MainHelper::page_and_size($filter);

            // 查询
            $res = $query->leftJoin('shop_auto_manage as a', function ($join) {
                $join->on('g.goods_id', '=', 'a.item_id')->where('a.type', '=', 'goods');
            })
                ->select('g.*', 'a.starttime', 'a.endtime')
                ->orderBy('g.goods_id', 'ASC')
                ->orderBy($filter['sort_by'], $filter['sort_order'])
                ->offset($filter['start'])
                ->limit($filter['page_size'])
                ->get();

            MainHelper::set_filter($filter, '');
        } else {
            $filter = $result['filter'];
            $query = DB::table('goods as g');
            if (! empty($filter['goods_name'])) {
                $query->where('g.goods_name', 'like', '%'.BaseHelper::mysql_like_quote($filter['goods_name']).'%');
            }
            $query->where('g.is_delete', '<>', 1);

            $res = $query->leftJoin('shop_auto_manage as a', function ($join) {
                $join->on('g.goods_id', '=', 'a.item_id')->where('a.type', '=', 'goods');
            })
                ->select('g.*', 'a.starttime', 'a.endtime')
                ->orderBy('g.goods_id', 'ASC')
                ->orderBy($filter['sort_by'], $filter['sort_order'])
                ->offset($filter['start'])
                ->limit($filter['page_size'])
                ->get();
        }

        $goodsdb = [];
        foreach ($res as $rt) {
            $rt = (array) $rt;
            if (! empty($rt['starttime'])) {
                $rt['starttime'] = TimeHelper::local_date('Y-m-d', $rt['starttime']);
            }
            if (! empty($rt['endtime'])) {
                $rt['endtime'] = TimeHelper::local_date('Y-m-d', $rt['endtime']);
            }
            $goodsdb[] = $rt;
        }

        $arr = ['goodsdb' => $goodsdb, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']];

        return $arr;
    }
}
