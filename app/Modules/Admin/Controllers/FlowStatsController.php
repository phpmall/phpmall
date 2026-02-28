<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Helpers\BaseHelper;
use App\Helpers\TimeHelper;
use App\Modules\Admin\Helpers\MainHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FlowStatsController extends BaseController
{
    public function index(Request $request)
    {
        lang([dirname(__DIR__).'/Languages/zh-CN/statistic.php']);

        $action = $request->get('act');

        if ($action === 'view') {
            if (cfg('visit_stats') === 'off') {
                return $this->sys_msg(lang('stats_off'));
            }
            $this->admin_priv('client_flow_stats');
            $is_multi = empty($_POST['is_multi']) ? false : true;

            // 时间参数
            if (isset($_POST['start_date']) && ! empty($_POST['end_date'])) {
                $start_date = TimeHelper::local_strtotime($_POST['start_date']);
                $end_date = TimeHelper::local_strtotime($_POST['end_date']);
            } else {
                $today = TimeHelper::local_strtotime(TimeHelper::local_date('Y-m-d'));
                $start_date = $today - 86400 * 7;
                $end_date = $today;
            }

            $start_date_arr = [];
            $end_date_arr = [];
            if (! empty($_POST['year_month'])) {
                $tmp = $_POST['year_month'];

                for ($i = 0; $i < count($tmp); $i++) {
                    if (! empty($tmp[$i])) {
                        $tmp_time = TimeHelper::local_strtotime($tmp[$i].'-1');
                        $start_date_arr[] = $tmp_time;
                        $end_date_arr[] = TimeHelper::local_strtotime($tmp[$i].'-'.date('t', $tmp_time));
                    }
                }
            } else {
                $tmp_time = TimeHelper::local_strtotime(TimeHelper::local_date('Y-m-d'));
                $start_date_arr[] = TimeHelper::local_strtotime(TimeHelper::local_date('Y-m').'-1');
                $end_date_arr[] = TimeHelper::local_strtotime(TimeHelper::local_date('Y-m').'-31');
            }

            /**
             * 综合流量
             */
            $max = 0;

            if (! $is_multi) {
                $general_xml = "<graph caption='".lang('general_stats')."' shownames='1' showvalues='1' decimalPrecision='0' yaxisminvalue='0' yaxismaxvalue='%d' animation='1' outCnvBaseFontSize='12' baseFontSize='12' xaxisname='".lang('date')."' yaxisname='".lang('access_count')."' >";

                $res = DB::table('shop_stats')
                    ->select(DB::raw("FLOOR((access_time - $start_date) / (24 * 3600)) AS sn"), 'access_time', DB::raw('COUNT(*) AS access_count'))
                    ->where('access_time', '>=', $start_date)
                    ->where('access_time', '<=', $end_date + 86400)
                    ->groupBy('sn')
                    ->get()
                    ->map(fn ($item) => (array) $item)
                    ->all();

                $key = 0;

                foreach ($res as $val) {
                    $val['access_date'] = gmdate('m-d', $val['access_time'] + intval($timezone) * 3600);
                    $general_xml .= "<set name='$val[access_date]' value='$val[access_count]' color='".MainHelper::chart_color($key)."' />";
                    if ($val['access_count'] > $max) {
                        $max = $val['access_count'];
                    }
                    $key++;
                }

                $general_xml .= '</graph>';
                $general_xml = sprintf($general_xml, $max);
            } else {
                $general_xml = "<graph caption='".lang('general_stats')."' lineThickness='1' showValues='0' formatNumberScale='0' anchorRadius='2'   divLineAlpha='20' divLineColor='CC3300' divLineIsDashed='1' showAlternateHGridColor='1' alternateHGridAlpha='5' alternateHGridColor='CC3300' shadowAlpha='40' labelStep='2' numvdivlines='5' chartRightMargin='35' bgColor='FFFFFF,CC3300' bgAngle='270' bgAlpha='10,10' outCnvBaseFontSize='12' baseFontSize='12' >";
                foreach ($start_date_arr as $k => $val) {
                    $seriesName = TimeHelper::local_date('Y-m', $start_date_arr[$k]);
                    $general_xml .= "<dataset seriesName='$seriesName' color='".MainHelper::chart_color($k)."' anchorBorderColor='".MainHelper::chart_color($k)."' anchorBgColor='".MainHelper::chart_color($k)."'>";

                    $res = DB::table('shop_stats')
                        ->select(DB::raw("FLOOR((access_time - $start_date_arr[$k]) / (24 * 3600)) AS sn"), 'access_time', DB::raw('COUNT(*) AS access_count'))
                        ->where('access_time', '>=', $start_date_arr[$k])
                        ->where('access_time', '<=', $end_date_arr[$k] + 86400)
                        ->groupBy('sn')
                        ->get()
                        ->map(fn ($item) => (array) $item)
                        ->all();

                    $lastDay = 0;

                    foreach ($res as $val) {
                        $day = gmdate('d', $val['access_time'] + $timezone * 3600);

                        if ($lastDay === 0) {
                            $time_span = (($day - 1) - $lastDay);
                            $lastDay++;
                            for (; $lastDay < $day; $lastDay++) {
                                $general_xml .= "<set value='0' />";
                            }
                        }
                        $general_xml .= "<set value='$val[access_count]' />";
                        $lastDay = $day;
                    }

                    $general_xml .= '</dataset>';
                }

                $general_xml .= '<categories>';

                for ($i = 1; $i <= 31; $i++) {
                    $general_xml .= "<category label='$i' />";
                }
                $general_xml .= '</categories>';
                $general_xml .= '</graph>';
            }

            /**
             * 地域分布
             */
            $area_xml = '';

            if (! $is_multi) {
                $area_xml .= "<graph caption='".lang('area_stats')."' shownames='1' showvalues='1' decimalPrecision='2' outCnvBaseFontSize='13' baseFontSize='13' pieYScale='45'  pieBorderAlpha='40' pieFillAlpha='70' pieSliceDepth='15' pieRadius='100' bgAngle='460'>";

                $res = DB::table('shop_stats')
                    ->select(DB::raw('COUNT(*) AS access_count'), 'area')
                    ->where('access_time', '>=', $start_date)
                    ->where('access_time', '<', $end_date + 86400)
                    ->groupBy('area')
                    ->orderByDesc('access_count')
                    ->limit(20)
                    ->get()
                    ->map(fn ($item) => (array) $item)
                    ->all();

                $key = 0;
                foreach ($res as $val) {
                    $area = empty($val['area']) ? 'unknow' : $val['area'];

                    $area_xml .= "<set name='$area' value='$val[access_count]' color='".MainHelper::chart_color($key)."' />";
                    $key++;
                }
                $area_xml .= '</graph>';
                $res = DB::table('shop_stats')
                    ->select('access_time', 'area')
                    ->where(function ($query) use ($start_date_arr, $end_date_arr) {
                        foreach ($start_date_arr as $k => $val) {
                            $query->orWhere(function ($q) use ($start_date_arr, $end_date_arr, $k) {
                                $q->where('access_time', '>=', $start_date_arr[$k])
                                    ->where('access_time', '<=', $end_date_arr[$k] + 86400);
                            });
                        }
                    })
                    ->get()
                    ->map(fn ($item) => (array) $item)
                    ->all();
                $area_arr = [];
                foreach ($res as $val) {
                    $date = TimeHelper::local_date('Y-m', $val['access_time']);
                    $area_arr[$val['area']] = null;
                    if (isset($category[$date][$val['area']])) {
                        $category[$date][$val['area']]++;
                    } else {
                        $category[$date][$val['area']] = 1;
                    }
                }
                $area_xml = "<chart palette='2' caption='".lang('area_stats')."' shownames='1' showvalues='0' numberPrefix='' useRoundEdges='1' legendBorderAlpha='0' outCnvBaseFontSize='13' baseFontSize='13'>";
                $area_xml .= '<categories>';
                foreach ($area_arr as $k => $v) {
                    $area_xml .= "<category label='$k'/>";
                }
                $area_xml .= '</categories>';
                $key = 0;
                foreach ($start_date_arr as $val) {
                    $key++;
                    $date = TimeHelper::local_date('Y-m', $val);
                    $area_xml .= "<dataset seriesName='$date' color='".MainHelper::chart_color($key)."' showValues='0'>";

                    foreach ($area_arr as $k => $v) {
                        if (isset($category[$date][$k])) {
                            $area_xml .= "<set value='".$category[$date][$k]."'/>";
                        } else {
                            $area_xml .= "<set value='0'/>";
                        }
                    }
                    $area_xml .= '</dataset>';
                }
                $area_xml .= '</chart>';
            }

            /**
             * 来源网站
             */
            if (! $is_multi) {
                $from_xml = "<graph caption='".lang('from_stats')."' shownames='1' showvalues='1' decimalPrecision='2' outCnvBaseFontSize='12' baseFontSize='12' pieYScale='45' pieBorderAlpha='40' pieFillAlpha='70' pieSliceDepth='15' pieRadius='100' bgAngle='460'>";

                $res = DB::table('shop_stats')
                    ->select(DB::raw('COUNT(*) AS access_count'), 'referer_domain')
                    ->where('access_time', '>=', $start_date)
                    ->where('access_time', '<=', $end_date + 86400)
                    ->groupBy('referer_domain')
                    ->orderByDesc('access_count')
                    ->limit(20)
                    ->get()
                    ->map(fn ($item) => (array) $item)
                    ->all();

                $key = 0;

                foreach ($res as $val) {
                    $from = empty($val['referer_domain']) ? lang('input_url') : $val['referer_domain'];

                    $from_xml .= "<set name='".str_replace(['http://', 'https://'], ['', ''], $from)."' value='$val[access_count]' color='".MainHelper::chart_color($key)."' />";

                    $key++;
                }

                $from_xml .= '</graph>';
            } else {
                $res = DB::table('shop_stats')
                    ->select('access_time', 'referer_domain')
                    ->where(function ($query) use ($start_date_arr, $end_date_arr) {
                        foreach ($start_date_arr as $k => $val) {
                            $query->orWhere(function ($q) use ($start_date_arr, $end_date_arr, $k) {
                                $q->where('access_time', '>=', $start_date_arr[$k])
                                    ->where('access_time', '<=', $end_date_arr[$k] + 86400);
                            });
                        }
                    })
                    ->get()
                    ->map(fn ($item) => (array) $item)
                    ->all();
                $domain_arr = [];
                foreach ($res as $val) {
                    $date = TimeHelper::local_date('Y-m', $val['access_time']);
                    $domain_arr[$val['referer_domain']] = null;
                    if (isset($category[$date][$val['referer_domain']])) {
                        $category[$date][$val['referer_domain']]++;
                    } else {
                        $category[$date][$val['referer_domain']] = 1;
                    }
                }
                $from_xml = "<chart palette='2' caption='".lang('from_stats')."' shownames='1' showvalues='0' numberPrefix='' useRoundEdges='1' legendBorderAlpha='0' outCnvBaseFontSize='13' baseFontSize='13'>";
                $from_xml .= '<categories>';
                foreach ($domain_arr as $k => $v) {
                    $from = $k === '' ? lang('input_url') : $k;
                    $from_xml .= "<category label='$from'/>";
                }
                $from_xml .= '</categories>';
                $key = 0;
                foreach ($start_date_arr as $val) {
                    $key++;
                    $date = TimeHelper::local_date('Y-m', $val);
                    $from_xml .= "<dataset seriesName='$date' color='".MainHelper::chart_color($key)."' showValues='0'>";

                    foreach ($domain_arr as $k => $v) {
                        if (isset($category[$date][$k])) {
                            $from_xml .= "<set value='".$category[$date][$k]."'/>";
                        } else {
                            $from_xml .= "<set value='0'/>";
                        }
                    }
                    $from_xml .= '</dataset>';
                }
                $from_xml .= '</chart>';
            }

            $this->assign('ur_here', lang('flow_stats'));
            $this->assign('general_data', $general_xml);
            $this->assign('area_data', $area_xml);
            $this->assign('is_multi', $is_multi);
            $this->assign('from_data', $from_xml);

            $this->assign('start_date', TimeHelper::local_date('Y-m-d', $start_date));
            $this->assign('end_date', TimeHelper::local_date('Y-m-d', $end_date));

            for ($i = 0; $i < 5; $i++) {
                if (isset($start_date_arr[$i])) {
                    $start_date_arr[$i] = TimeHelper::local_date('Y-m', $start_date_arr[$i]);
                } else {
                    $start_date_arr[$i] = null;
                }
            }
            $this->assign('start_date_arr', $start_date_arr);

            if (! $is_multi) {
                $filename = gmdate(cfg('date_format'), intval($start_date) + intval($timezone) * 3600).'_'.
                    gmdate(cfg('date_format'), intval($end_date) + intval($timezone) * 3600);

                $this->assign('action_link', [
                    'text' => lang('down_flow_stats'),
                    'href' => 'flow_stats.php?act=download&filename='.$filename.
                        '&start_date='.$start_date.'&end_date='.$end_date,
                ]);
            }

            return $this->display('flow_stats');
        }

        // 报表下载
        if ($_REQUEST['act'] = 'download') {
            $filename = ! empty($_REQUEST['filename']) ? trim($_REQUEST['filename']) : '';

            header('Content-type: application/vnd.ms-excel; charset=utf-8');
            header("Content-Disposition: attachment; filename=$filename.xls");
            $start_date = empty($_GET['start_date']) ? strtotime('-20 day') : intval($_GET['start_date']);
            $end_date = empty($_GET['end_date']) ? time() : intval($_GET['end_date']);
            $res = DB::table('shop_stats')
                ->select(DB::raw("FLOOR((access_time - $start_date) / (24 * 3600)) AS sn"), 'access_time', DB::raw('COUNT(*) AS access_count'))
                ->where('access_time', '>=', $start_date)
                ->where('access_time', '<=', $end_date + 86400)
                ->groupBy('sn')
                ->get()
                ->map(fn ($item) => (array) $item)
                ->all();

            $data = lang('general_stats')."\t\n";
            $data .= lang('date')."\t";
            $data .= lang('access_count')."\t\n";

            foreach ($res as $val) {
                $val['access_date'] = gmdate('m-d', $val['access_time'] + $timezone * 3600);
                $data .= $val['access_date']."\t";
                $data .= $val['access_count']."\t\n";
            }

            $res = DB::table('shop_stats')
                ->select(DB::raw('COUNT(*) AS access_count'), 'area')
                ->where('access_time', '>=', $start_date)
                ->where('access_time', '<=', $end_date + 86400)
                ->groupBy('area')
                ->orderByDesc('access_count')
                ->limit(20)
                ->get()
                ->map(fn ($item) => (array) $item)
                ->all();

            $data .= lang('area_stats')."\t\n";
            $data .= lang('area')."\t";
            $data .= lang('access_count')."\t\n";

            foreach ($res as $val) {
                $data .= $val['area']."\t";
                $data .= $val['access_count']."\t\n";
            }

            $res = DB::table('shop_stats')
                ->select(DB::raw('COUNT(*) AS access_count'), 'referer_domain')
                ->where('access_time', '>=', $start_date)
                ->where('access_time', '<=', $end_date + 86400)
                ->groupBy('referer_domain')
                ->orderByDesc('access_count')
                ->limit(20)
                ->get()
                ->map(fn ($item) => (array) $item)
                ->all();

            $data .= "\n".lang('from_stats')."\t\n";

            $data .= lang('url')."\t";
            $data .= lang('access_count')."\t\n";

            foreach ($res as $val) {
                $data .= ($val['referer_domain'] === '' ? lang('input_url') : $val['referer_domain'])."\t";
                $data .= $val['access_count']."\t\n";
            }
            if (EC_CHARSET != 'gbk') {
                echo BaseHelper::ecs_iconv(EC_CHARSET, 'gbk', $data)."\t";
            } else {
                echo $data."\t";
            }
        }
    }
}
