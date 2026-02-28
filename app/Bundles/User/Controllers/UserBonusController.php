<?php

declare(strict_types=1);

namespace App\Bundles\User\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Bundles\User\Entities\UserBonusEntity;
use App\Bundles\User\Requests\UserBonus\UserBonusCreateRequest;
use App\Bundles\User\Requests\UserBonus\UserBonusDestroyRequest;
use App\Bundles\User\Requests\UserBonus\UserBonusQueryRequest;
use App\Bundles\User\Requests\UserBonus\UserBonusUpdateRequest;
use App\Bundles\User\Responses\UserBonus\UserBonusDestroyResponse;
use App\Bundles\User\Responses\UserBonus\UserBonusQueryResponse;
use App\Bundles\User\Responses\UserBonus\UserBonusResponse;
use App\Bundles\User\Services\UserBonusBundleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class UserBonusController extends BaseController
{
    #[OA\Post(path: '/userBonus/query', summary: '查询用户红包列表接口', security: [['bearerAuth' => []]], tags: ['用户红包模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserBonusQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserBonusQueryResponse::class))]
    public function query(UserBonusQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[UserBonusQueryRequest::getBonusId])) {
                $condition[] = [UserBonusEntity::getBonusId, '=', $requestData[UserBonusQueryRequest::getBonusId]];
            }
            if (isset($requestData[UserBonusQueryRequest::getUserId])) {
                $condition[] = [UserBonusEntity::getUserId, '=', $requestData[UserBonusQueryRequest::getUserId]];
            }

            $userBonusBundleService = new UserBonusBundleService;
            $result = $userBonusBundleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new UserBonusResponse($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = new UserBonusQueryResponse($result);
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

    #[OA\Post(path: '/userBonus/store', summary: '新增用户红包接口', security: [['bearerAuth' => []]], tags: ['用户红包模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserBonusCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserBonusResponse::class))]
    public function store(UserBonusCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = new UserBonusEntity($requestData);

            $userBonusBundleService = new UserBonusBundleService;
            if ($userBonusBundleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/userBonus/show', summary: '获取用户红包详情接口', security: [['bearerAuth' => []]], tags: ['用户红包模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserBonusResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $userBonusBundleService = new UserBonusBundleService;
            $userBonus = $userBonusBundleService->getOneById($id);
            if (empty($userBonus)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = new UserBonusResponse($userBonus);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/userBonus/update', summary: '更新用户红包接口', security: [['bearerAuth' => []]], tags: ['用户红包模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserBonusUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserBonusResponse::class))]
    public function update(UserBonusUpdateRequest $updateRequest): JsonResponse
    {
        $id = intval($updateRequest->query('id', 0));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $userBonusBundleService = new UserBonusBundleService;
            $userBonus = $userBonusBundleService->getOneById($id);
            if (empty($userBonus)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = new UserBonusEntity($requestData);

            $userBonusBundleService->updateById($input->toEntity(), $id);

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

    #[OA\Delete(path: '/userBonus/destroy', summary: '删除用户红包接口', security: [['bearerAuth' => []]], tags: ['用户红包模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserBonusDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserBonusDestroyResponse::class))]
    public function destroy(UserBonusDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            $userBonusBundleService = new UserBonusBundleService;
            if ($userBonusBundleService->removeByIds($requestData['ids'])) {
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
