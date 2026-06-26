<?php

declare(strict_types=1);

namespace App\Api\Seller\Controllers;

use App\Api\Seller\Responses\Waybill\WaybillListResponse;
use App\Api\Seller\Responses\Waybill\WaybillPrintResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class WaybillController extends BaseController
{
    #[OA\Get(path: '/waybills', summary: '获取运单列表', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: WaybillListResponse::class))]
    public function index(Request $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/waybills/{id}/print', summary: '打印运单', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: '运单ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: WaybillPrintResponse::class))]
    public function print(int $id): JsonResponse
    {
        return $this->success();
    }
}
