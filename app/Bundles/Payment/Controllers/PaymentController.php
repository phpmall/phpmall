<?php

declare(strict_types=1);

namespace App\Bundles\Payment\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Bundles\Payment\Entities\PaymentEntity;
use App\Bundles\Payment\Requests\Payment\PaymentCreateRequest;
use App\Bundles\Payment\Requests\Payment\PaymentDestroyRequest;
use App\Bundles\Payment\Requests\Payment\PaymentQueryRequest;
use App\Bundles\Payment\Requests\Payment\PaymentUpdateRequest;
use App\Bundles\Payment\Responses\Payment\PaymentDestroyResponse;
use App\Bundles\Payment\Responses\Payment\PaymentQueryResponse;
use App\Bundles\Payment\Responses\Payment\PaymentResponse;
use App\Bundles\Payment\Services\PaymentBundleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class PaymentController extends BaseController
{
    #[OA\Post(path: '/payment/query', summary: '查询支付方式列表接口', security: [['bearerAuth' => []]], tags: ['支付方式模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: PaymentQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: PaymentQueryResponse::class))]
    public function query(PaymentQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[PaymentQueryRequest::getPayCode])) {
                $condition[] = [PaymentEntity::getPayCode, '=', $requestData[PaymentQueryRequest::getPayCode]];
            }
            if (isset($requestData[PaymentQueryRequest::getPayId])) {
                $condition[] = [PaymentEntity::getPayId, '=', $requestData[PaymentQueryRequest::getPayId]];
            }

            $paymentBundleService = new PaymentBundleService;
            $result = $paymentBundleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new PaymentResponse($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = new PaymentQueryResponse($result);
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

    #[OA\Post(path: '/payment/store', summary: '新增支付方式接口', security: [['bearerAuth' => []]], tags: ['支付方式模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: PaymentCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: PaymentResponse::class))]
    public function store(PaymentCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = new PaymentEntity($requestData);

            $paymentBundleService = new PaymentBundleService;
            if ($paymentBundleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/payment/show', summary: '获取支付方式详情接口', security: [['bearerAuth' => []]], tags: ['支付方式模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: PaymentResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $paymentBundleService = new PaymentBundleService;
            $payment = $paymentBundleService->getOneById($id);
            if (empty($payment)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = new PaymentResponse($payment);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/payment/update', summary: '更新支付方式接口', security: [['bearerAuth' => []]], tags: ['支付方式模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: PaymentUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: PaymentResponse::class))]
    public function update(PaymentUpdateRequest $updateRequest): JsonResponse
    {
        $id = intval($updateRequest->query('id', 0));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $paymentBundleService = new PaymentBundleService;
            $payment = $paymentBundleService->getOneById($id);
            if (empty($payment)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = new PaymentEntity($requestData);

            $paymentBundleService->updateById($input->toEntity(), $id);

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

    #[OA\Delete(path: '/payment/destroy', summary: '删除支付方式接口', security: [['bearerAuth' => []]], tags: ['支付方式模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: PaymentDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: PaymentDestroyResponse::class))]
    public function destroy(PaymentDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            $paymentBundleService = new PaymentBundleService;
            if ($paymentBundleService->removeByIds($requestData['ids'])) {
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
