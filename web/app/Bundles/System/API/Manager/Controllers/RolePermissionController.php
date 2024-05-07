<?php

declare(strict_types=1);

namespace App\Bundles\System\API\Manager\Controllers;

use App\API\Manager\Controllers\BaseController;
use App\Bundles\System\API\Manager\Requests\RolePermission\RolePermissionCreateRequest;
use App\Bundles\System\API\Manager\Requests\RolePermission\RolePermissionQueryRequest;
use App\Bundles\System\API\Manager\Requests\RolePermission\RolePermissionUpdateRequest;
use App\Bundles\System\API\Manager\Responses\RolePermission\RolePermissionDestroyResponse;
use App\Bundles\System\API\Manager\Responses\RolePermission\RolePermissionQueryResponse;
use App\Bundles\System\API\Manager\Responses\RolePermission\RolePermissionResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Exceptions\CustomException;
use OpenApi\Attributes as OA;
use Throwable;

class RolePermissionController extends BaseController
{
    #[OA\Post(path: '/rolePermission/query', summary: '查询列表接口', security: [['bearerAuth' => []]], tags: ['角色资源权限模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: RolePermissionQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: RolePermissionQueryResponse::class))]
    public function query(RolePermissionQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $request = $queryRequest->validated();

        try {
            $condition = [];

            $rolePermissionService = new RolePermissionService();
            $result = $rolePermissionService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new RolePermissionResponse();
                $response->setData($item);
                $result['data'][$key] = $response->toArray();
            }

            return $this->success($result);
        } catch (Throwable $e) {
            if ($e instanceof CustomException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(RolePermissionErrorEnum::QUERY_ERROR);
        }
    }

    #[OA\Post(path: '/rolePermission/create', summary: '新增接口', security: [['bearerAuth' => []]], tags: ['角色资源权限模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: RolePermissionCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: RolePermissionResponse::class))]
    public function create(RolePermissionCreateRequest $createRequest): JsonResponse
    {
        $request = $createRequest->validated();

        DB::beginTransaction();
        try {
            $input = new RolePermissionEntity();
            $input->setData($request);

            $rolePermissionService = new RolePermissionService();
            if ($rolePermissionService->save($input->toArray())) {
                DB::commit();

                return $this->success();
            }

            throw new CustomException(RolePermissionErrorEnum::CREATE_FAIL);
        } catch (Throwable $e) {
            DB::rollBack();

            if ($e instanceof CustomException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(RolePermissionErrorEnum::CREATE_ERROR);
        }
    }

    #[OA\Get(path: '/rolePermission/show', summary: '获取详情接口', security: [['bearerAuth' => []]], tags: ['角色资源权限模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: RolePermissionResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $rolePermissionService = new RolePermissionService();

            $rolePermission = $rolePermissionService->getOneById($id);
            if (empty($rolePermission)) {
                throw new CustomException(RolePermissionErrorEnum::NOT_FOUND);
            }

            $response = new RolePermissionResponse();
            $response->setData($rolePermission);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof CustomException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(RolePermissionErrorEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/rolePermission/update', summary: '更新接口', security: [['bearerAuth' => []]], tags: ['角色资源权限模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: RolePermissionUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: RolePermissionResponse::class))]
    public function update(RolePermissionUpdateRequest $updateRequest): JsonResponse
    {
        $request = $updateRequest->validated();
        $id = intval($request->query('id', 0));

        DB::beginTransaction();
        try {
            $rolePermissionService = new RolePermissionService();

            $rolePermission = $rolePermissionService->getOneById($id);
            if (empty($rolePermission)) {
                throw new CustomException(RolePermissionErrorEnum::NOT_FOUND);
            }

            $input = new RolePermissionEntity();
            $input->setData($request);

            $rolePermissionService->updateById($input->toArray(), $id);

            DB::commit();

            return $this->success();
        } catch (Throwable $e) {
            DB::rollBack();

            if ($e instanceof CustomException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(RolePermissionErrorEnum::UPDATE_ERROR);
        }
    }

    #[OA\Delete(path: '/rolePermission/destroy', summary: '删除接口', security: [['bearerAuth' => []]], tags: ['角色资源权限模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: RolePermissionDestroyResponse::class))]
    public function destroy(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        DB::beginTransaction();
        try {
            $rolePermissionService = new RolePermissionService();

            $rolePermission = $rolePermissionService->getOneById($id);
            if (empty($rolePermission)) {
                throw new CustomException(RolePermissionErrorEnum::NOT_FOUND);
            }

            if ($rolePermissionService->removeById($id)) {
                DB::commit();

                return $this->success();
            }

            throw new CustomException(RolePermissionErrorEnum::DESTROY_FAIL);
        } catch (Throwable $e) {
            DB::rollBack();

            if ($e instanceof CustomException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(RolePermissionErrorEnum::DESTROY_ERROR);
        }
    }
}
