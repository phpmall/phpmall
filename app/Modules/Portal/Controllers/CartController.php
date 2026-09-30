<?php

declare(strict_types=1);

namespace App\Modules\Portal\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class CartController extends Controller
{
    /**
     * 购物车页面
     */
    public function index(): View
    {
        return view('portal::cart.index');
    }
}
