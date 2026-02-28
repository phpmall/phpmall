<?php

declare(strict_types=1);

namespace App\Bundles\User\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Bundles\User\Entities\UserCollectEntity;
use App\Bundles\User\Requests\UserCollect\UserCollectCreateRequest;
use App\Bundles\User\Requests\UserCollect\UserCollectDestroyRequest;
use App\Bundles\User\Requests\UserCollect\UserCollectQueryRequest;
use App\Bundles\User\Requests\UserCollect\UserCollectUpdateRequest;
use App\Bundles\User\Responses\UserCollect\UserCollectDestroyResponse;
use App\Bundles\User\Responses\UserCollect\UserCollectQueryResponse;
use App\Bundles\User\Responses\UserCollect\UserCollectResponse;
use App\Bundles\User\Services\UserCollectBundleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class UserCollectController extends BaseController
{
    #[OA\Post(path: '/userCollect/query', summary: '查询用户收藏列表接口', security: [['bearerAuth' => []]], tags: ['用户收藏模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserCollectQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserCollectQueryResponse::class))]
    public function query(UserCollectQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[UserCollectQueryRequest::getRecId])) {
                $condition[] = [UserCollectEntity::getRecId, '=', $requestData[UserCollectQueryRequest::getRecId]];
            }
            if (isset($requestData[UserCollectQueryRequest::getGoodsId])) {
                $condition[] = [UserCollectEntity::getGoodsId, '=', $requestData[UserCollectQueryRequest::getGoodsId]];
            }
            if (isset($requestData[UserCollectQueryRequest::getIsAttention])) {
                $condition[] = [UserCollectEntity::getIsAttention, '=', $requestData[UserCollectQueryRequest::getIsAttention]];
            }
            if (isset($requestData[UserCollectQueryRequest::getUserId])) {
                $condition[] = [UserCollectEntity::getUserId, '=', $requestData[UserCollectQueryRequest::getUserId]];
            }

            $userCollectBundleService = new UserCollectBundleService;
            $result = $userCollectBundleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new UserCollectResponse($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = new UserCollectQueryResponse($result);
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

    #[OA\Post(path: '/userCollect/store', summary: '新增用户收藏接口', security: [['bearerAuth' => []]], tags: ['用户收藏模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserCollectCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserCollectResponse::class))]
    public function store(UserCollectCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = new UserCollectEntity($requestData);

            $userCollectBundleService = new UserCollectBundleService;
            if ($userCollectBundleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/userCollect/show', summary: '获取用户收藏详情接口', security: [['bearerAuth' => []]], tags: ['用户收藏模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserCollectResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $userCollectBundleService = new UserCollectBundleService;
            $userCollect = $userCollectBundleService->getOneById($id);
            if (empty($userCollect)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = new UserCollectResponse($userCollect);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/userCollect/update', summary: '更新用户收藏接口', security: [['bearerAuth' => []]], tags: ['用户收藏模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserCollectUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserCollectResponse::class))]
    public function update(UserCollectUpdateRequest $updateRequest): JsonResponse
    {
        $id = intval($updateRequest->query('id', 0));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $userCollectBundleService = new UserCollectBundleService;
            $userCollect = $userCollectBundleService->getOneById($id);
            if (empty($userCollect)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = new UserCollectEntity($requestData);

            $userCollectBundleService->updateById($input->toEntity(), $id);

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

    #[OA\Delete(path: '/userCollect/destroy', summary: '删除用户收藏接口', security: [['bearerAuth' => []]], tags: ['用户收藏模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserCollectDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserCollectDestroyResponse::class))]
    public function destroy(UserCollectDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            $userCollectBundleService = new UserCollectBundleService;
            if ($userCollectBundleService->removeByIds($requestData['ids'])) {
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
