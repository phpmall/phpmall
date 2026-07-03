<?php

declare(strict_types=1);

namespace App\Api\Shop\Controllers;

use App\Api\Shop\Requests\Store\StoreIndexRequest;
use App\Api\Shop\Requests\Store\StoreNearbyRequest;
use App\Api\Shop\Responses\Store\StoreListResponse;
use App\Api\Shop\Responses\Store\StoreNearbyResponse;
use App\Api\Shop\Responses\Store\StoreResponse;
use App\Exceptions\NotImplementedException;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class StoreController extends BaseController
{
    #[OA\Get(path: '/stores', summary: '门店列表', security: [[]], tags: ['店铺'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: StoreListResponse::class))]
    public function index(StoreIndexRequest $request): JsonResponse
    {
        throw new NotImplementedException('TODO: implement '.__CLASS__.'::'.__FUNCTION__);
    }

    #[OA\Get(path: '/stores/{id}', summary: '门店详情', security: [[]], tags: ['店铺'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: StoreResponse::class))]
    public function show(int $id): JsonResponse
    {
        throw new NotImplementedException('TODO: implement '.__CLASS__.'::'.__FUNCTION__);
    }

    #[OA\Get(path: '/stores/nearby', summary: '附近门店', security: [[]], tags: ['店铺'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: StoreNearbyResponse::class))]
    public function nearby(StoreNearbyRequest $request): JsonResponse
    {
        throw new NotImplementedException('TODO: implement '.__CLASS__.'::'.__FUNCTION__);
    }
}
