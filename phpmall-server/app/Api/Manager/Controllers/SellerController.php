<?php

declare(strict_types=1);

namespace App\Api\Manager\Controllers;

use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class SellerController extends BaseController
{
    #[OA\Get(path: '/admin/seller', summary: '全部卖家', tags: ['卖家管理'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function index(): JsonResponse
    {
        return $this->success(['admin::user.index']);
    }
}
