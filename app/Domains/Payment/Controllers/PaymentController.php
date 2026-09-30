<?php

declare(strict_types=1);

namespace App\Domains\Payment\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Payment\Entities\PaymentEntity;
use App\Domains\Payment\Requests\Payment\PaymentCreateRequest;
use App\Domains\Payment\Requests\Payment\PaymentDestroyRequest;
use App\Domains\Payment\Requests\Payment\PaymentQueryRequest;
use App\Domains\Payment\Requests\Payment\PaymentUpdateRequest;
use App\Domains\Payment\Responses\Payment\PaymentDestroyResponse;
use App\Domains\Payment\Responses\Payment\PaymentQueryResponse;
use App\Domains\Payment\Responses\Payment\PaymentResponse;
use App\Domains\Payment\Services\PaymentService;
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
    public function __construct(
        private readonly PaymentService $paymentService,
    ) {}

    #[OA\Post(path: '/payment/search', summary: '查询列表接口', security: [['bearerAuth' => []]], tags: ['模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: PaymentQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: PaymentQueryResponse::class),
        ],
    ))]
    public function search(PaymentQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[PaymentQueryRequest::getOrderId])) {
                $condition[] = [PaymentEntity::getOrderId, '=', $requestData[PaymentQueryRequest::getOrderId]];
            }
            if (isset($requestData[PaymentQueryRequest::getStatus])) {
                $condition[] = [PaymentEntity::getStatus, '=', $requestData[PaymentQueryRequest::getStatus]];
            }
            if (isset($requestData[PaymentQueryRequest::getTransactionId])) {
                $condition[] = [PaymentEntity::getTransactionId, '=', $requestData[PaymentQueryRequest::getTransactionId]];
            }
            if (isset($requestData[PaymentQueryRequest::getId])) {
                $condition[] = [PaymentEntity::getId, '=', $requestData[PaymentQueryRequest::getId]];
            }
            if (isset($requestData[PaymentQueryRequest::getPaymentNo])) {
                $condition[] = [PaymentEntity::getPaymentNo, '=', $requestData[PaymentQueryRequest::getPaymentNo]];
            }

            $result = $this->paymentService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = PaymentResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = PaymentQueryResponse::from($result);
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

    #[OA\Post(path: '/payment/store', summary: '新增接口', security: [['bearerAuth' => []]], tags: ['模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: PaymentCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: PaymentResponse::class),
        ],
    ))]
    public function store(PaymentCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = PaymentEntity::from($requestData);

            if ($this->paymentService->save($input->toEntity())) {
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

    #[OA\Get(path: '/payment/show', summary: '获取详情接口', security: [['bearerAuth' => []]], tags: ['模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: PaymentResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $payment = $this->paymentService->getOneById($id);
            if (empty($payment)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = PaymentResponse::from($payment);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/payment/update', summary: '更新接口', security: [['bearerAuth' => []]], tags: ['模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: PaymentUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: PaymentResponse::class),
        ],
    ))]
    public function update(PaymentUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $payment = $this->paymentService->getOneById($id);
            if (empty($payment)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = PaymentEntity::from($requestData);

            $this->paymentService->updateById($input->toEntity(), $id);

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

    #[OA\Post(path: '/payment/destroy', summary: '删除接口', security: [['bearerAuth' => []]], tags: ['模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: PaymentDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: PaymentDestroyResponse::class),
        ],
    ))]
    public function destroy(PaymentDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->paymentService->removeByIds($requestData['ids'])) {
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
