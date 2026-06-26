<?php

declare(strict_types=1);

namespace App\Api\Seller\Controllers;

use App\Api\Seller\Requests\DistributionConfig\DistributionConfigUpdateRequest;
use App\Api\Seller\Responses\DistributionConfig\DistributionConfigResponse;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class DistributionConfigController extends BaseController
{
    #[OA\Get(path: '/distribution-config/{id}', summary: '获取分销配置', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: '配置ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: DistributionConfigResponse::class))]
    public function show(int $id): JsonResponse
    {
        return $this->success();
    }

    #[OA\Put(path: '/distribution-config/{id}', summary: '更新分销配置', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: '配置ID', in: 'path', required: true)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: DistributionConfigUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function update(DistributionConfigUpdateRequest $request, int $id): JsonResponse
    {
        return $this->success();
    }
}
