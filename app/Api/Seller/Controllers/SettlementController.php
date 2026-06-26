<?php

declare(strict_types=1);

namespace App\Api\Seller\Controllers;

use App\Api\Seller\Responses\Settlement\SettlementListResponse;
use App\Api\Seller\Responses\Settlement\SettlementResponse;
use App\Api\Seller\Responses\Settlement\SettlementStatementResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class SettlementController extends BaseController
{
    #[OA\Get(path: '/settlements', summary: '获取结算列表', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: SettlementListResponse::class))]
    public function index(Request $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/settlements/{id}', summary: '获取结算详情', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: '结算ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: SettlementResponse::class))]
    public function show(int $id): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/settlements/{id}/statement', summary: '获取结算账单', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: '结算ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: SettlementStatementResponse::class))]
    public function statement(int $id): JsonResponse
    {
        return $this->success();
    }
}
