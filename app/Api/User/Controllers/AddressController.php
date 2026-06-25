<?php

namespace App\Api\User\Controllers;

use App\Api\User\Requests\AddressRequest;
use App\Modules\User\Models\Address;
use App\Modules\User\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AddressController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $this->resolveUser($request);

        return response()->json([
            'code' => 0,
            'data' => $user->addresses()->orderByDesc('is_default')->orderByDesc('id')->get(),
        ]);
    }

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

    public function show(Request $request, int $id): JsonResponse
    {
        $user = $this->resolveUser($request);
        $address = $user->addresses()->findOrFail($id);

        return response()->json([
            'code' => 0,
            'data' => $address,
        ]);
    }

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
