<?php

declare(strict_types=1);

namespace App\Bundles\Shop\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Bundles\Shop\Entities\ShopPackEntity;
use App\Bundles\Shop\Requests\ShopPack\ShopPackCreateRequest;
use App\Bundles\Shop\Requests\ShopPack\ShopPackDestroyRequest;
use App\Bundles\Shop\Requests\ShopPack\ShopPackQueryRequest;
use App\Bundles\Shop\Requests\ShopPack\ShopPackUpdateRequest;
use App\Bundles\Shop\Responses\ShopPack\ShopPackDestroyResponse;
use App\Bundles\Shop\Responses\ShopPack\ShopPackQueryResponse;
use App\Bundles\Shop\Responses\ShopPack\ShopPackResponse;
use App\Bundles\Shop\Services\ShopPackBundleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class ShopPackController extends BaseController
{
    #[OA\Post(path: '/shopPack/query', summary: '查询包装列表接口', security: [['bearerAuth' => []]], tags: ['包装模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ShopPackQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShopPackQueryResponse::class))]
    public function query(ShopPackQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[ShopPackQueryRequest::getPackId])) {
                $condition[] = [ShopPackEntity::getPackId, '=', $requestData[ShopPackQueryRequest::getPackId]];
            }

            $shopPackBundleService = new ShopPackBundleService;
            $result = $shopPackBundleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new ShopPackResponse($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = new ShopPackQueryResponse($result);
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

    #[OA\Post(path: '/shopPack/store', summary: '新增包装接口', security: [['bearerAuth' => []]], tags: ['包装模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ShopPackCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShopPackResponse::class))]
    public function store(ShopPackCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = new ShopPackEntity($requestData);

            $shopPackBundleService = new ShopPackBundleService;
            if ($shopPackBundleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/shopPack/show', summary: '获取包装详情接口', security: [['bearerAuth' => []]], tags: ['包装模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShopPackResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $shopPackBundleService = new ShopPackBundleService;
            $shopPack = $shopPackBundleService->getOneById($id);
            if (empty($shopPack)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = new ShopPackResponse($shopPack);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/shopPack/update', summary: '更新包装接口', security: [['bearerAuth' => []]], tags: ['包装模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ShopPackUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShopPackResponse::class))]
    public function update(ShopPackUpdateRequest $updateRequest): JsonResponse
    {
        $id = intval($updateRequest->query('id', 0));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $shopPackBundleService = new ShopPackBundleService;
            $shopPack = $shopPackBundleService->getOneById($id);
            if (empty($shopPack)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = new ShopPackEntity($requestData);

            $shopPackBundleService->updateById($input->toEntity(), $id);

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

    #[OA\Delete(path: '/shopPack/destroy', summary: '删除包装接口', security: [['bearerAuth' => []]], tags: ['包装模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ShopPackDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShopPackDestroyResponse::class))]
    public function destroy(ShopPackDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            $shopPackBundleService = new ShopPackBundleService;
            if ($shopPackBundleService->removeByIds($requestData['ids'])) {
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
