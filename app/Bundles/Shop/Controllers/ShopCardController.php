<?php

declare(strict_types=1);

namespace App\Bundles\Shop\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Bundles\Shop\Entities\ShopCardEntity;
use App\Bundles\Shop\Requests\ShopCard\ShopCardCreateRequest;
use App\Bundles\Shop\Requests\ShopCard\ShopCardDestroyRequest;
use App\Bundles\Shop\Requests\ShopCard\ShopCardQueryRequest;
use App\Bundles\Shop\Requests\ShopCard\ShopCardUpdateRequest;
use App\Bundles\Shop\Responses\ShopCard\ShopCardDestroyResponse;
use App\Bundles\Shop\Responses\ShopCard\ShopCardQueryResponse;
use App\Bundles\Shop\Responses\ShopCard\ShopCardResponse;
use App\Bundles\Shop\Services\ShopCardBundleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class ShopCardController extends BaseController
{
    #[OA\Post(path: '/shopCard/query', summary: '查询贺卡列表接口', security: [['bearerAuth' => []]], tags: ['贺卡模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ShopCardQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShopCardQueryResponse::class))]
    public function query(ShopCardQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[ShopCardQueryRequest::getCardId])) {
                $condition[] = [ShopCardEntity::getCardId, '=', $requestData[ShopCardQueryRequest::getCardId]];
            }

            $shopCardBundleService = new ShopCardBundleService;
            $result = $shopCardBundleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new ShopCardResponse($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = new ShopCardQueryResponse($result);
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

    #[OA\Post(path: '/shopCard/store', summary: '新增贺卡接口', security: [['bearerAuth' => []]], tags: ['贺卡模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ShopCardCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShopCardResponse::class))]
    public function store(ShopCardCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = new ShopCardEntity($requestData);

            $shopCardBundleService = new ShopCardBundleService;
            if ($shopCardBundleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/shopCard/show', summary: '获取贺卡详情接口', security: [['bearerAuth' => []]], tags: ['贺卡模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShopCardResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $shopCardBundleService = new ShopCardBundleService;
            $shopCard = $shopCardBundleService->getOneById($id);
            if (empty($shopCard)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = new ShopCardResponse($shopCard);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/shopCard/update', summary: '更新贺卡接口', security: [['bearerAuth' => []]], tags: ['贺卡模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ShopCardUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShopCardResponse::class))]
    public function update(ShopCardUpdateRequest $updateRequest): JsonResponse
    {
        $id = intval($updateRequest->query('id', 0));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $shopCardBundleService = new ShopCardBundleService;
            $shopCard = $shopCardBundleService->getOneById($id);
            if (empty($shopCard)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = new ShopCardEntity($requestData);

            $shopCardBundleService->updateById($input->toEntity(), $id);

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

    #[OA\Delete(path: '/shopCard/destroy', summary: '删除贺卡接口', security: [['bearerAuth' => []]], tags: ['贺卡模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ShopCardDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShopCardDestroyResponse::class))]
    public function destroy(ShopCardDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            $shopCardBundleService = new ShopCardBundleService;
            if ($shopCardBundleService->removeByIds($requestData['ids'])) {
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
