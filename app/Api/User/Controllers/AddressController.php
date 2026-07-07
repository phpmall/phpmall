<?php

declare(strict_types=1);

namespace App\Api\User\Controllers;

use App\Api\User\Requests\Address\AddressIndexRequest;
use App\Api\User\Requests\AddressRequest;
use App\Api\User\Responses\Address\AddressListResponse;
use App\Api\User\Responses\AddressResponse;
use App\Modules\User\Models\Address;
use App\Modules\User\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class AddressController extends BaseController
{
    #[OA\Get(path: '/addresses', summary: '收货地址列表', security: [['bearerAuth' => []]], tags: ['会员中心'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', schema: new OA\Schema(type: 'integer', default: 1))]
    #[OA\Parameter(name: 'per_page', description: '每页数量', in: 'query', schema: new OA\Schema(type: 'integer', default: 20))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: AddressListResponse::class))]
    public function index(AddressIndexRequest $request): JsonResponse
    {
        $user = $this->resolveUser($request);
        $addresses = $user->addresses()->orderByDesc('is_default')->orderByDesc('id')->get();

        $response = new AddressListResponse;
        $response->setList($addresses->toArray());
        $response->setTotal($addresses->count());

        return response()->json(['code' => 0, 'data' => $response->toArray()]);
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

    #[OA\Post(path: '/addresses/{id}/default', summary: '设置默认收货地址', security: [['bearerAuth' => []]], tags: ['会员中心'])]
    #[OA\Parameter(name: 'id', description: '地址ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: AddressResponse::class))]
    public function setDefault(Request $request, int $id): JsonResponse
    {
        $user = $this->resolveUser($request);
        $address = $user->addresses()->findOrFail($id);

        $user->addresses()->update(['is_default' => 0]);
        $address->update(['is_default' => 1]);

        return response()->json([
            'code' => 0,
            'message' => '设置成功',
            'data' => $address->fresh(),
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
