<?php

declare(strict_types=1);

namespace App\Api\Seller\Controllers;

use App\Api\Seller\Requests\Data\DataExportRequest;
use App\Api\Seller\Responses\Data\DataOrdersResponse;
use App\Api\Seller\Responses\Data\DataOverviewResponse;
use App\Api\Seller\Responses\Data\DataProductsResponse;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class DataController extends BaseController
{
    #[OA\Get(path: '/data/overview', summary: '数据概览', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: DataOverviewResponse::class))]
    public function overview(): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/data/orders', summary: '订单数据', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: DataOrdersResponse::class))]
    public function orders(): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/data/products', summary: '商品数据', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: DataProductsResponse::class))]
    public function products(): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/data/export', summary: '数据导出', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: DataExportRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function export(DataExportRequest $request): JsonResponse
    {
        return $this->success();
    }
}
