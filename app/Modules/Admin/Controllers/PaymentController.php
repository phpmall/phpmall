<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Helpers\BaseHelper;
use App\Modules\Admin\Helpers\MainHelper;
use App\Modules\Admin\Libraries\Exchange;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        $exc = new Exchange(ecs()->table('payment'), db(), 'pay_code', 'pay_name');

        /**
         * 支付方式列表 ?act=list
         */
        if ($action === 'list') {
            // 查询数据库中启用的支付方式
            $pay_list = [];
            $res = DB::table('payment')
                ->where('enabled', 1)
                ->orderBy('pay_order')
                ->get();
            foreach ($res as $row) {
                $row = (array) $row;
                $pay_list[$row['pay_code']] = $row;
            }

            // 取得插件文件中的支付方式
            $modules = MainHelper::read_modules('../includes/modules/payment');
            for ($i = 0; $i < count($modules); $i++) {
                $code = $modules[$i]['code'];
                $modules[$i]['pay_code'] = $modules[$i]['code'];
                // 如果数据库中有，取数据库中的名称和描述
                if (isset($pay_list[$code])) {
                    $modules[$i]['name'] = $pay_list[$code]['pay_name'];
                    $modules[$i]['pay_fee'] = $pay_list[$code]['pay_fee'];
                    $modules[$i]['is_cod'] = $pay_list[$code]['is_cod'];
                    $modules[$i]['desc'] = $pay_list[$code]['pay_desc'];
                    $modules[$i]['pay_order'] = $pay_list[$code]['pay_order'];
                    $modules[$i]['install'] = '1';
                } else {
                    $modules[$i]['name'] = lang($modules[$i]['code']);
                    if (! isset($modules[$i]['pay_fee'])) {
                        $modules[$i]['pay_fee'] = 0;
                    }
                    $modules[$i]['desc'] = lang($modules[$i]['desc']);
                    $modules[$i]['install'] = '0';
                }
            }

            $this->assign('ur_here', lang('02_payment_list'));
            $this->assign('modules', $modules);

            return $this->display('payment_list');
        }

        /**
         * 安装支付方式 ?act=install&code=".$code."
         */
        if ($action === 'install') {
            $this->admin_priv('payment');

            // 取相应插件信息
            $set_modules = true;
            $modules = MainHelper::read_modules('../includes/modules/payment');

            $data = $modules[0];
            // 对支付费用判断。如果data['pay_fee']为false无支付费用，为空则说明以配送有关，其它可以修改
            if (isset($data['pay_fee'])) {
                $data['pay_fee'] = trim($data['pay_fee']);
            } else {
                $data['pay_fee'] = 0;
            }

            $pay['pay_code'] = $data['code'];
            $pay['pay_name'] = lang($data['code']);
            $pay['pay_desc'] = lang($data['desc']);
            $pay['is_cod'] = $data['is_cod'];
            $pay['pay_fee'] = $data['pay_fee'];
            $pay['is_online'] = $data['is_online'];
            $pay['pay_config'] = [];

            foreach ($data['config'] as $key => $value) {
                $config_desc = (lang($value['name'].'_desc') !== $value['name'].'_desc') ? lang($value['name'].'_desc') : '';
                $pay['pay_config'][$key] = $value +
                    ['label' => lang($value['name']), 'value' => $value['value'], 'desc' => $config_desc];

                if (
                    $pay['pay_config'][$key]['type'] === 'select' ||
                    $pay['pay_config'][$key]['type'] === 'radiobox'
                ) {
                    $pay['pay_config'][$key]['range'] = lang($pay['pay_config'][$key]['name'].'_range');
                }
            }

            $this->assign('action_link', ['text' => lang('02_payment_list'), 'href' => 'payment.php?act=list']);
            $this->assign('pay', $pay);

            return $this->display('payment_edit');
        }

        if ($action === 'get_config') {
            $this->check_authz_json('payment');

            $code = $_REQUEST['code'];

            // 取相应插件信息
            $set_modules = true;
            $modules = MainHelper::read_modules('../includes/modules/payment');
            $data = $modules[0]['config'];
            $config = '<table>';
            $range = '';
            foreach ($data as $key => $value) {
                $config .= "<tr><td width=80><span class='label'>";
                $config .= lang($data[$key]['name']);
                $config .= '</span></td>';
                if ($data[$key]['type'] === 'text') {
                    if ($data[$key]['name'] === 'alipay_account') {
                        $config .= "<td><input name='cfg_value[]' type='text' value='".$data[$key]['value']."' /><a href=\"https://www.alipay.com/himalayas/practicality.htm\" target=\"_blank\">".lang('alipay_look').'</a></td>';
                    } elseif ($data[$key]['name'] === 'tenpay_account') {
                        $config .= "<td><input name='cfg_value[]' type='text' value='".$data[$key]['value']."' />".lang('penpay_register').'</td>';
                    } else {
                        $config .= "<td><input name='cfg_value[]' type='text' value='".$data[$key]['value']."' /></td>";
                    }
                } elseif ($data[$key]['type'] === 'select') {
                    $range = lang($data[$key]['name'].'_range');
                    $config .= "<td><select name='cfg_value[]'>";
                    if (is_array($range)) {
                        foreach ($range as $index => $val) {
                            $config .= "<option value='$index'>".$val.'</option>';
                        }
                    }
                    $config .= '</select></td>';
                }
                $config .= '</tr>';
                // $config .= '<br />';
                $config .= "<input name='cfg_name[]' type='hidden' value='".$data[$key]['name']."' />";
                $config .= "<input name='cfg_type[]' type='hidden' value='".$data[$key]['type']."' />";
                $config .= "<input name='cfg_lang[]' type='hidden' value='".$data[$key]['lang']."' />";
            }
            $config .= '</table>';

            return $this->make_json_result($config);
        }

        /**
         * 编辑支付方式 ?act=edit&code={$code}
         */
        if ($action === 'edit') {
            $this->admin_priv('payment');

            // 查询该支付方式内容
            if (isset($_REQUEST['code'])) {
                $_REQUEST['code'] = trim($_REQUEST['code']);
            } else {
                exit('invalid parameter');
            }

            $pay = DB::table('payment')
                ->where('pay_code', $_REQUEST['code'])
                ->where('enabled', 1)
                ->first();
            $pay = $pay ? (array) $pay : [];

            if (empty($pay)) {
                $links[] = ['text' => lang('back_list'), 'href' => 'payment.php?act=list'];

                return $this->sys_msg(lang('payment_not_available'), 0, $links);
            }

            // 取相应插件信息
            $set_modules = true;
            $modules = MainHelper::read_modules('../includes/modules/payment');
            $data = $modules[0];

            // 取得配置信息
            if (is_string($pay['pay_config'])) {
                $store = unserialize($pay['pay_config']);
                // 取出已经设置属性的code
                $code_list = [];
                if ($store) {
                    foreach ($store as $key => $value) {
                        $code_list[$value['name']] = $value['value'];
                    }
                }

                $pay['pay_config'] = [];

                // 循环插件中所有属性
                foreach ($data['config'] as $key => $value) {
                    $pay['pay_config'][$key]['desc'] = (lang($value['name'].'_desc') !== $value['name'].'_desc') ? lang($value['name'].'_desc') : '';
                    $pay['pay_config'][$key]['label'] = lang($value['name']);
                    $pay['pay_config'][$key]['name'] = $value['name'];
                    $pay['pay_config'][$key]['type'] = $value['type'];

                    if (isset($code_list[$value['name']])) {
                        $pay['pay_config'][$key]['value'] = $code_list[$value['name']];
                    } else {
                        $pay['pay_config'][$key]['value'] = $value['value'];
                    }

                    if (
                        $pay['pay_config'][$key]['type'] === 'select' ||
                        $pay['pay_config'][$key]['type'] === 'radiobox'
                    ) {
                        $pay['pay_config'][$key]['range'] = lang($pay['pay_config'][$key]['name'].'_range');
                    }
                }
            }

            // 如果以前没设置支付费用，编辑时补上
            if (! isset($pay['pay_fee'])) {
                if (isset($data['pay_fee'])) {
                    $pay['pay_fee'] = $data['pay_fee'];
                } else {
                    $pay['pay_fee'] = 0;
                }
            }

            $this->assign('action_link', ['text' => lang('02_payment_list'), 'href' => 'payment.php?act=list']);
            $this->assign('ur_here', lang('edit').lang('payment'));
            $this->assign('pay', $pay);

            return $this->display('payment_edit');
        }

        /**
         * 提交支付方式 post
         */
        if (isset($_POST['Submit'])) {
            $this->admin_priv('payment');

            // 检查输入
            if (empty($_POST['pay_name'])) {
                return $this->sys_msg(lang('payment_name').lang('empty'));
            }

            $count = DB::table('payment')
                ->where('pay_name', $_POST['pay_name'])
                ->where('pay_code', '<>', $_POST['pay_code'])
                ->count();
            if ($count > 0) {
                return $this->sys_msg(lang('payment_name').lang('repeat'), 1);
            }

            // 取得配置信息
            $pay_config = [];
            if (isset($_POST['cfg_value']) && is_array($_POST['cfg_value'])) {
                for ($i = 0; $i < count($_POST['cfg_value']); $i++) {
                    $pay_config[] = [
                        'name' => trim($_POST['cfg_name'][$i]),
                        'type' => trim($_POST['cfg_type'][$i]),
                        'value' => trim($_POST['cfg_value'][$i]),
                    ];
                }
            }
            $pay_config = serialize($pay_config);
            // 取得和验证支付手续费
            $pay_fee = empty($_POST['pay_fee']) ? 0 : $_POST['pay_fee'];

            // 检查是编辑还是安装
            $link[] = ['text' => lang('back_list'), 'href' => 'payment.php?act=list'];
            if ($_POST['pay_id']) {
                // 编辑
                DB::table('payment')
                    ->where('pay_code', $_POST['pay_code'])
                    ->update([
                        'pay_name' => $_POST['pay_name'],
                        'pay_desc' => $_POST['pay_desc'],
                        'pay_config' => $pay_config,
                        'pay_fee' => $pay_fee,
                    ]);

                // 记录日志
                $this->admin_log($_POST['pay_name'], 'edit', 'payment');

                return $this->sys_msg(lang('edit_ok'), 0, $link);
            } else {
                // 安装，检查该支付方式是否曾经安装过
                $count = DB::table('payment')
                    ->where('pay_code', $_REQUEST['pay_code'])
                    ->count();
                if ($count > 0) {
                    // 该支付方式已经安装过, 将该支付方式的状态设置为 enable
                    DB::table('payment')
                        ->where('pay_code', $_POST['pay_code'])
                        ->update([
                            'pay_name' => $_POST['pay_name'],
                            'pay_desc' => $_POST['pay_desc'],
                            'pay_config' => $pay_config,
                            'pay_fee' => $pay_fee,
                            'enabled' => 1,
                        ]);
                } else {
                    // 该支付方式没有安装过, 将该支付方式的信息添加到数据库
                    DB::table('payment')->insert([
                        'pay_code' => $_POST['pay_code'],
                        'pay_name' => $_POST['pay_name'],
                        'pay_desc' => $_POST['pay_desc'],
                        'pay_config' => $pay_config,
                        'is_cod' => $_POST['is_cod'],
                        'pay_fee' => $pay_fee,
                        'enabled' => 1,
                        'is_online' => $_POST['is_online'],
                    ]);
                }

                // 记录日志
                $this->admin_log($_POST['pay_name'], 'install', 'payment');

                return $this->sys_msg(lang('install_ok'), 0, $link);
            }
        }

        /**
         * 卸载支付方式 ?act=uninstall&code={$code}
         */
        if ($action === 'uninstall') {
            $this->admin_priv('payment');

            // 把 enabled 设为 0
            DB::table('payment')
                ->where('pay_code', $_REQUEST['code'])
                ->update(['enabled' => 0]);

            // 记录日志
            $this->admin_log($_REQUEST['code'], 'uninstall', 'payment');

            $link[] = ['text' => lang('back_list'), 'href' => 'payment.php?act=list'];

            return $this->sys_msg(lang('uninstall_ok'), 0, $link);
        }

        /**
         * 修改支付方式名称
         */
        if ($action === 'edit_name') {
            $this->check_authz_json('payment');

            // 取得参数
            $code = BaseHelper::json_str_iconv(trim($_POST['id']));
            $name = BaseHelper::json_str_iconv(trim($_POST['val']));

            // 检查名称是否为空
            if (empty($name)) {
                return $this->make_json_error(lang('name_is_null'));
            }

            // 检查名称是否重复
            if (! $exc->is_only('pay_name', $name, $code)) {
                return $this->make_json_error(lang('name_exists'));
            }

            // 更新支付方式名称
            $exc->edit("pay_name = '$name'", $code);

            return $this->make_json_result(stripcslashes($name));
        }

        /**
         * 修改支付方式描述
         */
        if ($action === 'edit_desc') {
            $this->check_authz_json('payment');

            // 取得参数
            $code = BaseHelper::json_str_iconv(trim($_POST['id']));
            $desc = BaseHelper::json_str_iconv(trim($_POST['val']));

            // 更新描述
            $exc->edit("pay_desc = '$desc'", $code);

            return $this->make_json_result(stripcslashes($desc));
        }

        /**
         * 修改支付方式排序
         */
        if ($action === 'edit_order') {
            $this->check_authz_json('payment');

            // 取得参数
            $code = BaseHelper::json_str_iconv(trim($_POST['id']));
            $order = intval($_POST['val']);

            // 更新排序
            $exc->edit("pay_order = '$order'", $code);

            return $this->make_json_result(stripcslashes($order));
        }

        /**
         * 修改支付方式费用
         */
        if ($action === 'edit_pay_fee') {
            $this->check_authz_json('payment');

            // 取得参数
            $code = BaseHelper::json_str_iconv(trim($_POST['id']));
            $pay_fee = BaseHelper::json_str_iconv(trim($_POST['val']));
            if (empty($pay_fee)) {
                $pay_fee = 0;
            } else {
                $pay_fee = BaseHelper::make_semiangle($pay_fee); // 全角转半角
                if (strpos($pay_fee, '%') === false) {
                    $pay_fee = floatval($pay_fee);
                } else {
                    $pay_fee = floatval($pay_fee).'%';
                }
            }

            // 更新支付费用
            $exc->edit("pay_fee = '$pay_fee'", $code);

            return $this->make_json_result(stripcslashes($pay_fee));
        }
    }
}
