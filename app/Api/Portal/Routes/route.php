<?php

declare(strict_types=1);

use App\Api\Portal\Controllers\CartController;
use App\Api\Portal\Controllers\GoodsController;
use App\Api\Portal\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::prefix('portal')->as('api.portal.')->group(function () {
    // 1. 商品与分类公共接口（无需登录）
    Route::get('goods', [GoodsController::class, 'search'])->name('goods.search');
    Route::get('goods/featured', [GoodsController::class, 'featured'])->name('goods.featured');
    Route::get('goods/{id}', [GoodsController::class, 'show'])->name('goods.show');
    Route::get('categories', [GoodsController::class, 'categories'])->name('categories');

    // 2. 购物车交互接口
    Route::get('cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('cart', [CartController::class, 'store'])->name('cart.store');
    Route::put('cart/{id}', [CartController::class, 'updateQuantity'])->name('cart.update');
    Route::post('cart/select', [CartController::class, 'select'])->name('cart.select');
    Route::post('cart/remove', [CartController::class, 'destroy'])->name('cart.destroy');

    // 3. 订单交易与结算接口
    Route::post('order/preview', [OrderController::class, 'preview'])->name('order.preview');
    Route::post('order', [OrderController::class, 'store'])->name('order.store');
    Route::get('order', [OrderController::class, 'index'])->name('order.index');
    Route::get('order/{id}', [OrderController::class, 'show'])->name('order.show');
    Route::post('order/{id}/cancel', [OrderController::class, 'cancel'])->name('order.cancel');
    Route::post('order/{id}/confirm-receipt', [OrderController::class, 'confirmReceipt'])->name('order.confirm_receipt');
});
