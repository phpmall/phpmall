<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Helpers\BaseHelper;
use App\Services\Article\ArticleCatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArticlecatController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        $articleCatService = new ArticleCatService;

        /**
         * 分类列表
         */
        if ($action === 'list') {
            $articlecat = $articleCatService->article_cat_list(0, 0, false);
            foreach ($articlecat as $key => $cat) {
                $articlecat[$key]['type_name'] = lang('type_name')[$cat['cat_type']];
            }
            $this->assign('ur_here', lang('02_articlecat_list'));
            $this->assign('action_link', ['text' => lang('articlecat_add'), 'href' => 'articlecat.php?act=add']);
            $this->assign('full_page', 1);
            $this->assign('articlecat', $articlecat);

            return $this->display('articlecat_list');
        }

        /**
         * 查询
         */
        if ($action === 'query') {
            $articlecat = $articleCatService->article_cat_list(0, 0, false);
            foreach ($articlecat as $key => $cat) {
                $articlecat[$key]['type_name'] = lang('type_name')[$cat['cat_type']];
            }
            $this->assign('articlecat', $articlecat);

            return $this->make_json_result($this->fetch('articlecat_list'));
        }

        /**
         * 添加分类
         */
        if ($action === 'add') {
            // 权限判断
            $this->admin_priv('article_cat');

            $this->assign('cat_select', $articleCatService->article_cat_list(0));
            $this->assign('ur_here', lang('articlecat_add'));
            $this->assign('action_link', ['text' => lang('02_articlecat_list'), 'href' => 'articlecat.php?act=list']);
            $this->assign('form_action', 'insert');

            return $this->display('articlecat_info');
        }

        if ($action === 'insert') {
            // 权限判断
            $this->admin_priv('article_cat');

            // 检查分类名是否重复
            $is_only = ! DB::table('article_cat')->where('cat_name', $_POST['cat_name'])->exists();

            if (! $is_only) {
                return $this->sys_msg(sprintf(lang('catname_exist'), stripslashes($_POST['cat_name'])), 1);
            }

            $cat_type = 1;
            if ($_POST['parent_id'] > 0) {
                $p_cat_type = DB::table('article_cat')
                    ->where('cat_id', $_POST['parent_id'])
                    ->value('cat_type');

                if ($p_cat_type === 2 || $p_cat_type === 3 || $p_cat_type === 5) {
                    return $this->sys_msg(lang('not_allow_add'), 0);
                } elseif ($p_cat_type === 4) {
                    $cat_type = 5;
                }
            }

            $cat_id = DB::table('article_cat')->insertGetId([
                'cat_name' => $_POST['cat_name'],
                'cat_type' => $cat_type,
                'cat_desc' => $_POST['cat_desc'],
                'keywords' => $_POST['keywords'],
                'parent_id' => $_POST['parent_id'],
                'sort_order' => $_POST['sort_order'],
                'show_in_nav' => $_POST['show_in_nav'],
            ]);

            if ($_POST['show_in_nav'] === 1) {
                $vieworder = DB::table('shop_nav')->where('type', 'middle')->max('vieworder');
                $vieworder += 2;
                // 显示在自定义导航栏中
                DB::table('shop_nav')->insert([
                    'name' => $_POST['cat_name'],
                    'ctype' => 'a',
                    'cid' => $cat_id,
                    'ifshow' => '1',
                    'vieworder' => $vieworder,
                    'opennew' => '0',
                    'url' => build_uri('article_cat', ['acid' => $cat_id], $_POST['cat_name']),
                    'type' => 'middle',
                ]);
            }

            $this->admin_log($_POST['cat_name'], 'add', 'articlecat');

            $link[0]['text'] = lang('continue_add');
            $link[0]['href'] = 'articlecat.php?act=add';

            $link[1]['text'] = lang('back_list');
            $link[1]['href'] = 'articlecat.php?act=list';
            $this->clear_cache_files();

            return $this->sys_msg($_POST['cat_name'].lang('catadd_succed'), 0, $link);
        }

        /**
         * 编辑文章分类
         */
        if ($action === 'edit') {
            // 权限判断
            $this->admin_priv('article_cat');

            $cat = DB::table('article_cat')
                ->select('cat_id', 'cat_name', 'cat_type', 'cat_desc', 'show_in_nav', 'keywords', 'parent_id', 'sort_order')
                ->where('cat_id', $_REQUEST['id'])
                ->first();
            $cat = $cat ? (array) $cat : [];

            if ($cat['cat_type'] === 2 || $cat['cat_type'] === 3 || $cat['cat_type'] === 4) {
                $this->assign('disabled', 1);
            }
            $options = $articleCatService->article_cat_list(0, $cat['parent_id'], false);
            $select = '';
            $selected = $cat['parent_id'];
            foreach ($options as $var) {
                if ($var['cat_id'] === $_REQUEST['id']) {
                    continue;
                }
                $select .= '<option value="'.$var['cat_id'].'" ';
                $select .= ' cat_type="'.$var['cat_type'].'" ';
                $select .= ($selected === $var['cat_id']) ? "selected='ture'" : '';
                $select .= '>';
                if ($var['level'] > 0) {
                    $select .= str_repeat('&nbsp;', $var['level'] * 4);
                }
                $select .= htmlspecialchars($var['cat_name']).'</option>';
            }
            unset($options);
            $this->assign('cat', $cat);
            $this->assign('cat_select', $select);
            $this->assign('ur_here', lang('articlecat_edit'));
            $this->assign('action_link', ['text' => lang('02_articlecat_list'), 'href' => 'articlecat.php?act=list']);
            $this->assign('form_action', 'update');

            return $this->display('articlecat_info');
        }

        if ($action === 'update') {
            // 权限判断
            $this->admin_priv('article_cat');

            // 检查重名
            if ($_POST['cat_name'] != $_POST['old_catname']) {
                $is_only = ! DB::table('article_cat')
                    ->where('cat_name', $_POST['cat_name'])
                    ->where('cat_id', '<>', $_POST['id'])
                    ->exists();

                if (! $is_only) {
                    return $this->sys_msg(sprintf(lang('catname_exist'), stripslashes($_POST['cat_name'])), 1);
                }
            }

            if (! isset($_POST['parent_id'])) {
                $_POST['parent_id'] = 0;
            }

            $row = DB::table('article_cat')->select('cat_type', 'parent_id')->where('cat_id', $_POST['id'])->first();
            $row = $row ? (array) $row : [];
            $cat_type = $row['cat_type'];
            if ($cat_type === 3 || $cat_type === 4) {
                $_POST['parent_id'] = $row['parent_id'];
            }

            // 检查设定的分类的父分类是否合法
            $child_cat = $articleCatService->article_cat_list($_POST['id'], 0, false);
            if (! empty($child_cat)) {
                foreach ($child_cat as $child_data) {
                    $catid_array[] = $child_data['cat_id'];
                }
            }
            if (isset($catid_array) && in_array($_POST['parent_id'], $catid_array)) {
                return $this->sys_msg(sprintf(lang('parent_id_err'), stripslashes($_POST['cat_name'])), 1);
            }

            if ($cat_type === 1 || $cat_type === 5) {
                if ($_POST['parent_id'] > 0) {
                    $p_cat_type = DB::table('article_cat')
                        ->where('cat_id', $_POST['parent_id'])
                        ->value('cat_type');

                    if ($p_cat_type === 4) {
                        $cat_type = 5;
                    } else {
                        $cat_type = 1;
                    }
                } else {
                    $cat_type = 1;
                }
            }

            $dat = DB::table('article_cat')->select('cat_name', 'show_in_nav')->where('cat_id', $_POST['id'])->first();
            $dat = $dat ? (array) $dat : [];

            $update_data = [
                'cat_name' => $_POST['cat_name'],
                'cat_desc' => $_POST['cat_desc'],
                'keywords' => $_POST['keywords'],
                'parent_id' => $_POST['parent_id'],
                'cat_type' => $cat_type,
                'sort_order' => $_POST['sort_order'],
                'show_in_nav' => $_POST['show_in_nav'],
            ];

            if (DB::table('article_cat')->where('cat_id', $_POST['id'])->update($update_data)) {
                if ($_POST['cat_name'] != $dat['cat_name']) {
                    // 如果分类名称发生了改变
                    DB::table('shop_nav')
                        ->where('ctype', 'a')
                        ->where('cid', $_POST['id'])
                        ->where('type', 'middle')
                        ->update(['name' => $_POST['cat_name']]);
                }
                if ($_POST['show_in_nav'] != $dat['show_in_nav']) {
                    if ($_POST['show_in_nav'] === 1) {
                        // 显示
                        $nid = DB::table('shop_nav')
                            ->where('ctype', 'a')
                            ->where('cid', $_POST['id'])
                            ->where('type', 'middle')
                            ->value('id');

                        if (empty($nid)) {
                            $vieworder = DB::table('shop_nav')->where('type', 'middle')->max('vieworder');
                            $vieworder += 2;
                            $uri = build_uri('article_cat', ['acid' => $_POST['id']], $_POST['cat_name']);
                            // 不存在
                            DB::table('shop_nav')->insert([
                                'name' => $_POST['cat_name'],
                                'ctype' => 'a',
                                'cid' => $_POST['id'],
                                'ifshow' => '1',
                                'vieworder' => $vieworder,
                                'opennew' => '0',
                                'url' => $uri,
                                'type' => 'middle',
                            ]);
                        } else {
                            DB::table('shop_nav')
                                ->where('ctype', 'a')
                                ->where('cid', $_POST['id'])
                                ->where('type', 'middle')
                                ->update(['ifshow' => 1]);
                        }
                    } else {
                        // 去除
                        DB::table('shop_nav')
                            ->where('ctype', 'a')
                            ->where('cid', $_POST['id'])
                            ->where('type', 'middle')
                            ->update(['ifshow' => 0]);
                    }
                }
                $link[0]['text'] = lang('back_list');
                $link[0]['href'] = 'articlecat.php?act=list';
                $note = sprintf(lang('catedit_succed'), $_POST['cat_name']);
                $this->admin_log($_POST['cat_name'], 'edit', 'articlecat');
                $this->clear_cache_files();

                return $this->sys_msg($note, 0, $link);
            } else {
                return $this->sys_msg(lang('edit_failed'), 1);
            }
        }

        /**
         * 编辑文章分类的排序
         */
        if ($action === 'edit_sort_order') {
            $this->check_authz_json('article_cat');

            $id = intval($_POST['id']);
            $order = trim($_POST['val']); // BaseHelper::json_str_iconv();

            // 检查输入的值是否合法
            if (! preg_match('/^[0-9]+$/', $order)) {
                return $this->make_json_error(sprintf(lang('enter_int'), $order));
            } else {
                if (DB::table('article_cat')->where('cat_id', $id)->update(['sort_order' => $order])) {
                    $this->clear_cache_files();

                    return $this->make_json_result((string) stripslashes($order));
                } else {
                    return $this->make_json_error('DB error');
                }
            }
        }

        /**
         * 删除文章分类
         */
        if ($action === 'remove') {
            $this->check_authz_json('article_cat');

            $id = intval($_GET['id']);

            $cat = DB::table('article_cat')->where('cat_id', $id)->first();
            $cat = $cat ? (array) $cat : [];
            $cat_type = $cat['cat_type'] ?? 0;

            if ($cat_type === 2 || $cat_type === 3 || $cat_type === 4) {
                // 系统保留分类，不能删除
                return $this->make_json_error(lang('not_allow_remove'));
            }

            if (DB::table('article_cat')->where('parent_id', $id)->count() > 0) {
                // 还有子分类，不能删除
                return $this->make_json_error(lang('is_fullcat'));
            }

            // 非空的分类不允许删除
            if (DB::table('article')->where('cat_id', $id)->count() > 0) {
                return $this->make_json_error(sprintf(lang('not_emptycat')));
            } else {
                DB::table('article_cat')->where('cat_id', $id)->delete();
                DB::table('shop_nav')
                    ->where('ctype', 'a')
                    ->where('cid', $id)
                    ->where('type', 'middle')
                    ->delete();
                $this->clear_cache_files();
                $this->admin_log($cat['cat_name'], 'remove', 'category');
            }

            $url = 'articlecat.php?act=query&'.str_replace('act=remove', '', $_SERVER['QUERY_STRING']);

            return redirect()->to($url);
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
                    $nid = DB::table('shop_nav')
                        ->where('ctype', 'a')
                        ->where('cid', $id)
                        ->where('type', 'middle')
                        ->value('id');

                    if (empty($nid)) {
                        // 不存在
                        $vieworder = DB::table('shop_nav')->where('type', 'middle')->max('vieworder');
                        $vieworder += 2;
                        $catname = DB::table('article_cat')->where('cat_id', $id)->value('cat_name');
                        $uri = build_uri('article_cat', ['acid' => $id], $_POST['cat_name']);

                        DB::table('shop_nav')->insert([
                            'name' => $catname,
                            'ctype' => 'a',
                            'cid' => $id,
                            'ifshow' => '1',
                            'vieworder' => $vieworder,
                            'opennew' => '0',
                            'url' => $uri,
                            'type' => 'middle',
                        ]);
                    } else {
                        DB::table('shop_nav')
                            ->where('ctype', 'a')
                            ->where('cid', $id)
                            ->where('type', 'middle')
                            ->update(['ifshow' => 1]);
                    }
                } else {
                    // 去除
                    DB::table('shop_nav')
                        ->where('ctype', 'a')
                        ->where('cid', $id)
                        ->where('type', 'middle')
                        ->update(['ifshow' => 0]);
                }
                $this->clear_cache_files();

                return $this->make_json_result((string) $val);
            } else {
                return $this->make_json_error('DB error');
            }
        }
    }

    /**
     * 添加商品分类
     *
     * * @param int $cat_id
     * @param  array  $args
     * @return mixed
     */
    private function cat_update($cat_id, $args)
    {
        if (empty($args) || empty($cat_id)) {
            return false;
        }

        return DB::table('article_cat')->where('cat_id', $cat_id)->update($args);
    }
}
