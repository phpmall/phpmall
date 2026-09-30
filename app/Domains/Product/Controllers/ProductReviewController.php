<?php

declare(strict_types=1);

namespace App\Domains\Product\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Product\Entities\ProductReviewEntity;
use App\Domains\Product\Requests\ProductReview\ProductReviewCreateRequest;
use App\Domains\Product\Requests\ProductReview\ProductReviewDestroyRequest;
use App\Domains\Product\Requests\ProductReview\ProductReviewQueryRequest;
use App\Domains\Product\Requests\ProductReview\ProductReviewUpdateRequest;
use App\Domains\Product\Responses\ProductReview\ProductReviewDestroyResponse;
use App\Domains\Product\Responses\ProductReview\ProductReviewQueryResponse;
use App\Domains\Product\Responses\ProductReview\ProductReviewResponse;
use App\Domains\Product\Services\ProductReviewService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class ProductReviewController extends BaseController
{
    public function __construct(
        private readonly ProductReviewService $productReviewService,
    ) {}

    #[OA\Post(path: '/productReview/search', summary: '查询列表接口', security: [['bearerAuth' => []]], tags: ['模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ProductReviewQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ProductReviewQueryResponse::class),
        ],
    ))]
    public function search(ProductReviewQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[ProductReviewQueryRequest::getMerchantId])) {
                $condition[] = [ProductReviewEntity::getMerchantId, '=', $requestData[ProductReviewQueryRequest::getMerchantId]];
            }
            if (isset($requestData[ProductReviewQueryRequest::getOrderItemId])) {
                $condition[] = [ProductReviewEntity::getOrderItemId, '=', $requestData[ProductReviewQueryRequest::getOrderItemId]];
            }
            if (isset($requestData[ProductReviewQueryRequest::getProductId])) {
                $condition[] = [ProductReviewEntity::getProductId, '=', $requestData[ProductReviewQueryRequest::getProductId]];
            }
            if (isset($requestData[ProductReviewQueryRequest::getUserId])) {
                $condition[] = [ProductReviewEntity::getUserId, '=', $requestData[ProductReviewQueryRequest::getUserId]];
            }
            if (isset($requestData[ProductReviewQueryRequest::getId])) {
                $condition[] = [ProductReviewEntity::getId, '=', $requestData[ProductReviewQueryRequest::getId]];
            }

            $result = $this->productReviewService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = ProductReviewResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = ProductReviewQueryResponse::from($result);
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

    #[OA\Post(path: '/productReview/store', summary: '新增接口', security: [['bearerAuth' => []]], tags: ['模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ProductReviewCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ProductReviewResponse::class),
        ],
    ))]
    public function store(ProductReviewCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = ProductReviewEntity::from($requestData);

            if ($this->productReviewService->save($input->toEntity())) {
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

    #[OA\Get(path: '/productReview/show', summary: '获取详情接口', security: [['bearerAuth' => []]], tags: ['模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ProductReviewResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $productReview = $this->productReviewService->getOneById($id);
            if (empty($productReview)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = ProductReviewResponse::from($productReview);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/productReview/update', summary: '更新接口', security: [['bearerAuth' => []]], tags: ['模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ProductReviewUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ProductReviewResponse::class),
        ],
    ))]
    public function update(ProductReviewUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $productReview = $this->productReviewService->getOneById($id);
            if (empty($productReview)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = ProductReviewEntity::from($requestData);

            $this->productReviewService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/productReview/destroy', summary: '删除接口', security: [['bearerAuth' => []]], tags: ['模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ProductReviewDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: ProductReviewDestroyResponse::class),
        ],
    ))]
    public function destroy(ProductReviewDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->productReviewService->removeByIds($requestData['ids'])) {
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
