<?php

declare(strict_types=1);

namespace App\API\Merchant\Controllers;

use App\Bundles\System\Services\PermissionBundleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class DashboardController extends BaseController
{
    #[OA\Get(path: '/dashboard', summary: '商家首页', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function index(Request $request): JsonResponse
    {
        return $this->success(['seller::index', $request->user()]);
    }

    #[OA\Get(path: '/dashboard/menu', summary: '获取管理菜单', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function menu(): JsonResponse
    {
        $permissionService = new PermissionBundleService();
        $menu = $permissionService->getMenu();

        return $this->success($menu);
    }

    #[OA\Get(path: '/dashboard/message', summary: '获取系统消息', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function message(): JsonResponse
    {
        return $this->success(['message']);
    }
}
