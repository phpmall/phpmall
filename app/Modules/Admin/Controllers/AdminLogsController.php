<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Helpers\TimeHelper;
use App\Modules\Admin\Helpers\MainHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminLogsController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');

        /**
         * 获取所有日志列表
         */
        if ($action === 'list') {
            // 权限的判断
            $this->admin_priv('logs_manage');

            $user_id = ! empty($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
            $admin_ip = ! empty($_REQUEST['ip']) ? $_REQUEST['ip'] : '';
            $log_date = ! empty($_REQUEST['log_date']) ? $_REQUEST['log_date'] : '';

            // 查询IP地址列表
            $ip_list = [];
            $res = DB::table('admin_log')->distinct()->pluck('ip_address')->all();
            foreach ($res as $ip) {
                $ip_list[$ip] = $ip;
            }

            $this->assign('ur_here', lang('admin_logs'));
            $this->assign('ip_list', $ip_list);
            $this->assign('full_page', 1);

            $log_list = $this->get_admin_logs();

            $this->assign('log_list', $log_list['list']);
            $this->assign('filter', $log_list['filter']);
            $this->assign('record_count', $log_list['record_count']);
            $this->assign('page_count', $log_list['page_count']);

            $sort_flag = MainHelper::sort_flag($log_list['filter']);
            $this->assign($sort_flag['tag'], $sort_flag['img']);

            return $this->display('admin_logs');
        }

        /**
         * 排序、分页、查询
         */
        if ($action === 'query') {
            $log_list = $this->get_admin_logs();

            $this->assign('log_list', $log_list['list']);
            $this->assign('filter', $log_list['filter']);
            $this->assign('record_count', $log_list['record_count']);
            $this->assign('page_count', $log_list['page_count']);

            $sort_flag = MainHelper::sort_flag($log_list['filter']);
            $this->assign($sort_flag['tag'], $sort_flag['img']);

            return $this->make_json_result(
                $this->fetch('admin_logs'),
                '',
                ['filter' => $log_list['filter'], 'page_count' => $log_list['page_count']]
            );
        }

        /**
         * 批量删除日志记录
         */
        if ($action === 'batch_drop') {
            $this->admin_priv('logs_drop');

            $drop_type_date = isset($_POST['drop_type_date']) ? $_POST['drop_type_date'] : '';

            // 按日期删除日志
            if ($drop_type_date) {
                if ($_POST['log_date'] === '0') {
                    return response()->redirectTo('admin_logs.php?act=list');
                } elseif ($_POST['log_date'] > '0') {
                    $query = DB::table('admin_log');
                    switch ($_POST['log_date']) {
                        case '1':
                            $a_week = TimeHelper::gmtime() - (3600 * 24 * 7);
                            $query->where('log_time', '<=', $a_week);
                            break;
                        case '2':
                            $a_month = TimeHelper::gmtime() - (3600 * 24 * 30);
                            $query->where('log_time', '<=', $a_month);
                            break;
                        case '3':
                            $three_month = TimeHelper::gmtime() - (3600 * 24 * 90);
                            $query->where('log_time', '<=', $three_month);
                            break;
                        case '4':
                            $half_year = TimeHelper::gmtime() - (3600 * 24 * 180);
                            $query->where('log_time', '<=', $half_year);
                            break;
                        case '5':
                            $a_year = TimeHelper::gmtime() - (3600 * 24 * 365);
                            $query->where('log_time', '<=', $a_year);
                            break;
                    }

                    if ($query->delete()) {
                        $this->admin_log('', 'remove', 'adminlog');

                        $link[] = ['text' => lang('back_list'), 'href' => 'admin_logs.php?act=list'];

                        return $this->sys_msg(lang('drop_sueeccud'), 1, $link);
                    }
                }
            } // 如果不是按日期来删除, 就按ID删除日志
            else {
                $ids = (array) ($_POST['checkboxes'] ?? []);
                $count = count($ids);

                if ($count > 0) {
                    DB::table('admin_log')->whereIn('log_id', $ids)->delete();

                    $this->admin_log('', 'remove', 'adminlog');

                    $link[] = ['text' => lang('back_list'), 'href' => 'admin_logs.php?act=list'];

                    return $this->sys_msg(sprintf(lang('batch_drop_success'), $count), 0, $link);
                }
            }
        }
    }

    // 获取管理员操作记录
    private function get_admin_logs()
    {
        $user_id = ! empty($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
        $admin_ip = ! empty($_REQUEST['ip']) ? $_REQUEST['ip'] : '';

        $filter = [];
        $filter['sort_by'] = empty($_REQUEST['sort_by']) ? 'al.log_id' : trim($_REQUEST['sort_by']);
        $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);

        // 查询条件
        $query = DB::table('admin_log as al');
        if (! empty($user_id)) {
            $query->where('al.user_id', $user_id);
        } elseif (! empty($admin_ip)) {
            $query->where('al.ip_address', $admin_ip);
        }

        // 获得总记录数据
        $filter['record_count'] = $query->count();

        $filter = MainHelper::page_and_size($filter);

        // 获取管理员日志记录
        $res = $query->leftJoin('admin_user as u', 'u.user_id', '=', 'al.user_id')
            ->select('al.*', 'u.user_name')
            ->orderBy($filter['sort_by'], $filter['sort_order'])
            ->offset($filter['start'])
            ->limit($filter['page_size'])
            ->get();

        $list = [];
        foreach ($res as $rows) {
            $rows = (array) $rows;
            $rows['log_time'] = TimeHelper::local_date(cfg('time_format'), $rows['log_time']);

            $list[] = $rows;
        }

        return ['list' => $list, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']];
    }
}
