<?php

declare(strict_types=1);

namespace App\Gateways\Manager\Controllers;

use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class PermissionController extends BaseController
{
    #[OA\Get(path: '/admin/permission', summary: '全部权限', tags: ['权限管理'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function index(): JsonResponse
    {
        return $this->success(['admin::user.index']);
    }
}
