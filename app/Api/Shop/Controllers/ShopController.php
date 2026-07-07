<?php

declare(strict_types=1);

namespace App\Api\Shop\Controllers;

use App\Api\Shop\Requests\Shop\ShopProductsRequest;
use App\Api\Shop\Requests\Shop\ShopReviewsRequest;
use App\Api\Shop\Responses\Review\ReviewResponse;
use App\Api\Shop\Responses\Shop\ShopProductListResponse;
use App\Api\Shop\Responses\Shop\ShopResponse;
use App\Api\Shop\Responses\Shop\ShopReviewListResponse;
use App\Modules\Product\Services\ProductReviewService;
use App\Modules\Product\Services\ProductService;
use App\Modules\Shop\Services\ShopService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class ShopController extends BaseController
{
    public function __construct(
        private readonly ShopService $shopService,
        private readonly ProductService $productService,
        private readonly ProductReviewService $reviewService,
    ) {}

    #[OA\Get(path: '/shops/{id}', summary: '店铺详情', security: [[]], tags: ['店铺'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShopResponse::class))]
    public function show(int $id): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/shops/{id}/products', summary: '店铺商品列表', security: [[]], tags: ['店铺'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShopProductListResponse::class))]
    public function products(int $id, ShopProductsRequest $request): JsonResponse
    {
        $shop = $this->shopService->findById($id);
        if ($shop === null) {
            return $this->error('店铺不存在', 404);
        }

        $params = $request->validated();
        $result = $this->productService->paginateByShopId($id, $params);

        $response = new ShopProductListResponse;
        $response->setItems($result['items']);
        $response->setPagination($result['pagination']);

        return $this->success($response->toArray());
    }

    #[OA\Get(path: '/shops/{id}/reviews', summary: '店铺评价列表', security: [[]], tags: ['店铺'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShopReviewListResponse::class))]
    public function reviews(int $id, ShopReviewsRequest $request): JsonResponse
    {
        $shop = $this->shopService->findById($id);
        if ($shop === null) {
            return $this->error('店铺不存在', 404);
        }

        $params = $request->validated();
        $page = (int) ($params['page'] ?? 1);
        $perPage = (int) ($params['per_page'] ?? 20);
        $filters = [
            'rating' => $params['rating'] ?? null,
            'has_image' => $params['has_image'] ?? null,
        ];

        $reviewPage = $this->reviewService->paginateByShopId($id, $filters, $page, $perPage);
        $stats = $this->reviewService->getRatingStatsByShopId($id);

        $reviewItems = [];
        foreach ($reviewPage['data'] ?? [] as $review) {
            $review['images'] = isset($review['images']) ? json_decode($review['images'], true) : [];
            $reviewItems[] = ReviewResponse::from($review)->toArray();
        }

        $pagination = [
            'total' => (int) ($reviewPage['total'] ?? 0),
            'per_page' => (int) ($reviewPage['per_page'] ?? $perPage),
            'current_page' => (int) ($reviewPage['current_page'] ?? $page),
            'last_page' => (int) ($reviewPage['last_page'] ?? 1),
        ];

        return $this->success([
            'items' => $reviewItems,
            'pagination' => $pagination,
            'summary' => $stats,
        ]);
    }
}
