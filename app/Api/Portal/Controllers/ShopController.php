<?php

declare(strict_types=1);

namespace App\Api\Portal\Controllers;

use App\Api\Portal\Requests\Shop\ShopIndexRequest;
use App\Api\Portal\Responses\Review\ReviewResponse;
use App\Api\Portal\Responses\Shop\ShopHomeResponse;
use App\Api\Portal\Responses\Shop\ShopListResponse;
use App\Api\Portal\Responses\Shop\ShopResponse;
use App\Modules\Product\Services\ProductCategoryService;
use App\Modules\Product\Services\ProductReviewService;
use App\Modules\Product\Services\ProductService;
use App\Modules\Shop\Services\ShopService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class ShopController extends BaseController
{
    public function __construct(
        private readonly ShopService $shopService,
        private readonly ProductCategoryService $categoryService,
        private readonly ProductService $productService,
        private readonly ProductReviewService $reviewService,
    ) {}

    #[OA\Get(path: '/shops', summary: '店铺列表', security: [[]], tags: ['商城平台'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShopListResponse::class))]
    public function index(ShopIndexRequest $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/shops/{id}/home', summary: '店铺首页', security: [[]], tags: ['商城平台'])]
    #[OA\Parameter(name: 'id', description: '店铺ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShopHomeResponse::class))]
    public function home(int $id): JsonResponse
    {
        $shop = $this->shopService->findById($id);
        if ($shop === null) {
            return $this->error('店铺不存在', 404);
        }

        $categories = $this->categoryService->getTree();
        $products = $this->productService->paginateByShopId($id, ['page' => 1, 'per_page' => 10]);

        $reviewPage = $this->reviewService->paginateByShopId($id, [], 1, 10);
        $reviews = [];
        foreach ($reviewPage['data'] ?? [] as $review) {
            $review['images'] = isset($review['images']) ? json_decode($review['images'], true) : [];
            $reviews[] = ReviewResponse::from($review)->toArray();
        }

        $response = new ShopHomeResponse;
        $response->setShop($shop);
        $response->setCategories($categories);
        $response->setProducts($products['items']);
        $response->setReviews([
            'items' => $reviews,
            'pagination' => [
                'total' => (int) ($reviewPage['total'] ?? 0),
                'per_page' => (int) ($reviewPage['per_page'] ?? 10),
                'current_page' => (int) ($reviewPage['current_page'] ?? 1),
                'last_page' => (int) ($reviewPage['last_page'] ?? 1),
            ],
        ]);

        return $this->success($response->toArray());
    }

    #[OA\Get(path: '/shops/{id}', summary: '店铺详情', security: [[]], tags: ['商城平台'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShopResponse::class))]
    public function show(int $id): JsonResponse
    {
        return $this->success();
    }
}
