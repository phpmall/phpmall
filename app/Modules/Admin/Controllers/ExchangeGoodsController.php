<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Helpers\BaseHelper;
use App\Helpers\TimeHelper;
use App\Libraries\Image;
use App\Modules\Admin\Helpers\MainHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExchangeGoodsController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        // 初始化数据
        $image = new Image;

        /**
         * 商品列表
         */
        if ($action === 'list') {
            // 权限判断
            $this->admin_priv('exchange_goods');

            // 取得过滤条件
            $filter = [];
            $this->assign('ur_here', lang('15_exchange_goods_list'));
            $this->assign('action_link', ['text' => lang('exchange_goods_add'), 'href' => 'exchange_goods.php?act=add']);
            $this->assign('full_page', 1);
            $this->assign('filter', $filter);

            $goods_list = $this->get_exchange_goodslist();

            $this->assign('goods_list', $goods_list['arr']);
            $this->assign('filter', $goods_list['filter']);
            $this->assign('record_count', $goods_list['record_count']);
            $this->assign('page_count', $goods_list['page_count']);

            $sort_flag = MainHelper::sort_flag($goods_list['filter']);
            $this->assign($sort_flag['tag'], $sort_flag['img']);

            return $this->display('exchange_goods_list');
        }

        /**
         * 翻页，排序
         */
        if ($action === 'query') {
            $this->check_authz_json('exchange_goods');

            $goods_list = $this->get_exchange_goodslist();

            $this->assign('goods_list', $goods_list['arr']);
            $this->assign('filter', $goods_list['filter']);
            $this->assign('record_count', $goods_list['record_count']);
            $this->assign('page_count', $goods_list['page_count']);

            $sort_flag = MainHelper::sort_flag($goods_list['filter']);
            $this->assign($sort_flag['tag'], $sort_flag['img']);

            return $this->make_json_result(
                $this->fetch('exchange_goods_list'),
                '',
                ['filter' => $goods_list['filter'], 'page_count' => $goods_list['page_count']]
            );
        }

        /**
         * 添加商品
         */
        if ($action === 'add') {
            // 权限判断
            $this->admin_priv('exchange_goods');

            // 初始化
            $goods = [];
            $goods['is_exchange'] = 1;
            $goods['is_hot'] = 0;
            $goods['option'] = '<option value="0">'.lang('make_option').'</option>';

            $this->assign('goods', $goods);
            $this->assign('ur_here', lang('exchange_goods_add'));
            $this->assign('action_link', ['text' => lang('15_exchange_goods_list'), 'href' => 'exchange_goods.php?act=list']);
            $this->assign('form_action', 'insert');

            return $this->display('exchange_goods_info');
        }

        /**
         * 添加商品
         */
        if ($action === 'insert') {
            // 权限判断
            $this->admin_priv('exchange_goods');

            // 检查是否重复
            if (DB::table('activity_exchange')->where('goods_id', $_POST['goods_id'])->exists()) {
                return $this->sys_msg(lang('goods_exist'), 1);
            }

            // 插入数据
            $add_time = TimeHelper::gmtime();
            if (empty($_POST['goods_id'])) {
                $_POST['goods_id'] = 0;
            }
            DB::table('activity_exchange')->insert([
                'goods_id' => $_POST['goods_id'],
                'exchange_integral' => $_POST['exchange_integral'],
                'is_exchange' => $_POST['is_exchange'],
                'is_hot' => $_POST['is_hot'],
            ]);

            $link[0]['text'] = lang('continue_add');
            $link[0]['href'] = 'exchange_goods.php?act=add';

            $link[1]['text'] = lang('back_list');
            $link[1]['href'] = 'exchange_goods.php?act=list';

            $this->admin_log($_POST['goods_id'], 'add', 'exchange_goods');

            $this->clear_cache_files(); // 清除相关的缓存文件

            return $this->sys_msg(lang('articleadd_succeed'), 0, $link);
        }

        /**
         * 编辑
         */
        if ($action === 'edit') {
            // 权限判断
            $this->admin_priv('exchange_goods');

            // 取商品数据
            $goods = DB::table('activity_exchange as eg')
                ->leftJoin('goods as g', 'g.goods_id', '=', 'eg.goods_id')
                ->where('eg.goods_id', $_REQUEST['id'])
                ->select('eg.goods_id', 'eg.exchange_integral', 'eg.is_exchange', 'eg.is_hot', 'g.goods_name')
                ->first();
            $goods = $goods ? (array) $goods : [];

            $goods['option'] = '<option value="'.$goods['goods_id'].'">'.$goods['goods_name'].'</option>';

            $this->assign('goods', $goods);
            $this->assign('ur_here', lang('exchange_goods_add'));
            $this->assign('action_link', ['text' => lang('15_exchange_goods_list'), 'href' => 'exchange_goods.php?act=list&'.MainHelper::list_link_postfix()]);
            $this->assign('form_action', 'update');

            return $this->display('exchange_goods_info');
        }

        /**
         * 编辑
         */
        if ($action === 'update') {
            // 权限判断
            $this->admin_priv('exchange_goods');

            if (empty($_POST['goods_id'])) {
                $_POST['goods_id'] = 0;
            }

            if (
                DB::table('activity_exchange')->where('goods_id', $_POST['goods_id'])->update([
                    'exchange_integral' => $_POST['exchange_integral'],
                    'is_exchange' => $_POST['is_exchange'],
                    'is_hot' => $_POST['is_hot'],
                ])
            ) {
                $link[0]['text'] = lang('back_list');
                $link[0]['href'] = 'exchange_goods.php?act=list&'.MainHelper::list_link_postfix();

                $this->admin_log($_POST['goods_id'], 'edit', 'exchange_goods');

                $this->clear_cache_files();

                return $this->sys_msg(lang('articleedit_succeed'), 0, $link);
            } else {
                return $this->make_json_error('DB error');
            }
        }

        /**
         * 编辑使用积分值
         */
        if ($action === 'edit_exchange_integral') {
            $this->check_authz_json('exchange_goods');

            $id = intval($_POST['id']);
            $exchange_integral = floatval($_POST['val']);

            // 检查文章标题是否重复
            if ($exchange_integral < 0 || $exchange_integral === 0 && $_POST['val'] != "$goods_price") {
                return $this->make_json_error(lang('exchange_integral_invalid'));
            } else {
                if (DB::table('activity_exchange')->where('goods_id', $id)->update(['exchange_integral' => $exchange_integral])) {
                    $this->clear_cache_files();
                    $this->admin_log($id, 'edit', 'exchange_goods');

                    return $this->make_json_result(stripslashes((string) $exchange_integral));
                } else {
                    return $this->make_json_error('DB error');
                }
            }
        }

        /**
         * 切换是否兑换
         */
        if ($action === 'toggle_exchange') {
            $this->check_authz_json('exchange_goods');

            $id = intval($_POST['id']);
            $val = intval($_POST['val']);

            DB::table('activity_exchange')->where('goods_id', $id)->update(['is_exchange' => $val]);
            $this->clear_cache_files();

            return $this->make_json_result($val);
        }

        /**
         * 切换是否兑换
         */
        if ($action === 'toggle_hot') {
            $this->check_authz_json('exchange_goods');

            $id = intval($_POST['id']);
            $val = intval($_POST['val']);

            DB::table('activity_exchange')->where('goods_id', $id)->update(['is_hot' => $val]);
            $this->clear_cache_files();

            return $this->make_json_result($val);
        }

        /**
         * 批量删除商品
         */
        if ($action === 'batch_remove') {
            $this->admin_priv('exchange_goods');

            if (! isset($_POST['checkboxes']) || ! is_array($_POST['checkboxes'])) {
                return $this->sys_msg(lang('no_select_goods'), 1);
            }

            $count = 0;
            foreach ($_POST['checkboxes'] as $key => $id) {
                if (DB::table('activity_exchange')->where('goods_id', $id)->delete()) {
                    $this->admin_log($id, 'remove', 'exchange_goods');
                    $count++;
                }
            }

            $lnk[] = ['text' => lang('back_list'), 'href' => 'exchange_goods.php?act=list'];

            return $this->sys_msg(sprintf(lang('batch_remove_succeed'), $count), 0, $lnk);
        }

        /**
         * 删除商品
         */
        if ($action === 'remove') {
            $this->check_authz_json('exchange_goods');

            $id = intval($_GET['id']);
            if (DB::table('activity_exchange')->where('goods_id', $id)->delete()) {
                $this->admin_log($id, 'remove', 'article');
                $this->clear_cache_files();
            }

            $url = 'exchange_goods.php?act=query&'.str_replace('act=remove', '', $_SERVER['QUERY_STRING']);

            return response()->redirectTo($url);
        }

        /**
         * 搜索商品
         */
        if ($action === 'search_goods') {
            $filters = json_decode($_GET['JSON']);

            $arr = MainHelper::get_goods_list($filters);

            return $this->make_json_result($arr);
        }
    }

    // 获得商品列表
    private function get_exchange_goodslist()
    {
        $result = MainHelper::get_filter();
        if ($result === false) {
            $filter = [];
            $filter['keyword'] = empty($_REQUEST['keyword']) ? '' : trim($_REQUEST['keyword']);
            if (isset($_REQUEST['is_ajax']) && $_REQUEST['is_ajax'] === 1) {
                $filter['keyword'] = BaseHelper::json_str_iconv($filter['keyword']);
            }
            $filter['sort_by'] = empty($_REQUEST['sort_by']) ? 'eg.goods_id' : trim($_REQUEST['sort_by']);
            $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);

            $query = DB::table('activity_exchange as eg')
                ->leftJoin('goods as g', 'g.goods_id', '=', 'eg.goods_id');

            if (! empty($filter['keyword'])) {
                $query->where('g.goods_name', 'like', '%'.BaseHelper::mysql_like_quote($filter['keyword']).'%');
            }

            $filter['record_count'] = $query->count();

            $filter = MainHelper::page_and_size($filter);

            // 获取文章数据
            $res = $query->select('eg.*', 'g.goods_name')
                ->orderBy($filter['sort_by'], $filter['sort_order'])
                ->offset($filter['start'])
                ->limit($filter['page_size'])
                ->get();

            $filter['keyword'] = stripslashes($filter['keyword']);
            MainHelper::set_filter($filter, '');
        } else {
            $filter = $result['filter'];
            $res = DB::table('activity_exchange as eg')
                ->leftJoin('goods as g', 'g.goods_id', '=', 'eg.goods_id')
                ->select('eg.*', 'g.goods_name')
                ->orderBy($filter['sort_by'], $filter['sort_order'])
                ->offset($filter['start'])
                ->limit($filter['page_size'])
                ->get();
        }

        $arr = [];
        foreach ($res as $rows) {
            $arr[] = (array) $rows;
        }

        return ['arr' => $arr, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']];
    }
}
