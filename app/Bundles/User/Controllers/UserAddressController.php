<?php

declare(strict_types=1);

namespace App\Bundles\User\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Bundles\User\Entities\UserAddressEntity;
use App\Bundles\User\Requests\UserAddress\UserAddressCreateRequest;
use App\Bundles\User\Requests\UserAddress\UserAddressDestroyRequest;
use App\Bundles\User\Requests\UserAddress\UserAddressQueryRequest;
use App\Bundles\User\Requests\UserAddress\UserAddressUpdateRequest;
use App\Bundles\User\Responses\UserAddress\UserAddressDestroyResponse;
use App\Bundles\User\Responses\UserAddress\UserAddressQueryResponse;
use App\Bundles\User\Responses\UserAddress\UserAddressResponse;
use App\Bundles\User\Services\UserAddressBundleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class UserAddressController extends BaseController
{
    #[OA\Post(path: '/userAddress/query', summary: '查询用户地址列表接口', security: [['bearerAuth' => []]], tags: ['用户地址模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserAddressQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserAddressQueryResponse::class))]
    public function query(UserAddressQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[UserAddressQueryRequest::getAddressId])) {
                $condition[] = [UserAddressEntity::getAddressId, '=', $requestData[UserAddressQueryRequest::getAddressId]];
            }
            if (isset($requestData[UserAddressQueryRequest::getUserId])) {
                $condition[] = [UserAddressEntity::getUserId, '=', $requestData[UserAddressQueryRequest::getUserId]];
            }

            $userAddressBundleService = new UserAddressBundleService;
            $result = $userAddressBundleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new UserAddressResponse($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = new UserAddressQueryResponse($result);
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

    #[OA\Post(path: '/userAddress/store', summary: '新增用户地址接口', security: [['bearerAuth' => []]], tags: ['用户地址模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserAddressCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserAddressResponse::class))]
    public function store(UserAddressCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = new UserAddressEntity($requestData);

            $userAddressBundleService = new UserAddressBundleService;
            if ($userAddressBundleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/userAddress/show', summary: '获取用户地址详情接口', security: [['bearerAuth' => []]], tags: ['用户地址模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserAddressResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $userAddressBundleService = new UserAddressBundleService;
            $userAddress = $userAddressBundleService->getOneById($id);
            if (empty($userAddress)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = new UserAddressResponse($userAddress);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/userAddress/update', summary: '更新用户地址接口', security: [['bearerAuth' => []]], tags: ['用户地址模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserAddressUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserAddressResponse::class))]
    public function update(UserAddressUpdateRequest $updateRequest): JsonResponse
    {
        $id = intval($updateRequest->query('id', 0));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $userAddressBundleService = new UserAddressBundleService;
            $userAddress = $userAddressBundleService->getOneById($id);
            if (empty($userAddress)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = new UserAddressEntity($requestData);

            $userAddressBundleService->updateById($input->toEntity(), $id);

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

    #[OA\Delete(path: '/userAddress/destroy', summary: '删除用户地址接口', security: [['bearerAuth' => []]], tags: ['用户地址模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserAddressDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserAddressDestroyResponse::class))]
    public function destroy(UserAddressDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            $userAddressBundleService = new UserAddressBundleService;
            if ($userAddressBundleService->removeByIds($requestData['ids'])) {
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
