<?php

declare(strict_types=1);

namespace App\Modules\Web\Controllers;

use App\Helpers\CommonHelper;
use App\Helpers\GoodsHelper;
use App\Helpers\MainHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReceiveController extends BaseController
{
    public function index(Request $request)
    {
        $action = $request->get('act');
        // 取得参数
        $order_id = ! empty($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;  // 订单号
        $consignee = ! empty($_REQUEST['con']) ? rawurldecode(trim($_REQUEST['con'])) : ''; // 收货人

        // 查询订单信息
        $order = DB::table('order_info')
            ->where('order_id', $order_id)
            ->first();
        $order = $order ? (array) $order : [];

        if (empty($order)) {
            $msg = lang('order_not_exists');
        } // 检查订单
        elseif ($order['shipping_status'] === SS_RECEIVED) {
            $msg = lang('order_already_received');
        } elseif ($order['shipping_status'] != SS_SHIPPED) {
            $msg = lang('order_invalid');
        } elseif ($order['consignee'] != $consignee) {
            $msg = lang('order_invalid');
        } else {
            // 修改订单发货状态为“确认收货”
            DB::table('order_info')
                ->where('order_id', $order_id)
                ->update(['shipping_status' => SS_RECEIVED]);

            // 记录日志
            CommonHelper::order_action($order['order_sn'], $order['order_status'], SS_RECEIVED, $order['pay_status'], '', lang('buyer'));

            $msg = lang('act_ok');
        }

        $this->assign_template();
        $position = $this->assign_ur_here();
        $this->assign('page_title', $position['title']);    // 页面标题
        $this->assign('ur_here', $position['ur_here']);  // 当前位置

        $this->assign('categories', GoodsHelper::get_categories_tree()); // 分类树
        $this->assign('helps', MainHelper::get_shop_help());       // 网店帮助

        $this->assign_dynamic('receive');

        $this->assign('msg', $msg);

        return $this->display('receive');
    }
}
