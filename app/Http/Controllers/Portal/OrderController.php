<?php

declare(strict_types=1);

namespace App\Http\Controllers\Portal;

use App\Domains\Order\Models\Order;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * 结算确认下单页
     */
    public function checkout(Request $request): View
    {
        $skuId = $request->query('sku_id');
        $quantity = $request->query('quantity', '1');

        return view('portal.order.checkout', [
            'directSkuId' => $skuId ? (int) $skuId : null,
            'directQuantity' => (int) $quantity,
        ]);
    }

    /**
     * 收银台支付页
     */
    public function pay(string $orderNo): View
    {
        $order = Order::query()->where('order_no', $orderNo)->first();

        if (! $order) {
            abort(404, '订单不存在');
        }

        return view('portal.order.pay', [
            'order' => $order,
        ]);
    }

    /**
     * 前台用户我的订单中心
     */
    public function myOrders(Request $request): View
    {
        $status = $request->query('status');

        return view('portal.order.index', [
            'currentStatus' => $status,
        ]);
    }
}
