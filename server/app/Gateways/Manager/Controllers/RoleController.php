<?php

declare(strict_types=1);

namespace App\Gateways\Manager\Controllers;

use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class RoleController extends BaseController
{
    #[OA\Get(path: '/admin/role', summary: '全部角色', tags: ['角色管理'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function index(): JsonResponse
    {
        return $this->success(['admin::user.index']);
    }
}
