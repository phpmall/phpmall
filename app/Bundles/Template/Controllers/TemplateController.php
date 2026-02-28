<?php

declare(strict_types=1);

namespace App\Bundles\Template\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Bundles\Template\Entities\TemplateEntity;
use App\Bundles\Template\Requests\Template\TemplateCreateRequest;
use App\Bundles\Template\Requests\Template\TemplateDestroyRequest;
use App\Bundles\Template\Requests\Template\TemplateQueryRequest;
use App\Bundles\Template\Requests\Template\TemplateUpdateRequest;
use App\Bundles\Template\Responses\Template\TemplateDestroyResponse;
use App\Bundles\Template\Responses\Template\TemplateQueryResponse;
use App\Bundles\Template\Responses\Template\TemplateResponse;
use App\Bundles\Template\Services\TemplateBundleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class TemplateController extends BaseController
{
    #[OA\Post(path: '/template/query', summary: '查询模板列表接口', security: [['bearerAuth' => []]], tags: ['模板模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: TemplateQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: TemplateQueryResponse::class))]
    public function query(TemplateQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[TemplateQueryRequest::getRegion])) {
                $condition[] = [TemplateEntity::getRegion, '=', $requestData[TemplateQueryRequest::getRegion]];
            }
            if (isset($requestData[TemplateQueryRequest::getId])) {
                $condition[] = [TemplateEntity::getId, '=', $requestData[TemplateQueryRequest::getId]];
            }
            if (isset($requestData[TemplateQueryRequest::getRemarks])) {
                $condition[] = [TemplateEntity::getRemarks, '=', $requestData[TemplateQueryRequest::getRemarks]];
            }
            if (isset($requestData[TemplateQueryRequest::getTheme])) {
                $condition[] = [TemplateEntity::getTheme, '=', $requestData[TemplateQueryRequest::getTheme]];
            }

            $templateBundleService = new TemplateBundleService;
            $result = $templateBundleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new TemplateResponse($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = new TemplateQueryResponse($result);
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

    #[OA\Post(path: '/template/store', summary: '新增模板接口', security: [['bearerAuth' => []]], tags: ['模板模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: TemplateCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: TemplateResponse::class))]
    public function store(TemplateCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = new TemplateEntity($requestData);

            $templateBundleService = new TemplateBundleService;
            if ($templateBundleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/template/show', summary: '获取模板详情接口', security: [['bearerAuth' => []]], tags: ['模板模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: TemplateResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $templateBundleService = new TemplateBundleService;
            $template = $templateBundleService->getOneById($id);
            if (empty($template)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = new TemplateResponse($template);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/template/update', summary: '更新模板接口', security: [['bearerAuth' => []]], tags: ['模板模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: TemplateUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: TemplateResponse::class))]
    public function update(TemplateUpdateRequest $updateRequest): JsonResponse
    {
        $id = intval($updateRequest->query('id', 0));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $templateBundleService = new TemplateBundleService;
            $template = $templateBundleService->getOneById($id);
            if (empty($template)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = new TemplateEntity($requestData);

            $templateBundleService->updateById($input->toEntity(), $id);

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

    #[OA\Delete(path: '/template/destroy', summary: '删除模板接口', security: [['bearerAuth' => []]], tags: ['模板模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: TemplateDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: TemplateDestroyResponse::class))]
    public function destroy(TemplateDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            $templateBundleService = new TemplateBundleService;
            if ($templateBundleService->removeByIds($requestData['ids'])) {
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
