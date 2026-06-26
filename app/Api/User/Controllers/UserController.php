<?php

declare(strict_types=1);

namespace App\Api\User\Controllers;

use App\Api\User\Requests\UpdateProfileRequest;
use App\Api\User\Requests\User\UserProfileRequest;
use App\Api\User\Responses\AddressResponse;
use App\Api\User\Responses\UserProfileResponse;
use App\Modules\User\Models\User;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class UserController extends BaseController
{
    #[OA\Get(path: '/me', summary: '获取会员资料', security: [['bearerAuth' => []]], tags: ['会员中心'])]
    #[OA\Parameter(name: 'with_addresses', in: 'query', description: '是否包含地址列表', schema: new OA\Schema(type: 'integer', enum: [0, 1], nullable: true))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserProfileResponse::class))]
    public function profile(UserProfileRequest $request): JsonResponse
    {
        $user = $this->resolveUser($request);

        if ((int) $request->input(UserProfileRequest::getWithAddresses) === 1) {
            $user->load('addresses');
        }

        $userArray = $user->toArray();
        if (isset($userArray['addresses'])) {
            $userArray['addresses'] = array_map(
                fn (array $address) => AddressResponse::from($address),
                $userArray['addresses']
            );
        }

        return response()->json([
            'code' => 0,
            'data' => UserProfileResponse::from($userArray),
        ]);
    }

    #[OA\Put(path: '/me', summary: '更新会员资料', security: [['bearerAuth' => []]], tags: ['会员中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UpdateProfileRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserProfileResponse::class))]
    public function updateProfile(UpdateProfileRequest $request): JsonResponse
    {
        $user = $this->resolveUser($request);
        $user->update($request->validated());

        return response()->json([
            'code' => 0,
            'message' => '更新成功',
            'data' => UserProfileResponse::from($user->fresh()->toArray()),
        ]);
    }

    private function resolveUser(UserProfileRequest|UpdateProfileRequest $request): User
    {
        $user = $request->user();

        if (! $user instanceof User) {
            abort(401, '未登录');
        }

        return $user;
    }
}
