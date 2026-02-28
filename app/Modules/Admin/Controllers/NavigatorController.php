<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Helpers\BaseHelper;
use App\Helpers\CommonHelper;
use App\Modules\Admin\Helpers\MainHelper;
use App\Modules\Admin\Libraries\Exchange;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NavigatorController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        $this->admin_priv('navigator');

        $exc = new Exchange(ecs()->table('shop_nav'), db(), 'id', 'name');

        /**
         * 自定义导航栏列表
         */
        if ($action === 'list') {
            $this->assign('ur_here', lang('navigator'));
            $this->assign('action_link', ['text' => lang('add_new'), 'href' => 'navigator.php?act=add']);
            $this->assign('full_page', 1);

            $navdb = $this->get_nav($request);

            $this->assign('navdb', $navdb['navdb']);
            $this->assign('filter', $navdb['filter']);
            $this->assign('record_count', $navdb['record_count']);
            $this->assign('page_count', $navdb['page_count']);

            return $this->display('navigator');
        }
        /**
         * 自定义导航栏列表Ajax
         */
        if ($action === 'query') {
            $navdb = $this->get_nav($request);
            $this->assign('navdb', $navdb['navdb']);
            $this->assign('filter', $navdb['filter']);
            $this->assign('record_count', $navdb['record_count']);
            $this->assign('page_count', $navdb['page_count']);

            $sort_flag = MainHelper::sort_flag($navdb['filter']);
            $this->assign($sort_flag['tag'], $sort_flag['img']);

            return $this->make_json_result($this->fetch('navigator'), '', ['filter' => $navdb['filter'], 'page_count' => $navdb['page_count']]);
        }
        /**
         * 自定义导航栏增加
         */
        if ($action === 'add') {
            if (! $request->has('step')) {
                $rt = ['act' => 'add'];

                $sysmain = $this->get_sysnav();

                $this->assign('action_link', ['text' => lang('go_list'), 'href' => 'navigator.php?act=list']);
                $this->assign('ur_here', lang('navigator'));

                $this->assign('sysmain', $sysmain);
                $this->assign('rt', $rt);

                return $this->display('navigator_add');
            } elseif ($request->input('step') == 2) {
                $item_name = $request->input('item_name');
                $item_url = $request->input('item_url');
                $item_ifshow = $request->input('item_ifshow');
                $item_opennew = $request->input('item_opennew');
                $item_type = $request->input('item_type');

                $vieworder = DB::table('shop_nav')->where('type', $item_type)->max('vieworder');

                $item_vieworder = $request->input('item_vieworder') ?: $vieworder + 1;

                if ($item_ifshow == 1 && $item_type === 'middle') {
                    // 如果设置为在中部显示

                    $arr = $this->analyse_uri($item_url);  // 分析URI
                    if ($arr) {
                        // 如果为分类
                        $this->set_show_in_nav($arr['type'], $arr['id'], 1);   // 设置显示
                        $insertData = ['name' => $item_name, 'ctype' => $arr['type'], 'cid' => $arr['id'], 'ifshow' => $item_ifshow, 'vieworder' => $item_vieworder, 'opennew' => $item_opennew, 'url' => $item_url, 'type' => $item_type];
                    }
                }

                if (empty($insertData)) {
                    $insertData = ['name' => $item_name, 'ifshow' => $item_ifshow, 'vieworder' => $item_vieworder, 'opennew' => $item_opennew, 'url' => $item_url, 'type' => $item_type];
                }
                DB::table('shop_nav')->insert($insertData);
                $this->clear_cache_files();
                $links[] = ['text' => lang('navigator'), 'href' => 'navigator.php?act=list'];
                $links[] = ['text' => lang('add_new'), 'href' => 'navigator.php?act=add'];

                return $this->sys_msg(lang('edit_ok'), 0, $links);
            }
        }
        /**
         * 自定义导航栏编辑
         */
        if ($action === 'edit') {
            $id = $request->input('id');
            if (! $request->has('step')) {
                $rt = ['act' => 'edit', 'id' => $id];
                $row = (array) DB::table('shop_nav')->where('id', $id)->first();
                $rt['item_name'] = $row['name'];
                $rt['item_url'] = $row['url'];
                $rt['item_vieworder'] = $row['vieworder'];
                $rt['item_ifshow_'.$row['ifshow']] = 'selected';
                $rt['item_opennew_'.$row['opennew']] = 'selected';
                $rt['item_type_'.$row['type']] = 'selected';

                $sysmain = $this->get_sysnav();

                $this->assign('action_link', ['text' => lang('go_list'), 'href' => 'navigator.php?act=list']);
                $this->assign('ur_here', lang('navigator'));

                $this->assign('sysmain', $sysmain);
                $this->assign('rt', $rt);

                return $this->display('navigator_add');
            } elseif ($request->input('step') == 2) {
                $item_name = $request->input('item_name');
                $item_url = $request->input('item_url');
                $item_ifshow = $request->input('item_ifshow');
                $item_opennew = $request->input('item_opennew');
                $item_type = $request->input('item_type');
                $item_vieworder = (int) $request->input('item_vieworder');

                $row = (array) DB::table('shop_nav')->where('id', $id)->select('ctype', 'cid', 'ifshow', 'type')->first();
                $arr = $this->analyse_uri($item_url);

                if ($arr) {
                    // 目标为分类
                    if ($row['ctype'] === $arr['type'] && $row['cid'] === $arr['id']) {
                        // 没有修改分类
                        if ($item_type != 'middle') {
                            // 位置不在中部
                            $this->set_show_in_nav($arr['type'], $arr['id'], 0);
                        }
                    } else {
                        // 修改了分类
                        if ($row['ifshow'] === 1 && $row['type'] === 'middle') {
                            // 原来在中部显示
                            $this->set_show_in_nav($row['ctype'], $row['cid'], 0); // 设置成不显示
                        } elseif ($row['ifshow'] === 0 && $row['type'] === 'middle') {
                            // 原来不显示
                        }
                    }

                    // 分类判断
                    if ($item_ifshow != $this->is_show_in_nav($arr['type'], $arr['id']) && $item_type === 'middle') {
                        $this->set_show_in_nav($arr['type'], $arr['id'], $item_ifshow);
                    }
                    DB::table('shop_nav')->where('id', $id)->update(['name' => $item_name, 'ctype' => $arr['type'], 'cid' => $arr['id'], 'ifshow' => $item_ifshow, 'vieworder' => $item_vieworder, 'opennew' => $item_opennew, 'url' => $item_url, 'type' => $item_type]);
                } else {
                    // 目标不是分类
                    if ($row['ctype'] && $row['cid']) {
                        // 原来是分类
                        $this->set_show_in_nav($row['ctype'], $row['cid'], 0);
                    }

                    DB::table('shop_nav')->where('id', $id)->update(['name' => $item_name, 'ctype' => '', 'cid' => '', 'ifshow' => $item_ifshow, 'vieworder' => $item_vieworder, 'opennew' => $item_opennew, 'url' => $item_url, 'type' => $item_type]);
                }
                $this->clear_cache_files();
                $links[] = ['text' => lang('navigator'), 'href' => 'navigator.php?act=list'];

                return $this->sys_msg(lang('edit_ok'), 0, $links);
            }
        }
        /**
         * 自定义导航栏删除
         */
        if ($action === 'del') {
            $id = (int) $request->input('id');
            $row = (array) DB::table('shop_nav')->where('id', $id)->select('ctype', 'cid', 'type')->limit(1)->first();

            if ($row['type'] === 'middle' && $row['ctype'] && $row['cid']) {
                $this->set_show_in_nav($row['ctype'], $row['cid'], 0);
            }

            DB::table('shop_nav')->where('id', $id)->delete();
            $this->clear_cache_files();

            return response()->redirectTo('navigator.php?act=list');
        }

        /**
         * 编辑排序
         */
        if ($action === 'edit_sort_order') {
            $this->check_authz_json('nav');

            $id = intval($request->input('id'));
            $order = BaseHelper::json_str_iconv(trim($request->input('val')));

            // 检查输入的值是否合法
            if (! preg_match('/^[0-9]+$/', $order)) {
                return $this->make_json_error(sprintf(lang('enter_int'), $order));
            } else {
                if ($exc->edit("vieworder = '$order'", $id)) {
                    $this->clear_cache_files();

                    return $this->make_json_result(stripslashes($order));
                } else {
                    return $this->make_json_error('DB error');
                }
            }
        }

        /**
         * 切换是否显示
         */
        if ($action === 'toggle_ifshow') {
            $id = intval($request->input('id'));
            $val = intval($request->input('val'));

            $row = (array) DB::table('shop_nav')->where('id', $id)->select('type', 'ctype', 'cid')->limit(1)->first();

            if ($row['type'] === 'middle' && $row['ctype'] && $row['cid']) {
                $this->set_show_in_nav($row['ctype'], $row['cid'], $val);
            }

            if ($this->nav_update($id, ['ifshow' => $val]) != false) {
                $this->clear_cache_files();

                return $this->make_json_result($val);
            } else {
                return $this->make_json_error('DB error');
            }
        }

        /**
         * 切换是否新窗口
         */
        if ($action === 'toggle_opennew') {
            $id = intval($request->input('id'));
            $val = intval($request->input('val'));

            if ($this->nav_update($id, ['opennew' => $val]) != false) {
                $this->clear_cache_files();

                return $this->make_json_result($val);
            } else {
                return $this->make_json_error('DB error');
            }
        }
    }

    private function get_nav(Request $request)
    {
        $result = MainHelper::get_filter();
        if ($result === false) {
            $filter['sort_by'] = $request->has('sort_by') ? trim($request->input('sort_by')) : 'type DESC, vieworder';
            $filter['sort_order'] = $request->has('sort_order') ? trim($request->input('sort_order')) : 'ASC';

            $filter['record_count'] = DB::table('shop_nav')->count();

            // 分页大小
            $filter = MainHelper::page_and_size($filter);

            // 查询
            MainHelper::set_filter($filter, '');
        } else {
            $sql = $result['sql'];
            $filter = $result['filter'];
        }

        $navdb = DB::table('shop_nav')
            ->select('id', 'name', 'ifshow', 'vieworder', 'opennew', 'url', 'type')
            ->orderBy($filter['sort_by'], $filter['sort_order'])
            ->offset($filter['start'])
            ->limit($filter['page_size'])
            ->get();

        $type = '';
        $navdb2 = [];
        foreach ($navdb as $k => $v) {
            $v = (array) $v;
            if (! empty($type) && $type != $v['type']) {
                $navdb2[] = [];
            }
            $navdb2[] = $v;
            $type = $v['type'];
        }

        $arr = ['navdb' => $navdb2, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']];

        return $arr;
    }

    // ------------------------------------------------------
    // -- 排序相关
    // ------------------------------------------------------
    private function sort_nav($a, $b)
    {
        return $a['vieworder'] > $b['vieworder'] ? 1 : -1;
    }

    // ------------------------------------------------------
    // -- 获得系统列表
    // ------------------------------------------------------
    private function get_sysnav()
    {
        $sysmain = [
            [lang('view_cart'), 'flow.php'],
            [lang('pick_out'), 'pick_out.php'],
            [lang('group_buy_goods'), 'group_buy.php'],
            [lang('snatch'), 'snatch.php'],
            [lang('tag_cloud'), 'tag_cloud.php'],
            [lang('user_center'), 'user.php'],
            [lang('wholesale'), 'wholesale.php'],
            [lang('activity'), 'activity.php'],
            [lang('myship'), 'myship.php'],
            [lang('message_board'), 'message.php'],
            [lang('quotation'), 'quotation.php'],
        ];

        $sysmain[] = ['-', '-'];

        $catlist = array_merge(CommonHelper::cat_list(0, 0, false), ['-'], article_cat_list(0, 0, false));
        foreach ($catlist as $key => $val) {
            $val['view_name'] = $val['cat_name'];
            for ($i = 0; $i < $val['level']; $i++) {
                $val['view_name'] = '&nbsp;&nbsp;&nbsp;&nbsp;'.$val['view_name'];
            }
            $val['url'] = str_replace('&amp;', '&', $val['url']);
            $val['url'] = str_replace('&', '&amp;', $val['url']);
            $sysmain[] = [$val['cat_name'], $val['url'], $val['view_name']];
        }

        return $sysmain;
    }

    // ------------------------------------------------------
    // -- 列表项修改
    // ------------------------------------------------------
    private function nav_update($id, $args)
    {
        if (empty($args) || empty($id)) {
            return false;
        }

        return DB::table('shop_nav')->where('id', $id)->update($args) !== false;
    }

    // ------------------------------------------------------
    // -- 根据URI对导航栏项目进行分析，确定其为商品分类还是文章分类
    // ------------------------------------------------------
    private function analyse_uri($uri)
    {
        $uri = strtolower(str_replace('&amp;', '&', $uri));
        $arr = explode('-', $uri);
        switch ($arr[0]) {
            case 'category':
                return ['type' => 'c', 'id' => $arr[1]];
                break;
            case 'article_cat':
                return ['type' => 'a', 'id' => $arr[1]];
                break;
            default:

                break;
        }

        [$fn, $pm] = explode('?', $uri);

        if (strpos($uri, '&') === false) {
            $arr = [$pm];
        } else {
            $arr = explode('&', $pm);
        }
        switch ($fn) {
            case 'category.php':
                // 商品分类
                foreach ($arr as $k => $v) {
                    [$key, $val] = explode('=', $v);
                    if ($key === 'id') {
                        return ['type' => 'c', 'id' => $val];
                    }
                }
                break;
            case 'article_cat.php':
                // 文章分类
                foreach ($arr as $k => $v) {
                    [$key, $val] = explode('=', $v);
                    if ($key === 'id') {
                        return ['type' => 'a', 'id' => $val];
                    }
                }
                break;
            default:
                // 未知
                return false;
                break;
        }
    }

    // ------------------------------------------------------
    // -- 是否显示
    // ------------------------------------------------------
    private function is_show_in_nav($type, $id)
    {
        return DB::table($type === 'c' ? 'goods_category' : 'article_cat')->where('cat_id', $id)->value('show_in_nav');
    }

    // ------------------------------------------------------
    // -- 设置是否显示
    // ------------------------------------------------------
    private function set_show_in_nav($type, $id, $val)
    {
        DB::table($type === 'c' ? 'goods_category' : 'article_cat')->where('cat_id', $id)->update(['show_in_nav' => $val]);
        $this->clear_cache_files();
    }
}
