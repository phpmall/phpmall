<?php

declare(strict_types=1);

namespace App\Api\Seller\Controllers;

use App\Api\Seller\Requests\Withdraw\WithdrawStoreRequest;
use App\Api\Seller\Responses\Withdraw\WithdrawListResponse;
use App\Api\Seller\Responses\Withdraw\WithdrawResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class WithdrawController extends BaseController
{
    #[OA\Get(path: '/withdraws', summary: '获取提现记录列表', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: WithdrawListResponse::class))]
    public function index(Request $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/withdraws', summary: '申请提现', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: WithdrawStoreRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: WithdrawResponse::class))]
    public function store(WithdrawStoreRequest $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/withdraws/{id}', summary: '获取提现详情', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: '提现ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: WithdrawResponse::class))]
    public function show(int $id): JsonResponse
    {
        return $this->success();
    }
}
