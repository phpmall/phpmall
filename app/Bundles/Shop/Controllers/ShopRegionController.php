<?php

declare(strict_types=1);

namespace App\Bundles\Shop\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Bundles\Shop\Entities\ShopRegionEntity;
use App\Bundles\Shop\Requests\ShopRegion\ShopRegionCreateRequest;
use App\Bundles\Shop\Requests\ShopRegion\ShopRegionDestroyRequest;
use App\Bundles\Shop\Requests\ShopRegion\ShopRegionQueryRequest;
use App\Bundles\Shop\Requests\ShopRegion\ShopRegionUpdateRequest;
use App\Bundles\Shop\Responses\ShopRegion\ShopRegionDestroyResponse;
use App\Bundles\Shop\Responses\ShopRegion\ShopRegionQueryResponse;
use App\Bundles\Shop\Responses\ShopRegion\ShopRegionResponse;
use App\Bundles\Shop\Services\ShopRegionBundleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class ShopRegionController extends BaseController
{
    #[OA\Post(path: '/shopRegion/query', summary: '查询地区列表接口', security: [['bearerAuth' => []]], tags: ['地区模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ShopRegionQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShopRegionQueryResponse::class))]
    public function query(ShopRegionQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[ShopRegionQueryRequest::getRegionId])) {
                $condition[] = [ShopRegionEntity::getRegionId, '=', $requestData[ShopRegionQueryRequest::getRegionId]];
            }
            if (isset($requestData[ShopRegionQueryRequest::getAgencyId])) {
                $condition[] = [ShopRegionEntity::getAgencyId, '=', $requestData[ShopRegionQueryRequest::getAgencyId]];
            }
            if (isset($requestData[ShopRegionQueryRequest::getParentId])) {
                $condition[] = [ShopRegionEntity::getParentId, '=', $requestData[ShopRegionQueryRequest::getParentId]];
            }
            if (isset($requestData[ShopRegionQueryRequest::getRegionType])) {
                $condition[] = [ShopRegionEntity::getRegionType, '=', $requestData[ShopRegionQueryRequest::getRegionType]];
            }

            $shopRegionBundleService = new ShopRegionBundleService;
            $result = $shopRegionBundleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new ShopRegionResponse($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = new ShopRegionQueryResponse($result);
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

    #[OA\Post(path: '/shopRegion/store', summary: '新增地区接口', security: [['bearerAuth' => []]], tags: ['地区模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ShopRegionCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShopRegionResponse::class))]
    public function store(ShopRegionCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = new ShopRegionEntity($requestData);

            $shopRegionBundleService = new ShopRegionBundleService;
            if ($shopRegionBundleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/shopRegion/show', summary: '获取地区详情接口', security: [['bearerAuth' => []]], tags: ['地区模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShopRegionResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $shopRegionBundleService = new ShopRegionBundleService;
            $shopRegion = $shopRegionBundleService->getOneById($id);
            if (empty($shopRegion)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = new ShopRegionResponse($shopRegion);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/shopRegion/update', summary: '更新地区接口', security: [['bearerAuth' => []]], tags: ['地区模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ShopRegionUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShopRegionResponse::class))]
    public function update(ShopRegionUpdateRequest $updateRequest): JsonResponse
    {
        $id = intval($updateRequest->query('id', 0));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $shopRegionBundleService = new ShopRegionBundleService;
            $shopRegion = $shopRegionBundleService->getOneById($id);
            if (empty($shopRegion)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = new ShopRegionEntity($requestData);

            $shopRegionBundleService->updateById($input->toEntity(), $id);

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

    #[OA\Delete(path: '/shopRegion/destroy', summary: '删除地区接口', security: [['bearerAuth' => []]], tags: ['地区模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ShopRegionDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShopRegionDestroyResponse::class))]
    public function destroy(ShopRegionDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            $shopRegionBundleService = new ShopRegionBundleService;
            if ($shopRegionBundleService->removeByIds($requestData['ids'])) {
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
