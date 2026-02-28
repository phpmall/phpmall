<?php

declare(strict_types=1);

namespace App\Bundles\Admin\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Bundles\Admin\Entities\AdminRoleEntity;
use App\Bundles\Admin\Requests\AdminRole\AdminRoleCreateRequest;
use App\Bundles\Admin\Requests\AdminRole\AdminRoleDestroyRequest;
use App\Bundles\Admin\Requests\AdminRole\AdminRoleQueryRequest;
use App\Bundles\Admin\Requests\AdminRole\AdminRoleUpdateRequest;
use App\Bundles\Admin\Responses\AdminRole\AdminRoleDestroyResponse;
use App\Bundles\Admin\Responses\AdminRole\AdminRoleQueryResponse;
use App\Bundles\Admin\Responses\AdminRole\AdminRoleResponse;
use App\Bundles\Admin\Services\AdminRoleBundleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class AdminRoleController extends BaseController
{
    #[OA\Post(path: '/adminRole/query', summary: '查询管理员角色列表接口', security: [['bearerAuth' => []]], tags: ['管理员角色模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: AdminRoleQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: AdminRoleQueryResponse::class))]
    public function query(AdminRoleQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[AdminRoleQueryRequest::getRoleName])) {
                $condition[] = [AdminRoleEntity::getRoleName, '=', $requestData[AdminRoleQueryRequest::getRoleName]];
            }
            if (isset($requestData[AdminRoleQueryRequest::getRoleId])) {
                $condition[] = [AdminRoleEntity::getRoleId, '=', $requestData[AdminRoleQueryRequest::getRoleId]];
            }

            $adminRoleBundleService = new AdminRoleBundleService;
            $result = $adminRoleBundleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new AdminRoleResponse($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = new AdminRoleQueryResponse($result);
            $response->setFirstPageUrl('');
            $response->setLastPageUrl('');
            $response->setLinks([]);
            $response->setPath('');

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::QUERY_ERROR);
        }
    }

    #[OA\Post(path: '/adminRole/store', summary: '新增管理员角色接口', security: [['bearerAuth' => []]], tags: ['管理员角色模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: AdminRoleCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: AdminRoleResponse::class))]
    public function store(AdminRoleCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = new AdminRoleEntity($requestData);

            $adminRoleBundleService = new AdminRoleBundleService;
            if ($adminRoleBundleService->save($input->toEntity())) {
                DB::commit();

                return $this->success();
            }

            throw new BusinessException(BusinessEnum::CREATE_FAIL);
        } catch (Throwable $e) {
            DB::rollBack();

            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::CREATE_ERROR);
        }
    }

    #[OA\Get(path: '/adminRole/show', summary: '获取管理员角色详情接口', security: [['bearerAuth' => []]], tags: ['管理员角色模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: AdminRoleResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $adminRoleBundleService = new AdminRoleBundleService;
            $adminRole = $adminRoleBundleService->getOneById($id);
            if (empty($adminRole)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = new AdminRoleResponse($adminRole);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/adminRole/update', summary: '更新管理员角色接口', security: [['bearerAuth' => []]], tags: ['管理员角色模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: AdminRoleUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: AdminRoleResponse::class))]
    public function update(AdminRoleUpdateRequest $updateRequest): JsonResponse
    {
        $id = intval($updateRequest->query('id', 0));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $adminRoleBundleService = new AdminRoleBundleService;
            $adminRole = $adminRoleBundleService->getOneById($id);
            if (empty($adminRole)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = new AdminRoleEntity($requestData);

            $adminRoleBundleService->updateById($input->toEntity(), $id);

            DB::commit();

            return $this->success();
        } catch (Throwable $e) {
            DB::rollBack();

            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::UPDATE_ERROR);
        }
    }

    #[OA\Delete(path: '/adminRole/destroy', summary: '删除管理员角色接口', security: [['bearerAuth' => []]], tags: ['管理员角色模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: AdminRoleDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: AdminRoleDestroyResponse::class))]
    public function destroy(AdminRoleDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            $adminRoleBundleService = new AdminRoleBundleService;
            if ($adminRoleBundleService->removeByIds($requestData['ids'])) {
                DB::commit();

                return $this->success();
            }

            throw new BusinessException(BusinessEnum::DESTROY_FAIL);
        } catch (Throwable $e) {
            DB::rollBack();

            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::DESTROY_ERROR);
        }
    }
}
