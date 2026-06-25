<?php

namespace App\Modules\Auth\Http\Controllers;

use App\Modules\Auth\Entities\PermissionEntity;
use App\Modules\Auth\Http\Requests\PermissionRequest;
use App\Modules\Auth\Services\PermissionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class PermissionController extends Controller
{
    public function __construct(private readonly PermissionService $permissionService) {}

    public function index(): JsonResponse
    {
        return response()->json([
            'code' => 0,
            'data' => $this->permissionService->list(),
        ]);
    }

    public function tree(): JsonResponse
    {
        return response()->json([
            'code' => 0,
            'data' => $this->permissionService->tree(),
        ]);
    }

    public function store(PermissionRequest $request): JsonResponse
    {
        $data = $request->validated();
        $entity = new PermissionEntity(
            name: $data['name'],
            displayName: $data['display_name'],
            description: $data['description'] ?? null,
            parentId: $data['parent_id'] ?? 0,
            type: $data['type'] ?? 'menu',
            route: $data['route'] ?? null,
            icon: $data['icon'] ?? null,
            sort: $data['sort'] ?? 0,
            status: $data['status'] ?? 1,
        );

        return response()->json([
            'code' => 0,
            'message' => '创建成功',
            'data' => $this->permissionService->create($entity),
        ]);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json([
            'code' => 0,
            'data' => $this->permissionService->list()->firstWhere('id', $id),
        ]);
    }

    public function update(PermissionRequest $request, int $id): JsonResponse
    {
        $data = $request->validated();
        $entity = new PermissionEntity(
            name: $data['name'],
            displayName: $data['display_name'],
            description: $data['description'] ?? null,
            parentId: $data['parent_id'] ?? 0,
            type: $data['type'] ?? 'menu',
            route: $data['route'] ?? null,
            icon: $data['icon'] ?? null,
            sort: $data['sort'] ?? 0,
            status: $data['status'] ?? 1,
        );

        return response()->json([
            'code' => 0,
            'message' => '更新成功',
            'data' => $this->permissionService->update($id, $entity),
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->permissionService->delete($id);

        return response()->json([
            'code' => 0,
            'message' => '删除成功',
        ]);
    }
}
