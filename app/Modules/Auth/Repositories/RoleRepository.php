<?php

namespace App\Modules\Auth\Repositories;

use App\Modules\Auth\Entities\RoleEntity;
use App\Modules\Auth\Models\Role;
use Illuminate\Database\Eloquent\Collection;

class RoleRepository
{
    public function findById(int $id): ?Role
    {
        return Role::find($id);
    }

    public function findByName(string $name): ?Role
    {
        return Role::where('name', $name)->first();
    }

    /**
     * @return Collection<int, Role>
     */
    public function all(): Collection
    {
        return Role::orderBy('sort')->orderBy('id')->get();
    }

    public function create(RoleEntity $entity): Role
    {
        $role = Role::create([
            'name' => $entity->name,
            'display_name' => $entity->displayName,
            'description' => $entity->description,
            'status' => $entity->status,
            'sort' => $entity->sort,
        ]);

        if (! empty($entity->permissionIds)) {
            $role->permissions()->sync($entity->permissionIds);
        }

        return $role;
    }

    public function update(int $id, RoleEntity $entity): ?Role
    {
        $role = $this->findById($id);

        if (! $role) {
            return null;
        }

        $role->update([
            'name' => $entity->name,
            'display_name' => $entity->displayName,
            'description' => $entity->description,
            'status' => $entity->status,
            'sort' => $entity->sort,
        ]);

        $role->permissions()->sync($entity->permissionIds);

        return $role->fresh();
    }

    public function delete(int $id): bool
    {
        $role = $this->findById($id);

        if (! $role) {
            return false;
        }

        return (bool) $role->delete();
    }
}
