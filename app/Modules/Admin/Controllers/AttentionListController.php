<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Helpers\TimeHelper;
use App\Modules\Admin\Helpers\MainHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttentionListController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        $this->admin_priv('attention_list');
        if ($action === 'list') {
            $goodsdb = $this->get_attention();
            $this->assign('full_page', 1);
            $this->assign('ur_here', lang('attention_list'));
            $this->assign('goodsdb', $goodsdb['goodsdb']);
            $this->assign('filter', $goodsdb['filter']);
            $this->assign('cfg_lang', cfg('lang'));
            $this->assign('record_count', $goodsdb['record_count']);
            $this->assign('page_count', $goodsdb['page_count']);

            return $this->display('attention_list');
        }

        if ($action === 'query') {
            $goodsdb = $this->get_attention();
            $this->assign('goodsdb', $goodsdb['goodsdb']);
            $this->assign('filter', $goodsdb['filter']);
            $this->assign('record_count', $goodsdb['record_count']);
            $this->assign('page_count', $goodsdb['page_count']);

            return $this->make_json_result(
                $this->fetch('attention_list'),
                '',
                ['filter' => $goodsdb['filter'], 'page_count' => $goodsdb['page_count']]
            );
        }

        if ($action === 'addtolist') {
            $id = intval($_REQUEST['id']);
            $pri = (intval($_REQUEST['pri']) === 1) ? 1 : 0;
            $start = empty($_GET['start']) ? 0 : (int) $_GET['start'];

            $query = DB::table('goods as g')
                ->leftJoin('user_collect as c', 'g.goods_id', '=', 'c.goods_id')
                ->leftJoin('user as u', 'c.user_id', '=', 'u.user_id')
                ->where('c.is_attention', 1)
                ->where('g.is_delete', 0)
                ->where('c.goods_id', $id);

            $count = $query->count();

            if ($count > $start) {
                $res = $query->select('u.user_name', 'u.email', 'g.goods_name', 'g.goods_id')
                    ->offset($start)
                    ->limit(100)
                    ->get();

                $template = DB::table('email_template')
                    ->where('template_code', 'attention_list')
                    ->where('type', 'template')
                    ->first();
                $template = $template ? (array) $template : [];

                $email_data = [];
                $i = 0;
                foreach ($res as $rt) {
                    $rt = (array) $rt;
                    $time = time();
                    $goods_url = ecs()->url().build_uri('goods', ['gid' => $id], $rt['goods_name']);
                    $this->assign([
                        'user_name' => $rt['user_name'],
                        'goods_name' => $rt['goods_name'],
                        'goods_url' => $goods_url,
                        'shop_name' => cfg('shop_title'),
                        'send_date' => TimeHelper::local_date(cfg('date_format')),
                    ]);
                    $content = $this->fetch("str:{$template['template_content']}");
                    $email_data[] = [
                        'email' => $rt['email'],
                        'template_id' => $template['template_id'],
                        'email_content' => $content,
                        'pri' => $pri,
                        'last_send' => $time,
                    ];
                    $i++;
                }

                if (! empty($email_data)) {
                    DB::table('email_send')->insert($email_data);
                }

                if ($i === 100) {
                    $start = $start + 100;
                } else {
                    $start = $start + $i;
                }
                $links[] = ['text' => sprintf(lang('finish_list'), $start), 'href' => "attention_list.php?act=addtolist&id=$id&pri=$pri&start=$start"];

                return $this->sys_msg(lang('finishing'), 0, $links);
            } else {
                $links[] = ['text' => lang('attention_list'), 'href' => 'attention_list.php?act=list'];

                return $this->sys_msg(lang('edit_ok'), 0, $links);
            }
        }

        if ($action === 'batch_addtolist') {
            $olddate = $_REQUEST['date'];
            $date = TimeHelper::local_strtotime(trim($_REQUEST['date']));
            $pri = (intval($_REQUEST['pri']) === 1) ? 1 : 0;
            $start = empty($_GET['start']) ? 0 : (int) $_GET['start'];

            $query = DB::table('goods as g')
                ->leftJoin('user_collect as c', 'g.goods_id', '=', 'c.goods_id')
                ->leftJoin('user as u', 'c.user_id', '=', 'u.user_id')
                ->where('c.is_attention', 1)
                ->where('g.is_delete', 0)
                ->where('g.last_update', '>=', $date);

            $count = $query->count();

            if ($count > $start) {
                $res = $query->select('u.user_name', 'u.email', 'g.goods_name', 'g.goods_id')
                    ->offset($start)
                    ->limit(100)
                    ->get();

                $template = DB::table('email_template')
                    ->where('template_code', 'attention_list')
                    ->where('type', 'template')
                    ->first();
                $template = $template ? (array) $template : [];

                $email_data = [];
                $i = 0;
                foreach ($res as $rt) {
                    $rt = (array) $rt;
                    $time = time();
                    $goods_url = ecs()->url().build_uri('goods', ['gid' => $rt['goods_id']], $rt['user_name']);
                    $this->assign(['user_name' => $rt['user_name'], 'goods_name' => $rt['goods_name'], 'goods_url' => $goods_url]);
                    $content = $this->fetch("str:{$template['template_content']}");
                    $email_data[] = [
                        'email' => $rt['email'],
                        'template_id' => $template['template_id'],
                        'email_content' => $content,
                        'pri' => $pri,
                        'last_send' => $time,
                    ];
                    $i++;
                }

                if (! empty($email_data)) {
                    DB::table('email_send')->insert($email_data);
                }

                if ($i === 100) {
                    $start = $start + 100;
                } else {
                    $start = $start + $i;
                }
                $links[] = ['text' => sprintf(lang('finish_list'), $start), 'href' => "attention_list.php?act=batch_addtolist&date=$olddate&pri=$pri&start=$start"];

                return $this->sys_msg(lang('finishing'), 0, $links);
            } else {
                $links[] = ['text' => lang('attention_list'), 'href' => 'attention_list.php?act=list'];

                return $this->sys_msg(lang('edit_ok'), 0, $links);
            }
        }
    }

    private function get_attention()
    {
        $result = MainHelper::get_filter();

        if ($result === false) {
            $query = DB::table('user_collect as c')
                ->leftJoin('goods as g', 'c.goods_id', '=', 'g.goods_id')
                ->where('c.is_attention', 1)
                ->where('g.is_delete', 0);

            if (! empty($_POST['goods_name'])) {
                $goods_name = trim($_POST['goods_name']);
                $query->where('g.goods_name', 'like', '%'.$goods_name.'%');
                $filter['goods_name'] = $goods_name;
            }

            $filter['sort_by'] = empty($_REQUEST['sort_by']) ? 'last_update' : trim($_REQUEST['sort_by']);
            $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);

            $filter['record_count'] = $query->distinct('c.goods_id')->count('c.goods_id');

            // 分页大小
            $filter = MainHelper::page_and_size($filter);

            // 查询
            $res = $query->select('c.goods_id', 'g.goods_name', 'g.last_update')
                ->distinct()
                ->orderBy($filter['sort_by'], $filter['sort_order'])
                ->offset($filter['start'])
                ->limit($filter['page_size'])
                ->get();

            // MainHelper::set_filter($filter, $sql); // Cannot easily set_filter for Query Builder without serializing it.
        } else {
            $res = DB::select($result['sql']);
            $filter = $result['filter'];
        }

        $goodsdb = [];
        foreach ($res as $v) {
            $v = (array) $v;
            $v['last_update'] = TimeHelper::local_date('Y-m-d', $v['last_update']);
            $goodsdb[] = $v;
        }

        $arr = ['goodsdb' => $goodsdb, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']];

        return $arr;
    }
}
