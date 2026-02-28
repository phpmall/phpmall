<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Helpers\BaseHelper;
use App\Modules\Admin\Helpers\MainHelper;
use App\Modules\Admin\Libraries\Exchange;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShippingController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        $exc = new Exchange(ecs()->table('shipping'), db(), 'shipping_code', 'shipping_name');

        /**
         * 配送方式列表
         */
        if ($action === 'list') {
            $modules = MainHelper::read_modules(ROOT_PATH.'includes/modules/shipping');

            for ($i = 0; $i < count($modules); $i++) {
                $lang_file = ROOT_PATH.'languages/'.cfg('lang').'/shipping/'.$modules[$i]['code'].'.php';

                if (file_exists($lang_file)) {
                    // include_once $lang_file;
                }

                // 检查该插件是否已经安装
                $row = (array) DB::table('shipping')
                    ->where('shipping_code', $modules[$i]['code'])
                    ->orderBy('shipping_order')
                    ->select('shipping_id', 'shipping_name', 'shipping_desc', 'insure', 'support_cod', 'shipping_order')
                    ->first();

                if ($row) {
                    // 插件已经安装了，获得名称以及描述
                    $modules[$i]['id'] = $row['shipping_id'];
                    $modules[$i]['name'] = $row['shipping_name'];
                    $modules[$i]['desc'] = $row['shipping_desc'];
                    $modules[$i]['insure_fee'] = $row['insure'];
                    $modules[$i]['cod'] = $row['support_cod'];
                    $modules[$i]['shipping_order'] = $row['shipping_order'];
                    $modules[$i]['install'] = 1;

                    if (isset($modules[$i]['insure']) && ($modules[$i]['insure'] === false)) {
                        $modules[$i]['is_insure'] = 0;
                    } else {
                        $modules[$i]['is_insure'] = 1;
                    }
                } else {
                    $modules[$i]['name'] = $_LANG[$modules[$i]['code']];
                    $modules[$i]['desc'] = $_LANG[$modules[$i]['desc']];
                    $modules[$i]['insure_fee'] = empty($modules[$i]['insure']) ? 0 : $modules[$i]['insure'];
                    $modules[$i]['cod'] = $modules[$i]['cod'];
                    $modules[$i]['install'] = 0;
                }
            }

            $this->assign('ur_here', lang('03_shipping_list'));
            $this->assign('modules', $modules);

            return $this->display('shipping_list');
        }

        /**
         * 安装配送方式
         */
        if ($action === 'install') {
            $this->admin_priv('ship_manage');

            $set_modules = true;
            // include_once ROOT_PATH.'includes/modules/shipping/'.$_GET['code'].'.php';

            // 检查该配送方式是否已经安装
            $id = DB::table('shipping')->where('shipping_code', $_GET['code'])->value('shipping_id');

            if ($id > 0) {
                // 该配送方式已经安装过, 将该配送方式的状态设置为 enable
                DB::table('shipping')->where('shipping_code', $_GET['code'])->limit(1)->update(['enabled' => 1]);
            } else {
                // 该配送方式没有安装过, 将该配送方式的信息添加到数据库
                $insure = empty($modules[0]['insure']) ? 0 : $modules[0]['insure'];
                $id = DB::table('shipping')->insertGetId([
                    'shipping_code' => addslashes($modules[0]['code']),
                    'shipping_name' => addslashes($_LANG[$modules[0]['code']]),
                    'shipping_desc' => addslashes($_LANG[$modules[0]['desc']]),
                    'insure' => $insure,
                    'support_cod' => intval($modules[0]['cod']),
                    'enabled' => 1,
                    'print_bg' => addslashes($modules[0]['print_bg']),
                    'config_lable' => addslashes($modules[0]['config_lable']),
                    'print_model' => $modules[0]['print_model'],
                ]);
            }

            // 记录管理员操作
            $this->admin_log(addslashes($_LANG[$modules[0]['code']]), 'install', 'shipping');

            // 提示信息
            $lnk[] = ['text' => lang('add_shipping_area'), 'href' => 'shipping_area.php?act=add&shipping='.$id];
            $lnk[] = ['text' => lang('go_back'), 'href' => 'shipping.php?act=list'];

            return $this->sys_msg(sprintf(lang('install_succeess'), $_LANG[$modules[0]['code']]), 0, $lnk);
        }

        /**
         * 卸载配送方式
         */
        if ($action === 'uninstall') {
            $this->admin_priv('ship_manage');

            // 获得该配送方式的ID
            $row = (array) DB::table('shipping')->where('shipping_code', $_GET['code'])->select('shipping_id', 'shipping_name', 'print_bg')->first();
            $shipping_id = $row['shipping_id'];
            $shipping_name = $row['shipping_name'];

            // 删除 shipping_fee 以及 shipping 表中的数据
            if ($row) {
                $all = DB::table('shipping_area')->where('shipping_id', $shipping_id)->pluck('shipping_area_id')->all();
                DB::table('shipping_area_region')->whereIn('shipping_area_id', $all)->delete();
                DB::table('shipping_area')->where('shipping_id', $shipping_id)->delete();
                DB::table('shipping')->where('shipping_id', $shipping_id)->delete();

                // 删除上传的非默认快递单
                if (($row['print_bg'] != '') && (! $this->is_print_bg_default($row['print_bg']))) {
                    @unlink(ROOT_PATH.$row['print_bg']);
                }

                // 记录管理员操作
                $this->admin_log(addslashes($shipping_name), 'uninstall', 'shipping');

                $lnk[] = ['text' => lang('go_back'), 'href' => 'shipping.php?act=list'];

                return $this->sys_msg(sprintf(lang('uninstall_success'), $shipping_name), 0, $lnk);
            }
        }

        /**
         * 模板Flash编辑器
         */
        if ($action === 'print_index') {
            // 检查登录权限
            $this->admin_priv('ship_manage');

            $shipping_id = ! empty($_GET['shipping']) ? intval($_GET['shipping']) : 0;

            // 检查该插件是否已经安装 取值
            $row = (array) DB::table('shipping')->where('shipping_id', $shipping_id)->limit(1)->first();
            if ($row) {
                // include_once ROOT_PATH.'includes/modules/shipping/'.$row['shipping_code'].'.php';
                $row['shipping_print'] = ! empty($row['shipping_print']) ? $row['shipping_print'] : '';
                $row['print_bg'] = empty($row['print_bg']) ? '' : $this->get_site_root_url().$row['print_bg'];
            }
            $this->assign('shipping', $row);
            $this->assign('shipping_id', $shipping_id);

            return $this->display('print_index');
        }

        /**
         * 模板Flash编辑器
         */
        if ($action === 'recovery_default_template') {
            // 检查登录权限
            $this->admin_priv('ship_manage');

            $shipping_id = ! empty($_POST['shipping']) ? intval($_POST['shipping']) : 0;

            // 取配送代码
            $code = DB::table('shipping')->where('shipping_id', $shipping_id)->value('shipping_code');

            $set_modules = true;
            // include_once ROOT_PATH.'includes/modules/shipping/'.$code.'.php';

            // 恢复默认
            DB::table('shipping')->where('shipping_code', $code)->limit(1)->update([
                'print_bg' => addslashes($modules[0]['print_bg']),
                'config_lable' => addslashes($modules[0]['config_lable']),
            ]);

            $url = "shipping.php?act=edit_print_template&shipping=$shipping_id";

            return response()->redirectTo($url);
        }

        /**
         * 模板Flash编辑器 上传图片
         */
        if ($action === 'print_upload') {
            // 检查登录权限
            $this->admin_priv('ship_manage');

            // 设置上传文件类型
            $allow_suffix = ['jpg', 'png', 'jpeg'];

            $shipping_id = ! empty($_POST['shipping']) ? intval($_POST['shipping']) : 0;

            // 接收上传文件
            if (! empty($_FILES['bg']['name'])) {
                if (! BaseHelper::get_file_suffix($_FILES['bg']['name'], $allow_suffix)) {
                    echo '<script type="text/javascript">';
                    echo 'parent.alert("'.sprintf(lang('js_languages.upload_falid'), implode('，', $allow_suffix)).'");';
                    echo '</script>';
                    exit;
                }

                $name = date('Ymd');
                for ($i = 0; $i < 6; $i++) {
                    $name .= chr(mt_rand(97, 122));
                }
                $bg_name_arr = explode('.', $_FILES['bg']['name']);
                $name .= '.'.end($bg_name_arr);
                $target = ROOT_PATH.'/images/receipt/'.$name;

                if (BaseHelper::move_upload_file($_FILES['bg']['tmp_name'], $target)) {
                    $src = '/images/receipt/'.$name;
                }
            }

            // 保存
            $res = DB::table('shipping')->where('shipping_id', $shipping_id)->update(['print_bg' => $src]);
            if ($res) {
                echo '<script type="text/javascript">';
                echo 'parent.call_flash("bg_add", "'.$this->get_site_root_url().$src.'");';
                echo '</script>';
            }
        }

        /**
         * 模板Flash编辑器 删除图片
         */
        if ($action === 'print_del') {
            $this->check_authz_json('ship_manage');

            $shipping_id = ! empty($_GET['shipping']) ? intval($_GET['shipping']) : 0;
            $shipping_id = BaseHelper::json_str_iconv($shipping_id);

            // 检查该插件是否已经安装 取值
            $row = (array) DB::table('shipping')->where('shipping_id', $shipping_id)->select('print_bg')->limit(1)->first();
            if ($row) {
                if (($row['print_bg'] != '') && (! $this->is_print_bg_default($row['print_bg']))) {
                    @unlink(ROOT_PATH.$row['print_bg']);
                }

                DB::table('shipping')->where('shipping_id', $shipping_id)->update(['print_bg' => '']);
            } else {
                return $this->make_json_error(lang('js_languages.upload_del_falid'));
            }

            return $this->make_json_result($shipping_id);
        }

        /**
         * 编辑打印模板
         */
        if ($action === 'edit_print_template') {
            $this->admin_priv('ship_manage');

            $shipping_id = ! empty($_GET['shipping']) ? intval($_GET['shipping']) : 0;

            // 检查该插件是否已经安装
            $row = (array) DB::table('shipping')->where('shipping_id', $shipping_id)->first();
            if ($row) {
                // include_once ROOT_PATH.'includes/modules/shipping/'.$row['shipping_code'].'.php';
                $row['shipping_print'] = ! empty($row['shipping_print']) ? $row['shipping_print'] : '';
                $row['print_model'] = empty($row['print_model']) ? 1 : $row['print_model']; // 兼容以前版本

                $this->assign('shipping', $row);
            } else {
                $lnk[] = ['text' => lang('go_back'), 'href' => 'shipping.php?act=list'];

                return $this->sys_msg(lang('no_shipping_install'), 0, $lnk);
            }

            $this->assign('ur_here', lang('03_shipping_list').' - '.$row['shipping_name'].' - '.lang('shipping_print_template'));
            $this->assign('action_link', ['text' => lang('03_shipping_list'), 'href' => 'shipping.php?act=list']);
            $this->assign('shipping_id', $shipping_id);

            return $this->display('shipping_template');
        }

        /**
         * 编辑打印模板
         */
        if ($action === 'do_edit_print_template') {
            $this->admin_priv('ship_manage');

            // 参数处理
            $print_model = ! empty($_POST['print_model']) ? intval($_POST['print_model']) : 0;
            $shipping_id = ! empty($_REQUEST['shipping']) ? intval($_REQUEST['shipping']) : 0;

            // 处理不同模式编辑的表单
            if ($print_model === 2) {
                DB::table('shipping')->where('shipping_id', $shipping_id)->update([
                    'config_lable' => $_POST['config_lable'],
                    'print_model' => $print_model,
                ]);
            } elseif ($print_model === 1) {
                $template = ! empty($_POST['shipping_print']) ? $_POST['shipping_print'] : '';
                DB::table('shipping')->where('shipping_id', $shipping_id)->update([
                    'shipping_print' => $template,
                    'print_model' => $print_model,
                ]);
            }

            // 记录管理员操作
            $this->admin_log(addslashes($_POST['shipping_name']), 'edit', 'shipping');

            $lnk[] = ['text' => lang('go_back'), 'href' => 'shipping.php?act=list'];

            return $this->sys_msg(lang('edit_template_success'), 0, $lnk);
        }

        /**
         * 编辑配送方式名称
         */
        if ($action === 'edit_name') {
            $this->check_authz_json('ship_manage');

            // 取得参数
            $id = BaseHelper::json_str_iconv(trim($_POST['id']));
            $val = BaseHelper::json_str_iconv(trim($_POST['val']));

            // 检查名称是否为空
            if (empty($val)) {
                return $this->make_json_error(lang('no_shipping_name'));
            }

            // 检查名称是否重复
            if (! $exc->is_only('shipping_name', $val, $id)) {
                return $this->make_json_error(lang('repeat_shipping_name'));
            }

            // 更新支付方式名称
            $exc->edit("shipping_name = '$val'", $id);

            return $this->make_json_result(stripcslashes($val));
        }

        /**
         * 编辑配送方式描述
         */
        if ($action === 'edit_desc') {
            $this->check_authz_json('ship_manage');

            // 取得参数
            $id = BaseHelper::json_str_iconv(trim($_POST['id']));
            $val = BaseHelper::json_str_iconv(trim($_POST['val']));

            // 更新描述
            $exc->edit("shipping_desc = '$val'", $id);

            return $this->make_json_result(stripcslashes($val));
        }

        /**
         * 修改配送方式保价费
         */
        if ($action === 'edit_insure') {
            $this->check_authz_json('ship_manage');

            // 取得参数
            $id = BaseHelper::json_str_iconv(trim($_POST['id']));
            $val = BaseHelper::json_str_iconv(trim($_POST['val']));
            if (empty($val)) {
                $val = 0;
            } else {
                $val = BaseHelper::make_semiangle($val); // 全角转半角
                if (strpos($val, '%') === false) {
                    $val = floatval($val);
                } else {
                    $val = floatval($val).'%';
                }
            }

            // 检查该插件是否支持保价
            $set_modules = true;
            // include_once ROOT_PATH.'includes/modules/shipping/'.$id.'.php';
            if (isset($modules[0]['insure']) && $modules[0]['insure'] === false) {
                return $this->make_json_error(lang('not_support_insure'));
            }

            // 更新保价费用
            $exc->edit("insure = '$val'", $id);

            return $this->make_json_result(stripcslashes($val));
        }

        if ($action === 'shipping_priv') {
            $this->check_authz_json('ship_manage');

            return $this->make_json_result('');
        }
        /**
         * 修改配送方式排序
         */
        if ($action === 'edit_order') {
            $this->check_authz_json('ship_manage');

            // 取得参数
            $code = BaseHelper::json_str_iconv(trim($_POST['id']));
            $order = intval($_POST['val']);

            // 更新排序
            $exc->edit("shipping_order = '$order'", $code);

            return $this->make_json_result(stripcslashes($order));
        }
    }

    /**
     * 获取站点根目录网址
     */
    private function get_site_root_url(): string
    {
        return 'http://'.$_SERVER['HTTP_HOST'].str_replace('/'.ADMIN_PATH.'/shipping.php', '', PHP_SELF);
    }

    /**
     * 判断是否为默认安装快递单背景图片
     *
     * @param  string  $print_bg  快递单背景图片路径名
     * @return bool
     */
    private function is_print_bg_default($print_bg)
    {
        $_bg = basename($print_bg);

        $_bg_array = explode('.', $_bg);

        if (count($_bg_array) != 2) {
            return false;
        }

        if (strpos('|'.$_bg_array[0], 'dly_') != 1) {
            return false;
        }

        $_bg_array[0] = ltrim($_bg_array[0], 'dly_');
        $list = explode('|', SHIP_LIST);

        if (in_array($_bg_array[0], $list)) {
            return true;
        }

        return false;
    }
}
