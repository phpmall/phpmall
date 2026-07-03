<?php

declare(strict_types=1);

namespace App\Api\Seller\Controllers;

use App\Api\Seller\Requests\Shipment\ShipmentBatchShipRequest;
use App\Api\Seller\Requests\Shipment\ShipmentIndexRequest;
use App\Api\Seller\Requests\Shipment\ShipmentStoreRequest;
use App\Api\Seller\Responses\Shipment\ShipmentListResponse;
use App\Exceptions\NotImplementedException;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class ShipmentController extends BaseController
{
    #[OA\Get(path: '/shipments', summary: '获取发货单列表', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'status', description: '发货单状态', in: 'query', required: false, schema: new OA\Schema(type: 'integer', nullable: true))]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: false, schema: new OA\Schema(type: 'integer', example: 1))]
    #[OA\Parameter(name: 'per_page', description: '每页数量', in: 'query', required: false, schema: new OA\Schema(type: 'integer', example: 20))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShipmentListResponse::class))]
    public function index(ShipmentIndexRequest $request): JsonResponse
    {
        throw new NotImplementedException('TODO: implement '.__CLASS__.'::'.__FUNCTION__);
    }

    #[OA\Post(path: '/shipments', summary: '创建发货单', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ShipmentStoreRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function store(ShipmentStoreRequest $request): JsonResponse
    {
        throw new NotImplementedException('TODO: implement '.__CLASS__.'::'.__FUNCTION__);
    }

    #[OA\Post(path: '/shipments/batch', summary: '批量发货', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ShipmentBatchShipRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function batchShip(ShipmentBatchShipRequest $request): JsonResponse
    {
        throw new NotImplementedException('TODO: implement '.__CLASS__.'::'.__FUNCTION__);
    }
}
