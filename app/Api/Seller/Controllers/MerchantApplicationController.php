<?php

declare(strict_types=1);

namespace App\Api\Seller\Controllers;

use App\Api\Seller\Requests\MerchantApplication\MerchantApplicationApplyRequest;
use App\Api\Seller\Requests\MerchantApplication\MerchantApplicationResubmitRequest;
use App\Api\Seller\Responses\MerchantApplication\MerchantApplicationStatusResponse;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class MerchantApplicationController extends BaseController
{
    #[OA\Post(path: '/merchant-application/apply', summary: '提交商家入驻申请', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MerchantApplicationApplyRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function apply(MerchantApplicationApplyRequest $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/merchant-application/status', summary: '获取入驻申请状态', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: MerchantApplicationStatusResponse::class))]
    public function status(): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/merchant-application/resubmit', summary: '重新提交入驻申请', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MerchantApplicationResubmitRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function resubmit(MerchantApplicationResubmitRequest $request): JsonResponse
    {
        return $this->success();
    }
}
