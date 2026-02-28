<?php

declare(strict_types=1);

namespace App\Plugins\Payment;

$payment_lang = ROOT_PATH.'languages/'.cfg('lang').'/payment/post.php';

if (file_exists($payment_lang)) {
    include_once $payment_lang;
}

// 模块的基本信息
if (isset($set_modules) && $set_modules === true) {
    $i = isset($modules) ? count($modules) : 0;

    // 代码
    $modules[$i]['code'] = basename(__FILE__, '.php');

    // 描述对应的语言项
    $modules[$i]['desc'] = 'post_desc';

    // 是否支持货到付款
    $modules[$i]['is_cod'] = '0';

    // 是否支持在线支付
    $modules[$i]['is_online'] = '0';

    // 作者
    $modules[$i]['author'] = 'PHPMall TEAM';

    // 网址
    $modules[$i]['website'] = 'http://www.phpmall.net';

    // 版本号
    $modules[$i]['version'] = '1.0.0';

    // 配置信息
    $modules[$i]['config'] = [];

    return;
}

/**
 * 类
 */
class Post
{
    /**
     * 构造函数
     *
     *
     * @return void
     */
    public function __construct() {}

    /**
     * 提交函数
     */
    public function get_code()
    {
        return '';
    }

    /**
     * 处理函数
     */
    public function response() {}
}
