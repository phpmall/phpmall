<?php

declare(strict_types=1);

namespace App\Bundles\System\API\Admin\Controllers;

use App\API\Admin\Controllers\BaseController;
use App\Bundles\System\Services\PermissionBundleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class IndexController extends BaseController
{
    #[OA\Get(path: '/profile', summary: '管理员信息', security: [['bearerAuth' => []]], tags: ['运营中心'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function profile(Request $request): JsonResponse
    {
        $user = $request->user();

        return $this->success([
            'user' => $user->toArray(),
            'roles' => [],
            'permissions' => [],
        ]);
    }

    #[OA\Get(path: '/menu', summary: '管理菜单', security: [['bearerAuth' => []]], tags: ['运营中心'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function menu(): JsonResponse
    {
        $permissionService = new PermissionBundleService();
        $menu = $permissionService->getMenu();

        return $this->success([
            'data' => $menu,
        ]);
    }

    #[OA\Get(path: '/message', summary: '系统消息', security: [['bearerAuth' => []]], tags: ['运营中心'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function message(): JsonResponse
    {
        return $this->success(['message']);
    }

    #[OA\Post(path: '/password', summary: '修改密码', security: [['bearerAuth' => []]], tags: ['运营中心'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function password(): JsonResponse
    {
        return $this->success('password');
    }

    #[OA\Post(path: '/logout', summary: '注销登录', security: [['bearerAuth' => []]], tags: ['运营中心'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function logout(Request $request): JsonResponse
    {
        $request->user()->logout();

        return $this->success('ok');
    }
}
