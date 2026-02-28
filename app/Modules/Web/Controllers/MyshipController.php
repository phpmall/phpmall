<?php

declare(strict_types=1);

namespace App\Modules\Web\Controllers;

use App\Helpers\CommonHelper;
use App\Helpers\MainHelper;
use App\Helpers\OrderHelper;
use App\Helpers\TransactionHelper;
use Illuminate\Http\Request;

class MyshipController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        // 载入语言文件
        require_once ROOT_PATH.'languages/'.cfg('lang').'/shopping_flow.php';
        require_once ROOT_PATH.'languages/'.cfg('lang').'/user.php';

        if (Session::get('user_id', 0) > 0) {
            $consignee_list = TransactionHelper::get_consignee_list(Session::get('user_id'));

            $choose['country'] = isset($_POST['country']) ? intval($_POST['country']) : $consignee_list[0]['country'];
            $choose['province'] = isset($_POST['province']) ? intval($_POST['province']) : $consignee_list[0]['province'];
            $choose['city'] = isset($_POST['city']) ? intval($_POST['city']) : $consignee_list[0]['city'];
            $choose['district'] = isset($_POST['district']) ? intval($_POST['district']) : (isset($consignee_list[0]['district']) ? $consignee_list[0]['district'] : 0);
        } else {
            $choose['country'] = isset($_POST['country']) ? intval($_POST['country']) : cfg('shop_country');
            $choose['province'] = isset($_POST['province']) ? intval($_POST['province']) : 2;
            $choose['city'] = isset($_POST['city']) ? intval($_POST['city']) : 35;
            $choose['district'] = isset($_POST['district']) ? intval($_POST['district']) : 417;
        }

        $this->assign_template();
        $this->assign_dynamic('myship');
        $position = $this->assign_ur_here(0, lang('shopping_myship'));
        $this->assign('page_title', $position['title']);    // 页面标题
        $this->assign('ur_here', $position['ur_here']);  // 当前位置

        $this->assign('helps', MainHelper::get_shop_help());       // 网店帮助

        $this->assign('choose', $choose);

        $province_list[null] = CommonHelper::get_regions(1, $choose['country']);
        $city_list[null] = CommonHelper::get_regions(2, $choose['province']);
        $district_list[null] = CommonHelper::get_regions(3, $choose['city']);

        $this->assign('province_list', $province_list);
        $this->assign('city_list', $city_list);
        $this->assign('district_list', $district_list);

        // 取得国家列表、商店所在国家、商店所在国家的省列表
        $this->assign('country_list', CommonHelper::get_regions());

        // 取得配送列表
        $region = [$choose['country'], $choose['province'], $choose['city'], $choose['district']];
        $shipping_list = OrderHelper::available_shipping_list($region);
        $cart_weight_price = 0;
        $insure_disabled = true;
        $cod_disabled = true;

        foreach ($shipping_list as $key => $val) {
            $shipping_cfg = OrderHelper::unserialize_config($val['configure']);
            $shipping_fee = OrderHelper::shipping_fee(
                $val['shipping_code'],
                unserialize($val['configure']),
                $cart_weight_price['weight'],
                $cart_weight_price['amount']
            );

            $shipping_list[$key]['format_shipping_fee'] = CommonHelper::price_format($shipping_fee, false);
            $shipping_list[$key]['fee'] = $shipping_fee;
            $shipping_list[$key]['free_money'] = CommonHelper::price_format($shipping_cfg['free_money'], false);
            $shipping_list[$key]['insure_formated'] = strpos($val['insure'], '%') === false ?
                CommonHelper::price_format($val['insure'], false) : $val['insure'];
        }

        $this->assign('shipping_list', $shipping_list);

        return $this->display('myship');
    }
}
