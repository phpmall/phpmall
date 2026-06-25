<?php

namespace App\Modules\Auth\Entities;

use App\Modules\Auth\Models\Permission;

class PermissionEntity
{
    /**
     * @param  array<int, self>  $children
     */
    public function __construct(
        public ?int $id = null,
        public string $name = '',
        public string $displayName = '',
        public ?string $description = null,
        public int $parentId = 0,
        public string $type = 'menu',
        public ?string $route = null,
        public ?string $icon = null,
        public int $sort = 0,
        public int $status = 1,
        public array $children = [],
    ) {}

    public static function fromModel(Permission $permission): self
    {
        return new self(
            id: $permission->id,
            name: $permission->name,
            displayName: $permission->display_name,
            description: $permission->description,
            parentId: $permission->parent_id,
            type: $permission->type,
            route: $permission->route,
            icon: $permission->icon,
            sort: $permission->sort,
            status: $permission->status,
        );
    }
}
