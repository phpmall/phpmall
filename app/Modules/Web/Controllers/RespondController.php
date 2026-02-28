<?php

declare(strict_types=1);

namespace App\Modules\Web\Controllers;

use App\Helpers\MainHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RespondController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        // 支付方式代码
        $pay_code = ! empty($_REQUEST['code']) ? trim($_REQUEST['code']) : '';

        // 参数是否为空
        if (empty($pay_code)) {
            $msg = lang('pay_not_exist');
        } else {
            // 检查code里面有没有问号
            if (strpos($pay_code, '?') !== false) {
                $arr1 = explode('?', $pay_code);
                $arr2 = explode('=', $arr1[1]);

                $_REQUEST['code'] = $arr1[0];
                $_REQUEST[$arr2[0]] = $arr2[1];
                $_GET['code'] = $arr1[0];
                $_GET[$arr2[0]] = $arr2[1];
                $pay_code = $arr1[0];
            }

            // 判断是否启用
            $count = DB::table('payment')
                ->where('pay_code', $pay_code)
                ->where('enabled', 1)
                ->count();
            if ($count === 0) {
                $msg = lang('pay_disabled');
            } else {
                $plugin_file = ROOT_PATH.'includes/modules/payment/'.$pay_code.'.php';
                // 检查插件文件是否存在，如果存在则验证支付是否成功，否则则返回失败信息
                if (file_exists($plugin_file)) {
                    // 根据支付方式代码创建支付类的对象并调用其响应操作方法
                    include_once $plugin_file;

                    $payment = new $pay_code;
                    $msg = (@$payment->respond()) ? lang('pay_success') : lang('pay_fail');
                } else {
                    $msg = lang('pay_not_exist');
                }
            }
        }

        $this->assign_template();
        $position = $this->assign_ur_here();
        $this->assign('page_title', $position['title']);   // 页面标题
        $this->assign('ur_here', $position['ur_here']); // 当前位置
        $this->assign('page_title', $position['title']);   // 页面标题
        $this->assign('ur_here', $position['ur_here']); // 当前位置
        $this->assign('helps', MainHelper::get_shop_help());      // 网店帮助

        $this->assign('message', $msg);
        $this->assign('shop_url', ecs()->url());

        return $this->display('respond');
    }
}
