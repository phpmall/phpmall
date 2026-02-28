<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Helpers\TimeHelper;
use App\Modules\Admin\Helpers\MainHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArticleAutoController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        $this->admin_priv('article_auto');
        $this->assign('thisfile', 'article_auto.php');
        if ($action === 'list') {
            $goodsdb = $this->get_auto_goods();
            $crons_enable = DB::table('shop_cron')->where('cron_code', 'ipdel')->value('enable');
            $this->assign('crons_enable', $crons_enable);
            $this->assign('full_page', 1);
            $this->assign('ur_here', lang('article_auto'));
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
            $this->assign('record_count', $goodsdb['record_count']);
            $this->assign('page_count', $goodsdb['page_count']);

            $sort_flag = MainHelper::sort_flag($goodsdb['filter']);
            $this->assign($sort_flag['tag'], $sort_flag['img']);

            return $this->make_json_result($this->fetch('goods_auto'), '', ['filter' => $goodsdb['filter'], 'page_count' => $goodsdb['page_count']]);
        }

        if ($action === 'del') {
            $goods_id = (int) $_REQUEST['goods_id'];
            DB::table('shop_auto_manage')
                ->where('item_id', $goods_id)
                ->where('type', 'article')
                ->delete();
            $links[] = ['text' => lang('article_auto'), 'href' => 'article_auto.php?act=list'];

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
                ['item_id' => $id, 'type' => 'article'],
                ['starttime' => $time]
            );

            $this->clear_cache_files();

            return $this->make_json_result(stripslashes($_POST['val']), '', ['act' => 'article_auto', 'id' => $id]);
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
                ['item_id' => $id, 'type' => 'article'],
                ['endtime' => $time]
            );

            $this->clear_cache_files();

            return $this->make_json_result(stripslashes($_POST['val']), '', ['act' => 'article_auto', 'id' => $id]);
        }

        // 批量发布
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
                    ['item_id' => $id, 'type' => 'article'],
                    ['starttime' => $_POST['date']]
                );
            }

            $lnk[] = ['text' => lang('back_list'), 'href' => 'article_auto.php?act=list'];

            return $this->sys_msg(lang('batch_start_succeed'), 0, $lnk);
        }

        // 批量取消发布
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
                    ['item_id' => $id, 'type' => 'article'],
                    ['endtime' => $_POST['date']]
                );
            }

            $lnk[] = ['text' => lang('back_list'), 'href' => 'article_auto.php?act=list'];

            return $this->sys_msg(lang('batch_end_succeed'), 0, $lnk);
        }
    }

    private function get_auto_goods()
    {
        $query = DB::table('article as g');

        if (! empty($_POST['goods_name'])) {
            $goods_name = trim($_POST['goods_name']);
            $query->where('g.title', 'like', '%'.$goods_name.'%');
            $filter['goods_name'] = $goods_name;
        }

        $filter['record_count'] = $query->count();
        $filter = MainHelper::page_and_size($filter);

        $res = $query->leftJoin('shop_auto_manage as a', function ($join) {
            $join->on('g.article_id', '=', 'a.item_id')
                ->where('a.type', 'article');
        })
            ->select('g.*', 'a.starttime', 'a.endtime')
            ->orderBy('g.add_time', 'DESC')
            ->offset($filter['start'])
            ->limit($filter['page_size'])
            ->get();

        $goodsdb = [];
        foreach ($res as $rt) {
            $rt = (array) $rt;
            if (! empty($rt['starttime'])) {
                $rt['starttime'] = TimeHelper::local_date('Y-m-d', $rt['starttime']);
            }
            if (! empty($rt['endtime'])) {
                $rt['endtime'] = TimeHelper::local_date('Y-m-d', $rt['endtime']);
            }
            $rt['goods_id'] = $rt['article_id'];
            $rt['goods_name'] = $rt['title'];
            $goodsdb[] = $rt;
        }
        $arr = ['goodsdb' => $goodsdb, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']];

        return $arr;
    }
}
