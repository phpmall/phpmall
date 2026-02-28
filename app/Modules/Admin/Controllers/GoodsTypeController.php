<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Helpers\BaseHelper;
use App\Modules\Admin\Helpers\MainHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GoodsTypeController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');

        /**
         * 管理界面
         */
        if ($action === 'manage') {
            $this->assign('ur_here', lang('08_goods_type'));
            $this->assign('full_page', 1);

            $good_type_list = $this->get_goodstype();
            $this->assign('goods_type_arr', $good_type_list['type']);
            $this->assign('filter', $good_type_list['filter']);
            $this->assign('record_count', $good_type_list['record_count']);
            $this->assign('page_count', $good_type_list['page_count']);

            $good_in_type = [];
            $res = DB::table('goods_type_attribute as a')
                ->rightJoin('goods_attr as g', 'g.attr_id', '=', 'a.attr_id')
                ->groupBy('a.cat_id')
                ->select('a.cat_id')
                ->get();
            foreach ($res as $row) {
                $row = (array) $row;
                if ($row['cat_id']) {
                    $good_in_type[$row['cat_id']] = 1;
                }
            }
            $this->assign('good_in_type', $good_in_type);

            $this->assign('action_link', ['text' => lang('new_goods_type'), 'href' => 'goods_type.php?act=add']);

            return $this->display('goods_type');
        }

        /**
         * 获得列表
         */
        if ($action === 'query') {
            $good_type_list = $this->get_goodstype();

            $this->assign('goods_type_arr', $good_type_list['type']);
            $this->assign('filter', $good_type_list['filter']);
            $this->assign('record_count', $good_type_list['record_count']);
            $this->assign('page_count', $good_type_list['page_count']);

            return $this->make_json_result(
                $this->fetch('goods_type'),
                '',
                ['filter' => $good_type_list['filter'], 'page_count' => $good_type_list['page_count']]
            );
        }

        /**
         * 修改商品类型名称
         */
        if ($action === 'edit_type_name') {
            $this->check_authz_json('goods_type');

            $type_id = ! empty($_POST['id']) ? intval($_POST['id']) : 0;
            $type_name = ! empty($_POST['val']) ? BaseHelper::json_str_iconv(trim($_POST['val'])) : '';

            // 检查名称是否重复
            if (DB::table('goods_type')->where('cat_name', $type_name)->where('cat_id', '<>', $type_id)->exists()) {
                return $this->make_json_error(lang('repeat_type_name'));
            } else {
                DB::table('goods_type')->where('cat_id', $type_id)->update(['cat_name' => $type_name]);

                $this->admin_log($type_name, 'edit', 'goods_type');

                return $this->make_json_result(stripslashes($type_name));
            }
        }

        /**
         * 切换启用状态
         */
        if ($action === 'toggle_enabled') {
            $this->check_authz_json('goods_type');

            $id = intval($_POST['id']);
            $val = intval($_POST['val']);

            DB::table('goods_type')->where('cat_id', $id)->update(['enabled' => $val]);

            return $this->make_json_result((string) $val);
        }

        /**
         * 添加商品类型
         */
        if ($action === 'add') {
            $this->admin_priv('goods_type');

            $this->assign('ur_here', lang('new_goods_type'));
            $this->assign('action_link', ['href' => 'goods_type.php?act=manage', 'text' => lang('goods_type_list')]);
            $this->assign('action', 'add');
            $this->assign('form_act', 'insert');
            $this->assign('goods_type', ['enabled' => 1]);

            return $this->display('goods_type_info');
        }

        if ($action === 'insert') {
            $goods_type['cat_name'] = Str::limit($_POST['cat_name'], 60, '');
            $goods_type['attr_group'] = Str::limit($_POST['attr_group'], 255, '');
            $goods_type['enabled'] = intval($_POST['enabled']);

            if (DB::table('goods_type')->insert($goods_type)) {
                $links = [['href' => 'goods_type.php?act=manage', 'text' => lang('back_list')]];

                return $this->sys_msg(lang('add_goodstype_success'), 0, $links);
            } else {
                return $this->sys_msg(lang('add_goodstype_failed'), 1);
            }
        }

        /**
         * 编辑商品类型
         */
        if ($action === 'edit') {
            $goods_type = $this->get_goodstype_info(intval($_GET['cat_id']));

            if (empty($goods_type)) {
                return $this->sys_msg(lang('cannot_found_goodstype'), 1);
            }

            $this->admin_priv('goods_type');

            $this->assign('ur_here', lang('edit_goods_type'));
            $this->assign('action_link', ['href' => 'goods_type.php?act=manage', 'text' => lang('goods_type_list')]);
            $this->assign('action', 'add');
            $this->assign('form_act', 'update');
            $this->assign('goods_type', $goods_type);

            return $this->display('goods_type_info');
        }

        if ($action === 'update') {
            $goods_type['cat_name'] = Str::limit($_POST['cat_name'], 60, '');
            $goods_type['attr_group'] = Str::limit($_POST['attr_group'], 255, '');
            $goods_type['enabled'] = intval($_POST['enabled']);
            $cat_id = intval($_POST['cat_id']);
            $old_groups = MainHelper::get_attr_groups($cat_id);

            if (DB::table('goods_type')->where('cat_id', $cat_id)->update($goods_type) !== false) {
                // 对比原来的分组
                $new_groups = explode("\n", str_replace("\r", '', $goods_type['attr_group']));  // 新的分组

                foreach ($old_groups as $key => $val) {
                    $found = array_search($val, $new_groups);

                    if ($found === null || $found === false) {
                        // 老的分组没有在新的分组中找到
                        $this->update_attribute_group($cat_id, $key, 0);
                    } else {
                        // 老的分组出现在新的分组中了
                        if ($key != $found) {
                            $this->update_attribute_group($cat_id, $key, $found); // 但是分组的key变了,需要更新属性的分组
                        }
                    }
                }

                $links = [['href' => 'goods_type.php?act=manage', 'text' => lang('back_list')]];

                return $this->sys_msg(lang('edit_goodstype_success'), 0, $links);
            } else {
                return $this->sys_msg(lang('edit_goodstype_failed'), 1);
            }
        }

        /**
         * 删除商品类型
         */
        if ($action === 'remove') {
            $this->check_authz_json('goods_type');

            $id = intval($_GET['id']);

            $name = DB::table('goods_type')->where('cat_id', $id)->value('cat_name');

            if (DB::table('goods_type')->where('cat_id', $id)->delete()) {
                $this->admin_log(addslashes($name), 'remove', 'goods_type');

                // 清除该类型下的所有属性
                $attr_ids = DB::table('goods_type_attribute')->where('cat_id', $id)->pluck('attr_id')->toArray();

                DB::table('goods_type_attribute')->whereIn('attr_id', $attr_ids)->delete();
                DB::table('goods_attr')->whereIn('attr_id', $attr_ids)->delete();

                $url = 'goods_type.php?act=query&'.str_replace('act=remove', '', $_SERVER['QUERY_STRING']);

                return response()->redirectTo($url);
            } else {
                return $this->make_json_error(lang('remove_failed'));
            }
        }
    }

    /**
     * 获得所有商品类型
     */
    private function get_goodstype(): array
    {
        $result = MainHelper::get_filter();
        if ($result === false) {
            // 分页大小
            $filter = [];

            // 记录总数以及页数
            $filter['record_count'] = DB::table('goods_type')->count();

            $filter = MainHelper::page_and_size($filter);

            // 查询记录
            $res = DB::table('goods_type as t')
                ->leftJoin('goods_type_attribute as a', 'a.cat_id', '=', 't.cat_id')
                ->select('t.*', DB::raw('COUNT(a.cat_id) AS attr_count'))
                ->groupBy('t.cat_id')
                ->offset($filter['start'])
                ->limit($filter['page_size'])
                ->get();

            MainHelper::set_filter($filter, '');
        } else {
            $filter = $result['filter'];
            $res = DB::table('goods_type as t')
                ->leftJoin('goods_type_attribute as a', 'a.cat_id', '=', 't.cat_id')
                ->select('t.*', DB::raw('COUNT(a.cat_id) AS attr_count'))
                ->groupBy('t.cat_id')
                ->offset($filter['start'])
                ->limit($filter['page_size'])
                ->get();
        }

        $all = [];
        foreach ($res as $val) {
            $val = (array) $val;
            $val['attr_group'] = strtr($val['attr_group'], ["\r" => '', "\n" => ', ']);
            $all[] = $val;
        }

        return ['type' => $all, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']];
    }

    /**
     * 获得指定的商品类型的详情
     *
     * @param  int  $cat_id  分类ID
     * @return array
     */
    private function get_goodstype_info($cat_id)
    {
        $row = DB::table('goods_type')->where('cat_id', $cat_id)->first();

        return $row ? (array) $row : [];
    }

    /**
     * 更新属性的分组
     *
     * @param  int  $cat_id  商品类型ID
     * @param  int  $old_group
     * @param  int  $new_group
     * @return void
     */
    private function update_attribute_group($cat_id, $old_group, $new_group)
    {
        DB::table('goods_type_attribute')
            ->where('cat_id', $cat_id)
            ->where('attr_group', $old_group)
            ->update(['attr_group' => $new_group]);
    }
}
