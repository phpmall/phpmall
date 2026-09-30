<?php

declare(strict_types=1);

namespace App\Modules\Portal\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Goods\GoodsService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class GoodsController extends Controller
{
    public function __construct(
        protected readonly GoodsService $goodsService,
    ) {}

    /**
     * 商品搜索与分类列表页
     */
    #[OA\Get(path: '/goods', summary: '前台商品列表与搜索页', tags: ['前台-页面展示'])]
    public function index(Request $request): View
    {
        $keyword = (string) $request->query('keyword', '');
        $categoryId = $request->filled('category_id') ? (int) $request->query('category_id') : null;
        $categories = $this->goodsService->getCategoryTree();

        return view('portal::goods.list', [
            'keyword' => $keyword,
            'categoryId' => $categoryId,
            'categories' => $categories,
        ]);
    }

    /**
     * 商品详情页
     */
    #[OA\Get(path: '/goods/{id}', summary: '前台商品详情页', tags: ['前台-页面展示'])]
    public function show(int $id): View
    {
        $detail = $this->goodsService->getDetail($id);

        if (! $detail) {
            abort(404, '商品不存在或已下架');
        }

        return view('portal::goods.detail', [
            'productId' => $id,
            'detail' => $detail,
        ]);
    }
}
