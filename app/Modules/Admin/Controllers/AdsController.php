<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Helpers\BaseHelper;
use App\Helpers\TimeHelper;
use App\Libraries\Image;
use App\Modules\Admin\Helpers\MainHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdsController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        $image = new Image(cfg('bgcolor'));
        $allow_suffix = ['gif', 'jpg', 'png', 'jpeg', 'bmp', 'swf'];

        /**
         * 广告列表页面
         */
        if ($action === 'list') {
            $this->admin_priv('ad_manage');
            $pid = ! empty($_REQUEST['pid']) ? intval($_REQUEST['pid']) : 0;

            $this->assign('ur_here', lang('ad_list'));
            $this->assign('action_link', ['text' => lang('ads_add'), 'href' => 'ads.php?act=add']);
            $this->assign('pid', $pid);
            $this->assign('full_page', 1);

            $ads_list = $this->get_adslist();

            $this->assign('ads_list', $ads_list['ads']);
            $this->assign('filter', $ads_list['filter']);
            $this->assign('record_count', $ads_list['record_count']);
            $this->assign('page_count', $ads_list['page_count']);

            $sort_flag = MainHelper::sort_flag($ads_list['filter']);
            $this->assign($sort_flag['tag'], $sort_flag['img']);

            return $this->display('ads_list');
        }

        /**
         * 排序、分页、查询
         */
        if ($action === 'query') {
            $ads_list = $this->get_adslist();

            $this->assign('ads_list', $ads_list['ads']);
            $this->assign('filter', $ads_list['filter']);
            $this->assign('record_count', $ads_list['record_count']);
            $this->assign('page_count', $ads_list['page_count']);

            $sort_flag = MainHelper::sort_flag($ads_list['filter']);
            $this->assign($sort_flag['tag'], $sort_flag['img']);

            return $this->make_json_result(
                $this->fetch('ads_list'),
                '',
                ['filter' => $ads_list['filter'], 'page_count' => $ads_list['page_count']]
            );
        }

        /**
         * 添加新广告页面
         */
        if ($action === 'add') {
            $this->admin_priv('ad_manage');

            $ad_link = empty($_GET['ad_link']) ? '' : trim($_GET['ad_link']);
            $ad_name = empty($_GET['ad_name']) ? '' : trim($_GET['ad_name']);

            $start_time = TimeHelper::local_date('Y-m-d');
            $end_time = TimeHelper::local_date('Y-m-d', TimeHelper::gmtime() + 3600 * 24 * 30);  // 默认结束时间为1个月以后

            $this->assign(
                'ads',
                [
                    'ad_link' => $ad_link,
                    'ad_name' => $ad_name,
                    'start_time' => $start_time,
                    'end_time' => $end_time,
                    'enabled' => 1,
                ]
            );

            $this->assign('ur_here', lang('ads_add'));
            $this->assign('action_link', ['href' => 'ads.php?act=list', 'text' => lang('ad_list')]);
            $this->assign('position_list', MainHelper::get_position_list());

            $this->assign('form_act', 'insert');
            $this->assign('action', 'add');
            $this->assign('cfg_lang', cfg('lang'));

            return $this->display('ads_info');
        }

        /**
         * 新广告的处理
         */
        if ($action === 'insert') {
            $this->admin_priv('ad_manage');

            // 初始化变量
            $id = ! empty($_POST['id']) ? intval($_POST['id']) : 0;
            $type = ! empty($_POST['type']) ? intval($_POST['type']) : 0;
            $ad_name = ! empty($_POST['ad_name']) ? trim($_POST['ad_name']) : '';

            if ($_POST['media_type'] === '0') {
                $ad_link = ! empty($_POST['ad_link']) ? trim($_POST['ad_link']) : '';
            } else {
                $ad_link = ! empty($_POST['ad_link2']) ? trim($_POST['ad_link2']) : '';
            }

            // 获得广告的开始时期与结束日期
            $start_time = TimeHelper::local_strtotime($_POST['start_time']);
            $end_time = TimeHelper::local_strtotime($_POST['end_time']);

            // 查看广告名称是否有重复
            $exists = DB::table('ad')->where('ad_name', $ad_name)->exists();
            if ($exists) {
                $link[] = ['text' => lang('go_back'), 'href' => 'javascript:history.back(-1)'];

                return $this->sys_msg(lang('ad_name_exist'), 0, $link);
            }

            // 添加图片类型的广告
            if ($_POST['media_type'] === '0') {
                if ((isset($_FILES['ad_img']['error']) && $_FILES['ad_img']['error'] === 0) || (! isset($_FILES['ad_img']['error']) && isset($_FILES['ad_img']['tmp_name']) && $_FILES['ad_img']['tmp_name'] != 'none')) {
                    $ad_code = basename($image->upload_image($_FILES['ad_img'], 'afficheimg'));
                }
                if (! empty($_POST['img_url'])) {
                    $ad_code = $_POST['img_url'];
                }
                if (((isset($_FILES['ad_img']['error']) && $_FILES['ad_img']['error'] > 0) || (! isset($_FILES['ad_img']['error']) && isset($_FILES['ad_img']['tmp_name']) && $_FILES['ad_img']['tmp_name'] === 'none')) && empty($_POST['img_url'])) {
                    $link[] = ['text' => lang('go_back'), 'href' => 'javascript:history.back(-1)'];

                    return $this->sys_msg(lang('js_languages.ad_photo_empty'), 0, $link);
                }
            } // 如果添加的广告是Flash广告
            elseif ($_POST['media_type'] === '1') {
                if ((isset($_FILES['upfile_flash']['error']) && $_FILES['upfile_flash']['error'] === 0) || (! isset($_FILES['upfile_flash']['error']) && isset($_FILES['ad_img']['tmp_name']) && $_FILES['upfile_flash']['tmp_name'] != 'none')) {
                    // 检查文件类型
                    if ($_FILES['upfile_flash']['type'] != 'application/x-shockwave-flash') {
                        $link[] = ['text' => lang('go_back'), 'href' => 'javascript:history.back(-1)'];

                        return $this->sys_msg(lang('upfile_flash_type'), 0, $link);
                    }

                    // 生成文件名
                    $urlstr = date('Ymd');
                    for ($i = 0; $i < 6; $i++) {
                        $urlstr .= chr(mt_rand(97, 122));
                    }

                    $source_file = $_FILES['upfile_flash']['tmp_name'];
                    $target = ROOT_PATH.DATA_DIR.'/afficheimg/';
                    $file_name = $urlstr.'.swf';

                    if (! BaseHelper::move_upload_file($source_file, $target.$file_name)) {
                        $link[] = ['text' => lang('go_back'), 'href' => 'javascript:history.back(-1)'];

                        return $this->sys_msg(lang('upfile_error'), 0, $link);
                    } else {
                        $ad_code = $file_name;
                    }
                } elseif (! empty($_POST['flash_url'])) {
                    if (substr(strtolower($_POST['flash_url']), strlen($_POST['flash_url']) - 4) != '.swf') {
                        $link[] = ['text' => lang('go_back'), 'href' => 'javascript:history.back(-1)'];

                        return $this->sys_msg(lang('upfile_flash_type'), 0, $link);
                    }
                    $ad_code = $_POST['flash_url'];
                }

                if (((isset($_FILES['upfile_flash']['error']) && $_FILES['upfile_flash']['error'] > 0) || (! isset($_FILES['upfile_flash']['error']) && isset($_FILES['upfile_flash']['tmp_name']) && $_FILES['upfile_flash']['tmp_name'] === 'none')) && empty($_POST['flash_url'])) {
                    $link[] = ['text' => lang('go_back'), 'href' => 'javascript:history.back(-1)'];

                    return $this->sys_msg(lang('js_languages.ad_flash_empty'), 0, $link);
                }
            } // 如果广告类型为代码广告
            elseif ($_POST['media_type'] === '2') {
                if (! empty($_POST['ad_code'])) {
                    $ad_code = $_POST['ad_code'];
                } else {
                    $link[] = ['text' => lang('go_back'), 'href' => 'javascript:history.back(-1)'];

                    return $this->sys_msg(lang('js_languages.ad_code_empty'), 0, $link);
                }
            } // 广告类型为文本广告
            elseif ($_POST['media_type'] === '3') {
                if (! empty($_POST['ad_text'])) {
                    $ad_code = $_POST['ad_text'];
                } else {
                    $link[] = ['text' => lang('go_back'), 'href' => 'javascript:history.back(-1)'];

                    return $this->sys_msg(lang('js_languages.ad_text_empty'), 0, $link);
                }
            }

            // 插入数据
            DB::table('ad')->insert([
                'position_id' => $_POST['position_id'],
                'media_type' => $_POST['media_type'],
                'ad_name' => $ad_name,
                'ad_link' => $ad_link,
                'ad_code' => $ad_code,
                'start_time' => $start_time,
                'end_time' => $end_time,
                'link_man' => $_POST['link_man'],
                'link_email' => $_POST['link_email'],
                'link_phone' => $_POST['link_phone'],
                'click_count' => 0,
                'enabled' => 1,
            ]);

            // 记录管理员操作
            $this->admin_log($_POST['ad_name'], 'add', 'ads');

            $this->clear_cache_files(); // 清除缓存文件

            // 提示信息

            $link[0]['text'] = lang('show_ads_template');
            $link[0]['href'] = 'template.php?act=setup';

            $link[1]['text'] = lang('back_ads_list');
            $link[1]['href'] = 'ads.php?act=list';

            $link[2]['text'] = lang('continue_add_ad');
            $link[2]['href'] = 'ads.php?act=add';

            return $this->sys_msg(lang('add').'&nbsp;'.$_POST['ad_name'].'&nbsp;'.lang('attradd_succed'), 0, $link);
        }

        /**
         * 广告编辑页面
         */
        if ($action === 'edit') {
            $this->admin_priv('ad_manage');

            // 获取广告数据
            $ads_arr = DB::table('ad')->where('ad_id', intval($_REQUEST['id']))->first();
            $ads_arr = $ads_arr ? (array) $ads_arr : [];

            $ads_arr['ad_name'] = htmlspecialchars($ads_arr['ad_name']);
            // 格式化广告的有效日期
            $ads_arr['start_time'] = TimeHelper::local_date('Y-m-d', $ads_arr['start_time']);
            $ads_arr['end_time'] = TimeHelper::local_date('Y-m-d', $ads_arr['end_time']);

            if ($ads_arr['media_type'] === '0') {
                if (strpos($ads_arr['ad_code'], 'http://') === false && strpos($ads_arr['ad_code'], 'https://') === false) {
                    $src = '../'.DATA_DIR.'/afficheimg/'.$ads_arr['ad_code'];
                    $this->assign('img_src', $src);
                } else {
                    $src = $ads_arr['ad_code'];
                    $this->assign('url_src', $src);
                }
            }
            if ($ads_arr['media_type'] === '1') {
                if (strpos($ads_arr['ad_code'], 'http://') === false && strpos($ads_arr['ad_code'], 'https://') === false) {
                    $src = '../'.DATA_DIR.'/afficheimg/'.$ads_arr['ad_code'];
                    $this->assign('flash_url', $src);
                } else {
                    $src = $ads_arr['ad_code'];
                    $this->assign('flash_url', $src);
                }
                $this->assign('src', $src);
            }
            if ($ads_arr['media_type'] === 0) {
                $this->assign('media_type', lang('ad_img'));
            } elseif ($ads_arr['media_type'] === 1) {
                $this->assign('media_type', lang('ad_flash'));
            } elseif ($ads_arr['media_type'] === 2) {
                $this->assign('media_type', lang('ad_html'));
            } elseif ($ads_arr['media_type'] === 3) {
                $this->assign('media_type', lang('ad_text'));
            }

            $this->assign('ur_here', lang('ads_edit'));
            $this->assign('action_link', ['href' => 'ads.php?act=list', 'text' => lang('ad_list')]);
            $this->assign('form_act', 'update');
            $this->assign('action', 'edit');
            $this->assign('position_list', MainHelper::get_position_list());
            $this->assign('ads', $ads_arr);

            return $this->display('ads_info');
        }

        /**
         * 广告编辑的处理
         */
        if ($action === 'update') {
            $this->admin_priv('ad_manage');

            // 初始化变量
            $id = ! empty($_POST['id']) ? intval($_POST['id']) : 0;
            $type = ! empty($_POST['media_type']) ? intval($_POST['media_type']) : 0;

            if ($_POST['media_type'] === '0') {
                $ad_link = ! empty($_POST['ad_link']) ? trim($_POST['ad_link']) : '';
            } else {
                $ad_link = ! empty($_POST['ad_link2']) ? trim($_POST['ad_link2']) : '';
            }

            // 获得广告的开始时期与结束日期
            $start_time = TimeHelper::local_strtotime($_POST['start_time']);
            $end_time = TimeHelper::local_strtotime($_POST['end_time']);

            // 编辑图片类型的广告
            $ad_code_val = '';
            if ($type === 0) {
                if ((isset($_FILES['ad_img']['error']) && $_FILES['ad_img']['error'] === 0) || (! isset($_FILES['ad_img']['error']) && isset($_FILES['ad_img']['tmp_name']) && $_FILES['ad_img']['tmp_name'] != 'none')) {
                    $img_up_info = basename($image->upload_image($_FILES['ad_img'], 'afficheimg'));
                    $ad_code_val = $img_up_info;
                }
                if (! empty($_POST['img_url'])) {
                    $ad_code_val = $_POST['img_url'];
                }
            } // 如果是编辑Flash广告
            elseif ($type === 1) {
                if ((isset($_FILES['upfile_flash']['error']) && $_FILES['upfile_flash']['error'] === 0) || (! isset($_FILES['upfile_flash']['error']) && isset($_FILES['upfile_flash']['tmp_name']) && $_FILES['upfile_flash']['tmp_name'] != 'none')) {
                    // 检查文件类型
                    if ($_FILES['upfile_flash']['type'] != 'application/x-shockwave-flash') {
                        $link[] = ['text' => lang('go_back'), 'href' => 'javascript:history.back(-1)'];

                        return $this->sys_msg(lang('upfile_flash_type'), 0, $link);
                    }
                    // 生成文件名
                    $urlstr = date('Ymd');
                    for ($i = 0; $i < 6; $i++) {
                        $urlstr .= chr(mt_rand(97, 122));
                    }

                    $source_file = $_FILES['upfile_flash']['tmp_name'];
                    $target = ROOT_PATH.DATA_DIR.'/afficheimg/';
                    $file_name = $urlstr.'.swf';

                    if (! BaseHelper::move_upload_file($source_file, $target.$file_name)) {
                        $link[] = ['text' => lang('go_back'), 'href' => 'javascript:history.back(-1)'];

                        return $this->sys_msg(lang('upfile_error'), 0, $link);
                    } else {
                        $ad_code_val = $file_name;
                    }
                } elseif (! empty($_POST['flash_url'])) {
                    if (substr(strtolower($_POST['flash_url']), strlen($_POST['flash_url']) - 4) != '.swf') {
                        $link[] = ['text' => lang('go_back'), 'href' => 'javascript:history.back(-1)'];

                        return $this->sys_msg(lang('upfile_flash_type'), 0, $link);
                    }
                    $ad_code_val = $_POST['flash_url'];
                }
            } // 编辑代码类型的广告
            elseif ($type === 2) {
                $ad_code_val = $_POST['ad_code'];
            } // 编辑文本类型的广告
            elseif ($type === 3) {
                $ad_code_val = $_POST['ad_text'];
            }

            $ad_code_val = str_replace('../'.DATA_DIR.'/afficheimg/', '', $ad_code_val);

            // 更新信息
            $update_data = [
                'position_id' => $_POST['position_id'],
                'ad_name' => $_POST['ad_name'],
                'ad_link' => $ad_link,
                'start_time' => $start_time,
                'end_time' => $end_time,
                'link_man' => $_POST['link_man'],
                'link_email' => $_POST['link_email'],
                'link_phone' => $_POST['link_phone'],
                'enabled' => $_POST['enabled'],
            ];

            if ($ad_code_val !== '') {
                $update_data['ad_code'] = $ad_code_val;
            }

            DB::table('ad')->where('ad_id', $id)->update($update_data);

            // 记录管理员操作
            $this->admin_log($_POST['ad_name'], 'edit', 'ads');

            $this->clear_cache_files(); // 清除模版缓存

            // 提示信息
            $href[] = ['text' => lang('back_ads_list'), 'href' => 'ads.php?act=list'];

            return $this->sys_msg(lang('edit').' '.$_POST['ad_name'].' '.lang('attradd_succed'), 0, $href);
        }

        /**
         *生成广告的JS代码
         */
        if ($action === 'add_js') {
            $this->admin_priv('ad_manage');

            // 编码
            $lang_list = [
                'UTF8' => lang('charset.utf8'),
                'GB2312' => lang('charset.zh_cn'),
                'BIG5' => lang('charset.zh_tw'),
            ];

            $js_code = '<script type='.'"'.'text/javascript'.'"';
            $js_code .= ' src='.'"'.ecs()->url().'affiche.php?act=js&type='.$_REQUEST['type'].'&ad_id='.intval($_REQUEST['id']).'"'.'></script>';

            $site_url = ecs()->url().'affiche.php?act=js&type='.$_REQUEST['type'].'&ad_id='.intval($_REQUEST['id']);

            $this->assign('ur_here', lang('add_js_code'));
            $this->assign('action_link', ['href' => 'ads.php?act=list', 'text' => lang('ad_list')]);
            $this->assign('url', $site_url);
            $this->assign('js_code', $js_code);
            $this->assign('lang_list', $lang_list);

            return $this->display('ads_js');
        }

        /**
         * 编辑广告名称
         */
        if ($action === 'edit_ad_name') {
            $this->check_authz_json('ad_manage');

            $id = intval($_POST['id']);
            $ad_name = BaseHelper::json_str_iconv(trim($_POST['val']));

            // 检查广告名称是否重复
            if (DB::table('ad')->where('ad_name', $ad_name)->where('ad_id', '<>', $id)->exists()) {
                return $this->make_json_error(sprintf(lang('ad_name_exist'), $ad_name));
            } else {
                if (DB::table('ad')->where('ad_id', $id)->update(['ad_name' => $ad_name])) {
                    $this->admin_log($ad_name, 'edit', 'ads');

                    return $this->make_json_result(stripslashes($ad_name));
                } else {
                    return $this->make_json_error('DB error');
                }
            }
        }

        /**
         * 删除广告位置
         */
        if ($action === 'remove') {
            $this->check_authz_json('ad_manage');

            $id = intval($_GET['id']);
            $img = DB::table('ad')->where('ad_id', $id)->value('ad_code');

            if (DB::table('ad')->where('ad_id', $id)->delete()) {
                if ((strpos((string) $img, 'http://') === false) && (strpos((string) $img, 'https://') === false) && BaseHelper::get_file_suffix((string) $img, $allow_suffix)) {
                    $img_name = basename((string) $img);
                    @unlink(ROOT_PATH.DATA_DIR.'/afficheimg/'.$img_name);
                }
            }
            $this->admin_log('', 'remove', 'ads');

            $url = 'ads.php?act=query&'.str_replace('act=remove', '', $_SERVER['QUERY_STRING']);

            return response()->redirectTo($url);
        }
    }

    // 获取广告数据列表
    private function get_adslist()
    {
        // 过滤查询
        $pid = ! empty($_REQUEST['pid']) ? intval($_REQUEST['pid']) : 0;

        $filter = [];
        $filter['sort_by'] = empty($_REQUEST['sort_by']) ? 'ad.ad_name' : trim($_REQUEST['sort_by']);
        $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);

        $query = DB::table('ad as ad');
        if ($pid > 0) {
            $query->where('ad.position_id', $pid);
        }

        // 获得总记录数据
        $filter['record_count'] = $query->count();

        $filter = MainHelper::page_and_size($filter);

        // 获得广告数据
        $res = $query->leftJoin('ad_position as p', 'p.position_id', '=', 'ad.position_id')
            ->leftJoin('order_info as o', 'o.from_ad', '=', 'ad.ad_id')
            ->select('ad.*', 'p.position_name', DB::raw('COUNT(o.order_id) AS ad_stats'))
            ->groupBy('ad.ad_id')
            ->orderBy($filter['sort_by'], $filter['sort_order'])
            ->offset($filter['start'])
            ->limit($filter['page_size'])
            ->get();

        $arr = [];
        foreach ($res as $rows) {
            $rows = (array) $rows;
            // 广告类型的名称
            $rows['type'] = ($rows['media_type'] === 0) ? lang('ad_img') : '';
            $rows['type'] .= ($rows['media_type'] === 1) ? lang('ad_flash') : '';
            $rows['type'] .= ($rows['media_type'] === 2) ? lang('ad_html') : '';
            $rows['type'] .= ($rows['media_type'] === 3) ? lang('ad_text') : '';

            // 格式化日期
            $rows['start_date'] = TimeHelper::local_date(cfg('date_format'), $rows['start_time']);
            $rows['end_date'] = TimeHelper::local_date(cfg('date_format'), $rows['end_time']);

            $arr[] = $rows;
        }

        return ['ads' => $arr, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']];
    }
}
