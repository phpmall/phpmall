<?php

declare(strict_types=1);

namespace App\Bundles\Admin\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Bundles\Admin\Entities\AdminLogEntity;
use App\Bundles\Admin\Requests\AdminLog\AdminLogCreateRequest;
use App\Bundles\Admin\Requests\AdminLog\AdminLogDestroyRequest;
use App\Bundles\Admin\Requests\AdminLog\AdminLogQueryRequest;
use App\Bundles\Admin\Requests\AdminLog\AdminLogUpdateRequest;
use App\Bundles\Admin\Responses\AdminLog\AdminLogDestroyResponse;
use App\Bundles\Admin\Responses\AdminLog\AdminLogQueryResponse;
use App\Bundles\Admin\Responses\AdminLog\AdminLogResponse;
use App\Bundles\Admin\Services\AdminLogBundleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class AdminLogController extends BaseController
{
    #[OA\Post(path: '/adminLog/query', summary: '查询管理员操作日志列表接口', security: [['bearerAuth' => []]], tags: ['管理员操作日志模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: AdminLogQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: AdminLogQueryResponse::class))]
    public function query(AdminLogQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[AdminLogQueryRequest::getLogTime])) {
                $condition[] = [AdminLogEntity::getLogTime, '=', $requestData[AdminLogQueryRequest::getLogTime]];
            }
            if (isset($requestData[AdminLogQueryRequest::getUserId])) {
                $condition[] = [AdminLogEntity::getUserId, '=', $requestData[AdminLogQueryRequest::getUserId]];
            }
            if (isset($requestData[AdminLogQueryRequest::getLogId])) {
                $condition[] = [AdminLogEntity::getLogId, '=', $requestData[AdminLogQueryRequest::getLogId]];
            }

            $adminLogBundleService = new AdminLogBundleService;
            $result = $adminLogBundleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new AdminLogResponse($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = new AdminLogQueryResponse($result);
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

    #[OA\Post(path: '/adminLog/store', summary: '新增管理员操作日志接口', security: [['bearerAuth' => []]], tags: ['管理员操作日志模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: AdminLogCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: AdminLogResponse::class))]
    public function store(AdminLogCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = new AdminLogEntity($requestData);

            $adminLogBundleService = new AdminLogBundleService;
            if ($adminLogBundleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/adminLog/show', summary: '获取管理员操作日志详情接口', security: [['bearerAuth' => []]], tags: ['管理员操作日志模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: AdminLogResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $adminLogBundleService = new AdminLogBundleService;
            $adminLog = $adminLogBundleService->getOneById($id);
            if (empty($adminLog)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = new AdminLogResponse($adminLog);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/adminLog/update', summary: '更新管理员操作日志接口', security: [['bearerAuth' => []]], tags: ['管理员操作日志模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: AdminLogUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: AdminLogResponse::class))]
    public function update(AdminLogUpdateRequest $updateRequest): JsonResponse
    {
        $id = intval($updateRequest->query('id', 0));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $adminLogBundleService = new AdminLogBundleService;
            $adminLog = $adminLogBundleService->getOneById($id);
            if (empty($adminLog)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = new AdminLogEntity($requestData);

            $adminLogBundleService->updateById($input->toEntity(), $id);

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

    #[OA\Delete(path: '/adminLog/destroy', summary: '删除管理员操作日志接口', security: [['bearerAuth' => []]], tags: ['管理员操作日志模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: AdminLogDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: AdminLogDestroyResponse::class))]
    public function destroy(AdminLogDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            $adminLogBundleService = new AdminLogBundleService;
            if ($adminLogBundleService->removeByIds($requestData['ids'])) {
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
