<?php

declare(strict_types=1);

namespace App\Bundles\User\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Bundles\User\Entities\UserAccountLogEntity;
use App\Bundles\User\Requests\UserAccountLog\UserAccountLogCreateRequest;
use App\Bundles\User\Requests\UserAccountLog\UserAccountLogDestroyRequest;
use App\Bundles\User\Requests\UserAccountLog\UserAccountLogQueryRequest;
use App\Bundles\User\Requests\UserAccountLog\UserAccountLogUpdateRequest;
use App\Bundles\User\Responses\UserAccountLog\UserAccountLogDestroyResponse;
use App\Bundles\User\Responses\UserAccountLog\UserAccountLogQueryResponse;
use App\Bundles\User\Responses\UserAccountLog\UserAccountLogResponse;
use App\Bundles\User\Services\UserAccountLogBundleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class UserAccountLogController extends BaseController
{
    #[OA\Post(path: '/userAccountLog/query', summary: '查询用户账户日志列表接口', security: [['bearerAuth' => []]], tags: ['用户账户日志模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserAccountLogQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserAccountLogQueryResponse::class))]
    public function query(UserAccountLogQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[UserAccountLogQueryRequest::getLogId])) {
                $condition[] = [UserAccountLogEntity::getLogId, '=', $requestData[UserAccountLogQueryRequest::getLogId]];
            }
            if (isset($requestData[UserAccountLogQueryRequest::getUserId])) {
                $condition[] = [UserAccountLogEntity::getUserId, '=', $requestData[UserAccountLogQueryRequest::getUserId]];
            }

            $userAccountLogBundleService = new UserAccountLogBundleService;
            $result = $userAccountLogBundleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new UserAccountLogResponse($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = new UserAccountLogQueryResponse($result);
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

    #[OA\Post(path: '/userAccountLog/store', summary: '新增用户账户日志接口', security: [['bearerAuth' => []]], tags: ['用户账户日志模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserAccountLogCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserAccountLogResponse::class))]
    public function store(UserAccountLogCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = new UserAccountLogEntity($requestData);

            $userAccountLogBundleService = new UserAccountLogBundleService;
            if ($userAccountLogBundleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/userAccountLog/show', summary: '获取用户账户日志详情接口', security: [['bearerAuth' => []]], tags: ['用户账户日志模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserAccountLogResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $userAccountLogBundleService = new UserAccountLogBundleService;
            $userAccountLog = $userAccountLogBundleService->getOneById($id);
            if (empty($userAccountLog)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = new UserAccountLogResponse($userAccountLog);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/userAccountLog/update', summary: '更新用户账户日志接口', security: [['bearerAuth' => []]], tags: ['用户账户日志模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserAccountLogUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserAccountLogResponse::class))]
    public function update(UserAccountLogUpdateRequest $updateRequest): JsonResponse
    {
        $id = intval($updateRequest->query('id', 0));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $userAccountLogBundleService = new UserAccountLogBundleService;
            $userAccountLog = $userAccountLogBundleService->getOneById($id);
            if (empty($userAccountLog)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = new UserAccountLogEntity($requestData);

            $userAccountLogBundleService->updateById($input->toEntity(), $id);

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

    #[OA\Delete(path: '/userAccountLog/destroy', summary: '删除用户账户日志接口', security: [['bearerAuth' => []]], tags: ['用户账户日志模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserAccountLogDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserAccountLogDestroyResponse::class))]
    public function destroy(UserAccountLogDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            $userAccountLogBundleService = new UserAccountLogBundleService;
            if ($userAccountLogBundleService->removeByIds($requestData['ids'])) {
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
