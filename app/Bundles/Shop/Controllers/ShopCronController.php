<?php

declare(strict_types=1);

namespace App\Bundles\Shop\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Bundles\Shop\Entities\ShopCronEntity;
use App\Bundles\Shop\Requests\ShopCron\ShopCronCreateRequest;
use App\Bundles\Shop\Requests\ShopCron\ShopCronDestroyRequest;
use App\Bundles\Shop\Requests\ShopCron\ShopCronQueryRequest;
use App\Bundles\Shop\Requests\ShopCron\ShopCronUpdateRequest;
use App\Bundles\Shop\Responses\ShopCron\ShopCronDestroyResponse;
use App\Bundles\Shop\Responses\ShopCron\ShopCronQueryResponse;
use App\Bundles\Shop\Responses\ShopCron\ShopCronResponse;
use App\Bundles\Shop\Services\ShopCronBundleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class ShopCronController extends BaseController
{
    #[OA\Post(path: '/shopCron/query', summary: '查询计划任务列表接口', security: [['bearerAuth' => []]], tags: ['计划任务模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ShopCronQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShopCronQueryResponse::class))]
    public function query(ShopCronQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[ShopCronQueryRequest::getCronId])) {
                $condition[] = [ShopCronEntity::getCronId, '=', $requestData[ShopCronQueryRequest::getCronId]];
            }
            if (isset($requestData[ShopCronQueryRequest::getCronCode])) {
                $condition[] = [ShopCronEntity::getCronCode, '=', $requestData[ShopCronQueryRequest::getCronCode]];
            }
            if (isset($requestData[ShopCronQueryRequest::getEnable])) {
                $condition[] = [ShopCronEntity::getEnable, '=', $requestData[ShopCronQueryRequest::getEnable]];
            }
            if (isset($requestData[ShopCronQueryRequest::getNextime])) {
                $condition[] = [ShopCronEntity::getNextime, '=', $requestData[ShopCronQueryRequest::getNextime]];
            }

            $shopCronBundleService = new ShopCronBundleService;
            $result = $shopCronBundleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new ShopCronResponse($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = new ShopCronQueryResponse($result);
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

    #[OA\Post(path: '/shopCron/store', summary: '新增计划任务接口', security: [['bearerAuth' => []]], tags: ['计划任务模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ShopCronCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShopCronResponse::class))]
    public function store(ShopCronCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = new ShopCronEntity($requestData);

            $shopCronBundleService = new ShopCronBundleService;
            if ($shopCronBundleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/shopCron/show', summary: '获取计划任务详情接口', security: [['bearerAuth' => []]], tags: ['计划任务模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShopCronResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $shopCronBundleService = new ShopCronBundleService;
            $shopCron = $shopCronBundleService->getOneById($id);
            if (empty($shopCron)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = new ShopCronResponse($shopCron);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/shopCron/update', summary: '更新计划任务接口', security: [['bearerAuth' => []]], tags: ['计划任务模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ShopCronUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShopCronResponse::class))]
    public function update(ShopCronUpdateRequest $updateRequest): JsonResponse
    {
        $id = intval($updateRequest->query('id', 0));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $shopCronBundleService = new ShopCronBundleService;
            $shopCron = $shopCronBundleService->getOneById($id);
            if (empty($shopCron)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = new ShopCronEntity($requestData);

            $shopCronBundleService->updateById($input->toEntity(), $id);

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

    #[OA\Delete(path: '/shopCron/destroy', summary: '删除计划任务接口', security: [['bearerAuth' => []]], tags: ['计划任务模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ShopCronDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShopCronDestroyResponse::class))]
    public function destroy(ShopCronDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            $shopCronBundleService = new ShopCronBundleService;
            if ($shopCronBundleService->removeByIds($requestData['ids'])) {
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
