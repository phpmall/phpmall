<?php

declare(strict_types=1);

namespace App\Bundles\Shipping\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Bundles\Shipping\Entities\ShippingEntity;
use App\Bundles\Shipping\Requests\Shipping\ShippingCreateRequest;
use App\Bundles\Shipping\Requests\Shipping\ShippingDestroyRequest;
use App\Bundles\Shipping\Requests\Shipping\ShippingQueryRequest;
use App\Bundles\Shipping\Requests\Shipping\ShippingUpdateRequest;
use App\Bundles\Shipping\Responses\Shipping\ShippingDestroyResponse;
use App\Bundles\Shipping\Responses\Shipping\ShippingQueryResponse;
use App\Bundles\Shipping\Responses\Shipping\ShippingResponse;
use App\Bundles\Shipping\Services\ShippingBundleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class ShippingController extends BaseController
{
    #[OA\Post(path: '/shipping/query', summary: '查询配送方式列表接口', security: [['bearerAuth' => []]], tags: ['配送方式模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ShippingQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShippingQueryResponse::class))]
    public function query(ShippingQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[ShippingQueryRequest::getShippingId])) {
                $condition[] = [ShippingEntity::getShippingId, '=', $requestData[ShippingQueryRequest::getShippingId]];
            }
            if (isset($requestData[ShippingQueryRequest::getEnabled])) {
                $condition[] = [ShippingEntity::getEnabled, '=', $requestData[ShippingQueryRequest::getEnabled]];
            }

            $shippingBundleService = new ShippingBundleService;
            $result = $shippingBundleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new ShippingResponse($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = new ShippingQueryResponse($result);
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

    #[OA\Post(path: '/shipping/store', summary: '新增配送方式接口', security: [['bearerAuth' => []]], tags: ['配送方式模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ShippingCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShippingResponse::class))]
    public function store(ShippingCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = new ShippingEntity($requestData);

            $shippingBundleService = new ShippingBundleService;
            if ($shippingBundleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/shipping/show', summary: '获取配送方式详情接口', security: [['bearerAuth' => []]], tags: ['配送方式模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShippingResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $shippingBundleService = new ShippingBundleService;
            $shipping = $shippingBundleService->getOneById($id);
            if (empty($shipping)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = new ShippingResponse($shipping);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/shipping/update', summary: '更新配送方式接口', security: [['bearerAuth' => []]], tags: ['配送方式模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ShippingUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShippingResponse::class))]
    public function update(ShippingUpdateRequest $updateRequest): JsonResponse
    {
        $id = intval($updateRequest->query('id', 0));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $shippingBundleService = new ShippingBundleService;
            $shipping = $shippingBundleService->getOneById($id);
            if (empty($shipping)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = new ShippingEntity($requestData);

            $shippingBundleService->updateById($input->toEntity(), $id);

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

    #[OA\Delete(path: '/shipping/destroy', summary: '删除配送方式接口', security: [['bearerAuth' => []]], tags: ['配送方式模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ShippingDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShippingDestroyResponse::class))]
    public function destroy(ShippingDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            $shippingBundleService = new ShippingBundleService;
            if ($shippingBundleService->removeByIds($requestData['ids'])) {
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
