<?php

declare(strict_types=1);

namespace App\Api\Portal\Controllers;

use App\Api\Portal\Requests\Product\ProductIndexRequest;
use App\Api\Portal\Responses\Product\ProductListResponse;
use App\Api\Portal\Responses\Product\ProductResponse;
use App\Api\Portal\Responses\Review\ReviewListResponse;
use App\Api\Portal\Responses\Review\ReviewResponse;
use App\Modules\Product\Services\ProductReviewService;
use App\Modules\Product\Services\ProductService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class ProductController extends BaseController
{
    public function __construct(
        private readonly ProductService $productService,
        private readonly ProductReviewService $productReviewService,
    ) {}

    #[OA\Get(path: '/products', summary: '商品列表', security: [[]], tags: ['商城平台'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ProductListResponse::class))]
    public function index(ProductIndexRequest $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/products/{id}', summary: '商品详情', security: [[]], tags: ['商城平台'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ProductResponse::class))]
    public function show(int $id): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/products/recommend', summary: '推荐商品', security: [[]], tags: ['商城平台'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ProductListResponse::class))]
    public function recommend(): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/products/hot', summary: '热销商品', security: [[]], tags: ['商城平台'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ProductListResponse::class))]
    public function hot(): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/products/{id}/reviews', summary: '商品评价列表', security: [[]], tags: ['商城平台'])]
    #[OA\Parameter(name: 'id', description: '商品ID', in: 'path', required: true)]
    #[OA\Parameter(name: 'page', description: '页码', in: 'query', required: false)]
    #[OA\Parameter(name: 'per_page', description: '每页数量', in: 'query', required: false)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ReviewListResponse::class))]
    public function reviews(int $id): JsonResponse
    {
        $product = $this->productService->getRepository()->findById($id);
        if (empty($product)) {
            return $this->error('商品不存在', 404);
        }

        $page = (int) request()->input('page', 1);
        $perPage = (int) request()->input('per_page', 20);

        $reviewPage = $this->productReviewService->paginateByProductId($id, $page, $perPage);
        $stats = $this->productReviewService->getRatingStats($id);

        $reviewItems = [];
        foreach ($reviewPage['data'] ?? [] as $review) {
            $review['images'] = isset($review['images']) ? json_decode($review['images'], true) : [];
            $reviewResponse = ReviewResponse::from($review);
            $reviewItems[] = $reviewResponse->toArray();
        }

        $pagination = [
            'total' => $reviewPage['total'] ?? 0,
            'per_page' => $reviewPage['per_page'] ?? $perPage,
            'current_page' => $reviewPage['current_page'] ?? $page,
            'last_page' => $reviewPage['last_page'] ?? 1,
        ];

        return $this->success([
            'items' => $reviewItems,
            'pagination' => $pagination,
            'summary' => $stats,
        ]);
    }
}
