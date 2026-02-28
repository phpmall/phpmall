<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Helpers\BaseHelper;
use App\Helpers\CommonHelper;
use App\Helpers\TimeHelper;
use App\Modules\Admin\Helpers\MainHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FlashplayController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        $uri = ecs()->url();
        $allow_suffix = ['gif', 'jpg', 'png', 'jpeg', 'bmp'];

        /**
         * 系统
         */
        if ($action === 'list') {
            // 判断系统当前设置 如果为用户自定义 则跳转到自定义
            if (cfg('index_ad') === 'cus') {
                return response()->redirectTo('flashplay.php?act=custom_list');
            }

            $playerdb = $this->get_flash_xml();
            foreach ($playerdb as $key => $val) {
                if (strpos($val['src'], 'http') === false) {
                    $playerdb[$key]['src'] = $uri.$val['src'];
                }
            }

            // 标签初始化
            $group_list = [
                'sys' => ['text' => lang('system_set'), 'url' => ''],
                'cus' => ['text' => lang('custom_set'), 'url' => 'flashplay.php?act=custom_list'],
            ];

            $flash_dir = ROOT_PATH.'data/flashdata/';

            $this->assign('current', 'sys');
            $this->assign('group_list', $group_list);
            $this->assign('group_selected', cfg('index_ad'));
            $this->assign('uri', $uri);
            $this->assign('ur_here', lang('flashplay'));
            $this->assign('action_link_special', ['text' => lang('add_new'), 'href' => 'flashplay.php?act=add']);
            $this->assign('flashtpls', $this->get_flash_templates($flash_dir));
            $this->assign('current_flashtpl', cfg('flash_theme'));
            $this->assign('playerdb', $playerdb);

            return $this->display('flashplay_list');
        }

        if ($action === 'del') {
            $this->admin_priv('flash_manage');

            $id = (int) $_GET['id'];
            $flashdb = $this->get_flash_xml();
            if (isset($flashdb[$id])) {
                $rt = $flashdb[$id];
            } else {
                $links[] = ['text' => lang('go_url'), 'href' => 'flashplay.php?act=list'];

                return $this->sys_msg(lang('id_error'), 0, $links);
            }

            if (strpos($rt['src'], 'http') === false) {
                @unlink(ROOT_PATH.$rt['src']);
            }
            $temp = [];
            foreach ($flashdb as $key => $val) {
                if ($key != $id) {
                    $temp[] = $val;
                }
            }
            $this->put_flash_xml($temp);
            $error_msg = '';
            $this->set_flash_data(cfg('flash_theme'), $error_msg);

            return response()->redirectTo('flashplay.php?act=list');
        }

        if ($action === 'add') {
            $this->admin_priv('flash_manage');

            if (empty($_POST['step'])) {
                $url = isset($_GET['url']) ? $_GET['url'] : 'http://';
                $src = isset($_GET['src']) ? $_GET['src'] : '';
                $sort = 0;
                $rt = ['act' => 'add', 'img_url' => $url, 'img_src' => $src, 'img_sort' => $sort];
                $width_height = $this->get_width_height();

                if (isset($width_height['width']) || isset($width_height['height'])) {
                    $this->assign('width_height', sprintf(lang('width_height'), $width_height['width'], $width_height['height']));
                }

                $this->assign('action_link', ['text' => lang('go_url'), 'href' => 'flashplay.php?act=list']);
                $this->assign('rt', $rt);
                $this->assign('ur_here', lang('add_picad'));

                return $this->display('flashplay_add');
            } elseif ($_POST['step'] === 2) {
                if (! empty($_FILES['img_file_src']['name'])) {
                    if (! BaseHelper::get_file_suffix($_FILES['img_file_src']['name'], $allow_suffix)) {
                        return $this->sys_msg(lang('invalid_type'));
                    }
                    $name = date('Ymd');
                    for ($i = 0; $i < 6; $i++) {
                        $name .= chr(mt_rand(97, 122));
                    }
                    $img_file_src_name_arr = explode('.', $_FILES['img_file_src']['name']);
                    $name .= '.'.end($img_file_src_name_arr);
                    $target = ROOT_PATH.DATA_DIR.'/afficheimg/'.$name;
                    if (BaseHelper::move_upload_file($_FILES['img_file_src']['tmp_name'], $target)) {
                        $src = DATA_DIR.'/afficheimg/'.$name;
                    }
                } elseif (! empty($_POST['img_src'])) {
                    if (! BaseHelper::get_file_suffix($_POST['img_src'], $allow_suffix)) {
                        return $this->sys_msg(lang('invalid_type'));
                    }
                    $src = $_POST['img_src'];
                    if (strstr($src, 'http') && ! strstr($src, $_SERVER['SERVER_NAME'])) {
                        $src = $this->get_url_image($src);
                    }
                } else {
                    $links[] = ['text' => lang('add_new'), 'href' => 'flashplay.php?act=add'];

                    return $this->sys_msg(lang('src_empty'), 0, $links);
                }

                if (empty($_POST['img_url'])) {
                    $links[] = ['text' => lang('add_new'), 'href' => 'flashplay.php?act=add'];

                    return $this->sys_msg(lang('link_empty'), 0, $links);
                }

                // 获取flash播放器数据
                $flashdb = $this->get_flash_xml();

                // 插入新数据
                array_unshift($flashdb, ['src' => $src, 'url' => $_POST['img_url'], 'text' => $_POST['img_text'], 'sort' => $_POST['img_sort']]);

                // 实现排序
                $flashdb_sort = [];
                $_flashdb = [];
                foreach ($flashdb as $key => $value) {
                    $flashdb_sort[$key] = $value['sort'];
                }
                asort($flashdb_sort, SORT_NUMERIC);
                foreach ($flashdb_sort as $key => $value) {
                    $_flashdb[] = $flashdb[$key];
                }
                unset($flashdb, $flashdb_sort);

                $this->put_flash_xml($_flashdb);
                $error_msg = '';
                $this->set_flash_data(cfg('flash_theme'), $error_msg);
                $links[] = ['text' => lang('go_url'), 'href' => 'flashplay.php?act=list'];

                return $this->sys_msg(lang('edit_ok'), 0, $links);
            }
        }

        if ($action === 'edit') {
            $this->admin_priv('flash_manage');

            $id = (int) $_REQUEST['id']; // 取得id
            $flashdb = $this->get_flash_xml(); // 取得数据
            if (isset($flashdb[$id])) {
                $rt = $flashdb[$id];
            } else {
                $links[] = ['text' => lang('go_url'), 'href' => 'flashplay.php?act=list'];

                return $this->sys_msg(lang('id_error'), 0, $links);
            }
            if (empty($_POST['step'])) {
                $rt['act'] = 'edit';
                $rt['img_url'] = $rt['url'];
                $rt['img_src'] = $rt['src'];
                $rt['img_txt'] = $rt['text'];
                $rt['img_sort'] = empty($rt['sort']) ? 0 : $rt['sort'];

                $rt['id'] = $id;
                $this->assign('action_link', ['text' => lang('go_url'), 'href' => 'flashplay.php?act=list']);
                $this->assign('rt', $rt);
                $this->assign('ur_here', lang('edit_picad'));

                return $this->display('flashplay_add');
            } elseif ($_POST['step'] === 2) {
                if (empty($_POST['img_url'])) {
                    // 若链接地址为空
                    $links[] = ['text' => lang('return_edit'), 'href' => 'flashplay.php?act=edit&id='.$id];

                    return $this->sys_msg(lang('link_empty'), 0, $links);
                }

                if (! empty($_FILES['img_file_src']['name'])) {
                    if (! BaseHelper::get_file_suffix($_FILES['img_file_src']['name'], $allow_suffix)) {
                        return $this->sys_msg(lang('invalid_type'));
                    }
                    // 有上传
                    $name = date('Ymd');
                    for ($i = 0; $i < 6; $i++) {
                        $name .= chr(mt_rand(97, 122));
                    }
                    $img_file_src_name_arr = explode('.', $_FILES['img_file_src']['name']);
                    $name .= '.'.end($img_file_src_name_arr);
                    $target = ROOT_PATH.DATA_DIR.'/afficheimg/'.$name;

                    if (BaseHelper::move_upload_file($_FILES['img_file_src']['tmp_name'], $target)) {
                        $src = DATA_DIR.'/afficheimg/'.$name;
                    }
                } elseif (! empty($_POST['img_src'])) {
                    $src = $_POST['img_src'];
                    if (! BaseHelper::get_file_suffix($_POST['img_src'], $allow_suffix)) {
                        return $this->sys_msg(lang('invalid_type'));
                    }
                    if (strstr($src, 'http') && ! strstr($src, $_SERVER['SERVER_NAME'])) {
                        $src = $this->get_url_image($src);
                    }
                } else {
                    $links[] = ['text' => lang('return_edit'), 'href' => 'flashplay.php?act=edit&id='.$id];

                    return $this->sys_msg(lang('src_empty'), 0, $links);
                }

                if (strpos($rt['src'], 'http') === false && $rt['src'] != $src) {
                    @unlink(ROOT_PATH.$rt['src']);
                }
                $flashdb[$id] = ['src' => $src, 'url' => $_POST['img_url'], 'text' => $_POST['img_text'], 'sort' => $_POST['img_sort']];

                // 实现排序
                $flashdb_sort = [];
                $_flashdb = [];
                foreach ($flashdb as $key => $value) {
                    $flashdb_sort[$key] = $value['sort'];
                }
                asort($flashdb_sort, SORT_NUMERIC);
                foreach ($flashdb_sort as $key => $value) {
                    $_flashdb[] = $flashdb[$key];
                }
                unset($flashdb, $flashdb_sort);

                $this->put_flash_xml($_flashdb);
                $error_msg = '';
                $this->set_flash_data(cfg('flash_theme'), $error_msg);
                $links[] = ['text' => lang('go_url'), 'href' => 'flashplay.php?act=list'];

                return $this->sys_msg(lang('edit_ok'), 0, $links);
            }
        }

        if ($action === 'install') {
            $this->check_authz_json('flash_manage');
            $flash_theme = trim($_GET['flashtpl']);
            if (cfg('flash_theme') != $flash_theme) {
                $result = DB::table('shop_config')
                    ->where('code', 'flash_theme')
                    ->update(['value' => $flash_theme]);

                if ($result !== false) {
                    CommonHelper::clear_all_files(); // 清除模板编译文件

                    $error_msg = '';
                    if ($this->set_flash_data($flash_theme, $error_msg)) {
                        return $this->make_json_error($error_msg);
                    } else {
                        return $this->make_json_result($flash_theme, lang('install_success'));
                    }
                } else {
                    return $this->make_json_error('Update failed');
                }
            } else {
                return $this->make_json_result($flash_theme, lang('install_success'));
            }
        }

        /**
         * 用户自定义
         */
        if ($action === 'custom_list') {
            // 标签初始化
            $group_list = [
                'sys' => ['text' => lang('system_set'), 'url' => (cfg('index_ad') === 'cus') ? 'javascript:system_set();void(0);' : 'flashplay.php?act=list'],
                'cus' => ['text' => lang('custom_set'), 'url' => ''],
            ];

            // 列表
            $ad_list = $this->ad_list();
            $this->assign('ad_list', $ad_list['ad']);

            $width_height = $this->get_width_height();
            //        if(isset($width_height['width'])|| isset($width_height['height']))
            //        {
            $this->assign('width_height', sprintf(lang('width_height'), $width_height['width'], $width_height['height']));
            //        }
            $this->assign('full_page', 1);
            $this->assign('current', 'cus');
            $this->assign('group_list', $group_list);
            $this->assign('group_selected', cfg('index_ad'));
            $this->assign('uri', $uri);
            $this->assign('ur_here', lang('flashplay'));
            $this->assign('action_link_special', ['text' => lang('add_flash'), 'href' => 'flashplay.php?act=custom_add']);

            // 添加
            $ad = [
                'ad_name' => '',
                'ad_type' => 0,
                'ad_url' => 'http://',
                'htmls' => '',
                'ad_status' => '1',
                'ad_id' => '0',
                'url' => 'http://',
            ];
            $this->assign('ad', $ad);
            $this->assign('form_act', 'custom_insert');

            return $this->display('flashplay_custom');
        }

        /**
         * 用户自定义添加
         */
        if ($action === 'custom_add') {
            // 标签初始化
            $group_list = [
                'sys' => ['text' => lang('system_set'), 'url' => (cfg('index_ad') === 'cus') ? 'javascript:system_set();void(0);' : 'flashplay.php?act=list'],
                'cus' => ['text' => lang('custom_set'), 'url' => ''],
            ];

            // 列表
            $ad_list = $this->ad_list();
            $this->assign('ad_list', $ad_list['ad']);

            $width_height = $this->get_width_height();
            //        if(isset($width_height['width'])|| isset($width_height['height']))
            //        {
            $this->assign('width_height', sprintf(lang('width_height'), $width_height['width'], $width_height['height']));
            //        }
            $this->assign('full_page', 1);
            $this->assign('current', 'cus');
            $this->assign('group_list', $group_list);
            $this->assign('group_selected', cfg('index_ad'));
            $this->assign('uri', $uri);
            $this->assign('ur_here', lang('add_ad'));
            $this->assign('action_link_special', ['text' => lang('add_flash'), 'href' => 'flashplay.php?act=custom_add']);
            $this->assign('action_link', ['text' => lang('ad_play_url'), 'href' => 'flashplay.php?act=custom_list']);
            // 添加
            $ad = [
                'ad_name' => '',
                'ad_type' => 0,
                'ad_url' => 'http://',
                'htmls' => '',
                'ad_status' => '1',
                'ad_id' => '0',
                'url' => 'http://',
            ];
            $this->assign('ad', $ad);
            $this->assign('form_act', 'custom_insert');

            return $this->display('flashplay_custom_add');
        }

        /**
         * 用户自定义 添加广告入库
         */
        if ($action === 'custom_insert') {
            $this->admin_priv('flash_manage');

            // 定义当前时间
            define('GMTIME_UTC', TimeHelper::gmtime()); // 获取 UTC 时间戳

            if (empty($_POST['ad']) || empty($_POST['content']) || empty($_POST['ad']['ad_name'])) {
                $links[] = ['text' => lang('back'), 'href' => 'flashplay.php?act=custom_list'];

                return $this->sys_msg(lang('form_none'), 0, $links);
            }

            $filter = [];
            $filter['ad'] = $_POST['ad'];
            $filter['content'] = $_POST['content'];
            $ad_img = $_FILES;

            // 配置接收文件类型
            switch ($filter['ad']['ad_type']) {
                case '0':
                    break;

                case '1':
                    $allow_suffix[] = 'swf';
                    break;
            }

            // 接收文件
            if ($ad_img['ad_img']['name'] && $ad_img['ad_img']['size'] > 0) {
                // 检查文件合法性
                if (! BaseHelper::get_file_suffix($ad_img['ad_img']['name'], $allow_suffix)) {
                    return $this->sys_msg(lang('invalid_type'));
                }

                // 处理
                $name = date('Ymd');
                for ($i = 0; $i < 6; $i++) {
                    $name .= chr(mt_rand(97, 122));
                }
                $ad_img_name_arr = explode('.', $ad_img['ad_img']['name']);
                $name .= '.'.end($ad_img_name_arr);
                $target = ROOT_PATH.DATA_DIR.'/afficheimg/'.$name;

                if (BaseHelper::move_upload_file($ad_img['ad_img']['tmp_name'], $target)) {
                    $src = DATA_DIR.'/afficheimg/'.$name;
                }
            } elseif (! empty($filter['content']['url'])) {
                // 来自互联网图片 不可以是服务器地址
                if (strstr($filter['content']['url'], 'http') && ! strstr($filter['content']['url'], $_SERVER['SERVER_NAME'])) {
                    // 取互联网图片至本地
                    $src = $this->get_url_image($filter['content']['url']);
                } else {
                    return $this->sys_msg(lang('web_url_no'));
                }
            }

            // 入库
            switch ($filter['ad']['ad_type']) {
                case '0':

                case '1':
                    $filter['content'] = $src;
                    break;

                case '2':

                case '3':
                    $filter['content'] = $filter['content']['htmls'];
                    break;
            }
            $ad = [
                'ad_type' => $filter['ad']['ad_type'],
                'ad_name' => $filter['ad']['ad_name'],
                'add_time' => GMTIME_UTC,
                'content' => $filter['content'],
                'url' => $filter['ad']['url'],
                'ad_status' => $filter['ad']['ad_status'],
            ];
            $ad_id = DB::table('ad_custom')->insertGetId($ad);

            // 修改状态
            $this->modfiy_ad_status($ad_id, $filter['ad']['ad_status']);

            // 状态为启用 清除模板编译文件
            if ($filter['ad']['ad_status'] === 1) {
                CommonHelper::clear_all_files();
            }

            $links[] = ['text' => lang('back_custom_set'), 'href' => 'flashplay.php?act=custom_list'];

            return $this->sys_msg(lang('edit_ok'), 0, $links);
        }

        /**
         * 用户自定义 删除广告
         */
        if ($action === 'custom_del') {
            $this->admin_priv('flash_manage');

            $id = empty($_GET['id']) ? 0 : intval(trim($_GET['id']));
            if (! $id) {
                $links[] = ['text' => lang('back_custom_set'), 'href' => 'flashplay.php?act=custom_list'];

                return $this->sys_msg(lang('form_none'), 0, $links);
            }

            // 修改状态
            $this->modfiy_ad_status($id, 0);

            // 清除模板编译文件
            CommonHelper::clear_all_files();

            $query = DB::table('ad_custom')->where('ad_id', $id)->delete();

            $links[] = ['text' => lang('back_custom_set'), 'href' => 'flashplay.php?act=custom_list'];
            if ($query) {
                return $this->sys_msg(lang('edit_ok'), 0, $links);
            } else {
                return $this->sys_msg(lang('edit_no'), 0, $links);
            }
        }

        /**
         * 用户自定义 启用与关闭广告
         */
        if ($action === 'custom_status') {
            $this->check_authz_json('flash_manage');

            $ad_status = empty($_GET['ad_status']) ? 1 : 0;
            $id = empty($_GET['id']) ? 0 : intval(trim($_GET['id']));
            $is_ajax = $_GET['is_ajax'];
            if (! $id || $is_ajax != '1') {
                return $this->make_json_error(lang('edit_no'));
            }

            // 修改状态
            $links[] = ['text' => lang('back_custom_set'), 'href' => 'flashplay.php?act=custom_list'];
            if ($this->modfiy_ad_status($id, $ad_status)) {
                // 清除模板编译文件
                CommonHelper::clear_all_files();

                // 标签初始化
                $shop_config = DB::table('shop_config')->where('id', 337)->first();
                $shop_config = $shop_config ? (array) $shop_config : [];
                $group_list = [
                    'sys' => ['text' => lang('system_set'), 'url' => (isset($shop_config['value']) && $shop_config['value'] === 'cus') ? 'javascript:system_set();void(0);' : 'flashplay.php?act=list'],
                    'cus' => ['text' => lang('custom_set'), 'url' => ''],
                ];

                // 列表
                $ad_list = $this->ad_list();
                $this->assign('ad_list', $ad_list['ad']);
                $this->assign('current', 'cus');
                $this->assign('group_list', $group_list);
                $this->assign('group_selected', cfg('index_ad'));
                $this->assign('uri', $uri);
                $this->assign('ur_here', lang('flashplay'));
                $this->assign('action_link_special', ['text' => lang('add_flash'), 'href' => 'flashplay.php?act=custom_add']);
                // 添加
                $ad = [
                    'ad_name' => '',
                    'ad_type' => 0,
                    'ad_url' => 'http://',
                    'htmls' => '',
                    'ad_status' => '1',
                    'ad_id' => '0',
                    'url' => 'http://',
                ];
                $this->assign('ad', $ad);
                $this->assign('form_act', 'custom_insert');

                $this->fetch('flashplay_custom');

                return $this->make_json_result($this->fetch('flashplay_custom'));
            } else {
                return $this->make_json_error(lang('edit_no'));
            }
        }

        /**
         * 用户自定义 修改
         */
        if ($action === 'custom_edit') {
            $id = empty($_GET['id']) ? 0 : intval(trim($_GET['id']));

            // 查询自定义广告信息
            $ad = DB::table('ad_custom')
                ->select('ad_id', 'ad_type', 'content', 'url', 'ad_status', 'ad_name')
                ->where('ad_id', $id)
                ->first();
            $ad = $ad ? (array) $ad : [];

            $width_height = $this->get_width_height();
            $this->assign('width_height', sprintf(lang('width_height'), $width_height['width'], $width_height['height']));

            $this->assign('group_selected', cfg('index_ad'));
            $this->assign('uri', $uri);
            $this->assign('ur_here', lang('flashplay'));
            $this->assign('action_link', ['text' => lang('ad_play_url'), 'href' => 'flashplay.php?act=custom_list']);
            $this->assign('ur_here', lang('edit_ad'));

            // 添加
            $this->assign('ad', $ad);

            return $this->display('flashplay_ccustom_edit');
        }

        /**
         * 用户自定义 更新数据库
         */
        if ($action === 'custom_update') {
            $this->admin_priv('flash_manage');

            if (empty($_POST['ad']) || empty($_POST['content']) || empty($_POST['ad']['ad_name']) || empty($_POST['ad']['id'])) {
                $links[] = ['text' => lang('back'), 'href' => 'flashplay.php?act=custom_list'];

                return $this->sys_msg(lang('form_none'), 0, $links);
            }

            $filter = [];
            $filter['ad'] = $_POST['ad'];
            $filter['content'] = $_POST['content'];
            $ad_img = $_FILES;

            // 查询自定义广告信息
            $ad_info = DB::table('ad_custom')
                ->select('ad_id', 'ad_type', 'content', 'url', 'ad_status', 'ad_name')
                ->where('ad_id', $filter['ad']['id'])
                ->first();
            $ad_info = $ad_info ? (array) $ad_info : [];

            // 配置接收文件类型
            switch ($filter['ad']['ad_type']) {
                case '0':
                    break;

                case '1':
                    $allow_suffix[] = 'swf';
                    break;
            }

            // 接收文件
            if ($ad_img['ad_img']['name'] && $ad_img['ad_img']['size'] > 0) {
                // 检查文件合法性
                if (! BaseHelper::get_file_suffix($ad_img['ad_img']['name'], $allow_suffix)) {
                    return $this->sys_msg(lang('invalid_type'));
                }

                // 处理
                $name = date('Ymd');
                for ($i = 0; $i < 6; $i++) {
                    $name .= chr(mt_rand(97, 122));
                }
                $ad_img_name_arr = explode('.', $ad_img['ad_img']['name']);
                $name .= '.'.end($ad_img_name_arr);
                $target = ROOT_PATH.DATA_DIR.'/afficheimg/'.$name;

                if (BaseHelper::move_upload_file($ad_img['ad_img']['tmp_name'], $target)) {
                    $src = DATA_DIR.'/afficheimg/'.$name;
                }
            } elseif (! empty($filter['content']['url'])) {
                // 来自互联网图片 不可以是服务器地址
                if (strstr($filter['content']['url'], 'http') && ! strstr($filter['content']['url'], $_SERVER['SERVER_NAME'])) {
                    // 取互联网图片至本地
                    $src = $this->get_url_image($filter['content']['url']);
                } else {
                    return $this->sys_msg(lang('web_url_no'));
                }
            }

            // 入库
            switch ($filter['ad']['ad_type']) {
                case '0':

                case '1':
                    $filter['content'] = ! is_file(ROOT_PATH.$src) && (trim($src) === '') ? $ad_info['content'] : $src;
                    break;

                case '2':

                case '3':
                    $filter['content'] = $filter['content']['htmls'];
                    break;
            }
            $ad = [
                'ad_type' => $filter['ad']['ad_type'],
                'ad_name' => $filter['ad']['ad_name'],
                'content' => $filter['content'],
                'url' => $filter['ad']['url'],
                'ad_status' => $filter['ad']['ad_status'],
            ];
            DB::table('ad_custom')->where('ad_id', $ad_info['ad_id'])->update($ad);

            // 修改状态
            $this->modfiy_ad_status($ad_info['ad_id'], $filter['ad']['ad_status']);

            // 状态为启用 清除模板编译文件
            if ($filter['ad']['ad_status'] === 1) {
                CommonHelper::clear_all_files();
            }

            $links[] = ['text' => lang('back_custom_set'), 'href' => 'flashplay.php?act=custom_list'];

            return $this->sys_msg(lang('edit_ok'), 0, $links);
        }
    }

    private function get_flash_xml()
    {
        $flashdb = [];
        if (file_exists(ROOT_PATH.DATA_DIR.'/flash_data.xml')) {
            // 兼容v2.7.0及以前版本 TODO
            if (! preg_match_all('/item_url="([^"]+)"\slink="([^"]+)"\stext="([^"]*)"\ssort="([^"]*)"/', file_get_contents(ROOT_PATH.DATA_DIR.'/flash_data.xml'), $t, PREG_SET_ORDER)) {
                preg_match_all('/item_url="([^"]+)"\slink="([^"]+)"\stext="([^"]*)"/', file_get_contents(ROOT_PATH.DATA_DIR.'/flash_data.xml'), $t, PREG_SET_ORDER);
            }

            if (! empty($t)) {
                foreach ($t as $key => $val) {
                    $val[4] = isset($val[4]) ? $val[4] : 0;
                    $flashdb[] = ['src' => $val[1], 'url' => $val[2], 'text' => $val[3], 'sort' => $val[4]];
                }
            }
        }

        return $flashdb;
    }

    private function put_flash_xml($flashdb)
    {
        if (! empty($flashdb)) {
            $xml = '<?xml version="1.0" encoding="'.EC_CHARSET.'"?><bcaster>';
            foreach ($flashdb as $key => $val) {
                $xml .= '<item item_url="'.$val['src'].'" link="'.$val['url'].'" text="'.$val['text'].'" sort="'.$val['sort'].'"/>';
            }
            $xml .= '</bcaster>';
            file_put_contents(ROOT_PATH.DATA_DIR.'/flash_data.xml', $xml);
        } else {
            @unlink(ROOT_PATH.DATA_DIR.'/flash_data.xml');
        }
    }

    private function get_url_image($url)
    {
        $url_arr = explode('.', $url);
        $ext = strtolower(end($url_arr));
        if ($ext != 'gif' && $ext != 'jpg' && $ext != 'png' && $ext != 'bmp' && $ext != 'jpeg') {
            return $url;
        }

        $name = date('Ymd');
        for ($i = 0; $i < 6; $i++) {
            $name .= chr(mt_rand(97, 122));
        }
        $name .= '.'.$ext;
        $target = ROOT_PATH.DATA_DIR.'/afficheimg/'.$name;

        $tmp_file = DATA_DIR.'/afficheimg/'.$name;
        $filename = ROOT_PATH.$tmp_file;

        $img = file_get_contents($url);

        $fp = @fopen($filename, 'a');
        fwrite($fp, $img);
        fclose($fp);

        return $tmp_file;
    }

    private function get_width_height()
    {
        $curr_template = cfg('template');
        $path = ROOT_PATH.'themes/'.$curr_template.'/library/';
        $template_dir = @opendir($path);

        $width_height = [];
        while ($file = readdir($template_dir)) {
            if ($file === 'index_ad') {
                $string = file_get_contents($path.$file);
                $pattern_width = '/var\s*swf_width\s*=\s*(\d+);/';
                $pattern_height = '/var\s*swf_height\s*=\s*(\d+);/';
                preg_match($pattern_width, $string, $width);
                preg_match($pattern_height, $string, $height);
                if (isset($width[1])) {
                    $width_height['width'] = $width[1];
                }
                if (isset($height[1])) {
                    $width_height['height'] = $height[1];
                }
                break;
            }
        }

        return $width_height;
    }

    private function get_flash_templates($dir)
    {
        $flashtpls = [];
        $template_dir = @opendir($dir);
        while ($file = readdir($template_dir)) {
            if ($file != '.' && $file != '..' && is_dir($dir.$file) && $file != '.svn' && $file != 'index') {
                $flashtpls[] = $this->get_flash_tpl_info($dir, $file);
            }
        }
        @closedir($template_dir);

        return $flashtpls;
    }

    private function get_flash_tpl_info($dir, $file)
    {
        $info = [];
        if (is_file($dir.$file.'/preview.jpg')) {
            $info['code'] = $file;
            $info['screenshot'] = '../data/flashdata/'.$file.'/preview.jpg';
            $arr = array_slice(file($dir.$file.'/cycle_image.js'), 1, 2);
            $info_name = explode(':', $arr[0]);
            $info_desc = explode(':', $arr[1]);
            $info['name'] = isset($info_name[1]) ? trim($info_name[1]) : '';
            $info['desc'] = isset($info_desc[1]) ? trim($info_desc[1]) : '';
        }

        return $info;
    }

    private function set_flash_data($tplname, &$msg)
    {
        $flashdata = $this->get_flash_xml();
        if (empty($flashdata)) {
            $flashdata[] = [
                'src' => 'data/afficheimg/20081027angsif.jpg',
                'text' => 'PHPMall',
                'url' => 'http://www.phpmall.net',
            ];
            $flashdata[] = [
                'src' => 'data/afficheimg/20081027wdwd.jpg',
                'text' => 'wdwd',
                'url' => 'http://www.wdwd.com',
            ];
            $flashdata[] = [
                'src' => 'data/afficheimg/20081027xuorxj.jpg',
                'text' => 'PHPMall',
                'url' => 'http://help.phpmall.net/index.php?doc-view-108.htm',
            ];
        }
        switch ($tplname) {
            case 'uproll':
                $msg = $this->set_flash_uproll($tplname, $flashdata);
                break;
            case 'redfocus':
            case 'pinkfocus':
            case 'dynfocus':
                $msg = $this->set_flash_focus($tplname, $flashdata);
                break;
            case 'default':
            default:
                $msg = $this->set_flash_default($tplname, $flashdata);
                break;
        }

        return $msg !== true;
    }

    private function set_flash_uproll($tplname, $flashdata)
    {
        $data_file = ROOT_PATH.DATA_DIR.'/flashdata/'.$tplname.'/data.xml';
        $xmldata = '<?xml version="1.0" encoding="'.EC_CHARSET.'"?><myMenu>';
        foreach ($flashdata as $data) {
            $xmldata .= '<myItem pic="'.$data['src'].'" url="'.$data['url'].'" />';
        }
        $xmldata .= '</myMenu>';
        file_put_contents($data_file, $xmldata);

        return true;
    }

    private function set_flash_focus($tplname, $flashdata)
    {
        $data_file = ROOT_PATH.DATA_DIR.'/flashdata/'.$tplname.'/data.js';
        $jsdata = '';
        $jsdata2 = ['url' => 'var pics=', 'txt' => 'var texts=', 'link' => 'var links='];
        $count = 1;
        $join = '';
        foreach ($flashdata as $data) {
            $jsdata .= 'imgUrl'.$count.'="'.$data['src'].'";'."\n";
            $jsdata .= 'imgtext'.$count.'="'.$data['text'].'";'."\n";
            $jsdata .= 'imgLink'.$count.'=escape("'.$data['url'].'");'."\n";
            if ($count != 1) {
                $join = '+"|"+';
            }
            $jsdata2['url'] .= $join.'imgUrl'.$count;
            $jsdata2['txt'] .= $join.'imgtext'.$count;
            $jsdata2['link'] .= $join.'imgLink'.$count;
            $count++;
        }
        file_put_contents($data_file, $jsdata."\n".$jsdata2['url'].";\n".$jsdata2['link'].";\n".$jsdata2['txt'].';');

        return true;
    }

    private function set_flash_default($tplname, $flashdata)
    {
        $data_file = ROOT_PATH.DATA_DIR.'/flashdata/'.$tplname.'/data.xml';
        $xmldata = '<?xml version="1.0" encoding="'.EC_CHARSET.'"?><bcaster>';
        foreach ($flashdata as $data) {
            $xmldata .= '<item item_url="'.$data['src'].'" link="'.$data['url'].'" />';
        }
        $xmldata .= '</bcaster>';
        file_put_contents($data_file, $xmldata);

        return true;
    }

    /**
     *  获取用户自定义广告列表信息
     *
     * @return array
     */
    private function ad_list()
    {
        $result = MainHelper::get_filter();
        if ($result === false) {
            $aiax = isset($_GET['is_ajax']) ? $_GET['is_ajax'] : 0;
            $filter = [];
            $filter['sort_by'] = 'add_time';
            $filter['sort_order'] = 'DESC';

            // 过滤信息
            $where = 'WHERE 1 ';

            // 查询
            $query = DB::table('ad_custom')
                ->select(
                    'ad_id',
                    DB::raw("(CASE WHEN ad_type = 0 THEN '图片'
                                   WHEN ad_type = 1 THEN 'Flash'
                                   WHEN ad_type = 2 THEN '代码'
                                   WHEN ad_type = 3 THEN '文字'
                                   ELSE '' END) AS type_name"),
                    'ad_name',
                    'add_time',
                    DB::raw("(CASE WHEN ad_status = 1 THEN '启用' ELSE '关闭' END) AS status_name"),
                    'ad_type',
                    'ad_status'
                )
                ->orderBy($filter['sort_by'], $filter['sort_order']);

            $sql = $query->toRawSql();

            MainHelper::set_filter($filter, $sql);
        } else {
            $sql = $result['sql'];
            $filter = $result['filter'];
        }

        if (isset($query)) {
            $row = $query->get()->map(fn ($item) => (array) $item)->all();
        } else {
            $row = DB::select($sql);
            $row = array_map(fn ($item) => (array) $item, $row);
        }

        // 格式化数据
        foreach ($row as $key => $value) {
            $row[$key]['add_time'] = TimeHelper::local_date(cfg('time_format'), $value['add_time']);
        }

        $arr = ['ad' => $row, 'filter' => $filter];

        return $arr;
    }

    /**
     * 修改自定义相状态
     *
     * @param  int  $ad_id  自定义广告 id
     * @param  int  $ad_status  自定义广告 状态 0，关闭；1，开启。
     * @return bool
     */
    private function modfiy_ad_status($ad_id, $ad_status = 0)
    {
        $return = false;

        if (empty($ad_id)) {
            return $return;
        }

        // 查询自定义广告信息
        $ad = DB::table('ad_custom')
            ->select('ad_type', 'content', 'url', 'ad_status')
            ->where('ad_id', $ad_id)
            ->first();
        $ad = $ad ? (array) $ad : [];

        if ($ad_status === 1) {
            // 如果当前自定义广告是关闭状态 则修改其状态为启用
            if (isset($ad['ad_status']) && $ad['ad_status'] === 0) {
                DB::table('ad_custom')->where('ad_id', $ad_id)->update(['ad_status' => 1]);
            }

            // 关闭 其它自定义广告
            DB::table('ad_custom')->where('ad_id', '<>', $ad_id)->update(['ad_status' => 0]);

            // 用户自定义广告开启
            DB::table('shop_config')->where('id', 337)->update(['value' => 'cus']);
        } else {
            // 如果当前自定义广告是关闭状态 则检查是否存在启用的自定义广告
            // 如果无 则启用系统默认广告播放器
            if (isset($ad['ad_status']) && $ad['ad_status'] === 0) {
                $ad_status_1 = DB::table('ad_custom')->where('ad_status', 1)->count();
                if (empty($ad_status_1)) {
                    DB::table('shop_config')->where('id', 337)->update(['value' => 'sys']);
                } else {
                    DB::table('shop_config')->where('id', 337)->update(['value' => 'cus']);
                }
            } else {
                // 当前自定义广告是开启状态 关闭之
                // 如果无 则启用系统默认广告播放器
                DB::table('ad_custom')->where('ad_id', $ad_id)->update(['ad_status' => 0]);

                DB::table('shop_config')->where('id', 337)->update(['value' => 'sys']);
            }
        }

        return $return = true;
    }
}
