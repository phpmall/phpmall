<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Helpers\BaseHelper;
use App\Helpers\CommonHelper;
use App\Helpers\GoodsHelper;
use App\Helpers\TimeHelper;
use App\Modules\Admin\Helpers\MainHelper;
use App\Modules\Admin\Libraries\Exchange;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AuctionController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        $exc = new Exchange(ecs()->table('goods_activity'), db(), 'act_id', 'act_name');

        /**
         * 活动列表页
         */
        if ($action === 'list') {
            $this->admin_priv('auction');

            $this->assign('full_page', 1);
            $this->assign('ur_here', lang('auction_list'));
            $this->assign('action_link', ['href' => 'auction.php?act=add', 'text' => lang('add_auction')]);

            $list = $this->auction_list();

            $this->assign('auction_list', $list['item']);
            $this->assign('filter', $list['filter']);
            $this->assign('record_count', $list['record_count']);
            $this->assign('page_count', $list['page_count']);

            $sort_flag = MainHelper::sort_flag($list['filter']);
            $this->assign($sort_flag['tag'], $sort_flag['img']);

            return $this->display('auction_list');
        }

        /**
         * 分页、排序、查询
         */
        if ($action === 'query') {
            $list = $this->auction_list();

            $this->assign('auction_list', $list['item']);
            $this->assign('filter', $list['filter']);
            $this->assign('record_count', $list['record_count']);
            $this->assign('page_count', $list['page_count']);

            $sort_flag = MainHelper::sort_flag($list['filter']);
            $this->assign($sort_flag['tag'], $sort_flag['img']);

            return $this->make_json_result(
                $this->fetch('auction_list'),
                '',
                ['filter' => $list['filter'], 'page_count' => $list['page_count']]
            );
        }

        /**
         * 删除
         */
        if ($action === 'remove') {
            $this->check_authz_json('auction');

            $id = intval($_GET['id']);
            $auction = GoodsHelper::auction_info($id);
            if (empty($auction)) {
                return $this->make_json_error(lang('auction_not_exist'));
            }
            if ($auction['bid_user_count'] > 0) {
                return $this->make_json_error(lang('auction_cannot_remove'));
            }
            $name = $auction['act_name'];
            $exc->drop($id);

            // 记日志
            $this->admin_log($name, 'remove', 'auction');

            // 清除缓存
            $this->clear_cache_files();

            $url = 'auction.php?act=query&'.str_replace('act=remove', '', $_SERVER['QUERY_STRING']);

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
                $this->admin_priv('auction');

                $ids = $_POST['checkboxes'];

                if (isset($_POST['drop'])) {
                    // 查询哪些拍卖活动已经有人出价
                    $col = DB::table('activity_auction')
                        ->whereIn('act_id', $ids)
                        ->distinct()
                        ->pluck('act_id')
                        ->all();
                    $delete_ids = array_diff($ids, $col);
                    if (! empty($delete_ids)) {
                        // 删除记录
                        DB::table('goods_activity')
                            ->whereIn('act_id', $delete_ids)
                            ->where('act_type', GAT_AUCTION)
                            ->delete();

                        // 记日志
                        $this->admin_log('', 'batch_remove', 'auction');

                        // 清除缓存
                        $this->clear_cache_files();
                    }
                    $links[] = ['text' => lang('back_auction_list'), 'href' => 'auction.php?act=list&'.MainHelper::list_link_postfix()];

                    return $this->sys_msg(lang('batch_drop_ok'), 0, $links);
                }
            }
        }

        /**
         * 查看出价记录
         */
        if ($action === 'view_log') {
            $this->admin_priv('auction');

            // 参数
            if (empty($_GET['id'])) {
                return $this->sys_msg('invalid param');
            }
            $id = intval($_GET['id']);
            $auction = GoodsHelper::auction_info($id);
            if (empty($auction)) {
                return $this->sys_msg(lang('auction_not_exist'));
            }
            $this->assign('auction', GoodsHelper::auction_info($id));

            // 出价记录
            $this->assign('auction_log', GoodsHelper::auction_log($id));

            $this->assign('ur_here', lang('auction_log'));
            $this->assign('action_link', ['href' => 'auction.php?act=list&'.MainHelper::list_link_postfix(), 'text' => lang('auction_list')]);

            return $this->display('auction_log');
        }

        /**
         * 添加、编辑
         */
        if ($action === 'add' || $action === 'edit') {
            $this->admin_priv('auction');

            // 是否添加
            $is_add = $action === 'add';
            $this->assign('form_action', $is_add ? 'insert' : 'update');

            // 初始化、取得拍卖活动信息
            if ($is_add) {
                $auction = [
                    'act_id' => 0,
                    'act_name' => '',
                    'act_desc' => '',
                    'goods_id' => 0,
                    'product_id' => 0,
                    'goods_name' => lang('pls_search_goods'),
                    'start_time' => date('Y-m-d', time() + 86400),
                    'end_time' => date('Y-m-d', time() + 4 * 86400),
                    'deposit' => 0,
                    'start_price' => 0,
                    'end_price' => 0,
                    'amplitude' => 0,
                ];
            } else {
                if (empty($_GET['id'])) {
                    return $this->sys_msg('invalid param');
                }
                $id = intval($_GET['id']);
                $auction = GoodsHelper::auction_info($id, true);
                if (empty($auction)) {
                    return $this->sys_msg(lang('auction_not_exist'));
                }
                $auction['status'] = lang('auction_status')[$auction['status_no']];
                $this->assign('bid_user_count', sprintf(lang('bid_user_count'), $auction['bid_user_count']));
            }
            $this->assign('auction', $auction);

            // 赋值时间控件的语言
            $this->assign('cfg_lang', cfg('lang'));

            // 商品货品表
            $this->assign('good_products_select', CommonHelper::get_good_products_select($auction['goods_id']));

            if ($is_add) {
                $this->assign('ur_here', lang('add_auction'));
            } else {
                $this->assign('ur_here', lang('edit_auction'));
            }
            $this->assign('action_link', $this->list_link($is_add));

            return $this->display('auction_info');
        }

        /**
         * 添加、编辑后提交
         */
        if ($action === 'insert' || $action === 'update') {
            $this->admin_priv('auction');

            // 是否添加
            $is_add = $action === 'insert';

            // 检查是否选择了商品
            $goods_id = intval($_POST['goods_id']);
            if ($goods_id <= 0) {
                return $this->sys_msg(lang('pls_select_goods'));
            }
            $row = DB::table('goods')->where('goods_id', $goods_id)->select('goods_name')->first();
            $row = $row ? (array) $row : [];
            if (empty($row)) {
                return $this->sys_msg(lang('goods_not_exist'));
            }
            $goods_name = $row['goods_name'];

            // 提交值
            $auction = [
                'act_id' => intval($_POST['id']),
                'act_name' => empty($_POST['act_name']) ? $goods_name : Str::limit($_POST['act_name'], 255, ''),
                'act_desc' => $_POST['act_desc'],
                'act_type' => GAT_AUCTION,
                'goods_id' => $goods_id,
                'product_id' => empty($_POST['product_id']) ? 0 : $_POST['product_id'],
                'goods_name' => $goods_name,
                'start_time' => TimeHelper::local_strtotime($_POST['start_time']),
                'end_time' => TimeHelper::local_strtotime($_POST['end_time']),
                'ext_info' => serialize([
                    'deposit' => round(floatval($_POST['deposit']), 2),
                    'start_price' => round(floatval($_POST['start_price']), 2),
                    'end_price' => empty($_POST['no_top']) ? round(floatval($_POST['end_price']), 2) : 0,
                    'amplitude' => round(floatval($_POST['amplitude']), 2),
                    'no_top' => ! empty($_POST['no_top']) ? intval($_POST['no_top']) : 0,
                ]),
            ];

            // 保存数据
            if ($is_add) {
                $auction['is_finished'] = 0;
                $auction['act_id'] = DB::table('goods_activity')->insertGetId($auction);
            } else {
                unset($auction['act_id']);
                DB::table('goods_activity')
                    ->where('act_id', intval($_POST['id']))
                    ->update($auction);
                $auction['act_id'] = intval($_POST['id']);
            }

            // 记日志
            if ($is_add) {
                $this->admin_log($auction['act_name'], 'add', 'auction');
            } else {
                $this->admin_log($auction['act_name'], 'edit', 'auction');
            }

            // 清除缓存
            $this->clear_cache_files();

            // 提示信息
            if ($is_add) {
                $links = [
                    ['href' => 'auction.php?act=add', 'text' => lang('continue_add_auction')],
                    ['href' => 'auction.php?act=list', 'text' => lang('back_auction_list')],
                ];

                return $this->sys_msg(lang('add_auction_ok'), 0, $links);
            } else {
                $links = [
                    ['href' => 'auction.php?act=list&'.MainHelper::list_link_postfix(), 'text' => lang('back_auction_list')],
                ];

                return $this->sys_msg(lang('edit_auction_ok'), 0, $links);
            }
        }

        /**
         * 处理冻结资金
         */
        if ($action === 'settle_money') {
            $this->admin_priv('auction');

            if (empty($_POST['id'])) {
                return $this->sys_msg('invalid param');
            }
            $id = intval($_POST['id']);
            $auction = GoodsHelper::auction_info($id);
            if (empty($auction)) {
                return $this->sys_msg(lang('auction_not_exist'));
            }
            if ($auction['status_no'] != FINISHED) {
                return $this->sys_msg(lang('invalid_status'));
            }
            if ($auction['deposit'] <= 0) {
                return $this->sys_msg(lang('no_deposit'));
            }

            // 处理保证金
            $exc->edit('is_finished = 2', $id); // 修改状态
            if (isset($_POST['unfreeze'])) {
                // 解冻
                CommonHelper::log_account_change(
                    $auction['last_bid']['bid_user'],
                    $auction['deposit'],
                    (-1) * $auction['deposit'],
                    0,
                    0,
                    sprintf(lang('unfreeze_auction_deposit'), $auction['act_name'])
                );
            } else {
                // 扣除
                CommonHelper::log_account_change(
                    $auction['last_bid']['bid_user'],
                    0,
                    (-1) * $auction['deposit'],
                    0,
                    0,
                    sprintf(lang('deduct_auction_deposit'), $auction['act_name'])
                );
            }

            // 记日志
            $this->admin_log($auction['act_name'], 'edit', 'auction');

            // 清除缓存
            $this->clear_cache_files();

            // 提示信息
            return $this->sys_msg(lang('settle_deposit_ok'));
        }

        /**
         * 搜索商品
         */
        if ($action === 'search_goods') {
            $this->check_authz_json('auction');

            $filter = json_decode($_GET['JSON']);
            $arr['goods'] = MainHelper::get_goods_list($filter);

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

    /*
     * 取得拍卖活动列表
     * @return   array
     */
    private function auction_list()
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

            $query = DB::table('goods_activity')->where('act_type', GAT_AUCTION);

            if (! empty($filter['keyword'])) {
                $query->where('goods_name', 'like', '%'.BaseHelper::mysql_like_quote($filter['keyword']).'%');
            }
            if ($filter['is_going']) {
                $now = TimeHelper::gmtime();
                $query->where('is_finished', 0)
                    ->where('start_time', '<=', $now)
                    ->where('end_time', '>=', $now);
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
        } else {
            $res = DB::select($result['sql']);
            $filter = $result['filter'];
        }

        $list = [];
        foreach ($res as $row) {
            $row = (array) $row;
            $ext_info = unserialize($row['ext_info']);
            $arr = array_merge($row, $ext_info);

            $arr['start_time'] = TimeHelper::local_date('Y-m-d H:i', $arr['start_time']);
            $arr['end_time'] = TimeHelper::local_date('Y-m-d H:i', $arr['end_time']);

            $list[] = $arr;
        }

        $arr = ['item' => $list, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']];

        return $arr;
    }

    /**
     * 列表链接
     *
     * @param  bool  $is_add  是否添加（插入）
     * @param  string  $text  文字
     * @return array('href' => $href, 'text' => $text)
     */
    private function list_link($is_add = true, $text = '')
    {
        $href = 'auction.php?act=list';
        if (! $is_add) {
            $href .= '&'.MainHelper::list_link_postfix();
        }
        if ($text === '') {
            $text = lang('auction_list');
        }

        return ['href' => $href, 'text' => $text];
    }
}
