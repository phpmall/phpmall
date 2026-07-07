<?php

declare(strict_types=1);

namespace App\Api\Seller\Controllers;

use App\Api\Seller\Requests\SubAccount\SubAccountPermissionRequest;
use App\Api\Seller\Requests\SubAccount\SubAccountStoreRequest;
use App\Api\Seller\Requests\SubAccount\SubAccountUpdateRequest;
use App\Api\Seller\Responses\SubAccount\SubAccountListResponse;
use App\Api\Seller\Responses\SubAccount\SubAccountResponse;
use App\Modules\Merchant\Services\MerchantStaffService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class SubAccountController extends BaseController
{
    public function __construct(
        private readonly MerchantStaffService $staffService,
    ) {
        parent::__construct();
    }

    #[OA\Get(path: '/sub-accounts', summary: '获取子账号列表', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: SubAccountListResponse::class))]
    public function index(): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/sub-accounts', summary: '创建子账号', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SubAccountStoreRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function store(SubAccountStoreRequest $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/sub-accounts/{id}', summary: '获取子账号详情', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: '子账号ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: SubAccountResponse::class))]
    public function show(int $id): JsonResponse
    {
        $staff = $this->staffService->findForMerchant($id, $this->getMerchantId());

        if ($staff === null) {
            return $this->error('子账号不存在', 404);
        }

        return $this->success($this->toResponse($staff->toArray())->toArray());
    }

    #[OA\Put(path: '/sub-accounts/{id}', summary: '更新子账号', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: '子账号ID', in: 'path', required: true)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SubAccountUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: SubAccountResponse::class))]
    public function update(SubAccountUpdateRequest $request, int $id): JsonResponse
    {
        $staff = $this->staffService->updateForMerchant(
            $id,
            $this->getMerchantId(),
            $request->validated()
        );

        if ($staff === null) {
            return $this->error('子账号不存在', 404);
        }

        return $this->success($this->toResponse($staff->toArray())->toArray());
    }

    #[OA\Delete(path: '/sub-accounts/{id}', summary: '删除子账号', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: '子账号ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK')]
    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->staffService->deleteForMerchant($id, $this->getMerchantId());

        if (! $deleted) {
            return $this->error('子账号不存在', 404);
        }

        return $this->success(['message' => '删除成功']);
    }

    #[OA\Post(path: '/sub-accounts/{id}/permissions', summary: '分配子账号权限', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: '子账号ID', in: 'path', required: true)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SubAccountPermissionRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function permissions(int $id, SubAccountPermissionRequest $request): JsonResponse
    {
        $staff = $this->staffService->syncPermissions(
            $id,
            $this->getMerchantId(),
            $request->input(SubAccountPermissionRequest::getPermissionIds, [])
        );

        if ($staff === null) {
            return $this->error('子账号不存在', 404);
        }

        return $this->success([
            'message' => '权限分配成功',
            'permission_ids' => $staff->permissions->pluck('id')->toArray(),
        ]);
    }

    #[OA\Post(path: '/sub-accounts/{id}/enable', summary: '启用子账号', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: '子账号ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK')]
    public function enable(int $id): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/sub-accounts/{id}/disable', summary: '禁用子账号', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: '子账号ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK')]
    public function disable(int $id): JsonResponse
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

    private function toResponse(array $data): SubAccountResponse
    {
        $response = new SubAccountResponse;
        $response->setId($data['id']);
        $response->setUsername($data['username']);
        $response->setRealName($data['real_name'] ?? '');
        $response->setPhone($data['phone'] ?? '');
        $response->setEmail($data['email'] ?? null);
        $response->setRoleIds($data['role_ids'] ?? []);
        $response->setStatus($data['status']);
        $response->setLastLoginAt($data['last_login_at'] ?? null);
        $response->setCreatedAt($data['created_at']);

        return $response;
    }
}
