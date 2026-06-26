<?php

declare(strict_types=1);

namespace App\Api\Seller\Controllers;

use App\Api\Seller\Requests\Distributor\DistributorAuditRequest;
use App\Api\Seller\Responses\Distributor\DistributorListResponse;
use App\Api\Seller\Responses\Distributor\DistributorResponse;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class DistributorController extends BaseController
{
    #[OA\Get(path: '/distributors', summary: '获取分销商列表', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: DistributorListResponse::class))]
    public function index(): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/distributors/{id}', summary: '获取分销商详情', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: '分销商ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: DistributorResponse::class))]
    public function show(int $id): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/distributors/{id}/audit', summary: '审核分销商', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: '分销商ID', in: 'path', required: true)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: DistributorAuditRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function audit(DistributorAuditRequest $request, int $id): JsonResponse
    {
        return $this->success();
    }
}
