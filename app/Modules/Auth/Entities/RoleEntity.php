<?php

namespace App\Modules\Auth\Entities;

use App\Modules\Auth\Models\Role;

class RoleEntity
{
    /**
     * @param  array<int, int>  $permissionIds
     */
    public function __construct(
        public ?int $id = null,
        public string $name = '',
        public string $displayName = '',
        public ?string $description = null,
        public int $status = 1,
        public int $sort = 0,
        public array $permissionIds = [],
    ) {}

    public static function fromModel(Role $role): self
    {
        return new self(
            id: $role->id,
            name: $role->name,
            displayName: $role->display_name,
            description: $role->description,
            status: $role->status,
            sort: $role->sort,
            permissionIds: $role->permissions->pluck('id')->toArray(),
        );
    }
}
