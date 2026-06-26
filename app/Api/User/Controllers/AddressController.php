<?php

declare(strict_types=1);

namespace App\Api\User\Controllers;

use App\Api\User\Requests\AddressRequest;
use App\Api\User\Responses\AddressResponse;
use App\Modules\User\Models\Address;
use App\Modules\User\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class AddressController extends BaseController
{
    #[OA\Get(path: '/addresses', summary: '收货地址列表', security: [['bearerAuth' => []]], tags: ['会员中心'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: AddressResponse::class)))]
    public function index(Request $request): JsonResponse
    {
        $user = $this->resolveUser($request);

        return response()->json([
            'code' => 0,
            'data' => $user->addresses()->orderByDesc('is_default')->orderByDesc('id')->get(),
        ]);
    }

    #[OA\Post(path: '/addresses', summary: '新增收货地址', security: [['bearerAuth' => []]], tags: ['会员中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: AddressRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: AddressResponse::class))]
    public function store(AddressRequest $request): JsonResponse
    {
        $user = $this->resolveUser($request);
        $data = $request->validated();
        $data['user_id'] = $user->id;

        if (! empty($data['is_default'])) {
            $user->addresses()->update(['is_default' => 0]);
        }

        $address = Address::create($data);

        return response()->json([
            'code' => 0,
            'message' => '添加成功',
            'data' => $address,
        ]);
    }

    #[OA\Get(path: '/addresses/{id}', summary: '收货地址详情', security: [['bearerAuth' => []]], tags: ['会员中心'])]
    #[OA\Parameter(name: 'id', description: '地址ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: AddressResponse::class))]
    public function show(Request $request, int $id): JsonResponse
    {
        $user = $this->resolveUser($request);
        $address = $user->addresses()->findOrFail($id);

        return response()->json([
            'code' => 0,
            'data' => $address,
        ]);
    }

    #[OA\Put(path: '/addresses/{id}', summary: '更新收货地址', security: [['bearerAuth' => []]], tags: ['会员中心'])]
    #[OA\Parameter(name: 'id', description: '地址ID', in: 'path', required: true)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: AddressRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: AddressResponse::class))]
    public function update(AddressRequest $request, int $id): JsonResponse
    {
        $user = $this->resolveUser($request);
        $address = $user->addresses()->findOrFail($id);
        $data = $request->validated();

        if (! empty($data['is_default'])) {
            $user->addresses()->where('id', '!=', $id)->update(['is_default' => 0]);
        }

        $address->update($data);

        return response()->json([
            'code' => 0,
            'message' => '更新成功',
            'data' => $address->fresh(),
        ]);
    }

    #[OA\Delete(path: '/addresses/{id}', summary: '删除收货地址', security: [['bearerAuth' => []]], tags: ['会员中心'])]
    #[OA\Parameter(name: 'id', description: '地址ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK')]
    public function destroy(Request $request, int $id): JsonResponse
    {
        $user = $this->resolveUser($request);
        $address = $user->addresses()->findOrFail($id);
        $address->delete();

        return response()->json([
            'code' => 0,
            'message' => '删除成功',
        ]);
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
