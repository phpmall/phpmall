<?php

declare(strict_types=1);

namespace App\Domains\Payment\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Payment\Entities\PaymentRefundEntity;
use App\Domains\Payment\Requests\PaymentRefund\PaymentRefundCreateRequest;
use App\Domains\Payment\Requests\PaymentRefund\PaymentRefundDestroyRequest;
use App\Domains\Payment\Requests\PaymentRefund\PaymentRefundQueryRequest;
use App\Domains\Payment\Requests\PaymentRefund\PaymentRefundUpdateRequest;
use App\Domains\Payment\Responses\PaymentRefund\PaymentRefundDestroyResponse;
use App\Domains\Payment\Responses\PaymentRefund\PaymentRefundQueryResponse;
use App\Domains\Payment\Responses\PaymentRefund\PaymentRefundResponse;
use App\Domains\Payment\Services\PaymentRefundService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class PaymentRefundController extends BaseController
{
    public function __construct(
        private readonly PaymentRefundService $paymentRefundService,
    ) {}

    #[OA\Post(path: '/paymentRefund/search', summary: '查询列表接口', security: [['bearerAuth' => []]], tags: ['模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: PaymentRefundQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: PaymentRefundQueryResponse::class),
        ],
    ))]
    public function search(PaymentRefundQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[PaymentRefundQueryRequest::getOrderId])) {
                $condition[] = [PaymentRefundEntity::getOrderId, '=', $requestData[PaymentRefundQueryRequest::getOrderId]];
            }
            if (isset($requestData[PaymentRefundQueryRequest::getPaymentId])) {
                $condition[] = [PaymentRefundEntity::getPaymentId, '=', $requestData[PaymentRefundQueryRequest::getPaymentId]];
            }
            if (isset($requestData[PaymentRefundQueryRequest::getStatus])) {
                $condition[] = [PaymentRefundEntity::getStatus, '=', $requestData[PaymentRefundQueryRequest::getStatus]];
            }
            if (isset($requestData[PaymentRefundQueryRequest::getId])) {
                $condition[] = [PaymentRefundEntity::getId, '=', $requestData[PaymentRefundQueryRequest::getId]];
            }
            if (isset($requestData[PaymentRefundQueryRequest::getRefundNo])) {
                $condition[] = [PaymentRefundEntity::getRefundNo, '=', $requestData[PaymentRefundQueryRequest::getRefundNo]];
            }

            $result = $this->paymentRefundService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = PaymentRefundResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = PaymentRefundQueryResponse::from($result);
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

    #[OA\Post(path: '/paymentRefund/store', summary: '新增接口', security: [['bearerAuth' => []]], tags: ['模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: PaymentRefundCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: PaymentRefundResponse::class),
        ],
    ))]
    public function store(PaymentRefundCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = PaymentRefundEntity::from($requestData);

            if ($this->paymentRefundService->save($input->toEntity())) {
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

    #[OA\Get(path: '/paymentRefund/show', summary: '获取详情接口', security: [['bearerAuth' => []]], tags: ['模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: PaymentRefundResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $paymentRefund = $this->paymentRefundService->getOneById($id);
            if (empty($paymentRefund)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = PaymentRefundResponse::from($paymentRefund);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/paymentRefund/update', summary: '更新接口', security: [['bearerAuth' => []]], tags: ['模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: PaymentRefundUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: PaymentRefundResponse::class),
        ],
    ))]
    public function update(PaymentRefundUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $paymentRefund = $this->paymentRefundService->getOneById($id);
            if (empty($paymentRefund)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = PaymentRefundEntity::from($requestData);

            $this->paymentRefundService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/paymentRefund/destroy', summary: '删除接口', security: [['bearerAuth' => []]], tags: ['模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: PaymentRefundDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: PaymentRefundDestroyResponse::class),
        ],
    ))]
    public function destroy(PaymentRefundDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->paymentRefundService->removeByIds($requestData['ids'])) {
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
