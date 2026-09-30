<?php

declare(strict_types=1);

namespace App\Domains\Product\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Product\Entities\ProductEntity;
use App\Domains\Product\Requests\Product\ProductCreateRequest;
use App\Domains\Product\Requests\Product\ProductDestroyRequest;
use App\Domains\Product\Requests\Product\ProductQueryRequest;
use App\Domains\Product\Requests\Product\ProductUpdateRequest;
use App\Domains\Product\Responses\Product\ProductDestroyResponse;
use App\Domains\Product\Responses\Product\ProductQueryResponse;
use App\Domains\Product\Responses\Product\ProductResponse;
use App\Domains\Product\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class ProductController extends BaseController
{
    public function __construct(
        private readonly ProductService $productService,
    ) {}

    #[OA\Post(path: '/product/search', summary: '查询商品列表接口', security: [['bearerAuth' => []]], tags: ['商品模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ProductQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ProductQueryResponse::class),
        ],
    ))]
    public function search(ProductQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[ProductQueryRequest::getAuditStatus])) {
                $condition[] = [ProductEntity::getAuditStatus, '=', $requestData[ProductQueryRequest::getAuditStatus]];
            }
            if (isset($requestData[ProductQueryRequest::getAuditStatus])) {
                $condition[] = [ProductEntity::getAuditStatus, '=', $requestData[ProductQueryRequest::getAuditStatus]];
            }
            if (isset($requestData[ProductQueryRequest::getStatus])) {
                $condition[] = [ProductEntity::getStatus, '=', $requestData[ProductQueryRequest::getStatus]];
            }
            if (isset($requestData[ProductQueryRequest::getCreatedAt])) {
                $condition[] = [ProductEntity::getCreatedAt, '=', $requestData[ProductQueryRequest::getCreatedAt]];
            }
            if (isset($requestData[ProductQueryRequest::getTitle])) {
                $condition[] = [ProductEntity::getTitle, '=', $requestData[ProductQueryRequest::getTitle]];
            }
            if (isset($requestData[ProductQueryRequest::getId])) {
                $condition[] = [ProductEntity::getId, '=', $requestData[ProductQueryRequest::getId]];
            }

            $result = $this->productService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = ProductResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = ProductQueryResponse::from($result);
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

    #[OA\Post(path: '/product/store', summary: '新增商品接口', security: [['bearerAuth' => []]], tags: ['商品模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ProductCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ProductResponse::class),
        ],
    ))]
    public function store(ProductCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = ProductEntity::from($requestData);

            if ($this->productService->save($input->toEntity())) {
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

    #[OA\Get(path: '/product/show', summary: '获取商品详情接口', security: [['bearerAuth' => []]], tags: ['商品模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ProductResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $product = $this->productService->getOneById($id);
            if (empty($product)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = ProductResponse::from($product);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/product/update', summary: '更新商品接口', security: [['bearerAuth' => []]], tags: ['商品模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ProductUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ProductResponse::class),
        ],
    ))]
    public function update(ProductUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $product = $this->productService->getOneById($id);
            if (empty($product)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = ProductEntity::from($requestData);

            $this->productService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/product/destroy', summary: '删除商品接口', security: [['bearerAuth' => []]], tags: ['商品模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ProductDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ProductDestroyResponse::class),
        ],
    ))]
    public function destroy(ProductDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->productService->removeByIds($requestData['ids'])) {
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
