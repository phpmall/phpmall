<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Modules\Admin\Helpers\MainHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmailListController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        $this->admin_priv('email_list');

        if ($action === 'list') {
            $emaildb = $this->get_email_list();
            $this->assign('full_page', 1);
            $this->assign('ur_here', lang('email_list'));
            $this->assign('emaildb', $emaildb['emaildb']);
            $this->assign('filter', $emaildb['filter']);
            $this->assign('record_count', $emaildb['record_count']);
            $this->assign('page_count', $emaildb['page_count']);

            return $this->display('email_list');
        }

        if ($action === 'export') {
            $emails = DB::table('email_subscriber')->where('stat', 1)->select('email')->get();
            $out = '';
            foreach ($emails as $key => $val) {
                $val = (array) $val;
                $out .= "$val[email]\n";
            }
            $contentType = 'text/plain';
            $len = strlen($out);
            header('Last-Modified: '.gmdate('D, d M Y H:i:s', time() + 31536000).' GMT');
            header('Pragma: no-cache');
            header('Content-Encoding: none');
            header('Content-type: '.$contentType);
            header('Content-Length: '.$len);
            header('Content-Disposition: attachment; filename="email_list.txt"');
            echo $out;
            exit;
        }

        if ($action === 'query') {
            $emaildb = $this->get_email_list();
            $this->assign('emaildb', $emaildb['emaildb']);
            $this->assign('filter', $emaildb['filter']);
            $this->assign('record_count', $emaildb['record_count']);
            $this->assign('page_count', $emaildb['page_count']);

            $sort_flag = MainHelper::sort_flag($emaildb['filter']);
            $this->assign($sort_flag['tag'], $sort_flag['img']);

            return $this->make_json_result(
                $this->fetch('email_list'),
                '',
                ['filter' => $emaildb['filter'], 'page_count' => $emaildb['page_count']]
            );
        }

        /**
         * 批量删除
         */
        if ($action === 'batch_remove') {
            if (! isset($_POST['checkboxes']) || ! is_array($_POST['checkboxes'])) {
                return $this->sys_msg(lang('no_select_email'), 1);
            }

            $affected_rows = DB::table('email_subscriber')->whereIn('id', $_POST['checkboxes'])->delete();

            $lnk[] = ['text' => lang('back_list'), 'href' => 'email_list.php?act=list'];

            return $this->sys_msg(sprintf(lang('batch_remove_succeed'), $affected_rows), 0, $lnk);
        }

        /**
         * 批量恢复
         */
        if ($action === 'batch_unremove') {
            if (! isset($_POST['checkboxes']) || ! is_array($_POST['checkboxes'])) {
                return $this->sys_msg(lang('no_select_email'), 1);
            }

            $affected_rows = DB::table('email_subscriber')
                ->where('stat', '<>', 1)
                ->whereIn('id', $_POST['checkboxes'])
                ->update(['stat' => 1]);

            $lnk[] = ['text' => lang('back_list'), 'href' => 'email_list.php?act=list'];

            return $this->sys_msg(sprintf(lang('batch_unremove_succeed'), $affected_rows), 0, $lnk);
        }

        /**
         * 批量退订
         */
        if ($action === 'batch_exit') {
            if (! isset($_POST['checkboxes']) || ! is_array($_POST['checkboxes'])) {
                return $this->sys_msg(lang('no_select_email'), 1);
            }

            $affected_rows = DB::table('email_subscriber')
                ->where('stat', '<>', 2)
                ->whereIn('id', $_POST['checkboxes'])
                ->update(['stat' => 2]);

            $lnk[] = ['text' => lang('back_list'), 'href' => 'email_list.php?act=list'];

            return $this->sys_msg(sprintf(lang('batch_exit_succeed'), $affected_rows), 0, $lnk);
        }
    }

    private function get_email_list()
    {
        $result = MainHelper::get_filter();
        if ($result === false) {
            $filter['sort_by'] = empty($_REQUEST['sort_by']) ? 'stat' : trim($_REQUEST['sort_by']);
            $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'ASC' : trim($_REQUEST['sort_order']);

            $filter['record_count'] = DB::table('email_subscriber')->count();

            // 分页大小
            $filter = MainHelper::page_and_size($filter);

            // 查询
            $res = DB::table('email_subscriber')
                ->orderBy($filter['sort_by'], $filter['sort_order'])
                ->offset($filter['start'])
                ->limit($filter['page_size'])
                ->get();

            $emaildb = [];
            foreach ($res as $row) {
                $emaildb[] = (array) $row;
            }

            MainHelper::set_filter($filter, '');
        } else {
            $filter = $result['filter'];
            $res = DB::table('email_subscriber')
                ->orderBy($filter['sort_by'], $filter['sort_order'])
                ->offset($filter['start'])
                ->limit($filter['page_size'])
                ->get();

            $emaildb = [];
            foreach ($res as $row) {
                $emaildb[] = (array) $row;
            }
        }

        $arr = ['emaildb' => $emaildb, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']];

        return $arr;
    }
}
