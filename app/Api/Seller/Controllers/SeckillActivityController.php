<?php

declare(strict_types=1);

namespace App\Api\Seller\Controllers;

use App\Api\Seller\Requests\SeckillActivity\SeckillActivityIndexRequest;
use App\Api\Seller\Requests\SeckillActivity\SeckillActivityStoreRequest;
use App\Api\Seller\Requests\SeckillActivity\SeckillActivityUpdateRequest;
use App\Api\Seller\Responses\SeckillActivity\SeckillActivityListResponse;
use App\Api\Seller\Responses\SeckillActivity\SeckillActivityResponse;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class SeckillActivityController extends BaseController
{
    #[OA\Get(path: '/seckill-activities', summary: '获取秒杀活动列表', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'status', description: '状态', in: 'query', required: false, schema: new OA\Schema(type: 'integer', nullable: true))]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: false, schema: new OA\Schema(type: 'integer', example: 1))]
    #[OA\Parameter(name: 'per_page', description: '每页数量', in: 'query', required: false, schema: new OA\Schema(type: 'integer', example: 20))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: SeckillActivityListResponse::class))]
    public function index(SeckillActivityIndexRequest $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/seckill-activities', summary: '创建秒杀活动', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SeckillActivityStoreRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: SeckillActivityResponse::class))]
    public function store(SeckillActivityStoreRequest $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/seckill-activities/{id}', summary: '获取秒杀活动详情', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: '活动ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: SeckillActivityResponse::class))]
    public function show(int $id): JsonResponse
    {
        return $this->success();
    }

    #[OA\Put(path: '/seckill-activities/{id}', summary: '更新秒杀活动', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: '活动ID', in: 'path', required: true)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SeckillActivityUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: SeckillActivityResponse::class))]
    public function update(SeckillActivityUpdateRequest $request, int $id): JsonResponse
    {
        return $this->success();
    }

    #[OA\Delete(path: '/seckill-activities/{id}', summary: '删除秒杀活动', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: '活动ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK')]
    public function destroy(int $id): JsonResponse
    {
        return $this->success();
    }
}
