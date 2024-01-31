<?php

namespace Database\Seeders;

use App\Bundles\System\Services\PermissionBundleService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    private array $authModules = [
        'Manager',
        'Seller',
    ];

    public function run()
    {
        Db::transaction(function () {
            $permissionBundleService = new PermissionBundleService();
            $modules = glob(app_path('Api/*'), GLOB_ONLYDIR);
            foreach ($modules as $module) {
                $moduleName = basename($module);
                if (in_array($moduleName, $this->authModules)) {
                    $permissionBundleService->collectionPermission($moduleName);
                }
            }
        });
    }

    private function menu()
    {
        $result = DB::table('permissions')->count();
        if (empty($result)) {
            $menu = [
                ['id' => 1, 'name' => '全局设置', 'path' => 'setting', 'icon' => 'layui-icon layui-icon-console', 'type' => 1],
                ['id' => 2, 'name' => '内容管理', 'path' => 'content', 'icon' => 'layui-icon layui-icon-form', 'type' => 1],
                ['id' => 3, 'name' => '其他模块', 'path' => 'extension', 'icon' => 'layui-icon layui-icon-app', 'type' => 1],
                ['id' => 4, 'name' => '系统管理', 'path' => 'system', 'icon' => 'layui-icon layui-icon-set', 'type' => 1],
                ['id' => 5, 'name' => '开发工具', 'path' => 'develop', 'icon' => 'layui-icon layui-icon-util', 'type' => 1],

                ['parent_id' => 1, 'name' => '基本参数', 'path' => 'setting/basic', 'type' => 1],
                ['parent_id' => 1, 'name' => '公司信息', 'path' => 'setting/company', 'type' => 1],
                ['parent_id' => 1, 'name' => '网站信息', 'path' => 'setting/site', 'type' => 1],
                ['parent_id' => 1, 'name' => '邮件设置', 'path' => 'setting/email', 'type' => 1],

                ['parent_id' => 2, 'name' => '栏目管理', 'path' => 'category/index', 'type' => 1],
                ['parent_id' => 2, 'name' => '内容管理', 'path' => 'content/index', 'type' => 1],
                ['parent_id' => 2, 'name' => '评论管理', 'path' => 'comment/index', 'type' => 1],
                ['parent_id' => 2, 'name' => '内容模型', 'path' => 'pattern/index', 'type' => 1],

                ['parent_id' => 3, 'name' => '导航管理', 'path' => 'nav/index', 'type' => 1],
                ['parent_id' => 3, 'name' => '广告管理', 'path' => 'adPosition/index', 'type' => 1],
                ['parent_id' => 3, 'name' => '表单管理', 'path' => 'form/index', 'type' => 1],
                ['parent_id' => 3, 'name' => '标签片段', 'path' => 'segment/index', 'type' => 1],

                ['parent_id' => 4, 'name' => '用户管理', 'path' => 'user/index', 'type' => 1],
                ['parent_id' => 4, 'name' => '角色管理', 'path' => 'role/index', 'type' => 1],
                ['parent_id' => 4, 'name' => '权限管理', 'path' => 'permission/index', 'type' => 1],
                ['parent_id' => 4, 'name' => '部门管理', 'path' => 'department/index', 'type' => 1],
                ['parent_id' => 4, 'name' => '系统日志', 'path' => 'system/log', 'type' => 1],
                ['parent_id' => 4, 'name' => '数据库管理', 'path' => 'database/index', 'type' => 1],

                ['parent_id' => 5, 'name' => '表单构建', 'path' => 'builder/index', 'type' => 1],
            ];

            foreach ($menu as $item) {
                DB::table('permissions')->insert($item);
            }
        }
    }
}
