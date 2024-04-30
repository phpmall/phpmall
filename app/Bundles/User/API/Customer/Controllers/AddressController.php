<?php

declare(strict_types=1);

namespace App\Bundles\User\API\Customer\Controllers;

use App\API\Customer\Controllers\BaseController;
use App\Bundles\User\Requests\Address\AddressCreateRequest;
use App\Bundles\User\Requests\Address\AddressQueryRequest;
use App\Bundles\User\Requests\Address\AddressUpdateRequest;
use App\Bundles\User\Responses\AddressResponse;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class AddressController extends BaseController
{
    #[OA\Get(path: '/address', summary: '获取用户全部收货地址', security: [['bearerAuth' => []]], tags: ['收货地址'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: AddressQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: AddressResponse::class))]
    public function index(): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/address/store', summary: '新增用户收货地址', security: [['bearerAuth' => []]], tags: ['收货地址'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: AddressCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function store(): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/address/show', summary: '查询用户收货地址', security: [['bearerAuth' => []]], tags: ['收货地址'])]
    #[OA\Parameter(name: 'id', description: '收货地址ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: AddressResponse::class))]
    public function show(): JsonResponse
    {
        return $this->success();
    }

    #[OA\Put(path: '/address/update', summary: '更新用户收货地址', security: [['bearerAuth' => []]], tags: ['收货地址'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: AddressUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function update(): JsonResponse
    {
        return $this->success();
    }

    #[OA\Delete(path: '/address/destroy', summary: '删除用户收货地址', security: [['bearerAuth' => []]], tags: ['收货地址'])]
    #[OA\Parameter(name: 'id', description: '收货地址ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK')]
    public function destroy(): JsonResponse
    {
        return $this->success();
    }
}
