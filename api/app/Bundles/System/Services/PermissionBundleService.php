<?php

declare(strict_types=1);

namespace App\Bundles\System\Services;

use App\Bundles\System\Enums\PermissionStatusEnum;
use App\Bundles\System\Enums\PermissionTypeEnum;
use App\Entities\PermissionEntity;
use App\Services\PermissionService;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;

class PermissionBundleService extends PermissionService
{
    /**
     * @throws ReflectionException
     */
    public function collectionPermission(string $module): void
    {
        $files = array_merge(
            glob(app_path('Api/'.$module.'/Controllers/*Controller.php')),
            glob(app_path('Bundles/*/Controllers/'.$module.'/*Controller.php'))
        );

        foreach ($files as $file) {
            $file = str_replace('/', '\\', $file);
            preg_match('/(app\\\.+?)\.php/', $file, $matches);
            $class = Str::ucfirst($matches[1]);

            $rc = new ReflectionClass($class);
            $methods = $rc->getMethods(ReflectionMethod::IS_PUBLIC);

            foreach ($methods as $method) {
                if ($method->class === $class) {
                    if (Str::substr($method->name, 0, 2) === '__') {
                        // 跳过魔术方法
                        continue;
                    }

                    $routeAttribute = [];
                    foreach ($method->getAttributes() as $attribute) {
                        $routeAttribute = $attribute->getArguments();
                        break; // 仅需处理接口概要（默认第一个注解）
                    }

                    if (empty($routeAttribute)) {
                        continue;
                    }

                    $routeAttribute['path'] = 'api/'.$module.'/'.$routeAttribute['path'];
                    $condition = ['path' => $routeAttribute['path']];
                    $result = $this->getOne($condition);

                    $permission = new PermissionEntity();
                    if (empty($result)) {
                        $permission->setModule($module);
                        $permission->setName($routeAttribute['summary']);
                        $permission->setIcon('');
                        $permission->setPath($routeAttribute['path']);
                        $permission->setType(PermissionTypeEnum::Api->value);
                        $permission->setTags($routeAttribute['tags'][0] ?? '');
                        $permission->setSort(0);
                        $permission->setStatus(PermissionStatusEnum::Normal->value);
                        $this->save($permission->toArray());
                    } else {
                        $permission->setName($routeAttribute['summary']);
                        $permission->setTags($routeAttribute['tags'][0] ?? '');
                        $this->update($permission->toArray(), $condition);
                    }
                }
            }
        }

        // 从数据表中移除没有定义接口名称的数据
        $this->remove(['name' => '']);
    }

    /**
     * 获取管理资源链接
     */
    public function getMenu(): array
    {
        $data = $this->getList([
            ['type', '=', PermissionTypeEnum::Menu->value],
            ['status', '=', PermissionStatusEnum::Normal->value],
        ], 'sort', 'asc');

        $collection = collect($data);

        $menu = [];
        foreach ($data as $item) {
            if ($item['parent_id'] == 0) {
                $filtered = $collection->filter(function ($v) use ($item) {
                    return $v['parent_id'] === $item['id'];
                });

                $children = [];
                foreach ($filtered->all() as $v) {
                    $children[] = [
                        'id' => $v['id'],
                        'name' => $v['name'],
                        'icon' => $v['icon'],
                        'href' => $v['path'],
                        'type' => 1,
                        'openType' => '_iframe',
                    ];
                }

                $menu[] = [
                    'id' => $item['id'],
                    'name' => $item['name'],
                    'icon' => $item['icon'],
                    'href' => '',
                    'type' => 0,
                    'children' => $children,
                ];
            }
        }

        return $menu;
    }
}
