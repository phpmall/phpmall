<?php

declare(strict_types=1);

namespace App\Domains\Product\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Product\Entities\ProductSkuEntity;
use App\Domains\Product\Requests\ProductSku\ProductSkuCreateRequest;
use App\Domains\Product\Requests\ProductSku\ProductSkuDestroyRequest;
use App\Domains\Product\Requests\ProductSku\ProductSkuQueryRequest;
use App\Domains\Product\Requests\ProductSku\ProductSkuUpdateRequest;
use App\Domains\Product\Responses\ProductSku\ProductSkuDestroyResponse;
use App\Domains\Product\Responses\ProductSku\ProductSkuQueryResponse;
use App\Domains\Product\Responses\ProductSku\ProductSkuResponse;
use App\Domains\Product\Services\ProductSkuService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class ProductSkuController extends BaseController
{
    public function __construct(
        private readonly ProductSkuService $productSkuService,
    ) {}

    #[OA\Post(path: '/productSku/search', summary: '查询列表接口', security: [['bearerAuth' => []]], tags: ['模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ProductSkuQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ProductSkuQueryResponse::class),
        ],
    ))]
    public function search(ProductSkuQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[ProductSkuQueryRequest::getMerchantId])) {
                $condition[] = [ProductSkuEntity::getMerchantId, '=', $requestData[ProductSkuQueryRequest::getMerchantId]];
            }
            if (isset($requestData[ProductSkuQueryRequest::getProductId])) {
                $condition[] = [ProductSkuEntity::getProductId, '=', $requestData[ProductSkuQueryRequest::getProductId]];
            }
            if (isset($requestData[ProductSkuQueryRequest::getStatus])) {
                $condition[] = [ProductSkuEntity::getStatus, '=', $requestData[ProductSkuQueryRequest::getStatus]];
            }
            if (isset($requestData[ProductSkuQueryRequest::getId])) {
                $condition[] = [ProductSkuEntity::getId, '=', $requestData[ProductSkuQueryRequest::getId]];
            }

            $result = $this->productSkuService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = ProductSkuResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = ProductSkuQueryResponse::from($result);
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

    #[OA\Post(path: '/productSku/store', summary: '新增接口', security: [['bearerAuth' => []]], tags: ['模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ProductSkuCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ProductSkuResponse::class),
        ],
    ))]
    public function store(ProductSkuCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = ProductSkuEntity::from($requestData);

            if ($this->productSkuService->save($input->toEntity())) {
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

    #[OA\Get(path: '/productSku/show', summary: '获取详情接口', security: [['bearerAuth' => []]], tags: ['模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ProductSkuResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $productSku = $this->productSkuService->getOneById($id);
            if (empty($productSku)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = ProductSkuResponse::from($productSku);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/productSku/update', summary: '更新接口', security: [['bearerAuth' => []]], tags: ['模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ProductSkuUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ProductSkuResponse::class),
        ],
    ))]
    public function update(ProductSkuUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $productSku = $this->productSkuService->getOneById($id);
            if (empty($productSku)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = ProductSkuEntity::from($requestData);

            $this->productSkuService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/productSku/destroy', summary: '删除接口', security: [['bearerAuth' => []]], tags: ['模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ProductSkuDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ProductSkuDestroyResponse::class),
        ],
    ))]
    public function destroy(ProductSkuDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->productSkuService->removeByIds($requestData['ids'])) {
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
