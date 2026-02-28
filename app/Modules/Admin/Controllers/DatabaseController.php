<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Helpers\BaseHelper;
use App\Helpers\CommonHelper;
use App\Modules\Admin\Helpers\MainHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DatabaseController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        /**
         * 数据库管理

        @ini_set('memory_limit', '1G');

        // 备份页面
        if ($action === 'backup') {
            $tables = DB::select("SHOW TABLES LIKE '".BaseHelper::mysql_like_quote(ecs()->prefix)."%'");
            $tables = array_map('current', array_map('get_object_vars', $tables));
            $allow_max_size = MainHelper::return_bytes(@ini_get('upload_max_filesize')); // 单位为字节
            $allow_max_size = $allow_max_size / 1024; // 转换单位为 KB

            // 权限检查
            $path = ROOT_PATH.DATA_DIR.'/sqldata';
            $mask = file_mode_info($path);
            if ($mask === false) {
                $warning = sprintf(lang('dir_not_exist'), $path);
                $this->assign('warning', $warning);
            } elseif ($mask != 15) {
                $warning = sprintf(lang('dir_priv'), $path).'<br/>';
                if (($mask & 1) < 1) {
                    $warning .= lang('cannot_read').'&nbsp;&nbsp;';
                }
                if (($mask & 2) < 1) {
                    $warning .= lang('cannot_write').'&nbsp;&nbsp;';
                }
                if (($mask & 4) < 1) {
                    $warning .= lang('cannot_add').'&nbsp;&nbsp;';
                }
                if (($mask & 8) < 1) {
                    $warning .= lang('cannot_modify');
                }
                $this->assign('warning', $warning);
            }

            $this->assign('action_link', ['text' => lang('restore'), 'href' => 'database.php?act=restore']);
            $this->assign('tables', $tables);
            $this->assign('vol_size', $allow_max_size);
            $this->assign('sql_name', cls_sql_dump::get_random_name().'.sql');
            $this->assign('ur_here', lang('02_db_manage'));

            return $this->display('db_backup');
        }

        // 备份恢复页面
        if ($action === 'restore') {
            // 权限判断
            $this->admin_priv('db_renew');

            $list = [];
            $path = ROOT_PATH.DATA_DIR.'/sqldata/';

            // 检查目录
            $mask = file_mode_info($path);
            if ($mask === false) {
                $warning = sprintf(lang('dir_not_exist'), $path);
                $this->assign('warning', $warning);
            } elseif (($mask & 1) < 1) {
                $warning = $path.'&nbsp;&nbsp;'.lang('cannot_read');
                $this->assign('warning', $warning);
            } else {
                // 获取文件列表
                $real_list = [];
                $folder = opendir($path);
                while ($file = readdir($folder)) {
                    if (strpos($file, '.sql') !== false) {
                        $real_list[] = $file;
                    }
                }
                natsort($real_list);

                $match = [];
                foreach ($real_list as $file) {
                    if (preg_match('/_([0-9])+\.sql$/', $file, $match)) {
                        if ($match[1] === 1) {
                            $mark = 1;
                        } else {
                            $mark = 2;
                        }
                    } else {
                        $mark = 0;
                    }

                    $file_size = filesize($path.$file);
                    $info = cls_sql_dump::get_head($path.$file);
                    $list[] = ['name' => $file, 'ver' => $info['ecs_ver'], 'add_time' => $info['date'], 'vol' => $info['vol'], 'file_size' => $this->num_bitunit($file_size), 'mark' => $mark];
                }
            }

            $this->assign('action_link', ['text' => lang('02_db_manage'), 'href' => 'database.php?act=backup']);
            $this->assign('ur_here', lang('restore'));
            $this->assign('list', $list);

            return $this->display('db_restore');
        }

        if ($action === 'dumpsql') {
            // 权限判断
            $token = trim($_REQUEST['token']);
            if ($token != cfg('token')) {
                return $this->sys_msg(lang('backup_failure'), 1);
            }
            $this->admin_priv('db_backup');

            // 检查目录权限
            $path = ROOT_PATH.DATA_DIR.'/sqldata';
            $mask = file_mode_info($path);
            if ($mask === false) {
                $warning = sprintf(lang('dir_not_exist'), $path);

                return $this->sys_msg($warning, 1);
            } elseif ($mask != 15) {
                $warning = sprintf(lang('dir_priv'), $path);
                if (($mask & 1) < 1) {
                    $warning .= lang('cannot_read');
                }
                if (($mask & 2) < 1) {
                    $warning .= lang('cannot_write');
                }
                if (($mask & 4) < 1) {
                    $warning .= lang('cannot_add');
                }
                if (($mask & 8) < 1) {
                    $warning .= lang('cannot_modify');
                }

                return $this->sys_msg($warning, 1);
            }

            // 设置最长执行时间为5分钟
            @set_time_limit(300);

            // 初始化
            $dump = new cls_sql_dump($db);
            $run_log = ROOT_PATH.DATA_DIR.'/sqldata/run.log';

            // 初始化输入变量
            if (empty($_REQUEST['sql_file_name'])) {
                $sql_file_name = $dump->get_random_name();
            } else {
                $sql_file_name = str_replace('0xa', '', trim($_REQUEST['sql_file_name'])); // 过滤 0xa 非法字符
                $pos = strpos($sql_file_name, '.sql');
                if ($pos !== false) {
                    $sql_file_name = substr($sql_file_name, 0, $pos);
                }
            }

            $max_size = empty($_REQUEST['vol_size']) ? 0 : intval($_REQUEST['vol_size']);
            $vol = empty($_REQUEST['vol']) ? 1 : intval($_REQUEST['vol']);
            $is_short = empty($_REQUEST['ext_insert']) ? false : true;

            $dump->is_short = $is_short;

            // 变量验证
            $allow_max_size = intval(@ini_get('upload_max_filesize')); // 单位M
            if ($allow_max_size > 0 && $max_size > ($allow_max_size * 1024)) {
                $max_size = $allow_max_size * 1024; // 单位K
            }

            if ($max_size > 0) {
                $dump->max_size = $max_size * 1024;
            }

            // 获取要备份数据列表
            $type = empty($_POST['type']) ? '' : trim($_POST['type']);
            $tables = [];

            switch ($type) {
                case 'full':
                    $except = [ecs()->prefix.'sessions', ecs()->prefix.'sessions_data'];
                    $temp = DB::select("SHOW TABLES LIKE '".BaseHelper::mysql_like_quote(ecs()->prefix)."%'");
                    $temp = array_map('current', array_map('get_object_vars', $temp));
                    foreach ($temp as $table) {
                        if (in_array($table, $except)) {
                            continue;
                        }
                        $tables[$table] = -1;
                    }

                    $dump->put_tables_list($run_log, $tables);
                    break;

                case 'stand':
                    $temp = ['admin_user', 'area_region', 'article', 'article_cat', 'attribute', 'brand', 'cart', 'category', 'comment', 'goods', 'goods_attr', 'goods_cat', 'goods_gallery', 'goods_type', 'group_goods', 'link_goods', 'member_price', 'order_action', 'order_goods', 'order_info', 'payment', 'region', 'shipping', 'shipping_area', 'shop_config', 'user_address', 'user_bonus', 'user_rank', 'users', 'virtual_card'];
                    foreach ($temp as $table) {
                        $tables[ecs()->prefix.$table] = -1;
                    }
                    $dump->put_tables_list($run_log, $tables);
                    break;

                case 'min':
                    $temp = ['attribute', 'brand', 'cart', 'category', 'goods', 'goods_attr', 'goods_cat', 'goods_gallery', 'goods_type', 'group_goods', 'link_goods', 'member_price', 'order_action', 'order_goods', 'order_info', 'shop_config', 'user_address', 'user_bonus', 'user_rank', 'users', 'virtual_card'];
                    foreach ($temp as $table) {
                        $tables[ecs()->prefix.$table] = -1;
                    }
                    $dump->put_tables_list($run_log, $tables);
                    break;
                case 'custom':
                    foreach ($_POST['customtables'] as $table) {
                        $tables[$table] = -1;
                    }
                    $dump->put_tables_list($run_log, $tables);
                    break;
            }

            // 开始备份
            $tables = $dump->dump_table($run_log, $vol);

            if ($tables === false) {
                exit($dump->errorMsg());
            }

            if (empty($tables)) {
                // 备份结束
                if ($vol > 1) {
                    // 有多个文件
                    if (! @file_put_contents(ROOT_PATH.DATA_DIR.'/sqldata/'.$sql_file_name.'_'.$vol.'.sql', $dump->dump_sql)) {
                        return $this->sys_msg(sprintf(lang('fail_write_file'), $sql_file_name.'_'.$vol.'.sql'), 1, [['text' => lang('02_db_manage'), 'href' => 'database.php?act=backup']], false);
                    }
                    $list = [];
                    for ($i = 1; $i <= $vol; $i++) {
                        $list[] = ['name' => $sql_file_name.'_'.$i.'.sql', 'href' => '../'.DATA_DIR.'/sqldata/'.$sql_file_name.'_'.$i.'.sql'];
                    }

                    $this->assign('list', $list);
                    $this->assign('title', lang('backup_success'));

                    return $this->display('sql_dump_msg');
                } else {
                    // 只有一个文件
                    if (! @file_put_contents(ROOT_PATH.DATA_DIR.'/sqldata/'.$sql_file_name.'.sql', $dump->dump_sql)) {
                        return $this->sys_msg(sprintf(lang('fail_write_file'), $sql_file_name.'_'.$vol.'.sql'), 1, [['text' => lang('02_db_manage'), 'href' => 'database.php?act=backup']], false);
                    }

                    $this->assign('list', [['name' => $sql_file_name.'.sql', 'href' => '../'.DATA_DIR.'/sqldata/'.$sql_file_name.'.sql']]);
                    $this->assign('title', lang('backup_success'));

                    return $this->display('sql_dump_msg');
                }
            } else {
                // 下一个页面处理
                if (! @file_put_contents(ROOT_PATH.DATA_DIR.'/sqldata/'.$sql_file_name.'_'.$vol.'.sql', $dump->dump_sql)) {
                    return $this->sys_msg(sprintf(lang('fail_write_file'), $sql_file_name.'_'.$vol.'.sql'), 1, [['text' => lang('02_db_manage'), 'href' => 'database.php?act=backup']], false);
                }

                $lnk = 'database.php?act=dumpsql&token='.cfg('token').'&sql_file_name='.$sql_file_name.'&vol_size='.$max_size.'&vol='.($vol + 1);
                $this->assign('title', sprintf(lang('backup_title'), '#'.$vol));
                $this->assign('auto_redirect', 1);
                $this->assign('auto_link', $lnk);

                return $this->display('sql_dump_msg');
            }
        }

        // 删除备份
        if ($action === 'remove') {
            // 权限判断
            $this->admin_priv('db_backup');

            if (isset($_POST['file'])) {
                $m_file = []; // 多卷文件
                $s_file = []; // 单卷文件

                $path = ROOT_PATH.DATA_DIR.'/sqldata/';

                foreach ($_POST['file'] as $file) {
                    if (preg_match('/_[0-9]+\.sql$/', $file)) {
                        $m_file[] = substr($file, 0, strrpos($file, '_'));
                    } else {
                        $s_file[] = $file;
                    }
                }

                if ($m_file) {
                    $m_file = array_unique($m_file);

                    // 获取文件列表
                    $real_file = [];

                    $folder = opendir($path);
                    while ($file = readdir($folder)) {
                        if (preg_match('/_[0-9]+\.sql$/', $file) && is_file($path.$file)) {
                            $real_file[] = $file;
                        }
                    }

                    foreach ($real_file as $file) {
                        $short_file = substr($file, 0, strrpos($file, '_'));
                        if (in_array($short_file, $m_file)) {
                            @unlink($path.$file);
                        }
                    }
                }

                if ($s_file) {
                    foreach ($s_file as $file) {
                        @unlink($path.$file);
                    }
                }
            }

            return $this->sys_msg(lang('remove_success'), 0, [['text' => lang('restore'), 'href' => 'database.php?act=restore']]);
        }

        // 从服务器上导入数据
        if ($action === 'import') {
            // 权限判断
            $this->admin_priv('db_renew');

            $is_confirm = empty($_GET['confirm']) ? false : true;
            $file_name = empty($_GET['file_name']) ? '' : trim($_GET['file_name']);
            $path = ROOT_PATH.DATA_DIR.'/sqldata/';

            // 设置最长执行时间为5分钟
            @set_time_limit(300);

            if (preg_match('/_[0-9]+\.sql$/', $file_name)) {
                // 多卷处理
                if ($is_confirm === false) {
                    // 提示用户要求确认
                    return $this->sys_msg(lang('confirm_import'), 1, [['text' => lang('also_continue'), 'href' => 'database.php?act=import&confirm=1&file_name='.$file_name]], false);
                }

                $short_name = substr($file_name, 0, strrpos($file_name, '_'));

                // 获取文件列表
                $real_file = [];
                $folder = opendir($path);
                while ($file = readdir($folder)) {
                    if (is_file($path.$file) && preg_match('/_[0-9]+\.sql$/', $file)) {
                        $real_file[] = $file;
                    }
                }

                // 所有相同分卷数据列表
                $post_list = [];
                foreach ($real_file as $file) {
                    $tmp_name = substr($file, 0, strrpos($file, '_'));
                    if ($tmp_name === $short_name) {
                        $post_list[] = $file;
                    }
                }

                natsort($post_list);

                // 开始恢复数据
                foreach ($post_list as $file) {
                    $info = cls_sql_dump::get_head($path.$file);
                    if ($info['ecs_ver'] != VERSION) {
                        return $this->sys_msg(sprintf(lang('version_error'), VERSION, $sql_info['ecs_ver']));
                    }
                    if (! $this->sql_import($path.$file)) {
                        return $this->sys_msg(lang('sqlfile_error'), 1);
                    }
                }

                $this->clear_cache_files();

                return $this->sys_msg(lang('restore_success'), 0, [['text' => lang('restore'), 'href' => 'database.php?act=restore']]);
            } else {
                // 单卷
                $info = cls_sql_dump::get_head($path.$file_name);
                if ($info['ecs_ver'] != VERSION) {
                    return $this->sys_msg(sprintf(lang('version_error'), VERSION, $sql_info['ecs_ver']));
                }
                if (sql_import($path.$file_name)) {
                    $this->clear_cache_files();
                    $this->admin_log(lang('backup_time').$info['date'], 'restore', 'db_backup');

                    return $this->sys_msg(lang('restore_success'), 0, [['text' => lang('restore'), 'href' => 'database.php?act=restore']]);
                } else {
                    return $this->sys_msg(lang('sqlfile_error'), 1);
                }
            }
        }

        /**
         * 上传sql 文件
         */
        if ($action === 'upload_sql') {
            // 权限判断
            $this->admin_priv('db_renew');

            $sql_file = ROOT_PATH.DATA_DIR.'/upload_database_bak.sql';

            if (empty($_GET['mysql_ver_confirm'])) {
                if (empty($_FILES['sqlfile'])) {
                    return $this->sys_msg(lang('empty_upload'), 1);
                }

                $file = $_FILES['sqlfile'];

                // 检查上传是否成功
                if ((isset($file['error']) && $file['error'] > 0) || (! isset($file['error']) && $file['tmp_name'] === 'none')) {
                    return $this->sys_msg(lang('fail_upload'), 1);
                }

                // 检查文件格式
                if ($file['type'] === 'application/x-zip-compressed') {
                    return $this->sys_msg(lang('not_support_zip_format'), 1);
                }

                if (! preg_match("/\.sql$/i", $file['name'])) {
                    return $this->sys_msg(lang('not_sql_file'), 1);
                }

                // 将文件移动到临时目录，避免权限问题
                @unlink($sql_file);
                if (! BaseHelper::move_upload_file($file['tmp_name'], $sql_file)) {
                    return $this->sys_msg(lang('fail_upload_move'), 1);
                }
            }

            // 获取sql文件头部信息
            $sql_info = cls_sql_dump::get_head($sql_file);

            // 如果备份文件的商场系统与现有商城系统版本不同则拒绝执行
            if (empty($sql_info['ecs_ver'])) {
                return $this->sys_msg(lang('unrecognize_version'), 1);
            } else {
                if ($sql_info['ecs_ver'] != VERSION) {
                    return $this->sys_msg(sprintf(lang('version_error'), VERSION, $sql_info['ecs_ver']));
                }
            }

            // 检查数据库版本是否正确
            if (empty($_GET['mysql_ver_confirm'])) {
                if (empty($sql_info['mysql_ver'])) {
                    return $this->sys_msg(lang('unrecognize_mysql_version'));
                } else {
                    $mysql_ver_arr = DB::getPdo()->getAttribute(\PDO::ATTR_SERVER_VERSION);
                    if ($sql_info['mysql_ver'] != $mysql_ver_arr) {
                        $lnk = [];
                        $lnk[] = ['text' => lang('confirm_ver'), 'href' => 'database.php?act=upload_sql&mysql_ver_confirm=1'];
                        $lnk[] = ['text' => lang('unconfirm_ver'), 'href' => 'database.php?act=restore'];

                        return $this->sys_msg(sprintf(lang('mysql_version_error'), $mysql_ver_arr, $sql_info['mysql_ver']), 0, $lnk, false);
                    }
                }
            }

            // 设置最长执行时间为5分钟
            @set_time_limit(300);

            if (sql_import($sql_file)) {
                CommonHelper::clear_all_files();
                @unlink($sql_file);

                return $this->sys_msg(lang('restore_success'), 0, []);
            } else {
                @unlink($sql_file);

                return $this->sys_msg(lang('sqlfile_error'), 1);
            }
        }

        /**
         * 优化页面
         */
        if ($action === 'optimize') {
            // 初始化数据
            $this->admin_priv('db_backup');
            $ret = DB::select("SHOW TABLE STATUS LIKE '".BaseHelper::mysql_like_quote(ecs()->prefix)."%'");

            $num = 0;
            $list = [];
            foreach ($ret as $row) {
                $row = (array) $row;
                if (strpos($row['Name'], '_session') !== false) {
                    $res['Msg_text'] = 'Ignore';
                    $row['Data_free'] = 'Ignore';
                } else {
                    $res = DB::selectOne('CHECK TABLE '.$row['Name']);
                    $res = (array) $res;
                    $num += $row['Data_free'];
                }
                $type = $row['Engine'];
                $charset = $row['Collation'];
                $list[] = ['table' => $row['Name'], 'type' => $type, 'rec_num' => $row['Rows'], 'rec_size' => sprintf(' %.2f KB', $row['Data_length'] / 1024), 'rec_index' => $row['Index_length'], 'rec_chip' => $row['Data_free'], 'status' => $res['Msg_text'], 'charset' => $charset];
            }
            unset($ret);
            // 赋值

            $this->assign('list', $list);
            $this->assign('num', $num);
            $this->assign('ur_here', lang('03_db_optimize'));

            return $this->display('optimize');
        }

        if ($action === 'run_optimize') {
            $this->admin_priv('db_backup');
            $tables = DB::select("SHOW TABLES LIKE '".BaseHelper::mysql_like_quote(ecs()->prefix)."%'");
            $tables = array_map('current', array_map('get_object_vars', $tables));
            foreach ($tables as $table) {
                if ($row = DB::selectOne('OPTIMIZE TABLE '.$table)) {
                    $row = (array) $row;
                    // 优化出错，尝试修复
                    if ($row['Msg_type'] === 'error' && strpos($row['Msg_text'], 'repair') !== false) {
                        DB::statement('REPAIR TABLE '.$table);
                    }
                }
            }

            return $this->sys_msg(sprintf(lang('optimize_ok'), $_POST['num']), 0, [['text' => lang('go_back'), 'href' => 'database.php?act=optimize']]);
        }
    }

    /**
     * @return void
     */
    private function sql_import($sql_file)
    {
        $sql_str = array_filter(file($sql_file), 'remove_comment');
        $sql_str = str_replace("\r", '', implode('', $sql_str));

        $ret = explode(";\n", $sql_str);
        $ret_count = count($ret);

        // 执行sql语句
        for ($i = 0; $i < $ret_count; $i++) {
            $ret[$i] = trim($ret[$i], " \r\n;"); // 剔除多余信息
            if (! empty($ret[$i])) {
                if ((strpos($ret[$i], 'CREATE TABLE') !== false) && (strpos($ret[$i], 'DEFAULT CHARSET='.str_replace('-', '', EC_CHARSET)) === false)) {
                    // 建表时缺 DEFAULT CHARSET=utf8
                    $ret[$i] = $ret[$i].'DEFAULT CHARSET='.str_replace('-', '', EC_CHARSET);
                }
                DB::statement($ret[$i]);
            }
        }

        return true;
    }

    /**
     * 将字节转成可阅读格式
     *
     *
     * @return void
     */
    private function num_bitunit($num)
    {
        $bitunit = [' B', ' KB', ' MB', ' GB'];
        for ($key = 0, $count = count($bitunit); $key < $count; $key++) {
            if ($num >= pow(2, 10 * $key) - 1) { // 1024B 会显示为 1KB
                $num_bitunit_str = (ceil($num / pow(2, 10 * $key) * 100) / 100)." $bitunit[$key]";
            }
        }

        return $num_bitunit_str;
    }

    /**
     * @return void
     */
    private function remove_comment($var)
    {
        return substr($var, 0, 2) != '--';
    }
}
