<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Helpers\BaseHelper;
use App\Modules\Admin\Helpers\MainHelper;
use App\Modules\Admin\Libraries\Exchange;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AreaManageController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        $exc = new Exchange('shop_region', db(), 'region_id', 'region_name');

        /**
         * 列出某地区下的所有地区列表
         */
        if ($action === 'list') {
            $this->admin_priv('area_manage');

            // 取得参数：上级地区id
            $region_id = $request->input('pid', 0);
            $this->assign('parent_id', $region_id);

            // 取得列表显示的地区的类型
            if ($region_id == 0) {
                $region_type = 0;
            } else {
                $region_type = $exc->get_name($region_id, 'region_type') + 1;
            }
            $this->assign('region_type', $region_type);

            // 获取地区列表
            $region_arr = MainHelper::area_list($region_id);
            $this->assign('region_arr', $region_arr);

            // 当前的地区名称
            if ($region_id > 0) {
                $area_name = $exc->get_name($region_id);
                $area = '[ '.$area_name.' ] ';
                if ($region_arr) {
                    $area .= $region_arr[0]['type'];
                }
            } else {
                $area = lang('country');
            }
            $this->assign('area_here', $area);

            // 返回上一级的链接
            if ($region_id > 0) {
                $parent_id = $exc->get_name($region_id, 'parent_id');
                $action_link = ['text' => lang('back_page'), 'href' => 'area_manage.php?act=list&&pid='.$parent_id];
            } else {
                $action_link = '';
            }
            $this->assign('action_link', $action_link);

            // 赋值模板显示
            $this->assign('ur_here', lang('05_area_list'));
            $this->assign('full_page', 1);

            return $this->display('area_list');
        }

        /**
         * 添加新的地区
         */
        if ($action === 'add_area') {
            $this->check_authz_json('area_manage');

            $parent_id = intval($request->input('parent_id'));
            $region_name = BaseHelper::json_str_iconv(trim($request->input('region_name')));
            $region_type = intval($request->input('region_type'));

            if (empty($region_name)) {
                return $this->make_json_error(lang('region_name_empty'));
            }

            // 查看区域是否重复
            if (! $exc->is_only('region_name', $region_name, 0, "parent_id = '$parent_id'")) {
                return $this->make_json_error(lang('region_name_exist'));
            }

            $inserted = DB::table('shop_region')->insert([
                'parent_id' => $parent_id,
                'region_name' => $region_name,
                'region_type' => $region_type,
            ]);

            if ($inserted) {
                $this->admin_log($region_name, 'add', 'area');

                // 获取地区列表
                $region_arr = MainHelper::area_list($parent_id);
                $this->assign('region_arr', $region_arr);

                $this->assign('region_type', $region_type);

                return $this->make_json_result($this->fetch('area_list'));
            } else {
                return $this->make_json_error(lang('add_area_error'));
            }
        }

        /**
         * 编辑区域名称
         */
        if ($action === 'edit_area_name') {
            $this->check_authz_json('area_manage');

            $id = intval($request->input('id'));
            $region_name = BaseHelper::json_str_iconv(trim($request->input('val')));

            if (empty($region_name)) {
                return $this->make_json_error(lang('region_name_empty'));
            }

            $msg = '';

            // 查看区域是否重复
            $parent_id = $exc->get_name($id, 'parent_id');
            if (! $exc->is_only('region_name', $region_name, $id, "parent_id = '$parent_id'")) {
                return $this->make_json_error(lang('region_name_exist'));
            }

            if ($exc->edit("region_name = '$region_name'", $id)) {
                $this->admin_log($region_name, 'edit', 'area');

                return $this->make_json_result(stripslashes($region_name));
            } else {
                return $this->make_json_error(lang('edit_area_error'));
            }
        }

        /**
         * 删除区域
         */
        if ($action === 'drop_area') {
            $this->check_authz_json('area_manage');

            $id = intval($request->input('id'));

            $region = DB::table('shop_region')->where('region_id', $id)->first();
            $region = $region ? (array) $region : [];

            //    // 如果底下有下级区域,不能删除
            //    $sql = "SELECT COUNT(*) FROM " . ecs()->table('shop_region') . " WHERE parent_id = '$id'";
            //    if (db()->getOne($sql) > 0)
            //    {
            //        return $this->make_json_error(lang('parent_id_exist'));
            //    }
            $region_type = $region['region_type'];
            $delete_region[] = $id;
            $new_region_id = $id;
            if ($region_type < 6) {
                for ($i = 1; $i < 6 - $region_type; $i++) {
                    $new_region_id = $this->new_region_id($new_region_id);
                    if (count($new_region_id)) {
                        $delete_region = array_merge($delete_region, $new_region_id);
                    } else {
                        continue;
                    }
                }
            }
            DB::table('shop_region')->whereIn('region_id', $delete_region)->delete();
            if ($exc->drop($id)) {
                $this->admin_log(addslashes($region['region_name']), 'remove', 'area');

                // 获取地区列表
                $region_arr = MainHelper::area_list($region['parent_id']);
                $this->assign('region_arr', $region_arr);
                $this->assign('region_type', $region['region_type']);

                return $this->make_json_result($this->fetch('area_list'));
            } else {
                return $this->make_json_error(lang('drop_area_error'));
            }
        }
    }

    private function new_region_id($region_id)
    {
        if (empty($region_id)) {
            return [];
        }

        return DB::table('shop_region')
            ->whereIn('parent_id', (array) $region_id)
            ->pluck('region_id')
            ->all();
    }
}
