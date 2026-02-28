<?php

declare(strict_types=1);

namespace App\Bundles\Shop\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Bundles\Shop\Entities\ShopFriendLinkEntity;
use App\Bundles\Shop\Requests\ShopFriendLink\ShopFriendLinkCreateRequest;
use App\Bundles\Shop\Requests\ShopFriendLink\ShopFriendLinkDestroyRequest;
use App\Bundles\Shop\Requests\ShopFriendLink\ShopFriendLinkQueryRequest;
use App\Bundles\Shop\Requests\ShopFriendLink\ShopFriendLinkUpdateRequest;
use App\Bundles\Shop\Responses\ShopFriendLink\ShopFriendLinkDestroyResponse;
use App\Bundles\Shop\Responses\ShopFriendLink\ShopFriendLinkQueryResponse;
use App\Bundles\Shop\Responses\ShopFriendLink\ShopFriendLinkResponse;
use App\Bundles\Shop\Services\ShopFriendLinkBundleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class ShopFriendLinkController extends BaseController
{
    #[OA\Post(path: '/shopFriendLink/query', summary: '查询友情链接列表接口', security: [['bearerAuth' => []]], tags: ['友情链接模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ShopFriendLinkQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShopFriendLinkQueryResponse::class))]
    public function query(ShopFriendLinkQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[ShopFriendLinkQueryRequest::getLinkId])) {
                $condition[] = [ShopFriendLinkEntity::getLinkId, '=', $requestData[ShopFriendLinkQueryRequest::getLinkId]];
            }
            if (isset($requestData[ShopFriendLinkQueryRequest::getShowOrder])) {
                $condition[] = [ShopFriendLinkEntity::getShowOrder, '=', $requestData[ShopFriendLinkQueryRequest::getShowOrder]];
            }

            $shopFriendLinkBundleService = new ShopFriendLinkBundleService;
            $result = $shopFriendLinkBundleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new ShopFriendLinkResponse($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = new ShopFriendLinkQueryResponse($result);
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

    #[OA\Post(path: '/shopFriendLink/store', summary: '新增友情链接接口', security: [['bearerAuth' => []]], tags: ['友情链接模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ShopFriendLinkCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShopFriendLinkResponse::class))]
    public function store(ShopFriendLinkCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = new ShopFriendLinkEntity($requestData);

            $shopFriendLinkBundleService = new ShopFriendLinkBundleService;
            if ($shopFriendLinkBundleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/shopFriendLink/show', summary: '获取友情链接详情接口', security: [['bearerAuth' => []]], tags: ['友情链接模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShopFriendLinkResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $shopFriendLinkBundleService = new ShopFriendLinkBundleService;
            $shopFriendLink = $shopFriendLinkBundleService->getOneById($id);
            if (empty($shopFriendLink)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = new ShopFriendLinkResponse($shopFriendLink);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/shopFriendLink/update', summary: '更新友情链接接口', security: [['bearerAuth' => []]], tags: ['友情链接模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ShopFriendLinkUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShopFriendLinkResponse::class))]
    public function update(ShopFriendLinkUpdateRequest $updateRequest): JsonResponse
    {
        $id = intval($updateRequest->query('id', 0));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $shopFriendLinkBundleService = new ShopFriendLinkBundleService;
            $shopFriendLink = $shopFriendLinkBundleService->getOneById($id);
            if (empty($shopFriendLink)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = new ShopFriendLinkEntity($requestData);

            $shopFriendLinkBundleService->updateById($input->toEntity(), $id);

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

    #[OA\Delete(path: '/shopFriendLink/destroy', summary: '删除友情链接接口', security: [['bearerAuth' => []]], tags: ['友情链接模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ShopFriendLinkDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShopFriendLinkDestroyResponse::class))]
    public function destroy(ShopFriendLinkDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            $shopFriendLinkBundleService = new ShopFriendLinkBundleService;
            if ($shopFriendLinkBundleService->removeByIds($requestData['ids'])) {
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
