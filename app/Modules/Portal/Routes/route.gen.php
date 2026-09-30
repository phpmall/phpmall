<?php

declare(strict_types=1);

use App\Modules\Portal\Controllers\CartController;
use App\Modules\Portal\Controllers\GoodsController;
use App\Modules\Portal\Controllers\HomeController;
use App\Modules\Portal\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    // PC 前台商城展示与交易页面
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/goods', [GoodsController::class, 'index'])->name('portal.goods.list');
    Route::get('/goods/{id}', [GoodsController::class, 'show'])->name('portal.goods.show');
    Route::get('/cart', [CartController::class, 'index'])->name('portal.cart.view');

    // 订单确认与支付收银
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('portal.order.checkout');
    Route::get('/pay/{orderNo}', [OrderController::class, 'pay'])->name('portal.order.pay');
    Route::get('/orders', [OrderController::class, 'myOrders'])->name('portal.order.my');
});
