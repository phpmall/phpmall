<?php

declare(strict_types=1);

namespace App\Gateways\Admin\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class RoleController extends BaseController
{
    #[OA\Get(path: '/admin/role', summary: '系统角色接口', security: [['bearerAuth' => []]], tags: ['角色'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function index(Request $request): JsonResponse
    {
        return $this->success($request->path());
    }
}
