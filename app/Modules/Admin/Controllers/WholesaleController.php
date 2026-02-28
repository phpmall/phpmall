<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Helpers\BaseHelper;
use App\Helpers\CommonHelper;
use App\Helpers\GoodsHelper;
use App\Modules\Admin\Helpers\MainHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WholesaleController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        /**
         * 活动列表页
         */
        if ($action === 'list') {
            $this->admin_priv('whole_sale');

            $this->assign('full_page', 1);
            $this->assign('ur_here', lang('wholesale_list'));
            $this->assign('action_link', ['href' => 'wholesale.php?act=add', 'text' => lang('add_wholesale')]);
            $this->assign('action_link2', ['href' => 'wholesale.php?act=batch_add', 'text' => lang('add_batch_wholesale')]);

            $list = $this->wholesale_list();

            $this->assign('wholesale_list', $list['item']);
            $this->assign('filter', $list['filter']);
            $this->assign('record_count', $list['record_count']);
            $this->assign('page_count', $list['page_count']);

            $sort_flag = MainHelper::sort_flag($list['filter']);
            $this->assign($sort_flag['tag'], $sort_flag['img']);

            return $this->display('wholesale_list');
        }

        /**
         * 分页、排序、查询
         */
        if ($action === 'query') {
            $list = $this->wholesale_list();

            $this->assign('wholesale_list', $list['item']);
            $this->assign('filter', $list['filter']);
            $this->assign('record_count', $list['record_count']);
            $this->assign('page_count', $list['page_count']);

            $sort_flag = MainHelper::sort_flag($list['filter']);
            $this->assign($sort_flag['tag'], $sort_flag['img']);

            return $this->make_json_result(
                $this->fetch('wholesale_list'),
                '',
                ['filter' => $list['filter'], 'page_count' => $list['page_count']]
            );
        }

        /**
         * 删除
         */
        if ($action === 'remove') {
            $this->check_authz_json('whole_sale');

            $id = intval($_GET['id']);
            $wholesale = GoodsHelper::wholesale_info($id);
            if (empty($wholesale)) {
                return $this->make_json_error(lang('wholesale_not_exist'));
            }
            $name = $wholesale['goods_name'];

            // 删除记录
            DB::table('activity_wholesale')->where('act_id', $id)->limit(1)->delete();

            // 记日志
            $this->admin_log($name, 'remove', 'wholesale');

            // 清除缓存
            $this->clear_cache_files();

            $url = 'wholesale.php?act=query&'.str_replace('act=remove', '', $_SERVER['QUERY_STRING']);

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
                $this->admin_priv('whole_sale');

                $ids = $_POST['checkboxes'];

                if (isset($_POST['drop'])) {
                    // 删除记录
                    DB::table('activity_wholesale')->whereIn('act_id', $ids)->delete();

                    // 记日志
                    $this->admin_log('', 'batch_remove', 'wholesale');

                    // 清除缓存
                    $this->clear_cache_files();

                    $links[] = ['text' => lang('back_wholesale_list'), 'href' => 'wholesale.php?act=list&'.MainHelper::list_link_postfix()];

                    return $this->sys_msg(lang('batch_drop_ok'), 0, $links);
                }
            }
        }

        /**
         * 修改排序
         */
        if ($action === 'toggle_enabled') {
            $this->check_authz_json('whole_sale');

            $id = intval($_POST['id']);
            $val = intval($_POST['val']);

            DB::table('activity_wholesale')->where('act_id', $id)->limit(1)->update(['enabled' => $val]);

            return $this->make_json_result($val);
        }

        /**
         * 批量添加
         */
        if ($action === 'batch_add') {
            $this->admin_priv('whole_sale');
            $this->assign('form_action', 'batch_add_insert');

            // 初始化、取得批发活动信息
            $wholesale = [
                'act_id' => 0,
                'goods_id' => 0,
                'goods_name' => lang('pls_search_goods'),
                'enabled' => '1',
                'price_list' => [],
            ];

            $wholesale['price_list'] = [
                [
                    'attr' => [],
                    'qp_list' => [
                        ['quantity' => 0, 'price' => 0],
                    ],
                ],
            ];
            $this->assign('wholesale', $wholesale);

            // 取得用户等级
            $user_rank_list = [];
            $res = DB::table('user_rank')->orderBy('special_rank')->orderBy('min_points')->select('rank_id', 'rank_name')->get();
            foreach ($res as $rank) {
                $rank = (array) $rank;
                if (! empty($wholesale['rank_ids']) && strpos($wholesale['rank_ids'], $rank['rank_id']) !== false) {
                    $rank['checked'] = 1;
                }
                $user_rank_list[] = $rank;
            }
            $this->assign('user_rank_list', $user_rank_list);

            $this->assign('cat_list', CommonHelper::cat_list());
            $this->assign('brand_list', CommonHelper::get_brand_list());

            $this->assign('ur_here', lang('add_wholesale'));

            $href = 'wholesale.php?act=list';
            $this->assign('action_link', ['href' => $href, 'text' => lang('wholesale_list')]);

            return $this->display('wholesale_batch_info');
        }

        /**
         * 批量添加入库
         */
        if ($action === 'batch_add_insert') {
            $this->admin_priv('whole_sale');

            // 取得goods
            $_POST['dst_goods_lists'] = [];
            if (! empty($_POST['goods_ids'])) {
                $_POST['dst_goods_lists'] = explode(',', $_POST['goods_ids']);
            }
            if (! empty($_POST['dst_goods_lists']) && is_array($_POST['dst_goods_lists'])) {
                foreach ($_POST['dst_goods_lists'] as $dst_key => $dst_goods) {
                    $dst_goods = intval($dst_goods);
                    if ($dst_goods === 0) {
                        unset($_POST['dst_goods_lists'][$dst_key]);
                    }
                }
            } elseif (! empty($_POST['dst_goods_lists'])) {
                $_POST['dst_goods_lists'] = [intval($_POST['dst_goods_lists'])];
            } else {
                return $this->sys_msg(lang('pls_search_goods'));
            }
            $dst_goods = implode(',', $_POST['dst_goods_lists']);

            $goods_name_rows = DB::table('goods')->whereIn('goods_id', $_POST['dst_goods_lists'])->select('goods_name', 'goods_id')->get();
            if (! empty($goods_name_rows)) {
                $goods_rebulid = [];
                foreach ($goods_name_rows as $goods_value) {
                    $goods_value = (array) $goods_value;
                    $goods_rebulid[$goods_value['goods_id']] = addslashes($goods_value['goods_name']);
                }
            }
            if (empty($goods_rebulid)) {
                return $this->sys_msg('invalid goods id: All');
            }

            // 会员等级
            if (! isset($_POST['rank_id'])) {
                return $this->sys_msg(lang('pls_set_user_rank'));
            }

            // 同一个商品，会员等级不能重叠
            // 一个批发方案只有一个商品 一个产品最多支持count(rank_id)个批发方案
            if (isset($_POST['rank_id'])) {
                $dst_res = [];
                foreach ($_POST['rank_id'] as $rank_id) {
                    $dst_res = DB::table('activity_wholesale')
                        ->selectRaw('COUNT(act_id) AS num, goods_id')
                        ->whereIn('goods_id', explode(',', $dst_goods))
                        ->whereRaw("CONCAT(',', rank_ids, ',') LIKE ?", ['%,'.$rank_id.',%'])
                        ->groupBy('goods_id')
                        ->get();
                    foreach ($dst_res as $dst) {
                        $dst = (array) $dst;
                        $key = array_search($dst['goods_id'], $_POST['dst_goods_lists']);
                        if ($key != null && $key !== false) {
                            unset($_POST['dst_goods_lists'][$key]);
                        }
                    }
                }
            }
            if (empty($_POST['dst_goods_lists'])) {
                return $this->sys_msg(lang('pls_search_goods'));
            }

            // 提交值
            $wholesale = [
                'rank_ids' => isset($_POST['rank_id']) ? implode(',', $_POST['rank_id']) : '',
                'prices' => '',
                'enabled' => empty($_POST['enabled']) ? 0 : 1,
            ];

            foreach ($_POST['dst_goods_lists'] as $goods_value) {
                $_wholesale = $wholesale;
                $_wholesale['goods_id'] = $goods_value;
                $_wholesale['goods_name'] = $goods_rebulid[$goods_value];

                // 保存数据
                DB::table('activity_wholesale')->insert($_wholesale);

                // 记日志
                $this->admin_log($goods_rebulid[$goods_value], 'add', 'wholesale');
            }

            // 清除缓存
            $this->clear_cache_files();

            // 提示信息
            $links = [
                ['href' => 'wholesale.php?act=list', 'text' => lang('back_wholesale_list')],
                ['href' => 'wholesale.php?act=add', 'text' => lang('continue_add_wholesale')],
            ];

            return $this->sys_msg(lang('add_wholesale_ok'), 0, $links);
        }

        /**
         * 添加、编辑
         */
        if ($action === 'add' || $action === 'edit') {
            $this->admin_priv('whole_sale');

            // 是否添加
            $is_add = $action === 'add';
            $this->assign('form_action', $is_add ? 'insert' : 'update');

            // 初始化、取得批发活动信息
            if ($is_add) {
                $wholesale = [
                    'act_id' => 0,
                    'goods_id' => 0,
                    'goods_name' => lang('pls_search_goods'),
                    'enabled' => '1',
                    'price_list' => [],
                ];
            } else {
                if (empty($_GET['id'])) {
                    return $this->sys_msg('invalid param');
                }
                $id = intval($_GET['id']);
                $wholesale = GoodsHelper::wholesale_info($id);
                if (empty($wholesale)) {
                    return $this->sys_msg(lang('wholesale_not_exist'));
                }

                // 取得商品属性
                $this->assign('attr_list', GoodsHelper::get_goods_attr($wholesale['goods_id']));
            }
            if (empty($wholesale['price_list'])) {
                $wholesale['price_list'] = [
                    [
                        'attr' => [],
                        'qp_list' => [
                            ['quantity' => 0, 'price' => 0],
                        ],
                    ],
                ];
            }
            $this->assign('wholesale', $wholesale);

            // 取得用户等级
            $user_rank_list = [];
            $res = DB::table('user_rank')->orderBy('special_rank')->orderBy('min_points')->select('rank_id', 'rank_name')->get();
            foreach ($res as $rank) {
                $rank = (array) $rank;
                if (! empty($wholesale['rank_ids']) && strpos($wholesale['rank_ids'], $rank['rank_id']) !== false) {
                    $rank['checked'] = 1;
                }
                $user_rank_list[] = $rank;
            }
            $this->assign('user_rank_list', $user_rank_list);

            $this->assign('cat_list', CommonHelper::cat_list());
            $this->assign('brand_list', CommonHelper::get_brand_list());

            if ($is_add) {
                $this->assign('ur_here', lang('add_wholesale'));
            } else {
                $this->assign('ur_here', lang('edit_wholesale'));
            }
            $href = 'wholesale.php?act=list';
            if (! $is_add) {
                $href .= '&'.MainHelper::list_link_postfix();
            }
            $this->assign('action_link', ['href' => $href, 'text' => lang('wholesale_list')]);

            return $this->display('wholesale_info');
        }

        /**
         * 添加、编辑后提交
         */
        if ($action === 'insert' || $action === 'update') {
            $this->admin_priv('whole_sale');

            // 是否添加
            $is_add = $action === 'insert';

            // 取得goods
            $goods_id = intval($_POST['goods_id']);
            if ($goods_id <= 0) {
                return $this->sys_msg(lang('pls_search_goods'));
            }
            $goods_name = DB::table('goods')->where('goods_id', $goods_id)->value('goods_name');
            $goods_name = addslashes($goods_name);
            if (is_null($goods_name)) {
                return $this->sys_msg('invalid goods id: '.$goods_id);
            }

            // 会员等级
            if (! isset($_POST['rank_id'])) {
                return $this->sys_msg(lang('pls_set_user_rank'));
            }

            // 同一个商品，会员等级不能重叠
            if (isset($_POST['rank_id'])) {
                foreach ($_POST['rank_id'] as $rank_id) {
                    $query = DB::table('activity_wholesale')
                        ->where('goods_id', $goods_id)
                        ->whereRaw("CONCAT(',', rank_ids, ',') LIKE ?", ['%,'.$rank_id.',%']);

                    if (! $is_add) {
                        $query->where('act_id', '<>', $_POST['id']);
                    }

                    if ($query->count() > 0) {
                        return $this->sys_msg(lang('user_rank_exist'));
                    }
                }
            }

            // 取得goods_attr
            $attr_id_list = DB::table('goods AS g')
                ->join('goods_type_attribute AS a', 'g.goods_type', '=', 'a.cat_id')
                ->where('g.goods_id', $goods_id)
                ->where('a.attr_type', 1)
                ->pluck('a.attr_id')
                ->all();

            // 取得属性、数量、价格信息
            $prices = [];
            $key_list = array_keys($_POST['quantity']);

            foreach ($key_list as $key) {
                $attr = [];
                foreach ($attr_id_list as $attr_id) {
                    if ($_POST['attr_'.$attr_id][$key] != 0) {
                        $attr[$attr_id] = $_POST['attr_'.$attr_id][$key];
                    }
                }

                // 判断商品的货品表是否存在此规格的货品
                $attr_error = false;
                if (! empty($attr)) {
                    $_attr = $attr;
                    ksort($_attr);
                    $goods_attr = implode('|', $_attr);

                    if (! DB::table('goods_product')->where('goods_attr', $goods_attr)->where('goods_id', $goods_id)->value('product_id')) {
                        $attr_error = true;

                        continue;
                    }
                }

                $qp_list = [];
                foreach ($_POST['quantity'][$key] as $index => $quantity) {
                    $quantity = intval($quantity);
                    $price = floatval($_POST['price'][$key][$index]);
                    // 数量或价格为0或者已经存在的数量忽略
                    if ($quantity <= 0 || $price <= 0 || isset($qp_list[$quantity])) {
                        continue;
                    }
                    $qp_list[$quantity] = $price;
                }
                ksort($qp_list);

                $arranged_qp_list = [];
                foreach ($qp_list as $quantity => $price) {
                    $arranged_qp_list[] = ['quantity' => $quantity, 'price' => $price];
                }

                // 只记录有数量价格的数据
                if ($arranged_qp_list) {
                    $prices[] = ['attr' => $attr, 'qp_list' => $arranged_qp_list];
                }
            }

            // 提交值
            $wholesale = [
                'act_id' => intval($_POST['id']),
                'goods_id' => $goods_id,
                'goods_name' => $goods_name,
                'rank_ids' => isset($_POST['rank_id']) ? implode(',', $_POST['rank_id']) : '',
                'prices' => serialize($prices),
                'enabled' => empty($_POST['enabled']) ? 0 : 1,
            ];

            // 保存数据
            if ($is_add) {
                $wholesale['act_id'] = DB::table('activity_wholesale')->insertGetId($wholesale);
            } else {
                DB::table('activity_wholesale')->where('act_id', $wholesale['act_id'])->update($wholesale);
            }

            // 记日志
            if ($is_add) {
                $this->admin_log($wholesale['goods_name'], 'add', 'wholesale');
            } else {
                $this->admin_log($wholesale['goods_name'], 'edit', 'wholesale');
            }

            // 清除缓存
            $this->clear_cache_files();

            // 提示信息
            if ($attr_error) {
                $links = [
                    ['href' => 'wholesale.php?act=list', 'text' => lang('back_wholesale_list')],
                ];

                return $this->sys_msg(sprintf(lang('save_wholesale_falid'), $wholesale['goods_name']), 1, $links);
            }

            if ($is_add) {
                $links = [
                    ['href' => 'wholesale.php?act=add', 'text' => lang('continue_add_wholesale')],
                    ['href' => 'wholesale.php?act=list', 'text' => lang('back_wholesale_list')],
                ];

                return $this->sys_msg(lang('add_wholesale_ok'), 0, $links);
            } else {
                $links = [
                    ['href' => 'wholesale.php?act=list&'.MainHelper::list_link_postfix(), 'text' => lang('back_wholesale_list')],
                ];

                return $this->sys_msg(lang('edit_wholesale_ok'), 0, $links);
            }
        }

        /**
         * 搜索商品
         */
        if ($action === 'search_goods') {
            $this->check_authz_json('whole_sale');

            $filter = json_decode($_GET['JSON']);
            $arr = MainHelper::get_goods_list($filter);
            if (empty($arr)) {
                $arr[0] = [
                    'goods_id' => 0,
                    'goods_name' => lang('search_result_empty'),
                ];
            }

            return $this->make_json_result($arr);
        }

        /**
         * 取得商品信息
         */
        if ($action === 'get_goods_info') {
            $goods_id = intval($_REQUEST['goods_id']);
            $goods_attr_list = array_values(GoodsHelper::get_goods_attr($goods_id));

            // 将数组中的 goods_attr_list 元素下的元素的数字下标转换成字符串下标
            if (! empty($goods_attr_list)) {
                foreach ($goods_attr_list as $goods_attr_key => $goods_attr_value) {
                    if (isset($goods_attr_value['goods_attr_list']) && ! empty($goods_attr_value['goods_attr_list'])) {
                        foreach ($goods_attr_value['goods_attr_list'] as $key => $value) {
                            $goods_attr_list[$goods_attr_key]['goods_attr_list']['c'.$key] = $value;
                            unset($goods_attr_list[$goods_attr_key]['goods_attr_list'][$key]);
                        }
                    }
                }
            }

            echo json_encode($goods_attr_list);
        }
    }

    /*
     * 取得批发活动列表
     * @return   array
     */
    private function wholesale_list()
    {
        // 查询会员等级
        $rank_list = [];
        $res = DB::table('user_rank')->select('rank_id', 'rank_name')->get();
        foreach ($res as $row) {
            $row = (array) $row;
            $rank_list[$row['rank_id']] = $row['rank_name'];
        }

        $result = MainHelper::get_filter();
        if ($result === false) {
            // 过滤条件
            $filter['keyword'] = empty($_REQUEST['keyword']) ? '' : trim($_REQUEST['keyword']);
            if (isset($_REQUEST['is_ajax']) && $_REQUEST['is_ajax'] === 1) {
                $filter['keyword'] = BaseHelper::json_str_iconv($filter['keyword']);
            }
            $filter['sort_by'] = empty($_REQUEST['sort_by']) ? 'act_id' : trim($_REQUEST['sort_by']);
            $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);

            $query = DB::table('activity_wholesale');

            if (! empty($filter['keyword'])) {
                $query->where('goods_name', 'like', '%'.BaseHelper::mysql_like_quote($filter['keyword']).'%');
            }

            $filter['record_count'] = $query->count();

            // 分页大小
            $filter = MainHelper::page_and_size($filter);

            // 查询
            $res = $query
                ->orderBy($filter['sort_by'], $filter['sort_order'])
                ->offset($filter['start'])
                ->limit($filter['page_size'])
                ->get();

            $filter['keyword'] = stripslashes($filter['keyword']);
            MainHelper::set_filter($filter, '');
        } else {
            $sql = $result['sql'];
            $filter = $result['filter'];
            $res = DB::select($sql);
        }

        $list = [];
        foreach ($res as $row) {
            $row = (array) $row;
            $rank_name_list = [];
            if ($row['rank_ids']) {
                $rank_id_list = explode(',', $row['rank_ids']);
                foreach ($rank_id_list as $id) {
                    if (isset($rank_list[$id])) {
                        $rank_name_list[] = $rank_list[$id];
                    }
                }
            }
            $row['rank_names'] = implode(',', $rank_name_list);
            $row['price_list'] = unserialize($row['prices']);

            $list[] = $row;
        }

        return ['item' => $list, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']];
    }
}
