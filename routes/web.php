<?php

declare(strict_types=1);

use App\Http\Controllers\Portal\CartController;
use App\Http\Controllers\Portal\GoodsController;
use App\Http\Controllers\Portal\HomeController;
use App\Http\Controllers\Portal\OrderController;
use Illuminate\Support\Facades\Route;

// PC 前台商城展示与交易页面
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/goods', [GoodsController::class, 'index'])->name('portal.goods.list');
Route::get('/goods/{id}', [GoodsController::class, 'show'])->name('portal.goods.show');
Route::get('/cart', [CartController::class, 'index'])->name('portal.cart.view');

// 订单确认与支付收银
Route::get('/checkout', [OrderController::class, 'checkout'])->name('portal.order.checkout');
Route::get('/pay/{orderNo}', [OrderController::class, 'pay'])->name('portal.order.pay');
Route::get('/orders', [OrderController::class, 'myOrders'])->name('portal.order.my');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';
