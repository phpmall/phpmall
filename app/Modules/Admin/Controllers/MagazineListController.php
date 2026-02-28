<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Helpers\TimeHelper;
use App\Modules\Admin\Helpers\MainHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MagazineListController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        $this->admin_priv('magazine_list');
        if ($action === 'list') {
            $this->assign('ur_here', lang('magazine_list'));
            $this->assign('action_link', ['text' => lang('add_new'), 'href' => 'magazine_list.php?act=add']);
            $this->assign('full_page', 1);

            $magazinedb = $this->get_magazine();

            $this->assign('magazinedb', $magazinedb['magazinedb']);
            $this->assign('filter', $magazinedb['filter']);
            $this->assign('record_count', $magazinedb['record_count']);
            $this->assign('page_count', $magazinedb['page_count']);

            $special_ranks = MainHelper::get_rank_list();
            $send_rank[SEND_LIST.'_0'] = lang('email_user');
            $send_rank[SEND_USER.'_0'] = lang('user_list');
            foreach ($special_ranks as $rank_key => $rank_value) {
                $send_rank[SEND_RANK.'_'.$rank_key] = $rank_value;
            }
            $this->assign('send_rank', $send_rank);

            return $this->display('magazine_list');
        }

        if ($action === 'query') {
            $magazinedb = $this->get_magazine();
            $this->assign('magazinedb', $magazinedb['magazinedb']);
            $this->assign('filter', $magazinedb['filter']);
            $this->assign('record_count', $magazinedb['record_count']);
            $this->assign('page_count', $magazinedb['page_count']);

            $sort_flag = MainHelper::sort_flag($magazinedb['filter']);
            $this->assign($sort_flag['tag'], $sort_flag['img']);

            return $this->make_json_result($this->fetch('magazine_list'), '', ['filter' => $magazinedb['filter'], 'page_count' => $magazinedb['page_count']]);
        }

        if ($action === 'add') {
            if (empty($_POST['step'])) {
                // include_once ROOT_PATH.'includes/fckeditor/fckeditor.php'; // 包含 html editor 类文件
                $this->assign('action_link', ['text' => lang('go_list'), 'href' => 'magazine_list.php?act=list']);
                $this->assign(['ur_here' => lang('magazine_list'), 'act' => 'add']);
                MainHelper::create_html_editor('magazine_content');

                return $this->display('magazine_list_add');
            } elseif ($_POST['step'] === 2) {
                $magazine_name = trim($_POST['magazine_name']);
                $magazine_content = trim($_POST['magazine_content']);
                $magazine_content = str_replace('src=\"', 'src=\"http://'.$_SERVER['HTTP_HOST'], $magazine_content);
                $time = TimeHelper::gmtime();
                DB::table('email_template')->insert([
                    'template_code' => md5($magazine_name.$time),
                    'is_html' => 1,
                    'template_subject' => $magazine_name,
                    'template_content' => $magazine_content,
                    'last_modify' => $time,
                    'type' => 'magazine',
                ]);
                $links[] = ['text' => lang('magazine_list'), 'href' => 'magazine_list.php?act=list'];
                $links[] = ['text' => lang('add_new'), 'href' => 'magazine_list.php?act=add'];

                return $this->sys_msg(lang('edit_ok'), 0, $links);
            }
        }

        if ($action === 'edit') {
            // include_once ROOT_PATH.'includes/fckeditor/fckeditor.php'; // 包含 html editor 类文件
            $id = intval($_REQUEST['id']);
            if (empty($_POST['step'])) {
                $rt = (array) DB::table('email_template')->where('type', 'magazine')->where('template_id', $id)->first();
                $this->assign(['id' => $id, 'act' => 'edit', 'magazine_name' => $rt['template_subject'], 'magazine_content' => $rt['template_content']]);
                $this->assign(['ur_here' => lang('magazine_list'), 'act' => 'edit']);
                $this->assign('action_link', ['text' => lang('go_list'), 'href' => 'magazine_list.php?act=list']);
                MainHelper::create_html_editor('magazine_content', $rt['template_content']);

                return $this->display('magazine_list_add');
            } elseif ($_POST['step'] === 2) {
                $magazine_name = trim($_POST['magazine_name']);
                $magazine_content = trim($_POST['magazine_content']);
                $magazine_content = str_replace('src=\"', 'src=\"http://'.$_SERVER['HTTP_HOST'], $magazine_content);
                $time = TimeHelper::gmtime();
                DB::table('email_template')
                    ->where('type', 'magazine')
                    ->where('template_id', $id)
                    ->update(['is_html' => 1, 'template_subject' => $magazine_name, 'template_content' => $magazine_content, 'last_modify' => $time]);
                $links[] = ['text' => lang('magazine_list'), 'href' => 'magazine_list.php?act=list'];

                return $this->sys_msg(lang('edit_ok'), 0, $links);
            }
        }

        if ($action === 'del') {
            $id = intval($_REQUEST['id']);
            DB::table('email_template')->where('type', 'magazine')->where('template_id', $id)->limit(1)->delete();
            $links[] = ['text' => lang('magazine_list'), 'href' => 'magazine_list.php?act=list'];

            return $this->sys_msg(lang('edit_ok'), 0, $links);
        }

        if ($action === 'addtolist') {
            $id = intval($_REQUEST['id']);
            $pri = ! empty($_REQUEST['pri']) ? 1 : 0;
            $start = empty($_GET['start']) ? 0 : (int) $_GET['start'];
            $send_rank = $_REQUEST['send_rank'];
            $rank_array = explode('_', $send_rank);
            $template_id = DB::table('email_template')->where('type', 'magazine')->where('template_id', $id)->value('template_id');
            if (! empty($template_id)) {
                if ($rank_array['0'] === SEND_LIST) {
                    $count = DB::table('email_subscriber')->where('stat', 1)->count();
                    if ($count > $start) {
                        $query = DB::table('email_subscriber')->where('stat', 1)->select('email')->offset($start)->limit(100)->get();
                        $add = '';

                        $i = 0;
                        foreach ($query as $rt) {
                            $rt = (array) $rt;
                            $time = time();
                            $add .= $add ? ",('$rt[email]','$id','$pri','$time')" : "('$rt[email]','$id','$pri','$time')";
                            $i++;
                        }
                        if ($add) {
                            DB::statement('INSERT INTO '.ecs()->table('email_send').' (email,template_id,pri,last_send) VALUES '.$add);
                        }
                        if ($i === 100) {
                            $start = $start + 100;
                        } else {
                            $start = $start + $i;
                        }
                        $links[] = ['text' => sprintf(lang('finish_list'), $start), 'href' => "magazine_list.php?act=addtolist&id=$id&pri=$pri&start=$start&send_rank=$send_rank"];

                        return $this->sys_msg(lang('finishing'), 0, $links);
                    } else {
                        DB::table('email_template')->where('type', 'magazine')->where('template_id', $id)->update(['last_send' => time()]);
                        $links[] = ['text' => lang('magazine_list'), 'href' => 'magazine_list.php?act=list'];

                        return $this->sys_msg(lang('edit_ok'), 0, $links);
                    }
                } else {
                    $row = (array) DB::table('user_rank')->where('rank_id', (int) $rank_array['1'])->select('special_rank')->first();
                    if ($rank_array['0'] === SEND_USER) {
                        $count = DB::table('user')->where('is_validated', 1)->count();
                        $query = DB::table('user')->where('is_validated', 1)->pluck('email')->skip($start)->take(100);
                    } elseif ($row['special_rank']) {
                        $count = DB::table('user')->where('is_validated', 1)->where('user_rank', $rank_array['1'])->count();
                        $query = DB::table('user')->where('is_validated', 1)->where('user_rank', $rank_array['1'])->pluck('email')->skip($start)->take(100);
                    } else {
                        $count = DB::table('user as u')
                            ->leftJoin('user_rank as ur', function ($join) {
                                $join->where('ur.special_rank', 0)->whereRaw('ur.min_points <= u.rank_points AND ur.max_points > u.rank_points');
                            })
                            ->where('ur.rank_id', $rank_array['1'])
                            ->where('u.is_validated', 1)
                            ->count();
                        $query = DB::table('user as u')
                            ->leftJoin('user_rank as ur', function ($join) {
                                $join->where('ur.special_rank', 0)->whereRaw('ur.min_points <= u.rank_points AND ur.max_points > u.rank_points');
                            })
                            ->where('ur.rank_id', $rank_array['1'])
                            ->where('u.is_validated', 1)
                            ->pluck('u.email')
                            ->skip($start)
                            ->take(100);
                    }

                    if ($count > $start) {
                        $insertData = [];
                        $i = 0;
                        $time = time();
                        foreach ($query as $email) {
                            $insertData[] = [
                                'email' => $email,
                                'template_id' => $id,
                                'pri' => $pri,
                                'last_send' => $time,
                            ];
                            $i++;
                        }
                        if (! empty($insertData)) {
                            DB::table('email_send')->insert($insertData);
                        }
                        if ($i === 100) {
                            $start = $start + 100;
                        } else {
                            $start = $start + $i;
                        }
                        $links[] = ['text' => sprintf(lang('finish_list'), $start), 'href' => "magazine_list.php?act=addtolist&id=$id&pri=$pri&start=$start&send_rank=$send_rank"];

                        return $this->sys_msg(lang('finishing'), 0, $links);
                    } else {
                        DB::table('email_template')->where('type', 'magazine')->where('template_id', $id)->update(['last_send' => time()]);
                        $links[] = ['text' => lang('magazine_list'), 'href' => 'magazine_list.php?act=list'];

                        return $this->sys_msg(lang('edit_ok'), 0, $links);
                    }
                }
            } else {
                $links[] = ['text' => lang('magazine_list'), 'href' => 'magazine_list.php?act=list'];

                return $this->sys_msg(lang('edit_ok'), 0, $links);
            }
        }
    }

    private function get_magazine()
    {
        $result = MainHelper::get_filter();

        if ($result === false) {
            $filter['sort_by'] = empty($_REQUEST['sort_by']) ? 'template_id' : trim($_REQUEST['sort_by']);
            $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);

            $filter['record_count'] = DB::table('email_template')->where('type', 'magazine')->count();

            // 分页大小
            $filter = MainHelper::page_and_size($filter);

            // 查询
            MainHelper::set_filter($filter, '');
        } else {
            $sql = $result['sql'];
            $filter = $result['filter'];
        }

        $magazinedb = DB::table('email_template')
            ->where('type', 'magazine')
            ->orderBy($filter['sort_by'], $filter['sort_order'])
            ->offset($filter['start'])
            ->limit($filter['page_size'])
            ->get();

        foreach ($magazinedb as $k => $v) {
            $v = (array) $v;
            $magazinedb[$k] = $v;
            $magazinedb[$k]['last_modify'] = TimeHelper::local_date('Y-m-d', $v['last_modify']);
            $magazinedb[$k]['last_send'] = TimeHelper::local_date('Y-m-d', $v['last_send']);
        }

        $arr = ['magazinedb' => $magazinedb, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']];

        return $arr;
    }
}
