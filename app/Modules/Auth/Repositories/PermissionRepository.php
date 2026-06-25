<?php

namespace App\Modules\Auth\Repositories;

use App\Modules\Auth\Entities\PermissionEntity;
use App\Modules\Auth\Models\Permission;
use Illuminate\Database\Eloquent\Collection;

class PermissionRepository
{
    public function findById(int $id): ?Permission
    {
        return Permission::find($id);
    }

    public function findByName(string $name): ?Permission
    {
        return Permission::where('name', $name)->first();
    }

    /**
     * @return Collection<int, Permission>
     */
    public function all(): Collection
    {
        return Permission::orderBy('sort')->orderBy('id')->get();
    }

    /**
     * @return array<int, PermissionEntity>
     */
    public function tree(): array
    {
        $permissions = $this->all();

        return $this->buildTree($permissions);
    }

    /**
     * @param  Collection<int, Permission>  $permissions
     * @return array<int, PermissionEntity>
     */
    private function buildTree(Collection $permissions, int $parentId = 0): array
    {
        $tree = [];

        foreach ($permissions as $permission) {
            if ($permission->parent_id !== $parentId) {
                continue;
            }

            $entity = PermissionEntity::fromModel($permission);
            $entity->children = $this->buildTree($permissions, $permission->id);
            $tree[] = $entity;
        }

        return $tree;
    }

    public function create(PermissionEntity $entity): Permission
    {
        return Permission::create([
            'name' => $entity->name,
            'display_name' => $entity->displayName,
            'description' => $entity->description,
            'parent_id' => $entity->parentId,
            'type' => $entity->type,
            'route' => $entity->route,
            'icon' => $entity->icon,
            'sort' => $entity->sort,
            'status' => $entity->status,
        ]);
    }

    public function update(int $id, PermissionEntity $entity): ?Permission
    {
        $permission = $this->findById($id);

        if (! $permission) {
            return null;
        }

        $permission->update([
            'name' => $entity->name,
            'display_name' => $entity->displayName,
            'description' => $entity->description,
            'parent_id' => $entity->parentId,
            'type' => $entity->type,
            'route' => $entity->route,
            'icon' => $entity->icon,
            'sort' => $entity->sort,
            'status' => $entity->status,
        ]);

        return $permission->fresh();
    }

    public function delete(int $id): bool
    {
        $permission = $this->findById($id);

        if (! $permission) {
            return false;
        }

        if ($permission->children()->exists()) {
            return false;
        }

        return (bool) $permission->delete();
    }
}
