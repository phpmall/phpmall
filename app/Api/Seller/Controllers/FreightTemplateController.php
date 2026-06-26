<?php

declare(strict_types=1);

namespace App\Api\Seller\Controllers;

use App\Api\Seller\Requests\FreightTemplate\FreightTemplateStoreRequest;
use App\Api\Seller\Requests\FreightTemplate\FreightTemplateUpdateRequest;
use App\Api\Seller\Responses\FreightTemplate\FreightTemplateListResponse;
use App\Api\Seller\Responses\FreightTemplate\FreightTemplateResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class FreightTemplateController extends BaseController
{
    #[OA\Get(path: '/freight-templates', summary: '获取运费模板列表', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: FreightTemplateListResponse::class))]
    public function index(Request $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/freight-templates', summary: '创建运费模板', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: FreightTemplateStoreRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: FreightTemplateResponse::class))]
    public function store(FreightTemplateStoreRequest $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/freight-templates/{id}', summary: '获取运费模板详情', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: '模板ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: FreightTemplateResponse::class))]
    public function show(int $id): JsonResponse
    {
        return $this->success();
    }

    #[OA\Put(path: '/freight-templates/{id}', summary: '更新运费模板', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: '模板ID', in: 'path', required: true)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: FreightTemplateUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: FreightTemplateResponse::class))]
    public function update(FreightTemplateUpdateRequest $request, int $id): JsonResponse
    {
        return $this->success();
    }

    #[OA\Delete(path: '/freight-templates/{id}', summary: '删除运费模板', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: '模板ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK')]
    public function destroy(int $id): JsonResponse
    {
        return $this->success();
    }
}
