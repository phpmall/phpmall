<?php

declare(strict_types=1);

namespace App\Bundles\User\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Bundles\User\Entities\UserCartEntity;
use App\Bundles\User\Requests\UserCart\UserCartCreateRequest;
use App\Bundles\User\Requests\UserCart\UserCartDestroyRequest;
use App\Bundles\User\Requests\UserCart\UserCartQueryRequest;
use App\Bundles\User\Requests\UserCart\UserCartUpdateRequest;
use App\Bundles\User\Responses\UserCart\UserCartDestroyResponse;
use App\Bundles\User\Responses\UserCart\UserCartQueryResponse;
use App\Bundles\User\Responses\UserCart\UserCartResponse;
use App\Bundles\User\Services\UserCartBundleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class UserCartController extends BaseController
{
    #[OA\Post(path: '/userCart/query', summary: '查询购物车列表接口', security: [['bearerAuth' => []]], tags: ['购物车模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserCartQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserCartQueryResponse::class))]
    public function query(UserCartQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[UserCartQueryRequest::getRecId])) {
                $condition[] = [UserCartEntity::getRecId, '=', $requestData[UserCartQueryRequest::getRecId]];
            }
            if (isset($requestData[UserCartQueryRequest::getGoodsId])) {
                $condition[] = [UserCartEntity::getGoodsId, '=', $requestData[UserCartQueryRequest::getGoodsId]];
            }
            if (isset($requestData[UserCartQueryRequest::getSessionId])) {
                $condition[] = [UserCartEntity::getSessionId, '=', $requestData[UserCartQueryRequest::getSessionId]];
            }
            if (isset($requestData[UserCartQueryRequest::getUserId])) {
                $condition[] = [UserCartEntity::getUserId, '=', $requestData[UserCartQueryRequest::getUserId]];
            }

            $userCartBundleService = new UserCartBundleService;
            $result = $userCartBundleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new UserCartResponse($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = new UserCartQueryResponse($result);
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

    #[OA\Post(path: '/userCart/store', summary: '新增购物车接口', security: [['bearerAuth' => []]], tags: ['购物车模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserCartCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserCartResponse::class))]
    public function store(UserCartCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = new UserCartEntity($requestData);

            $userCartBundleService = new UserCartBundleService;
            if ($userCartBundleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/userCart/show', summary: '获取购物车详情接口', security: [['bearerAuth' => []]], tags: ['购物车模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserCartResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $userCartBundleService = new UserCartBundleService;
            $userCart = $userCartBundleService->getOneById($id);
            if (empty($userCart)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = new UserCartResponse($userCart);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/userCart/update', summary: '更新购物车接口', security: [['bearerAuth' => []]], tags: ['购物车模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserCartUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserCartResponse::class))]
    public function update(UserCartUpdateRequest $updateRequest): JsonResponse
    {
        $id = intval($updateRequest->query('id', 0));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $userCartBundleService = new UserCartBundleService;
            $userCart = $userCartBundleService->getOneById($id);
            if (empty($userCart)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = new UserCartEntity($requestData);

            $userCartBundleService->updateById($input->toEntity(), $id);

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

    #[OA\Delete(path: '/userCart/destroy', summary: '删除购物车接口', security: [['bearerAuth' => []]], tags: ['购物车模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserCartDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserCartDestroyResponse::class))]
    public function destroy(UserCartDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            $userCartBundleService = new UserCartBundleService;
            if ($userCartBundleService->removeByIds($requestData['ids'])) {
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
