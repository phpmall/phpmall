<?php

declare(strict_types=1);

namespace App\Gateways\Admin\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class PermissionController extends BaseController
{
    #[OA\Get(path: '/admin/permission', summary: '系统权限接口', security: [['bearerAuth' => []]], tags: ['资源'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function index(Request $request): JsonResponse
    {
        return $this->success($request->path());
    }
}
