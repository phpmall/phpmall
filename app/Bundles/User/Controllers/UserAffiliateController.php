<?php

declare(strict_types=1);

namespace App\Bundles\User\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Bundles\User\Entities\UserAffiliateEntity;
use App\Bundles\User\Requests\UserAffiliate\UserAffiliateCreateRequest;
use App\Bundles\User\Requests\UserAffiliate\UserAffiliateDestroyRequest;
use App\Bundles\User\Requests\UserAffiliate\UserAffiliateQueryRequest;
use App\Bundles\User\Requests\UserAffiliate\UserAffiliateUpdateRequest;
use App\Bundles\User\Responses\UserAffiliate\UserAffiliateDestroyResponse;
use App\Bundles\User\Responses\UserAffiliate\UserAffiliateQueryResponse;
use App\Bundles\User\Responses\UserAffiliate\UserAffiliateResponse;
use App\Bundles\User\Services\UserAffiliateBundleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class UserAffiliateController extends BaseController
{
    #[OA\Post(path: '/userAffiliate/query', summary: '查询用户推荐关系列表接口', security: [['bearerAuth' => []]], tags: ['用户推荐关系模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserAffiliateQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserAffiliateQueryResponse::class))]
    public function query(UserAffiliateQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[UserAffiliateQueryRequest::getLogId])) {
                $condition[] = [UserAffiliateEntity::getLogId, '=', $requestData[UserAffiliateQueryRequest::getLogId]];
            }

            $userAffiliateBundleService = new UserAffiliateBundleService;
            $result = $userAffiliateBundleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new UserAffiliateResponse($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = new UserAffiliateQueryResponse($result);
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

    #[OA\Post(path: '/userAffiliate/store', summary: '新增用户推荐关系接口', security: [['bearerAuth' => []]], tags: ['用户推荐关系模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserAffiliateCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserAffiliateResponse::class))]
    public function store(UserAffiliateCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = new UserAffiliateEntity($requestData);

            $userAffiliateBundleService = new UserAffiliateBundleService;
            if ($userAffiliateBundleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/userAffiliate/show', summary: '获取用户推荐关系详情接口', security: [['bearerAuth' => []]], tags: ['用户推荐关系模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserAffiliateResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $userAffiliateBundleService = new UserAffiliateBundleService;
            $userAffiliate = $userAffiliateBundleService->getOneById($id);
            if (empty($userAffiliate)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = new UserAffiliateResponse($userAffiliate);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/userAffiliate/update', summary: '更新用户推荐关系接口', security: [['bearerAuth' => []]], tags: ['用户推荐关系模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserAffiliateUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserAffiliateResponse::class))]
    public function update(UserAffiliateUpdateRequest $updateRequest): JsonResponse
    {
        $id = intval($updateRequest->query('id', 0));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $userAffiliateBundleService = new UserAffiliateBundleService;
            $userAffiliate = $userAffiliateBundleService->getOneById($id);
            if (empty($userAffiliate)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = new UserAffiliateEntity($requestData);

            $userAffiliateBundleService->updateById($input->toEntity(), $id);

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

    #[OA\Delete(path: '/userAffiliate/destroy', summary: '删除用户推荐关系接口', security: [['bearerAuth' => []]], tags: ['用户推荐关系模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserAffiliateDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserAffiliateDestroyResponse::class))]
    public function destroy(UserAffiliateDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            $userAffiliateBundleService = new UserAffiliateBundleService;
            if ($userAffiliateBundleService->removeByIds($requestData['ids'])) {
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
