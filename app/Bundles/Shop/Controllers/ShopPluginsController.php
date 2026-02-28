<?php

declare(strict_types=1);

namespace App\Bundles\Shop\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Bundles\Shop\Entities\ShopPluginsEntity;
use App\Bundles\Shop\Requests\ShopPlugins\ShopPluginsCreateRequest;
use App\Bundles\Shop\Requests\ShopPlugins\ShopPluginsDestroyRequest;
use App\Bundles\Shop\Requests\ShopPlugins\ShopPluginsQueryRequest;
use App\Bundles\Shop\Requests\ShopPlugins\ShopPluginsUpdateRequest;
use App\Bundles\Shop\Responses\ShopPlugins\ShopPluginsDestroyResponse;
use App\Bundles\Shop\Responses\ShopPlugins\ShopPluginsQueryResponse;
use App\Bundles\Shop\Responses\ShopPlugins\ShopPluginsResponse;
use App\Bundles\Shop\Services\ShopPluginsBundleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class ShopPluginsController extends BaseController
{
    #[OA\Post(path: '/shopPlugins/query', summary: '查询插件列表接口', security: [['bearerAuth' => []]], tags: ['插件模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ShopPluginsQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShopPluginsQueryResponse::class))]
    public function query(ShopPluginsQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[ShopPluginsQueryRequest::getId])) {
                $condition[] = [ShopPluginsEntity::getId, '=', $requestData[ShopPluginsQueryRequest::getId]];
            }
            if (isset($requestData[ShopPluginsQueryRequest::getCode])) {
                $condition[] = [ShopPluginsEntity::getCode, '=', $requestData[ShopPluginsQueryRequest::getCode]];
            }

            $shopPluginsBundleService = new ShopPluginsBundleService;
            $result = $shopPluginsBundleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new ShopPluginsResponse($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = new ShopPluginsQueryResponse($result);
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

    #[OA\Post(path: '/shopPlugins/store', summary: '新增插件接口', security: [['bearerAuth' => []]], tags: ['插件模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ShopPluginsCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShopPluginsResponse::class))]
    public function store(ShopPluginsCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = new ShopPluginsEntity($requestData);

            $shopPluginsBundleService = new ShopPluginsBundleService;
            if ($shopPluginsBundleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/shopPlugins/show', summary: '获取插件详情接口', security: [['bearerAuth' => []]], tags: ['插件模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShopPluginsResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $shopPluginsBundleService = new ShopPluginsBundleService;
            $shopPlugins = $shopPluginsBundleService->getOneById($id);
            if (empty($shopPlugins)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = new ShopPluginsResponse($shopPlugins);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/shopPlugins/update', summary: '更新插件接口', security: [['bearerAuth' => []]], tags: ['插件模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ShopPluginsUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShopPluginsResponse::class))]
    public function update(ShopPluginsUpdateRequest $updateRequest): JsonResponse
    {
        $id = intval($updateRequest->query('id', 0));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $shopPluginsBundleService = new ShopPluginsBundleService;
            $shopPlugins = $shopPluginsBundleService->getOneById($id);
            if (empty($shopPlugins)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = new ShopPluginsEntity($requestData);

            $shopPluginsBundleService->updateById($input->toEntity(), $id);

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

    #[OA\Delete(path: '/shopPlugins/destroy', summary: '删除插件接口', security: [['bearerAuth' => []]], tags: ['插件模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ShopPluginsDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShopPluginsDestroyResponse::class))]
    public function destroy(ShopPluginsDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            $shopPluginsBundleService = new ShopPluginsBundleService;
            if ($shopPluginsBundleService->removeByIds($requestData['ids'])) {
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
