<?php

declare(strict_types=1);

namespace App\Bundles\Admin\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Bundles\Admin\Entities\AdminActionEntity;
use App\Bundles\Admin\Requests\AdminAction\AdminActionCreateRequest;
use App\Bundles\Admin\Requests\AdminAction\AdminActionDestroyRequest;
use App\Bundles\Admin\Requests\AdminAction\AdminActionQueryRequest;
use App\Bundles\Admin\Requests\AdminAction\AdminActionUpdateRequest;
use App\Bundles\Admin\Responses\AdminAction\AdminActionDestroyResponse;
use App\Bundles\Admin\Responses\AdminAction\AdminActionQueryResponse;
use App\Bundles\Admin\Responses\AdminAction\AdminActionResponse;
use App\Bundles\Admin\Services\AdminActionBundleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class AdminActionController extends BaseController
{
    #[OA\Post(path: '/adminAction/query', summary: '查询管理员操作权限列表接口', security: [['bearerAuth' => []]], tags: ['管理员操作权限模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: AdminActionQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: AdminActionQueryResponse::class))]
    public function query(AdminActionQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[AdminActionQueryRequest::getParentId])) {
                $condition[] = [AdminActionEntity::getParentId, '=', $requestData[AdminActionQueryRequest::getParentId]];
            }
            if (isset($requestData[AdminActionQueryRequest::getActionId])) {
                $condition[] = [AdminActionEntity::getActionId, '=', $requestData[AdminActionQueryRequest::getActionId]];
            }

            $adminActionBundleService = new AdminActionBundleService;
            $result = $adminActionBundleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new AdminActionResponse($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = new AdminActionQueryResponse($result);
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

    #[OA\Post(path: '/adminAction/store', summary: '新增管理员操作权限接口', security: [['bearerAuth' => []]], tags: ['管理员操作权限模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: AdminActionCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: AdminActionResponse::class))]
    public function store(AdminActionCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = new AdminActionEntity($requestData);

            $adminActionBundleService = new AdminActionBundleService;
            if ($adminActionBundleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/adminAction/show', summary: '获取管理员操作权限详情接口', security: [['bearerAuth' => []]], tags: ['管理员操作权限模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: AdminActionResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $adminActionBundleService = new AdminActionBundleService;
            $adminAction = $adminActionBundleService->getOneById($id);
            if (empty($adminAction)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = new AdminActionResponse($adminAction);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/adminAction/update', summary: '更新管理员操作权限接口', security: [['bearerAuth' => []]], tags: ['管理员操作权限模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: AdminActionUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: AdminActionResponse::class))]
    public function update(AdminActionUpdateRequest $updateRequest): JsonResponse
    {
        $id = intval($updateRequest->query('id', 0));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $adminActionBundleService = new AdminActionBundleService;
            $adminAction = $adminActionBundleService->getOneById($id);
            if (empty($adminAction)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = new AdminActionEntity($requestData);

            $adminActionBundleService->updateById($input->toEntity(), $id);

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

    #[OA\Delete(path: '/adminAction/destroy', summary: '删除管理员操作权限接口', security: [['bearerAuth' => []]], tags: ['管理员操作权限模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: AdminActionDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: AdminActionDestroyResponse::class))]
    public function destroy(AdminActionDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            $adminActionBundleService = new AdminActionBundleService;
            if ($adminActionBundleService->removeByIds($requestData['ids'])) {
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
