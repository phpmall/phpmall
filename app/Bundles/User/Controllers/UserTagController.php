<?php

declare(strict_types=1);

namespace App\Bundles\User\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Bundles\User\Entities\UserTagEntity;
use App\Bundles\User\Requests\UserTag\UserTagCreateRequest;
use App\Bundles\User\Requests\UserTag\UserTagDestroyRequest;
use App\Bundles\User\Requests\UserTag\UserTagQueryRequest;
use App\Bundles\User\Requests\UserTag\UserTagUpdateRequest;
use App\Bundles\User\Responses\UserTag\UserTagDestroyResponse;
use App\Bundles\User\Responses\UserTag\UserTagQueryResponse;
use App\Bundles\User\Responses\UserTag\UserTagResponse;
use App\Bundles\User\Services\UserTagBundleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class UserTagController extends BaseController
{
    #[OA\Post(path: '/userTag/query', summary: '查询用户标签列表接口', security: [['bearerAuth' => []]], tags: ['用户标签模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserTagQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserTagQueryResponse::class))]
    public function query(UserTagQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[UserTagQueryRequest::getTagId])) {
                $condition[] = [UserTagEntity::getTagId, '=', $requestData[UserTagQueryRequest::getTagId]];
            }
            if (isset($requestData[UserTagQueryRequest::getGoodsId])) {
                $condition[] = [UserTagEntity::getGoodsId, '=', $requestData[UserTagQueryRequest::getGoodsId]];
            }
            if (isset($requestData[UserTagQueryRequest::getUserId])) {
                $condition[] = [UserTagEntity::getUserId, '=', $requestData[UserTagQueryRequest::getUserId]];
            }

            $userTagBundleService = new UserTagBundleService;
            $result = $userTagBundleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new UserTagResponse($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = new UserTagQueryResponse($result);
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

    #[OA\Post(path: '/userTag/store', summary: '新增用户标签接口', security: [['bearerAuth' => []]], tags: ['用户标签模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserTagCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserTagResponse::class))]
    public function store(UserTagCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = new UserTagEntity($requestData);

            $userTagBundleService = new UserTagBundleService;
            if ($userTagBundleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/userTag/show', summary: '获取用户标签详情接口', security: [['bearerAuth' => []]], tags: ['用户标签模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserTagResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $userTagBundleService = new UserTagBundleService;
            $userTag = $userTagBundleService->getOneById($id);
            if (empty($userTag)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = new UserTagResponse($userTag);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/userTag/update', summary: '更新用户标签接口', security: [['bearerAuth' => []]], tags: ['用户标签模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserTagUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserTagResponse::class))]
    public function update(UserTagUpdateRequest $updateRequest): JsonResponse
    {
        $id = intval($updateRequest->query('id', 0));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $userTagBundleService = new UserTagBundleService;
            $userTag = $userTagBundleService->getOneById($id);
            if (empty($userTag)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = new UserTagEntity($requestData);

            $userTagBundleService->updateById($input->toEntity(), $id);

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

    #[OA\Delete(path: '/userTag/destroy', summary: '删除用户标签接口', security: [['bearerAuth' => []]], tags: ['用户标签模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserTagDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserTagDestroyResponse::class))]
    public function destroy(UserTagDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            $userTagBundleService = new UserTagBundleService;
            if ($userTagBundleService->removeByIds($requestData['ids'])) {
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
