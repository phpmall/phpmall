<?php

declare(strict_types=1);

namespace App\Bundles\Shipping\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Bundles\Shipping\Entities\ShippingAreaEntity;
use App\Bundles\Shipping\Requests\ShippingArea\ShippingAreaCreateRequest;
use App\Bundles\Shipping\Requests\ShippingArea\ShippingAreaDestroyRequest;
use App\Bundles\Shipping\Requests\ShippingArea\ShippingAreaQueryRequest;
use App\Bundles\Shipping\Requests\ShippingArea\ShippingAreaUpdateRequest;
use App\Bundles\Shipping\Responses\ShippingArea\ShippingAreaDestroyResponse;
use App\Bundles\Shipping\Responses\ShippingArea\ShippingAreaQueryResponse;
use App\Bundles\Shipping\Responses\ShippingArea\ShippingAreaResponse;
use App\Bundles\Shipping\Services\ShippingAreaBundleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class ShippingAreaController extends BaseController
{
    #[OA\Post(path: '/shippingArea/query', summary: '查询配送区域列表接口', security: [['bearerAuth' => []]], tags: ['配送区域模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ShippingAreaQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShippingAreaQueryResponse::class))]
    public function query(ShippingAreaQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[ShippingAreaQueryRequest::getShippingAreaId])) {
                $condition[] = [ShippingAreaEntity::getShippingAreaId, '=', $requestData[ShippingAreaQueryRequest::getShippingAreaId]];
            }
            if (isset($requestData[ShippingAreaQueryRequest::getShippingId])) {
                $condition[] = [ShippingAreaEntity::getShippingId, '=', $requestData[ShippingAreaQueryRequest::getShippingId]];
            }

            $shippingAreaBundleService = new ShippingAreaBundleService;
            $result = $shippingAreaBundleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new ShippingAreaResponse($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = new ShippingAreaQueryResponse($result);
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

    #[OA\Post(path: '/shippingArea/store', summary: '新增配送区域接口', security: [['bearerAuth' => []]], tags: ['配送区域模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ShippingAreaCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShippingAreaResponse::class))]
    public function store(ShippingAreaCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = new ShippingAreaEntity($requestData);

            $shippingAreaBundleService = new ShippingAreaBundleService;
            if ($shippingAreaBundleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/shippingArea/show', summary: '获取配送区域详情接口', security: [['bearerAuth' => []]], tags: ['配送区域模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShippingAreaResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $shippingAreaBundleService = new ShippingAreaBundleService;
            $shippingArea = $shippingAreaBundleService->getOneById($id);
            if (empty($shippingArea)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = new ShippingAreaResponse($shippingArea);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/shippingArea/update', summary: '更新配送区域接口', security: [['bearerAuth' => []]], tags: ['配送区域模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ShippingAreaUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShippingAreaResponse::class))]
    public function update(ShippingAreaUpdateRequest $updateRequest): JsonResponse
    {
        $id = intval($updateRequest->query('id', 0));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $shippingAreaBundleService = new ShippingAreaBundleService;
            $shippingArea = $shippingAreaBundleService->getOneById($id);
            if (empty($shippingArea)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = new ShippingAreaEntity($requestData);

            $shippingAreaBundleService->updateById($input->toEntity(), $id);

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

    #[OA\Delete(path: '/shippingArea/destroy', summary: '删除配送区域接口', security: [['bearerAuth' => []]], tags: ['配送区域模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ShippingAreaDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShippingAreaDestroyResponse::class))]
    public function destroy(ShippingAreaDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            $shippingAreaBundleService = new ShippingAreaBundleService;
            if ($shippingAreaBundleService->removeByIds($requestData['ids'])) {
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
