<?php

declare(strict_types=1);

namespace App\Api\Seller\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class MerchantApplicationController extends BaseController
{
    #[OA\Post(path: '/merchant-application/apply', summary: '提交商家入驻申请', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent)]
    #[OA\Response(response: 200, description: 'OK')]
    public function apply(Request $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/merchant-application/status', summary: '获取入驻申请状态', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function status(): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/merchant-application/resubmit', summary: '重新提交入驻申请', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent)]
    #[OA\Response(response: 200, description: 'OK')]
    public function resubmit(Request $request): JsonResponse
    {
        return $this->success();
    }
}
