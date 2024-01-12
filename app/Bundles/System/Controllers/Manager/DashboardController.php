<?php

declare(strict_types=1);

namespace App\Bundles\System\Controllers\Manager;

use App\Api\Manager\Controllers\BaseController;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class DashboardController extends BaseController
{
    #[OA\Get(path: 'dashboard', summary: '运营首页', tags: ['运营'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function index(): JsonResponse
    {
        // 商家 店铺 门店 商品 订单 买家
        // 平台-商品设置：无需审核 平台审核 店铺审核
        // 店铺-商品设置：无需审核 人工审核
        // 商品：优先按平台，再按照店铺审核
        return $this->success(['admin::dashboard.index']);
    }
}
