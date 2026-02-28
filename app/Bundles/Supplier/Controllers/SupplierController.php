<?php

declare(strict_types=1);

namespace App\Bundles\Supplier\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Bundles\Supplier\Entities\SupplierEntity;
use App\Bundles\Supplier\Requests\Supplier\SupplierCreateRequest;
use App\Bundles\Supplier\Requests\Supplier\SupplierDestroyRequest;
use App\Bundles\Supplier\Requests\Supplier\SupplierQueryRequest;
use App\Bundles\Supplier\Requests\Supplier\SupplierUpdateRequest;
use App\Bundles\Supplier\Responses\Supplier\SupplierDestroyResponse;
use App\Bundles\Supplier\Responses\Supplier\SupplierQueryResponse;
use App\Bundles\Supplier\Responses\Supplier\SupplierResponse;
use App\Bundles\Supplier\Services\SupplierBundleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class SupplierController extends BaseController
{
    #[OA\Post(path: '/supplier/query', summary: '查询供应商列表接口', security: [['bearerAuth' => []]], tags: ['供应商模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SupplierQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: SupplierQueryResponse::class))]
    public function query(SupplierQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[SupplierQueryRequest::getSuppliersId])) {
                $condition[] = [SupplierEntity::getSuppliersId, '=', $requestData[SupplierQueryRequest::getSuppliersId]];
            }

            $supplierBundleService = new SupplierBundleService;
            $result = $supplierBundleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new SupplierResponse($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = new SupplierQueryResponse($result);
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

    #[OA\Post(path: '/supplier/store', summary: '新增供应商接口', security: [['bearerAuth' => []]], tags: ['供应商模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SupplierCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: SupplierResponse::class))]
    public function store(SupplierCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = new SupplierEntity($requestData);

            $supplierBundleService = new SupplierBundleService;
            if ($supplierBundleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/supplier/show', summary: '获取供应商详情接口', security: [['bearerAuth' => []]], tags: ['供应商模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: SupplierResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $supplierBundleService = new SupplierBundleService;
            $supplier = $supplierBundleService->getOneById($id);
            if (empty($supplier)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = new SupplierResponse($supplier);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/supplier/update', summary: '更新供应商接口', security: [['bearerAuth' => []]], tags: ['供应商模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SupplierUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: SupplierResponse::class))]
    public function update(SupplierUpdateRequest $updateRequest): JsonResponse
    {
        $id = intval($updateRequest->query('id', 0));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $supplierBundleService = new SupplierBundleService;
            $supplier = $supplierBundleService->getOneById($id);
            if (empty($supplier)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = new SupplierEntity($requestData);

            $supplierBundleService->updateById($input->toEntity(), $id);

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

    #[OA\Delete(path: '/supplier/destroy', summary: '删除供应商接口', security: [['bearerAuth' => []]], tags: ['供应商模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SupplierDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: SupplierDestroyResponse::class))]
    public function destroy(SupplierDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            $supplierBundleService = new SupplierBundleService;
            if ($supplierBundleService->removeByIds($requestData['ids'])) {
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
