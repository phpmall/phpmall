<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Helpers\BaseHelper;
use App\Modules\Admin\Helpers\MainHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TagManageController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        /**
         * 获取标签数据列表
         */
        if ($action === 'list') {
            // 权限判断
            $this->admin_priv('tag_manage');

            $this->assign('ur_here', lang('tag_list'));
            $this->assign('action_link', ['href' => 'tag_manage.php?act=add', 'text' => lang('add_tag')]);
            $this->assign('full_page', 1);

            $tag_list = $this->get_tag_list();
            $this->assign('tag_list', $tag_list['tags']);
            $this->assign('filter', $tag_list['filter']);
            $this->assign('record_count', $tag_list['record_count']);
            $this->assign('page_count', $tag_list['page_count']);

            $sort_flag = MainHelper::sort_flag($tag_list['filter']);
            $this->assign($sort_flag['tag'], $sort_flag['img']);

            // 页面显示

            return $this->display('tag_manage');
        }

        /**
         * 添加 ,编辑
         */
        if ($action === 'add' || $action === 'edit') {
            $this->admin_priv('tag_manage');

            $is_add = $action === 'add';
            $this->assign('insert_or_update', $is_add ? 'insert' : 'update');

            if ($is_add) {
                $tag = [
                    'tag_id' => 0,
                    'tag_words' => '',
                    'goods_id' => 0,
                    'goods_name' => lang('pls_select_goods'),
                ];
                $this->assign('ur_here', lang('add_tag'));
            } else {
                $tag_id = $_GET['id'];
                $tag = $this->get_tag_info($tag_id);
                $tag['tag_words'] = htmlspecialchars($tag['tag_words']);
                $this->assign('ur_here', lang('tag_edit'));
            }
            $this->assign('tag', $tag);
            $this->assign('action_link', ['href' => 'tag_manage.php?act=list', 'text' => lang('tag_list')]);

            return $this->display('tag_edit');
        }

        /**
         * 更新
         */
        if ($action === 'insert' || $action === 'update') {
            $this->admin_priv('tag_manage');

            $is_insert = $action === 'insert';

            $tag_words = empty($_POST['tag_name']) ? '' : trim($_POST['tag_name']);
            $id = intval($_POST['id']);
            $goods_id = intval($_POST['goods_id']);
            if ($goods_id <= 0) {
                return $this->sys_msg(lang('pls_select_goods'));
            }

            if (! $this->tag_is_only($tag_words, $id, $goods_id)) {
                return $this->sys_msg(sprintf(lang('tagword_exist'), $tag_words));
            }

            if ($is_insert) {
                DB::table('user_tag')->insert([
                    'tag_id' => $id,
                    'goods_id' => $goods_id,
                    'tag_words' => $tag_words,
                ]);

                $this->admin_log($tag_words, 'add', 'tag');

                // 清除缓存
                $this->clear_cache_files();

                $link[0]['text'] = lang('back_list');
                $link[0]['href'] = 'tag_manage.php?act=list';

                return $this->sys_msg(lang('tag_add_success'), 0, $link);
            } else {
                $this->edit_tag($tag_words, $id, $goods_id);

                // 清除缓存
                $this->clear_cache_files();

                $link[0]['text'] = lang('back_list');
                $link[0]['href'] = 'tag_manage.php?act=list';

                return $this->sys_msg(lang('tag_edit_success'), 0, $link);
            }
        }

        /**
         * 翻页，排序
         */
        if ($action === 'query') {
            $this->check_authz_json('tag_manage');

            $tag_list = $this->get_tag_list();
            $this->assign('tag_list', $tag_list['tags']);
            $this->assign('filter', $tag_list['filter']);
            $this->assign('record_count', $tag_list['record_count']);
            $this->assign('page_count', $tag_list['page_count']);

            $sort_flag = MainHelper::sort_flag($tag_list['filter']);
            $this->assign($sort_flag['tag'], $sort_flag['img']);

            return $this->make_json_result(
                $this->fetch('tag_manage'),
                '',
                ['filter' => $tag_list['filter'], 'page_count' => $tag_list['page_count']]
            );
        }

        /**
         * 搜索
         */
        if ($action === 'search_goods') {
            $this->check_authz_json('tag_manage');

            $filter = json_decode($_GET['JSON']);
            $arr = MainHelper::get_goods_list($filter);
            if (empty($arr)) {
                $arr[0] = [
                    'goods_id' => 0,
                    'goods_name' => '',
                ];
            }

            return $this->make_json_result($arr);
        }

        /**
         * 批量删除标签
         */
        if ($action === 'batch_drop') {
            $this->admin_priv('tag_manage');

            if (isset($_POST['checkboxes'])) {
                $count = 0;
                foreach ($_POST['checkboxes'] as $key => $id) {
                    DB::table('user_tag')->where('tag_id', (int) $id)->delete();

                    $count++;
                }

                $this->admin_log($count, 'remove', 'tag_manage');
                $this->clear_cache_files();

                $link[] = ['text' => lang('back_list'), 'href' => 'tag_manage.php?act=list'];

                return $this->sys_msg(sprintf(lang('drop_success'), $count), 0, $link);
            } else {
                $link[] = ['text' => lang('back_list'), 'href' => 'tag_manage.php?act=list'];

                return $this->sys_msg(lang('no_select_tag'), 0, $link);
            }
        }

        /**
         * 删除标签
         */
        if ($action === 'remove') {
            $this->check_authz_json('tag_manage');

            $id = intval($_GET['id']);

            // 获取删除的标签的名称
            $tag_name = DB::table('user_tag')->where('tag_id', $id)->value('tag_words');

            $deleted = DB::table('user_tag')->where('tag_id', $id)->delete();
            if ($deleted) {
                // 管理员日志
                $this->admin_log(addslashes($tag_name), 'remove', 'tag_manage');

                $url = 'tag_manage.php?act=query&'.str_replace('act=remove', '', $_SERVER['QUERY_STRING']);

                return response()->redirectTo($url);
            } else {
                return $this->make_json_error('DB error');
            }
        }

        /**
         * 编辑标签名称
         */
        if ($action === 'edit_tag_name') {
            $this->check_authz_json('tag_manage');

            $name = BaseHelper::json_str_iconv(trim($_POST['val']));
            $id = intval($_POST['id']);

            if (! $this->tag_is_only($name, $id)) {
                return $this->make_json_error(sprintf(lang('tagword_exist'), $name));
            } else {
                $this->edit_tag($name, $id);

                return $this->make_json_result(stripslashes($name));
            }
        }
    }

    /**
     * 判断同一商品的标签是否唯一
     *
     * @param  $name  标签名
     * @param  $id  标签id
     * @return bool
     */
    private function tag_is_only($name, $tag_id, $goods_id = '')
    {
        if (empty($goods_id)) {
            $row = (array) DB::table('user_tag')->where('tag_id', $tag_id)->select('goods_id')->first();
            $goods_id = $row['goods_id'];
        }

        if (
            DB::table('user_tag')
                ->where('tag_words', $name)
                ->where('goods_id', $goods_id)
                ->where('tag_id', '<>', $tag_id)
                ->count() > 0
        ) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 更新标签
     *
     * @return void
     */
    private function edit_tag($name, $id, $goods_id = '')
    {
        $query = DB::table('user_tag')->where('tag_id', $id);
        if (! empty($goods_id)) {
            $query->update(['tag_words' => $name, 'goods_id' => $goods_id]);
        } else {
            $query->update(['tag_words' => $name]);
        }

        $this->admin_log($name, 'edit', 'tag');
    }

    /**
     * 获取标签数据列表
     *
     * @return array
     */
    private function get_tag_list()
    {
        $filter['sort_by'] = empty($_REQUEST['sort_by']) ? 't.tag_id' : trim($_REQUEST['sort_by']);
        $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);

        $filter['record_count'] = DB::table('user_tag')->count();

        $filter = MainHelper::page_and_size($filter);

        $row = DB::table('user_tag as t')
            ->leftJoin('user as u', 'u.user_id', '=', 't.user_id')
            ->leftJoin('goods as g', 'g.goods_id', '=', 't.goods_id')
            ->select('t.tag_id', 'u.user_name', 't.goods_id', 'g.goods_name', 't.tag_words')
            ->orderByRaw("{$filter['sort_by']} {$filter['sort_order']}")
            ->offset($filter['start'])
            ->limit($filter['page_size'])
            ->get()
            ->map(fn ($r) => (array) $r)
            ->all();
        foreach ($row as $k => $v) {
            $row[$k]['tag_words'] = htmlspecialchars($v['tag_words']);
        }

        $arr = ['tags' => $row, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']];

        return $arr;
    }

    /**
     * 取得标签的信息
     * return array
     */
    private function get_tag_info($tag_id)
    {
        return (array) DB::table('user_tag as t')
            ->leftJoin('goods as g', 't.goods_id', '=', 'g.goods_id')
            ->where('t.tag_id', $tag_id)
            ->select('t.tag_id', 't.tag_words', 't.goods_id', 'g.goods_name')
            ->first();
    }
}
