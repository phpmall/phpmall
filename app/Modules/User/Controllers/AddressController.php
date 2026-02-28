<?php

declare(strict_types=1);

namespace App\Modules\User\Controllers;

use App\Helpers\BaseHelper;
use App\Helpers\CommonHelper;
use App\Helpers\TransactionHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class AddressController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        // 收货地址列表界面
        if ($action === 'address_list') {
            include_once ROOT_PATH.'languages/'.cfg('lang').'/shopping_flow.php';

            // 取得国家列表、商店所在国家、商店所在国家的省列表
            $this->assign('country_list', CommonHelper::get_regions());
            $this->assign('shop_province_list', CommonHelper::get_regions(1, cfg('shop_country')));

            // 获得用户所有的收货人信息
            $consignee_list = TransactionHelper::get_consignee_list(Session::get('user_id'));

            if (count($consignee_list) < 5 && Session::get('user_id') > 0) {
                // 如果用户收货人信息的总数小于5 则增加一个新的收货人信息
                $consignee_list[] = ['country' => cfg('shop_country'), 'email' => Session::get('email', '')];
            }

            $this->assign('consignee_list', $consignee_list);

            // 取得国家列表，如果有收货人列表，取得省市区列表
            foreach ($consignee_list as $region_id => $consignee) {
                $consignee['country'] = isset($consignee['country']) ? intval($consignee['country']) : 0;
                $consignee['province'] = isset($consignee['province']) ? intval($consignee['province']) : 0;
                $consignee['city'] = isset($consignee['city']) ? intval($consignee['city']) : 0;

                $province_list[$region_id] = CommonHelper::get_regions(1, $consignee['country']);
                $city_list[$region_id] = CommonHelper::get_regions(2, $consignee['province']);
                $district_list[$region_id] = CommonHelper::get_regions(3, $consignee['city']);
            }

            // 获取默认收货ID
            $address_id = DB::table('users')->where('user_id', $this->getUserId())->value('address_id');

            // 赋值于模板
            $this->assign('real_goods_count', 1);
            $this->assign('shop_country', cfg('shop_country'));
            $this->assign('shop_province', CommonHelper::get_regions(1, cfg('shop_country')));
            $this->assign('province_list', $province_list);
            $this->assign('address', $address_id);
            $this->assign('city_list', $city_list);
            $this->assign('district_list', $district_list);
            $this->assign('currency_format', cfg('currency_format'));
            $this->assign('integral_scale', cfg('integral_scale'));
            $this->assign('name_of_region', [cfg('name_of_region_1'), cfg('name_of_region_2'), cfg('name_of_region_3'), cfg('name_of_region_4')]);

            return $this->display('user_transaction');
        }

        // 添加/编辑收货地址的处理
        if ($action === 'act_edit_address') {
            include_once ROOT_PATH.'languages/'.cfg('lang').'/shopping_flow.php';

            $address = [
                'user_id' => $this->getUserId(),
                'address_id' => intval($_POST['address_id']),
                'country' => isset($_POST['country']) ? intval($_POST['country']) : 0,
                'province' => isset($_POST['province']) ? intval($_POST['province']) : 0,
                'city' => isset($_POST['city']) ? intval($_POST['city']) : 0,
                'district' => isset($_POST['district']) ? intval($_POST['district']) : 0,
                'address' => isset($_POST['address']) ? BaseHelper::compile_str(trim($_POST['address'])) : '',
                'consignee' => isset($_POST['consignee']) ? BaseHelper::compile_str(trim($_POST['consignee'])) : '',
                'email' => isset($_POST['email']) ? BaseHelper::compile_str(trim($_POST['email'])) : '',
                'tel' => isset($_POST['tel']) ? BaseHelper::compile_str(BaseHelper::make_semiangle(trim($_POST['tel']))) : '',
                'mobile' => isset($_POST['mobile']) ? BaseHelper::compile_str(BaseHelper::make_semiangle(trim($_POST['mobile']))) : '',
                'best_time' => isset($_POST['best_time']) ? BaseHelper::compile_str(trim($_POST['best_time'])) : '',
                'sign_building' => isset($_POST['sign_building']) ? BaseHelper::compile_str(trim($_POST['sign_building'])) : '',
                'zipcode' => isset($_POST['zipcode']) ? BaseHelper::compile_str(BaseHelper::make_semiangle(trim($_POST['zipcode']))) : '',
            ];

            if (TransactionHelper::update_address($address)) {
                $this->show_message(lang('edit_address_success'), lang('address_list_lnk'), 'user.php?act=address_list');
            }
        }

        // 删除收货地址
        if ($action === 'drop_consignee') {
            $consignee_id = intval($_GET['id']);

            if (TransactionHelper::drop_consignee($consignee_id)) {
                return response()->redirectTo('user.php?act=address_list');
            } else {
                $this->show_message(lang('del_address_false'));
            }
        }
    }
}
