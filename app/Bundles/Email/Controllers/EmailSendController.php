<?php

declare(strict_types=1);

namespace App\Bundles\Email\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Bundles\Email\Entities\EmailSendEntity;
use App\Bundles\Email\Requests\EmailSend\EmailSendCreateRequest;
use App\Bundles\Email\Requests\EmailSend\EmailSendDestroyRequest;
use App\Bundles\Email\Requests\EmailSend\EmailSendQueryRequest;
use App\Bundles\Email\Requests\EmailSend\EmailSendUpdateRequest;
use App\Bundles\Email\Responses\EmailSend\EmailSendDestroyResponse;
use App\Bundles\Email\Responses\EmailSend\EmailSendQueryResponse;
use App\Bundles\Email\Responses\EmailSend\EmailSendResponse;
use App\Bundles\Email\Services\EmailSendBundleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class EmailSendController extends BaseController
{
    #[OA\Post(path: '/emailSend/query', summary: '查询邮件发送记录列表接口', security: [['bearerAuth' => []]], tags: ['邮件发送记录模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: EmailSendQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: EmailSendQueryResponse::class))]
    public function query(EmailSendQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[EmailSendQueryRequest::getId])) {
                $condition[] = [EmailSendEntity::getId, '=', $requestData[EmailSendQueryRequest::getId]];
            }

            $emailSendBundleService = new EmailSendBundleService;
            $result = $emailSendBundleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new EmailSendResponse($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = new EmailSendQueryResponse($result);
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

    #[OA\Post(path: '/emailSend/store', summary: '新增邮件发送记录接口', security: [['bearerAuth' => []]], tags: ['邮件发送记录模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: EmailSendCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: EmailSendResponse::class))]
    public function store(EmailSendCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = new EmailSendEntity($requestData);

            $emailSendBundleService = new EmailSendBundleService;
            if ($emailSendBundleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/emailSend/show', summary: '获取邮件发送记录详情接口', security: [['bearerAuth' => []]], tags: ['邮件发送记录模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: EmailSendResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $emailSendBundleService = new EmailSendBundleService;
            $emailSend = $emailSendBundleService->getOneById($id);
            if (empty($emailSend)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = new EmailSendResponse($emailSend);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/emailSend/update', summary: '更新邮件发送记录接口', security: [['bearerAuth' => []]], tags: ['邮件发送记录模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: EmailSendUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: EmailSendResponse::class))]
    public function update(EmailSendUpdateRequest $updateRequest): JsonResponse
    {
        $id = intval($updateRequest->query('id', 0));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $emailSendBundleService = new EmailSendBundleService;
            $emailSend = $emailSendBundleService->getOneById($id);
            if (empty($emailSend)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = new EmailSendEntity($requestData);

            $emailSendBundleService->updateById($input->toEntity(), $id);

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

    #[OA\Delete(path: '/emailSend/destroy', summary: '删除邮件发送记录接口', security: [['bearerAuth' => []]], tags: ['邮件发送记录模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: EmailSendDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: EmailSendDestroyResponse::class))]
    public function destroy(EmailSendDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            $emailSendBundleService = new EmailSendBundleService;
            if ($emailSendBundleService->removeByIds($requestData['ids'])) {
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
