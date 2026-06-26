<?php

declare(strict_types=1);

namespace App\Api\Seller\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class DistributionConfigController extends BaseController
{
    #[OA\Get(path: '/distribution-config/{id}', summary: '获取分销配置', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: '配置ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK')]
    public function show(int $id): JsonResponse
    {
        return $this->success();
    }

    #[OA\Put(path: '/distribution-config/{id}', summary: '更新分销配置', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: '配置ID', in: 'path', required: true)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent)]
    #[OA\Response(response: 200, description: 'OK')]
    public function update(Request $request, int $id): JsonResponse
    {
        return $this->success();
    }
}
