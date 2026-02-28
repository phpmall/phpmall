<?php

declare(strict_types=1);

namespace App\Bundles\User\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Bundles\User\Entities\UserFeedEntity;
use App\Bundles\User\Requests\UserFeed\UserFeedCreateRequest;
use App\Bundles\User\Requests\UserFeed\UserFeedDestroyRequest;
use App\Bundles\User\Requests\UserFeed\UserFeedQueryRequest;
use App\Bundles\User\Requests\UserFeed\UserFeedUpdateRequest;
use App\Bundles\User\Responses\UserFeed\UserFeedDestroyResponse;
use App\Bundles\User\Responses\UserFeed\UserFeedQueryResponse;
use App\Bundles\User\Responses\UserFeed\UserFeedResponse;
use App\Bundles\User\Services\UserFeedBundleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class UserFeedController extends BaseController
{
    #[OA\Post(path: '/userFeed/query', summary: '查询用户反馈列表接口', security: [['bearerAuth' => []]], tags: ['用户反馈模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserFeedQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserFeedQueryResponse::class))]
    public function query(UserFeedQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[UserFeedQueryRequest::getFeedId])) {
                $condition[] = [UserFeedEntity::getFeedId, '=', $requestData[UserFeedQueryRequest::getFeedId]];
            }

            $userFeedBundleService = new UserFeedBundleService;
            $result = $userFeedBundleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new UserFeedResponse($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = new UserFeedQueryResponse($result);
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

    #[OA\Post(path: '/userFeed/store', summary: '新增用户反馈接口', security: [['bearerAuth' => []]], tags: ['用户反馈模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserFeedCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserFeedResponse::class))]
    public function store(UserFeedCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = new UserFeedEntity($requestData);

            $userFeedBundleService = new UserFeedBundleService;
            if ($userFeedBundleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/userFeed/show', summary: '获取用户反馈详情接口', security: [['bearerAuth' => []]], tags: ['用户反馈模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserFeedResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $userFeedBundleService = new UserFeedBundleService;
            $userFeed = $userFeedBundleService->getOneById($id);
            if (empty($userFeed)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = new UserFeedResponse($userFeed);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/userFeed/update', summary: '更新用户反馈接口', security: [['bearerAuth' => []]], tags: ['用户反馈模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserFeedUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserFeedResponse::class))]
    public function update(UserFeedUpdateRequest $updateRequest): JsonResponse
    {
        $id = intval($updateRequest->query('id', 0));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $userFeedBundleService = new UserFeedBundleService;
            $userFeed = $userFeedBundleService->getOneById($id);
            if (empty($userFeed)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = new UserFeedEntity($requestData);

            $userFeedBundleService->updateById($input->toEntity(), $id);

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

    #[OA\Delete(path: '/userFeed/destroy', summary: '删除用户反馈接口', security: [['bearerAuth' => []]], tags: ['用户反馈模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserFeedDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserFeedDestroyResponse::class))]
    public function destroy(UserFeedDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            $userFeedBundleService = new UserFeedBundleService;
            if ($userFeedBundleService->removeByIds($requestData['ids'])) {
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
