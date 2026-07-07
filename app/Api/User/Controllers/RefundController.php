<?php

declare(strict_types=1);

namespace App\Api\User\Controllers;

use App\Api\User\Requests\Refund\RefundIndexRequest;
use App\Api\User\Requests\Refund\RefundStoreRequest;
use App\Api\User\Responses\Refund\RefundListResponse;
use App\Api\User\Responses\Refund\RefundResponse;
use App\Modules\Order\Services\OrderRefundService;
use App\Modules\User\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;

class RefundController extends BaseController
{
    public function __construct(
        private readonly OrderRefundService $refundService,
    ) {
        parent::__construct();
    }

    #[OA\Get(path: '/refunds', security: [['bearerAuth' => []]], summary: '退款列表', tags: ['会员中心'])]
    #[OA\Parameter(name: 'status', in: 'query', description: '退款状态', schema: new OA\Schema(type: 'integer', nullable: true))]
    #[OA\Parameter(name: 'page', in: 'query', description: '页码', schema: new OA\Schema(type: 'integer', example: 1))]
    #[OA\Parameter(name: 'per_page', in: 'query', description: '每页数量', schema: new OA\Schema(type: 'integer', example: 20))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: RefundListResponse::class))]
    public function index(RefundIndexRequest $request): JsonResponse
    {
        $user = $this->resolveUser($request);

        $result = $this->refundService->getUserRefunds(
            $user->id,
            $request->input(RefundIndexRequest::getStatus) ? (int) $request->input(RefundIndexRequest::getStatus) : null,
            (int) $request->input(RefundIndexRequest::getPage, 1),
            (int) $request->input(RefundIndexRequest::getPerPage, 20)
        );

        $response = new RefundListResponse;
        $response->setItems($this->mapItems($result['items'] ?? []));
        $response->setPagination($result['pagination'] ?? []);

        return $this->success($response->toArray());
    }

    #[OA\Post(path: '/refunds', security: [['bearerAuth' => []]], summary: '申请退款', tags: ['会员中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: RefundStoreRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: RefundResponse::class))]
    public function store(RefundStoreRequest $request): JsonResponse
    {
        $user = $this->resolveUser($request);

        try {
            $refund = $this->refundService->apply($user->id, [
                'order_id' => (int) $request->input(RefundStoreRequest::getOrderId),
                'reason' => (string) $request->input(RefundStoreRequest::getReason),
                'type' => (string) $request->input(RefundStoreRequest::getType),
                'amount' => $request->input(RefundStoreRequest::getAmount) ? (int) $request->input(RefundStoreRequest::getAmount) : null,
                'images' => $request->input(RefundStoreRequest::getImages, []),
                'description' => $request->input(RefundStoreRequest::getDescription),
            ]);
        } catch (BusinessException $e) {
            return $this->error($e->getMessage());
        }

        return $this->success($this->toResponse($refund)->toArray());
    }

    #[OA\Get(path: '/refunds/{id}', security: [['bearerAuth' => []]], summary: '退款详情', tags: ['会员中心'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: RefundResponse::class))]
    public function show(Request $request, int $id): JsonResponse
    {
        $user = $this->resolveUser($request);

        try {
            $refund = $this->refundService->getUserRefundDetail($user->id, $id);
        } catch (BusinessException $e) {
            return $this->error($e->getMessage(), 404);
        }

        return $this->success($this->toResponse($refund)->toArray());
    }

    #[OA\Post(path: '/refunds/{id}/cancel', security: [['bearerAuth' => []]], summary: '撤销退款', tags: ['会员中心'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK')]
    public function cancel(Request $request, int $id): JsonResponse
    {
        $user = $this->resolveUser($request);

        try {
            $this->refundService->cancelByUser($user->id, $id);
        } catch (BusinessException $e) {
            return $this->error($e->getMessage());
        }

        return $this->success();
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
        $response->setOrderNo($refund['order_no'] ?? '');
        $response->setType($this->mapType((int) $refund['type']));
        $response->setAmount((int) $refund['apply_amount']);
        $response->setReason($refund['reason']);
        $response->setDescription($refund['description']);
        $response->setImages($this->decodeImages($refund['images']));
        $response->setStatus($this->mapStatus((int) $refund['status']));
        $response->setRejectReason($refund['merchant_remark']);
        $response->setCreatedAt($refund['created_at']);
        $response->setProcessedAt($this->resolveProcessedAt($refund));

        return $response;
    }

    private function mapType(int $type): string
    {
        return $type === 1 ? 'refund' : 'return_refund';
    }

    private function mapStatus(int $status): int
    {
        return match ($status) {
            0 => 0,
            1 => 1,
            2 => 2,
            3 => 3,
            4 => 3,
            5 => 4,
            6 => 2,
            7 => 6,
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

    private function resolveUser(Request $request): User
    {
        $user = $request->user();

        if (! $user instanceof User) {
            abort(401, '未登录');
        }

        return $user;
    }
}
