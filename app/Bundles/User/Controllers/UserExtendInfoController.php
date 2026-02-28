<?php

declare(strict_types=1);

namespace App\Bundles\User\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Bundles\User\Entities\UserExtendInfoEntity;
use App\Bundles\User\Requests\UserExtendInfo\UserExtendInfoCreateRequest;
use App\Bundles\User\Requests\UserExtendInfo\UserExtendInfoDestroyRequest;
use App\Bundles\User\Requests\UserExtendInfo\UserExtendInfoQueryRequest;
use App\Bundles\User\Requests\UserExtendInfo\UserExtendInfoUpdateRequest;
use App\Bundles\User\Responses\UserExtendInfo\UserExtendInfoDestroyResponse;
use App\Bundles\User\Responses\UserExtendInfo\UserExtendInfoQueryResponse;
use App\Bundles\User\Responses\UserExtendInfo\UserExtendInfoResponse;
use App\Bundles\User\Services\UserExtendInfoBundleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class UserExtendInfoController extends BaseController
{
    #[OA\Post(path: '/userExtendInfo/query', summary: '查询用户扩展信息列表接口', security: [['bearerAuth' => []]], tags: ['用户扩展信息模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserExtendInfoQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserExtendInfoQueryResponse::class))]
    public function query(UserExtendInfoQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[UserExtendInfoQueryRequest::getId])) {
                $condition[] = [UserExtendInfoEntity::getId, '=', $requestData[UserExtendInfoQueryRequest::getId]];
            }

            $userExtendInfoBundleService = new UserExtendInfoBundleService;
            $result = $userExtendInfoBundleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new UserExtendInfoResponse($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = new UserExtendInfoQueryResponse($result);
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

    #[OA\Post(path: '/userExtendInfo/store', summary: '新增用户扩展信息接口', security: [['bearerAuth' => []]], tags: ['用户扩展信息模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserExtendInfoCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserExtendInfoResponse::class))]
    public function store(UserExtendInfoCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = new UserExtendInfoEntity($requestData);

            $userExtendInfoBundleService = new UserExtendInfoBundleService;
            if ($userExtendInfoBundleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/userExtendInfo/show', summary: '获取用户扩展信息详情接口', security: [['bearerAuth' => []]], tags: ['用户扩展信息模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserExtendInfoResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $userExtendInfoBundleService = new UserExtendInfoBundleService;
            $userExtendInfo = $userExtendInfoBundleService->getOneById($id);
            if (empty($userExtendInfo)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = new UserExtendInfoResponse($userExtendInfo);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/userExtendInfo/update', summary: '更新用户扩展信息接口', security: [['bearerAuth' => []]], tags: ['用户扩展信息模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserExtendInfoUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserExtendInfoResponse::class))]
    public function update(UserExtendInfoUpdateRequest $updateRequest): JsonResponse
    {
        $id = intval($updateRequest->query('id', 0));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $userExtendInfoBundleService = new UserExtendInfoBundleService;
            $userExtendInfo = $userExtendInfoBundleService->getOneById($id);
            if (empty($userExtendInfo)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = new UserExtendInfoEntity($requestData);

            $userExtendInfoBundleService->updateById($input->toEntity(), $id);

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

    #[OA\Delete(path: '/userExtendInfo/destroy', summary: '删除用户扩展信息接口', security: [['bearerAuth' => []]], tags: ['用户扩展信息模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserExtendInfoDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserExtendInfoDestroyResponse::class))]
    public function destroy(UserExtendInfoDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            $userExtendInfoBundleService = new UserExtendInfoBundleService;
            if ($userExtendInfoBundleService->removeByIds($requestData['ids'])) {
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
