<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Helpers\BaseHelper;
use App\Helpers\GoodsHelper;
use App\Helpers\TimeHelper;
use App\Modules\Admin\Helpers\MainHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FavourableController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');

        /**
         * 活动列表页
         */
        if ($action === 'list') {
            $this->admin_priv('favourable');

            $this->assign('full_page', 1);
            $this->assign('ur_here', lang('favourable_list'));
            $this->assign('action_link', ['href' => 'favourable.php?act=add', 'text' => lang('add_favourable')]);

            $list = $this->favourable_list();

            $this->assign('favourable_list', $list['item']);
            $this->assign('filter', $list['filter']);
            $this->assign('record_count', $list['record_count']);
            $this->assign('page_count', $list['page_count']);

            $sort_flag = MainHelper::sort_flag($list['filter']);
            $this->assign($sort_flag['tag'], $sort_flag['img']);

            return $this->display('favourable_list');
        }

        /**
         * 分页、排序、查询
         */
        if ($action === 'query') {
            $list = $this->favourable_list();

            $this->assign('favourable_list', $list['item']);
            $this->assign('filter', $list['filter']);
            $this->assign('record_count', $list['record_count']);
            $this->assign('page_count', $list['page_count']);

            $sort_flag = MainHelper::sort_flag($list['filter']);
            $this->assign($sort_flag['tag'], $sort_flag['img']);

            return $this->make_json_result(
                $this->fetch('favourable_list'),
                '',
                ['filter' => $list['filter'], 'page_count' => $list['page_count']]
            );
        }

        /**
         * 删除
         */
        if ($action === 'remove') {
            $this->check_authz_json('favourable');

            $id = intval($_GET['id']);
            $favourable = GoodsHelper::favourable_info($id);
            if (empty($favourable)) {
                return $this->make_json_error(lang('favourable_not_exist'));
            }
            $name = $favourable['act_name'];
            DB::table('activity')->where('act_id', $id)->delete();

            // 记日志
            $this->admin_log($name, 'remove', 'favourable');

            // 清除缓存
            $this->clear_cache_files();

            $url = 'favourable.php?act=query&'.str_replace('act=remove', '', $_SERVER['QUERY_STRING']);

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
                $this->admin_priv('favourable');

                $ids = $_POST['checkboxes'];

                if (isset($_POST['drop'])) {
                    // 删除记录
                    DB::table('activity')->whereIn('act_id', $ids)->delete();

                    // 记日志
                    $this->admin_log('', 'batch_remove', 'favourable');

                    // 清除缓存
                    $this->clear_cache_files();

                    $links[] = ['text' => lang('back_favourable_list'), 'href' => 'favourable.php?act=list&'.MainHelper::list_link_postfix()];

                    return $this->sys_msg(lang('batch_drop_ok'));
                }
            }
        }

        /**
         * 修改排序
         */
        if ($action === 'edit_sort_order') {
            $this->check_authz_json('favourable');

            $id = intval($_POST['id']);
            $val = intval($_POST['val']);

            DB::table('activity')->where('act_id', $id)->update(['sort_order' => $val]);

            return $this->make_json_result($val);
        }

        /**
         * 添加、编辑
         */
        if ($action === 'add' || $action === 'edit') {
            $this->admin_priv('favourable');

            // 是否添加
            $is_add = $action === 'add';
            $this->assign('form_action', $is_add ? 'insert' : 'update');

            // 初始化、取得优惠活动信息
            if ($is_add) {
                $favourable = [
                    'act_id' => 0,
                    'act_name' => '',
                    'start_time' => date('Y-m-d', time() + 86400),
                    'end_time' => date('Y-m-d', time() + 4 * 86400),
                    'user_rank' => '',
                    'act_range' => FAR_ALL,
                    'act_range_ext' => '',
                    'min_amount' => 0,
                    'max_amount' => 0,
                    'act_type' => FAT_GOODS,
                    'act_type_ext' => 0,
                    'gift' => [],
                ];
            } else {
                if (empty($_GET['id'])) {
                    return $this->sys_msg('invalid param');
                }
                $id = intval($_GET['id']);
                $favourable = GoodsHelper::favourable_info($id);
                if (empty($favourable)) {
                    return $this->sys_msg(lang('favourable_not_exist'));
                }
            }
            $this->assign('favourable', $favourable);

            // 取得用户等级
            $user_rank_list = [];
            $user_rank_list[] = [
                'rank_id' => 0,
                'rank_name' => lang('not_user'),
                'checked' => strpos(','.$favourable['user_rank'].',', ',0,') !== false,
            ];
            $res = DB::table('user_rank')->select('rank_id', 'rank_name')->get();
            foreach ($res as $row) {
                $row = (array) $row;
                $row['checked'] = strpos(','.$favourable['user_rank'].',', ','.$row['rank_id'].',') !== false;
                $user_rank_list[] = $row;
            }
            $this->assign('user_rank_list', $user_rank_list);

            // 取得优惠范围
            $act_range_ext = [];
            if ($favourable['act_range'] != FAR_ALL && ! empty($favourable['act_range_ext'])) {
                $ids = explode(',', $favourable['act_range_ext']);
                if ($favourable['act_range'] === FAR_CATEGORY) {
                    $act_range_ext = DB::table('goods_category')
                        ->whereIn('cat_id', $ids)
                        ->select('cat_id AS id', 'cat_name AS name')
                        ->get();
                } elseif ($favourable['act_range'] === FAR_BRAND) {
                    $act_range_ext = DB::table('goods_brand')
                        ->whereIn('brand_id', $ids)
                        ->select('brand_id AS id', 'brand_name AS name')
                        ->get();
                } else {
                    $act_range_ext = DB::table('goods')
                        ->whereIn('goods_id', $ids)
                        ->select('goods_id AS id', 'goods_name AS name')
                        ->get();
                }
                $act_range_ext = array_map(function ($item) {
                    return (array) $item;
                }, $act_range_ext->toArray());
            }
            $this->assign('act_range_ext', $act_range_ext);

            // 赋值时间控件的语言
            $this->assign('cfg_lang', cfg('lang'));

            if ($is_add) {
                $this->assign('ur_here', lang('add_favourable'));
            } else {
                $this->assign('ur_here', lang('edit_favourable'));
            }
            $href = 'favourable.php?act=list';
            if (! $is_add) {
                $href .= '&'.MainHelper::list_link_postfix();
            }
            $this->assign('action_link', ['href' => $href, 'text' => lang('favourable_list')]);

            return $this->display('favourable_info');
        }

        /**
         * 添加、编辑后提交
         */
        if ($action === 'insert' || $action === 'update') {
            $this->admin_priv('favourable');

            // 是否添加
            $is_add = $action === 'insert';

            // 检查名称是否重复
            $act_name = Str::limit($_POST['act_name'], 255, '');
            if (DB::table('activity')->where('act_name', $act_name)->where('act_id', '<>', intval($_POST['id']))->exists()) {
                return $this->sys_msg(lang('act_name_exists'));
            }

            // 检查享受优惠的会员等级
            if (! isset($_POST['user_rank'])) {
                return $this->sys_msg(lang('pls_set_user_rank'));
            }

            // 检查优惠范围扩展信息
            if (intval($_POST['act_range']) > 0 && ! isset($_POST['act_range_ext'])) {
                return $this->sys_msg(lang('pls_set_act_range'));
            }

            // 检查金额上下限
            $min_amount = floatval($_POST['min_amount']) >= 0 ? floatval($_POST['min_amount']) : 0;
            $max_amount = floatval($_POST['max_amount']) >= 0 ? floatval($_POST['max_amount']) : 0;
            if ($max_amount > 0 && $min_amount > $max_amount) {
                return $this->sys_msg(lang('amount_error'));
            }

            // 取得赠品
            $gift = [];
            if (intval($_POST['act_type']) === FAT_GOODS && isset($_POST['gift_id'])) {
                foreach ($_POST['gift_id'] as $key => $id) {
                    $gift[] = ['id' => $id, 'name' => $_POST['gift_name'][$key], 'price' => $_POST['gift_price'][$key]];
                }
            }

            // 提交值
            $favourable = [
                'act_id' => intval($_POST['id']),
                'act_name' => $act_name,
                'start_time' => TimeHelper::local_strtotime($_POST['start_time']),
                'end_time' => TimeHelper::local_strtotime($_POST['end_time']),
                'user_rank' => isset($_POST['user_rank']) ? implode(',', $_POST['user_rank']) : '0',
                'act_range' => intval($_POST['act_range']),
                'act_range_ext' => intval($_POST['act_range']) === 0 ? '' : implode(',', $_POST['act_range_ext']),
                'min_amount' => floatval($_POST['min_amount']),
                'max_amount' => floatval($_POST['max_amount']),
                'act_type' => intval($_POST['act_type']),
                'act_type_ext' => floatval($_POST['act_type_ext']),
                'gift' => serialize($gift),
            ];
            if ($favourable['act_type'] === FAT_GOODS) {
                $favourable['act_type_ext'] = round($favourable['act_type_ext']);
            }

            // 保存数据
            if ($is_add) {
                unset($favourable['act_id']);
                $favourable['act_id'] = DB::table('activity')->insertGetId($favourable);
            } else {
                DB::table('activity')->where('act_id', $favourable['act_id'])->update($favourable);
            }

            // 记日志
            if ($is_add) {
                $this->admin_log($favourable['act_name'], 'add', 'favourable');
            } else {
                $this->admin_log($favourable['act_name'], 'edit', 'favourable');
            }

            // 清除缓存
            $this->clear_cache_files();

            // 提示信息
            if ($is_add) {
                $links = [
                    ['href' => 'favourable.php?act=add', 'text' => lang('continue_add_favourable')],
                    ['href' => 'favourable.php?act=list', 'text' => lang('back_favourable_list')],
                ];

                return $this->sys_msg(lang('add_favourable_ok'), 0, $links);
            } else {
                $links = [
                    ['href' => 'favourable.php?act=list&'.MainHelper::list_link_postfix(), 'text' => lang('back_favourable_list')],
                ];

                return $this->sys_msg(lang('edit_favourable_ok'), 0, $links);
            }
        }

        /**
         * 搜索商品
         */
        if ($action === 'search') {
            $this->check_authz_json('favourable');

            $filter = json_decode($_GET['JSON']);
            $filter->keyword = BaseHelper::json_str_iconv($filter->keyword);
            $arr = [];
            if ($filter->act_range === FAR_ALL) {
                $arr[0] = [
                    'id' => 0,
                    'name' => lang('js_languages.all_need_not_search'),
                ];
            } elseif ($filter->act_range === FAR_CATEGORY) {
                $arr = DB::table('goods_category')
                    ->where('cat_name', 'like', '%'.BaseHelper::mysql_like_quote($filter->keyword).'%')
                    ->select('cat_id AS id', 'cat_name AS name')
                    ->limit(50)
                    ->get();
            } elseif ($filter->act_range === FAR_BRAND) {
                $arr = DB::table('goods_brand')
                    ->where('brand_name', 'like', '%'.BaseHelper::mysql_like_quote($filter->keyword).'%')
                    ->select('brand_id AS id', 'brand_name AS name')
                    ->limit(50)
                    ->get();
            } else {
                $arr = DB::table('goods')
                    ->where(function ($query) use ($filter) {
                        $query->where('goods_name', 'like', '%'.BaseHelper::mysql_like_quote($filter->keyword).'%')
                            ->orWhere('goods_sn', 'like', '%'.BaseHelper::mysql_like_quote($filter->keyword).'%');
                    })
                    ->select('goods_id AS id', 'goods_name AS name')
                    ->limit(50)
                    ->get();
            }
            if ($arr instanceof \Illuminate\Support\Collection) {
                $arr = $arr->map(function ($item) {
                    return (array) $item;
                })->toArray();
            }

            if (empty($arr)) {
                $arr = [
                    0 => [
                        'id' => 0,
                        'name' => lang('search_result_empty'),
                    ],
                ];
            }

            return $this->make_json_result($arr);
        }
    }

    /*
     * 取得优惠活动列表
     * @return   array
     */
    private function favourable_list()
    {
        $result = MainHelper::get_filter();
        if ($result === false) {
            // 过滤条件
            $filter['keyword'] = empty($_REQUEST['keyword']) ? '' : trim($_REQUEST['keyword']);
            if (isset($_REQUEST['is_ajax']) && $_REQUEST['is_ajax'] === 1) {
                $filter['keyword'] = BaseHelper::json_str_iconv($filter['keyword']);
            }
            $filter['is_going'] = empty($_REQUEST['is_going']) ? 0 : 1;
            $filter['sort_by'] = empty($_REQUEST['sort_by']) ? 'act_id' : trim($_REQUEST['sort_by']);
            $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);

            $query = DB::table('activity');

            if (! empty($filter['keyword'])) {
                $query->where('act_name', 'like', '%'.BaseHelper::mysql_like_quote($filter['keyword']).'%');
            }
            if ($filter['is_going']) {
                $now = TimeHelper::gmtime();
                $query->where('start_time', '<=', $now)->where('end_time', '>=', $now);
            }

            $filter['record_count'] = $query->count();

            // 分页大小
            $filter = MainHelper::page_and_size($filter);

            // 查询
            $res = $query->orderBy($filter['sort_by'], $filter['sort_order'])
                ->offset($filter['start'])
                ->limit($filter['page_size'])
                ->get();

            $filter['keyword'] = stripslashes($filter['keyword']);
            MainHelper::set_filter($filter, '');
        } else {
            $filter = $result['filter'];
            $res = DB::table('activity')
                ->orderBy($filter['sort_by'], $filter['sort_order'])
                ->offset($filter['start'])
                ->limit($filter['page_size'])
                ->get();
        }

        $list = [];
        foreach ($res as $row) {
            $row = (array) $row;
            $row['start_time'] = TimeHelper::local_date('Y-m-d H:i', $row['start_time']);
            $row['end_time'] = TimeHelper::local_date('Y-m-d H:i', $row['end_time']);

            $list[] = $row;
        }

        return ['item' => $list, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']];
    }
}
