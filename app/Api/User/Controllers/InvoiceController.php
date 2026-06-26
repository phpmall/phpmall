<?php

declare(strict_types=1);

namespace App\Api\User\Controllers;

use App\Api\User\Requests\Invoice\InvoiceIndexRequest;
use App\Api\User\Requests\Invoice\InvoiceStoreRequest;
use App\Api\User\Responses\Invoice\InvoiceListResponse;
use App\Api\User\Responses\Invoice\InvoiceResponse;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class InvoiceController extends BaseController
{
    #[OA\Get(path: '/invoices', security: [['bearerAuth' => []]], summary: 'Invoice Controller index', tags: ['会员中心'])]
    #[OA\Parameter(name: 'type', in: 'query', description: '发票类型', schema: new OA\Schema(type: 'integer', nullable: true))]
    #[OA\Parameter(name: 'page', in: 'query', description: '页码', schema: new OA\Schema(type: 'integer', example: 1))]
    #[OA\Parameter(name: 'per_page', in: 'query', description: '每页数量', schema: new OA\Schema(type: 'integer', example: 20))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: InvoiceListResponse::class))]
    public function index(InvoiceIndexRequest $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/invoices', security: [['bearerAuth' => []]], summary: 'Invoice Controller store', tags: ['会员中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: InvoiceStoreRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: InvoiceResponse::class))]
    public function store(InvoiceStoreRequest $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/invoices/{id}', security: [['bearerAuth' => []]], summary: 'Invoice Controller show', tags: ['会员中心'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: InvoiceResponse::class))]
    public function show(int $id): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/invoices/{id}/download', security: [['bearerAuth' => []]], summary: 'Invoice Controller download', tags: ['会员中心'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK')]
    public function download(int $id): JsonResponse
    {
        return $this->success();
    }
}
