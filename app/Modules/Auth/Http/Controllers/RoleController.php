<?php

namespace App\Modules\Auth\Http\Controllers;

use App\Modules\Auth\Entities\RoleEntity;
use App\Modules\Auth\Http\Requests\RoleRequest;
use App\Modules\Auth\Services\RoleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class RoleController extends Controller
{
    public function __construct(private readonly RoleService $roleService) {}

    public function index(): JsonResponse
    {
        return response()->json([
            'code' => 0,
            'data' => $this->roleService->list(),
        ]);
    }

    public function store(RoleRequest $request): JsonResponse
    {
        $data = $request->validated();
        $entity = new RoleEntity(
            name: $data['name'],
            displayName: $data['display_name'],
            description: $data['description'] ?? null,
            status: $data['status'] ?? 1,
            sort: $data['sort'] ?? 0,
            permissionIds: $data['permission_ids'] ?? [],
        );

        return response()->json([
            'code' => 0,
            'message' => '创建成功',
            'data' => $this->roleService->create($entity),
        ]);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json([
            'code' => 0,
            'data' => $this->roleService->detail($id),
        ]);
    }

    public function update(RoleRequest $request, int $id): JsonResponse
    {
        $data = $request->validated();
        $entity = new RoleEntity(
            name: $data['name'],
            displayName: $data['display_name'],
            description: $data['description'] ?? null,
            status: $data['status'] ?? 1,
            sort: $data['sort'] ?? 0,
            permissionIds: $data['permission_ids'] ?? [],
        );

        return response()->json([
            'code' => 0,
            'message' => '更新成功',
            'data' => $this->roleService->update($id, $entity),
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->roleService->delete($id);

        return response()->json([
            'code' => 0,
            'message' => '删除成功',
        ]);
    }
}
