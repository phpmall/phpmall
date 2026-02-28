<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Helpers\BaseHelper;
use App\Helpers\CommonHelper;
use App\Modules\Admin\Libraries\Exchange;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShippingAreaController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        $exc = new Exchange(ecs()->table('shipping_area'), db(), 'shipping_area_id', 'shipping_area_name');

        /**
         * 配送区域列表
         */
        if ($action === 'list') {
            $shipping_id = intval($_REQUEST['shipping']);

            $list = $this->get_shipping_area_list($shipping_id);
            $this->assign('areas', $list);

            $this->assign('ur_here', '<a href="shipping.php?act=list">'.
                lang('03_shipping_list').'</a> - '.lang('shipping_area_list').'</a>');
            $this->assign('action_link', [
                'href' => 'shipping_area.php?act=add&shipping='.$shipping_id,
                'text' => lang('new_area'),
            ]);
            $this->assign('full_page', 1);

            return $this->display('shipping_area_list');
        }

        /**
         * 新建配送区域
         */
        if ($action === 'add' && ! empty($_REQUEST['shipping'])) {
            $this->admin_priv('shiparea_manage');

            $shipping = (array) DB::table('shipping')->where('shipping_id', (int) $_REQUEST['shipping'])->select('shipping_name', 'shipping_code')->first();

            $set_modules = 1;
            // include_once ROOT_PATH.'includes/modules/shipping/'.$shipping['shipping_code'].'.php';

            $fields = [];
            foreach ($modules[0]['configure'] as $key => $val) {
                $fields[$key]['name'] = $val['name'];
                $fields[$key]['value'] = $val['value'];
                $fields[$key]['label'] = $_LANG[$val['name']];
            }
            $count = count($fields);
            $fields[$count]['name'] = 'free_money';
            $fields[$count]['value'] = '0';
            $fields[$count]['label'] = lang('free_money');

            // 如果支持货到付款，则允许设置货到付款支付费用
            if ($modules[0]['cod']) {
                $count++;
                $fields[$count]['name'] = 'pay_fee';
                $fields[$count]['value'] = '0';
                $fields[$count]['label'] = lang('pay_fee');
            }

            $shipping_area['shipping_id'] = 0;
            $shipping_area['free_money'] = 0;

            $this->assign('ur_here', $shipping['shipping_name'].' - '.lang('new_area'));
            $this->assign('shipping_area', ['shipping_id' => $_REQUEST['shipping'], 'shipping_code' => $shipping['shipping_code']]);
            $this->assign('fields', $fields);
            $this->assign('form_action', 'insert');
            $this->assign('countries', CommonHelper::get_regions());
            $this->assign('default_country', cfg('shop_country'));

            return $this->display('shipping_area_info');
        }

        if ($action === 'insert') {
            $this->admin_priv('shiparea_manage');

            // 检查同类型的配送方式下有没有重名的配送区域
            if (
                DB::table('shipping_area')
                    ->where('shipping_id', $_POST['shipping'])
                    ->where('shipping_area_name', $_POST['shipping_area_name'])
                    ->count() > 0
            ) {
                return $this->sys_msg(lang('repeat_area_name'), 1);
            } else {
                $shipping_code = DB::table('shipping')->where('shipping_id', (int) $_POST['shipping'])->value('shipping_code');
                $plugin = '../includes/modules/shipping/'.$shipping_code.'.php';

                if (! file_exists($plugin)) {
                    return $this->sys_msg(lang('not_find_plugin'), 1);
                } else {
                    $set_modules = 1;
                    // include_once $plugin;
                }

                $config = [];
                foreach ($modules[0]['configure'] as $key => $val) {
                    $config[$key]['name'] = $val['name'];
                    $config[$key]['value'] = $_POST[$val['name']];
                }

                $count = count($config);
                $config[$count]['name'] = 'free_money';
                $config[$count]['value'] = empty($_POST['free_money']) ? '' : $_POST['free_money'];
                $count++;
                $config[$count]['name'] = 'fee_compute_mode';
                $config[$count]['value'] = empty($_POST['fee_compute_mode']) ? '' : $_POST['fee_compute_mode'];
                // 如果支持货到付款，则允许设置货到付款支付费用
                if ($modules[0]['cod']) {
                    $count++;
                    $config[$count]['name'] = 'pay_fee';
                    $config[$count]['value'] = BaseHelper::make_semiangle(empty($_POST['pay_fee']) ? '' : $_POST['pay_fee']);
                }

                $new_id = DB::table('shipping_area')->insertGetId([
                    'shipping_area_name' => $_POST['shipping_area_name'],
                    'shipping_id' => $_POST['shipping'],
                    'configure' => serialize($config),
                ]);

                // 添加选定的城市和地区
                if (isset($_POST['regions']) && is_array($_POST['regions'])) {
                    foreach ($_POST['regions'] as $key => $val) {
                        DB::table('shipping_area_region')->insert(['shipping_area_id' => $new_id, 'region_id' => $val]);
                    }
                }

                $this->admin_log($_POST['shipping_area_name'], 'add', 'shipping_area');

                $lnk[] = ['text' => lang('back_list'), 'href' => 'shipping_area.php?act=list&shipping='.$_POST['shipping']];
                $lnk[] = ['text' => lang('add_continue'), 'href' => 'shipping_area.php?act=add&shipping='.$_POST['shipping']];

                return $this->sys_msg(lang('add_area_success'), 0, $lnk);
            }
        }

        /**
         * 编辑配送区域
         */
        if ($action === 'edit') {
            $this->admin_priv('shiparea_manage');

            $row = (array) DB::table('shipping AS a')
                ->join('shipping_area AS b', 'b.shipping_id', '=', 'a.shipping_id')
                ->where('b.shipping_area_id', (int) $_REQUEST['id'])
                ->select('a.shipping_name', 'a.shipping_code', 'a.support_cod', 'b.*')
                ->first();

            $set_modules = 1;
            // include_once ROOT_PATH.'includes/modules/shipping/'.$row['shipping_code'].'.php';

            $fields = unserialize($row['configure']);
            // 如果配送方式支持货到付款并且没有设置货到付款支付费用，则加入货到付款费用
            if ($row['support_cod'] && $fields[count($fields) - 1]['name'] != 'pay_fee') {
                $fields[] = ['name' => 'pay_fee', 'value' => 0];
            }

            foreach ($fields as $key => $val) {
                // 替换更改的语言项
                if ($val['name'] === 'basic_fee') {
                    $val['name'] = 'base_fee';
                }
                if ($val['name'] === 'item_fee') {
                    $item_fee = 1;
                }
                if ($val['name'] === 'fee_compute_mode') {
                    $this->assign('fee_compute_mode', $val['value']);
                    unset($fields[$key]);
                } else {
                    $fields[$key]['name'] = $val['name'];
                    $fields[$key]['label'] = $_LANG[$val['name']];
                }
            }

            if (empty($item_fee)) {
                $field = ['name' => 'item_fee', 'value' => '0', 'label' => empty(lang('item_fee')) ? '' : lang('item_fee')];
                array_unshift($fields, $field);
            }

            // 获得该区域下的所有地区
            $regions = [];

            $res = DB::table('shipping_area_region AS a')
                ->join('shop_region AS r', 'r.region_id', '=', 'a.region_id')
                ->where('a.shipping_area_id', (int) $_REQUEST['id'])
                ->select('a.region_id', 'r.region_name')
                ->get();
            foreach ($res as $arr) {
                $arr = (array) $arr;
                $regions[$arr['region_id']] = $arr['region_name'];
            }

            $this->assign('ur_here', $row['shipping_name'].' - '.lang('edit_area'));
            $this->assign('id', $_REQUEST['id']);
            $this->assign('fields', $fields);
            $this->assign('shipping_area', $row);
            $this->assign('regions', $regions);
            $this->assign('form_action', 'update');
            $this->assign('countries', CommonHelper::get_regions());
            $this->assign('default_country', 1);

            return $this->display('shipping_area_info');
        }

        if ($action === 'update') {
            $this->admin_priv('shiparea_manage');

            // 检查同类型的配送方式下有没有重名的配送区域
            if (
                DB::table('shipping_area')
                    ->where('shipping_id', $_POST['shipping'])
                    ->where('shipping_area_name', $_POST['shipping_area_name'])
                    ->where('shipping_area_id', '<>', (int) $_POST['id'])
                    ->count() > 0
            ) {
                return $this->sys_msg(lang('repeat_area_name'), 1);
            } else {
                $shipping_code = DB::table('shipping')->where('shipping_id', (int) $_POST['shipping'])->value('shipping_code');
                $plugin = '../includes/modules/shipping/'.$shipping_code.'.php';

                if (! file_exists($plugin)) {
                    return $this->sys_msg(lang('not_find_plugin'), 1);
                } else {
                    $set_modules = 1;
                    // include_once $plugin;
                }

                $config = [];
                foreach ($modules[0]['configure'] as $key => $val) {
                    $config[$key]['name'] = $val['name'];
                    $config[$key]['value'] = $_POST[$val['name']];
                }

                $count = count($config);
                $config[$count]['name'] = 'free_money';
                $config[$count]['value'] = empty($_POST['free_money']) ? '' : $_POST['free_money'];
                $count++;
                $config[$count]['name'] = 'fee_compute_mode';
                $config[$count]['value'] = empty($_POST['fee_compute_mode']) ? '' : $_POST['fee_compute_mode'];
                if ($modules[0]['cod']) {
                    $count++;
                    $config[$count]['name'] = 'pay_fee';
                    $config[$count]['value'] = BaseHelper::make_semiangle(empty($_POST['pay_fee']) ? '' : $_POST['pay_fee']);
                }

                DB::table('shipping_area')
                    ->where('shipping_area_id', (int) $_POST['id'])
                    ->update(['shipping_area_name' => $_POST['shipping_area_name'], 'configure' => serialize($config)]);

                $this->admin_log($_POST['shipping_area_name'], 'edit', 'shipping_area');

                // 过滤掉重复的region
                $selected_regions = [];
                if (isset($_POST['regions'])) {
                    foreach ($_POST['regions'] as $region_id) {
                        $selected_regions[$region_id] = $region_id;
                    }
                }

                // 查询所有区域 region_id => parent_id
                $res = DB::table('shop_region')->select('region_id', 'parent_id')->get();
                foreach ($res as $row) {
                    $row = (array) $row;
                    $region_list[$row['region_id']] = $row['parent_id'];
                }

                // 过滤掉上级存在的区域
                foreach ($selected_regions as $region_id) {
                    $id = $region_id;
                    while ($region_list[$id] != 0) {
                        $id = $region_list[$id];
                        if (isset($selected_regions[$id])) {
                            unset($selected_regions[$region_id]);
                            break;
                        }
                    }
                }

                // 清除原有的城市和地区
                DB::table('shipping_area_region')->where('shipping_area_id', (int) $_POST['id'])->delete();

                // 添加选定的城市和地区
                foreach ($selected_regions as $key => $val) {
                    DB::table('shipping_area_region')->insert(['shipping_area_id' => (int) $_POST['id'], 'region_id' => $val]);
                }

                $lnk[] = ['text' => lang('back_list'), 'href' => 'shipping_area.php?act=list&shipping='.$_POST['shipping']];

                return $this->sys_msg(lang('edit_area_success'), 0, $lnk);
            }
        }

        /**
         * 批量删除配送区域
         */
        if ($action === 'multi_remove') {
            $this->admin_priv('shiparea_manage');

            if (isset($_POST['areas']) && count($_POST['areas']) > 0) {
                $i = 0;
                foreach ($_POST['areas'] as $v) {
                    DB::table('shipping_area')->where('shipping_area_id', (int) $v)->delete();
                    $i++;
                }

                // 记录管理员操作
                $this->admin_log('', 'batch_remove', 'shipping_area');
            }
            // 返回
            $links[0] = ['href' => 'shipping_area.php?act=list&shipping='.intval($_REQUEST['shipping']), 'text' => lang('go_back')];

            return $this->sys_msg(lang('remove_success'), 0, $links);
        }

        /**
         * 编辑配送区域名称
         */
        if ($action === 'edit_area') {
            $this->check_authz_json('shiparea_manage');

            // 取得参数
            $id = intval($_POST['id']);
            $val = BaseHelper::json_str_iconv(trim($_POST['val']));

            // 取得该区域所属的配送id
            $shipping_id = $exc->get_name($id, 'shipping_id');

            // 检查是否有重复的配送区域名称
            if (! $exc->is_only('shipping_area_name', $val, $id, "shipping_id = '$shipping_id'")) {
                return $this->make_json_error(lang('repeat_area_name'));
            }

            // 更新名称
            $exc->edit("shipping_area_name = '$val'", $id);

            // 记录日志
            $this->admin_log($val, 'edit', 'shipping_area');

            // 返回
            return $this->make_json_result(stripcslashes($val));
        }

        /**
         * 删除配送区域
         */
        if ($action === 'remove_area') {
            $this->check_authz_json('shiparea_manage');

            $id = intval($_GET['id']);
            $name = $exc->get_name($id);
            $shipping_id = $exc->get_name($id, 'shipping_id');

            $exc->drop($id);
            DB::table('shipping_area_region')->where('shipping_area_id', $id)->delete();

            $this->admin_log($name, 'remove', 'shipping_area');

            $list = $this->get_shipping_area_list((int) $shipping_id);
            $this->assign('areas', $list);

            return $this->make_json_result($this->fetch('shipping_area_list'));
        }
    }

    /**
     * 取得配送区域列表
     *
     * @param  int  $shipping_id  配送id
     */
    private function get_shipping_area_list(int $shipping_id): array
    {
        $res = DB::table('shipping_area')
            ->when($shipping_id > 0, fn ($q) => $q->where('shipping_id', $shipping_id))
            ->get();
        $list = [];
        foreach ($res as $row) {
            $row = (array) $row;
            $regions = implode(', ', DB::table('shipping_area_region AS a')
                ->join('shop_region AS r', 'r.region_id', '=', 'a.region_id')
                ->where('a.shipping_area_id', $row['shipping_area_id'])
                ->pluck('r.region_name')
                ->all());

            $row['shipping_area_regions'] = empty($regions) ?
                '<a href="shipping_area.php?act=region&amp;id='.$row['shipping_area_id'].
                '" style="color:red">'.lang('empty_regions').'</a>' : $regions;
            $list[] = $row;
        }

        return $list;
    }
}
