<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Helpers\BaseHelper;
use App\Modules\Admin\Libraries\Exchange;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserRankController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        $exc = new Exchange(ecs()->table('user_rank'), db(), 'rank_id', 'rank_name');
        $exc_user = new Exchange(ecs()->table('user'), db(), 'user_rank', 'user_rank');

        /**
         * 会员等级列表
         */
        if ($action === 'list') {
            $ranks = [];
            $ranks = DB::table('user_rank')->get()->map(fn ($r) => (array) $r)->all();

            $this->assign('ur_here', lang('05_user_rank_list'));
            $this->assign('action_link', ['text' => lang('add_user_rank'), 'href' => 'user_rank.php?act=add']);
            $this->assign('full_page', 1);

            $this->assign('user_ranks', $ranks);

            return $this->display('user_rank');
        }

        /**
         * 翻页，排序
         */
        if ($action === 'query') {
            $ranks = [];
            $ranks = DB::table('user_rank')->get()->map(fn ($r) => (array) $r)->all();

            $this->assign('user_ranks', $ranks);

            return $this->make_json_result($this->fetch('user_rank'));
        }

        /**
         * 添加会员等级
         */
        if ($action === 'add') {
            $this->admin_priv('user_rank');

            $rank['rank_id'] = 0;
            $rank['rank_special'] = 0;
            $rank['show_price'] = 1;
            $rank['min_points'] = 0;
            $rank['max_points'] = 0;
            $rank['discount'] = 100;

            $form_action = 'insert';

            $this->assign('rank', $rank);
            $this->assign('ur_here', lang('add_user_rank'));
            $this->assign('action_link', ['text' => lang('05_user_rank_list'), 'href' => 'user_rank.php?act=list']);
            $this->assign('ur_here', lang('add_user_rank'));
            $this->assign('form_action', $form_action);

            return $this->display('user_rank_info');
        }

        /**
         * 增加会员等级到数据库
         */
        if ($action === 'insert') {
            $this->admin_priv('user_rank');

            $special_rank = isset($_POST['special_rank']) ? intval($_POST['special_rank']) : 0;
            $_POST['min_points'] = empty($_POST['min_points']) ? 0 : intval($_POST['min_points']);
            $_POST['max_points'] = empty($_POST['max_points']) ? 0 : intval($_POST['max_points']);

            // 检查是否存在重名的会员等级
            if (! $exc->is_only('rank_name', trim($_POST['rank_name']))) {
                return $this->sys_msg(sprintf(lang('rank_name_exists'), trim($_POST['rank_name'])), 1);
            }

            // 非特殊会员组检查积分的上下限是否合理
            if ($_POST['min_points'] >= $_POST['max_points'] && $special_rank === 0) {
                return $this->sys_msg(lang('js_languages.integral_max_small'), 1);
            }

            // 特殊等级会员组不判断积分限制
            if ($special_rank === 0) {
                // 检查下限制有无重复
                if (! $exc->is_only('min_points', intval($_POST['min_points']))) {
                    return $this->sys_msg(sprintf(lang('integral_min_exists'), intval($_POST['min_points'])));
                }
            }

            // 特殊等级会员组不判断积分限制
            if ($special_rank === 0) {
                // 检查上限有无重复
                if (! $exc->is_only('max_points', intval($_POST['max_points']))) {
                    return $this->sys_msg(sprintf(lang('integral_max_exists'), intval($_POST['max_points'])));
                }
            }

            DB::table('user_rank')->insert([
                'rank_name' => $_POST['rank_name'],
                'min_points' => intval($_POST['min_points']),
                'max_points' => intval($_POST['max_points']),
                'discount' => (int) $_POST['discount'],
                'special_rank' => $special_rank,
                'show_price' => intval($_POST['show_price']),
            ]);

            // 管理员日志
            $this->admin_log(trim($_POST['rank_name']), 'add', 'user_rank');
            $this->clear_cache_files();

            $lnk[] = ['text' => lang('back_list'), 'href' => 'user_rank.php?act=list'];
            $lnk[] = ['text' => lang('add_continue'), 'href' => 'user_rank.php?act=add'];

            return $this->sys_msg(lang('add_rank_success'), 0, $lnk);
        }

        /**
         * 删除会员等级
         */
        if ($action === 'remove') {
            $this->check_authz_json('user_rank');

            $rank_id = intval($_GET['id']);

            if ($exc->drop($rank_id)) {
                // 更新会员表的等级字段
                $exc_user->edit('user_rank = 0', $rank_id);

                $rank_name = $exc->get_name($rank_id);
                $this->admin_log(addslashes($rank_name), 'remove', 'user_rank');
                $this->clear_cache_files();
            }

            $url = 'user_rank.php?act=query&'.str_replace('act=remove', '', $_SERVER['QUERY_STRING']);

            return response()->redirectTo($url);
        }

        /**
         *  编辑会员等级名称
         */
        if ($action === 'edit_name') {
            $id = intval($_REQUEST['id']);
            $val = empty($_REQUEST['val']) ? '' : BaseHelper::json_str_iconv(trim($_REQUEST['val']));
            $this->check_authz_json('user_rank');
            if ($exc->is_only('rank_name', $val, $id)) {
                if ($exc->edit("rank_name = '$val'", $id)) {
                    // 管理员日志
                    $this->admin_log($val, 'edit', 'user_rank');
                    $this->clear_cache_files();

                    return $this->make_json_result(stripcslashes($val));
                } else {
                    return $this->make_json_error('DB error');
                }
            } else {
                return $this->make_json_error(sprintf(lang('rank_name_exists'), htmlspecialchars($val)));
            }
        }

        /**
         *  ajax编辑积分下限
         */
        if ($action === 'edit_min_points') {
            $this->check_authz_json('user_rank');

            $rank_id = empty($_REQUEST['id']) ? 0 : intval($_REQUEST['id']);
            $val = empty($_REQUEST['val']) ? 0 : intval($_REQUEST['val']);

            $rank = (array) DB::table('user_rank')
                ->where('rank_id', $rank_id)
                ->select('max_points', 'special_rank')
                ->first();
            if ($val >= $rank['max_points'] && $rank['special_rank'] === 0) {
                return $this->make_json_error(lang('js_languages.integral_max_small'));
            }

            if ($rank['special_rank'] === 0 && ! $exc->is_only('min_points', $val, $rank_id)) {
                return $this->make_json_error(sprintf(lang('integral_min_exists'), $val));
            }

            if ($exc->edit("min_points = '$val'", $rank_id)) {
                $rank_name = $exc->get_name($rank_id);
                $this->admin_log(addslashes($rank_name), 'edit', 'user_rank');

                return $this->make_json_result($val);
            } else {
                return $this->make_json_error('DB error');
            }
        }

        /**
         *  ajax修改积分上限
         */
        if ($action === 'edit_max_points') {
            $this->check_authz_json('user_rank');

            $rank_id = empty($_REQUEST['id']) ? 0 : intval($_REQUEST['id']);
            $val = empty($_REQUEST['val']) ? 0 : intval($_REQUEST['val']);

            $rank = (array) DB::table('user_rank')
                ->where('rank_id', $rank_id)
                ->select('min_points', 'special_rank')
                ->first();

            if ($val <= $rank['min_points'] && $rank['special_rank'] === 0) {
                return $this->make_json_error(lang('js_languages.integral_max_small'));
            }

            if ($rank['special_rank'] === 0 && ! $exc->is_only('max_points', $val, $rank_id)) {
                return $this->make_json_error(sprintf(lang('integral_max_exists'), $val));
            }
            if ($exc->edit("max_points = '$val'", $rank_id)) {
                $rank_name = $exc->get_name($rank_id);
                $this->admin_log(addslashes($rank_name), 'edit', 'user_rank');

                return $this->make_json_result($val);
            } else {
                return $this->make_json_error('DB error');
            }
        }

        /**
         *  修改折扣率
         */
        if ($action === 'edit_discount') {
            $this->check_authz_json('user_rank');

            $rank_id = empty($_REQUEST['id']) ? 0 : intval($_REQUEST['id']);
            $val = empty($_REQUEST['val']) ? 0 : intval($_REQUEST['val']);

            if ($val < 1 || $val > 100) {
                return $this->make_json_error(lang('js_languages.discount_invalid'));
            }

            if ($exc->edit("discount = '$val'", $rank_id)) {
                $rank_name = $exc->get_name($rank_id);
                $this->admin_log(addslashes($rank_name), 'edit', 'user_rank');
                $this->clear_cache_files();

                return $this->make_json_result($val);
            } else {
                return $this->make_json_error($val);
            }
        }

        /**
         * 切换是否是特殊会员组
         */
        if ($action === 'toggle_special') {
            $this->check_authz_json('user_rank');

            $rank_id = intval($_POST['id']);
            $is_special = intval($_POST['val']);

            if ($exc->edit("special_rank = '$is_special'", $rank_id)) {
                $rank_name = $exc->get_name($rank_id);
                $this->admin_log(addslashes($rank_name), 'edit', 'user_rank');

                return $this->make_json_result($is_special);
            } else {
                return $this->make_json_error('DB error');
            }
        }

        /**
         * 切换是否显示价格
         */
        if ($action === 'toggle_showprice') {
            $this->check_authz_json('user_rank');

            $rank_id = intval($_POST['id']);
            $is_show = intval($_POST['val']);

            if ($exc->edit("show_price = '$is_show'", $rank_id)) {
                $rank_name = $exc->get_name($rank_id);
                $this->admin_log(addslashes($rank_name), 'edit', 'user_rank');
                $this->clear_cache_files();

                return $this->make_json_result($is_show);
            } else {
                return $this->make_json_error('DB error');
            }
        }
    }
}
