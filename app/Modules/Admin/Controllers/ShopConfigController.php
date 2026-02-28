<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Helpers\BaseHelper;
use App\Helpers\CommonHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShopConfigController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        // 代码
        /**
         * 列表编辑 ?act=list_edit
         */
        if ($action === 'list_edit') {
            $this->admin_priv('shop_config');

            // 可选语言
            $dir = opendir(ROOT_PATH.'languages');
            $lang_list = [];
            while (@$file = readdir($dir)) {
                if ($file != '.' && $file != '..' && $file != '.svn' && $file != '_svn' && is_dir(ROOT_PATH.'languages/'.$file)) {
                    $lang_list[] = $file;
                }
            }
            @closedir($dir);

            $this->assign('lang_list', $lang_list);
            $this->assign('ur_here', lang('01_shop_config'));
            $this->assign('group_list', $this->get_settings(null, ['5']));
            $this->assign('countries', CommonHelper::get_regions());

            if (strpos(strtolower($_SERVER['SERVER_SOFTWARE']), 'iis') !== false) {
                $rewrite_confirm = lang('rewrite_confirm_iis');
            } else {
                $rewrite_confirm = lang('rewrite_confirm_apache');
            }
            $this->assign('rewrite_confirm', $rewrite_confirm);

            if (cfg('shop_country') > 0) {
                $this->assign('provinces', CommonHelper::get_regions(1, cfg('shop_country')));
                if (cfg('shop_province')) {
                    $this->assign('cities', CommonHelper::get_regions(2, cfg('shop_province')));
                }
            }
            $this->assign('cfg', cfg());

            return $this->display('shop_config');
        }

        /**
         * 邮件服务器设置
         */
        if ($action === 'mail_settings') {
            $this->admin_priv('shop_config');

            $arr = $this->get_settings([5]);

            $this->assign('ur_here', lang('mail_settings'));
            $this->assign('cfg', $arr[5]['vars']);

            return $this->display('shop_config_mail_settings');
        }

        /**
         * 提交   ?act=post
         */
        if ($action === 'post') {
            $type = empty($_POST['type']) ? '' : $_POST['type'];

            $this->admin_priv('shop_config');

            // 允许上传的文件类型
            $allow_file_types = '|GIF|JPG|PNG|BMP|SWF|DOC|XLS|PPT|MID|WAV|ZIP|RAR|PDF|CHM|RM|TXT|CERT|';

            // 保存变量值
            $count = count($_POST['value']);

            $arr = DB::table('shop_config')
                ->select('id', 'value')
                ->get()
                ->pluck('value', 'id')
                ->all();
            foreach ($_POST['value'] as $key => $val) {
                if ($arr[$key] != $val) {
                    DB::table('shop_config')
                        ->where('id', $key)
                        ->update(['value' => trim($val)]);
                }
            }

            // 处理上传文件
            $file_var_list = [];
            $res = DB::table('shop_config')
                ->where('parent_id', '>', 0)
                ->where('type', 'file')
                ->get();
            foreach ($res as $row) {
                $row = (array) $row;
                $file_var_list[$row['code']] = $row;
            }

            foreach ($_FILES as $code => $file) {
                // 判断用户是否选择了文件
                if ((isset($file['error']) && $file['error'] === 0) || (! isset($file['error']) && $file['tmp_name'] != 'none')) {
                    // 检查上传的文件类型是否合法
                    if (! BaseHelper::check_file_type($file['tmp_name'], $file['name'], $allow_file_types)) {
                        return $this->sys_msg(sprintf(lang('msg_invalid_file'), $file['name']));
                    } else {
                        if ($code === 'shop_logo') {
                            $info = get_template_info(cfg('template'));

                            $file_name = str_replace('{$template}', cfg('template'), $file_var_list[$code]['store_dir']).$info['logo'];
                        } elseif ($code === 'watermark') {
                            $file_name_arr = explode('.', $file['name']);
                            $ext = array_pop($file_name_arr);
                            $file_name = $file_var_list[$code]['store_dir'].'watermark.'.$ext;
                            if (file_exists($file_var_list[$code]['value'])) {
                                @unlink($file_var_list[$code]['value']);
                            }
                        } elseif ($code === 'wap_logo') {
                            $file_name_arr = explode('.', $file['name']);
                            $ext = array_pop($file_name_arr);
                            $file_name = $file_var_list[$code]['store_dir'].'wap_logo.'.$ext;
                            if (file_exists($file_var_list[$code]['value'])) {
                                @unlink($file_var_list[$code]['value']);
                            }
                        } else {
                            $file_name = $file_var_list[$code]['store_dir'].$file['name'];
                        }

                        // 判断是否上传成功
                        if (BaseHelper::move_upload_file($file['tmp_name'], $file_name)) {
                            DB::table('shop_config')
                                ->where('code', $code)
                                ->update(['value' => $file_name]);
                        } else {
                            return $this->sys_msg(sprintf(lang('msg_upload_failed'), $file['name'], $file_var_list[$code]['store_dir']));
                        }
                    }
                }
            }

            // 处理发票类型及税率
            if (! empty($_POST['invoice_rate'])) {
                foreach ($_POST['invoice_rate'] as $key => $rate) {
                    $rate = round(floatval($rate), 2);
                    if ($rate < 0) {
                        $rate = 0;
                    }
                    $_POST['invoice_rate'][$key] = $rate;
                }
                $invoice = [
                    'type' => $_POST['invoice_type'],
                    'rate' => $_POST['invoice_rate'],
                ];
                DB::table('shop_config')
                    ->where('code', 'invoice_type')
                    ->update(['value' => serialize($invoice)]);
            }

            // 记录日志
            $this->admin_log('', 'edit', 'shop_config');

            // 清除缓存
            CommonHelper::clear_all_files();

            $_CFG = CommonHelper::load_config();

            $shop_country = DB::table('shop_region')->where('region_id', $_CFG['shop_country'])->value('region_name');
            $shop_province = DB::table('shop_region')->where('region_id', $_CFG['shop_province'])->value('region_name');
            $shop_city = DB::table('shop_region')->where('region_id', $_CFG['shop_city'])->value('region_name');

            if ($type === 'mail_setting') {
                $links[] = ['text' => lang('back_mail_settings'), 'href' => 'shop_config.php?act=mail_settings'];

                return $this->sys_msg(lang('mail_save_success'), 0, $links);
            } else {
                $links[] = ['text' => lang('back_shop_config'), 'href' => 'shop_config.php?act=list_edit'];

                return $this->sys_msg(lang('save_success'), 0, $links);
            }
        }

        /**
         * 发送测试邮件
         */
        if ($action === 'send_test_email') {
            $this->check_authz_json('shop_config');

            // 取得参数
            $email = trim($_POST['email']);

            // 更新配置
            cfg('mail_service') = intval($_POST['mail_service']);
            cfg('smtp_host') = trim($_POST['smtp_host']);
            cfg('smtp_port') = trim($_POST['smtp_port']);
            cfg('smtp_user') = BaseHelper::json_str_iconv(trim($_POST['smtp_user']));
            cfg('smtp_pass') = trim($_POST['smtp_pass']);
            cfg('smtp_mail') = trim($_POST['reply_email']);
            cfg('mail_charset') = trim($_POST['mail_charset']);

            if (BaseHelper::send_mail('', $email, lang('test_mail_title'), lang('cfg_name.email_content'), 0)) {
                return $this->make_json_result('', lang('sendemail_success').$email);
            } else {
                return $this->make_json_error(implode("\n", $err->_message));
            }
        }

        /**
         * 删除上传文件
         */
        if ($action === 'del') {
            $this->check_authz_json('shop_config');

            // 取得参数
            $code = trim($_GET['code']);

            $filename = $_CFG[$code];

            // 删除文件
            @unlink($filename);

            // 更新设置
            $this->update_configure($code, '');

            // 记录日志
            $this->admin_log('', 'edit', 'shop_config');

            // 清除缓存
            CommonHelper::clear_all_files();

            return $this->sys_msg(lang('save_success'), 0);
        }
    }

    /**
     * 设置系统设置
     *
     * @param  string  $key
     * @param  string  $val
     * @return bool
     */
    private function update_configure($key, $val = '')
    {
        if (! empty($key)) {
            return (bool) DB::table('shop_config')
                ->where('code', $key)
                ->update(['value' => $val]);
        }

        return true;
    }

    /**
     * 获得设置信息
     *
     * @param  array  $groups  需要获得的设置组
     * @param  array  $excludes  不需要获得的设置组
     * @return array
     */
    private function get_settings($groups = null, $excludes = null)
    {
        $query = DB::table('shop_config')
            ->where('type', '<>', 'hidden');

        if (! empty($groups)) {
            $query->where(function ($q) use ($groups) {
                foreach ($groups as $val) {
                    $q->orWhere('id', $val)->orWhere('parent_id', $val);
                }
            });
        }

        if (! empty($excludes)) {
            foreach ($excludes as $val) {
                $query->where('parent_id', '<>', $val)->where('id', '<>', $val);
            }
        }

        $item_list = $query->orderBy('parent_id')->orderBy('sort_order')->orderBy('id')
            ->get()
            ->map(fn ($item) => (array) $item)
            ->all();

        // 整理数据
        $group_list = [];
        foreach ($item_list as $key => $item) {
            $pid = $item['parent_id'];
            $item['name'] = isset(lang('cfg_name')[$item['code']]) ? lang('cfg_name')[$item['code']] : $item['code'];
            $item['desc'] = isset(lang('cfg_desc')[$item['code']]) ? lang('cfg_desc')[$item['code']] : '';

            if ($item['code'] === 'sms_shop_mobile') {
                $item['url'] = 1;
            }
            if ($pid === 0) {
                // 分组
                if ($item['type'] === 'group') {
                    $group_list[$item['id']] = $item;
                }
            } else {
                // 变量
                if (isset($group_list[$pid])) {
                    if ($item['store_range']) {
                        $item['store_options'] = explode(',', $item['store_range']);

                        foreach ($item['store_options'] as $k => $v) {
                            $item['display_options'][$k] = isset(lang('cfg_range')[$item['code']][$v]) ?
                                lang('cfg_range')[$item['code']][$v] : $v;
                        }
                    }
                    $group_list[$pid]['vars'][] = $item;
                }
            }
        }

        return $group_list;
    }
}
