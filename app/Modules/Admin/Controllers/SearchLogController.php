<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Helpers\TimeHelper;
use Illuminate\Http\Request;

class SearchLogController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        $this->admin_priv('search_log');

        if ($action === 'list') {
            $logdb = $this->get_search_log();
            $this->assign('ur_here', lang('search_log'));
            $this->assign('full_page', 1);
            $this->assign('logdb', $logdb['logdb']);
            $this->assign('filter', $logdb['filter']);
            $this->assign('record_count', $logdb['record_count']);
            $this->assign('page_count', $logdb['page_count']);
            $this->assign('start_date', TimeHelper::local_date('Y-m-d'));
            $this->assign('end_date', TimeHelper::local_date('Y-m-d'));

            return $this->display('search_log_list');
        }

        if ($action === 'query') {
            $logdb = $this->get_search_log();
            $this->assign('full_page', 0);
            $this->assign('logdb', $logdb['logdb']);
            $this->assign('filter', $logdb['filter']);
            $this->assign('record_count', $logdb['record_count']);
            $this->assign('page_count', $logdb['page_count']);
            $this->assign('start_date', TimeHelper::local_date('Y-m-d'));
            $this->assign('end_date', TimeHelper::local_date('Y-m-d'));

            return $this->make_json_result(
                $this->fetch('search_log_list'),
                '',
                ['filter' => $logdb['filter'], 'page_count' => $logdb['page_count']]
            );
        }
    }

    private function get_search_log()
    {
        $where = '';
        if (isset($_REQUEST['start_dateYear']) && isset($_REQUEST['end_dateYear'])) {
            $start_date = $_POST['start_dateYear'].'-'.$_POST['start_dateMonth'].'-'.$_POST['start_dateDay'];
            $end_date = $_POST['end_dateYear'].'-'.$_POST['end_dateMonth'].'-'.$_POST['end_dateDay'];
            $where .= " AND date <= '$end_date' AND date >= '$start_date'";
            $filter['start_dateYear'] = $_REQUEST['start_dateYear'];
            $filter['start_dateMonth'] = $_REQUEST['start_dateMonth'];
            $filter['start_dateDay'] = $_REQUEST['start_dateDay'];

            $filter['end_dateYear'] = $_REQUEST['end_dateYear'];
            $filter['end_dateMonth'] = $_REQUEST['end_dateMonth'];
            $filter['end_dateDay'] = $_REQUEST['end_dateDay'];
        }

        $query = DB::table('search_keywords')->where('searchengine', 'phpmall');
        if ($where) {
            $query->whereRaw(ltrim($where, ' AND'));
        }
        $filter['record_count'] = $query->count();
        $logdb = [];
        $filter = MainHelper::page_and_size($filter);
        $query = DB::table('search_keywords')->where('searchengine', 'phpmall');
        if ($where) {
            $query->whereRaw(ltrim($where, ' AND'));
        }
        $query = $query->orderByDesc('date')
            ->orderByDesc('count')
            ->offset($filter['start'])
            ->limit($filter['page_size'])
            ->get();

        foreach ($query as $rt) {
            $logdb[] = $rt;
        }

        return ['logdb' => $logdb, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']];
    }
}
