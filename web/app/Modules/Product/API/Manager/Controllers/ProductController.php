<?php

declare(strict_types=1);

namespace App\Modules\Product\API\Manager\Controllers;

use App\API\Manager\Controllers\BaseController;
use App\Modules\Product\API\Manager\Requests\Product\ProductCreateRequest;
use App\Modules\Product\API\Manager\Requests\Product\ProductQueryRequest;
use App\Modules\Product\API\Manager\Requests\Product\ProductUpdateRequest;
use App\Modules\Product\API\Manager\Responses\Product\ProductDestroyResponse;
use App\Modules\Product\API\Manager\Responses\Product\ProductQueryResponse;
use App\Modules\Product\API\Manager\Responses\Product\ProductResponse;
use App\Modules\Product\Enums\Product\ProductErrorEnum;
use App\Entities\ProductEntity;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Exceptions\CustomException;
use OpenApi\Attributes as OA;
use Throwable;

class ProductController extends BaseController
{
    #[OA\Post(path: '/product/query', summary: '查询列表接口', security: [['bearerAuth' => []]], tags: ['商品模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ProductQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ProductQueryResponse::class))]
    public function query(ProductQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $request = $queryRequest->validated();

        try {
            $condition = [];

            $productService = new ProductService();
            $result = $productService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new ProductResponse();
                $response->setData($item);
                $result['data'][$key] = $response->toArray();
            }

            return $this->success($result);
        } catch (Throwable $e) {
            if ($e instanceof CustomException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(ProductErrorEnum::QUERY_ERROR);
        }
    }

    #[OA\Post(path: '/product/create', summary: '新增接口', security: [['bearerAuth' => []]], tags: ['商品模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ProductCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ProductResponse::class))]
    public function create(ProductCreateRequest $createRequest): JsonResponse
    {
        $request = $createRequest->validated();

        DB::beginTransaction();
        try {
            $input = new ProductEntity();
            $input->setData($request);

            $productService = new ProductService();
            if ($productService->save($input->toArray())) {
                DB::commit();

                return $this->success();
            }

            throw new CustomException(ProductErrorEnum::CREATE_FAIL);
        } catch (Throwable $e) {
            DB::rollBack();

            if ($e instanceof CustomException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(ProductErrorEnum::CREATE_ERROR);
        }
    }

    #[OA\Get(path: '/product/show', summary: '获取详情接口', security: [['bearerAuth' => []]], tags: ['商品模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ProductResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $productService = new ProductService();

            $product = $productService->getOneById($id);
            if (empty($product)) {
                throw new CustomException(ProductErrorEnum::NOT_FOUND);
            }

            $response = new ProductResponse();
            $response->setData($product);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof CustomException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(ProductErrorEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/product/update', summary: '更新接口', security: [['bearerAuth' => []]], tags: ['商品模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ProductUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ProductResponse::class))]
    public function update(ProductUpdateRequest $updateRequest): JsonResponse
    {
        $request = $updateRequest->validated();
        $id = intval($request->query('id', 0));

        DB::beginTransaction();
        try {
            $productService = new ProductService();

            $product = $productService->getOneById($id);
            if (empty($product)) {
                throw new CustomException(ProductErrorEnum::NOT_FOUND);
            }

            $input = new ProductEntity();
            $input->setData($request);

            $productService->updateById($input->toArray(), $id);

            DB::commit();

            return $this->success();
        } catch (Throwable $e) {
            DB::rollBack();

            if ($e instanceof CustomException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(ProductErrorEnum::UPDATE_ERROR);
        }
    }

    #[OA\Delete(path: '/product/destroy', summary: '删除接口', security: [['bearerAuth' => []]], tags: ['商品模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ProductDestroyResponse::class))]
    public function destroy(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        DB::beginTransaction();
        try {
            $productService = new ProductService();

            $product = $productService->getOneById($id);
            if (empty($product)) {
                throw new CustomException(ProductErrorEnum::NOT_FOUND);
            }

            if ($productService->removeById($id)) {
                DB::commit();

                return $this->success();
            }

            throw new CustomException(ProductErrorEnum::DESTROY_FAIL);
        } catch (Throwable $e) {
            DB::rollBack();

            if ($e instanceof CustomException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(ProductErrorEnum::DESTROY_ERROR);
        }
    }
}
