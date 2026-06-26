<?php

declare(strict_types=1);

namespace App\Api\Seller\Controllers;

use App\Api\Seller\Requests\Promotion\PromotionStoreRequest;
use App\Api\Seller\Requests\Promotion\PromotionUpdateRequest;
use App\Api\Seller\Responses\Promotion\PromotionListResponse;
use App\Api\Seller\Responses\Promotion\PromotionResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class PromotionController extends BaseController
{
    #[OA\Get(path: '/promotions', summary: '获取促销活动列表', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: PromotionListResponse::class))]
    public function index(Request $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/promotions', summary: '创建促销活动', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: PromotionStoreRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: PromotionResponse::class))]
    public function store(PromotionStoreRequest $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/promotions/{id}', summary: '获取促销活动详情', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: '活动ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: PromotionResponse::class))]
    public function show(int $id): JsonResponse
    {
        return $this->success();
    }

    #[OA\Put(path: '/promotions/{id}', summary: '更新促销活动', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: '活动ID', in: 'path', required: true)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: PromotionUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: PromotionResponse::class))]
    public function update(PromotionUpdateRequest $request, int $id): JsonResponse
    {
        return $this->success();
    }

    #[OA\Delete(path: '/promotions/{id}', summary: '删除促销活动', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: '活动ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK')]
    public function destroy(int $id): JsonResponse
    {
        return $this->success();
    }
}
