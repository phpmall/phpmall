<?php

declare(strict_types=1);

namespace App\Api\Manager\Controllers;

use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class UserAddressController extends BaseController
{
    #[OA\Get(path: '/api/admin/userAddress', summary: '买家收货地址', tags: ['买家收货地址'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function index(): JsonResponse
    {
        return $this->success(['admin::user.index']);
    }
}
