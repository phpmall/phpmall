<?php

declare(strict_types=1);

namespace App\Bundles\Shop\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Bundles\Shop\Entities\ShopAutoManageEntity;
use App\Bundles\Shop\Requests\ShopAutoManage\ShopAutoManageCreateRequest;
use App\Bundles\Shop\Requests\ShopAutoManage\ShopAutoManageDestroyRequest;
use App\Bundles\Shop\Requests\ShopAutoManage\ShopAutoManageQueryRequest;
use App\Bundles\Shop\Requests\ShopAutoManage\ShopAutoManageUpdateRequest;
use App\Bundles\Shop\Responses\ShopAutoManage\ShopAutoManageDestroyResponse;
use App\Bundles\Shop\Responses\ShopAutoManage\ShopAutoManageQueryResponse;
use App\Bundles\Shop\Responses\ShopAutoManage\ShopAutoManageResponse;
use App\Bundles\Shop\Services\ShopAutoManageBundleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class ShopAutoManageController extends BaseController
{
    #[OA\Post(path: '/shopAutoManage/query', summary: '查询自动管理列表接口', security: [['bearerAuth' => []]], tags: ['自动管理模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ShopAutoManageQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShopAutoManageQueryResponse::class))]
    public function query(ShopAutoManageQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[ShopAutoManageQueryRequest::getType])) {
                $condition[] = [ShopAutoManageEntity::getType, '=', $requestData[ShopAutoManageQueryRequest::getType]];
            }
            if (isset($requestData[ShopAutoManageQueryRequest::getId])) {
                $condition[] = [ShopAutoManageEntity::getId, '=', $requestData[ShopAutoManageQueryRequest::getId]];
            }

            $shopAutoManageBundleService = new ShopAutoManageBundleService;
            $result = $shopAutoManageBundleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new ShopAutoManageResponse($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = new ShopAutoManageQueryResponse($result);
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

    #[OA\Post(path: '/shopAutoManage/store', summary: '新增自动管理接口', security: [['bearerAuth' => []]], tags: ['自动管理模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ShopAutoManageCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShopAutoManageResponse::class))]
    public function store(ShopAutoManageCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = new ShopAutoManageEntity($requestData);

            $shopAutoManageBundleService = new ShopAutoManageBundleService;
            if ($shopAutoManageBundleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/shopAutoManage/show', summary: '获取自动管理详情接口', security: [['bearerAuth' => []]], tags: ['自动管理模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShopAutoManageResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $shopAutoManageBundleService = new ShopAutoManageBundleService;
            $shopAutoManage = $shopAutoManageBundleService->getOneById($id);
            if (empty($shopAutoManage)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = new ShopAutoManageResponse($shopAutoManage);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/shopAutoManage/update', summary: '更新自动管理接口', security: [['bearerAuth' => []]], tags: ['自动管理模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ShopAutoManageUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShopAutoManageResponse::class))]
    public function update(ShopAutoManageUpdateRequest $updateRequest): JsonResponse
    {
        $id = intval($updateRequest->query('id', 0));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $shopAutoManageBundleService = new ShopAutoManageBundleService;
            $shopAutoManage = $shopAutoManageBundleService->getOneById($id);
            if (empty($shopAutoManage)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = new ShopAutoManageEntity($requestData);

            $shopAutoManageBundleService->updateById($input->toEntity(), $id);

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

    #[OA\Delete(path: '/shopAutoManage/destroy', summary: '删除自动管理接口', security: [['bearerAuth' => []]], tags: ['自动管理模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ShopAutoManageDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShopAutoManageDestroyResponse::class))]
    public function destroy(ShopAutoManageDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            $shopAutoManageBundleService = new ShopAutoManageBundleService;
            if ($shopAutoManageBundleService->removeByIds($requestData['ids'])) {
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
