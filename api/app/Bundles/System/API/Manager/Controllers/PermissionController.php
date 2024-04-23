<?php

declare(strict_types=1);

namespace App\Bundles\System\API\Manager\Controllers;

use App\API\Manager\Controllers\BaseController;
use App\Bundles\System\API\Manager\Requests\Permission\PermissionCreateRequest;
use App\Bundles\System\API\Manager\Requests\Permission\PermissionQueryRequest;
use App\Bundles\System\API\Manager\Requests\Permission\PermissionUpdateRequest;
use App\Bundles\System\API\Manager\Responses\Permission\PermissionDestroyResponse;
use App\Bundles\System\API\Manager\Responses\Permission\PermissionQueryResponse;
use App\Bundles\System\API\Manager\Responses\Permission\PermissionResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Exceptions\CustomException;
use OpenApi\Attributes as OA;
use Throwable;

class PermissionController extends BaseController
{
    #[OA\Post(path: '/permission/query', summary: '查询列表接口', security: [['bearerAuth' => []]], tags: ['权限资源模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: PermissionQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: PermissionQueryResponse::class))]
    public function query(PermissionQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $request = $queryRequest->validated();

        try {
            $condition = [];

            $permissionService = new PermissionService();
            $result = $permissionService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new PermissionResponse();
                $response->setData($item);
                $result['data'][$key] = $response->toArray();
            }

            return $this->success($result);
        } catch (Throwable $e) {
            if ($e instanceof CustomException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(PermissionErrorEnum::QUERY_ERROR);
        }
    }

    #[OA\Post(path: '/permission/create', summary: '新增接口', security: [['bearerAuth' => []]], tags: ['权限资源模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: PermissionCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: PermissionResponse::class))]
    public function create(PermissionCreateRequest $createRequest): JsonResponse
    {
        $request = $createRequest->validated();

        DB::beginTransaction();
        try {
            $input = new PermissionEntity();
            $input->setData($request);

            $permissionService = new PermissionService();
            if ($permissionService->save($input->toArray())) {
                DB::commit();

                return $this->success();
            }

            throw new CustomException(PermissionErrorEnum::CREATE_FAIL);
        } catch (Throwable $e) {
            DB::rollBack();

            if ($e instanceof CustomException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(PermissionErrorEnum::CREATE_ERROR);
        }
    }

    #[OA\Get(path: '/permission/show', summary: '获取详情接口', security: [['bearerAuth' => []]], tags: ['权限资源模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: PermissionResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $permissionService = new PermissionService();

            $permission = $permissionService->getOneById($id);
            if (empty($permission)) {
                throw new CustomException(PermissionErrorEnum::NOT_FOUND);
            }

            $response = new PermissionResponse();
            $response->setData($permission);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof CustomException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(PermissionErrorEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/permission/update', summary: '更新接口', security: [['bearerAuth' => []]], tags: ['权限资源模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: PermissionUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: PermissionResponse::class))]
    public function update(PermissionUpdateRequest $updateRequest): JsonResponse
    {
        $request = $updateRequest->validated();
        $id = intval($request->query('id', 0));

        DB::beginTransaction();
        try {
            $permissionService = new PermissionService();

            $permission = $permissionService->getOneById($id);
            if (empty($permission)) {
                throw new CustomException(PermissionErrorEnum::NOT_FOUND);
            }

            $input = new PermissionEntity();
            $input->setData($request);

            $permissionService->updateById($input->toArray(), $id);

            DB::commit();

            return $this->success();
        } catch (Throwable $e) {
            DB::rollBack();

            if ($e instanceof CustomException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(PermissionErrorEnum::UPDATE_ERROR);
        }
    }

    #[OA\Delete(path: '/permission/destroy', summary: '删除接口', security: [['bearerAuth' => []]], tags: ['权限资源模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: PermissionDestroyResponse::class))]
    public function destroy(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        DB::beginTransaction();
        try {
            $permissionService = new PermissionService();

            $permission = $permissionService->getOneById($id);
            if (empty($permission)) {
                throw new CustomException(PermissionErrorEnum::NOT_FOUND);
            }

            if ($permissionService->removeById($id)) {
                DB::commit();

                return $this->success();
            }

            throw new CustomException(PermissionErrorEnum::DESTROY_FAIL);
        } catch (Throwable $e) {
            DB::rollBack();

            if ($e instanceof CustomException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(PermissionErrorEnum::DESTROY_ERROR);
        }
    }
}
