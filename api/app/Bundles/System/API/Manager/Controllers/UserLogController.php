<?php

declare(strict_types=1);

namespace App\Bundles\System\API\Manager\Controllers;

use App\API\Manager\Controllers\BaseController;
use App\Bundles\System\API\Manager\Requests\UserLog\UserLogCreateRequest;
use App\Bundles\System\API\Manager\Requests\UserLog\UserLogQueryRequest;
use App\Bundles\System\API\Manager\Requests\UserLog\UserLogUpdateRequest;
use App\Bundles\System\API\Manager\Responses\UserLog\UserLogDestroyResponse;
use App\Bundles\System\API\Manager\Responses\UserLog\UserLogQueryResponse;
use App\Bundles\System\API\Manager\Responses\UserLog\UserLogResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Exceptions\CustomException;
use OpenApi\Attributes as OA;
use Throwable;

class UserLogController extends BaseController
{
    #[OA\Post(path: '/userLog/query', summary: '查询列表接口', security: [['bearerAuth' => []]], tags: ['用户日志模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserLogQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserLogQueryResponse::class))]
    public function query(UserLogQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $request = $queryRequest->validated();

        try {
            $condition = [];

            $userLogService = new UserLogService();
            $result = $userLogService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new UserLogResponse();
                $response->setData($item);
                $result['data'][$key] = $response->toArray();
            }

            return $this->success($result);
        } catch (Throwable $e) {
            if ($e instanceof CustomException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(UserLogErrorEnum::QUERY_ERROR);
        }
    }

    #[OA\Post(path: '/userLog/create', summary: '新增接口', security: [['bearerAuth' => []]], tags: ['用户日志模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserLogCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserLogResponse::class))]
    public function create(UserLogCreateRequest $createRequest): JsonResponse
    {
        $request = $createRequest->validated();

        DB::beginTransaction();
        try {
            $input = new UserLogEntity();
            $input->setData($request);

            $userLogService = new UserLogService();
            if ($userLogService->save($input->toArray())) {
                DB::commit();

                return $this->success();
            }

            throw new CustomException(UserLogErrorEnum::CREATE_FAIL);
        } catch (Throwable $e) {
            DB::rollBack();

            if ($e instanceof CustomException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(UserLogErrorEnum::CREATE_ERROR);
        }
    }

    #[OA\Get(path: '/userLog/show', summary: '获取详情接口', security: [['bearerAuth' => []]], tags: ['用户日志模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserLogResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $userLogService = new UserLogService();

            $userLog = $userLogService->getOneById($id);
            if (empty($userLog)) {
                throw new CustomException(UserLogErrorEnum::NOT_FOUND);
            }

            $response = new UserLogResponse();
            $response->setData($userLog);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof CustomException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(UserLogErrorEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/userLog/update', summary: '更新接口', security: [['bearerAuth' => []]], tags: ['用户日志模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserLogUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserLogResponse::class))]
    public function update(UserLogUpdateRequest $updateRequest): JsonResponse
    {
        $request = $updateRequest->validated();
        $id = intval($request->query('id', 0));

        DB::beginTransaction();
        try {
            $userLogService = new UserLogService();

            $userLog = $userLogService->getOneById($id);
            if (empty($userLog)) {
                throw new CustomException(UserLogErrorEnum::NOT_FOUND);
            }

            $input = new UserLogEntity();
            $input->setData($request);

            $userLogService->updateById($input->toArray(), $id);

            DB::commit();

            return $this->success();
        } catch (Throwable $e) {
            DB::rollBack();

            if ($e instanceof CustomException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(UserLogErrorEnum::UPDATE_ERROR);
        }
    }

    #[OA\Delete(path: '/userLog/destroy', summary: '删除接口', security: [['bearerAuth' => []]], tags: ['用户日志模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserLogDestroyResponse::class))]
    public function destroy(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        DB::beginTransaction();
        try {
            $userLogService = new UserLogService();

            $userLog = $userLogService->getOneById($id);
            if (empty($userLog)) {
                throw new CustomException(UserLogErrorEnum::NOT_FOUND);
            }

            if ($userLogService->removeById($id)) {
                DB::commit();

                return $this->success();
            }

            throw new CustomException(UserLogErrorEnum::DESTROY_FAIL);
        } catch (Throwable $e) {
            DB::rollBack();

            if ($e instanceof CustomException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(UserLogErrorEnum::DESTROY_ERROR);
        }
    }
}
