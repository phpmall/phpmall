<?php

declare(strict_types=1);

namespace App\Api\Seller\Controllers;

use App\Api\Seller\Requests\MerchantQualification\MerchantQualificationUpdateRequest;
use App\Api\Seller\Requests\MerchantQualification\MerchantQualificationUploadRequest;
use App\Api\Seller\Responses\MerchantQualification\MerchantQualificationListResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class MerchantQualificationController extends BaseController
{
    #[OA\Get(path: '/merchant-qualifications', summary: '获取商家资质列表', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: MerchantQualificationListResponse::class)))]
    public function index(Request $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/merchant-qualifications/upload', summary: '上传商家资质', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MerchantQualificationUploadRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function upload(MerchantQualificationUploadRequest $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Put(path: '/merchant-qualifications/{id}', summary: '更新商家资质', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: '资质ID', in: 'path', required: true)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MerchantQualificationUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function update(MerchantQualificationUpdateRequest $request, int $id): JsonResponse
    {
        return $this->success();
    }

    #[OA\Delete(path: '/merchant-qualifications/{id}', summary: '删除商家资质', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: '资质ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK')]
    public function delete(int $id): JsonResponse
    {
        return $this->success();
    }
}
