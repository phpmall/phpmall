<?php

declare(strict_types=1);

namespace App\Api\Portal\Controllers;

use App\Api\Portal\Requests\Shop\ShopIndexRequest;
use App\Api\Portal\Responses\Shop\ShopListResponse;
use App\Api\Portal\Responses\Shop\ShopResponse;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class ShopController extends BaseController
{
    #[OA\Get(path: '/shops', summary: '店铺列表', security: [[]], tags: ['商城平台'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShopListResponse::class))]
    public function index(ShopIndexRequest $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/shops/{id}', summary: '店铺详情', security: [[]], tags: ['商城平台'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShopResponse::class))]
    public function show(int $id): JsonResponse
    {
        return $this->success();
    }
}
