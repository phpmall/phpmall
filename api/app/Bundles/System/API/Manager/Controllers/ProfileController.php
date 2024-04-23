<?php

declare(strict_types=1);

namespace App\Bundles\System\API\Manager\Controllers;

use App\API\Manager\Controllers\BaseController;
use App\Bundles\System\Services\PermissionBundleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use OpenApi\Attributes as OA;

class ProfileController extends BaseController
{
    #[OA\Get(path: '/dashboard', summary: '运营首页', security: [['bearerAuth' => []]], tags: ['运营中心'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function dashboard(Request $request): JsonResponse
    {
        // 商家 店铺 门店 商品 订单 买家
        // 平台-商品设置：无需审核 平台审核 店铺审核
        // 店铺-商品设置：无需审核 人工审核
        // 商品：优先按平台，再按照店铺审核
        return $this->success(['test admin token', $request->user()]);
    }

    #[OA\Get(path: '/menu', summary: '获取管理菜单', security: [['bearerAuth' => []]], tags: ['运营中心'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function menu(): JsonResponse
    {
        $permissionService = new PermissionBundleService();
        $menu = $permissionService->getMenu();

        return $this->success($menu);
    }

    #[OA\Get(path: '/message', summary: '获取系统消息', security: [['bearerAuth' => []]], tags: ['运营中心'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function message(): JsonResponse
    {
        return $this->success(['message']);
    }

    #[OA\Get(path: '/profile', summary: '获取个人资料', security: [['bearerAuth' => []]], tags: ['运营中心'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function profile(): JsonResponse
    {
        return $this->success('ok');
    }

    #[OA\Post(path: '/password', summary: '修改密码', security: [['bearerAuth' => []]], tags: ['运营中心'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function password(): JsonResponse
    {
        return $this->success('password');
    }

    #[OA\Post(path: '/logout', summary: '注销登录', security: [['bearerAuth' => []]], tags: ['运营中心'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function logout(): JsonResponse
    {
        Session::forget('auth_console');

        return $this->success('logout');
    }
}
