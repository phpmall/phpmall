<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Helpers\BaseHelper;
use App\Modules\Admin\Helpers\MainHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdPositionController extends BaseController
{
    public function index(Request $request)
    {
        lang([dirname(__DIR__).'/Languages/zh-CN/ads.php']);

        $action = $request->get('act');

        /**
         * 广告位置列表
         */
        if ($action === 'list') {
            $this->assign('ur_here', lang('ad_position'));
            $this->assign('action_link', ['text' => lang('position_add'), 'href' => 'ad_position.php?act=add']);
            $this->assign('full_page', 1);

            $position_list = $this->ad_position_list();

            $this->assign('position_list', $position_list['position']);
            $this->assign('filter', $position_list['filter']);
            $this->assign('record_count', $position_list['record_count']);
            $this->assign('page_count', $position_list['page_count']);

            return $this->display('ad_position_list');
        }

        /**
         * 添加广告位页面
         */
        if ($action === 'add') {
            $this->admin_priv('ad_manage');

            $this->assign('ur_here', lang('position_add'));
            $this->assign('form_act', 'insert');

            $this->assign('action_link', ['href' => 'ad_position.php?act=list', 'text' => lang('ad_position')]);
            $this->assign('posit_arr', ['position_style' => '<table cellpadding="0" cellspacing="0">'."\n".'{foreach from=$ads item=ad}'."\n".'<tr><td>{$ad}</td></tr>'."\n".'{/foreach}'."\n".'</table>']);

            return $this->display('ad_position_info');
        }

        if ($action === 'insert') {
            $this->admin_priv('ad_manage');

            // 对POST上来的值进行处理并去除空格
            $position_name = ! empty($_POST['position_name']) ? trim($_POST['position_name']) : '';
            $position_desc = ! empty($_POST['position_desc']) ? nl2br(htmlspecialchars($_POST['position_desc'])) : '';
            $ad_width = ! empty($_POST['ad_width']) ? intval($_POST['ad_width']) : 0;
            $ad_height = ! empty($_POST['ad_height']) ? intval($_POST['ad_height']) : 0;

            // 查看广告位是否有重复
            if (DB::table('ad_position')->where('position_name', $position_name)->count() === 0) {
                // 将广告位置的信息插入数据表
                DB::table('ad_position')->insert([
                    'position_name' => $position_name,
                    'ad_width' => $ad_width,
                    'ad_height' => $ad_height,
                    'position_desc' => $position_desc,
                    'position_style' => $_POST['position_style'],
                ]);

                // 记录管理员操作
                $this->admin_log($position_name, 'add', 'ads_position');

                // 提示信息
                $link[0]['text'] = lang('ads_add');
                $link[0]['href'] = 'ads.php?act=add';

                $link[1]['text'] = lang('continue_add_position');
                $link[1]['href'] = 'ad_position.php?act=add';

                $link[2]['text'] = lang('back_position_list');
                $link[2]['href'] = 'ad_position.php?act=list';

                return $this->sys_msg(lang('add').'&nbsp;'.stripslashes($position_name).'&nbsp;'.lang('attradd_succed'), 0, $link);
            } else {
                $link[] = ['text' => lang('go_back'), 'href' => 'javascript:history.back(-1)'];

                return $this->sys_msg(lang('posit_name_exist'), 0, $link);
            }
        }

        /**
         * 广告位编辑页面
         */
        if ($action === 'edit') {
            $this->admin_priv('ad_manage');

            $id = ! empty($_GET['id']) ? intval($_GET['id']) : 0;

            // 获取广告位数据
            $posit_arr = DB::table('ad_position')->where('position_id', $id)->first();
            $posit_arr = $posit_arr ? (array) $posit_arr : [];

            $this->assign('ur_here', lang('position_edit'));
            $this->assign('action_link', ['href' => 'ad_position.php?act=list', 'text' => lang('ad_position')]);
            $this->assign('posit_arr', $posit_arr);
            $this->assign('form_act', 'update');

            return $this->display('ad_position_info');
        }

        if ($action === 'update') {
            $this->admin_priv('ad_manage');

            // 对POST上来的值进行处理并去除空格
            $position_name = ! empty($_POST['position_name']) ? trim($_POST['position_name']) : '';
            $position_desc = ! empty($_POST['position_desc']) ? nl2br(htmlspecialchars($_POST['position_desc'])) : '';
            $ad_width = ! empty($_POST['ad_width']) ? intval($_POST['ad_width']) : 0;
            $ad_height = ! empty($_POST['ad_height']) ? intval($_POST['ad_height']) : 0;
            $position_id = ! empty($_POST['id']) ? intval($_POST['id']) : 0;
            // 查看广告位是否与其它有重复
            $exists = DB::table('ad_position')
                ->where('position_name', $position_name)
                ->where('position_id', '<>', $position_id)
                ->exists();

            if (! $exists) {
                $updated = DB::table('ad_position')
                    ->where('position_id', $position_id)
                    ->update([
                        'position_name' => $position_name,
                        'ad_width' => $ad_width,
                        'ad_height' => $ad_height,
                        'position_desc' => $position_desc,
                        'position_style' => $_POST['position_style'],
                    ]);

                if ($updated !== false) {
                    // 记录管理员操作
                    $this->admin_log($position_name, 'edit', 'ads_position');

                    // 清除缓存
                    $this->clear_cache_files();

                    // 提示信息
                    $link[] = ['text' => lang('back_position_list'), 'href' => 'ad_position.php?act=list'];

                    return $this->sys_msg(lang('edit').' '.stripslashes($position_name).' '.lang('attradd_succed'), 0, $link);
                }
            } else {
                $link[] = ['text' => lang('go_back'), 'href' => 'javascript:history.back(-1)'];

                return $this->sys_msg(lang('posit_name_exist'), 0, $link);
            }
        }

        /**
         * 排序、分页、查询
         */
        if ($action === 'query') {
            $position_list = $this->ad_position_list();

            $this->assign('position_list', $position_list['position']);
            $this->assign('filter', $position_list['filter']);
            $this->assign('record_count', $position_list['record_count']);
            $this->assign('page_count', $position_list['page_count']);

            return $this->make_json_result(
                $this->fetch('ad_position_list'),
                '',
                ['filter' => $position_list['filter'], 'page_count' => $position_list['page_count']]
            );
        }

        /**
         * 编辑广告位置名称
         */
        if ($action === 'edit_position_name') {
            $this->check_authz_json('ad_manage');

            $id = intval($_POST['id']);
            $position_name = BaseHelper::json_str_iconv(trim($_POST['val']));

            // 检查名称是否重复
            if (DB::table('ad_position')->where('position_name', $position_name)->where('position_id', '<>', $id)->exists()) {
                return $this->make_json_error(sprintf(lang('posit_name_exist'), $position_name));
            } else {
                if (DB::table('ad_position')->where('position_id', $id)->update(['position_name' => $position_name])) {
                    $this->admin_log($position_name, 'edit', 'ads_position');

                    return $this->make_json_result(stripslashes($position_name));
                } else {
                    return $this->make_json_result(sprintf(lang('brandedit_fail'), $position_name));
                }
            }
        }

        /**
         * 编辑广告位宽高
         */
        if ($action === 'edit_ad_width') {
            $this->check_authz_json('ad_manage');

            $id = intval($_POST['id']);
            $ad_width = BaseHelper::json_str_iconv(trim($_POST['val']));

            // 宽度值必须是数字
            if (! preg_match("/^[\.0-9]+$/", $ad_width)) {
                return $this->make_json_error(lang('width_number'));
            }

            // 广告位宽度应在1-1024之间
            if ($ad_width > 1024 || $ad_width < 1) {
                return $this->make_json_error(lang('width_value'));
            }

            if (DB::table('ad_position')->where('position_id', $id)->update(['ad_width' => $ad_width])) {
                $this->clear_cache_files(); // 清除模版缓存
                $this->admin_log($ad_width, 'edit', 'ads_position');

                return $this->make_json_result(stripslashes($ad_width));
            } else {
                return $this->make_json_error('DB error');
            }
        }

        /**
         * 编辑广告位宽高
         */
        if ($action === 'edit_ad_height') {
            $this->check_authz_json('ad_manage');

            $id = intval($_POST['id']);
            $ad_height = BaseHelper::json_str_iconv(trim($_POST['val']));

            // 高度值必须是数字
            if (! preg_match("/^[\.0-9]+$/", $ad_height)) {
                return $this->make_json_error(lang('height_number'));
            }

            // 广告位宽度应在1-1024之间
            if ($ad_height > 1024 || $ad_height < 1) {
                return $this->make_json_error(lang('height_value'));
            }

            if (DB::table('ad_position')->where('position_id', $id)->update(['ad_height' => $ad_height])) {
                $this->clear_cache_files(); // 清除模版缓存
                $this->admin_log($ad_height, 'edit', 'ads_position');

                return $this->make_json_result(stripslashes($ad_height));
            } else {
                return $this->make_json_error('DB error');
            }
        }

        /**
         * 删除广告位置
         */
        if ($action === 'remove') {
            $this->check_authz_json('ad_manage');

            $id = intval($_GET['id']);

            // 查询广告位下是否有广告存在
            $count = DB::table('ad')->where('position_id', $id)->count();

            if ($count > 0) {
                return $this->make_json_error(lang('not_del_adposit'));
            } else {
                DB::table('ad_position')->where('position_id', $id)->delete();
                $this->admin_log('', 'remove', 'ads_position');
            }

            $url = 'ad_position.php?act=query&'.str_replace('act=remove', '', $_SERVER['QUERY_STRING']);

            return response()->redirectTo($url);
        }
    }

    // 获取广告位置列表
    private function ad_position_list()
    {
        $filter = [];

        // 记录总数以及页数
        $filter['record_count'] = DB::table('ad_position')->count();

        $filter = MainHelper::page_and_size($filter);

        // 查询数据
        $res = DB::table('ad_position')
            ->orderBy('position_id', 'DESC')
            ->offset($filter['start'])
            ->limit($filter['page_size'])
            ->get();

        $arr = [];
        foreach ($res as $rows) {
            $rows = (array) $rows;
            $position_desc = ! empty($rows['position_desc']) ? Str::limit($rows['position_desc'], 50) : '';
            $rows['position_desc'] = nl2br(htmlspecialchars($position_desc));

            $arr[] = $rows;
        }

        return ['position' => $arr, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']];
    }
}
