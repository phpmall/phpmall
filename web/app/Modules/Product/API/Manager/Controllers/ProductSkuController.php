<?php

declare(strict_types=1);

namespace App\Modules\Product\API\Manager\Controllers;

use App\API\Manager\Controllers\BaseController;
use App\Modules\Product\API\Manager\Requests\ProductSku\ProductSkuCreateRequest;
use App\Modules\Product\API\Manager\Requests\ProductSku\ProductSkuQueryRequest;
use App\Modules\Product\API\Manager\Requests\ProductSku\ProductSkuUpdateRequest;
use App\Modules\Product\API\Manager\Responses\ProductSku\ProductSkuDestroyResponse;
use App\Modules\Product\API\Manager\Responses\ProductSku\ProductSkuQueryResponse;
use App\Modules\Product\API\Manager\Responses\ProductSku\ProductSkuResponse;
use App\Modules\Product\Enums\ProductSku\ProductSkuErrorEnum;
use App\Entities\ProductSkuEntity;
use App\Services\ProductSkuService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Exceptions\CustomException;
use OpenApi\Attributes as OA;
use Throwable;

class ProductSkuController extends BaseController
{
    #[OA\Post(path: '/productSku/query', summary: '查询列表接口', security: [['bearerAuth' => []]], tags: ['商品货品模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ProductSkuQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ProductSkuQueryResponse::class))]
    public function query(ProductSkuQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $request = $queryRequest->validated();

        try {
            $condition = [];

            $productSkuService = new ProductSkuService();
            $result = $productSkuService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new ProductSkuResponse();
                $response->setData($item);
                $result['data'][$key] = $response->toArray();
            }

            return $this->success($result);
        } catch (Throwable $e) {
            if ($e instanceof CustomException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(ProductSkuErrorEnum::QUERY_ERROR);
        }
    }

    #[OA\Post(path: '/productSku/create', summary: '新增接口', security: [['bearerAuth' => []]], tags: ['商品货品模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ProductSkuCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ProductSkuResponse::class))]
    public function create(ProductSkuCreateRequest $createRequest): JsonResponse
    {
        $request = $createRequest->validated();

        DB::beginTransaction();
        try {
            $input = new ProductSkuEntity();
            $input->setData($request);

            $productSkuService = new ProductSkuService();
            if ($productSkuService->save($input->toArray())) {
                DB::commit();

                return $this->success();
            }

            throw new CustomException(ProductSkuErrorEnum::CREATE_FAIL);
        } catch (Throwable $e) {
            DB::rollBack();

            if ($e instanceof CustomException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(ProductSkuErrorEnum::CREATE_ERROR);
        }
    }

    #[OA\Get(path: '/productSku/show', summary: '获取详情接口', security: [['bearerAuth' => []]], tags: ['商品货品模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ProductSkuResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $productSkuService = new ProductSkuService();

            $productSku = $productSkuService->getOneById($id);
            if (empty($productSku)) {
                throw new CustomException(ProductSkuErrorEnum::NOT_FOUND);
            }

            $response = new ProductSkuResponse();
            $response->setData($productSku);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof CustomException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(ProductSkuErrorEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/productSku/update', summary: '更新接口', security: [['bearerAuth' => []]], tags: ['商品货品模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ProductSkuUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ProductSkuResponse::class))]
    public function update(ProductSkuUpdateRequest $updateRequest): JsonResponse
    {
        $request = $updateRequest->validated();
        $id = intval($request->query('id', 0));

        DB::beginTransaction();
        try {
            $productSkuService = new ProductSkuService();

            $productSku = $productSkuService->getOneById($id);
            if (empty($productSku)) {
                throw new CustomException(ProductSkuErrorEnum::NOT_FOUND);
            }

            $input = new ProductSkuEntity();
            $input->setData($request);

            $productSkuService->updateById($input->toArray(), $id);

            DB::commit();

            return $this->success();
        } catch (Throwable $e) {
            DB::rollBack();

            if ($e instanceof CustomException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(ProductSkuErrorEnum::UPDATE_ERROR);
        }
    }

    #[OA\Delete(path: '/productSku/destroy', summary: '删除接口', security: [['bearerAuth' => []]], tags: ['商品货品模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ProductSkuDestroyResponse::class))]
    public function destroy(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        DB::beginTransaction();
        try {
            $productSkuService = new ProductSkuService();

            $productSku = $productSkuService->getOneById($id);
            if (empty($productSku)) {
                throw new CustomException(ProductSkuErrorEnum::NOT_FOUND);
            }

            if ($productSkuService->removeById($id)) {
                DB::commit();

                return $this->success();
            }

            throw new CustomException(ProductSkuErrorEnum::DESTROY_FAIL);
        } catch (Throwable $e) {
            DB::rollBack();

            if ($e instanceof CustomException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(ProductSkuErrorEnum::DESTROY_ERROR);
        }
    }
}
