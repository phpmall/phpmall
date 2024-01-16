<?php

declare(strict_types=1);

namespace App\Bundles\User\Controllers\Manager;

use App\Api\Manager\Controllers\BaseController;
use App\Bundles\User\Responses\AddressResponse;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class UserAddressController extends BaseController
{
    #[OA\Get(path: 'userAddress', summary: '买家收货地址', tags: ['买家收货地址'])]
    #[OA\Parameter(name: 'userId', description: '用户ID', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: AddressResponse::class))]
    public function index(): JsonResponse
    {
        return $this->success(['admin::user.index']);
    }
}
