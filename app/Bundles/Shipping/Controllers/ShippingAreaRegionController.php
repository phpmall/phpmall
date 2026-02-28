<?php

declare(strict_types=1);

namespace App\Bundles\Shipping\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Bundles\Shipping\Entities\ShippingAreaRegionEntity;
use App\Bundles\Shipping\Requests\ShippingAreaRegion\ShippingAreaRegionCreateRequest;
use App\Bundles\Shipping\Requests\ShippingAreaRegion\ShippingAreaRegionDestroyRequest;
use App\Bundles\Shipping\Requests\ShippingAreaRegion\ShippingAreaRegionQueryRequest;
use App\Bundles\Shipping\Requests\ShippingAreaRegion\ShippingAreaRegionUpdateRequest;
use App\Bundles\Shipping\Responses\ShippingAreaRegion\ShippingAreaRegionDestroyResponse;
use App\Bundles\Shipping\Responses\ShippingAreaRegion\ShippingAreaRegionQueryResponse;
use App\Bundles\Shipping\Responses\ShippingAreaRegion\ShippingAreaRegionResponse;
use App\Bundles\Shipping\Services\ShippingAreaRegionBundleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class ShippingAreaRegionController extends BaseController
{
    #[OA\Post(path: '/shippingAreaRegion/query', summary: '查询配送区域地区列表接口', security: [['bearerAuth' => []]], tags: ['配送区域地区模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ShippingAreaRegionQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShippingAreaRegionQueryResponse::class))]
    public function query(ShippingAreaRegionQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[ShippingAreaRegionQueryRequest::getId])) {
                $condition[] = [ShippingAreaRegionEntity::getId, '=', $requestData[ShippingAreaRegionQueryRequest::getId]];
            }
            if (isset($requestData[ShippingAreaRegionQueryRequest::getRegionId])) {
                $condition[] = [ShippingAreaRegionEntity::getRegionId, '=', $requestData[ShippingAreaRegionQueryRequest::getRegionId]];
            }

            $shippingAreaRegionBundleService = new ShippingAreaRegionBundleService;
            $result = $shippingAreaRegionBundleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new ShippingAreaRegionResponse($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = new ShippingAreaRegionQueryResponse($result);
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

    #[OA\Post(path: '/shippingAreaRegion/store', summary: '新增配送区域地区接口', security: [['bearerAuth' => []]], tags: ['配送区域地区模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ShippingAreaRegionCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShippingAreaRegionResponse::class))]
    public function store(ShippingAreaRegionCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = new ShippingAreaRegionEntity($requestData);

            $shippingAreaRegionBundleService = new ShippingAreaRegionBundleService;
            if ($shippingAreaRegionBundleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/shippingAreaRegion/show', summary: '获取配送区域地区详情接口', security: [['bearerAuth' => []]], tags: ['配送区域地区模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShippingAreaRegionResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $shippingAreaRegionBundleService = new ShippingAreaRegionBundleService;
            $shippingAreaRegion = $shippingAreaRegionBundleService->getOneById($id);
            if (empty($shippingAreaRegion)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = new ShippingAreaRegionResponse($shippingAreaRegion);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/shippingAreaRegion/update', summary: '更新配送区域地区接口', security: [['bearerAuth' => []]], tags: ['配送区域地区模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ShippingAreaRegionUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShippingAreaRegionResponse::class))]
    public function update(ShippingAreaRegionUpdateRequest $updateRequest): JsonResponse
    {
        $id = intval($updateRequest->query('id', 0));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $shippingAreaRegionBundleService = new ShippingAreaRegionBundleService;
            $shippingAreaRegion = $shippingAreaRegionBundleService->getOneById($id);
            if (empty($shippingAreaRegion)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = new ShippingAreaRegionEntity($requestData);

            $shippingAreaRegionBundleService->updateById($input->toEntity(), $id);

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

    #[OA\Delete(path: '/shippingAreaRegion/destroy', summary: '删除配送区域地区接口', security: [['bearerAuth' => []]], tags: ['配送区域地区模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ShippingAreaRegionDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShippingAreaRegionDestroyResponse::class))]
    public function destroy(ShippingAreaRegionDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            $shippingAreaRegionBundleService = new ShippingAreaRegionBundleService;
            if ($shippingAreaRegionBundleService->removeByIds($requestData['ids'])) {
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
