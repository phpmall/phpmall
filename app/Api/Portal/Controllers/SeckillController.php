<?php

declare(strict_types=1);

namespace App\Api\Portal\Controllers;

use App\Api\Portal\Requests\Seckill\SeckillIndexRequest;
use App\Api\Portal\Responses\Seckill\SeckillListResponse;
use App\Api\Portal\Responses\Seckill\SeckillResponse;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class SeckillController extends BaseController
{
    #[OA\Get(path: '/seckills', summary: '秒杀活动列表', security: [[]], tags: ['商城平台'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: SeckillListResponse::class))]
    public function index(SeckillIndexRequest $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/seckills/{id}', summary: '秒杀活动详情', security: [[]], tags: ['商城平台'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: SeckillResponse::class))]
    public function show(int $id): JsonResponse
    {
        return $this->success();
    }
}
