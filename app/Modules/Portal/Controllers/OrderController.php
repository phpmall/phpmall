<?php

declare(strict_types=1);

namespace App\Modules\Portal\Controllers;

use App\Domains\Order\Models\Order;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class OrderController extends Controller
{
    /**
     * 结算确认下单页
     */
    #[OA\Get(path: '/checkout', summary: '订单确认结算页', tags: ['前台-页面展示'])]
    public function checkout(Request $request): View
    {
        $skuId = $request->query('sku_id');
        $quantity = $request->query('quantity', '1');

        return view('portal::order.checkout', [
            'directSkuId' => $skuId ? (int) $skuId : null,
            'directQuantity' => (int) $quantity,
        ]);
    }

    /**
     * 收银台支付页
     */
    #[OA\Get(path: '/pay/{orderNo}', summary: '收银台在线支付页', tags: ['前台-页面展示'])]
    public function pay(string $orderNo): View
    {
        $order = Order::query()->where('order_no', $orderNo)->first();

        if (! $order) {
            abort(404, '订单不存在');
        }

        return view('portal::order.pay', [
            'order' => $order,
        ]);
    }

    /**
     * 前台用户我的订单中心
     */
    #[OA\Get(path: '/orders', summary: '前台用户订单中心', tags: ['前台-页面展示'])]
    public function myOrders(Request $request): View
    {
        $status = $request->query('status');

        return view('portal::order.index', [
            'currentStatus' => $status,
        ]);
    }
}
