<?php

declare(strict_types=1);

namespace App\Bundles\Admin\Services;

use App\Bundles\Admin\Enums\PermissionStatusEnum;
use App\Bundles\Admin\Enums\PermissionTypeEnum;
use App\Services\PermissionService as BasePermissionService;

class PermissionService extends BasePermissionService
{
    /**
     * 获取管理资源链接
     */
    public function getMenu(): array
    {
        $data = $this->getList([
            ['status', '=', PermissionStatusEnum::Normal->value],
            ['type', '=', PermissionTypeEnum::Menu->value],
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
                        'title' => $v['name'],
                        'href' => route('console/'.$v['rule']),
                        'icon' => $v['icon'],
                        'type' => 1,
                        'openType' => '_iframe',
                    ];
                }

                $menu[] = [
                    'id' => $item['id'],
                    'title' => $item['name'],
                    'href' => '',
                    'icon' => $item['icon'],
                    'type' => 0,
                    'children' => $children,
                ];
            }
        }

        return $menu;
    }
}
