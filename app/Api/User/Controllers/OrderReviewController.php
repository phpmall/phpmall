<?php

declare(strict_types=1);

namespace App\Api\User\Controllers;

use App\Api\User\Requests\OrderReview\OrderReviewIndexRequest;
use App\Api\User\Requests\OrderReview\OrderReviewStoreRequest;
use App\Api\User\Requests\OrderReview\OrderReviewUpdateRequest;
use App\Api\User\Responses\OrderReview\OrderReviewListResponse;
use App\Api\User\Responses\OrderReview\OrderReviewResponse;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class OrderReviewController extends BaseController
{
    #[OA\Get(path: '/order-reviews', security: [['bearerAuth' => []]], summary: 'Order Review Controller index', tags: ['会员中心'])]
    #[OA\Parameter(name: 'order_id', in: 'query', description: '订单ID', schema: new OA\Schema(type: 'integer', nullable: true))]
    #[OA\Parameter(name: 'page', in: 'query', description: '页码', schema: new OA\Schema(type: 'integer', example: 1))]
    #[OA\Parameter(name: 'per_page', in: 'query', description: '每页数量', schema: new OA\Schema(type: 'integer', example: 20))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: OrderReviewListResponse::class))]
    public function index(OrderReviewIndexRequest $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/order-reviews', security: [['bearerAuth' => []]], summary: 'Order Review Controller store', tags: ['会员中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderReviewStoreRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: OrderReviewResponse::class))]
    public function store(OrderReviewStoreRequest $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/order-reviews/{id}', security: [['bearerAuth' => []]], summary: 'Order Review Controller show', tags: ['会员中心'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: OrderReviewResponse::class))]
    public function show(int $id): JsonResponse
    {
        return $this->success();
    }

    #[OA\Put(path: '/order-reviews/{id}', security: [['bearerAuth' => []]], summary: 'Order Review Controller update', tags: ['会员中心'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderReviewUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: OrderReviewResponse::class))]
    public function update(OrderReviewUpdateRequest $request, int $id): JsonResponse
    {
        return $this->success();
    }
}
