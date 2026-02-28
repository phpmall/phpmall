<?php

declare(strict_types=1);

namespace App\Bundles\Feedback\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Bundles\Feedback\Entities\FeedbackEntity;
use App\Bundles\Feedback\Requests\Feedback\FeedbackCreateRequest;
use App\Bundles\Feedback\Requests\Feedback\FeedbackDestroyRequest;
use App\Bundles\Feedback\Requests\Feedback\FeedbackQueryRequest;
use App\Bundles\Feedback\Requests\Feedback\FeedbackUpdateRequest;
use App\Bundles\Feedback\Responses\Feedback\FeedbackDestroyResponse;
use App\Bundles\Feedback\Responses\Feedback\FeedbackQueryResponse;
use App\Bundles\Feedback\Responses\Feedback\FeedbackResponse;
use App\Bundles\Feedback\Services\FeedbackBundleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class FeedbackController extends BaseController
{
    #[OA\Post(path: '/feedback/query', summary: '查询用户反馈列表接口', security: [['bearerAuth' => []]], tags: ['用户反馈模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: FeedbackQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: FeedbackQueryResponse::class))]
    public function query(FeedbackQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[FeedbackQueryRequest::getUserId])) {
                $condition[] = [FeedbackEntity::getUserId, '=', $requestData[FeedbackQueryRequest::getUserId]];
            }
            if (isset($requestData[FeedbackQueryRequest::getMsgId])) {
                $condition[] = [FeedbackEntity::getMsgId, '=', $requestData[FeedbackQueryRequest::getMsgId]];
            }

            $feedbackBundleService = new FeedbackBundleService;
            $result = $feedbackBundleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new FeedbackResponse($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = new FeedbackQueryResponse($result);
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

    #[OA\Post(path: '/feedback/store', summary: '新增用户反馈接口', security: [['bearerAuth' => []]], tags: ['用户反馈模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: FeedbackCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: FeedbackResponse::class))]
    public function store(FeedbackCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = new FeedbackEntity($requestData);

            $feedbackBundleService = new FeedbackBundleService;
            if ($feedbackBundleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/feedback/show', summary: '获取用户反馈详情接口', security: [['bearerAuth' => []]], tags: ['用户反馈模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: FeedbackResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $feedbackBundleService = new FeedbackBundleService;
            $feedback = $feedbackBundleService->getOneById($id);
            if (empty($feedback)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = new FeedbackResponse($feedback);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/feedback/update', summary: '更新用户反馈接口', security: [['bearerAuth' => []]], tags: ['用户反馈模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: FeedbackUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: FeedbackResponse::class))]
    public function update(FeedbackUpdateRequest $updateRequest): JsonResponse
    {
        $id = intval($updateRequest->query('id', 0));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $feedbackBundleService = new FeedbackBundleService;
            $feedback = $feedbackBundleService->getOneById($id);
            if (empty($feedback)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = new FeedbackEntity($requestData);

            $feedbackBundleService->updateById($input->toEntity(), $id);

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

    #[OA\Delete(path: '/feedback/destroy', summary: '删除用户反馈接口', security: [['bearerAuth' => []]], tags: ['用户反馈模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: FeedbackDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: FeedbackDestroyResponse::class))]
    public function destroy(FeedbackDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            $feedbackBundleService = new FeedbackBundleService;
            if ($feedbackBundleService->removeByIds($requestData['ids'])) {
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
