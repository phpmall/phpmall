<?php

declare(strict_types=1);

namespace App\Api\Seller\Controllers;

use App\Api\Seller\Requests\Contract\ContractSignRequest;
use App\Api\Seller\Responses\Contract\ContractDownloadResponse;
use App\Api\Seller\Responses\Contract\ContractListResponse;
use App\Api\Seller\Responses\Contract\ContractResponse;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class ContractController extends BaseController
{
    #[OA\Get(path: '/contracts', summary: '获取合同列表', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ContractListResponse::class))]
    public function index(): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/contracts/{id}', summary: '获取合同详情', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: '合同ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ContractResponse::class))]
    public function show(int $id): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/contracts/{id}/sign', summary: '签署合同', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: '合同ID', in: 'path', required: true)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ContractSignRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function sign(ContractSignRequest $request, int $id): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/contracts/{id}/download', summary: '下载合同', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: '合同ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ContractDownloadResponse::class))]
    public function download(int $id): JsonResponse
    {
        return $this->success();
    }
}
