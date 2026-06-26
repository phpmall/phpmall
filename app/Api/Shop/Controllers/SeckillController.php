<?php

declare(strict_types=1);

namespace App\Api\Shop\Controllers;

use App\Api\Shop\Requests\Seckill\SeckillIndexRequest;
use App\Api\Shop\Responses\Seckill\SeckillListResponse;
use App\Api\Shop\Responses\Seckill\SeckillResponse;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class SeckillController extends BaseController
{
    #[OA\Get(path: '/seckills', summary: '秒杀列表', security: [[]], tags: ['店铺'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: SeckillListResponse::class))]
    public function index(SeckillIndexRequest $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/seckills/{id}', summary: '秒杀详情', security: [[]], tags: ['店铺'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: SeckillResponse::class))]
    public function show(int $id): JsonResponse
    {
        return $this->success();
    }
}
