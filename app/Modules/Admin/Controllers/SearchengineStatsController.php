<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Helpers\BaseHelper;
use App\Helpers\TimeHelper;
use App\Modules\Admin\Helpers\MainHelper;
use Illuminate\Http\Request;

class SearchengineStatsController extends BaseController
{
    public function index(Request $request)
    {
        lang([dirname(__DIR__).'/Languages/zh-CN/statistic.php']);

        $action = $request->get('act');

        if ($action === 'view') {
            $this->admin_priv('client_flow_stats');

            // 时间参数
            // TODO: 时间需要改
            if (isset($_POST) && ! empty($_POST)) {
                $start_date = $_POST['start_date'];
                $end_date = $_POST['end_date'];
            } else {
                $start_date = TimeHelper::local_date('Y-m-d', strtotime('-1 week'));
                $end_date = TimeHelper::local_date('Y-m-d');
            }

            // 综合流量
            $max = 0;
            $general_xml = "<chart caption='$_LANG[tab_keywords]' shownames='1' showvalues='0' decimals='0' numberPrefix='' outCnvBaseFontSize='12' baseFontSize='12'>";
            $query = DB::table('search_keywords')
                ->select('keyword', 'count', 'searchengine')
                ->where('date', '>=', $start_date)
                ->where('date', '<=', $end_date);
            if (isset($_POST['filter'])) {
                $query->whereIn('searchengine', $_POST['filter']);
            }
            $res = $query->get();
            $search = [];
            $searchengine = [];
            $keyword = [];

            foreach ($res as $val) {
                $keyword[$val['keyword']] = 1;
                $searchengine[$val['searchengine']][$val['keyword']] = $val['count'];
            }

            $general_xml .= '<categories>';
            foreach ($keyword as $key => $val) {
                $key = str_replace('&', '＆', $key);
                $key = str_replace('>', '＞', $key);
                $key = str_replace('<', '＜', $key);
                $key = htmlspecialchars($key);
                $general_xml .= "<category label='".str_replace('\'', '', $key)."' />";
            }
            $general_xml .= "</categories>\n";

            $i = 0;

            foreach ($searchengine as $key => $val) {
                $general_xml .= "<dataset seriesName='$key' color='".MainHelper::chart_color($i)."' showValues='0'>";
                foreach ($keyword as $k => $v) {
                    $count = 0;
                    if (! empty($searchengine[$key][$k])) {
                        $count = $searchengine[$key][$k];
                    }
                    $general_xml .= "<set value='$count' />";
                }
                $general_xml .= '</dataset>';
                $i++;
            }

            $general_xml .= '</chart>';

            $this->assign('ur_here', lang('searchengine_stats'));
            $this->assign('general_data', $general_xml);

            $searchengines = [
                'phpmall' => false,
                'MSLIVE' => false,
                'BAIDU' => false,
                'GOOGLE' => false,
                'GOOGLE CHINA' => false,
                'CT114' => false,
                'SOSO' => false,
            ];

            if (isset($_POST['filter'])) {
                foreach ($_POST['filter'] as $v) {
                    $searchengines[$v] = true;
                }
            }
            $this->assign('searchengines', $searchengines);

            $this->assign('start_date', $start_date);
            $this->assign('end_date', $end_date);

            $filename = str_replace('-', '', $start_date.'_'.$end_date);
            $this->assign('action_link', ['text' => lang('down_search_stats'), 'href' => 'searchengine_stats.php?act=download&start_date='.$start_date.'&end_date='.$end_date.'&filename='.$filename]);

            return $this->display('searchengine_stats');
        }

        if ($action === 'download') {
            $start_date = empty($_REQUEST['start_date']) ? strtotime('-20 day') : intval($_REQUEST['start_date']);
            $end_date = empty($_REQUEST['end_date']) ? time() : intval($_REQUEST['end_date']);

            $filename = $start_date.'_'.$end_date;
            $res = DB::table('search_keywords')
                ->select('keyword', 'count', 'searchengine')
                ->where('date', '>=', $start_date)
                ->where('date', '<=', $end_date)
                ->get();

            $searchengine = [];
            $keyword = [];

            foreach ($res as $val) {
                $keyword[$val['keyword']] = 1;
                $searchengine[$val['searchengine']][$val['keyword']] = $val['count'];
            }
            header('Content-type: application/vnd.ms-excel; charset=utf-8');
            header("Content-Disposition: attachment; filename=$filename.xls");
            $data = "\t";
            foreach ($searchengine as $k => $v) {
                $data .= "$k\t";
            }
            foreach ($keyword as $kw => $val) {
                $data .= "\n$kw\t";
                foreach ($searchengine as $k => $v) {
                    if (isset($searchengine[$k][$kw])) {
                        $data .= $searchengine[$k][$kw]."\t";
                    } else {
                        $data .= '0'."\t";
                    }
                }
            }
            echo BaseHelper::ecs_iconv(EC_CHARSET, 'GB2312', $data)."\t";
        }
    }
}
