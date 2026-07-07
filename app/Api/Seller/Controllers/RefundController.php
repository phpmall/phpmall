<?php

declare(strict_types=1);

namespace App\Api\Seller\Controllers;

use App\Api\Seller\Requests\Refund\RefundArbitrateRequest;
use App\Api\Seller\Requests\Refund\RefundAuditRequest;
use App\Api\Seller\Requests\Refund\RefundIndexRequest;
use App\Api\Seller\Responses\Refund\RefundListResponse;
use App\Api\Seller\Responses\Refund\RefundResponse;
use App\Modules\Order\Services\OrderRefundService;
use Illuminate\Http\JsonResponse;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;

class RefundController extends BaseController
{
    public function __construct(
        private readonly OrderRefundService $refundService,
    ) {
        parent::__construct();
    }

    #[OA\Get(path: '/refunds', summary: '获取退款列表', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'status', description: '退款状态', in: 'query', required: false, schema: new OA\Schema(type: 'integer', nullable: true))]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: false, schema: new OA\Schema(type: 'integer', example: 1))]
    #[OA\Parameter(name: 'per_page', description: '每页数量', in: 'query', required: false, schema: new OA\Schema(type: 'integer', example: 20))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: RefundListResponse::class))]
    public function index(RefundIndexRequest $request): JsonResponse
    {
        $result = $this->refundService->getMerchantRefunds(
            $this->getMerchantId(),
            $request->input(RefundIndexRequest::getStatus) ? (int) $request->input(RefundIndexRequest::getStatus) : null,
            (int) $request->input(RefundIndexRequest::getPage, 1),
            (int) $request->input(RefundIndexRequest::getPerPage, 20)
        );

        $response = new RefundListResponse;
        $response->setItems($this->mapItems($result['items'] ?? []));
        $response->setPagination($result['pagination'] ?? []);

        return $this->success($response->toArray());
    }

    #[OA\Get(path: '/refunds/{id}', summary: '获取退款详情', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: '退款ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: RefundResponse::class))]
    public function show(int $id): JsonResponse
    {
        try {
            $refund = $this->refundService->getMerchantRefundDetail($this->getMerchantId(), $id);
        } catch (BusinessException $e) {
            return $this->error($e->getMessage(), 404);
        }

        return $this->success($this->toResponse($refund)->toArray());
    }

    #[OA\Post(path: '/refunds/{id}/audit', summary: '审核退款', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: '退款ID', in: 'path', required: true)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: RefundAuditRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function audit(RefundAuditRequest $request, int $id): JsonResponse
    {
        try {
            $this->refundService->audit(
                $this->getMerchantId(),
                $id,
                (int) $request->input(RefundAuditRequest::getStatus),
                $request->input(RefundAuditRequest::getRemark)
            );
        } catch (BusinessException $e) {
            return $this->error($e->getMessage());
        }

        return $this->success(['message' => '审核成功']);
    }

    #[OA\Post(path: '/refunds/{id}/arbitrate', summary: '仲裁退款', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: '退款ID', in: 'path', required: true)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: RefundArbitrateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function arbitrate(RefundArbitrateRequest $request, int $id): JsonResponse
    {
        return $this->success();
    }

    private function getMerchantId(): int
    {
        $payloadMerchantId = request()->attributes->get('jwt_merchant_id');
        if ($payloadMerchantId !== null) {
            return (int) $payloadMerchantId;
        }

        return $this->queryWrapper()[self::MerchantId];
    }

    /**
     * @param  array<int, array<string, mixed>>  $items
     * @return array<int, array<string, mixed>>
     */
    private function mapItems(array $items): array
    {
        $result = [];
        foreach ($items as $item) {
            $result[] = $this->toResponse($item)->toArray();
        }

        return $result;
    }

    /**
     * @param  array<string, mixed>  $refund
     */
    private function toResponse(array $refund): RefundResponse
    {
        $response = new RefundResponse;
        $response->setId((int) $refund['id']);
        $response->setRefundNo($refund['refund_no']);
        $response->setOrderId((int) $refund['order_id']);
        $response->setStatus($this->mapStatus((int) $refund['status']));
        $response->setRefundAmount((int) $refund['apply_amount']);
        $response->setReason($refund['reason']);
        $response->setDescription($refund['description']);
        $response->setEvidenceImages($this->decodeImages($refund['images']));
        $response->setCreatedAt($refund['created_at']);
        $response->setProcessedAt($this->resolveProcessedAt($refund));

        return $response;
    }

    private function mapStatus(int $status): int
    {
        return match ($status) {
            0 => 0,
            1 => 1,
            2 => 2,
            3 => 1,
            4 => 3,
            5 => 4,
            6 => 2,
            7 => 4,
            default => $status,
        };
    }

    /**
     * @return array<int, string>
     */
    private function decodeImages(mixed $images): array
    {
        if (is_string($images)) {
            $decoded = json_decode($images, true);

            return is_array($decoded) ? $decoded : [];
        }

        return is_array($images) ? $images : [];
    }

    /**
     * @param  array<string, mixed>  $refund
     */
    private function resolveProcessedAt(array $refund): ?string
    {
        if (! empty($refund['refund_time'])) {
            return (string) $refund['refund_time'];
        }

        if ((int) $refund['status'] !== 0 && ! empty($refund['updated_at'])) {
            return (string) $refund['updated_at'];
        }

        return null;
    }
}
