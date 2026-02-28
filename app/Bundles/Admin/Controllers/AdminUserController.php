<?php

declare(strict_types=1);

namespace App\Bundles\Admin\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Bundles\Admin\Entities\AdminUserEntity;
use App\Bundles\Admin\Requests\AdminUser\AdminUserCreateRequest;
use App\Bundles\Admin\Requests\AdminUser\AdminUserDestroyRequest;
use App\Bundles\Admin\Requests\AdminUser\AdminUserQueryRequest;
use App\Bundles\Admin\Requests\AdminUser\AdminUserUpdateRequest;
use App\Bundles\Admin\Responses\AdminUser\AdminUserDestroyResponse;
use App\Bundles\Admin\Responses\AdminUser\AdminUserQueryResponse;
use App\Bundles\Admin\Responses\AdminUser\AdminUserResponse;
use App\Bundles\Admin\Services\AdminUserBundleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class AdminUserController extends BaseController
{
    #[OA\Post(path: '/adminUser/query', summary: '查询管理员用户列表接口', security: [['bearerAuth' => []]], tags: ['管理员用户模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: AdminUserQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: AdminUserQueryResponse::class))]
    public function query(AdminUserQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[AdminUserQueryRequest::getAgencyId])) {
                $condition[] = [AdminUserEntity::getAgencyId, '=', $requestData[AdminUserQueryRequest::getAgencyId]];
            }
            if (isset($requestData[AdminUserQueryRequest::getUserName])) {
                $condition[] = [AdminUserEntity::getUserName, '=', $requestData[AdminUserQueryRequest::getUserName]];
            }
            if (isset($requestData[AdminUserQueryRequest::getUserId])) {
                $condition[] = [AdminUserEntity::getUserId, '=', $requestData[AdminUserQueryRequest::getUserId]];
            }

            $adminUserBundleService = new AdminUserBundleService;
            $result = $adminUserBundleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new AdminUserResponse($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = new AdminUserQueryResponse($result);
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

    #[OA\Post(path: '/adminUser/store', summary: '新增管理员用户接口', security: [['bearerAuth' => []]], tags: ['管理员用户模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: AdminUserCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: AdminUserResponse::class))]
    public function store(AdminUserCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = new AdminUserEntity($requestData);

            $adminUserBundleService = new AdminUserBundleService;
            if ($adminUserBundleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/adminUser/show', summary: '获取管理员用户详情接口', security: [['bearerAuth' => []]], tags: ['管理员用户模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: AdminUserResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $adminUserBundleService = new AdminUserBundleService;
            $adminUser = $adminUserBundleService->getOneById($id);
            if (empty($adminUser)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = new AdminUserResponse($adminUser);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/adminUser/update', summary: '更新管理员用户接口', security: [['bearerAuth' => []]], tags: ['管理员用户模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: AdminUserUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: AdminUserResponse::class))]
    public function update(AdminUserUpdateRequest $updateRequest): JsonResponse
    {
        $id = intval($updateRequest->query('id', 0));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $adminUserBundleService = new AdminUserBundleService;
            $adminUser = $adminUserBundleService->getOneById($id);
            if (empty($adminUser)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = new AdminUserEntity($requestData);

            $adminUserBundleService->updateById($input->toEntity(), $id);

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

    #[OA\Delete(path: '/adminUser/destroy', summary: '删除管理员用户接口', security: [['bearerAuth' => []]], tags: ['管理员用户模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: AdminUserDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: AdminUserDestroyResponse::class))]
    public function destroy(AdminUserDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            $adminUserBundleService = new AdminUserBundleService;
            if ($adminUserBundleService->removeByIds($requestData['ids'])) {
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
