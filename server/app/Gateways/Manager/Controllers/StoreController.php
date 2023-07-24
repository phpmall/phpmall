<?php

declare(strict_types=1);

namespace App\Gateways\Manager\Controllers;

use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
class StoreController extends BaseController
{
    #[OA\Get(path: '/manager/store', summary: '卖家门店', tags: ['门店管理'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function index(): JsonResponse
    {
        return $this->success(['admin::user.index']);
    }
}
