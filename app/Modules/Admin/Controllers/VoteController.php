<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Helpers\BaseHelper;
use App\Helpers\TimeHelper;
use App\Modules\Admin\Helpers\MainHelper;
use App\Modules\Admin\Libraries\Exchange;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VoteController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        $exc = new Exchange(ecs()->table('vote'), db(), 'vote_id', 'vote_name');
        $exc_opn = new Exchange(ecs()->table('vote_option'), db(), 'option_id', 'option_name');

        /**
         * 投票列表页面
         */
        if ($action === 'list') {
            $this->assign('ur_here', lang('list_vote'));
            $this->assign('action_link', ['text' => lang('add_vote'), 'href' => 'vote.php?act=add']);
            $this->assign('full_page', 1);

            $vote_list = $this->get_votelist();

            $this->assign('list', $vote_list['list']);
            $this->assign('filter', $vote_list['filter']);
            $this->assign('record_count', $vote_list['record_count']);
            $this->assign('page_count', $vote_list['page_count']);

            return $this->display('vote_list');
        }

        /**
         * 排序、分页、查询
         */
        if ($action === 'query') {
            $vote_list = $this->get_votelist();

            $this->assign('list', $vote_list['list']);
            $this->assign('filter', $vote_list['filter']);
            $this->assign('record_count', $vote_list['record_count']);
            $this->assign('page_count', $vote_list['page_count']);

            return $this->make_json_result(
                $this->fetch('vote_list'),
                '',
                ['filter' => $vote_list['filter'], 'page_count' => $vote_list['page_count']]
            );
        }

        /**
         * 添加新的投票页面
         */
        if ($action === 'add') {
            // 权限检查
            $this->admin_priv('vote_priv');

            // 日期初始化
            $vote = ['start_time' => TimeHelper::local_date('Y-m-d'), 'end_time' => TimeHelper::local_date('Y-m-d', TimeHelper::local_strtotime('+2 weeks'))];

            $this->assign('ur_here', lang('add_vote'));
            $this->assign('action_link', ['href' => 'vote.php?act=list', 'text' => lang('list_vote')]);

            $this->assign('action', 'add');
            $this->assign('form_act', 'insert');
            $this->assign('vote_arr', $vote);
            $this->assign('cfg_lang', cfg('lang'));

            return $this->display('vote_info');
        }

        if ($action === 'insert') {
            $this->admin_priv('vote_priv');

            // 获得广告的开始时期与结束日期
            $start_time = TimeHelper::local_strtotime($_POST['start_time']);
            $end_time = TimeHelper::local_strtotime($_POST['end_time']);

            // 查看广告名称是否有重复
            if (DB::table('vote')->where('vote_name', $_POST['vote_name'])->count() === 0) {
                // 插入数据
                $new_id = DB::table('vote')->insertGetId([
                    'vote_name' => $_POST['vote_name'],
                    'start_time' => $start_time,
                    'end_time' => $end_time,
                    'can_multi' => $_POST['can_multi'],
                    'vote_count' => 0,
                ]);

                // 记录管理员操作
                $this->admin_log($_POST['vote_name'], 'add', 'vote');

                // 清除缓存
                $this->clear_cache_files();

                // 提示信息
                $link[0]['text'] = lang('continue_add_option');
                $link[0]['href'] = 'vote.php?act=option&id='.$new_id;

                $link[1]['text'] = lang('continue_add_vote');
                $link[1]['href'] = 'vote.php?act=add';

                $link[2]['text'] = lang('back_list');
                $link[2]['href'] = 'vote.php?act=list';

                return $this->sys_msg(lang('add').'&nbsp;'.$_POST['vote_name'].'&nbsp;'.lang('attradd_succed'), 0, $link);
            } else {
                $link[] = ['text' => lang('go_back'), 'href' => 'javascript:history.back(-1)'];

                return $this->sys_msg(lang('vote_name_exist'), 0, $link);
            }
        }
        /**
         * 在线调查编辑页面
         */
        if ($action === 'edit') {
            $this->admin_priv('vote_priv');

            // 获取数据
            $vote_arr = (array) DB::table('vote')->where('vote_id', (int) $_REQUEST['id'])->first();
            $vote_arr['start_time'] = TimeHelper::local_date('Y-m-d', $vote_arr['start_time']);
            $vote_arr['end_time'] = TimeHelper::local_date('Y-m-d', $vote_arr['end_time']);

            $this->assign('ur_here', lang('edit_vote'));
            $this->assign('action_link', ['href' => 'vote.php?act=list', 'text' => lang('list_vote')]);
            $this->assign('form_act', 'update');
            $this->assign('vote_arr', $vote_arr);

            return $this->display('vote_info');
        }

        if ($action === 'update') {
            // 获得广告的开始时期与结束日期
            $start_time = TimeHelper::local_strtotime($_POST['start_time']);
            $end_time = TimeHelper::local_strtotime($_POST['end_time']);

            // 更新信息
            DB::table('vote')->where('vote_id', (int) $_REQUEST['id'])->update([
                'vote_name' => $_POST['vote_name'],
                'start_time' => $start_time,
                'end_time' => $end_time,
                'can_multi' => $_POST['can_multi'],
            ]);

            // 清除缓存
            $this->clear_cache_files();

            // 记录管理员操作
            $this->admin_log($_POST['vote_name'], 'edit', 'vote');

            // 提示信息
            $link[] = ['text' => lang('back_list'), 'href' => 'vote.php?act=list'];

            return $this->sys_msg(lang('edit').' '.$_POST['vote_name'].' '.lang('attradd_succed'), 0, $link);
        }
        /**
         * 调查选项列表页面
         */
        if ($action === 'option') {
            $id = ! empty($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;

            $this->assign('ur_here', lang('list_vote_option'));
            $this->assign('action_link', ['href' => 'vote.php?act=list', 'text' => lang('list_vote')]);
            $this->assign('full_page', 1);

            $this->assign('id', $id);
            $this->assign('option_arr', $this->get_optionlist($id));

            return $this->display('vote_option');
        }

        /**
         * 调查选项查询
         */
        if ($action === 'query_option') {
            $id = intval($_GET['vid']);

            $this->assign('id', $id);
            $this->assign('option_arr', $this->get_optionlist($id));

            return $this->make_json_result($this->fetch('vote_option'));
        }

        /**
         * 添加新调查选项
         */
        if ($action === 'new_option') {
            $this->check_authz_json('vote_priv');

            $option_name = BaseHelper::json_str_iconv(trim($_POST['option_name']));
            $vote_id = intval($_POST['id']);

            if (! empty($option_name)) {
                // 查看调查标题是否有重复
                if (
                    DB::table('vote_option')
                        ->where('option_name', $option_name)
                        ->where('vote_id', $vote_id)
                        ->count() != 0
                ) {
                    return $this->make_json_error(lang('vote_option_exist'));
                } else {
                    DB::table('vote_option')->insert([
                        'vote_id' => $vote_id,
                        'option_name' => $option_name,
                        'option_count' => 0,
                    ]);

                    $this->clear_cache_files();
                    $this->admin_log($option_name, 'add', 'vote');

                    $url = 'vote.php?act=query_option&vid='.$vote_id.'&'.str_replace('act=new_option', '', $_SERVER['QUERY_STRING']);

                    return response()->redirectTo($url);
                }
            } else {
                return $this->make_json_error(lang('js_languages.option_name_empty'));
            }
        }

        /**
         * 编辑调查主题
         */
        if ($action === 'edit_vote_name') {
            $this->check_authz_json('vote_priv');

            $id = intval($_POST['id']);
            $vote_name = BaseHelper::json_str_iconv(trim($_POST['val']));

            // 检查名称是否重复
            if ($exc->num('vote_name', $vote_name, $id) != 0) {
                return $this->make_json_error(sprintf(lang('vote_name_exist'), $vote_name));
            } else {
                if ($exc->edit("vote_name = '$vote_name'", $id)) {
                    $this->admin_log($vote_name, 'edit', 'vote');

                    return $this->make_json_result(stripslashes($vote_name));
                }
            }
        }

        /**
         * 编辑调查选项
         */
        if ($action === 'edit_option_name') {
            $this->check_authz_json('vote_priv');

            $id = intval($_POST['id']);
            $option_name = BaseHelper::json_str_iconv(trim($_POST['val']));

            // 检查名称是否重复
            $vote_id = DB::table('vote_option')->where('option_id', $id)->value('vote_id');

            if (
                DB::table('vote_option')
                    ->where('option_name', $option_name)
                    ->where('vote_id', $vote_id)
                    ->where('option_id', '<>', $id)
                    ->count() != 0
            ) {
                return $this->make_json_error(sprintf(lang('vote_option_exist'), $option_name));
            } else {
                if ($exc_opn->edit("option_name = '$option_name'", $id)) {
                    $this->admin_log($option_name, 'edit', 'vote');

                    return $this->make_json_result(stripslashes($option_name));
                }
            }
        }

        /**
         * 编辑调查选项排序值
         */
        if ($action === 'edit_option_order') {
            $this->check_authz_json('vote_priv');

            $id = intval($_POST['id']);
            $option_order = BaseHelper::json_str_iconv(trim($_POST['val']));

            if ($exc_opn->edit("option_order = '$option_order'", $id)) {
                $this->admin_log(lang('edit_option_order'), 'edit', 'vote');

                return $this->make_json_result(stripslashes($option_order));
            }
        }

        /**
         * 删除在线调查主题
         */
        if ($action === 'remove') {
            $this->check_authz_json('vote_priv');

            $id = intval($_GET['id']);

            if ($exc->drop($id)) {
                // 同时删除调查选项
                DB::table('vote_option')->where('vote_id', $id)->delete();
                $this->clear_cache_files();
                $this->admin_log('', 'remove', 'ads_position');
            }

            $url = 'vote.php?act=query&'.str_replace('act=remove', '', $_SERVER['QUERY_STRING']);

            return response()->redirectTo($url);
        }

        /**
         * 删除在线调查选项
         */
        if ($action === 'remove_option') {
            $this->check_authz_json('vote_priv');

            $id = intval($_GET['id']);
            $vote_id = DB::table('vote_option')->where('option_id', $id)->value('vote_id');

            if ($exc_opn->drop($id)) {
                $this->clear_cache_files();
                $this->admin_log('', 'remove', 'vote');
            }

            $url = 'vote.php?act=query_option&vid='.$vote_id.'&'.str_replace('act=remove_option', '', $_SERVER['QUERY_STRING']);

            return response()->redirectTo($url);
        }
    }

    // 获取在线调查数据列表
    private function get_votelist()
    {
        $filter = [];

        // 记录总数以及页数
        $filter['record_count'] = DB::table('vote')->count();

        $filter = MainHelper::page_and_size($filter);

        // 查询数据
        $res = DB::table('vote')
            ->orderByDesc('vote_id')
            ->offset($filter['start'])
            ->limit($filter['page_size'])
            ->get()
            ->map(fn ($r) => (array) $r)
            ->all();

        $list = [];
        foreach ($res as $rows) {
            $rows['begin_date'] = TimeHelper::local_date('Y-m-d', $rows['start_time']);
            $rows['end_date'] = TimeHelper::local_date('Y-m-d', $rows['end_time']);
            $list[] = $rows;
        }

        return ['list' => $list, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']];
    }

    // 获取调查选项列表
    private function get_optionlist($id)
    {
        $list = [];
        $res = DB::table('vote_option')
            ->where('vote_id', $id)
            ->select('option_id', 'vote_id', 'option_name', 'option_count', 'option_order')
            ->orderBy('option_order')
            ->orderByDesc('option_id')
            ->get()
            ->map(fn ($r) => (array) $r)
            ->all();
        foreach ($res as $rows) {
            $list[] = $rows;
        }

        return $list;
    }
}
