<?php

declare(strict_types=1);

namespace App\Bundles\User\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Bundles\User\Entities\UserRankEntity;
use App\Bundles\User\Requests\UserRank\UserRankCreateRequest;
use App\Bundles\User\Requests\UserRank\UserRankDestroyRequest;
use App\Bundles\User\Requests\UserRank\UserRankQueryRequest;
use App\Bundles\User\Requests\UserRank\UserRankUpdateRequest;
use App\Bundles\User\Responses\UserRank\UserRankDestroyResponse;
use App\Bundles\User\Responses\UserRank\UserRankQueryResponse;
use App\Bundles\User\Responses\UserRank\UserRankResponse;
use App\Bundles\User\Services\UserRankBundleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class UserRankController extends BaseController
{
    #[OA\Post(path: '/userRank/query', summary: '查询用户等级列表接口', security: [['bearerAuth' => []]], tags: ['用户等级模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserRankQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserRankQueryResponse::class))]
    public function query(UserRankQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[UserRankQueryRequest::getRankId])) {
                $condition[] = [UserRankEntity::getRankId, '=', $requestData[UserRankQueryRequest::getRankId]];
            }

            $userRankBundleService = new UserRankBundleService;
            $result = $userRankBundleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new UserRankResponse($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = new UserRankQueryResponse($result);
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

    #[OA\Post(path: '/userRank/store', summary: '新增用户等级接口', security: [['bearerAuth' => []]], tags: ['用户等级模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserRankCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserRankResponse::class))]
    public function store(UserRankCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = new UserRankEntity($requestData);

            $userRankBundleService = new UserRankBundleService;
            if ($userRankBundleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/userRank/show', summary: '获取用户等级详情接口', security: [['bearerAuth' => []]], tags: ['用户等级模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserRankResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $userRankBundleService = new UserRankBundleService;
            $userRank = $userRankBundleService->getOneById($id);
            if (empty($userRank)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = new UserRankResponse($userRank);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/userRank/update', summary: '更新用户等级接口', security: [['bearerAuth' => []]], tags: ['用户等级模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserRankUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserRankResponse::class))]
    public function update(UserRankUpdateRequest $updateRequest): JsonResponse
    {
        $id = intval($updateRequest->query('id', 0));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $userRankBundleService = new UserRankBundleService;
            $userRank = $userRankBundleService->getOneById($id);
            if (empty($userRank)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = new UserRankEntity($requestData);

            $userRankBundleService->updateById($input->toEntity(), $id);

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

    #[OA\Delete(path: '/userRank/destroy', summary: '删除用户等级接口', security: [['bearerAuth' => []]], tags: ['用户等级模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserRankDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserRankDestroyResponse::class))]
    public function destroy(UserRankDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            $userRankBundleService = new UserRankBundleService;
            if ($userRankBundleService->removeByIds($requestData['ids'])) {
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
