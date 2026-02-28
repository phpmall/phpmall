<?php

declare(strict_types=1);

namespace App\Bundles\Email\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Bundles\Email\Entities\EmailSubscriberEntity;
use App\Bundles\Email\Requests\EmailSubscriber\EmailSubscriberCreateRequest;
use App\Bundles\Email\Requests\EmailSubscriber\EmailSubscriberDestroyRequest;
use App\Bundles\Email\Requests\EmailSubscriber\EmailSubscriberQueryRequest;
use App\Bundles\Email\Requests\EmailSubscriber\EmailSubscriberUpdateRequest;
use App\Bundles\Email\Responses\EmailSubscriber\EmailSubscriberDestroyResponse;
use App\Bundles\Email\Responses\EmailSubscriber\EmailSubscriberQueryResponse;
use App\Bundles\Email\Responses\EmailSubscriber\EmailSubscriberResponse;
use App\Bundles\Email\Services\EmailSubscriberBundleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class EmailSubscriberController extends BaseController
{
    #[OA\Post(path: '/emailSubscriber/query', summary: '查询邮件订阅列表接口', security: [['bearerAuth' => []]], tags: ['邮件订阅模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: EmailSubscriberQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: EmailSubscriberQueryResponse::class))]
    public function query(EmailSubscriberQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[EmailSubscriberQueryRequest::getId])) {
                $condition[] = [EmailSubscriberEntity::getId, '=', $requestData[EmailSubscriberQueryRequest::getId]];
            }

            $emailSubscriberBundleService = new EmailSubscriberBundleService;
            $result = $emailSubscriberBundleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new EmailSubscriberResponse($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = new EmailSubscriberQueryResponse($result);
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

    #[OA\Post(path: '/emailSubscriber/store', summary: '新增邮件订阅接口', security: [['bearerAuth' => []]], tags: ['邮件订阅模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: EmailSubscriberCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: EmailSubscriberResponse::class))]
    public function store(EmailSubscriberCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = new EmailSubscriberEntity($requestData);

            $emailSubscriberBundleService = new EmailSubscriberBundleService;
            if ($emailSubscriberBundleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/emailSubscriber/show', summary: '获取邮件订阅详情接口', security: [['bearerAuth' => []]], tags: ['邮件订阅模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: EmailSubscriberResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $emailSubscriberBundleService = new EmailSubscriberBundleService;
            $emailSubscriber = $emailSubscriberBundleService->getOneById($id);
            if (empty($emailSubscriber)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = new EmailSubscriberResponse($emailSubscriber);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/emailSubscriber/update', summary: '更新邮件订阅接口', security: [['bearerAuth' => []]], tags: ['邮件订阅模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: EmailSubscriberUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: EmailSubscriberResponse::class))]
    public function update(EmailSubscriberUpdateRequest $updateRequest): JsonResponse
    {
        $id = intval($updateRequest->query('id', 0));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $emailSubscriberBundleService = new EmailSubscriberBundleService;
            $emailSubscriber = $emailSubscriberBundleService->getOneById($id);
            if (empty($emailSubscriber)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = new EmailSubscriberEntity($requestData);

            $emailSubscriberBundleService->updateById($input->toEntity(), $id);

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

    #[OA\Delete(path: '/emailSubscriber/destroy', summary: '删除邮件订阅接口', security: [['bearerAuth' => []]], tags: ['邮件订阅模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: EmailSubscriberDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: EmailSubscriberDestroyResponse::class))]
    public function destroy(EmailSubscriberDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            $emailSubscriberBundleService = new EmailSubscriberBundleService;
            if ($emailSubscriberBundleService->removeByIds($requestData['ids'])) {
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
