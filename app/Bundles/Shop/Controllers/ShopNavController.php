<?php

declare(strict_types=1);

namespace App\Bundles\Shop\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Bundles\Shop\Entities\ShopNavEntity;
use App\Bundles\Shop\Requests\ShopNav\ShopNavCreateRequest;
use App\Bundles\Shop\Requests\ShopNav\ShopNavDestroyRequest;
use App\Bundles\Shop\Requests\ShopNav\ShopNavQueryRequest;
use App\Bundles\Shop\Requests\ShopNav\ShopNavUpdateRequest;
use App\Bundles\Shop\Responses\ShopNav\ShopNavDestroyResponse;
use App\Bundles\Shop\Responses\ShopNav\ShopNavQueryResponse;
use App\Bundles\Shop\Responses\ShopNav\ShopNavResponse;
use App\Bundles\Shop\Services\ShopNavBundleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class ShopNavController extends BaseController
{
    #[OA\Post(path: '/shopNav/query', summary: '查询导航菜单列表接口', security: [['bearerAuth' => []]], tags: ['导航菜单模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ShopNavQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShopNavQueryResponse::class))]
    public function query(ShopNavQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[ShopNavQueryRequest::getId])) {
                $condition[] = [ShopNavEntity::getId, '=', $requestData[ShopNavQueryRequest::getId]];
            }
            if (isset($requestData[ShopNavQueryRequest::getIfshow])) {
                $condition[] = [ShopNavEntity::getIfshow, '=', $requestData[ShopNavQueryRequest::getIfshow]];
            }
            if (isset($requestData[ShopNavQueryRequest::getType])) {
                $condition[] = [ShopNavEntity::getType, '=', $requestData[ShopNavQueryRequest::getType]];
            }

            $shopNavBundleService = new ShopNavBundleService;
            $result = $shopNavBundleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new ShopNavResponse($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = new ShopNavQueryResponse($result);
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

    #[OA\Post(path: '/shopNav/store', summary: '新增导航菜单接口', security: [['bearerAuth' => []]], tags: ['导航菜单模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ShopNavCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShopNavResponse::class))]
    public function store(ShopNavCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = new ShopNavEntity($requestData);

            $shopNavBundleService = new ShopNavBundleService;
            if ($shopNavBundleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/shopNav/show', summary: '获取导航菜单详情接口', security: [['bearerAuth' => []]], tags: ['导航菜单模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShopNavResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $shopNavBundleService = new ShopNavBundleService;
            $shopNav = $shopNavBundleService->getOneById($id);
            if (empty($shopNav)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = new ShopNavResponse($shopNav);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/shopNav/update', summary: '更新导航菜单接口', security: [['bearerAuth' => []]], tags: ['导航菜单模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ShopNavUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShopNavResponse::class))]
    public function update(ShopNavUpdateRequest $updateRequest): JsonResponse
    {
        $id = intval($updateRequest->query('id', 0));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $shopNavBundleService = new ShopNavBundleService;
            $shopNav = $shopNavBundleService->getOneById($id);
            if (empty($shopNav)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = new ShopNavEntity($requestData);

            $shopNavBundleService->updateById($input->toEntity(), $id);

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

    #[OA\Delete(path: '/shopNav/destroy', summary: '删除导航菜单接口', security: [['bearerAuth' => []]], tags: ['导航菜单模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ShopNavDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShopNavDestroyResponse::class))]
    public function destroy(ShopNavDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            $shopNavBundleService = new ShopNavBundleService;
            if ($shopNavBundleService->removeByIds($requestData['ids'])) {
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
