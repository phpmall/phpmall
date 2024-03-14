<?php

declare(strict_types=1);

namespace App\Bundles\UMS\Controllers\Admin;

use App\Api\Admin\Controllers\BaseController;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class AddressController extends BaseController
{
    #[OA\Get(path: '/address', summary: '买家收货地址', tags: ['买家收货地址'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function index(): JsonResponse
    {
        return $this->success(['admin::user.index']);
    }
}
