<?php

namespace App\Modules\Auth\Services;

use App\Modules\Auth\Entities\RoleEntity;
use App\Modules\Auth\Models\Role;
use App\Modules\Auth\Repositories\RoleRepository;
use Illuminate\Database\Eloquent\Collection;

class RoleService
{
    public function __construct(private readonly RoleRepository $repository) {}

    /**
     * @return Collection<int, Role>
     */
    public function list(): Collection
    {
        return $this->repository->all();
    }

    public function create(RoleEntity $entity): Role
    {
        return $this->repository->create($entity);
    }

    public function update(int $id, RoleEntity $entity): ?Role
    {
        return $this->repository->update($id, $entity);
    }

    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }

    public function detail(int $id): ?Role
    {
        return $this->repository->findById($id)?->load('permissions');
    }
}
