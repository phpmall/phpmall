<?php

declare(strict_types=1);

namespace App\Api\Seller\Controllers;

use App\Api\Seller\Requests\MerchantSettlementAccount\MerchantSettlementAccountStoreRequest;
use App\Api\Seller\Requests\MerchantSettlementAccount\MerchantSettlementAccountUpdateRequest;
use App\Api\Seller\Responses\MerchantSettlementAccount\MerchantSettlementAccountListResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class MerchantSettlementAccountController extends BaseController
{
    #[OA\Get(path: '/merchant-settlement-accounts', summary: '获取结算账户列表', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: MerchantSettlementAccountListResponse::class)))]
    public function index(Request $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/merchant-settlement-accounts', summary: '创建结算账户', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MerchantSettlementAccountStoreRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function store(MerchantSettlementAccountStoreRequest $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Put(path: '/merchant-settlement-accounts/{id}', summary: '更新结算账户', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: '账户ID', in: 'path', required: true)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MerchantSettlementAccountUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function update(MerchantSettlementAccountUpdateRequest $request, int $id): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/merchant-settlement-accounts/{id}/default', summary: '设置默认结算账户', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: '账户ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK')]
    public function setDefault(int $id): JsonResponse
    {
        return $this->success();
    }
}
