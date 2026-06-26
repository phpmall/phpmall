<?php

declare(strict_types=1);

namespace App\Api\Seller\Controllers;

use App\Api\Seller\Requests\Merchant\MerchantUpdateRequest;
use App\Api\Seller\Responses\Merchant\MerchantResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class MerchantController extends BaseController
{
    #[OA\Get(path: '/merchant', summary: '获取商家信息', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: MerchantResponse::class))]
    public function index(Request $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Put(path: '/merchant', summary: '更新商家信息', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MerchantUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function update(MerchantUpdateRequest $request): JsonResponse
    {
        return $this->success();
    }
}
