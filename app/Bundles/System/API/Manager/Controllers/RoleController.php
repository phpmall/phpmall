<?php

declare(strict_types=1);

namespace App\Bundles\System\API\Manager\Controllers;

use App\API\Manager\Controllers\BaseController;
use App\Bundles\System\API\Manager\Requests\Role\RoleCreateRequest;
use App\Bundles\System\API\Manager\Requests\Role\RoleQueryRequest;
use App\Bundles\System\API\Manager\Requests\Role\RoleUpdateRequest;
use App\Bundles\System\API\Manager\Responses\Role\RoleDestroyResponse;
use App\Bundles\System\API\Manager\Responses\Role\RoleQueryResponse;
use App\Bundles\System\API\Manager\Responses\Role\RoleResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Exceptions\CustomException;
use OpenApi\Attributes as OA;
use Throwable;

class RoleController extends BaseController
{
    #[OA\Post(path: '/role/query', summary: '查询列表接口', security: [['bearerAuth' => []]], tags: ['用户角色模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: RoleQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: RoleQueryResponse::class))]
    public function query(RoleQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $request = $queryRequest->validated();

        try {
            $condition = [];

            $roleService = new RoleService();
            $result = $roleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new RoleResponse();
                $response->setData($item);
                $result['data'][$key] = $response->toArray();
            }

            return $this->success($result);
        } catch (Throwable $e) {
            if ($e instanceof CustomException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(RoleErrorEnum::QUERY_ERROR);
        }
    }

    #[OA\Post(path: '/role/create', summary: '新增接口', security: [['bearerAuth' => []]], tags: ['用户角色模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: RoleCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: RoleResponse::class))]
    public function create(RoleCreateRequest $createRequest): JsonResponse
    {
        $request = $createRequest->validated();

        DB::beginTransaction();
        try {
            $input = new RoleEntity();
            $input->setData($request);

            $roleService = new RoleService();
            if ($roleService->save($input->toArray())) {
                DB::commit();

                return $this->success();
            }

            throw new CustomException(RoleErrorEnum::CREATE_FAIL);
        } catch (Throwable $e) {
            DB::rollBack();

            if ($e instanceof CustomException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(RoleErrorEnum::CREATE_ERROR);
        }
    }

    #[OA\Get(path: '/role/show', summary: '获取详情接口', security: [['bearerAuth' => []]], tags: ['用户角色模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: RoleResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $roleService = new RoleService();

            $role = $roleService->getOneById($id);
            if (empty($role)) {
                throw new CustomException(RoleErrorEnum::NOT_FOUND);
            }

            $response = new RoleResponse();
            $response->setData($role);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof CustomException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(RoleErrorEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/role/update', summary: '更新接口', security: [['bearerAuth' => []]], tags: ['用户角色模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: RoleUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: RoleResponse::class))]
    public function update(RoleUpdateRequest $updateRequest): JsonResponse
    {
        $request = $updateRequest->validated();
        $id = intval($request->query('id', 0));

        DB::beginTransaction();
        try {
            $roleService = new RoleService();

            $role = $roleService->getOneById($id);
            if (empty($role)) {
                throw new CustomException(RoleErrorEnum::NOT_FOUND);
            }

            $input = new RoleEntity();
            $input->setData($request);

            $roleService->updateById($input->toArray(), $id);

            DB::commit();

            return $this->success();
        } catch (Throwable $e) {
            DB::rollBack();

            if ($e instanceof CustomException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(RoleErrorEnum::UPDATE_ERROR);
        }
    }

    #[OA\Delete(path: '/role/destroy', summary: '删除接口', security: [['bearerAuth' => []]], tags: ['用户角色模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: RoleDestroyResponse::class))]
    public function destroy(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        DB::beginTransaction();
        try {
            $roleService = new RoleService();

            $role = $roleService->getOneById($id);
            if (empty($role)) {
                throw new CustomException(RoleErrorEnum::NOT_FOUND);
            }

            if ($roleService->removeById($id)) {
                DB::commit();

                return $this->success();
            }

            throw new CustomException(RoleErrorEnum::DESTROY_FAIL);
        } catch (Throwable $e) {
            DB::rollBack();

            if ($e instanceof CustomException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(RoleErrorEnum::DESTROY_ERROR);
        }
    }
}
