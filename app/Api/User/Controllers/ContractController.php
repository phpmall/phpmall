<?php

declare(strict_types=1);

namespace App\Api\User\Controllers;

use App\Api\User\Requests\Contract\ContractSignRequest;
use App\Api\User\Responses\Contract\ContractListResponse;
use App\Api\User\Responses\Contract\ContractResponse;
use App\Api\User\Responses\Contract\ContractSignResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class ContractController extends BaseController
{
    #[OA\Get(path: '/contracts', security: [['bearerAuth' => []]], summary: 'Contract Controller index', tags: ['会员中心'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ContractListResponse::class))]
    public function index(Request $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/contracts/{id}', security: [['bearerAuth' => []]], summary: 'Contract Controller show', tags: ['会员中心'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ContractResponse::class))]
    public function show(int $id): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/contracts/{id}/sign', security: [['bearerAuth' => []]], summary: 'Contract Controller sign', tags: ['会员中心'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ContractSignRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ContractSignResponse::class))]
    public function sign(ContractSignRequest $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/contracts/{id}/download', security: [['bearerAuth' => []]], summary: 'Contract Controller download', tags: ['会员中心'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK')]
    public function download(int $id): JsonResponse
    {
        return $this->success();
    }
}
