<?php

declare(strict_types=1);

namespace App\Bundles\System\API\Admin\Controllers;

use App\API\Admin\Controllers\BaseController;
use App\Bundles\System\API\Admin\Requests\UserPermission\UserPermissionCreateRequest;
use App\Bundles\System\API\Admin\Requests\UserPermission\UserPermissionQueryRequest;
use App\Bundles\System\API\Admin\Requests\UserPermission\UserPermissionUpdateRequest;
use App\Bundles\System\API\Admin\Responses\UserPermission\UserPermissionDestroyResponse;
use App\Bundles\System\API\Admin\Responses\UserPermission\UserPermissionQueryResponse;
use App\Bundles\System\API\Admin\Responses\UserPermission\UserPermissionResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Exceptions\CustomException;
use OpenApi\Attributes as OA;
use Throwable;

class UserPermissionController extends BaseController
{
    #[OA\Post(path: '/userPermission/query', summary: '查询列表接口', security: [['bearerAuth' => []]], tags: ['用户资源权限模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserPermissionQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserPermissionQueryResponse::class))]
    public function query(UserPermissionQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $request = $queryRequest->validated();

        try {
            $condition = [];

            $userPermissionService = new UserPermissionService();
            $result = $userPermissionService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new UserPermissionResponse();
                $response->setData($item);
                $result['data'][$key] = $response->toArray();
            }

            return $this->success($result);
        } catch (Throwable $e) {
            if ($e instanceof CustomException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(UserPermissionErrorEnum::QUERY_ERROR);
        }
    }

    #[OA\Post(path: '/userPermission/create', summary: '新增接口', security: [['bearerAuth' => []]], tags: ['用户资源权限模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserPermissionCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserPermissionResponse::class))]
    public function create(UserPermissionCreateRequest $createRequest): JsonResponse
    {
        $request = $createRequest->validated();

        DB::beginTransaction();
        try {
            $input = new UserPermissionEntity();
            $input->setData($request);

            $userPermissionService = new UserPermissionService();
            if ($userPermissionService->save($input->toArray())) {
                DB::commit();

                return $this->success();
            }

            throw new CustomException(UserPermissionErrorEnum::CREATE_FAIL);
        } catch (Throwable $e) {
            DB::rollBack();

            if ($e instanceof CustomException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(UserPermissionErrorEnum::CREATE_ERROR);
        }
    }

    #[OA\Get(path: '/userPermission/show', summary: '获取详情接口', security: [['bearerAuth' => []]], tags: ['用户资源权限模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserPermissionResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $userPermissionService = new UserPermissionService();

            $userPermission = $userPermissionService->getOneById($id);
            if (empty($userPermission)) {
                throw new CustomException(UserPermissionErrorEnum::NOT_FOUND);
            }

            $response = new UserPermissionResponse();
            $response->setData($userPermission);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof CustomException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(UserPermissionErrorEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/userPermission/update', summary: '更新接口', security: [['bearerAuth' => []]], tags: ['用户资源权限模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserPermissionUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserPermissionResponse::class))]
    public function update(UserPermissionUpdateRequest $updateRequest): JsonResponse
    {
        $request = $updateRequest->validated();
        $id = intval($request->query('id', 0));

        DB::beginTransaction();
        try {
            $userPermissionService = new UserPermissionService();

            $userPermission = $userPermissionService->getOneById($id);
            if (empty($userPermission)) {
                throw new CustomException(UserPermissionErrorEnum::NOT_FOUND);
            }

            $input = new UserPermissionEntity();
            $input->setData($request);

            $userPermissionService->updateById($input->toArray(), $id);

            DB::commit();

            return $this->success();
        } catch (Throwable $e) {
            DB::rollBack();

            if ($e instanceof CustomException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(UserPermissionErrorEnum::UPDATE_ERROR);
        }
    }

    #[OA\Delete(path: '/userPermission/destroy', summary: '删除接口', security: [['bearerAuth' => []]], tags: ['用户资源权限模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserPermissionDestroyResponse::class))]
    public function destroy(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        DB::beginTransaction();
        try {
            $userPermissionService = new UserPermissionService();

            $userPermission = $userPermissionService->getOneById($id);
            if (empty($userPermission)) {
                throw new CustomException(UserPermissionErrorEnum::NOT_FOUND);
            }

            if ($userPermissionService->removeById($id)) {
                DB::commit();

                return $this->success();
            }

            throw new CustomException(UserPermissionErrorEnum::DESTROY_FAIL);
        } catch (Throwable $e) {
            DB::rollBack();

            if ($e instanceof CustomException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(UserPermissionErrorEnum::DESTROY_ERROR);
        }
    }
}
