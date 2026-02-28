<?php

declare(strict_types=1);

namespace App\Bundles\Email\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Bundles\Email\Entities\EmailTemplateEntity;
use App\Bundles\Email\Requests\EmailTemplate\EmailTemplateCreateRequest;
use App\Bundles\Email\Requests\EmailTemplate\EmailTemplateDestroyRequest;
use App\Bundles\Email\Requests\EmailTemplate\EmailTemplateQueryRequest;
use App\Bundles\Email\Requests\EmailTemplate\EmailTemplateUpdateRequest;
use App\Bundles\Email\Responses\EmailTemplate\EmailTemplateDestroyResponse;
use App\Bundles\Email\Responses\EmailTemplate\EmailTemplateQueryResponse;
use App\Bundles\Email\Responses\EmailTemplate\EmailTemplateResponse;
use App\Bundles\Email\Services\EmailTemplateBundleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class EmailTemplateController extends BaseController
{
    #[OA\Post(path: '/emailTemplate/query', summary: '查询邮件模板列表接口', security: [['bearerAuth' => []]], tags: ['邮件模板模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: EmailTemplateQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: EmailTemplateQueryResponse::class))]
    public function query(EmailTemplateQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[EmailTemplateQueryRequest::getTemplateCode])) {
                $condition[] = [EmailTemplateEntity::getTemplateCode, '=', $requestData[EmailTemplateQueryRequest::getTemplateCode]];
            }
            if (isset($requestData[EmailTemplateQueryRequest::getType])) {
                $condition[] = [EmailTemplateEntity::getType, '=', $requestData[EmailTemplateQueryRequest::getType]];
            }
            if (isset($requestData[EmailTemplateQueryRequest::getTemplateId])) {
                $condition[] = [EmailTemplateEntity::getTemplateId, '=', $requestData[EmailTemplateQueryRequest::getTemplateId]];
            }

            $emailTemplateBundleService = new EmailTemplateBundleService;
            $result = $emailTemplateBundleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new EmailTemplateResponse($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = new EmailTemplateQueryResponse($result);
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

    #[OA\Post(path: '/emailTemplate/store', summary: '新增邮件模板接口', security: [['bearerAuth' => []]], tags: ['邮件模板模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: EmailTemplateCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: EmailTemplateResponse::class))]
    public function store(EmailTemplateCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = new EmailTemplateEntity($requestData);

            $emailTemplateBundleService = new EmailTemplateBundleService;
            if ($emailTemplateBundleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/emailTemplate/show', summary: '获取邮件模板详情接口', security: [['bearerAuth' => []]], tags: ['邮件模板模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: EmailTemplateResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $emailTemplateBundleService = new EmailTemplateBundleService;
            $emailTemplate = $emailTemplateBundleService->getOneById($id);
            if (empty($emailTemplate)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = new EmailTemplateResponse($emailTemplate);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/emailTemplate/update', summary: '更新邮件模板接口', security: [['bearerAuth' => []]], tags: ['邮件模板模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: EmailTemplateUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: EmailTemplateResponse::class))]
    public function update(EmailTemplateUpdateRequest $updateRequest): JsonResponse
    {
        $id = intval($updateRequest->query('id', 0));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $emailTemplateBundleService = new EmailTemplateBundleService;
            $emailTemplate = $emailTemplateBundleService->getOneById($id);
            if (empty($emailTemplate)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = new EmailTemplateEntity($requestData);

            $emailTemplateBundleService->updateById($input->toEntity(), $id);

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

    #[OA\Delete(path: '/emailTemplate/destroy', summary: '删除邮件模板接口', security: [['bearerAuth' => []]], tags: ['邮件模板模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: EmailTemplateDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: EmailTemplateDestroyResponse::class))]
    public function destroy(EmailTemplateDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            $emailTemplateBundleService = new EmailTemplateBundleService;
            if ($emailTemplateBundleService->removeByIds($requestData['ids'])) {
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
