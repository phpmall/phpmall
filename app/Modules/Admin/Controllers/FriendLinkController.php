<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Helpers\BaseHelper;
use App\Libraries\Image;
use App\Modules\Admin\Helpers\MainHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FriendLinkController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        $image = new Image(cfg('bgcolor'));

        // Removed: $exc = new Exchange(ecs()->table('shop_friend_link'), db(), 'link_id', 'link_name');

        /**
         * 友情链接列表页面
         */
        if ($action === 'list') {
            $this->assign('ur_here', lang('list_link'));
            $this->assign('action_link', ['text' => lang('add_link'), 'href' => 'friend_link.php?act=add']);
            $this->assign('full_page', 1);

            // 获取友情链接数据
            $links_list = $this->get_links_list();

            $this->assign('links_list', $links_list['list']);
            $this->assign('filter', $links_list['filter']);
            $this->assign('record_count', $links_list['record_count']);
            $this->assign('page_count', $links_list['page_count']);

            $sort_flag = MainHelper::sort_flag($links_list['filter']);
            $this->assign($sort_flag['tag'], $sort_flag['img']);

            return $this->display('link_list');
        }

        /**
         * 排序、分页、查询
         */
        if ($action === 'query') {
            // 获取友情链接数据
            $links_list = $this->get_links_list();

            $this->assign('links_list', $links_list['list']);
            $this->assign('filter', $links_list['filter']);
            $this->assign('record_count', $links_list['record_count']);
            $this->assign('page_count', $links_list['page_count']);

            $sort_flag = MainHelper::sort_flag($links_list['filter']);
            $this->assign($sort_flag['tag'], $sort_flag['img']);

            return $this->make_json_result(
                $this->fetch('link_list'),
                '',
                ['filter' => $links_list['filter'], 'page_count' => $links_list['page_count']]
            );
        }

        /**
         * 添加新链接页面
         */
        if ($action === 'add') {
            $this->admin_priv('friendlink');

            $this->assign('ur_here', lang('add_link'));
            $this->assign('action_link', ['href' => 'friend_link.php?act=list', 'text' => lang('list_link')]);
            $this->assign('action', 'add');
            $this->assign('form_act', 'insert');

            return $this->display('link_info');
        }

        /**
         * 处理添加的链接
         */
        if ($action === 'insert') {
            // 变量初始化
            $link_logo = '';
            $show_order = (! empty($_POST['show_order'])) ? intval($_POST['show_order']) : 0;
            $link_name = (! empty($_POST['link_name'])) ? Str::limit(trim($_POST['link_name']), 250, '') : '';

            // 查看链接名称是否有重复
            if (DB::table('shop_friend_link')->where('link_name', $link_name)->count() === 0) {
                // 处理上传的LOGO图片
                if ((isset($_FILES['link_img']['error']) && $_FILES['link_img']['error'] === 0) || (! isset($_FILES['link_img']['error']) && isset($_FILES['link_img']['tmp_name']) && $_FILES['link_img']['tmp_name'] != 'none')) {
                    $img_up_info = @basename($image->upload_image($_FILES['link_img'], 'afficheimg'));
                    $link_logo = DATA_DIR.'/afficheimg/'.$img_up_info;
                }

                // 使用远程的LOGO图片
                if (! empty($_POST['url_logo'])) {
                    if (strpos($_POST['url_logo'], 'http://') === false && strpos($_POST['url_logo'], 'https://') === false) {
                        $link_logo = 'http://'.trim($_POST['url_logo']);
                    } else {
                        $link_logo = trim($_POST['url_logo']);
                    }
                }

                // 如果链接LOGO为空, LOGO为链接的名称
                if (((isset($_FILES['upfile_flash']['error']) && $_FILES['upfile_flash']['error'] > 0) || (! isset($_FILES['upfile_flash']['error']) && isset($_FILES['upfile_flash']['tmp_name']) && $_FILES['upfile_flash']['tmp_name'] === 'none')) && empty($_POST['url_logo'])) {
                    $link_logo = '';
                }

                // 如果友情链接的链接地址没有http://，补上
                if (strpos($_POST['link_url'], 'http://') === false && strpos($_POST['link_url'], 'https://') === false) {
                    $link_url = 'http://'.trim($_POST['link_url']);
                } else {
                    $link_url = trim($_POST['link_url']);
                }

                // 插入数据
                DB::table('shop_friend_link')->insert([
                    'link_name' => $link_name,
                    'link_url' => $link_url,
                    'link_logo' => $link_logo,
                    'show_order' => $show_order,
                ]);

                // 记录管理员操作
                $this->admin_log($_POST['link_name'], 'add', 'friendlink');

                // 清除缓存
                $this->clear_cache_files();

                // 提示信息
                $link[0]['text'] = lang('continue_add');
                $link[0]['href'] = 'friend_link.php?act=add';

                $link[1]['text'] = lang('back_list');
                $link[1]['href'] = 'friend_link.php?act=list';

                return $this->sys_msg(lang('add').'&nbsp;'.stripcslashes($_POST['link_name']).' '.lang('attradd_succed'), 0, $link);
            } else {
                $link[] = ['text' => lang('go_back'), 'href' => 'javascript:history.back(-1)'];

                return $this->sys_msg(lang('link_name_exist'), 0, $link);
            }
        }

        /**
         * 友情链接编辑页面
         */
        if ($action === 'edit') {
            $this->admin_priv('friendlink');

            // 取得友情链接数据
            $link_arr = DB::table('shop_friend_link')
                ->where('link_id', intval($_REQUEST['id']))
                ->select('link_id', 'link_name', 'link_url', 'link_logo', 'show_order')
                ->first();
            $link_arr = $link_arr ? (array) $link_arr : [];

            // 标记为图片链接还是文字链接
            if (! empty($link_arr['link_logo'])) {
                $type = 'img';
                $link_logo = $link_arr['link_logo'];
            } else {
                $type = 'chara';
                $link_logo = '';
            }

            $link_arr['link_name'] = Str::limit($link_arr['link_name'], 250, ''); // 截取字符串为250个字符避免出现非法字符的情况

            $this->assign('ur_here', lang('edit_link'));
            $this->assign('action_link', ['href' => 'friend_link.php?act=list&'.MainHelper::list_link_postfix(), 'text' => lang('list_link')]);
            $this->assign('form_act', 'update');
            $this->assign('action', 'edit');

            $this->assign('type', $type);
            $this->assign('link_logo', $link_logo);
            $this->assign('link_arr', $link_arr);

            return $this->display('link_info');
        }

        /**
         * 编辑链接的处理页面
         */
        if ($action === 'update') {
            // 变量初始化
            $id = (! empty($_REQUEST['id'])) ? intval($_REQUEST['id']) : 0;
            $show_order = (! empty($_POST['show_order'])) ? intval($_POST['show_order']) : 0;
            $link_name = (! empty($_POST['link_name'])) ? trim($_POST['link_name']) : '';

            $link_logo = ''; // Initialize link_logo for update array

            // 如果有图片LOGO要上传
            if ((isset($_FILES['link_img']['error']) && $_FILES['link_img']['error'] === 0) || (! isset($_FILES['link_img']['error']) && isset($_FILES['link_img']['tmp_name']) && $_FILES['link_img']['tmp_name'] != 'none')) {
                $img_up_info = @basename($image->upload_image($_FILES['link_img'], 'afficheimg'));
                $link_logo = DATA_DIR.'/afficheimg/'.$img_up_info;
            } elseif (! empty($_POST['url_logo'])) {
                $link_logo = $_POST['url_logo'];
            } else {
                // 如果是文字链接, LOGO为链接的名称
                $link_logo = '';
            }

            // 如果要修改链接图片, 删除原来的图片
            if (! empty($img_up_info)) {
                // 获取链子LOGO,并删除
                $old_logo = DB::table('shop_friend_link')->where('link_id', $id)->value('link_logo');
                if ($old_logo && (strpos($old_logo, 'http://') === false) && (strpos($old_logo, 'https://') === false)) {
                    $img_name = basename($old_logo);
                    @unlink(ROOT_PATH.DATA_DIR.'/afficheimg/'.$img_name);
                }
            }

            // 如果友情链接的链接地址没有http://，补上
            if (strpos($_POST['link_url'], 'http://') === false && strpos($_POST['link_url'], 'https://') === false) {
                $link_url = 'http://'.trim($_POST['link_url']);
            } else {
                $link_url = trim($_POST['link_url']);
            }

            // 更新信息
            DB::table('shop_friend_link')->where('link_id', $id)->update([
                'link_name' => $link_name,
                'link_url' => $link_url,
                'link_logo' => $link_logo,
                'show_order' => $show_order,
            ]);
            // 记录管理员操作
            $this->admin_log($_POST['link_name'], 'edit', 'friendlink');

            // 清除缓存
            $this->clear_cache_files();

            // 提示信息
            $link[0]['text'] = lang('back_list');
            $link[0]['href'] = 'friend_link.php?act=list&'.MainHelper::list_link_postfix();

            return $this->sys_msg(lang('edit').'&nbsp;'.stripcslashes($_POST['link_name']).'&nbsp;'.lang('attradd_succed'), 0, $link);
        }

        /**
         * 编辑链接名称
         */
        if ($action === 'edit_link_name') {
            $this->check_authz_json('friendlink');

            $id = intval($_POST['id']);
            $link_name = BaseHelper::json_str_iconv(trim($_POST['val']));

            // 检查链接名称是否重复
            if (DB::table('shop_friend_link')->where('link_name', $link_name)->where('link_id', '<>', $id)->count() != 0) {
                return $this->make_json_error(sprintf(lang('link_name_exist'), $link_name));
            } else {
                if (DB::table('shop_friend_link')->where('link_id', $id)->update(['link_name' => $link_name])) {
                    $this->admin_log($link_name, 'edit', 'friendlink');
                    $this->clear_cache_files();

                    return $this->make_json_result(stripslashes($link_name));
                } else {
                    return $this->make_json_error('DB error'); // Replaced db()->error()
                }
            }
        }

        /**
         * 删除友情链接
         */
        if ($action === 'remove') {
            $this->check_authz_json('friendlink');

            $id = intval($_GET['id']);

            // 获取链子LOGO,并删除
            $link_logo = DB::table('shop_friend_link')->where('link_id', $id)->value('link_logo');

            if ($link_logo && (strpos($link_logo, 'http://') === false) && (strpos($link_logo, 'https://') === false)) {
                $img_name = basename($link_logo);
                @unlink(ROOT_PATH.DATA_DIR.'/afficheimg/'.$img_name);
            }

            DB::table('shop_friend_link')->where('link_id', $id)->delete();
            $this->clear_cache_files();
            $this->admin_log('', 'remove', 'friendlink');

            $url = 'friend_link.php?act=query&'.str_replace('act=remove', '', $_SERVER['QUERY_STRING']);

            return response()->redirectTo($url);
        }

        /**
         * 编辑排序
         */
        if ($action === 'edit_show_order') {
            $this->check_authz_json('friendlink');

            $id = intval($_POST['id']);
            $order = BaseHelper::json_str_iconv(trim($_POST['val']));

            // 检查输入的值是否合法
            if (! preg_match('/^[0-9]+$/', $order)) {
                return $this->make_json_error(sprintf(lang('enter_int'), $order));
            } else {
                if (DB::table('shop_friend_link')->where('link_id', $id)->update(['show_order' => $order])) {
                    $this->clear_cache_files();

                    return $this->make_json_result(stripslashes($order));
                }
            }
        }
    }

    // 获取友情链接数据列表
    private function get_links_list()
    {
        $result = MainHelper::get_filter();
        if ($result === false) {
            $filter = [];
            $filter['sort_by'] = empty($_REQUEST['sort_by']) ? 'link_id' : trim($_REQUEST['sort_by']);
            $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);

            // 获得总记录数据
            $filter['record_count'] = DB::table('shop_friend_link')->count();

            $filter = MainHelper::page_and_size($filter);

            // 获取数据
            $res = DB::table('shop_friend_link')
                ->orderBy($filter['sort_by'], $filter['sort_order'])
                ->offset($filter['start'])
                ->limit($filter['page_size'])
                ->get();

            MainHelper::set_filter($filter, ''); // SQL string is no longer directly used for caching
        } else {
            $filter = $result['filter'];
            // Re-fetch data using the filter from cache
            $res = DB::table('shop_friend_link')
                ->orderBy($filter['sort_by'], $filter['sort_order'])
                ->offset($filter['start'])
                ->limit($filter['page_size'])
                ->get();
        }

        $list = [];
        foreach ($res as $rows) {
            $rows = (array) $rows; // Cast stdClass object to array
            if (empty($rows['link_logo'])) {
                $rows['link_logo'] = '';
            } else {
                if ((strpos($rows['link_logo'], 'http://') === false) && (strpos($rows['link_logo'], 'https://') === false)) {
                    $rows['link_logo'] = "<img src='".'../'.$rows['link_logo']."' width=88 height=31 />";
                } else {
                    $rows['link_logo'] = "<img src='".$rows['link_logo']."' width=88 height=31 />";
                }
            }

            $list[] = $rows;
        }

        return ['list' => $list, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']];
    }
}
