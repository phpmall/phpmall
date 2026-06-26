<?php

declare(strict_types=1);

namespace App\Api\User\Controllers;

use App\Api\User\Requests\Commission\CommissionIndexRequest;
use App\Api\User\Requests\Commission\CommissionWithdrawRequest;
use App\Api\User\Responses\Commission\CommissionListResponse;
use App\Api\User\Responses\Commission\CommissionStatsResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class CommissionController extends BaseController
{
    #[OA\Get(path: '/commissions', security: [['bearerAuth' => []]], summary: 'Commission Controller index', tags: ['会员中心'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: CommissionListResponse::class))]
    public function index(CommissionIndexRequest $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/commissions/stats', security: [['bearerAuth' => []]], summary: 'Commission Controller stats', tags: ['会员中心'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: CommissionStatsResponse::class))]
    public function stats(): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/commissions/withdraw', security: [['bearerAuth' => []]], summary: 'Commission Controller withdraw', tags: ['会员中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: CommissionWithdrawRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function withdraw(CommissionWithdrawRequest $request): JsonResponse
    {
        return $this->success();
    }
}
