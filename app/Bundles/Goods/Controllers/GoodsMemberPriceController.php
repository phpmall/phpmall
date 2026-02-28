<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Bundles\Goods\Entities\GoodsMemberPriceEntity;
use App\Bundles\Goods\Requests\GoodsMemberPrice\GoodsMemberPriceCreateRequest;
use App\Bundles\Goods\Requests\GoodsMemberPrice\GoodsMemberPriceDestroyRequest;
use App\Bundles\Goods\Requests\GoodsMemberPrice\GoodsMemberPriceQueryRequest;
use App\Bundles\Goods\Requests\GoodsMemberPrice\GoodsMemberPriceUpdateRequest;
use App\Bundles\Goods\Responses\GoodsMemberPrice\GoodsMemberPriceDestroyResponse;
use App\Bundles\Goods\Responses\GoodsMemberPrice\GoodsMemberPriceQueryResponse;
use App\Bundles\Goods\Responses\GoodsMemberPrice\GoodsMemberPriceResponse;
use App\Bundles\Goods\Services\GoodsMemberPriceBundleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class GoodsMemberPriceController extends BaseController
{
    #[OA\Post(path: '/goodsMemberPrice/query', summary: '查询商品会员价格列表接口', security: [['bearerAuth' => []]], tags: ['商品会员价格模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: GoodsMemberPriceQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: GoodsMemberPriceQueryResponse::class))]
    public function query(GoodsMemberPriceQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[GoodsMemberPriceQueryRequest::getUserRank])) {
                $condition[] = [GoodsMemberPriceEntity::getUserRank, '=', $requestData[GoodsMemberPriceQueryRequest::getUserRank]];
            }
            if (isset($requestData[GoodsMemberPriceQueryRequest::getPriceId])) {
                $condition[] = [GoodsMemberPriceEntity::getPriceId, '=', $requestData[GoodsMemberPriceQueryRequest::getPriceId]];
            }

            $goodsMemberPriceBundleService = new GoodsMemberPriceBundleService;
            $result = $goodsMemberPriceBundleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new GoodsMemberPriceResponse($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = new GoodsMemberPriceQueryResponse($result);
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

    #[OA\Post(path: '/goodsMemberPrice/store', summary: '新增商品会员价格接口', security: [['bearerAuth' => []]], tags: ['商品会员价格模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: GoodsMemberPriceCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: GoodsMemberPriceResponse::class))]
    public function store(GoodsMemberPriceCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = new GoodsMemberPriceEntity($requestData);

            $goodsMemberPriceBundleService = new GoodsMemberPriceBundleService;
            if ($goodsMemberPriceBundleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/goodsMemberPrice/show', summary: '获取商品会员价格详情接口', security: [['bearerAuth' => []]], tags: ['商品会员价格模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: GoodsMemberPriceResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $goodsMemberPriceBundleService = new GoodsMemberPriceBundleService;
            $goodsMemberPrice = $goodsMemberPriceBundleService->getOneById($id);
            if (empty($goodsMemberPrice)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = new GoodsMemberPriceResponse($goodsMemberPrice);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/goodsMemberPrice/update', summary: '更新商品会员价格接口', security: [['bearerAuth' => []]], tags: ['商品会员价格模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: GoodsMemberPriceUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: GoodsMemberPriceResponse::class))]
    public function update(GoodsMemberPriceUpdateRequest $updateRequest): JsonResponse
    {
        $id = intval($updateRequest->query('id', 0));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $goodsMemberPriceBundleService = new GoodsMemberPriceBundleService;
            $goodsMemberPrice = $goodsMemberPriceBundleService->getOneById($id);
            if (empty($goodsMemberPrice)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = new GoodsMemberPriceEntity($requestData);

            $goodsMemberPriceBundleService->updateById($input->toEntity(), $id);

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

    #[OA\Delete(path: '/goodsMemberPrice/destroy', summary: '删除商品会员价格接口', security: [['bearerAuth' => []]], tags: ['商品会员价格模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: GoodsMemberPriceDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: GoodsMemberPriceDestroyResponse::class))]
    public function destroy(GoodsMemberPriceDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            $goodsMemberPriceBundleService = new GoodsMemberPriceBundleService;
            if ($goodsMemberPriceBundleService->removeByIds($requestData['ids'])) {
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
