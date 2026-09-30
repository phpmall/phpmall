<?php

declare(strict_types=1);

namespace App\Modules\Portal\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Goods\GoodsService;
use Illuminate\Contracts\View\View;
use OpenApi\Attributes as OA;

class HomeController extends Controller
{
    public function __construct(
        protected readonly GoodsService $goodsService,
    ) {}

    /**
     * 商城 PC 首页
     */
    #[OA\Get(path: '/', summary: 'PC 前台商城首页', tags: ['前台-页面展示'])]
    public function index(): View
    {
        $categories = $this->goodsService->getCategoryTree();
        $featured = $this->goodsService->getFeaturedProducts('recommend', 10);
        $hot = $this->goodsService->getFeaturedProducts('hot', 5);

        return view('portal::home', [
            'categories' => $categories,
            'featured' => $featured,
            'hot' => $hot,
        ]);
    }
}
