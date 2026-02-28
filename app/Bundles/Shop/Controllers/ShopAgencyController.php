<?php

declare(strict_types=1);

namespace App\Bundles\Shop\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Bundles\Shop\Entities\ShopAgencyEntity;
use App\Bundles\Shop\Requests\ShopAgency\ShopAgencyCreateRequest;
use App\Bundles\Shop\Requests\ShopAgency\ShopAgencyDestroyRequest;
use App\Bundles\Shop\Requests\ShopAgency\ShopAgencyQueryRequest;
use App\Bundles\Shop\Requests\ShopAgency\ShopAgencyUpdateRequest;
use App\Bundles\Shop\Responses\ShopAgency\ShopAgencyDestroyResponse;
use App\Bundles\Shop\Responses\ShopAgency\ShopAgencyQueryResponse;
use App\Bundles\Shop\Responses\ShopAgency\ShopAgencyResponse;
use App\Bundles\Shop\Services\ShopAgencyBundleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class ShopAgencyController extends BaseController
{
    #[OA\Post(path: '/shopAgency/query', summary: '查询办事处列表接口', security: [['bearerAuth' => []]], tags: ['办事处模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ShopAgencyQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShopAgencyQueryResponse::class))]
    public function query(ShopAgencyQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[ShopAgencyQueryRequest::getAgencyId])) {
                $condition[] = [ShopAgencyEntity::getAgencyId, '=', $requestData[ShopAgencyQueryRequest::getAgencyId]];
            }
            if (isset($requestData[ShopAgencyQueryRequest::getAgencyName])) {
                $condition[] = [ShopAgencyEntity::getAgencyName, '=', $requestData[ShopAgencyQueryRequest::getAgencyName]];
            }

            $shopAgencyBundleService = new ShopAgencyBundleService;
            $result = $shopAgencyBundleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new ShopAgencyResponse($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = new ShopAgencyQueryResponse($result);
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

    #[OA\Post(path: '/shopAgency/store', summary: '新增办事处接口', security: [['bearerAuth' => []]], tags: ['办事处模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ShopAgencyCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShopAgencyResponse::class))]
    public function store(ShopAgencyCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = new ShopAgencyEntity($requestData);

            $shopAgencyBundleService = new ShopAgencyBundleService;
            if ($shopAgencyBundleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/shopAgency/show', summary: '获取办事处详情接口', security: [['bearerAuth' => []]], tags: ['办事处模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShopAgencyResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $shopAgencyBundleService = new ShopAgencyBundleService;
            $shopAgency = $shopAgencyBundleService->getOneById($id);
            if (empty($shopAgency)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = new ShopAgencyResponse($shopAgency);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/shopAgency/update', summary: '更新办事处接口', security: [['bearerAuth' => []]], tags: ['办事处模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ShopAgencyUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShopAgencyResponse::class))]
    public function update(ShopAgencyUpdateRequest $updateRequest): JsonResponse
    {
        $id = intval($updateRequest->query('id', 0));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $shopAgencyBundleService = new ShopAgencyBundleService;
            $shopAgency = $shopAgencyBundleService->getOneById($id);
            if (empty($shopAgency)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = new ShopAgencyEntity($requestData);

            $shopAgencyBundleService->updateById($input->toEntity(), $id);

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

    #[OA\Delete(path: '/shopAgency/destroy', summary: '删除办事处接口', security: [['bearerAuth' => []]], tags: ['办事处模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ShopAgencyDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShopAgencyDestroyResponse::class))]
    public function destroy(ShopAgencyDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            $shopAgencyBundleService = new ShopAgencyBundleService;
            if ($shopAgencyBundleService->removeByIds($requestData['ids'])) {
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
