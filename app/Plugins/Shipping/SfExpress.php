<?php

declare(strict_types=1);

namespace App\Plugins\Shipping;

$shipping_lang = ROOT_PATH.'languages/'.cfg('lang').'/shipping/sf_express.php';

if (file_exists($shipping_lang)) {
    include_once $shipping_lang;
}

// 模块的基本信息
if (isset($set_modules) && $set_modules === true) {
    include_once ROOT_PATH.'languages/'.cfg('lang').'/admin/shipping.php';

    $i = (isset($modules)) ? count($modules) : 0;

    // 配送方式插件的代码必须和文件名保持一致
    $modules[$i]['code'] = basename(__FILE__, '.php');

    $modules[$i]['version'] = '1.0.0';

    // 配送方式的描述
    $modules[$i]['desc'] = 'sf_express_desc';

    // 配送方式是否支持货到付款
    $modules[$i]['cod'] = false;

    // 插件的作者
    $modules[$i]['author'] = 'PHPMall TEAM';

    // 插件作者的官方网站
    $modules[$i]['website'] = 'http://www.phpmall.net';

    // 配送接口需要的参数
    $modules[$i]['configure'] = [
        ['name' => 'item_fee', 'value' => 20], // 单件商品的配送费用
        ['name' => 'base_fee', 'value' => 15], // 1000克以内的价格
        ['name' => 'step_fee', 'value' => 2],  // 续重每1000克增加的价格
    ];

    // 模式编辑器
    $modules[$i]['print_model'] = 2;

    // 打印单背景
    $modules[$i]['print_bg'] = '/images/receipt/dly_sf_express.jpg';

    // 打印快递单标签位置信息
    $modules[$i]['config_lable'] = 't_shop_name,'.lang('lable_box.shop_name').',150,29,112,137,b_shop_name||,||t_shop_address,'.lang('lable_box.shop_address').',268,55,105,168,b_shop_address||,||t_shop_tel,'.lang('lable_box.shop_tel').',55,25,177,224,b_shop_tel||,||t_customer_name,'.lang('lable_box.customer_name').',78,23,299,265,b_customer_name||,||t_customer_address,'.lang('lable_box.customer_address').',271,94,104,293,b_customer_address||,||';

    return;
}

/**
 * 顺丰速运费用计算方式: 起点到终点 * 重量(kg)
 * ====================================================================================
 * -浙江，上海，江苏地区为15元/公斤，续重(2元/公斤)
 * -续重每500克或其零数 (具体请上顺丰速运网站查询:http://www.sf-express.com/sfwebapp/price.jsp 客服电话 4008111111)
 *
 * -------------------------------------------------------------------------------------
 */
class SfExpress
{
    /**
     * 配置信息参数
     */
    public $configure;

    /**
     * 构造函数
     *
     * @param: $configure[array]    配送方式的参数的数组
     *
     * @return null
     */
    public function __construct($cfg = [])
    {
        foreach ($cfg as $key => $val) {
            $this->configure[$val['name']] = $val['value'];
        }
    }

    /**
     * 计算订单的配送费用的函数
     *
     * @param  float  $goods_weight  商品重量
     * @param  float  $goods_amount  商品金额
     * @param  float  $goods_number  商品数量
     * @return decimal
     */
    public function calculate($goods_weight, $goods_amount, $goods_number)
    {
        if ($this->configure['free_money'] > 0 && $goods_amount >= $this->configure['free_money']) {
            return 0;
        } else {
            @$fee = $this->configure['base_fee'];
            $this->configure['fee_compute_mode'] = ! empty($this->configure['fee_compute_mode']) ? $this->configure['fee_compute_mode'] : 'by_weight';

            if ($this->configure['fee_compute_mode'] === 'by_number') {
                $fee = $goods_number * $this->configure['item_fee'];
            } else {
                if ($goods_weight > 1) {
                    $fee += (ceil(($goods_weight - 1))) * $this->configure['step_fee'];
                }
            }

            // $_SESSION['cart_weight'] = $goods_weight;
            return $fee;
        }
    }

    /**
     * 查询快递状态
     *
     * @return string 查询窗口的链接地址
     */
    public function query($invoice_sn)
    {
        $form_str = '<a href="http://www.sf-express.com/cn/sc/dynamic_functions/waybill/#search/bill-number/'.$invoice_sn.' " target="_blank">'.$invoice_sn.'</a>';

        return $form_str;
    }
}
