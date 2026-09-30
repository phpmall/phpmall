<?php

declare(strict_types=1);

namespace App\Domains\Product\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Product\Entities\ProductCategoryEntity;
use App\Domains\Product\Requests\ProductCategory\ProductCategoryCreateRequest;
use App\Domains\Product\Requests\ProductCategory\ProductCategoryDestroyRequest;
use App\Domains\Product\Requests\ProductCategory\ProductCategoryQueryRequest;
use App\Domains\Product\Requests\ProductCategory\ProductCategoryUpdateRequest;
use App\Domains\Product\Responses\ProductCategory\ProductCategoryDestroyResponse;
use App\Domains\Product\Responses\ProductCategory\ProductCategoryQueryResponse;
use App\Domains\Product\Responses\ProductCategory\ProductCategoryResponse;
use App\Domains\Product\Services\ProductCategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class ProductCategoryController extends BaseController
{
    public function __construct(
        private readonly ProductCategoryService $productCategoryService,
    ) {}

    #[OA\Post(path: '/productCategory/search', summary: '查询商品分类列表接口', security: [['bearerAuth' => []]], tags: ['商品分类模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ProductCategoryQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ProductCategoryQueryResponse::class),
        ],
    ))]
    public function search(ProductCategoryQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[ProductCategoryQueryRequest::getSortOrder])) {
                $condition[] = [ProductCategoryEntity::getSortOrder, '=', $requestData[ProductCategoryQueryRequest::getSortOrder]];
            }
            if (isset($requestData[ProductCategoryQueryRequest::getParentId])) {
                $condition[] = [ProductCategoryEntity::getParentId, '=', $requestData[ProductCategoryQueryRequest::getParentId]];
            }
            if (isset($requestData[ProductCategoryQueryRequest::getId])) {
                $condition[] = [ProductCategoryEntity::getId, '=', $requestData[ProductCategoryQueryRequest::getId]];
            }

            $result = $this->productCategoryService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = ProductCategoryResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = ProductCategoryQueryResponse::from($result);
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

    #[OA\Post(path: '/productCategory/store', summary: '新增商品分类接口', security: [['bearerAuth' => []]], tags: ['商品分类模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ProductCategoryCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ProductCategoryResponse::class),
        ],
    ))]
    public function store(ProductCategoryCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = ProductCategoryEntity::from($requestData);

            if ($this->productCategoryService->save($input->toEntity())) {
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

    #[OA\Get(path: '/productCategory/show', summary: '获取商品分类详情接口', security: [['bearerAuth' => []]], tags: ['商品分类模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ProductCategoryResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $productCategory = $this->productCategoryService->getOneById($id);
            if (empty($productCategory)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = ProductCategoryResponse::from($productCategory);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/productCategory/update', summary: '更新商品分类接口', security: [['bearerAuth' => []]], tags: ['商品分类模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ProductCategoryUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ProductCategoryResponse::class),
        ],
    ))]
    public function update(ProductCategoryUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $productCategory = $this->productCategoryService->getOneById($id);
            if (empty($productCategory)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = ProductCategoryEntity::from($requestData);

            $this->productCategoryService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/productCategory/destroy', summary: '删除商品分类接口', security: [['bearerAuth' => []]], tags: ['商品分类模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ProductCategoryDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ProductCategoryDestroyResponse::class),
        ],
    ))]
    public function destroy(ProductCategoryDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->productCategoryService->removeByIds($requestData['ids'])) {
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
