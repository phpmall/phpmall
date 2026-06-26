<?php

declare(strict_types=1);

namespace App\Api\Portal\Controllers;

use App\Api\Portal\Requests\Banner\BannerIndexRequest;
use App\Api\Portal\Responses\Banner\BannerListResponse;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class BannerController extends BaseController
{
    #[OA\Get(path: '/banners', summary: '轮播图列表', security: [[]], tags: ['商城平台'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: BannerListResponse::class))]
    public function index(BannerIndexRequest $request): JsonResponse
    {
        return $this->success();
    }
}
