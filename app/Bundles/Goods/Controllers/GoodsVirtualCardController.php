<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Bundles\Goods\Entities\GoodsVirtualCardEntity;
use App\Bundles\Goods\Requests\GoodsVirtualCard\GoodsVirtualCardCreateRequest;
use App\Bundles\Goods\Requests\GoodsVirtualCard\GoodsVirtualCardDestroyRequest;
use App\Bundles\Goods\Requests\GoodsVirtualCard\GoodsVirtualCardQueryRequest;
use App\Bundles\Goods\Requests\GoodsVirtualCard\GoodsVirtualCardUpdateRequest;
use App\Bundles\Goods\Responses\GoodsVirtualCard\GoodsVirtualCardDestroyResponse;
use App\Bundles\Goods\Responses\GoodsVirtualCard\GoodsVirtualCardQueryResponse;
use App\Bundles\Goods\Responses\GoodsVirtualCard\GoodsVirtualCardResponse;
use App\Bundles\Goods\Services\GoodsVirtualCardBundleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class GoodsVirtualCardController extends BaseController
{
    #[OA\Post(path: '/goodsVirtualCard/query', summary: '查询虚拟商品卡密列表接口', security: [['bearerAuth' => []]], tags: ['虚拟商品卡密模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: GoodsVirtualCardQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: GoodsVirtualCardQueryResponse::class))]
    public function query(GoodsVirtualCardQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[GoodsVirtualCardQueryRequest::getCardSn])) {
                $condition[] = [GoodsVirtualCardEntity::getCardSn, '=', $requestData[GoodsVirtualCardQueryRequest::getCardSn]];
            }
            if (isset($requestData[GoodsVirtualCardQueryRequest::getGoodsId])) {
                $condition[] = [GoodsVirtualCardEntity::getGoodsId, '=', $requestData[GoodsVirtualCardQueryRequest::getGoodsId]];
            }
            if (isset($requestData[GoodsVirtualCardQueryRequest::getIsSaled])) {
                $condition[] = [GoodsVirtualCardEntity::getIsSaled, '=', $requestData[GoodsVirtualCardQueryRequest::getIsSaled]];
            }
            if (isset($requestData[GoodsVirtualCardQueryRequest::getCardId])) {
                $condition[] = [GoodsVirtualCardEntity::getCardId, '=', $requestData[GoodsVirtualCardQueryRequest::getCardId]];
            }

            $goodsVirtualCardBundleService = new GoodsVirtualCardBundleService;
            $result = $goodsVirtualCardBundleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new GoodsVirtualCardResponse($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = new GoodsVirtualCardQueryResponse($result);
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

    #[OA\Post(path: '/goodsVirtualCard/store', summary: '新增虚拟商品卡密接口', security: [['bearerAuth' => []]], tags: ['虚拟商品卡密模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: GoodsVirtualCardCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: GoodsVirtualCardResponse::class))]
    public function store(GoodsVirtualCardCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = new GoodsVirtualCardEntity($requestData);

            $goodsVirtualCardBundleService = new GoodsVirtualCardBundleService;
            if ($goodsVirtualCardBundleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/goodsVirtualCard/show', summary: '获取虚拟商品卡密详情接口', security: [['bearerAuth' => []]], tags: ['虚拟商品卡密模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: GoodsVirtualCardResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $goodsVirtualCardBundleService = new GoodsVirtualCardBundleService;
            $goodsVirtualCard = $goodsVirtualCardBundleService->getOneById($id);
            if (empty($goodsVirtualCard)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = new GoodsVirtualCardResponse($goodsVirtualCard);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/goodsVirtualCard/update', summary: '更新虚拟商品卡密接口', security: [['bearerAuth' => []]], tags: ['虚拟商品卡密模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: GoodsVirtualCardUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: GoodsVirtualCardResponse::class))]
    public function update(GoodsVirtualCardUpdateRequest $updateRequest): JsonResponse
    {
        $id = intval($updateRequest->query('id', 0));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $goodsVirtualCardBundleService = new GoodsVirtualCardBundleService;
            $goodsVirtualCard = $goodsVirtualCardBundleService->getOneById($id);
            if (empty($goodsVirtualCard)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = new GoodsVirtualCardEntity($requestData);

            $goodsVirtualCardBundleService->updateById($input->toEntity(), $id);

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

    #[OA\Delete(path: '/goodsVirtualCard/destroy', summary: '删除虚拟商品卡密接口', security: [['bearerAuth' => []]], tags: ['虚拟商品卡密模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: GoodsVirtualCardDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: GoodsVirtualCardDestroyResponse::class))]
    public function destroy(GoodsVirtualCardDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            $goodsVirtualCardBundleService = new GoodsVirtualCardBundleService;
            if ($goodsVirtualCardBundleService->removeByIds($requestData['ids'])) {
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
