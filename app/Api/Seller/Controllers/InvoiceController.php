<?php

declare(strict_types=1);

namespace App\Api\Seller\Controllers;

use App\Api\Seller\Requests\Invoice\InvoiceIssueRequest;
use App\Api\Seller\Requests\Invoice\InvoiceRedFlushRequest;
use App\Api\Seller\Responses\Invoice\InvoiceListResponse;
use App\Api\Seller\Responses\Invoice\InvoiceResponse;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class InvoiceController extends BaseController
{
    #[OA\Get(path: '/invoices', summary: '获取发票列表', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: InvoiceListResponse::class))]
    public function index(): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/invoices/{id}', summary: '获取发票详情', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: '发票ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: InvoiceResponse::class))]
    public function show(int $id): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/invoices/{id}/issue', summary: '开具发票', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: '发票ID', in: 'path', required: true)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: InvoiceIssueRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function issue(InvoiceIssueRequest $request, int $id): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/invoices/{id}/red-flush', summary: '红冲发票', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: '发票ID', in: 'path', required: true)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: InvoiceRedFlushRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function redFlush(InvoiceRedFlushRequest $request, int $id): JsonResponse
    {
        return $this->success();
    }
}
