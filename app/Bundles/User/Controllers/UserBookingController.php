<?php

declare(strict_types=1);

namespace App\Bundles\User\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Bundles\User\Entities\UserBookingEntity;
use App\Bundles\User\Requests\UserBooking\UserBookingCreateRequest;
use App\Bundles\User\Requests\UserBooking\UserBookingDestroyRequest;
use App\Bundles\User\Requests\UserBooking\UserBookingQueryRequest;
use App\Bundles\User\Requests\UserBooking\UserBookingUpdateRequest;
use App\Bundles\User\Responses\UserBooking\UserBookingDestroyResponse;
use App\Bundles\User\Responses\UserBooking\UserBookingQueryResponse;
use App\Bundles\User\Responses\UserBooking\UserBookingResponse;
use App\Bundles\User\Services\UserBookingBundleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class UserBookingController extends BaseController
{
    #[OA\Post(path: '/userBooking/query', summary: '查询用户缺货登记列表接口', security: [['bearerAuth' => []]], tags: ['用户缺货登记模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserBookingQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserBookingQueryResponse::class))]
    public function query(UserBookingQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[UserBookingQueryRequest::getRecId])) {
                $condition[] = [UserBookingEntity::getRecId, '=', $requestData[UserBookingQueryRequest::getRecId]];
            }
            if (isset($requestData[UserBookingQueryRequest::getUserId])) {
                $condition[] = [UserBookingEntity::getUserId, '=', $requestData[UserBookingQueryRequest::getUserId]];
            }

            $userBookingBundleService = new UserBookingBundleService;
            $result = $userBookingBundleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new UserBookingResponse($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = new UserBookingQueryResponse($result);
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

    #[OA\Post(path: '/userBooking/store', summary: '新增用户缺货登记接口', security: [['bearerAuth' => []]], tags: ['用户缺货登记模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserBookingCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserBookingResponse::class))]
    public function store(UserBookingCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = new UserBookingEntity($requestData);

            $userBookingBundleService = new UserBookingBundleService;
            if ($userBookingBundleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/userBooking/show', summary: '获取用户缺货登记详情接口', security: [['bearerAuth' => []]], tags: ['用户缺货登记模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserBookingResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $userBookingBundleService = new UserBookingBundleService;
            $userBooking = $userBookingBundleService->getOneById($id);
            if (empty($userBooking)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = new UserBookingResponse($userBooking);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/userBooking/update', summary: '更新用户缺货登记接口', security: [['bearerAuth' => []]], tags: ['用户缺货登记模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserBookingUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserBookingResponse::class))]
    public function update(UserBookingUpdateRequest $updateRequest): JsonResponse
    {
        $id = intval($updateRequest->query('id', 0));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $userBookingBundleService = new UserBookingBundleService;
            $userBooking = $userBookingBundleService->getOneById($id);
            if (empty($userBooking)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = new UserBookingEntity($requestData);

            $userBookingBundleService->updateById($input->toEntity(), $id);

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

    #[OA\Delete(path: '/userBooking/destroy', summary: '删除用户缺货登记接口', security: [['bearerAuth' => []]], tags: ['用户缺货登记模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserBookingDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserBookingDestroyResponse::class))]
    public function destroy(UserBookingDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            $userBookingBundleService = new UserBookingBundleService;
            if ($userBookingBundleService->removeByIds($requestData['ids'])) {
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
