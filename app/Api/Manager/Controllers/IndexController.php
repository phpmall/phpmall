<?php

declare(strict_types=1);

namespace App\Api\Manager\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class IndexController extends BaseController
{
    #[OA\Get(path: '/admin', summary: '仪表台', security: [['bearerAuth' => []]], tags: ['运营中心'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function index(): JsonResponse
    {
        return $this->success(['test admin token', $this->getAdminUser()]);
    }

    /**
     * 管理菜单
     */
    public function menu(): JsonResponse
    {
        $permissionService = new PermissionService();
        $menu = $permissionService->getMenu();

        return $this->json($menu);
    }

    /**
     * 系统消息
     */
    public function message(): JsonResponse
    {
        return $this->json(['message']);
    }

    /**
     * 起始页
     */
    public function dashboard(): JsonResponse
    {
        return $this->success('');
    }

    /**
     * 个人资料
     */
    public function profile(): Renderable
    {
        return $this->success('ok');
    }

    /**
     * 修改密码
     */
    public function password(): JsonResponse
    {
        return $this->success('password');
    }

    /**
     * 注销登录
     */
    public function logout(): JsonResponse
    {
        session('auth_console', null);

        return $this->success('logout');
    }
}
