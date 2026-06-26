<?php

declare(strict_types=1);

namespace App\Api\User\Controllers;

use App\Api\User\Requests\Points\PointsExchangeRequest;
use App\Api\User\Requests\Points\PointsIndexRequest;
use App\Api\User\Responses\Points\PointsHistoryListResponse;
use App\Api\User\Responses\Points\PointsResponse;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class PointsController extends BaseController
{
    #[OA\Get(path: '/points', security: [['bearerAuth' => []]], summary: 'Points Controller index', tags: ['会员中心'])]
    #[OA\Parameter(name: 'page', in: 'query', description: '页码', schema: new OA\Schema(type: 'integer', example: 1))]
    #[OA\Parameter(name: 'per_page', in: 'query', description: '每页数量', schema: new OA\Schema(type: 'integer', example: 20))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: PointsResponse::class))]
    public function index(PointsIndexRequest $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/points/history', security: [['bearerAuth' => []]], summary: 'Points Controller history', tags: ['会员中心'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: PointsHistoryListResponse::class))]
    public function history(): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/points/exchange', security: [['bearerAuth' => []]], summary: 'Points Controller exchange', tags: ['会员中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: PointsExchangeRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function exchange(PointsExchangeRequest $request): JsonResponse
    {
        return $this->success();
    }
}
