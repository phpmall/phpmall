<?php

declare(strict_types=1);

namespace App\Api\Portal\Controllers;

use App\Api\Portal\Requests\Marketing\MarketingIndexRequest;
use App\Api\Portal\Responses\Marketing\MarketingCurrentResponse;
use App\Api\Portal\Responses\Marketing\MarketingListResponse;
use App\Api\Portal\Responses\Marketing\MarketingUpcomingResponse;
use App\Exceptions\NotImplementedException;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class MarketingController extends BaseController
{
    #[OA\Get(path: '/marketing', summary: '营销活动列表', security: [[]], tags: ['商城平台'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: MarketingListResponse::class))]
    public function index(MarketingIndexRequest $request): JsonResponse
    {
        throw new NotImplementedException('TODO: implement '.__CLASS__.'::'.__FUNCTION__);
    }

    #[OA\Get(path: '/marketing/current', summary: '当前营销活动', security: [[]], tags: ['商城平台'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: MarketingCurrentResponse::class))]
    public function current(): JsonResponse
    {
        throw new NotImplementedException('TODO: implement '.__CLASS__.'::'.__FUNCTION__);
    }

    #[OA\Get(path: '/marketing/upcoming', summary: '即将开始营销活动', security: [[]], tags: ['商城平台'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: MarketingUpcomingResponse::class))]
    public function upcoming(): JsonResponse
    {
        throw new NotImplementedException('TODO: implement '.__CLASS__.'::'.__FUNCTION__);
    }
}
