<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Helpers\BaseHelper;
use App\Helpers\CommonHelper;
use App\Modules\Admin\Helpers\MainHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        /**
         * 商品分类列表
         */
        if ($action === 'list') {
            // 获取分类列表
            $cat_list = CommonHelper::cat_list(0, 0, false);

            $this->assign('ur_here', lang('03_category_list'));
            $this->assign('action_link', ['href' => 'category.php?act=add', 'text' => lang('04_category_add')]);
            $this->assign('full_page', 1);

            $this->assign('cat_info', $cat_list);

            // 列表页面

            return $this->display('category_list');
        }

        /**
         * 排序、分页、查询
         */
        if ($action === 'query') {
            $cat_list = CommonHelper::cat_list(0, 0, false);
            $this->assign('cat_info', $cat_list);

            return $this->make_json_result($this->fetch('category_list'));
        }
        /**
         * 添加商品分类
         */
        if ($action === 'add') {
            // 权限检查
            $this->admin_priv('cat_manage');

            $this->assign('ur_here', lang('04_category_add'));
            $this->assign('action_link', ['href' => 'category.php?act=list', 'text' => lang('03_category_list')]);

            $this->assign('goods_type_list', MainHelper::goods_type_list(0)); // 取得商品类型
            $this->assign('attr_list', $this->get_attr_list()); // 取得商品属性

            $this->assign('cat_select', CommonHelper::cat_list(0, 0, true));
            $this->assign('form_act', 'insert');
            $this->assign('cat_info', ['is_show' => 1]);

            return $this->display('category_info');
        }

        /**
         * 商品分类添加时的处理
         */
        if ($action === 'insert') {
            // 权限检查
            $this->admin_priv('cat_manage');

            // 初始化变量
            $cat['cat_id'] = ! empty($_POST['cat_id']) ? intval($_POST['cat_id']) : 0;
            $cat['parent_id'] = ! empty($_POST['parent_id']) ? intval($_POST['parent_id']) : 0;
            $cat['sort_order'] = ! empty($_POST['sort_order']) ? intval($_POST['sort_order']) : 0;
            $cat['keywords'] = ! empty($_POST['keywords']) ? trim($_POST['keywords']) : '';
            $cat['cat_desc'] = ! empty($_POST['cat_desc']) ? $_POST['cat_desc'] : '';
            $cat['measure_unit'] = ! empty($_POST['measure_unit']) ? trim($_POST['measure_unit']) : '';
            $cat['cat_name'] = ! empty($_POST['cat_name']) ? trim($_POST['cat_name']) : '';
            $cat['show_in_nav'] = ! empty($_POST['show_in_nav']) ? intval($_POST['show_in_nav']) : 0;
            $cat['style'] = ! empty($_POST['style']) ? trim($_POST['style']) : '';
            $cat['is_show'] = ! empty($_POST['is_show']) ? intval($_POST['is_show']) : 0;
            $cat['grade'] = ! empty($_POST['grade']) ? intval($_POST['grade']) : 0;
            $cat['filter_attr'] = ! empty($_POST['filter_attr']) ? implode(',', array_unique(array_diff($_POST['filter_attr'], [0]))) : 0;

            $cat['cat_recommend'] = ! empty($_POST['cat_recommend']) ? $_POST['cat_recommend'] : [];

            if (MainHelper::cat_exists($cat['cat_name'], $cat['parent_id'])) {
                // 同级别下不能有重复的分类名称
                $link[] = ['text' => lang('go_back'), 'href' => 'javascript:history.back(-1)'];

                return $this->sys_msg(lang('catname_exist'), 0, $link);
            }

            if ($cat['grade'] > 10 || $cat['grade'] < 0) {
                // 价格区间数超过范围
                $link[] = ['text' => lang('go_back'), 'href' => 'javascript:history.back(-1)'];

                return $this->sys_msg(lang('grade_error'), 0, $link);
            }

            // 入库的操作
            $cat_id = DB::table('goods_category')->insertGetId($cat);
            if ($cat_id) {
                if ($cat['show_in_nav'] === 1) {
                    $vieworder = DB::table('shop_nav')->where('type', 'middle')->max('vieworder');
                    $vieworder += 2;
                    // 显示在自定义导航栏中
                    DB::table('shop_nav')->insert([
                        'name' => $cat['cat_name'],
                        'ctype' => 'c',
                        'cid' => $cat_id,
                        'ifshow' => '1',
                        'vieworder' => $vieworder,
                        'opennew' => '0',
                        'url' => build_uri('category', ['cid' => $cat_id], $cat['cat_name']),
                        'type' => 'middle',
                    ]);
                }
                $this->insert_cat_recommend($cat['cat_recommend'], $cat_id);

                $this->admin_log($_POST['cat_name'], 'add', 'category');   // 记录管理员操作
                $this->clear_cache_files();    // 清除缓存

                // 添加链接
                $link[0]['text'] = lang('continue_add');
                $link[0]['href'] = 'category.php?act=add';

                $link[1]['text'] = lang('back_list');
                $link[1]['href'] = 'category.php?act=list';

                return $this->sys_msg(lang('catadd_succed'), 0, $link);
            }
        }

        /**
         * 编辑商品分类信息
         */
        if ($action === 'edit') {
            $this->admin_priv('cat_manage');   // 权限检查
            $cat_id = intval($_REQUEST['cat_id']);
            $cat_info = $this->get_cat_info($cat_id);  // 查询分类信息数据
            $attr_list = $this->get_attr_list();
            $filter_attr_list = [];

            if ($cat_info['filter_attr']) {
                $filter_attr = explode(',', $cat_info['filter_attr']);  // 把多个筛选属性放到数组中

                foreach ($filter_attr as $k => $v) {
                    $attr_cat_id = DB::table('goods_type_attribute')->where('attr_id', intval($v))->value('cat_id');
                    $filter_attr_list[$k]['goods_type_list'] = MainHelper::goods_type_list($attr_cat_id);  // 取得每个属性的商品类型
                    $filter_attr_list[$k]['filter_attr'] = $v;
                    $attr_option = [];

                    foreach ($attr_list[$attr_cat_id] as $val) {
                        $attr_option[key($val)] = current($val);
                    }

                    $filter_attr_list[$k]['option'] = $attr_option;
                }

                $this->assign('filter_attr_list', $filter_attr_list);
            } else {
                $attr_cat_id = 0;
            }

            $this->assign('attr_list', $attr_list); // 取得商品属性
            $this->assign('attr_cat_id', $attr_cat_id);
            $this->assign('ur_here', lang('category_edit'));
            $this->assign('action_link', ['text' => lang('03_category_list'), 'href' => 'category.php?act=list']);

            // 分类是否存在首页推荐
            $res = DB::table('goods_cat_recommend')->where('cat_id', $cat_id)->select('recommend_type')->get();
            if ($res->isNotEmpty()) {
                $cat_recommend = [];
                foreach ($res as $data) {
                    $cat_recommend[$data->recommend_type] = 1;
                }
                $this->assign('cat_recommend', $cat_recommend);
            }

            $this->assign('cat_info', $cat_info);
            $this->assign('form_act', 'update');
            $this->assign('cat_select', CommonHelper::cat_list(0, $cat_info['parent_id'], true));
            $this->assign('goods_type_list', MainHelper::goods_type_list(0)); // 取得商品类型

            return $this->display('category_info');
        }

        if ($action === 'add_category') {
            $parent_id = empty($_REQUEST['parent_id']) ? 0 : intval($_REQUEST['parent_id']);
            $category = empty($_REQUEST['cat']) ? '' : BaseHelper::json_str_iconv(trim($_REQUEST['cat']));

            if (MainHelper::cat_exists($category, $parent_id)) {
                return $this->make_json_error(lang('catname_exist'));
            } else {
                $category_id = DB::table('goods_category')->insertGetId([
                    'cat_name' => $category,
                    'parent_id' => $parent_id,
                    'is_show' => 1,
                ]);

                $arr = ['parent_id' => $parent_id, 'id' => $category_id, 'cat' => $category];

                $this->clear_cache_files();    // 清除缓存

                return $this->make_json_result($arr);
            }
        }

        /**
         * 编辑商品分类信息
         */
        if ($action === 'update') {
            // 权限检查
            $this->admin_priv('cat_manage');

            // 初始化变量
            $cat_id = ! empty($_POST['cat_id']) ? intval($_POST['cat_id']) : 0;
            $old_cat_name = $_POST['old_cat_name'];
            $cat['parent_id'] = ! empty($_POST['parent_id']) ? intval($_POST['parent_id']) : 0;
            $cat['sort_order'] = ! empty($_POST['sort_order']) ? intval($_POST['sort_order']) : 0;
            $cat['keywords'] = ! empty($_POST['keywords']) ? trim($_POST['keywords']) : '';
            $cat['cat_desc'] = ! empty($_POST['cat_desc']) ? $_POST['cat_desc'] : '';
            $cat['measure_unit'] = ! empty($_POST['measure_unit']) ? trim($_POST['measure_unit']) : '';
            $cat['cat_name'] = ! empty($_POST['cat_name']) ? trim($_POST['cat_name']) : '';
            $cat['is_show'] = ! empty($_POST['is_show']) ? intval($_POST['is_show']) : 0;
            $cat['show_in_nav'] = ! empty($_POST['show_in_nav']) ? intval($_POST['show_in_nav']) : 0;
            $cat['style'] = ! empty($_POST['style']) ? trim($_POST['style']) : '';
            $cat['grade'] = ! empty($_POST['grade']) ? intval($_POST['grade']) : 0;
            $cat['filter_attr'] = ! empty($_POST['filter_attr']) ? implode(',', array_unique(array_diff($_POST['filter_attr'], [0]))) : 0;
            $cat['cat_recommend'] = ! empty($_POST['cat_recommend']) ? $_POST['cat_recommend'] : [];

            // 判断分类名是否重复

            if ($cat['cat_name'] != $old_cat_name) {
                if (MainHelper::cat_exists($cat['cat_name'], $cat['parent_id'], $cat_id)) {
                    $link[] = ['text' => lang('go_back'), 'href' => 'javascript:history.back(-1)'];

                    return $this->sys_msg(lang('catname_exist'), 0, $link);
                }
            }

            // 判断上级目录是否合法
            $children = array_keys(CommonHelper::cat_list($cat_id, 0, false));     // 获得当前分类的所有下级分类
            if (in_array($cat['parent_id'], $children)) {
                // 选定的父类是当前分类或当前分类的下级分类
                $link[] = ['text' => lang('go_back'), 'href' => 'javascript:history.back(-1)'];

                return $this->sys_msg(lang('is_leaf_error'), 0, $link);
            }

            if ($cat['grade'] > 10 || $cat['grade'] < 0) {
                // 价格区间数超过范围
                $link[] = ['text' => lang('go_back'), 'href' => 'javascript:history.back(-1)'];

                return $this->sys_msg(lang('grade_error'), 0, $link);
            }

            $dat = DB::table('goods_category')->where('cat_id', $cat_id)->select('cat_name', 'show_in_nav')->first();
            $dat = $dat ? (array) $dat : [];

            if (DB::table('goods_category')->where('cat_id', $cat_id)->update($cat)) {
                if ($cat['cat_name'] != $dat['cat_name']) {
                    // 如果分类名称发生了改变
                    DB::table('shop_nav')
                        ->where('ctype', 'c')
                        ->where('cid', $cat_id)
                        ->where('type', 'middle')
                        ->update(['name' => $cat['cat_name']]);
                }
                if ($cat['show_in_nav'] != $dat['show_in_nav']) {
                    // 是否显示于导航栏发生了变化
                    if ($cat['show_in_nav'] === 1) {
                        // 显示
                        $nid = DB::table('shop_nav')->where('ctype', 'c')->where('cid', $cat_id)->where('type', 'middle')->value('id');
                        if (empty($nid)) {
                            // 不存在
                            $vieworder = DB::table('shop_nav')->where('type', 'middle')->max('vieworder');
                            $vieworder += 2;
                            $uri = build_uri('category', ['cid' => $cat_id], $cat['cat_name']);

                            DB::table('shop_nav')->insert([
                                'name' => $cat['cat_name'],
                                'ctype' => 'c',
                                'cid' => $cat_id,
                                'ifshow' => '1',
                                'vieworder' => $vieworder,
                                'opennew' => '0',
                                'url' => $uri,
                                'type' => 'middle',
                            ]);
                        } else {
                            DB::table('shop_nav')
                                ->where('ctype', 'c')
                                ->where('cid', $cat_id)
                                ->where('type', 'middle')
                                ->update(['ifshow' => 1]);
                        }
                    } else {
                        // 去除
                        DB::table('shop_nav')
                            ->where('ctype', 'c')
                            ->where('cid', $cat_id)
                            ->where('type', 'middle')
                            ->update(['ifshow' => 0]);
                    }
                }

                // 更新首页推荐
                $this->insert_cat_recommend($cat['cat_recommend'], $cat_id);
                // 更新分类信息成功
                $this->clear_cache_files(); // 清除缓存
                $this->admin_log($_POST['cat_name'], 'edit', 'category'); // 记录管理员操作

                // 提示信息
                $link[] = ['text' => lang('back_list'), 'href' => 'category.php?act=list'];

                return $this->sys_msg(lang('catedit_succed'), 0, $link);
            }
        }

        /**
         * 批量转移商品分类页面
         */
        if ($action === 'move') {
            // 权限检查
            $this->admin_priv('cat_drop');

            $cat_id = ! empty($_REQUEST['cat_id']) ? intval($_REQUEST['cat_id']) : 0;

            $this->assign('ur_here', lang('move_goods'));
            $this->assign('action_link', ['href' => 'category.php?act=list', 'text' => lang('03_category_list')]);

            $this->assign('cat_select', CommonHelper::cat_list(0, $cat_id, true));
            $this->assign('form_act', 'move_cat');

            return $this->display('category_move');
        }

        /**
         * 处理批量转移商品分类的处理程序
         */
        if ($action === 'move_cat') {
            // 权限检查
            $this->admin_priv('cat_drop');

            $cat_id = ! empty($_POST['cat_id']) ? intval($_POST['cat_id']) : 0;
            $target_cat_id = ! empty($_POST['target_cat_id']) ? intval($_POST['target_cat_id']) : 0;

            // 商品分类不允许为空
            if ($cat_id === 0 || $target_cat_id === 0) {
                $link[] = ['text' => lang('go_back'), 'href' => 'category.php?act=move'];

                return $this->sys_msg(lang('cat_move_empty'), 0, $link);
            }

            // 更新商品分类
            if (DB::table('goods')->where('cat_id', $cat_id)->update(['cat_id' => $target_cat_id])) {
                // 清除缓存
                $this->clear_cache_files();

                // 提示信息
                $link[] = ['text' => lang('go_back'), 'href' => 'category.php?act=list'];

                return $this->sys_msg(lang('move_cat_success'), 0, $link);
            }
        }

        /**
         * 编辑排序序号
         */
        if ($action === 'edit_sort_order') {
            $this->check_authz_json('cat_manage');

            $id = intval($_POST['id']);
            $val = intval($_POST['val']);

            if ($this->cat_update($id, ['sort_order' => $val])) {
                $this->clear_cache_files(); // 清除缓存

                return $this->make_json_result($val);
            } else {
                return $this->make_json_error('DB error');
            }
        }

        /**
         * 编辑数量单位
         */
        if ($action === 'edit_measure_unit') {
            $this->check_authz_json('cat_manage');

            $id = intval($_POST['id']);
            $val = BaseHelper::json_str_iconv($_POST['val']);

            if ($this->cat_update($id, ['measure_unit' => $val])) {
                $this->clear_cache_files(); // 清除缓存

                return $this->make_json_result($val);
            } else {
                return $this->make_json_error('DB error');
            }
        }

        /**
         * 编辑排序序号
         */
        if ($action === 'edit_grade') {
            $this->check_authz_json('cat_manage');

            $id = intval($_POST['id']);
            $val = intval($_POST['val']);

            if ($val > 10 || $val < 0) {
                // 价格区间数超过范围
                return $this->make_json_error(lang('grade_error'));
            }

            if ($this->cat_update($id, ['grade' => $val])) {
                $this->clear_cache_files(); // 清除缓存

                return $this->make_json_result($val);
            } else {
                return $this->make_json_error('DB error');
            }
        }

        /**
         * 切换是否显示在导航栏
         */
        if ($action === 'toggle_show_in_nav') {
            $this->check_authz_json('cat_manage');

            $id = intval($_POST['id']);
            $val = intval($_POST['val']);

            if ($this->cat_update($id, ['show_in_nav' => $val]) != false) {
                if ($val === 1) {
                    // 显示
                    $vieworder = DB::table('shop_nav')->where('type', 'middle')->max('vieworder');
                    $vieworder += 2;
                    $catname = DB::table('goods_category')->where('cat_id', $id)->value('cat_name');
                    // 显示在自定义导航栏中
                    $uri = build_uri('category', ['cid' => $id], $catname);

                    $nid = DB::table('shop_nav')->where('ctype', 'c')->where('cid', $id)->where('type', 'middle')->value('id');
                    if (empty($nid)) {
                        // 不存在
                        DB::table('shop_nav')->insert([
                            'name' => $catname,
                            'ctype' => 'c',
                            'cid' => $id,
                            'ifshow' => '1',
                            'vieworder' => $vieworder,
                            'opennew' => '0',
                            'url' => $uri,
                            'type' => 'middle',
                        ]);
                    } else {
                        DB::table('shop_nav')
                            ->where('ctype', 'c')
                            ->where('cid', $id)
                            ->where('type', 'middle')
                            ->update(['ifshow' => 1]);
                    }
                } else {
                    // 去除
                    DB::table('shop_nav')
                        ->where('ctype', 'c')
                        ->where('cid', $id)
                        ->where('type', 'middle')
                        ->update(['ifshow' => 0]);
                }
                $this->clear_cache_files();

                return $this->make_json_result($val);
            } else {
                return $this->make_json_error('DB error');
            }
        }

        /**
         * 切换是否显示
         */
        if ($action === 'toggle_is_show') {
            $this->check_authz_json('cat_manage');

            $id = intval($_POST['id']);
            $val = intval($_POST['val']);

            if ($this->cat_update($id, ['is_show' => $val]) != false) {
                $this->clear_cache_files();

                return $this->make_json_result($val);
            } else {
                return $this->make_json_error('DB error');
            }
        }

        /**
         * 删除商品分类
         */
        if ($action === 'remove') {
            $this->check_authz_json('cat_manage');

            // 初始化分类ID并取得分类名称
            $cat_id = intval($_GET['id']);
            $cat_name = DB::table('goods_category')->where('cat_id', $cat_id)->value('cat_name');

            // 当前分类下是否有子分类
            $cat_count = DB::table('goods_category')->where('parent_id', $cat_id)->count();

            // 当前分类下是否存在商品
            $goods_count = DB::table('goods')->where('cat_id', $cat_id)->count();

            // 如果不存在下级子分类和商品，则删除之
            if ($cat_count === 0 && $goods_count === 0) {
                // 删除分类
                if (DB::table('goods_category')->where('cat_id', $cat_id)->delete()) {
                    DB::table('shop_nav')
                        ->where('ctype', 'c')
                        ->where('cid', $cat_id)
                        ->where('type', 'middle')
                        ->delete();
                    $this->clear_cache_files();
                    $this->admin_log($cat_name, 'remove', 'category');
                }
            } else {
                return $this->make_json_error($cat_name.' '.lang('cat_isleaf'));
            }

            $url = 'category.php?act=query&'.str_replace('act=remove', '', $_SERVER['QUERY_STRING']);

            return response()->redirectTo($url);
        }
    }

    /**
     * 获得商品分类的所有信息
     *
     * @param  int  $cat_id  指定的分类ID
     * @return mix
     */
    private function get_cat_info($cat_id)
    {
        $res = DB::table('goods_category')->where('cat_id', $cat_id)->first();

        return $res ? (array) $res : [];
    }

    /**
     * 添加商品分类
     *
     * @param  int  $cat_id
     * @param  array  $args
     * @return mix
     */
    private function cat_update($cat_id, $args)
    {
        if (empty($args) || empty($cat_id)) {
            return false;
        }

        return DB::table('goods_category')->where('cat_id', $cat_id)->update($args);
    }

    /**
     * 获取属性列表
     *
     *
     * @return void
     */
    private function get_attr_list()
    {
        $arr = DB::table('goods_type_attribute as a')
            ->join('goods_type as c', 'a.cat_id', '=', 'c.cat_id')
            ->where('c.enabled', 1)
            ->select('a.attr_id', 'a.cat_id', 'a.attr_name')
            ->orderBy('a.cat_id')
            ->orderBy('a.sort_order')
            ->get();

        $list = [];

        foreach ($arr as $val) {
            $val = (array) $val;
            $list[$val['cat_id']][] = [$val['attr_id'] => $val['attr_name']];
        }

        return $list;
    }

    /**
     * 插入首页推荐扩展分类
     *
     * @param  array  $recommend_type  推荐类型
     * @param  int  $cat_id  分类ID
     * @return void
     */
    private function insert_cat_recommend($recommend_type, $cat_id)
    {
        // 检查分类是否为首页推荐
        if (! empty($recommend_type)) {
            // 取得之前的分类
            $recommend_res = DB::table('goods_cat_recommend')->where('cat_id', $cat_id)->select('recommend_type')->get();
            if ($recommend_res->isEmpty()) {
                foreach ($recommend_type as $data) {
                    DB::table('goods_cat_recommend')->insert([
                        'cat_id' => $cat_id,
                        'recommend_type' => intval($data),
                    ]);
                }
            } else {
                $old_data = [];
                foreach ($recommend_res as $data) {
                    $old_data[] = $data->recommend_type;
                }
                $delete_array = array_diff($old_data, $recommend_type);
                if (! empty($delete_array)) {
                    DB::table('goods_cat_recommend')
                        ->where('cat_id', $cat_id)
                        ->whereIn('recommend_type', $delete_array)
                        ->delete();
                }
                $insert_array = array_diff($recommend_type, $old_data);
                if (! empty($insert_array)) {
                    foreach ($insert_array as $data) {
                        DB::table('goods_cat_recommend')->insert([
                            'cat_id' => $cat_id,
                            'recommend_type' => intval($data),
                        ]);
                    }
                }
            }
        } else {
            DB::table('goods_cat_recommend')->where('cat_id', $cat_id)->delete();
        }
    }
}
