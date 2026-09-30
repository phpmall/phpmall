<?php

declare(strict_types=1);

namespace App\Modules\Portal\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use OpenApi\Attributes as OA;

class CartController extends Controller
{
    /**
     * 购物车页面
     */
    #[OA\Get(path: '/cart', summary: '前台购物车页面', tags: ['前台-页面展示'])]
    public function index(): View
    {
        return view('portal::cart.index');
    }
}
