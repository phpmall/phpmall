<?php

namespace Database\Seeders;

use App\Bundles\System\Services\PermissionBundleService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    private array $ignoreModule = [
        'Auth',
        'Portal',
    ];

    public function run()
    {
        Db::transaction(function () {
            $permissionBundleService = new PermissionBundleService();
            $modules = glob(app_path('Api/*'), GLOB_ONLYDIR);
            foreach ($modules as $module) {
                $moduleName = basename($module);
                if (! in_array($moduleName, $this->ignoreModule)) {
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
                ['id' => 1, 'name' => '全局设置', 'rule' => 'setting', 'icon' => 'layui-icon layui-icon-console', 'is_menu' => 1],
                ['id' => 2, 'name' => '内容管理', 'rule' => 'content', 'icon' => 'layui-icon layui-icon-form', 'is_menu' => 1],
                ['id' => 3, 'name' => '其他模块', 'rule' => 'extension', 'icon' => 'layui-icon layui-icon-app', 'is_menu' => 1],
                ['id' => 4, 'name' => '系统管理', 'rule' => 'system', 'icon' => 'layui-icon layui-icon-set', 'is_menu' => 1],
                ['id' => 5, 'name' => '开发工具', 'rule' => 'develop', 'icon' => 'layui-icon layui-icon-util', 'is_menu' => 1],

                ['parent_id' => 1, 'name' => '基本参数', 'rule' => 'setting/basic', 'is_menu' => 1],
                ['parent_id' => 1, 'name' => '公司信息', 'rule' => 'setting/company', 'is_menu' => 1],
                ['parent_id' => 1, 'name' => '网站信息', 'rule' => 'setting/site', 'is_menu' => 1],
                ['parent_id' => 1, 'name' => '邮件设置', 'rule' => 'setting/email', 'is_menu' => 1],

                ['parent_id' => 2, 'name' => '栏目管理', 'rule' => 'category/index', 'is_menu' => 1],
                ['parent_id' => 2, 'name' => '内容管理', 'rule' => 'content/index', 'is_menu' => 1],
                ['parent_id' => 2, 'name' => '评论管理', 'rule' => 'comment/index', 'is_menu' => 1],
                ['parent_id' => 2, 'name' => '内容模型', 'rule' => 'pattern/index', 'is_menu' => 1],

                ['parent_id' => 3, 'name' => '导航管理', 'rule' => 'nav/index', 'is_menu' => 1],
                ['parent_id' => 3, 'name' => '广告管理', 'rule' => 'adPosition/index', 'is_menu' => 1],
                ['parent_id' => 3, 'name' => '表单管理', 'rule' => 'form/index', 'is_menu' => 1],
                ['parent_id' => 3, 'name' => '标签片段', 'rule' => 'segment/index', 'is_menu' => 1],

                ['parent_id' => 4, 'name' => '用户管理', 'rule' => 'user/index', 'is_menu' => 1],
                ['parent_id' => 4, 'name' => '角色管理', 'rule' => 'role/index', 'is_menu' => 1],
                ['parent_id' => 4, 'name' => '权限管理', 'rule' => 'permission/index', 'is_menu' => 1],
                ['parent_id' => 4, 'name' => '部门管理', 'rule' => 'department/index', 'is_menu' => 1],
                ['parent_id' => 4, 'name' => '系统日志', 'rule' => 'system/log', 'is_menu' => 1],
                ['parent_id' => 4, 'name' => '数据库管理', 'rule' => 'database/index', 'is_menu' => 1],

                ['parent_id' => 5, 'name' => '表单构建', 'rule' => 'builder/index', 'is_menu' => 1],
            ];

            foreach ($menu as $item) {
                DB::table('permissions')->insert($item);
            }
        }
    }
}
