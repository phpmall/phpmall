<?php

declare(strict_types=1);

namespace App\Api\Manager\Controllers;

use App\Api\Manager\Services\PermissionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use OpenApi\Attributes as OA;

class IndexController extends BaseController
{
    public function index(): View
    {
        return view('admin::index');
    }

    #[OA\Get(path: '/admin/index1', summary: '管理仪表台', security: [['bearerAuth' => []]], tags: ['运营中心'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function index1(Request $request): JsonResponse
    {
        return $this->success(['test admin token', $request->user()]);
    }

    /**
     * 管理菜单
     */
    public function menu(): JsonResponse
    {
        $permissionService = new PermissionService();
        $menu = $permissionService->getMenu();

        return $this->success($menu);
    }

    /**
     * 系统消息
     */
    public function message(): JsonResponse
    {
        return $this->success(['message']);
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
    public function profile(): JsonResponse
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
