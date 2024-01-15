<?php

declare(strict_types=1);

namespace App\Bundles\Admin\Controllers\Manager;

use App\Api\Manager\Controllers\BaseController;
use App\Bundles\Admin\Services\PermissionBundleService;
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

    #[OA\Get(path: 'admin/index1', summary: '管理仪表台', security: [['bearerAuth' => []]], tags: ['运营中心'])]
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
        $permissionService = new PermissionBundleService();
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
    #[OA\Get(path: 'dashboard', summary: '运营首页', tags: ['运营'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function dashboard(): JsonResponse
    {
        // 商家 店铺 门店 商品 订单 买家
        // 平台-商品设置：无需审核 平台审核 店铺审核
        // 店铺-商品设置：无需审核 人工审核
        // 商品：优先按平台，再按照店铺审核
        return $this->success(['admin::dashboard.index']);
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
