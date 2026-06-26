<?php

declare(strict_types=1);

namespace App\Api\Supplier\Controllers;

use App\Api\Supplier\Requests\Contract\SignRequest;
use App\Api\Supplier\Responses\Contract\ContractListResponse;
use App\Api\Supplier\Responses\Contract\ContractResponse;
use App\Api\Supplier\Responses\Contract\ContractSignResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class ContractController extends BaseController
{
    #[OA\Get(path: '/contracts', summary: '合同列表', tags: ['供应商中心'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ContractListResponse::class))]
    public function index(Request $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/contracts/{id}', summary: '合同详情', tags: ['供应商中心'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ContractResponse::class))]
    public function show(int $id): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/contracts/{id}/sign', security: [['bearerAuth' => []]], summary: '合同签署', tags: ['供应商中心'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SignRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ContractSignResponse::class))]
    public function sign(SignRequest $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/contracts/{id}/download', security: [['bearerAuth' => []]], summary: '合同下载', tags: ['供应商中心'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK')]
    public function download(int $id): JsonResponse
    {
        return $this->success();
    }
}
