<?php

namespace App\Modules\Auth\Services;

use App\Modules\Auth\Entities\PermissionEntity;
use App\Modules\Auth\Models\Permission;
use App\Modules\Auth\Repositories\PermissionRepository;
use Illuminate\Database\Eloquent\Collection;

class PermissionService
{
    public function __construct(private readonly PermissionRepository $repository) {}

    /**
     * @return Collection<int, Permission>
     */
    public function list(): Collection
    {
        return $this->repository->all();
    }

    /**
     * @return array<int, PermissionEntity>
     */
    public function tree(): array
    {
        return $this->repository->tree();
    }

    public function create(PermissionEntity $entity): Permission
    {
        return $this->repository->create($entity);
    }

    public function update(int $id, PermissionEntity $entity): ?Permission
    {
        return $this->repository->update($id, $entity);
    }

    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }
}
